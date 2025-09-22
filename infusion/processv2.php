<?php
/**
 * Keap/Infusionsoft Integration for Open Inclusion Registration
 * Handles all form data submission to Keap for the multi-step registration process
 */

// Include the Keap SDK and configuration
require_once(__DIR__ . '/xmlrpc-4.10.3/src/Client.php');
require_once(__DIR__ . '/conn.cfg.php');
require_once(__DIR__ . '/keap-config.php');

class KeapIntegration {
    private $app;
    private $key;
    private $client;
    private $debugMode = true;
    
    public function __construct() {
        global $connectionInfo;
        $this->app = $connectionInfo['connectionName'];
        $this->key = $connectionInfo['apiKey'];
        
        // Initialize XML-RPC client
        $this->client = new PhpXmlRpc\Client('https://' . $this->app . '.infusionsoft.com/api/xmlrpc');
        $this->client->setDebug(0);
    }
    
    /**
     * Log debug information
     */
    private function log($message, $data = null) {
        if ($this->debugMode && defined('KEAP_DEBUG_MODE') && KEAP_DEBUG_MODE) {
            $prefix = defined('KEAP_LOG_PREFIX') ? KEAP_LOG_PREFIX : 'Keap Integration: ';
            error_log($prefix . $message);
            if ($data) {
                error_log($prefix . "Data: " . print_r($data, true));
            }
        }
    }
    
    /**
     * Create or update contact in Keap
     */
    public function createOrUpdateContact($contactData) {
        try {
            $this->log("Creating/updating contact", $contactData);
            
            // Check if contact exists by email
            $contactId = $this->findContactByEmail($contactData['Email']);
            
            if ($contactId) {
                // Update existing contact
                $this->log("Updating existing contact ID: " . $contactId);
                $result = $this->updateContact($contactId, $contactData);
                return $contactId;
            } else {
                // Create new contact
                $this->log("Creating new contact");
                $contactId = $this->createContact($contactData);
                return $contactId;
            }
        } catch (Exception $e) {
            $this->log("Error in createOrUpdateContact: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Find contact by email
     */
    public function findContactByEmail($email) {
        try {
            $query = array('Email' => $email);
            $returnFields = array('Id');
            
            $request = new PhpXmlRpc\Request(
                'DataService.query',
                array(
                    new PhpXmlRpc\Value($this->key, 'string'),
                    new PhpXmlRpc\Value('Contact', 'string'),
                    new PhpXmlRpc\Value(1000, 'int'),
                    new PhpXmlRpc\Value(0, 'int'),
                    new PhpXmlRpc\Value($query, 'struct'),
                    new PhpXmlRpc\Value($returnFields, 'array')
                )
            );
            
            $response = $this->client->send($request);
            
            if ($response->faultCode() == 0) {
                $contacts = $response->value();
                if ($contacts->arraySize() > 0) {
                    $contact = $contacts->arrayMem(0);
                    return $contact->structMem('Id')->scalarVal();
                }
            } else {
                $this->log("Error finding contact: " . $response->faultString());
            }
            
            return false;
        } catch (Exception $e) {
            $this->log("Error in findContactByEmail: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Create new contact
     */
    private function createContact($contactData) {
        try {
            $request = new PhpXmlRpc\Request(
                'ContactService.add',
                array(
                    new PhpXmlRpc\Value($this->key, 'string'),
                    new PhpXmlRpc\Value($contactData, 'struct')
                )
            );
            
            $response = $this->client->send($request);
            
            if ($response->faultCode() == 0) {
                $contactId = $response->value()->scalarVal();
                $this->log("Created contact with ID: " . $contactId);
                return $contactId;
            } else {
                $this->log("Error creating contact: " . $response->faultString());
                return false;
            }
        } catch (Exception $e) {
            $this->log("Error in createContact: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Update existing contact
     */
    public function updateContact($contactId, $contactData) {
        try {
            $request = new PhpXmlRpc\Request(
                'ContactService.update',
                array(
                    new PhpXmlRpc\Value($this->key, 'string'),
                    new PhpXmlRpc\Value($contactId, 'int'),
                    new PhpXmlRpc\Value($contactData, 'struct')
                )
            );
            
            $response = $this->client->send($request);
            
            if ($response->faultCode() == 0) {
                $this->log("Updated contact ID: " . $contactId);
                return true;
            } else {
                $this->log("Error updating contact: " . $response->faultString());
                return false;
            }
        } catch (Exception $e) {
            $this->log("Error in updateContact: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Get contact tags
     */
    public function getContactTags($contactId) {
        try {
            $request = new PhpXmlRpc\Request(
                'ContactService.load',
                array(
                    new PhpXmlRpc\Value($this->key, 'string'),
                    new PhpXmlRpc\Value($contactId, 'int'),
                    new PhpXmlRpc\Value(array('Groups'), 'array')
                )
            );
            
            $response = $this->client->send($request);
            
            if ($response->faultCode() == 0) {
                $contact = $response->value();
                $groups = $contact->structMem('Groups');
                $tagIds = array();
                
                if ($groups && $groups->kindOf() == 'array') {
                    for ($i = 0; $i < $groups->arraySize(); $i++) {
                        $tagIds[] = $groups->arrayMem($i)->scalarVal();
                    }
                }
                
                return $tagIds;
            } else {
                $this->log("Error getting contact tags: " . $response->faultString());
                return array();
            }
        } catch (Exception $e) {
            $this->log("Error in getContactTags: " . $e->getMessage());
            return array();
        }
    }
    
    /**
     * Add tags to contact
     */
    public function addTagsToContact($contactId, $tags) {
        try {
            foreach ($tags as $tagId) {
                $request = new PhpXmlRpc\Request(
                    'ContactService.addToGroup',
                    array(
                        new PhpXmlRpc\Value($this->key, 'string'),
                        new PhpXmlRpc\Value($contactId, 'int'),
                        new PhpXmlRpc\Value($tagId, 'int')
                    )
                );
                
                $response = $this->client->send($request);
                if ($response->faultCode() != 0) {
                    $this->log("Error adding tag {$tagId} to contact {$contactId}: " . $response->faultString());
                }
            }
            return true;
        } catch (Exception $e) {
            $this->log("Error in addTagsToContact: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Process initial registration data
     */
    public function processInitialRegistration($userData) {
        try {
            $this->log("Processing initial registration");
            
            // Prepare contact data for Keap
            $contactData = array(
                'FirstName' => $userData['inf_field_FirstName'],
                'LastName' => $userData['inf_field_LastName'],
                'Email' => $userData['inf_field_Email'],
                'Phone1' => $userData['inf_field_Phone2'],
                'Phone1Type' => 'Main'
            );
            
            // Add custom fields if they exist
            if (isset($userData['inf_field_countryphonecode'])) {
                $contactData['_CountryPhoneCode'] = $userData['inf_field_countryphonecode'];
            }
            
            if (isset($userData['PreferToContact']) && is_array($userData['PreferToContact'])) {
                $contactData['_PreferredContactMethods'] = implode('|', $userData['PreferToContact']);
            }
            
            $contactId = $this->createOrUpdateContact($contactData);
            
            if ($contactId) {
                // Add initial registration tag
                $tagId = getKeapTagId('initial_registration');
                if ($tagId) {
                    $this->addTagsToContact($contactId, array($tagId));
                }
                $this->log("Successfully processed initial registration for contact ID: " . $contactId);
            }
            
            return $contactId;
            
        } catch (Exception $e) {
            $this->log("Error in processInitialRegistration: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Process Part 2 Step 1 data
     */
    public function processPart2Step1($userMetaData, $contactId = null) {
        try {
            $this->log("Processing Part 2 Step 1 data");
            
            if (!$contactId) {
                $contactId = $this->findContactByEmail($userMetaData['Email']);
            }
            
            if (!$contactId) {
                $this->log("Contact not found for Part 2 Step 1");
                return false;
            }
            
            $updateData = array();
            
            // Map the user meta data to Keap fields
            if (isset($userMetaData['Country'])) {
                $updateData['_Country'] = $userMetaData['Country'];
            }
            if (isset($userMetaData['Region'])) {
                $updateData['_Region'] = $userMetaData['Region'];
            }
            if (isset($userMetaData['Year_Born'])) {
                $updateData['_YearBorn'] = $userMetaData['Year_Born'];
            }
            if (isset($userMetaData['Has Disability'])) {
                $updateData['_HasDisability'] = $userMetaData['Has Disability'];
            }
            if (isset($userMetaData['Relationship to Disability'])) {
                $updateData['_RelationshipToDisability'] = $userMetaData['Relationship to Disability'];
            }
            
            $result = $this->updateContact($contactId, $updateData);
            
            if ($result) {
                // Add Step 1 completion tag (user can now proceed to Step 2)
                $tagId = getKeapTagId('step1_completed');
                if ($tagId) {
                    $this->addTagsToContact($contactId, array($tagId));
                }
            }
            
            return $result;
            
        } catch (Exception $e) {
            $this->log("Error in processPart2Step1: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Process Part 2 Step 2 data (Access Needs)
     */
    public function processPart2Step2($userMetaData, $contactId = null) {
        try {
            $this->log("Processing Part 2 Step 2 data");
            
            if (!$contactId) {
                $contactId = $this->findContactByEmail($userMetaData['Email']);
            }
            
            if (!$contactId) {
                $this->log("Contact not found for Part 2 Step 2");
                return false;
            }
            
            $updateData = array();
            
            // Access needs fields
            if (isset($userMetaData['Sensory Needs'])) {
                $updateData['_SensoryNeeds'] = $userMetaData['Sensory Needs'];
            }
            if (isset($userMetaData['Physical Needs'])) {
                $updateData['_PhysicalNeeds'] = $userMetaData['Physical Needs'];
            }
            if (isset($userMetaData['Cognitive And Mental health Needs'])) {
                $updateData['_CognitiveAndMentalHealthNeeds'] = $userMetaData['Cognitive And Mental health Needs'];
            }
            if (isset($userMetaData['Communication Needs'])) {
                $updateData['_CommunicationNeeds'] = $userMetaData['Communication Needs'];
            }
            if (isset($userMetaData['Chronic health Needs'])) {
                $updateData['_ChronicHealthNeeds'] = $userMetaData['Chronic health Needs'];
            }
            if (isset($userMetaData['Other Needs'])) {
                $updateData['_OtherNeeds'] = $userMetaData['Other Needs'];
            }
            
            $result = $this->updateContact($contactId, $updateData);
            
            if ($result) {
                // Add Step 2 completion tag (user can now proceed to Step 3)
                $tagId = getKeapTagId('step2_completed');
                if ($tagId) {
                    $this->addTagsToContact($contactId, array($tagId));
                }
            }
            
            return $result;
            
        } catch (Exception $e) {
            $this->log("Error in processPart2Step2: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Process Part 2 Step 3 data (Assistive Technologies)
     */
    public function processPart2Step3($userMetaData, $contactId = null) {
        try {
            $this->log("Processing Part 2 Step 3 data");
            
            if (!$contactId) {
                $contactId = $this->findContactByEmail($userMetaData['Email']);
            }
            
            if (!$contactId) {
                $this->log("Contact not found for Part 2 Step 3");
                return false;
            }
            
            $updateData = array();
            
            // Assistive technology fields
            if (isset($userMetaData['Digital and Screen Technologies'])) {
                $updateData['_DigitalScreenTechnologies'] = $userMetaData['Digital and Screen Technologies'];
            }
            if (isset($userMetaData['PrintMedia'])) {
                $updateData['_PrintMedia'] = $userMetaData['PrintMedia'];
            }
            if (isset($userMetaData['Movement Canes and Service Animals'])) {
                $updateData['_MovementCanesServiceAnimals'] = $userMetaData['Movement Canes and Service Animals'];
            }
            if (isset($userMetaData['Communication Preferences'])) {
                $updateData['_CommunicationPreferences'] = $userMetaData['Communication Preferences'];
            }
            if (isset($userMetaData['PersonalSupportandHome'])) {
                $updateData['_PersonalSupportAndHome'] = $userMetaData['PersonalSupportandHome'];
            }
            if (isset($userMetaData['Other Technologies'])) {
                $updateData['_OtherTechnologies'] = $userMetaData['Other Technologies'];
            }
            if (isset($userMetaData['Research Formats'])) {
                $updateData['_ResearchFormats'] = $userMetaData['Research Formats'];
            }
            if (isset($userMetaData['inf_field_referred'])) {
                $updateData['_Referred'] = $userMetaData['inf_field_referred'];
            }
            if (isset($userMetaData['inf_field_referred_name'])) {
                $updateData['_ReferrerName'] = $userMetaData['inf_field_referred_name'];
            }
            
            $result = $this->updateContact($contactId, $updateData);
            
            if ($result) {
                // Add Step 3 completion tag (user can now proceed to Step 4)
                $tagId = getKeapTagId('step3_completed');
                if ($tagId) {
                    $this->addTagsToContact($contactId, array($tagId));
                }
            }
            
            return $result;
            
        } catch (Exception $e) {
            $this->log("Error in processPart2Step3: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Process Part 2 Step 4 data (Personal Characteristics)
     */
    public function processPart2Step4($userMetaData, $contactId = null) {
        try {
            $this->log("Processing Part 2 Step 4 data");
            
            if (!$contactId) {
                $contactId = $this->findContactByEmail($userMetaData['Email']);
            }
            
            if (!$contactId) {
                $this->log("Contact not found for Part 2 Step 4");
                return false;
            }
            
            $updateData = array();
            
            // Personal characteristics fields
            if (isset($userMetaData['Gender'])) {
                $updateData['_Gender'] = $userMetaData['Gender'];
            }
            if (isset($userMetaData['inf_field_gender_at_birth_diff'])) {
                $updateData['_GenderAtBirthDifferent'] = $userMetaData['inf_field_gender_at_birth_diff'];
            }
            if (isset($userMetaData['SexualOrientations'])) {
                $updateData['_SexualOrientations'] = $userMetaData['SexualOrientations'];
            }
            if (isset($userMetaData['inf_option_pronouns'])) {
                $updateData['_PreferredPronouns'] = $userMetaData['inf_option_pronouns'];
            }
            if (isset($userMetaData['inf_field_identify_terms'])) {
                $updateData['_EthnicIdentity'] = $userMetaData['inf_field_identify_terms'];
            }
            
            $result = $this->updateContact($contactId, $updateData);
            
            if ($result) {
                // Add Step 4 completion tag (user can now proceed to Step 5)
                $tagId = getKeapTagId('step4_completed');
                if ($tagId) {
                    $this->addTagsToContact($contactId, array($tagId));
                }
            }
            
            return $result;
            
        } catch (Exception $e) {
            $this->log("Error in processPart2Step4: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Process Part 2 Step 5 data (Community Agreement)
     */
    public function processPart2Step5($userMetaData, $contactId = null) {
        try {
            $this->log("Processing Part 2 Step 5 data");
            
            if (!$contactId) {
                $contactId = $this->findContactByEmail($userMetaData['Email']);
            }
            
            if (!$contactId) {
                $this->log("Contact not found for Part 2 Step 5");
                return false;
            }
            
            $updateData = array();
            
            if (isset($userMetaData['Community Agreement'])) {
                $updateData['_CommunityAgreement'] = $userMetaData['Community Agreement'];
                $updateData['_CommunityAgreementDate'] = date('Y-m-d H:i:s');
            }
            
            $result = $this->updateContact($contactId, $updateData);
            
            if ($result) {
                // Add Step 5 completion tag (user can now proceed to Step 6)
                $tagId = getKeapTagId('step5_completed');
                if ($tagId) {
                    $this->addTagsToContact($contactId, array($tagId));
                }
                // Also add community agreement tag for tracking
                $agreementTagId = getKeapTagId('community_agreement');
                if ($agreementTagId) {
                    $this->addTagsToContact($contactId, array($agreementTagId));
                }
            }
            
            return $result;
            
        } catch (Exception $e) {
            $this->log("Error in processPart2Step5: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Process Part 2 Step 6 data (Privacy Confirmation)
     */
    public function processPart2Step6($userMetaData, $contactId = null) {
        try {
            $this->log("Processing Part 2 Step 6 data");
            
            if (!$contactId) {
                $contactId = $this->findContactByEmail($userMetaData['Email']);
            }
            
            if (!$contactId) {
                $this->log("Contact not found for Part 2 Step 6");
                return false;
            }
            
            $updateData = array();
            
            if (isset($userMetaData['Consent'])) {
                $updateData['_PrivacyConsent'] = $userMetaData['Consent'];
                $updateData['_PrivacyConsentDate'] = date('Y-m-d H:i:s');
            }
            
            $result = $this->updateContact($contactId, $updateData);
            
            if ($result) {
                // Add Step 6 completion tag (user can now proceed to Step 7)
                $tagId = getKeapTagId('step6_completed');
                if ($tagId) {
                    $this->addTagsToContact($contactId, array($tagId));
                }
                // Also add privacy consent tag for tracking
                $consentTagId = getKeapTagId('privacy_consent');
                if ($consentTagId) {
                    $this->addTagsToContact($contactId, array($consentTagId));
                }
            }
            
            return $result;
            
        } catch (Exception $e) {
            $this->log("Error in processPart2Step6: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Process Part 2 Step 7 data (Identity Verification)
     */
    public function processPart2Step7($userMetaData, $contactId = null) {
        try {
            $this->log("Processing Part 2 Step 7 data");
            
            if (!$contactId) {
                $contactId = $this->findContactByEmail($userMetaData['Email']);
            }
            
            if (!$contactId) {
                $this->log("Contact not found for Part 2 Step 7");
                return false;
            }
            
            $updateData = array();
            
            if (isset($userMetaData['Open Verified Opt In'])) {
                $updateData['_OpenVerifiedOptIn'] = $userMetaData['Open Verified Opt In'];
                if ($userMetaData['Open Verified Opt In'] === 'yes') {
                    $updateData['_OpenVerifiedOptInDate'] = date('Y-m-d H:i:s');
                }
            }
            
            $result = $this->updateContact($contactId, $updateData);
            
            if ($result) {
                // Add Step 7 completion tag (user can now proceed to Step 8)
                $tagId = getKeapTagId('step7_completed');
                if ($tagId) {
                    $this->addTagsToContact($contactId, array($tagId));
                }
                // Add verification opt-in tag if they opted in
                if (isset($userMetaData['Open Verified Opt In']) && $userMetaData['Open Verified Opt In'] === 'yes') {
                    $optinTagId = getKeapTagId('open_verified_optin');
                    if ($optinTagId) {
                        $this->addTagsToContact($contactId, array($optinTagId));
                    }
                }
            }
            
            return $result;
            
        } catch (Exception $e) {
            $this->log("Error in processPart2Step7: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Process Part 2 Step 8 data (Community Login Creation)
     */
    public function processPart2Step8($userMetaData, $contactId = null) {
        try {
            $this->log("Processing Part 2 Step 8 data");
            
            if (!$contactId) {
                $contactId = $this->findContactByEmail($userMetaData['Email']);
            }
            
            if (!$contactId) {
                $this->log("Contact not found for Part 2 Step 8");
                return false;
            }
            
            $updateData = array();
            
            if (isset($userMetaData['inf_field_UserName'])) {
                $updateData['_CommunityUsername'] = $userMetaData['inf_field_UserName'];
                $updateData['_CommunityAccountCreated'] = date('Y-m-d H:i:s');
            }
            
            $result = $this->updateContact($contactId, $updateData);
            
            if ($result) {
                // Add Step 8 completion tag (user can now proceed to Step 9)
                $tagId = getKeapTagId('step8_completed');
                if ($tagId) {
                    $this->addTagsToContact($contactId, array($tagId));
                }
                // Also add community account created tag for tracking
                $accountTagId = getKeapTagId('community_account_created');
                if ($accountTagId) {
                    $this->addTagsToContact($contactId, array($accountTagId));
                }
            }
            
            return $result;
            
        } catch (Exception $e) {
            $this->log("Error in processPart2Step8: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Process Part 2 completion (Step 9)
     */
    public function processPart2Completion($contactId = null, $userEmail = null) {
        try {
            $this->log("Processing Part 2 completion");
            
            if (!$contactId && $userEmail) {
                $contactId = $this->findContactByEmail($userEmail);
            }
            
            if (!$contactId) {
                $this->log("Contact not found for Part 2 completion");
                return false;
            }
            
            $updateData = array(
                '_RegistrationCompleted' => 'Yes',
                '_RegistrationCompletedDate' => date('Y-m-d H:i:s'),
                '_RegistrationStatus' => 'Complete'
            );
            
            $result = $this->updateContact($contactId, $updateData);
            
            if ($result) {
                // Add registration completed tag
                $tagId = getKeapTagId('registration_completed');
                if ($tagId) {
                    $this->addTagsToContact($contactId, array($tagId));
                }
            }
            
            return $result;
            
        } catch (Exception $e) {
            $this->log("Error in processPart2Completion: " . $e->getMessage());
            return false;
        }
    }
}

// For backwards compatibility and direct usage
if (isset($_POST) && !empty($_POST)) {
    try {
        $keapIntegration = new KeapIntegration();
        $contactId = $keapIntegration->processInitialRegistration($_POST);
        
        if ($contactId) {
            error_log("Successfully processed initial registration. Contact ID: " . $contactId);
        } else {
            error_log("Failed to process initial registration");
        }
    } catch (Exception $e) {
        error_log("Error in processv2.php: " . $e->getMessage());
    }
}
?>