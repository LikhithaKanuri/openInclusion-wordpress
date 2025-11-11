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
function storeStepDataInSession($step, $fieldData) {
    if (!isset($_SESSION['registration_data'])) {
        $_SESSION['registration_data'] = array();
    }
    $_SESSION['registration_data'][$step] = $fieldData;
}

function getStepDataFromSession($step) {
    if (isset($_SESSION['registration_data']) && isset($_SESSION['registration_data'][$step])) {
        return $_SESSION['registration_data'][$step];
    }
    return array();
}
// function updateKeapMultiStepData($step, $fieldData, $userEmail) {
function updateKeapMultiStepData($step, $fieldData, $userEmail, $updateMode = false) {
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
        
        // Update registration status
        $statusUpdate = ['_RegistrationStatus' => 'In Progress - Step ' . substr($step, -1)];
        $statusResult = $app->updateCon($contactId, $statusUpdate);
        error_log("Registration status update result: " . print_r($statusResult, true));
        
        error_log("Keap data updated for step: " . $step . " - Contact ID: " . $contactId);
    } else {
        error_log("No contact data to update for step: " . $step . " - Field data received: " . print_r($fieldData, true));
    }
    
    // Always assign step completion tag regardless of whether we have contact data
    // assignStepTags($contactId, $step, $fieldData);
        // In update mode, get current contact data and remove old tags before assigning new ones
    if ($updateMode) {
        // Get current contact data to compare with new data
        $returnFields = ['Id'];
        $currentContact = $app->loadCon($contactId, $returnFields);
        
        // Get all current tags for this contact to identify which tags to remove
        $currentTags = $app->loadCon($contactId, ['Groups']);
        
        // Remove old tags and assign new ones based on changes
        updateStepTags($contactId, $step, $fieldData, $currentContact);
    } else {
        // Normal mode: just assign tags
        assignStepTags($contactId, $step, $fieldData);
    }
    
    return true;
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
 * Replace "Other" style option values with the user's supplied open-text input
 * so that Keap receives the descriptive value rather than the placeholder.
 *
 * @param array $selectedValues The raw option values submitted for a field
 * @param array $fieldData      The full submitted payload (used to look up open-text fields)
 * @param array $valueMap       Map of option value => open-text field name
 * @return array                Normalised selection values with open-text overrides applied
 */
function normalizeSelectionsWithOpenText($selectedValues, $fieldData, $valueMap) {
    if (!is_array($selectedValues) || empty($valueMap)) {
        return $selectedValues;
    }

    $normalised = array();
    foreach ($selectedValues as $value) {
        if (isset($valueMap[$value])) {
            $openFieldName = $valueMap[$value];
            if (!empty($fieldData[$openFieldName])) {
                $normalised[] = trim($fieldData[$openFieldName]);
                continue;
            }
        }
        $normalised[] = $value;
    }

    return $normalised;
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
        $relationshipValues = $fieldData['RelationShip'];
        $relationshipValues = normalizeSelectionsWithOpenText($relationshipValues, $fieldData, array(
            'OtherPleaseSpecify' => 'RelationShipOtherPleaseSpecify_OpenText',
        ));
        $contactData['_RelationShip'] = cleanAndJoinArray($relationshipValues);
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
        $sensoryValues = normalizeSelectionsWithOpenText($fieldData['SensoryNeeds'], $fieldData, array(
            'OtherPleaseSpecify' => 'SensoryNeedsOtherPleaseSpecify_OpenText',
        ));
        $contactData['_SensoryNeed'] = cleanAndJoinArray($sensoryValues);
    }
    // Other sensory need text
    if (isset($fieldData['SensoryNeedsOtherPleaseSpecify_OpenText']) && !empty($fieldData['SensoryNeedsOtherPleaseSpecify_OpenText'])) {
        $contactData['_othersensoryneed'] = $fieldData['SensoryNeedsOtherPleaseSpecify_OpenText'];
    }
    
    // Physical needs
    if (isset($fieldData['PhysicalNeeds'])) {
        $physicalValues = normalizeSelectionsWithOpenText($fieldData['PhysicalNeeds'], $fieldData, array(
            'OtherPleaseSpecify' => 'PhysicalNeedsOtherPleaseSpecify_OpenText',
        ));
        $contactData['_PhysicalNeed'] = cleanAndJoinArray($physicalValues);
    }
    // Other physical need text
    if (isset($fieldData['PhysicalNeedsOtherPleaseSpecify_OpenText']) && !empty($fieldData['PhysicalNeedsOtherPleaseSpecify_OpenText'])) {
        $contactData['_otherphysicalneed'] = $fieldData['PhysicalNeedsOtherPleaseSpecify_OpenText'];
    }
    
    // Cognitive and mental health needs
    if (isset($fieldData['CognitiveAndMentalhealthNeeds'])) {
        $cognitiveValues = normalizeSelectionsWithOpenText($fieldData['CognitiveAndMentalhealthNeeds'], $fieldData, array(
            'OtherMentalHealth' => 'CognitiveAndMentalhealthNeedsOtherMentalHealth_OpenText',
        ));
        $contactData['_cognitiveandmentalhealthneed'] = cleanAndJoinArray($cognitiveValues);
    }
    // Other mental health conditions text
    if (isset($fieldData['CognitiveAndMentalhealthNeedsOtherMentalHealth_OpenText']) && !empty($fieldData['CognitiveAndMentalhealthNeedsOtherMentalHealth_OpenText'])) {
        $contactData['_othermentalhealthconditions'] = $fieldData['CognitiveAndMentalhealthNeedsOtherMentalHealth_OpenText'];
    }
    
    // Communication needs
    if (isset($fieldData['CommunicationNeeds'])) {
        $communicationValues = normalizeSelectionsWithOpenText($fieldData['CommunicationNeeds'], $fieldData, array(
            'OtherPleaseSpecify' => 'CommunicationNeedsOtherPleaseSpecify_OpenText',
        ));
        $contactData['_communicationneed'] = cleanAndJoinArray($communicationValues);
    }
    // Other communication challenges text
    if (isset($fieldData['CommunicationNeedsOtherPleaseSpecify_OpenText']) && !empty($fieldData['CommunicationNeedsOtherPleaseSpecify_OpenText'])) {
        $contactData['_othercommunicationchallenges'] = $fieldData['CommunicationNeedsOtherPleaseSpecify_OpenText'];
    }
    
    // Chronic health needs
    if (isset($fieldData['ChronichealthNeeds'])) {
        $chronicValues = normalizeSelectionsWithOpenText($fieldData['ChronichealthNeeds'], $fieldData, array(
            'OtherLongTermCondition' => 'ChronichealthNeedsOtherLongTermCondition_OpenText',
        ));
        $contactData['_chronichealthneed'] = cleanAndJoinArray($chronicValues);
    }
    // Other long term condition text
    if (isset($fieldData['ChronichealthNeedsOtherLongTermCondition_OpenText']) && !empty($fieldData['ChronichealthNeedsOtherLongTermCondition_OpenText'])) {
        $contactData['_otherlongtermcondition'] = $fieldData['ChronichealthNeedsOtherLongTermCondition_OpenText'];
    }
    
    // Other needs
    if (isset($fieldData['OtherNeeds'])) {
        $otherNeedValues = normalizeSelectionsWithOpenText($fieldData['OtherNeeds'], $fieldData, array(
            'OtherPleaseSpecify' => 'OtherNeedsOtherPleaseSpecify_OpenText',
        ));
        $contactData['_OtherNeed'] = cleanAndJoinArray($otherNeedValues);
    }
    // Other access need text
    if (isset($fieldData['OtherNeedsOtherPleaseSpecify_OpenText']) && !empty($fieldData['OtherNeedsOtherPleaseSpecify_OpenText'])) {
        $contactData['_otheraccessneed'] = $fieldData['OtherNeedsOtherPleaseSpecify_OpenText'];
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
        $digitalValues = normalizeSelectionsWithOpenText($fieldData['DigitalandScreenTechnologies'], $fieldData, array(
            'OtherPleaseSpecify' => 'DigitalandScreenTechnologiesOtherPleaseSpecify_OpenText',
        ));
        $contactData['_Digitalincludingsoftwareandhardware'] = cleanAndJoinArray($digitalValues);
    }
    // Specific software names
    if (isset($fieldData['DigitalandScreenTechnologiesSpecificSoftware']) && !empty($fieldData['DigitalandScreenTechnologiesSpecificSoftware'])) {
        $contactData['_DigitalScreenTechnologiesSpecificSoftware'] = $fieldData['DigitalandScreenTechnologiesSpecificSoftware'];
    }
    // Other digital assistive technology text
    if (isset($fieldData['DigitalandScreenTechnologiesOtherPleaseSpecify_OpenText']) && !empty($fieldData['DigitalandScreenTechnologiesOtherPleaseSpecify_OpenText'])) {
        $contactData['_Otherdigitalassistivetechnology'] = $fieldData['DigitalandScreenTechnologiesOtherPleaseSpecify_OpenText'];
    }

    // Specific software open-text responses for key digital technologies
    $digitalSpecificFieldMap = array(
        '_otherscreenreader'       => 'DigitalandScreenTechnologiesSpecificSoftware_DigitalandScreenTechnologies_ScreenReader',
        '_otherscreenmagnifier'    => 'DigitalandScreenTechnologiesSpecificSoftware_DigitalandScreenTechnologies_ScreenMagnifier',
        '_otherreadaloudsoftware'  => 'DigitalandScreenTechnologiesSpecificSoftware_DigitalandScreenTechnologies_ReadAloudSoftware',
        '_otherspeechrecognition'  => 'DigitalandScreenTechnologiesSpecificSoftware_DigitalandScreenTechnologies_Dragonandother',
    );

    foreach ($digitalSpecificFieldMap as $customFieldName => $requestFieldName) {
        if (!empty($fieldData[$requestFieldName])) {
            $contactData[$customFieldName] = trim($fieldData[$requestFieldName]);
        }
    }
    
    // Printed media
    if (isset($fieldData['PrintMedia'])) {
        $printValues = normalizeSelectionsWithOpenText($fieldData['PrintMedia'], $fieldData, array(
            'OtherPleaseSpecify' => 'PrintMediaOtherPleaseSpecify_OpenText',
        ));
        $contactData['_Printedmedia'] = cleanAndJoinArray($printValues);
    }
    // Other printed media aid or adaptation text
    if (isset($fieldData['PrintMediaOtherPleaseSpecify_OpenText']) && !empty($fieldData['PrintMediaOtherPleaseSpecify_OpenText'])) {
        $contactData['_Otherprintedmediaaidoradaptation'] = $fieldData['PrintMediaOtherPleaseSpecify_OpenText'];
    }
    
    // Movement: canes and service animals
    if (isset($fieldData['MovementCanesandServiceAnimals'])) {
        $movementValues = normalizeSelectionsWithOpenText($fieldData['MovementCanesandServiceAnimals'], $fieldData, array(
            'OtherNavigationalMobilityAid' => 'MovementCanesandServiceAnimalsOtherNavigationalMobilityAid_OpenText',
        ));
        $contactData['_Movement'] = cleanAndJoinArray($movementValues);
    }
    // Other navigational or mobility aid text
    if (isset($fieldData['MovementCanesandServiceAnimalsOtherNavigationalMobilityAid_OpenText']) && !empty($fieldData['MovementCanesandServiceAnimalsOtherNavigationalMobilityAid_OpenText'])) {
        $contactData['_Othernavigationalormobilityaid'] = $fieldData['MovementCanesandServiceAnimalsOtherNavigationalMobilityAid_OpenText'];
    }
    
    // Communication preferences
    if (isset($fieldData['CommunicationPreferences'])) {
        $communicationPrefValues = normalizeSelectionsWithOpenText($fieldData['CommunicationPreferences'], $fieldData, array(
            'OtherPleaseSpecify' => 'CommunicationPreferencesOtherPleaseSpecify_OpenText',
        ));
        $contactData['_Communication'] = cleanAndJoinArray($communicationPrefValues);
    }
    // Other communication aids or alternatives text
    if (isset($fieldData['CommunicationPreferencesOtherPleaseSpecify_OpenText']) && !empty($fieldData['CommunicationPreferencesOtherPleaseSpecify_OpenText'])) {
        $contactData['_Othercommunicationaidsoralternatives'] = $fieldData['CommunicationPreferencesOtherPleaseSpecify_OpenText'];
    }
    
    // Personal support and home
    if (isset($fieldData['PersonalSupportandHome'])) {
        $contactData['_Personalsupportandhome'] = cleanAndJoinArray($fieldData['PersonalSupportandHome']);
    }

    // Other technologies
    if (isset($fieldData['OtherTechnologies'])) {
        $otherTechValues = normalizeSelectionsWithOpenText($fieldData['OtherTechnologies'], $fieldData, array(
            'OtherPleaseSpecify' => 'OtherTechnologiesOtherPleaseSpecify_OpenText',
        ));
        $contactData['_OtherTechnology'] = cleanAndJoinArray($otherTechValues);
    }
    // Other. Please describe text
    if (isset($fieldData['OtherTechnologiesOtherPleaseSpecify_OpenText']) && !empty($fieldData['OtherTechnologiesOtherPleaseSpecify_OpenText'])) {
        $contactData['_otherdescribe'] = $fieldData['OtherTechnologiesOtherPleaseSpecify_OpenText'];
    }

    // Research Formats
    if (isset($fieldData['ResearchFormats'])) {
        $contactData['_ResearchFormats'] = cleanAndJoinArray($fieldData['ResearchFormats']);
    }
    
    // Referred by
    if (isset($fieldData['inf_field_referred'])) {
        $contactData['_Referred'] = $fieldData['inf_field_referred'];
    }
    if (isset($fieldData['inf_field_referred_name']) && !empty($fieldData['inf_field_referred_name'])) {
        $contactData['_referredby'] = $fieldData['inf_field_referred_name'];
    }
    
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
        $genderSelection = $fieldData['inf_option_Gender'];
        $genderValue = isset($genderMap[$genderSelection]) ? $genderMap[$genderSelection] : $genderSelection;

        if (!empty($fieldData['inf_option_Gender_opentext'])) {
            $genderValue = trim($fieldData['inf_option_Gender_opentext']);
        } elseif (!empty($fieldData['inf_option_Gender_776_OpenText'])) {
            // Backwards compatibility with legacy open text name
            $genderValue = trim($fieldData['inf_option_Gender_776_OpenText']);
        }

        $contactData['_Gender'] = $genderValue;
    }
    
    if (isset($fieldData['SexualOrientations'])) {
        $sexualOrientationValues = normalizeSelectionsWithOpenText($fieldData['SexualOrientations'], $fieldData, array(
            'OtherPleaseSpecify' => 'SexualOrientationsOtherPleaseSpecify_OpenText',
        ));
        $contactData['_SexualOrientation'] = cleanAndJoinArray($sexualOrientationValues);
    }
   
   if (isset($fieldData['inf_option_pronouns'])) {
        $pronounValue = $fieldData['inf_option_pronouns'];
        if ($pronounValue === 'OtherPleaseSpecify' && !empty($fieldData['inf_option_pronouns_other_please_specify_OpenText'])) {
            $pronounValue = trim($fieldData['inf_option_pronouns_other_please_specify_OpenText']);
        }
        $contactData['_Pronouns'] = $pronounValue;
    }
   
    // Communication preferences
    if (isset($fieldData['inf_field_identify_terms'])) {
        $ethnicityValue = $fieldData['inf_field_identify_terms'];
        if ($ethnicityValue === 'SelfDescribe') {
            $ethnicityText = '';
            if (!empty($fieldData['inf_field_identify_terms_text'])) {
                $ethnicityText = trim($fieldData['inf_field_identify_terms_text']);
            } elseif (!empty($fieldData['inf_option_ethnicity_self_describe_OpenText'])) {
                $ethnicityText = trim($fieldData['inf_option_ethnicity_self_describe_OpenText']);
            }

            if ($ethnicityText !== '') {
                $ethnicityValue = $ethnicityText;
            }
        }

        $contactData['_RacialandEthnicIdentity'] = $ethnicityValue;
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
 * Assign step completion tags and step-specific tags
 */
function assignStepTags($contactId, $step, $fieldData) {
    global $app, $properties_ini;
    
    error_log("assignStepTags called for contactId: " . $contactId . ", step: " . $step);
    error_log("properties_ini available: " . (isset($properties_ini) ? 'YES' : 'NO'));
    
    if (!isset($properties_ini)) {
        error_log("ERROR: properties_ini not available in assignStepTags");
        return;
    }
    
    // Assign step completion tags
    // Convert step1 to step_1, step2 to step_2, etc.
    $stepNumber = str_replace('step', '', $step);
    $stepTagKey = 'step_' . $stepNumber . '_completed';
    $stepTag = isset($properties_ini[$stepTagKey]) ? $properties_ini[$stepTagKey] : null;
    
    error_log("Looking for step tag key: " . $stepTagKey . ", found: " . ($stepTag ? $stepTag : 'NOT FOUND'));
    
    if ($stepTag) {
        $result = $app->grpAssign($contactId, $stepTag);
        error_log("Assigned step completion tag for $step: " . $stepTag . ", result: " . print_r($result, true));
    } else {
        error_log("ERROR: Step tag not found for $step in properties_ini");
    }
    
    // Assign step-specific tags based on form data
    switch ($step) {
        case 'step1':
            assignStep1Tags($contactId, $fieldData);
            break;
        case 'step2':
            assignStep2Tags($contactId, $fieldData);
            break;
        case 'step3':
            assignStep3Tags($contactId, $fieldData);
            break;
        case 'step4':
            assignStep4Tags($contactId, $fieldData);
            break;
        case 'step5':
            assignStep5Tags($contactId, $fieldData);
            break;
        case 'step6':
            assignStep6Tags($contactId, $fieldData);
            break;
        case 'step7':
            assignStep7Tags($contactId, $fieldData);
            break;
        case 'step8':
            assignStep8Tags($contactId, $fieldData);
            break;
    }
}

/**
 * Update tags for a step by removing old tags and assigning new ones
 * This function is used when user goes back and edits previous steps
 */
function updateStepTags($contactId, $step, $newFieldData, $currentContact) {
    global $app, $properties_ini;
    
    // Get all currently assigned tags for this contact
    try {
        $contactTags = $app->loadCon($contactId, ['Groups']);
        $currentTagIds = array();
        
        // Groups might be returned as an array of tag IDs or as objects
        if (isset($contactTags['Groups']) && is_array($contactTags['Groups'])) {
            foreach ($contactTags['Groups'] as $group) {
                if (is_array($group) && isset($group['Id'])) {
                    $currentTagIds[] = $group['Id'];
                } elseif (is_numeric($group)) {
                    $currentTagIds[] = $group;
                } elseif (is_object($group) && isset($group->Id)) {
                    $currentTagIds[] = $group->Id;
                }
            }
        } elseif (isset($contactTags['Groups']) && is_numeric($contactTags['Groups'])) {
            // Single tag ID
            $currentTagIds[] = $contactTags['Groups'];
        }
    } catch (Exception $e) {
        error_log("Error loading contact tags: " . $e->getMessage());
        $currentTagIds = array();
    }
    
    // Define mapping arrays for each step to identify which tags belong to which step
    $stepTagMaps = array(
        'step2' => array(
            'sensory' => array('deaf' => 'Community-Need-Deaf', 'HI' => 'community-need-hearing-impaired', 'tinnitus' => 'Community-Need-Tinnitus', 'blind' => 'Community-Need-Blind', 'LV' => 'Community-Need-PartiallySighted', 'CB' => 'Community-Need-ColourBlind', 'LST' => 'Community-Need-No-Smell-Taste'),
            'physical' => array('CannotWalk' => 'Community-Need-Cannot-Walk-AtAll', 'CannotWalkFar' => 'Community-Need-Cannot-Walk-Far', 'Balance' => 'Community-Need-Balance', 'ShortStature' => 'Community-Need-UnderHeight', 'LongStature' => 'Community-Need-Overheight', 'LowerLimbDifference' => 'Community-Need-Lower-Limb-Difference', 'LimitedMobility' => 'Community-Need-Limited-Mobility', 'HandUpperLimbDifference' => 'Community-Need-Hand-Upper-Limb-Difference', 'LimitedDexterityStrength' => 'Community-Need-Other-Limited-Dexterity', 'LimitedDexterityControl' => 'Community-Need-Other-Limited-Dexterity', 'OtherClinicallyObese' => 'Community-Need-Other-Clinically-Obese'),
            'cognitive' => array('Memory' => 'Memory', 'FocusADHDADD' => 'Community-Need-Focus-ADHD/ADD', 'DyslexiaDyspraxiaDyscalculia' => 'Community-Need-Dyslexia-Dyspraxia-Dyscalculia', 'GeneralisedLearning' => 'Community-Need-Generalised-Learning', 'SocialSensoryChallenges' => 'SocialSensoryChallenges', 'Anxiety' => 'Community-Need-Anxiety', 'Depression' => 'Community-Need-Depression', 'PTSD' => 'PTSD', 'EatingDisorder' => 'EatingDisorder', 'SubstanceAbuseAddiction' => 'Community-Need-Substance-Abuse-Addiction', 'OtherMentalHealth' => 'Community-Need-Other-Mental-Health'),
            'communication' => array('NonverbalAtAll' => 'Community-Need-Nonverbal-AtAll', 'OcasionallyNonverbal' => 'Community-Need-Ocasionally-Nonverbal', 'SpeechImpairment' => 'Community-Need-Speech-Impairment', 'DifficultyWordRecall' => 'Community-Need-Difficulty-Word-Recall'),
            'chronic' => array('ChronicPain' => 'Community-Need-Chronic-Pain', 'HeartLungCondition' => 'Community-Need-Heart-Lung-Condition', 'PostStroke' => 'Community-Need-Post-Stroke', 'Cancer' => 'Community-Need-Cancer', 'AutoImmuneDisease' => 'Community-Need-Auto-Immune-Disease'),
            'other' => array('PreferNotToSay' => 'Community-Need-Prefer-Not-To-Say')
        )
    );
    
    // Get the tag map for this step
    if (!isset($stepTagMaps[$step])) {
        // For steps without specific maps, just assign new tags (can't determine old tags easily)
        assignStepTags($contactId, $step, $newFieldData);
        return;
    }
    
    $tagMap = $stepTagMaps[$step];
    $allStepTags = array();
    
    // Collect all possible tag keys for this step
    foreach ($tagMap as $category => $map) {
        foreach ($map as $value => $tagKey) {
            $allStepTags[] = $tagKey;
        }
    }
    
    // Find current step tags that should be removed
    $tagKeysToRemove = array();
    foreach ($allStepTags as $tagKey) {
        $tagId = getTagIdIfAvailable($tagKey);
        if ($tagId && in_array($tagId, $currentTagIds)) {
            // Check if this tag should still be assigned based on new data
            $shouldKeep = false;
            
            // Check if the corresponding value is in the new data
            foreach ($tagMap as $category => $map) {
                $fieldName = '';
                switch($category) {
                    case 'sensory': $fieldName = 'SensoryNeeds'; break;
                    case 'physical': $fieldName = 'PhysicalNeeds'; break;
                    case 'cognitive': $fieldName = 'CognitiveAndMentalhealthNeeds'; break;
                    case 'communication': $fieldName = 'CommunicationNeeds'; break;
                    case 'chronic': $fieldName = 'ChronichealthNeeds'; break;
                    case 'other': $fieldName = 'OtherNeeds'; break;
                }
                
                if (!empty($fieldName) && isset($newFieldData[$fieldName]) && is_array($newFieldData[$fieldName])) {
                    foreach ($map as $value => $mapTagKey) {
                        if ($mapTagKey === $tagKey && in_array($value, $newFieldData[$fieldName])) {
                            $shouldKeep = true;
                            break 2;
                        }
                    }
                }
            }
            
            if (!$shouldKeep) {
                $tagKeysToRemove[] = $tagKey;
            }
        }
    }
    
    // Remove tags that are no longer applicable
    foreach ($tagKeysToRemove as $tagKey) {
        $tagId = getTagIdIfAvailable($tagKey);
        if ($tagId) {
            $app->grpRemove($contactId, $tagId);
            error_log("Removed tag: $tagKey (ID: $tagId) from contact $contactId");
        }
    }
    
    // Now assign new tags (this will skip tags that are already assigned)
    assignStepTags($contactId, $step, $newFieldData);
}


/**
 * Helper: Get tag id by key if present in properties
 */
function getTagIdIfAvailable($tagKey) {
    global $properties_ini;
    return isset($properties_ini[$tagKey]) ? $properties_ini[$tagKey] : null;
}

/**
 * Assign tags for Part 2 Step 1 (Basic Information)
 */
function assignStep1Tags($contactId, $fieldData) {
    global $app;

    $assigned = array();

    // Country tags
    $countryMap = array(
        '01007' => 'Country-UK',
        '01008' => 'Country-USA',
        '01009' => 'Country-Australia',
        '01010' => 'Country-Ireland',
        '01011' => 'Country-Other', // Canada
        '01012' => 'Country-Other', // New Zealand
        // For other countries not in the main list, assign Country-Other
    );
    
    if (isset($fieldData['inf_field_country'])) {
        $countryValue = $fieldData['inf_field_country'];
        
        // Check if it's a numeric country code (01007, 01008, etc.)
        if (isset($countryMap[$countryValue])) {
            $tagKey = $countryMap[$countryValue];
            $tagId = getTagIdIfAvailable($tagKey);
            if ($tagId) { 
                if ($app->grpAssign($contactId, $tagId)) { 
                    $assigned[] = $tagKey; 
                } 
            }
        } else {
            // For all other countries, assign Country-Other
            $tagId = getTagIdIfAvailable('Country-Other');
            if ($tagId) { 
                if ($app->grpAssign($contactId, $tagId)) { 
                    $assigned[] = 'Country-Other'; 
                } 
            }
        }
    }

    // Region tags - region values match tag keys directly (e.g., '01101-Community-Region-UK-London')
    if (isset($fieldData['inf_field_region']) && !empty($fieldData['inf_field_region'])) {
        $regionValue = $fieldData['inf_field_region'];
        // Region value should match tag key directly
        $tagId = getTagIdIfAvailable($regionValue);
        if ($tagId) { 
            if ($app->grpAssign($contactId, $tagId)) { 
                $assigned[] = $regionValue; 
            } 
        }
    }

    // Relationship to disability
    $relationshipMap = array(
        'A-Disabled-Person' => 'A-Disabled-Person',
        'A-Carer-Of-A-Disabled-Person' => 'A-Carer-Of-A-Disabled-Person',
        'Over-65-Years-Old' => 'Over-65-Years-Old',
        'Having-Another-Relationship-To-Disability-Or-Specific-Access-Needs' => 'Having-Another-Relationship-To-Disability-Or-Specific-Access-Needs',
        // Map other relationship values to appropriate tags
        'A-person-with-specific-condition' => 'Having-Another-Relationship-To-Disability-Or-Specific-Access-Needs',
        'A-professional-caregiver-to-a-disabled-person' => 'A-Carer-Of-A-Disabled-Person',
        'A-personal-caregiver-to-a-disabled-person' => 'A-Carer-Of-A-Disabled-Person',
        'A-parent-of-someone-with-a-disability' => 'A-Carer-Of-A-Disabled-Person',
        'A-spouse-child-or-sibling-of-a-disabled-person' => 'Having-Another-Relationship-To-Disability-Or-Specific-Access-Needs',
        'I-have-another-relationship-to-disability-or-age-related-needs' => 'Having-Another-Relationship-To-Disability-Or-Specific-Access-Needs',
    );
    
    if (isset($fieldData['RelationShip']) && is_array($fieldData['RelationShip'])) {
        foreach ($fieldData['RelationShip'] as $val) {
            if (isset($relationshipMap[$val])) {
                $tagId = getTagIdIfAvailable($relationshipMap[$val]);
                if ($tagId) { 
                    if ($app->grpAssign($contactId, $tagId)) { 
                        $assigned[] = $relationshipMap[$val]; 
                    } 
                }
            }
        }
    }

    if (!empty($assigned)) {
        error_log('Step1 tags assigned: ' . implode(', ', array_unique($assigned)));
    } else {
        error_log('Step1 tags: none assigned (no mapped selections or missing tag keys).');
    }
}

/**
 * Assign tags for all Part 2 Step 2 checkbox groups
 */
function assignStep2Tags($contactId, $fieldData) {
    global $app;

    $assigned = array();

    // Sensory needs
    $sensoryMap = array(
        'deaf' => 'Community-Need-Deaf',
        'HI' => 'community-need-hearing-impaired',
        'tinnitus' => 'Community-Need-Tinnitus',
        'blind' => 'Community-Need-Blind',
        'LV' => 'Community-Need-PartiallySighted',
        // No explicit tag present for partially sighted (LV) in properties; skip
        'CB' => 'Community-Need-ColourBlind',
        'LST' => 'Community-Need-No-Smell-Taste',
    );
    if (isset($fieldData['SensoryNeeds']) && is_array($fieldData['SensoryNeeds'])) {
        foreach ($fieldData['SensoryNeeds'] as $val) {
            if (isset($sensoryMap[$val])) {
                $tagId = getTagIdIfAvailable($sensoryMap[$val]);
                if ($tagId) { if ($app->grpAssign($contactId, $tagId)) { $assigned[] = $sensoryMap[$val]; } }
            }
        }
    }

    // Physical needs
    $physicalMap = array(
        'CannotWalk' => 'Community-Need-Cannot-Walk-AtAll',
        'CannotWalkFar' => 'Community-Need-Cannot-Walk-Far',
        'Balance' => 'Community-Need-Balance',
        'ShortStature' => 'Community-Need-UnderHeight',
        'LongStature' => 'Community-Need-Overheight',
        'LowerLimbDifference' => 'Community-Need-Lower-Limb-Difference',
        'LimitedMobility' => 'Community-Need-Limited-Mobility',
        'HandUpperLimbDifference' => 'Community-Need-Hand-Upper-Limb-Difference',
        'LimitedDexterityStrength' => 'Community-Need-Other-Limited-Dexterity',
        'LimitedDexterityControl' => 'Community-Need-Other-Limited-Dexterity',
        'FacialDifferences    ' => 'Community-Condition-Facial-Disfigurement',
        'OtherClinicallyObese' => 'Community-Need-Other-Clinically-Obese',
    );
    if (isset($fieldData['PhysicalNeeds']) && is_array($fieldData['PhysicalNeeds'])) {
        foreach ($fieldData['PhysicalNeeds'] as $val) {
            if (isset($physicalMap[$val])) {
                $tagId = getTagIdIfAvailable($physicalMap[$val]);
                if ($tagId) { if ($app->grpAssign($contactId, $tagId)) { $assigned[] = $physicalMap[$val]; } }
            }
        }
    }

    // Cognitive and mental health needs
    $cognitiveMap = array(
        'Memory' => 'Memory',
        'FocusADHDADD' => 'Community-Need-Focus-ADHD/ADD',
        'DyslexiaDyspraxiaDyscalculia' => 'Community-Need-Dyslexia-Dyspraxia-Dyscalculia',
        'GeneralisedLearning' => 'Community-Need-Generalised-Learning',
        'SocialSensoryChallenges' => 'SocialSensoryChallenges',
        'Anxiety' => 'Community-Need-Anxiety',
        'Depression' => 'Community-Need-Depression',
        'PTSD' => 'PTSD',
        'EatingDisorder' => 'EatingDisorder',
        'SubstanceAbuseAddiction' => 'Community-Need-Substance-Abuse-Addiction',
        'OtherMentalHealth' => 'Community-Need-Other-Mental-Health',
    );
    if (isset($fieldData['CognitiveAndMentalhealthNeeds']) && is_array($fieldData['CognitiveAndMentalhealthNeeds'])) {
        foreach ($fieldData['CognitiveAndMentalhealthNeeds'] as $val) {
            if (isset($cognitiveMap[$val])) {
                $tagId = getTagIdIfAvailable($cognitiveMap[$val]);
                if ($tagId) { if ($app->grpAssign($contactId, $tagId)) { $assigned[] = $cognitiveMap[$val]; } }
            }
        }
    }

    // Communication needs
    $communicationMap = array(
        'NonverbalAtAll' => 'Community-Need-Nonverbal-AtAll',
        'OcasionallyNonverbal' => 'Community-Need-Ocasionally-Nonverbal',
        'SpeechImpairment' => 'Community-Need-Speech-Impairment',
        'DifficultyWordRecall' => 'Community-Need-Difficulty-Word-Recall',
    );
    if (isset($fieldData['CommunicationNeeds']) && is_array($fieldData['CommunicationNeeds'])) {
        foreach ($fieldData['CommunicationNeeds'] as $val) {
            if (isset($communicationMap[$val])) {
                $tagId = getTagIdIfAvailable($communicationMap[$val]);
                if ($tagId) { if ($app->grpAssign($contactId, $tagId)) { $assigned[] = $communicationMap[$val]; } }
            }
        }
    }

    // Chronic health needs
    $chronicMap = array(
        'ChronicPain' => 'Community-Need-Chronic-Pain',
        'HeartLungCondition' => 'Community-Need-Heart-Lung-Condition',
        'PostStroke' => 'Community-Need-Post-Stroke',
        'Cancer' => 'Community-Need-Cancer',
        'AutoImmuneDisease' => 'Community-Need-Auto-Immune-Disease',
    );
    if (isset($fieldData['ChronichealthNeeds']) && is_array($fieldData['ChronichealthNeeds'])) {
        foreach ($fieldData['ChronichealthNeeds'] as $val) {
            if (isset($chronicMap[$val])) {
                $tagId = getTagIdIfAvailable($chronicMap[$val]);
                if ($tagId) { if ($app->grpAssign($contactId, $tagId)) { $assigned[] = $chronicMap[$val]; } }
            }
        }
    }

    // Other needs
    $otherNeedsMap = array(
        'PreferNotToSay' => 'Community-Need-Prefer-Not-To-Say',
    );
    if (isset($fieldData['OtherNeeds']) && is_array($fieldData['OtherNeeds'])) {
        foreach ($fieldData['OtherNeeds'] as $val) {
            if (isset($otherNeedsMap[$val])) {
                $tagId = getTagIdIfAvailable($otherNeedsMap[$val]);
                if ($tagId) { if ($app->grpAssign($contactId, $tagId)) { $assigned[] = $otherNeedsMap[$val]; } }
            }
        }
    }

    if (!empty($assigned)) {
        error_log('Step2 tags assigned: ' . implode(', ', array_unique($assigned)));
    } else {
        error_log('Step2 tags: none assigned (no mapped selections or missing tag keys).');
    }
}

/**
 * Assign tags for Part 2 Step 3 (Assistive Technologies)
 */
function assignStep3Tags($contactId, $fieldData) {
    global $app;

    $assigned = array();

    // Digital and screen technologies
    // Note: ScreenReader is a general category. Specific software names would be captured in DigitalandScreenTechnologiesSpecificSoftware field
    // If specific software is mentioned, those would be mapped separately (e.g., JAWS, NVDA, Voiceover)
    $digitalMap = array(
        'ScreenMagnifier' => 'Community-AT-adaptive-approach-ZoomText',
        'Textresizedigital' => 'Community-AT-adaptive-approach-Text-resize-digital',
        'ColourChangesandContrast' => 'Community-AT-adaptive-approach-Colour-Changes-and-Contrast',
        'BrailleDisplay' => 'Community-AT-adaptive-approach-Braille-Display',
        'AudioDescription' => 'Community-AT-adaptive-approach-AudioDescription',
        'AIAT' => 'Community-AT-adaptive-approach-AI-Assistive-Technology',
        'Dragonandother' => 'Community-AT-adaptive-approach-Dragon-and-other',
        'AutoCaptioning' => 'Community-AT-adaptive-approach-Automatic-Captioning-Software',
        'MainstreamVoiceAssistants' => 'Community-AT-adaptive-approach-Mainstream-Voice-Assistants',
        'ReadAloudSoftware' => 'Community-AT-adaptive-approach-Read-Aloud-Software',
        'ClosedCaptionsSubtitles' => 'Community-AT-adaptive-approach-Closed-Captions-Subtitles',
        'RelayOrVideoPhone' => 'Community-AT-adaptive-approach-Relay-Service-App',
        'AlternativeKeyboard' => 'Community-AT-adaptive-approach-Alternative-Keyboard',
        'AlternativeMouseStylus' => 'Community-AT-adaptive-approach-Alternative-Mouse-Stylus',
        'AlternativeTouchscreenInteraction' => 'Community-AT-adaptive-approach-Alternative-Touchscreen-Interaction',
        'SwitchNavigation' => 'Community-AT-adaptive-approach-Switch-Navigation',
        'JoystickTrackball' => 'Community-AT-adaptive-approach-Joystick-Trackball',
        'HeadPointerMouthStickEyeTracking' => 'Community-AT-adaptive-approach-Head-Pointer-Mouth-Stick-Eye-Tracking',
        'NoiseCancellationHeadphones' => 'Community-AT-adaptive-approach-Noise-Cancellation-Headphones',
    );
    
    if (isset($fieldData['DigitalandScreenTechnologies']) && is_array($fieldData['DigitalandScreenTechnologies'])) {
        foreach ($fieldData['DigitalandScreenTechnologies'] as $val) {
            if (isset($digitalMap[$val])) {
                $tagId = getTagIdIfAvailable($digitalMap[$val]);
                if ($tagId) { 
                    if ($app->grpAssign($contactId, $tagId)) { 
                        $assigned[] = $digitalMap[$val]; 
                    } 
                }
            }
        }
    }

    // Print media
    $printMediaMap = array(
        'Largeprintdocumentspreferred' => 'Community-AT-adaptive-approach-Large-print-documents',
        'Brailledocumentspreferred' => 'Community-AT-adaptive-approach-Braille-documents',
        'Easyreaddocumentspreferred  ' => 'Community-AT-adaptive-approach-Easy-read',
        'Magnifyingglass' => 'Community-AT-adaptive-approach-Noise-Magnifying-Glass', // Note: Tag name includes "Noise" but is used for magnifying glass
    );
    
    if (isset($fieldData['PrintMedia']) && is_array($fieldData['PrintMedia'])) {
        foreach ($fieldData['PrintMedia'] as $val) {
            if (isset($printMediaMap[$val])) {
                $tagId = getTagIdIfAvailable($printMediaMap[$val]);
                if ($tagId) { 
                    if ($app->grpAssign($contactId, $tagId)) { 
                        $assigned[] = $printMediaMap[$val]; 
                    } 
                }
            }
        }
    }

    // Movement, canes and service animals
    $movementMap = array(
        'WheelchairPowered' => 'Community-AT-adaptive-approach-Power-wheelchair-user',
        'WheelchairManual' => 'Community-AT-adaptive-approach-Manual-wheelchair-user',
        'MobilityScooter' => 'Community-AT-adaptive-approach-Mobility-scooter-user',
        'Adaptedvehicle' => 'Community-AT-adaptive-approach-Adapted-private-vehicle',
        'ProstheticUpperLimb' => 'Community-AT-adaptive-approach-Prosthetic-upper-limb',
        'ProstheticLowerLimb' => 'Community-AT-adaptive-approach-Prosthetic-Lower-Limb',
        'Walkingaid' => 'Community-AT-adaptive-approach-Walking-aids',
        'Dog' => 'Community-AT-adaptive-approach-Service-Animal-guide-dog',
        'OtherServiceAnimal' => 'Community-AT-adaptive-approach-Other-Service-Animal',
        'Cane' => 'Community-AT-adaptive-approach-symbol-mobility-cane',
        'OtherNavigationalMobilityAid' => 'Community-AT-adaptive-approach-Other-Navigational-Mobility-Aid',
    );
    
    if (isset($fieldData['MovementCanesandServiceAnimals']) && is_array($fieldData['MovementCanesandServiceAnimals'])) {
        foreach ($fieldData['MovementCanesandServiceAnimals'] as $val) {
            if (isset($movementMap[$val])) {
                $tagId = getTagIdIfAvailable($movementMap[$val]);
                if ($tagId) { 
                    if ($app->grpAssign($contactId, $tagId)) { 
                        $assigned[] = $movementMap[$val]; 
                    } 
                }
            }
        }
    }

    // Communication preferences
    $communicationMap = array(
        'SignLanguage' => 'Community-AT-adaptive-approach-Sign-Language',
        'Lipreader' => 'Community-AT-adaptive-approach-Lipreader',
        'CochlearImplantBionic' => 'Community-AT-adaptive-approach-Cochlear-Implant-Bionic',
        'HearingAid' => 'Community-AT-adaptive-approach-HearingAid',
        'AAC' => 'Community-AT-adaptive-approach-Augmented-assistive-communication',
        'WirelessMicrophones' => 'Community-AT-adaptive-approach-Wireless-microphones',
        // Note: communication-aids tag exists in properties but may need mapping
    );
    
    if (isset($fieldData['CommunicationPreferences']) && is_array($fieldData['CommunicationPreferences'])) {
        foreach ($fieldData['CommunicationPreferences'] as $val) {
            if (isset($communicationMap[$val])) {
                $tagId = getTagIdIfAvailable($communicationMap[$val]);
                if ($tagId) { 
                    if ($app->grpAssign($contactId, $tagId)) { 
                        $assigned[] = $communicationMap[$val]; 
                    } 
                }
            }
        }
    }

    // Personal support and home
    $personalSupportMap = array(
        'PACarerPaidFulltime' => 'community-full-time-professional-carer',
        'PACarerPaidParttime' => 'community-occasional-professional-carer',
        'PACarerUnpaid' => 'community-unpaid-carer-support',
        'PACarerPaidOldPerson' => 'community-carer-disabled-person-paid-or-professional',
        'PACarerNotPaidOldPerson' => 'community-carer-of-a-disabled-or-older-person',
        'SmartHomeAdaptation' => 'community-Smart-home-adaptations',
        'Hoist' => 'Community-AT-adaptive-approach-Hoist',
        'AdaptedSpaces' => 'Community-AT-adaptive-approach-Adapted-Spaces',
        'AdaptedProducts' => 'Community-AT-adaptive-approach-Adapted-Products',
    );
    
    if (isset($fieldData['PersonalSupportandHome']) && is_array($fieldData['PersonalSupportandHome'])) {
        foreach ($fieldData['PersonalSupportandHome'] as $val) {
            if (isset($personalSupportMap[$val])) {
                $tagId = getTagIdIfAvailable($personalSupportMap[$val]);
                if ($tagId) { 
                    if ($app->grpAssign($contactId, $tagId)) { 
                        $assigned[] = $personalSupportMap[$val]; 
                    } 
                }
            }
        }
    }

    // Research formats
    $researchFormatMap = array(
        'any_paid_research' => 'any_paid_research',
        'online_surveys' => 'online_surveys',
        'Phone_or_video_conference_based_interviews' => 'Phone_or_video_conference_based_interviews',
        'Dairy_studies' => 'Community-Use-Diary-Studies-2017',  // Note: typo in form field name (Dairy instead of Diary)
        'Focus_groups' => 'Focus_groups',
        'Service_testing' => 'Service_testing',
        'Digital_testing' => 'Digital-experience-testing',
        'Journey_testing' => 'Journey_testing',
        'In_person_events' => 'In-person-events',
    );
    
    if (isset($fieldData['ResearchFormats']) && is_array($fieldData['ResearchFormats'])) {
        foreach ($fieldData['ResearchFormats'] as $val) {
            if (isset($researchFormatMap[$val])) {
                $tagId = getTagIdIfAvailable($researchFormatMap[$val]);
                if ($tagId) { 
                    if ($app->grpAssign($contactId, $tagId)) { 
                        $assigned[] = $researchFormatMap[$val]; 
                    } 
                }
            }
        }
    }

    if (!empty($assigned)) {
        error_log('Step3 tags assigned: ' . implode(', ', array_unique($assigned)));
    } else {
        error_log('Step3 tags: none assigned (no mapped selections or missing tag keys).');
    }
}

/**
 * Assign tags for Part 2 Step 4 (Other Personal Characteristics)
 */
function assignStep4Tags($contactId, $fieldData) {
    global $app;

    $assigned = array();

    // Gender tags
    $genderMap = array(
        '507' => 'Gender-Female',
        '505' => 'Gender-Male',
        '782' => 'Gender-Non-binary',
        '783' => 'Gender-Transgender-man',
        '784' => 'Gender-Transgender-Woman',
        '774' => 'Gender-Prefer_not_to_say',
    );
    
    if (isset($fieldData['inf_option_Gender'])) {
        $genderValue = $fieldData['inf_option_Gender'];
        if (isset($genderMap[$genderValue])) {
            $tagId = getTagIdIfAvailable($genderMap[$genderValue]);
            if ($tagId) { 
                if ($app->grpAssign($contactId, $tagId)) { 
                    $assigned[] = $genderMap[$genderValue]; 
                } 
            }
        }
    }

    // Sexual orientation tags
    $sexualOrientationMap = array(
        'Bisexual' => 'sexual_orientation_bisexual',
        'GayLesbianQueer' => 'sexual_orientation_ Gay_lesbian_queer', // Note: Space in tag key is intentional per myproperties.ini
        'Heterosexual' => 'sexual_orientation_Heterosexual_straight',
        'PreferNotToSay' => 'sexual_orientation_prefer_not_to_say',
    );
    
    if (isset($fieldData['SexualOrientations']) && is_array($fieldData['SexualOrientations'])) {
        foreach ($fieldData['SexualOrientations'] as $val) {
            if (isset($sexualOrientationMap[$val])) {
                $tagId = getTagIdIfAvailable($sexualOrientationMap[$val]);
                if ($tagId) { 
                    if ($app->grpAssign($contactId, $tagId)) { 
                        $assigned[] = $sexualOrientationMap[$val]; 
                    } 
                }
            }
        }
    }

    // Pronouns tags
    $pronounsMap = array(
        'She/her' => 'inf_option_pronouns_she_her',
        'He/him' => 'inf_option_pronouns_he_him',
        'They/them' => 'inf_option_pronouns_they_them',
        'OtherPleaseSpecify' => 'inf_option_pronouns_other_please_specify',
        'PreferNotToSend' => 'inf_option_pronouns_prefer_not_to_send',
    );
    
    if (isset($fieldData['inf_option_pronouns'])) {
        $pronounValue = $fieldData['inf_option_pronouns'];
        // Check if it's a direct match or needs mapping
        if (isset($pronounsMap[$pronounValue])) {
            $tagId = getTagIdIfAvailable($pronounsMap[$pronounValue]);
        } else {
            // Try direct lookup
            $tagId = getTagIdIfAvailable($pronounValue);
        }
        
        if ($tagId) { 
            if ($app->grpAssign($contactId, $tagId)) { 
                $assigned[] = isset($pronounsMap[$pronounValue]) ? $pronounsMap[$pronounValue] : $pronounValue; 
            } 
        }
    }

    if (!empty($assigned)) {
        error_log('Step4 tags assigned: ' . implode(', ', array_unique($assigned)));
    } else {
        error_log('Step4 tags: none assigned (no mapped selections or missing tag keys).');
    }
}

/**
 * Assign tags for Part 2 Step 5 (Community Agreement)
 */
function assignStep5Tags($contactId, $fieldData) {
    global $app;

    $assigned = array();

    // Community agreement acceptance - may not need a specific tag
    // This step mainly marks completion of the agreement phase
    
    if (!empty($assigned)) {
        error_log('Step5 tags assigned: ' . implode(', ', array_unique($assigned)));
    } else {
        error_log('Step5 tags: none assigned.');
    }
}

/**
 * Assign tags for Part 2 Step 6 (Privacy Protections)
 */
function assignStep6Tags($contactId, $fieldData) {
    global $app;

    $assigned = array();
    $timestampUpdated = false;

    // Check if all consent confirmations were provided (all 4 checkboxes checked)
    if (isset($fieldData['PleaseConfirm']) && is_array($fieldData['PleaseConfirm']) && count($fieldData['PleaseConfirm']) >= 4) {
        // User has confirmed all privacy and consent statements - assign consent_confirmed tag
        $consentConfirmedTagId = getTagIdIfAvailable('consent_confirmed');
        if ($consentConfirmedTagId) {
            if ($app->grpAssign($contactId, $consentConfirmedTagId)) {
                $assigned[] = 'consent_confirmed';
                error_log('Assigned consent_confirmed tag for contact ID: ' . $contactId . ' (user confirmed all privacy statements)');
            }
        } else {
            error_log('Warning: consent_confirmed tag ID not found in myproperties.ini for contact ID: ' . $contactId);
        }

        // Update the timestamp custom field when privacy statements are confirmed
        $timestampFieldName = '_privacy_statement_timestamp';
        $timestampValue = wp_date('Y-m-d H:i:s'); // Local site time including date and time
        try {
            $result = $app->updateCon($contactId, array($timestampFieldName => $timestampValue));
            error_log('Privacy statement timestamp updated for contact ID: ' . $contactId . ' to ' . $timestampValue . '. Result: ' . print_r($result, true));
            $timestampUpdated = true;
        } catch (Exception $e) {
            error_log('Failed to update privacy statement timestamp for contact ID: ' . $contactId . '. Error: ' . $e->getMessage());
        }
    } else {
        error_log('Step6: Not all confirmations checked - consent_confirmed tag not assigned');
    }
    
    if (!empty($assigned) || $timestampUpdated) {
        $logParts = array();
        if (!empty($assigned)) {
            $logParts[] = 'Tags assigned: ' . implode(', ', array_unique($assigned));
        }
        if ($timestampUpdated) {
            $logParts[] = 'privacy timestamp set';
        }
        error_log('Step6 updates for contact ID ' . $contactId . ': ' . implode(' | ', $logParts));
    } else {
        error_log('Step6 updates: none assigned or updated.');
    }
}

/**
 * Assign tags for Part 2 Step 7 (Identity Verification)
 */
function assignStep7Tags($contactId, $fieldData) {
    global $app, $properties_ini;

    $assigned = array();

    // Open Verified opt-in
    if (isset($fieldData['OpenVerifiedOptIn']) && is_array($fieldData['OpenVerifiedOptIn']) && count($fieldData['OpenVerifiedOptIn']) > 0) {
        // User opted in to be Open Verified - assign the open_verified tag
        $openVerifiedTagId = getTagIdIfAvailable('open_verified');
        if ($openVerifiedTagId) {
            if ($app->grpAssign($contactId, $openVerifiedTagId)) {
                $assigned[] = 'open_verified';
                error_log('Assigned open_verified tag for contact ID: ' . $contactId . ' (user opted in to Open Verified)');
            }
        } else {
            error_log('Warning: open_verified tag ID not found in myproperties.ini for contact ID: ' . $contactId);
        }
    }

    if (!empty($assigned)) {
        error_log('Step7 tags assigned: ' . implode(', ', array_unique($assigned)));
    } else {
        error_log('Step7 tags: none assigned.');
    }
}

/**
 * Assign tags for Part 2 Step 8 (Create Community Login)
 */
function assignStep8Tags($contactId, $fieldData) {
    global $app;

    $assigned = array();

    // Login creation - typically marks completion of registration
    // May assign member_tag or phase_complete tag
    
    if (!empty($assigned)) {
        error_log('Step8 tags assigned: ' . implode(', ', array_unique($assigned)));
    } else {
        error_log('Step8 tags: none assigned.');
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
 