<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING & ~E_DEPRECATED & ~E_STRICT);
require("infusion/isdk.php");
$app = new isdk();

$_REQUEST['consent'] = array(1206, 1203, 1204, 1207);
$_REQUEST['inf_field_FirstName'] = "Rohith";
$_REQUEST['inf_field_LastName'] = "Cherote";
$_REQUEST['inf_field_Email'] = "rohith.cherote@kenpath.io";
$_REQUEST['inf_field_countryphonecode'] = 44;
$_REQUEST['inf_field_Phone2'] = 9731184780;
$_REQUEST['PreferToContact'] = array("Others");
$_REQUEST['PreferToContactOthers'] = array("Phone", "Video_WP", "Voice_WP", "OtherPleaseSpecify", "Face to Face Meeting");
$_REQUEST['inf_field_UserName'] = "rohith.cherote";
$_REQUEST['inf_field_Password'] = "Welcome@123";
$_REQUEST['inf_field_country'] = "United Kingdom";
$_REQUEST['inf_field_region'] = "01101-Community-Region-UK-London";
$_REQUEST['inf_custom_YearBorn'] = 1980;
$_REQUEST['inf_option_Gender'] = "Male";
$_REQUEST['inf_option_Gender_opentext'] = "";
$_REQUEST['SensoryNeeds'] = array("HI", "tinnitus", "blind");
$_REQUEST['PhysicalNeeds'] = array("CannotWalkFar", "Balance", "ShortStature");
$_REQUEST['CognitiveAndMentalhealthNeeds'] = array("FocusADHDADD", "DyslexiaDyspraxiaDyscalculia", "GeneralisedLearning");
$_REQUEST['CommunicationNeeds'] = array("OcasionallyNonverbal", "SpeechImpairment", "DifficultyWordRecall");
$_REQUEST['ChronichealthNeeds'] = array("PostStroke", "Cancer", "AutoImmuneDisease");
$_REQUEST['OtherNeedsOtherPleaseSpecify_OpenText'] = "Other Technologies";
$_REQUEST['inf_field_PrimaryNeed'] = "Primary need";
$_REQUEST['inf_field_Age_Bracket'] = "01018";
$_REQUEST['inf_field_TemporaryAccessNeed'] = "Temporary Access Need";
$_REQUEST['DigitalandScreenTechnologies'] = array("ScreenMagnifier", "Textresizedigital", "ColourChangesandContrast");
$_REQUEST['MovementCanesandServiceAnimals'] = array("WheelchairManual", "MobilityScooter", "Adaptedvehicle");
$_REQUEST['CommunicationPreferences'] = array("Lipreader", "CochlearImplantBionic", "HearingAid");
$_REQUEST['PersonalSupportandHome'] = array("PACarerPaidParttime", "PACarerUnpaid", "PACarerIsOne");
$_REQUEST['OtherTechnologiesOtherPleaseSpecify_OpenText'] = "Other Needs";

if(isset($_REQUEST['consent'])) {
  if ($app->cfgCon("connectionName")) {
    $contactData = [
    'FirstName'                         => $_REQUEST['inf_field_FirstName'],
    'LastName'                          => $_REQUEST['inf_field_LastName'],
    'Email'                             => $_REQUEST['inf_field_Email'],
    'Phone1'                            => $_REQUEST['inf_field_countryphonecode']. "-" .$_REQUEST['inf_field_Phone2'],
    //'PreferToContact'                   => getRegistrationPreferToContact(),
    'UserName'                          => $_REQUEST['inf_field_UserName'],
    'Password'                          => $_REQUEST['inf_field_Password'],
    'Country'                           => $_REQUEST['inf_field_country'],
    //'Region'                            => $_REQUEST['inf_field_region'],
    //'YearBorn'                          => $_REQUEST['inf_custom_YearBorn'],
    //'Gender'                            => $_REQUEST['inf_option_Gender'] . $_REQUEST['inf_option_Gender_opentext'],
    //'SensoryNeeds'                      => implode(",", $_REQUEST['SensoryNeeds']),
    //'PhysicalNeeds'                     => implode(",", $_REQUEST['PhysicalNeeds']),
    //'CognitiveAndMentalhealthNeeds'     => implode(",", $_REQUEST['CognitiveAndMentalhealthNeeds']),
    //'CommunicationNeeds'                => implode(",", $_REQUEST['CommunicationNeeds']),
    //'ChronichealthNeeds'                => implode(",", $_REQUEST['ChronichealthNeeds']),
    //'OtherNeeds'                        => $_REQUEST['OtherNeedsOtherPleaseSpecify_OpenText'],
    //'PrimaryNeed'                       => $_REQUEST['inf_field_PrimaryNeed'],
    //'AgeBracket'                        => $_REQUEST['inf_field_Age_Bracket'],
    //'TemporaryAccessNeeds'              => $_REQUEST['inf_field_TemporaryAccessNeed'],
    //'DigitalandScreenTechnologies'      => implode(",", $_REQUEST['DigitalandScreenTechnologies']),
    //'MovementCanesandServiceAnimals'    => implode(",", $_REQUEST['MovementCanesandServiceAnimals']),
    //'CommunicationPreferences'          => implode(",", $_REQUEST['CommunicationPreferences']),
    //'PersonalSupportandHome'            => implode(",", $_REQUEST['PersonalSupportandHome']),
    //'OtherNeeds'                        => $_REQUEST['OtherTechnologiesOtherPleaseSpecify_OpenText'],
    //'consent'                           => implode(",", $_REQUEST['consent']),
    ];
    echo "<pre>";
    print_r($contactData);
    //check for duplication//
    $returnFields = ['Id'];    
    $conDat = $app->findByEmail($_REQUEST['inf_field_Email'], $returnFields);
    print_r($conDat);
    if (empty($conDat)) {
      echo "Creating new contact<br/>";
      $contactId = $app->addCon($contactData);
    } 
    else {
      $app->updateCon($conDat[0]['Id'], $contactData);
      $contactId = $conDat[0]['Id'];
    }

    $order = array('_Gender' => 'Male');
    $result = $app->dsUpdate("Contact", $contactId, $order);
    print_r($result);
    $settings = $app->dsGetSetting("Contact");
    print_r($settings);

    $tagId = getGenderTag();
    $result = $app->grpAssign($contactId, $tagId);
  }
}

function getRegistrationPreferToContact() {
  $output = array();
  if(isset($_REQUEST['PreferToContact']) && is_array($_REQUEST['PreferToContact'])) {
    $output = array_merge($output, $_REQUEST['PreferToContact']);
  }
  if(isset($_REQUEST['PreferToContactOthers']) && is_array($_REQUEST['PreferToContactOthers'])) {
    $output = array_merge($output, $_REQUEST['PreferToContactOthers']);
  }  
  return implode(",", $output);
}

function getGenderTag(){
  $gender = $_REQUEST['inf_option_Gender'];
  $gender_array = array(
    '507' => 1859,//Female
    '505' => 1839,//Male
    '782' => 2087,//Non-binary
    '783' => 2089,//Transgender man
    '784' => 2091,//Transgender woman
    '774' => 1528,//Would prefer not to say
 );  
 if(isset($gender_array[$gender])) {
    return $gender_array[$gender];
 }
 else {
  return "";
 }
}

