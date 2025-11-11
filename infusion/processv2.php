<?php

//header('Access-Control-Allow-Origin: *');
//require_once '../../vendor/autoload.php';
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
$properties_ini = parse_ini_file("myproperties.ini");
error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING & ~E_DEPRECATED & ~E_STRICT);
// require("isdk.php");
require_once("isdk.php");
$app = new isdk();

// Process Part 1 - Basic contact information only
$fieldData = $_REQUEST;

if ($app->cfgCon("connectionName")) {
    // Prepare contact data with only the basic fields from Part 1
    $contactData = [
        'FirstName'                         => isset($fieldData['inf_field_FirstName']) ? $fieldData['inf_field_FirstName'] : '',
        'LastName'                          => isset($fieldData['inf_field_LastName']) ? $fieldData['inf_field_LastName'] : '',
        'Email'                             => isset($fieldData['inf_field_Email']) ? $fieldData['inf_field_Email'] : '',
        'Phone1'                            => isset($fieldData['inf_field_Phone2']) ? $fieldData['inf_field_Phone2'] : '',
        '_ContactMethodPreference'          => isset($fieldData['PreferToContact']) ? implode(', ', $fieldData['PreferToContact']) : '',
        '_PhoneCountryCode'                 => isset($fieldData['inf_field_countryphonecode']) ? $fieldData['inf_field_countryphonecode'] : '',
        '_RegistrationStatus'               => 'Initial Registration' // Mark as initial registration
    ];

    // Handle phone code mapping
    if(isset($fieldData['inf_field_countryphonecode'])) {
        $phoneCode = $fieldData['inf_field_countryphonecode'];
        // $contactData['_PhoneCountryCode'] = $phoneCode;
         $phoneCodeMap = array(
            '44' => '+44',
            '1' => '+1', 
            '+61' => '+61',
            '+353' => '+353',
            '+1' => '+1',
            '+64' => '+64',
            '+' => 'Other' // For "Other" option
        );
        
        // Use mapped value if available, otherwise use original value
        $mappedPhoneCode = isset($phoneCodeMap[$phoneCode]) ? $phoneCodeMap[$phoneCode] : $phoneCode;
        $contactData['_PhoneCountryCode'] = $mappedPhoneCode;
    }
        // Handle contact method preferences - check individual fields
    $contactMethods = array();

    // Handle contact method preferences
       if(isset($fieldData['PreferToContact_Email']) && !empty($fieldData['PreferToContact_Email'])) {
        $contactMethods[] = 'Email';
    }
    if(isset($fieldData['PreferToContact_SMS']) && !empty($fieldData['PreferToContact_SMS'])) {
        $contactMethods[] = 'SMS/Text';
    }
    if(isset($fieldData['PreferToContact_Phone']) && !empty($fieldData['PreferToContact_Phone'])) {
        $contactMethods[] = 'Phone';
    }
    if(isset($fieldData['PreferToContact_Whatsapp']) && !empty($fieldData['PreferToContact_Whatsapp'])) {
        $contactMethods[] = 'WhatsApp';
    }
    if(isset($fieldData['PreferToContactOthers_Others_2']) && !empty($fieldData['PreferToContactOthers_Others_2'])) {
        $contactMethods[] = 'Other';
    }
    if(isset($fieldData['PreferToContact']) && is_array($fieldData['PreferToContact'])) {
        // $contactMethods = array();
        foreach($fieldData['PreferToContact'] as $method) {
            if($method == 'Email') {
                $contactMethods[] = 'Email';
            } elseif($method == 'SMS') {
                $contactMethods[] = 'SMS/Text';
            } elseif($method == 'Phone') {
                $contactMethods[] = 'Phone';
            } elseif($method == 'Whatsapp Message') {
                $contactMethods[] = 'WhatsApp';
            } elseif($method == 'Others') {
                $contactMethods[] = 'Other';
            }
        }
        // $contactData['_ContactMethodPreference'] = implode(', ', $contactMethods);
            }
    
    // Set the contact method preference if any methods were found
    if(!empty($contactMethods)) {
        $contactData['_ContactMethodPreference'] = implode(', ', $contactMethods);
    }
     
    // Check for duplication by email
    $returnFields = ['Id'];
    $conDat = $app->findByEmail($fieldData['inf_field_Email'], $returnFields);
    
    if (empty($conDat)) {
        // Create new contact
        $contactId = $app->addCon($contactData);
        
        // Assign basic tags
        if(isset($properties_ini['initial_registration_tag'])) {
            $app->grpAssign($contactId, $properties_ini['initial_registration_tag']);
        }
        if(isset($properties_ini['non_verified'])) {
            $app->grpAssign($contactId, $properties_ini['non_verified']);
        }
        
        // Assign phase_basic_info tag for Part 1 completion
        if(isset($properties_ini['phase_basic_info'])) {
            $app->grpAssign($contactId, $properties_ini['phase_basic_info']);
        }
        
        // Assign form submission tag
        if(isset($properties_ini['open-panel-form-submitted'])) {
            $app->grpAssign($contactId, $properties_ini['open-panel-form-submitted']);
        }
        
        // Assign contact method tags
        if(isset($fieldData['PreferToContact']) && is_array($fieldData['PreferToContact'])) {
            foreach($fieldData['PreferToContact'] as $method) {
                if(isset($properties_ini['contact_method_' . strtolower($method)])) {
                    $app->grpAssign($contactId, $properties_ini['contact_method_' . strtolower($method)]);
                }
            }
        }
        
    } else {
        // Update existing contact
        // $app->updateCon($conDat[0]['Id'], $contactData);
        // $contactId = $conDat[0]['Id'];
                if (is_array($conDat) && isset($conDat[0]) && is_array($conDat[0]) && isset($conDat[0]['Id'])) {
            $app->updateCon($conDat[0]['Id'], $contactData);
            $contactId = $conDat[0]['Id'];
            
            // Assign phase_basic_info tag for Part 1 completion (existing contact)
            if(isset($properties_ini['phase_basic_info'])) {
                $app->grpAssign($contactId, $properties_ini['phase_basic_info']);
            }
        } else {
            error_log("Invalid contact data structure in processv2.php: " . print_r($conDat, true));
            return false;
        }
    }
    
    // Log the contact ID for debugging
    error_log("Contact created/updated with ID: " . $contactId);
}
?>