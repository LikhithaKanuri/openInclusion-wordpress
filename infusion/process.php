<?php

//header('Access-Control-Allow-Origin: *');
//require_once '../../vendor/autoload.php';
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING & ~E_DEPRECATED & ~E_STRICT);
require("isdk.php");
$app = new isdk();

if(isset($_REQUEST['submitAction'])){
  $action = $_REQUEST['submitAction'];
  //submit open conclusion form to infusionsoft
  if($action == "submitOCformData"){

  parse_str($_POST['formData'], $fieldData); 
  //Multiple Records from one fields whill go comma seperator to infusionsoft
  foreach($fieldData['_PreferToContact'] as $fieldDataSingle){
    $PreferToContact = $fieldDataSingle .','. $PreferToContact;
  }
  foreach($fieldData['_Checkanythatapplytoyou'] as $CheckanythatapplytoyouDataSingle){
    $Checkanythatapplytoyou = $CheckanythatapplytoyouDataSingle .','. $Checkanythatapplytoyou;
  }
  foreach($fieldData['_SupportandAccessYouneed'] as $SupportandAccessYouneedDataSingle){
    $SupportandAccessYouneed = $SupportandAccessYouneedDataSingle .','. $SupportandAccessYouneed;
  }
     if ($app->cfgCon("connectionName")) {
      $contactData = [
        'FirstName'                         => $fieldData['inf_field_FirstName'],
        'LastName'                          => $fieldData['inf_field_LastName'],
        'Email'                             => $fieldData['inf_field_Email'],
        'Phone1'                            => $fieldData['inf_field_Phone1'],
        'Phone2'                            => $fieldData['inf_field_Phone2'],
        'PostalCode'                        => $fieldData['inf_field_PostalCode'],
        '_Whatregiondoyoulivein'            => $fieldData['inf_option_Whatregiondoyoulivein'],
        '_ReferredtotheOpenInclusionPanel'  => $fieldData['info_custom_ReferredtotheOpenInclusionPanel'],
        '_Gender'                           => $fieldData['inf_option_Gender'],
        '_YearBorn'                         => $fieldData['inf_custom_YearBorn'],
        '_Ethnicity'                        => $fieldData['inf_option_Ethnicity'], 
        '_ImpairmentInfo'                   => $fieldData['inf_custom_ImpairmentInfo'],
        '_AccessNeedinfo'                   => $fieldData['inf_custom_AccessNeedinfo'],
        '_PreferToContact'                  => $PreferToContact,
        '_Checkanythatapplytoyou'           => $Checkanythatapplytoyou,
        '_SupportandAccessYouneed'          => $SupportandAccessYouneed   
      ];
      //check for duplication//
      $returnFields = ['Id'];
      $conDat = $app->findByEmail($fieldData['inf_field_Email'], $returnFields);
       if (empty($conDat)) {
            $contactId = $app->addCon($contactData);
        } else {
            $app->updateCon($conDat[0]['Id'], $contactData);
            $contactId = $conDat[0]['Id'];
        }
      echo $contactId;
      $tagId = 1613;//1613 is the Tag 'open-panel-form-submitted'
      $result = $app->grpAssign($contactId, $tagId);
      //$campId = 253;
      //$result = $app->campAssign($contactId, $campId);
      die(); 
      
    };
  }
}
die(); 