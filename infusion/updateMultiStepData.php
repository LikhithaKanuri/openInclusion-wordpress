<?php

//header('Access-Control-Allow-Origin: *');
//require_once '../../vendor/autoload.php';
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
$properties_ini = parse_ini_file("myproperties.ini");
// $current_dir = dirname(__FILE__);
// $properties_ini = parse_ini_file($current_dir . "/myproperties.ini");
error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING & ~E_DEPRECATED & ~E_STRICT);
require("isdk.php");
require_once("isdk.php");
require("conn.cfg.php");
// Check if files exist before including
// if (file_exists($current_dir . "/isdk.php")) {
//     require_once($current_dir . "/isdk.php");
// } else {
//     error_log("isdk.php not found in: " . $current_dir);
// }

// if (file_exists($current_dir . "/conn.cfg.php")) {
//     require($current_dir . "/conn.cfg.php");
// } else {
//     error_log("conn.cfg.php not found in: " . $current_dir);
// }

$app = new isdk();

/**
 * Updates Keap contact data for multi-step registration
 * @param string $step - The current step (step1, step2, step3, step4, step5)
 * @param array $fieldData - The form data from the current step
 * @param string $userEmail - The user's email address
 */
// function updateKeapMultiStepData($step, $fieldData, $userEmail) {
//     global $app, $properties_ini;
//        try {
//         if (!$app || !is_object($app)) {
//             $app = new isdk();
//         }
        
//         // Ensure properties_ini is loaded
//         if (!isset($properties_ini) || empty($properties_ini)) {
//             $current_dir = dirname(__FILE__);
//             $properties_ini = parse_ini_file($current_dir . "/myproperties.ini");
//         }
        
//         if (!$properties_ini) {
//             error_log("Failed to load properties_ini in updateKeapMultiStepData");
//             return false;
//         }
    
//     error_log("Attempting Keap connection...");
//     $connectionResult = $app->cfgCon("connectionName");
//     error_log("Keap connection result: " . ($connectionResult ? 'SUCCESS' : 'FAILED'));
    
//     if (!$connectionResult) {
//         error_log("Keap connection failed for step: " . $step);
//         return false;
//     }
    
//     // Validate user email
//     if (empty($userEmail) || !filter_var($userEmail, FILTER_VALIDATE_EMAIL)) {
//         error_log("Invalid or empty user email for step: " . $step);
//         return false;
//     }
    
//     // Check for existing contact
//    //  $returnFields = ['Id'];
//    $returnFields = ['Id', 'FirstName', 'LastName', 'Email'];

//     error_log("Looking for contact with email: " . $userEmail);
//     $conDat = $app->findByEmail($userEmail, $returnFields);
//         error_log("Contact lookup result: " . print_r($conDat, true));

//     // if (empty($conDat)) {
//         if (!is_array($conDat) || empty($conDat)) {
//         error_log("Contact not found for email: " . $userEmail . " in step: " . $step);
//         error_log("This might be because the contact doesn't exist in Keap yet. The contact should be created during Part 1 registration.");

    
//    //  $contactId = $conDat[0]['Id'];
//            // Try to create a new contact if it doesn't exist
//         error_log("Attempting to create new contact for email: " . $userEmail);
//         $newContactData = [
//             'Email' => $userEmail,
//             'FirstName' => 'Unknown',
//             'LastName' => 'User'
//         ];
//         $newContactId = $app->addCon($newContactData);
//         if ($newContactId) {
//             error_log("New contact created with ID: " . $newContactId);
//             $contactId = $newContactId;
//         } else {
//             error_log("Failed to create new contact");
//             return false;
//         }
//     } else {
//         // $contactId = $conDat[0]['Id'];
//         // error_log("Found existing contact with ID: " . $contactId);
//                 if (is_array($conDat) && isset($conDat[0]) && is_array($conDat[0]) && isset($conDat[0]['Id'])) {
//             $contactId = $conDat[0]['Id'];
//             error_log("Found existing contact with ID: " . $contactId);
//         } else {
//             error_log("Invalid contact data structure: " . print_r($conDat, true));
//             return false;
//         }
//     }
//     $contactData = [];
    
//     // Prepare data based on step
//     switch ($step) {
//         case 'step1':
//             $contactData = prepareStep1Data($fieldData);
//             break;
//         case 'step2':
//             $contactData = prepareStep2Data($fieldData);
//             break;
//         case 'step3':
//             $contactData = prepareStep3Data($fieldData);
//             break;
//         case 'step4':
//             $contactData = prepareStep4Data($fieldData);
//             break;
//         case 'step5':
//             $contactData = prepareStep5Data($fieldData);
//             break;
//         case 'step6':
//             $contactData = prepareStep6Data($fieldData);
//             break;
//         case 'step7':
//             $contactData = prepareStep7Data($fieldData);
//             break;
//         case 'step8':
//             $contactData = prepareStep8Data($fieldData);
//             break;
//         default:
//             error_log("Unknown step: " . $step);
//             return false;
//     }
    
//     // Update contact with step data
//     if (!empty($contactData)) {
//         error_log("Updating Keap contact ID: " . $contactId . " with data: " . print_r($contactData, true));
//         $result = $app->updateCon($contactId, $contactData);
//         error_log("Keap updateCon result: " . print_r($result, true));
        
//         // Assign step completion tag
//         $stepTag = isset($properties_ini['step_' . $step . '_completed']) ? 
//                    $properties_ini['step_' . $step . '_completed'] : null;
        
//         if ($stepTag) {
//             $tagResult = $app->grpAssign($contactId, $stepTag);
//             error_log("Step completion tag assignment result: " . print_r($tagResult, true));
//         }

//         // Assign phase-specific tags based on step
//         $phaseTag = getPhaseTagForStep($step, $properties_ini);
//         if ($phaseTag) {
//             $phaseTagResult = $app->grpAssign($contactId, $phaseTag);
//             error_log("Phase tag assignment result: " . print_r($phaseTagResult, true));
//         }
        
//         // Update registration status
//         $statusUpdate = ['_RegistrationStatus' => 'In Progress - Step ' . substr($step, -1)];
//         $statusResult = $app->updateCon($contactId, $statusUpdate);
//         error_log("Registration status update result: " . print_r($statusResult, true));
        
//         error_log("Keap data updated for step: " . $step . " - Contact ID: " . $contactId);
//         return true;
//     } else {
//         error_log("No contact data to update for step: " . $step);
//     }
    
//     return false;
// }

function updateKeapMultiStepData($step, $fieldData, $userEmail) {
    global $app, $properties_ini;
    
    try {
        // Initialize Keap app if not already done
        if (!$app || !is_object($app)) {
            $app = new isdk();
        }
        
        // Ensure properties_ini is loaded
        if (!isset($properties_ini) || empty($properties_ini)) {
            $current_dir = dirname(__FILE__);
            $properties_ini = parse_ini_file($current_dir . "/myproperties.ini");
        }
        
        if (!$properties_ini) {
            error_log("Failed to load properties_ini in updateKeapMultiStepData");
            return false;
        }
    
    error_log("updateKeapMultiStepData called for step: " . $step . " with email: " . $userEmail);
    
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
   //  $returnFields = ['Id'];
   $returnFields = ['Id', 'FirstName', 'LastName', 'Email'];

    error_log("Looking for contact with email: " . $userEmail);
    $conDat = $app->findByEmail($userEmail, $returnFields);
        error_log("Contact lookup result: " . print_r($conDat, true));

    // Check if $conDat is an array and not empty
    if (!is_array($conDat) || empty($conDat)) {
        error_log("Contact not found for email: " . $userEmail . " in step: " . $step);
        error_log("This might be because the contact doesn't exist in Keap yet. The contact should be created during Part 1 registration.");
   //      return false;
   //  }
    
   //  $contactId = $conDat[0]['Id'];
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
        // Additional safety check to ensure $conDat is an array and has the expected structure
        if (is_array($conDat) && isset($conDat[0]) && is_array($conDat[0]) && isset($conDat[0]['Id'])) {
            $contactId = $conDat[0]['Id'];
            error_log("Found existing contact with ID: " . $contactId);
        } else {
            error_log("Invalid contact data structure: " . print_r($conDat, true));
            return false;
        }
    }
    $contactData = [];
    
    // Prepare data based on step
    switch ($step) {
        case 'step1':
            $contactData = prepareStep1Data($fieldData);
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
        case 'step6':
            $contactData = prepareStep6Data($fieldData);
            break;
        case 'step7':
            $contactData = prepareStep7Data($fieldData);
            break;
        case 'step8':
            $contactData = prepareStep8Data($fieldData);
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
        assignStepTags($contactId, $step, $fieldData);

                
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
    } catch (Exception $e) {
        error_log("Exception in updateKeapMultiStepData: " . $e->getMessage());
        error_log("Stack trace: " . $e->getTraceAsString());
        return false;
    }
}

/**
 * Helper function to clean array data and join with pipe separator
 * @param array $array - The array to clean and join
 * @return string - Cleaned and joined string
 */
function cleanAndJoinArray($array) {
    if (!is_array($array)) {
        return '';
    }
    
    // Log the original array for debugging
    error_log("cleanAndJoinArray - Original array: " . print_r($array, true));
    
    // Filter out empty values and clean the array
    $cleanArray = array_filter($array, function($value) {
        return !empty($value) && trim($value) !== '';
    });
    
    $result = !empty($cleanArray) ? implode(", ", $cleanArray) : '';
    error_log("cleanAndJoinArray - Cleaned result: " . $result);
    
    return $result;
}

/**
 * Prepare data for Step 1 (Basic Information)
 */
function prepareStep1Data($fieldData) {
    $contactData = []; 
    // Part 2 Step 1 fields - Basic location and demographics
    if (isset($fieldData['inf_field_country'])) {
        $contactData['_Country1'] = $fieldData['inf_field_country'];
    }
    if (isset($fieldData['inf_field_region'])) {
        $contactData['_Region'] = $fieldData['inf_field_region'];
    }
    if (isset($fieldData['inf_field_postcode'])) {
        $contactData['_Postcode'] = $fieldData['inf_field_postcode'];
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
    if (isset($fieldData['RelationShip'])) {
        $contactData['_RelationShip'] = cleanAndJoinArray($fieldData['RelationShip']);
    }
    
    return $contactData;
}

/**
 * Prepare data for Step 2 (Relationship & Demographics)
 */
function prepareStep2Data($fieldData) {
    $contactData = [];
    
    // // Age and relationship information
    // if (isset($fieldData['inf_field_over18'])) {
    //     $contactData['_Over18'] = $fieldData['inf_field_over18'];
    // }
    // if (isset($fieldData['inf_custom_YearBorn'])) {
    //     $contactData['_YearBorn'] = $fieldData['inf_custom_YearBorn'];
    // }
    // if (isset($fieldData['inf_option_Gender'])) {
    //     $contactData['_Gender'] = $fieldData['inf_option_Gender'];
    // }
    // if (isset($fieldData['inf_option_pronouns'])) {
    //     $contactData['_Pronouns'] = $fieldData['inf_option_pronouns'];
    // }
    // if (isset($fieldData['inf_option_ethnicity'])) {
    //     $contactData['_Ethnicity'] = $fieldData['inf_option_ethnicity'];
    // }
    // if (isset($fieldData['SexualOrientations'])) {
    //     $contactData['_SexualOrientation'] = cleanAndJoinArray($fieldData['SexualOrientations']);
    // }
    
    // // Relationship to disability
    // if (isset($fieldData['RelationShip'])) {
    //     $contactData['_RelationshipToDisability'] = cleanAndJoinArray($fieldData['RelationShip']);
    // }
    
    // // How they found the community
    // if (isset($fieldData['OurCommunity'])) {
    //     $contactData['_HowFoundCommunity'] = $fieldData['OurCommunity'];
    // }
    
    // return $contactData;
     // Sensory needs
    if (isset($fieldData['SensoryNeeds'])) {
        $contactData['_SensoryNeed'] = cleanAndJoinArray($fieldData['SensoryNeeds']);
    }
    
    // Physical needs
    if (isset($fieldData['PhysicalNeeds'])) {
        $contactData['_PhysicalNeed'] = cleanAndJoinArray($fieldData['PhysicalNeeds']);
    }
    
    // Cognitive and mental health needs
    if (isset($fieldData['CognitiveAndMentalhealthNeeds'])) {
        $contactData['_cognitiveandmentalhealthneed'] = cleanAndJoinArray($fieldData['CognitiveAndMentalhealthNeeds']);
    }
    
    // Communication needs
    if (isset($fieldData['CommunicationNeeds'])) {
        $contactData['_communicationneed'] = cleanAndJoinArray($fieldData['CommunicationNeeds']);
    }
    
    // Chronic health needs
    if (isset($fieldData['ChronichealthNeeds'])) {
        $contactData['_chronichealthneed'] = cleanAndJoinArray($fieldData['ChronichealthNeeds']);
    }
    
    // Other needs
    if (isset($fieldData['OtherNeedsOtherPleaseSpecify'])) {
        $contactData['_OtherNeed'] = $fieldData['OtherNeedsOtherPleaseSpecify'];
    }
    
    // Temporary access needs
    if (isset($fieldData['TemporaryAccessNeedsYes'])) {
        $contactData['_TemporaryAccessNeed'] = 'Yes';
    } elseif (isset($fieldData['TemporaryAccessNeedsNo'])) {
        $contactData['_TemporaryAccessNeed'] = 'No';
    } elseif (isset($fieldData['TemporaryAccessNeedsNA'])) {
        $contactData['_TemporaryAccessNeed'] = 'Not Applicable';
    }
    
    return $contactData;
}

/**
 * Prepare data for Step 3 (Access Needs)
 */
function prepareStep3Data($fieldData) {
    $contactData = [];
    
    // Digital: including software and hardware
    if (isset($fieldData['DigitalandScreenTechnologies'])) {
        $contactData['_Digitalincludingsoftwareandhardware'] = cleanAndJoinArray($fieldData['DigitalandScreenTechnologies']);
    }
    
    // Physical needs
    if (isset($fieldData['PrintMedia'])) {
        $contactData['_Printedmedia'] = cleanAndJoinArray($fieldData['PrintMedia']);
    }
    
    // Cognitive and mental health needs
    if (isset($fieldData['MovementCanesandServiceAnimals'])) {
        $contactData['_Movement'] = cleanAndJoinArray($fieldData['MovementCanesandServiceAnimals']);
    }
    
    // Communication needs
    if (isset($fieldData['CommunicationPreferences'])) {
        $contactData['_Communication'] = cleanAndJoinArray($fieldData['CommunicationPreferences']);
    }
    
    // Chronic health needs
    if (isset($fieldData['PersonalSupportandHome'])) {
        $contactData['_Personalsupportandhome'] = cleanAndJoinArray($fieldData['PersonalSupportandHome']);
    }


    // Research Formats
    if (isset($fieldData['ResearchFormats'])) {
        $contactData['_ResearchFormats'] = cleanAndJoinArray($fieldData['ResearchFormats']);
    }
    
    // Other needs
    if (isset($fieldData['OtherTechnologies'])) {
        $contactData['_OtherTechnology'] = $fieldData['OtherTechnologies'];
    }


    
    // // Temporary access needs
    // if (isset($fieldData['TemporaryAccessNeedsYes'])) {
    //     $contactData['_TemporaryAccessNeed'] = 'Yes';
    // } elseif (isset($fieldData['TemporaryAccessNeedsNo'])) {
    //     $contactData['_TemporaryAccessNeed'] = 'No';
    // } elseif (isset($fieldData['TemporaryAccessNeedsNA'])) {
    //     $contactData['_TemporaryAccessNeed'] = 'Not Applicable';
    // }
    
    return $contactData;
}

/**
 * Prepare data for Step 4 (Technologies & Support)
 */
function prepareStep4Data($fieldData) {
    $contactData = [];
    
    // if (isset($fieldData['inf_option_Gender'])) {
    //     $contactData['_Gender'] = $fieldData['inf_option_Gender'];
    // }
         if (isset($fieldData['inf_option_Gender'])) {
         // Map numeric values to text values for gender
         $genderMap = array(
             '507' => 'Woman',
             '505' => 'Man', 
             '782' => 'Non-binary/non-conforming',
             '776' => 'Other (please self-describe)',
             '774' => 'Prefer not to respond'
         );
         $genderValue = isset($genderMap[$fieldData['inf_option_Gender']]) ? 
                        $genderMap[$fieldData['inf_option_Gender']] : 
                        $fieldData['inf_option_Gender'];
         $contactData['_Gender'] = $genderValue;
     }
     
     if (isset($fieldData['SexualOrientations'])) {
         $contactData['_SexualOrientation'] = cleanAndJoinArray($fieldData['SexualOrientations']);
     }
    
    if (isset($fieldData['inf_option_pronouns'])) {
        $contactData['_Pronouns'] = $fieldData['inf_option_pronouns'];
    }
    
    // Communication preferences
    if (isset($fieldData['inf_field_identify_terms'])) {
        $contactData['_RacialandEthnicIdentity'] = $fieldData['inf_field_identify_terms'];
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
    // $stepTag = isset($properties_ini['step_' . $step . '_completed']) ? 
    //            $properties_ini['step_' . $step . '_completed'] : null;
    
    // if ($stepTag) {
    //     $app->grpAssign($contactId, $stepTag);
    // }
    
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

/**
 * Prepare data for Step 6 (Confirmations)
 */
function prepareStep6Data($fieldData) {
    $contactData = [];
    
    // Confirmations
    if (isset($fieldData['PleaseConfirm'])) {
        $contactData['_Confirmations'] = cleanAndJoinArray($fieldData['PleaseConfirm']);
    }
    
    return $contactData;
}

/**
 * Prepare data for Step 7 (Open Verified Opt-in)
 */
function prepareStep7Data($fieldData) {
    $contactData = [];
    
    // Open Verified opt-in
    if (isset($fieldData['OpenVerifiedOptIn'])) {
        $contactData['_OpenVerifiedOptIn'] = 'Yes';
    } else {
        $contactData['_OpenVerifiedOptIn'] = 'No';
    }
    
    return $contactData;
}

/**
 * Prepare data for Step 8 (Community Login)
 */
function prepareStep8Data($fieldData) {
    $contactData = [];
    
    // Username
    if (isset($fieldData['inf_field_UserName'])) {
        $contactData['_CommunityUsername'] = $fieldData['inf_field_UserName'];
    }
    
    // Password (we don't store the actual password, just that it was set)
    if (isset($fieldData['inf_field_Password']) && !empty($fieldData['inf_field_Password'])) {
        $contactData['_PasswordSet'] = 'Yes';
    }
    
    return $contactData;
}

?>
