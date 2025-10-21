<?php

//header('Access-Control-Allow-Origin: *');
//require_once '../../vendor/autoload.php';
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
$properties_ini = parse_ini_file("myproperties.ini");
error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING & ~E_DEPRECATED & ~E_STRICT);
require_once("isdk.php");
require("conn.cfg.php");
$app = new isdk();

/**
 * Updates Keap contact data for multi-step registration
 * @param string $step - The current step (step1, step2, step3, step4, step5)
 * @param array $fieldData - The form data from the current step
 * @param string $userEmail - The user's email address
 */
function updateKeapMultiStepData($step, $fieldData, $userEmail) {
    global $app, $properties_ini;
    
    error_log("=== KEAP FUNCTION CALLED: updateKeapMultiStepData ===");
    error_log("Step: " . $step);
    error_log("Email: " . $userEmail);
    error_log("Field data keys: " . implode(', ', array_keys($fieldData)));
    error_log("App object exists: " . (isset($app) ? 'YES' : 'NO'));
    error_log("App object type: " . (isset($app) ? get_class($app) : 'N/A'));
    error_log("Connection info loaded: " . (isset($connInfo) ? 'YES' : 'NO'));
    
    // Check if app object exists and connection is established
    if (!$app || !is_object($app) || !method_exists($app, 'cfgCon')) {
        error_log("Keap app object not properly initialized for step: " . $step);
        return false;
    }
    
    error_log("Attempting Keap connection...");
    $connectionResult = $app->cfgCon("connectionName");
    error_log("Keap connection result: " . ($connectionResult ? 'SUCCESS' : 'FAILED'));
    
    if (!$connectionResult) {
        error_log("Keap connection failed for step: " . $step);
        return false;
    }
    
    // Validate user email
    if (empty($userEmail) || !filter_var($userEmail, FILTER_VALIDATE_EMAIL)) {
        error_log("Invalid or empty user email for step: " . $step);
        return false;
    }
    
    // Check for existing contact
    $returnFields = ['Id', 'FirstName', 'LastName', 'Email'];
    error_log("Looking for contact with email: " . $userEmail);
    $conDat = $app->findByEmail($userEmail, $returnFields);
    error_log("Contact lookup result: " . print_r($conDat, true));
    
    if (empty($conDat)) {
        error_log("Contact not found for email: " . $userEmail . " in step: " . $step);
        error_log("This might be because the contact doesn't exist in Keap yet. The contact should be created during Part 1 registration.");
        
        // Try to create a new contact if it doesn't exist
        error_log("Attempting to create new contact for email: " . $userEmail);
        $newContactData = [
            'Email' => $userEmail,
            'FirstName' => 'Unknown',
            'LastName' => 'User'
        ];
        $newContactId = $app->addCon($newContactData);
        if ($newContactId) {
            error_log("New contact created with ID: " . $newContactId);
            $contactId = $newContactId;
        } else {
            error_log("Failed to create new contact");
            return false;
        }
    } else {
        $contactId = $conDat[0]['Id'];
        error_log("Found existing contact with ID: " . $contactId);
    }
    $contactData = [];
    
    // Prepare data based on step
    switch ($step) {
        case 'step1':
            error_log("Preparing Step 1 data...");
            $contactData = prepareStep1Data($fieldData);
            error_log("Step 1 contact data prepared: " . print_r($contactData, true));
            break;
        case 'step2':
            $contactData = prepareStep2Data($fieldData);
            break;
        case 'step3':
            $contactData = prepareStep3Data($fieldData);
            break;
        case 'step4':
            $contactData = prepareStep4Data($fieldData);
            break;
        case 'step5':
            $contactData = prepareStep5Data($fieldData);
            break;
        default:
            error_log("Unknown step: " . $step);
            return false;
    }
    
    // Update contact with step data
    if (!empty($contactData)) {
        error_log("Updating Keap contact ID: " . $contactId . " with data: " . print_r($contactData, true));
        $result = $app->updateCon($contactId, $contactData);
        error_log("Keap updateCon result: " . print_r($result, true));
        
        // Assign step completion tag
        $stepTag = isset($properties_ini['step_' . $step . '_completed']) ? 
                   $properties_ini['step_' . $step . '_completed'] : null;
        
        if ($stepTag) {
            $tagResult = $app->grpAssign($contactId, $stepTag);
            error_log("Step completion tag assignment result: " . print_r($tagResult, true));
        }
        
        // Update registration status
        $statusUpdate = ['_RegistrationStatus' => 'In Progress - Step ' . substr($step, -1)];
        $statusResult = $app->updateCon($contactId, $statusUpdate);
        error_log("Registration status update result: " . print_r($statusResult, true));
        
        error_log("Keap data updated for step: " . $step . " - Contact ID: " . $contactId);
        return true;
    } else {
        error_log("No contact data to update for step: " . $step);
    }
    
    return false;
}

/**
 * Prepare data for Step 1 (Basic Information)
 */
function prepareStep1Data($fieldData) {
    $contactData = [];
    
    // Part 2 Step 1 fields - Basic location and demographics
    if (isset($fieldData['inf_field_country'])) {
        $contactData['Country'] = $fieldData['inf_field_country'];
    }
    if (isset($fieldData['inf_field_region'])) {
        $contactData['_Region'] = $fieldData['inf_field_region'];
    }
    if (isset($fieldData['inf_field_postcode'])) {
        $contactData['PostalCode'] = $fieldData['inf_field_postcode'];
    }
    if (isset($fieldData['inf_field_over18'])) {
        $contactData['_Over18'] = $fieldData['inf_field_over18'];
    }
    if (isset($fieldData['inf_custom_YearBorn'])) {
        $contactData['_YearBorn'] = $fieldData['inf_custom_YearBorn'];
    }
    if (isset($fieldData['inf_field_hasDisability'])) {
        $contactData['_HasDisability'] = $fieldData['inf_field_hasDisability'];
    }
    if (isset($fieldData['RelationShip']) && is_array($fieldData['RelationShip'])) {
        $contactData['_RelationShip'] = implode('|', $fieldData['RelationShip']);
    }
    
    return $contactData;
}

/**
 * Prepare data for Step 2 (Relationship & Demographics)
 */
function prepareStep2Data($fieldData) {
    $contactData = [];
    
    // Age and relationship information
    if (isset($fieldData['inf_field_over18'])) {
        $contactData['_Over18'] = $fieldData['inf_field_over18'];
    }
    if (isset($fieldData['inf_custom_YearBorn'])) {
        $contactData['_YearBorn'] = $fieldData['inf_custom_YearBorn'];
    }
    if (isset($fieldData['inf_option_Gender'])) {
        $contactData['_Gender'] = $fieldData['inf_option_Gender'];
    }
    if (isset($fieldData['inf_option_pronouns'])) {
        $contactData['_Pronouns'] = $fieldData['inf_option_pronouns'];
    }
    if (isset($fieldData['inf_option_ethnicity'])) {
        $contactData['_Ethnicity'] = $fieldData['inf_option_ethnicity'];
    }
    if (isset($fieldData['SexualOrientations'])) {
        $contactData['_SexualOrientation'] = is_array($fieldData['SexualOrientations']) ? 
                                           implode('|', $fieldData['SexualOrientations']) : 
                                           $fieldData['SexualOrientations'];
    }
    
    // Relationship to disability
    if (isset($fieldData['RelationShip']) && is_array($fieldData['RelationShip'])) {
        $contactData['_RelationshipToDisability'] = implode('|', $fieldData['RelationShip']);
    }
    
    // How they found the community
    if (isset($fieldData['OurCommunity'])) {
        $contactData['_HowFoundCommunity'] = $fieldData['OurCommunity'];
    }
    
    return $contactData;
}

/**
 * Prepare data for Step 3 (Access Needs)
 */
function prepareStep3Data($fieldData) {
    $contactData = [];
    
    // Sensory needs
    if (isset($fieldData['SensoryNeeds']) && is_array($fieldData['SensoryNeeds'])) {
        $contactData['_SensoryNeeds'] = implode('|', $fieldData['SensoryNeeds']);
    }
    
    // Physical needs
    if (isset($fieldData['PhysicalNeeds']) && is_array($fieldData['PhysicalNeeds'])) {
        $contactData['_PhysicalNeeds'] = implode('|', $fieldData['PhysicalNeeds']);
    }
    
    // Cognitive and mental health needs
    if (isset($fieldData['CognitiveAndMentalhealthNeeds']) && is_array($fieldData['CognitiveAndMentalhealthNeeds'])) {
        $contactData['_CognitiveMentalHealthNeeds'] = implode('|', $fieldData['CognitiveAndMentalhealthNeeds']);
    }
    
    // Communication needs
    if (isset($fieldData['CommunicationNeeds']) && is_array($fieldData['CommunicationNeeds'])) {
        $contactData['_CommunicationNeeds'] = implode('|', $fieldData['CommunicationNeeds']);
    }
    
    // Chronic health needs
    if (isset($fieldData['ChronichealthNeeds']) && is_array($fieldData['ChronichealthNeeds'])) {
        $contactData['_ChronicHealthNeeds'] = implode('|', $fieldData['ChronichealthNeeds']);
    }
    
    // Other needs
    if (isset($fieldData['OtherNeedsOtherPleaseSpecify'])) {
        $contactData['_OtherNeeds'] = $fieldData['OtherNeedsOtherPleaseSpecify'];
    }
    
    // Temporary access needs
    if (isset($fieldData['TemporaryAccessNeedsYes'])) {
        $contactData['_TemporaryAccessNeeds'] = 'Yes';
    } elseif (isset($fieldData['TemporaryAccessNeedsNo'])) {
        $contactData['_TemporaryAccessNeeds'] = 'No';
    } elseif (isset($fieldData['TemporaryAccessNeedsNA'])) {
        $contactData['_TemporaryAccessNeeds'] = 'Not Applicable';
    }
    
    return $contactData;
}

/**
 * Prepare data for Step 4 (Technologies & Support)
 */
function prepareStep4Data($fieldData) {
    $contactData = [];
    
    // Digital and screen technologies
    if (isset($fieldData['DigitalandScreenTechnologies']) && is_array($fieldData['DigitalandScreenTechnologies'])) {
        $contactData['_DigitalScreenTechnologies'] = implode('|', $fieldData['DigitalandScreenTechnologies']);
    }
    
    // Print media preferences
    if (isset($fieldData['PrintMedia']) && is_array($fieldData['PrintMedia'])) {
        $contactData['_PrintMediaPreferences'] = implode('|', $fieldData['PrintMedia']);
    }
    
    // Movement, canes and service animals
    if (isset($fieldData['MovementCanesandServiceAnimals']) && is_array($fieldData['MovementCanesandServiceAnimals'])) {
        $contactData['_MovementCanesServiceAnimals'] = implode('|', $fieldData['MovementCanesandServiceAnimals']);
    }
    
    // Communication preferences
    if (isset($fieldData['CommunicationPreferences']) && is_array($fieldData['CommunicationPreferences'])) {
        $contactData['_CommunicationPreferences'] = implode('|', $fieldData['CommunicationPreferences']);
    }
    
    // Personal support and home
    if (isset($fieldData['PersonalSupportandHome']) && is_array($fieldData['PersonalSupportandHome'])) {
        $contactData['_PersonalSupportHome'] = implode('|', $fieldData['PersonalSupportandHome']);
    }
    
    // Other technologies
    if (isset($fieldData['OtherTechnologiesOtherPleaseSpecify'])) {
        $contactData['_OtherTechnologies'] = $fieldData['OtherTechnologiesOtherPleaseSpecify'];
    }
    
    // Research format preferences
    if (isset($fieldData['ResearchFormats']) && is_array($fieldData['ResearchFormats'])) {
        $contactData['_ResearchFormatPreferences'] = implode('|', $fieldData['ResearchFormats']);
    }
    
    return $contactData;
}

/**
 * Prepare data for Step 5 (Community Agreement)
 */
function prepareStep5Data($fieldData) {
    $contactData = [];
    
    // Community agreement
    if (isset($fieldData['CommunityAgreement']) && is_array($fieldData['CommunityAgreement'])) {
        $contactData['_CommunityAgreementAccepted'] = 'Yes';
        $contactData['_CommunityAgreementDate'] = date('Y-m-d H:i:s');
    }
    
    // Mark registration as complete
    $contactData['_RegistrationStatus'] = 'Complete';
    $contactData['_RegistrationCompletedDate'] = date('Y-m-d H:i:s');
    
    return $contactData;
}

/**
 * Assign tags based on step data
 */
function assignStepTags($contactId, $step, $fieldData) {
    global $app, $properties_ini;
    
    if (!isset($properties_ini)) {
        return;
    }
    
    // Assign step completion tag
    $stepTag = isset($properties_ini['step_' . $step . '_completed']) ? 
               $properties_ini['step_' . $step . '_completed'] : null;
    
    if ($stepTag) {
        $app->grpAssign($contactId, $stepTag);
    }
    
    // Assign specific tags based on step data
    switch ($step) {
        case 'step1':
            // Country and region tags
            if (isset($fieldData['inf_field_country']) && isset($properties_ini[$fieldData['inf_field_country']])) {
                $app->grpAssign($contactId, $properties_ini[$fieldData['inf_field_country']]);
            }
            if (isset($fieldData['inf_field_region']) && isset($properties_ini[$fieldData['inf_field_region']])) {
                $app->grpAssign($contactId, $properties_ini[$fieldData['inf_field_region']]);
            }
            break;
            
        case 'step2':
            // Gender and demographic tags
            if (isset($fieldData['inf_option_Gender']) && isset($properties_ini[$fieldData['inf_option_Gender']])) {
                $app->grpAssign($contactId, $properties_ini[$fieldData['inf_option_Gender']]);
            }
            break;
            
        case 'step3':
            // Access needs tags
            if (isset($fieldData['SensoryNeeds']) && is_array($fieldData['SensoryNeeds'])) {
                foreach ($fieldData['SensoryNeeds'] as $need) {
                    if (isset($properties_ini[$need])) {
                        $app->grpAssign($contactId, $properties_ini[$need]);
                    }
                }
            }
            if (isset($fieldData['PhysicalNeeds']) && is_array($fieldData['PhysicalNeeds'])) {
                foreach ($fieldData['PhysicalNeeds'] as $need) {
                    if (isset($properties_ini[$need])) {
                        $app->grpAssign($contactId, $properties_ini[$need]);
                    }
                }
            }
            break;
            
        case 'step5':
            // Final registration tags
            if (isset($properties_ini['member_tag'])) {
                $app->grpAssign($contactId, $properties_ini['member_tag']);
            }
            if (isset($properties_ini['registration_complete'])) {
                $app->grpAssign($contactId, $properties_ini['registration_complete']);
            }
            break;
    }
}

?>
