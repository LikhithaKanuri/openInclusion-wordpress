<?php

//header('Access-Control-Allow-Origin: *');
//require_once '../../vendor/autoload.php';
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
$properties_ini = parse_ini_file("myproperties.ini");
error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING & ~E_DEPRECATED & ~E_STRICT);
require("isdk.php");
$app = new isdk();

$fieldData = $_REQUEST;

$genderText="";
$sensoryText = "";
$physicalText = "";
$relationShipText = "";
$temporaryAccessNeed = "";
$otherTechnology = "";
$otherNeed = "";
$ourCommunityText="";

if ($app->cfgCon("connectionName")) {
    $genderTag = $properties_ini[$fieldData['inf_option_Gender']];
    ## This Needs to be Re-work in my-properties.ini and form
    foreach ($properties_ini as $key => $value) {
        if($genderTag === $value && (int)$genderTag !== $key){
        $genderText .= substr_replace($key, '', 0,7);
        }
    }
    if(strlen($fieldData['inf_option_Gender_776_OpenText'])>0){
        $genderText = $fieldData['inf_option_Gender_776_OpenText'];
    }

    // Sensory Needs
    $sensoryNeedArray = $fieldData['SensoryNeeds'];
    if(in_array('OtherPleaseSpecify',$sensoryNeedArray)){
        $sensoryText =  $sensoryNeedArray[count($sensoryNeedArray)-1];
    }
    // Physical Needs
    $physicalNeedArray = $fieldData['PhysicalNeeds'];
    if(in_array('OtherPleaseSpecify',$physicalNeedArray)){
        $physicalText =  $physicalNeedArray[count($physicalNeedArray)-1];
    }

    // Temproray Access Need
    if($fieldData['TemporaryAccessNeedsYes']){
        $temporaryAccessNeed = $fieldData['inf_field_TemporaryAccessNeed'];
    }
    elseif($fieldData['TemporaryAccessNeedsNo']){
        $temporaryAccessNeed = $fieldData['TemporaryAccessNeedsNo'];
    }
    elseif($fieldData['TemporaryAccessNeedsNA']){
        $temporaryAccessNeed = $fieldData['TemporaryAccessNeedsNA'];
    }

    // Other Need
    if($fieldData['OtherNeedsNoneoftheAbove']){
        $otherNeed = $fieldData['OtherNeedsNoneoftheAbove'];
    }
    elseif($fieldData['OtherNeedsPreferNotToSay']){
        $otherNeed = $fieldData['OtherNeedsPreferNotToSay'];
    }
    elseif($fieldData['OtherNeedsOtherPleaseSpecify']){
        $otherNeed = $fieldData['OtherNeedsOtherPleaseSpecify_OpenText'];
    }
    
    // Other Techonologies
    if($fieldData['OtherTechnologiesNoneoftheAbove']){
        $otherTechnology = $fieldData['OtherTechnologiesNoneoftheAbove'];
    }
    elseif($fieldData['OtherTechnologiesPreferNotToSay']){
        $otherTechnology = $fieldData['OtherTechnologiesPreferNotToSay'];
    }
    elseif($fieldData['OtherTechnologiesOtherPleaseSpecify']){
        $otherTechnology = $fieldData['OtherTechnologiesOtherPleaseSpecify_OpenText'];
    }

    //Relation Ship
    $relationShipArray = $fieldData['RelationShip'];
    if(in_array('OtherPleaseSpecify',$relationShipArray)){
        $relationShipText =  $relationShipArray[count($relationShipArray)-1];
    }

    //Our Community
    $ourCommunityText = $fieldData['OurCommunity'];
    if($ourCommunityText=='ACommunityOrganisation') {
        $ourCommunityText = $fieldData['ACommunityOrganisation_OpenText'];
    }
    elseif($ourCommunityText=='APartOfCommunity'){
        $ourCommunityText = $fieldData['APartOfCommunity_OpenText'];
    }
    elseif($ourCommunityText=='OurCommunityOther'){
        $ourCommunityText = $fieldData['OurCommunityOther_OpenText'];
    }
    $contactData = [
        'FirstName'                         => $fieldData['inf_field_FirstName'],
        'LastName'                          => $fieldData['inf_field_LastName'],
        'UserName'                          => $fieldData['inf_field_UserName'],
        'PostalCode'                        => $fieldData['inf_field_PostalCode'],
        '_Gender'                           => $genderText,
        '_YearBorn'                         => $fieldData['inf_custom_YearBorn'],
        '_Primaryneed'                      => $fieldData['inf_field_PrimaryNeed'],
        '_Region'                           => $fieldData['inf_field_region'],
        "_SensoryNeed"                      => $sensoryText,
        "_PhysicalNeed"                     => $physicalText,
        "_TemporaryAccessNeed"              => $temporaryAccessNeed,
        "_OtherTechnology"                  => $otherTechnology,
        "_OtherNeed"                        => $otherNeed,
        "_RelationShip"                     => $relationShipText,
        "_FindOurCommunity"                 => $ourCommunityText
    ];
    //check for duplication//
    $returnFields = ['Id'];





    $current_user=wp_get_current_user();
    $userid = $current_user->ID;
    $user_info = array();
    $user_info = get_user_meta($userid);
    $user_email = $user_info['Email'][0];
    $returnFields = ['Id'];
    $contactData += [
        '_RegistrationStatus' => 'Member'
    ];
    $conDat = $app->findByEmail($user_email, $returnFields);
    $contactId = '';
    if (empty($conDat)) {
        $contactId = $app->addCon($contactData);
    } else {
        $app->updateCon($conDat[0]['Id'], $contactData);
        $contactId = $conDat[0]['Id'];
    }
    if(!empty($contactId)) {
        $app->grpAssign($contactId, $properties_ini['member_tag']);
        $app->grpRemove($contactId, $properties_ini['partial_member_tag']);
        $tagId = $properties_ini['open-panel-form-submitted'];//1613 is the Tag 'open-panel-form-submitted'
        $countryTagId = $properties_ini[$fieldData['inf_field_country']];
        $genderTagId = $properties_ini[$fieldData['inf_option_Gender']];
        $stateTagId = $properties_ini[$fieldData['inf_field_region']];
        $primaryTagId = $properties_ini[$fieldData['inf_field_Age_Bracket']];
        
        //sensory Needs
        foreach($sensoryNeedArray as $sensoryNeeds){
          $result = $app->grpAssign($contactId, $properties_ini[$sensoryNeeds]);
        }
  
  
        // Physical Needs
        foreach($physicalNeedArray as $physicalNeeds){
          $result = $app->grpAssign($contactId, $properties_ini[$physicalNeeds]);
        }
  
        //Cognitive And Mental health Needs
        $cognitiveAndMentalhealthNeedArray = $fieldData['CognitiveAndMentalhealthNeeds'];
        foreach($cognitiveAndMentalhealthNeedArray as $cognitiveAndMentalhealthNeeds){
          $result = $app->grpAssign($contactId, $properties_ini[$cognitiveAndMentalhealthNeeds]);
        }
  
        //Relation Ship
        foreach($relationShipArray as $relationShip){
          $result = $app->grpAssign($contactId, $properties_ini[$relationShip]);
        }
  
        //Our Community
        foreach($oruCommunityArray as $ourCommunity){
          $result = $app->grpAssign($contactId, $properties_ini[$ourCommunity]);
        }
  
        //Communication Needs
        $communicationNeedArray = $fieldData['CommunicationNeeds'];
        foreach($communicationNeedArray as $communicationNeeds){
          $result = $app->grpAssign($contactId, $properties_ini[$communicationNeeds]);
        }
  
        //Chronic health needs
        $chronicHealthNeedArray = $fieldData['ChronichealthNeeds'];
        foreach($chronicHealthNeedArray as $chronicHealthNeeds){
          $result = $app->grpAssign($contactId, $properties_ini[$chronicHealthNeeds]);
        }
  
        // Digital and Screen Technologies
        $digitalAndScreenArray = $fieldData['DigitalandScreenTechnologies'];
        foreach($digitalAndScreenArray as $digitalAndScreens){
          $result = $app->grpAssign($contactId, $properties_ini[$digitalAndScreens]);
        }
  
        // Movement, canes and service animals
        $movementServiceArray = $fieldData['MovementCanesandServiceAnimals'];
        foreach($movementServiceArray as $movementServices){
          $result = $app->grpAssign($contactId, $properties_ini[$movementServices]);
        }
  
        // Communication, verbal and written preferences
        $communicationWrittenArray = $fieldData['CommunicationPreferences'];
        foreach($communicationWrittenArray as $communicationWrittens){
          $result = $app->grpAssign($contactId, $properties_ini[$communicationWrittens]);
        }
  
        // Prefer To Contact Others
        if(count($fieldData['CommunicationPreferences'])>0){
          $preferToContactArray = $fieldData['CommunicationPreferences'];
          foreach($preferToContactArray as $preferToContactArray){
            $result = $app->grpAssign($contactId, $properties_ini[$preferToContacts]);
          }
        }
        
        // Personal support and home
        $personalSupportArray = $fieldData['PersonalSupportandHome'];
        foreach($personalSupportArray as $personalSupports){
          $result = $app->grpAssign($contactId, $properties_ini[$personalSupports]);
        }
  
        $preferToContactArray = $fieldData['PreferToContactOthers'];
        foreach($preferToContactArray as $preferToContacts){
          $result = $app->grpAssign($contactId, $properties_ini[$preferToContacts]);
        }
        $result = $app->grpAssign($contactId, $tagId);
        $result = $app->grpAssign($contactId, $countryTagId);
        $result = $app->grpAssign($contactId, $stateTagId);
        $result = $app->grpAssign($contactId, $genderTagId);
        $result = $app->grpAssign($contactId, $primaryTagId);
    }
    
} 