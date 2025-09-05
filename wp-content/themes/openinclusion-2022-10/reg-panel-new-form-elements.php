<?php
// Arrays for courses and internet access
$contact_method_array = array(
    array('Email',__( 'Please contact me via email', 'openinclusion' ),'_PreferToContact[]', '_PreferToContact_Email'),
    array('Others',__( 'I would like to be contacted in another way', 'openinclusion' ),'_PreferToContact[]', '_PreferToContact_Others'),
    //array('Mobile',__( 'Mobile', 'openinclusion' ),'_PreferToContact', '_PreferToContact_Mobile'),
    //array('Text',__( 'Text', 'openinclusion' ),'_PreferToContact', '_PreferToContact_Text'),
    //array('Calls via Text Relay Service',__( 'Calls via Text Relay Service', 'openinclusion' ),'_PreferToContact', '_PreferToContact_cvtrs')
 );
 function get_contact_methods() {
    global $contact_method_array;
    return $contact_method_array; 
 }
 
 $contact_method_others_array = array(
    array('SMS',__( 'SMS / Text message', 'openinclusion' ),'_PreferToContactOthers[]', '_PreferToContactOthers_SMS'),
    array('Phone',__( 'Phone call', 'openinclusion' ),'_PreferToContactOthers[]', '_PreferToContactOthers_Phone'),
    array('Video_WP',__( 'Video call - WhatsApp or other', 'openinclusion' ),'_PreferToContactOthers[]', '_PreferToContactOthers_Video_WP'),
    array('Voice_WP',__( 'Voice Message - WhatsApp or other', 'openinclusion' ),'_PreferToContactOthers[]', '_PreferToContactOthers_Voice_WP'),
    array('Calls via Text Relay Service',__( 'Calls via Text Relay Service', 'openinclusion' ),'_PreferToContactOthers', '_PreferToContactOthers_cvtrs'),
    array('Video_BSL',__( 'Video chat BSL', 'openinclusion' ),'_PreferToContactOthers[]', '_PreferToContactOthers_video_BSL'),
    array('Video_ASL',__( 'Video chat ASL', 'openinclusion' ),'_PreferToContactOthers[]', '_PreferToContactOthers_video_ASL'),
    array('Others_2',__( 'Other, please provide details', 'openinclusion' ),'_PreferToContactOthers[]', '_PreferToContactOthers_Others_2'),
 
 );
 
 function get_contact_methods_others() {
    global $contact_method_others_array;
    return $contact_method_others_array; 
 }
 
 $sensory_needs_array = array(
    array('deaf',__( 'D/deaf', 'openinclusion' ),'_SensoryNeeds[]', '_SensoryNeeds_deaf'),
    array('HI',__( 'Hard of hearing or hearing impaired', 'openinclusion' ),'_SensoryNeeds[]', '_SensoryNeeds_HI'),
    array('blind',__( 'Blind', 'openinclusion' ),'_SensoryNeeds[]', '_SensoryNeeds_blind'),
    array('LV',__( 'Partially sighted / low vision', 'openinclusion' ),'_SensoryNeeds[]', '_SensoryNeeds_LV'),
    array('CB',__( 'Colour perception deficiency / colour blind', 'openinclusion' ),'_SensoryNeeds[]', '_SensoryNeeds_CB'),
    array('LST',__( 'Limited or no smell or taste', 'openinclusion' ),'_SensoryNeeds[]', '_SensoryNeeds_LST'),
 );   
 
 function get_sensory_needs() {
    global $sensory_needs_array;
    return $sensory_needs_array; 
 }
 
 $physical_needs_array = array(
    array('CannotWalk',__( 'Cannot Walk', 'openinclusion' ),'_PhysicalNeeds[]', '_PhysicalNeeds_CannotWalk'),
    array('CannotWalkFar',__( 'Cannot walk far or without difficulty', 'openinclusion' ),'_PhysicalNeeds[]', '_PhysicalNeeds_CannotWalkFar'),
    array('Balance',__( 'Balance challenges', 'openinclusion' ),'_PhysicalNeeds[]', '_PhysicalNeeds_Balance'),
    array('ShortStature',__( 'Short of stature', 'openinclusion' ),'_PhysicalNeeds[]', '_PhysicalNeeds_ShortStature'),
    array('LowerLimbDifference',__( 'Lower limb difference', 'openinclusion' ),'_PhysicalNeeds[]', '_PhysicalNeeds_LowerLimbDifference'),
    array('LimitedMobility',__( 'Limited mobility (restricted movement, paralysis, muscular control etc.)', 'openinclusion' ),'_PhysicalNeeds[]', '_PhysicalNeeds_LimitedMobility'),
    array('HandUpperLimbDifference',__( 'Hand or upper limb difference', 'openinclusion' ),'_PhysicalNeeds[]', '_PhysicalNeeds_HandUpperLimbDifference'),
    array('OtherLimitedDexterity',__( 'Other limited dexterity (tremor, low grip strength, arthritis, or control etc.)', 'openinclusion' ),'_PhysicalNeeds[]', '_PhysicalNeeds_OtherLimitedDexterity'),
    array('OtherClinicallyObese',__( 'Clinically obese', 'openinclusion' ),'_PhysicalNeeds[]', '_PhysicalNeeds_OtherClinicallyObese'),
 );   
 
 function get_physical_needs() {
    global $physical_needs_array;
    return $physical_needs_array; 
 }
 
 $cognitive_and_mentalhealth_needs_array = array(
    array('Memory',__( 'Memory challenges (ongoing or fluctuating, dementia, brain fog etc.)', 'openinclusion' ),'_CognitiveAndMentalhealthNeeds[]', '_CognitiveAndMentalhealthNeeds_Memory'),
    array('FocusADHDADD',__( 'Focus challenges (ADD/ADHD)', 'openinclusion' ),'_CognitiveAndMentalhealthNeeds[]', '_CognitiveAndMentalhealthNeeds_FocusADHDADD'),
    array('DyslexiaDyspraxiaDyscalculia',__( 'Specific learning difficulty (Dyslexia, Dyspraxia or Dyscalculia)', 'openinclusion' ),'_CognitiveAndMentalhealthNeeds[]', '_CognitiveAndMentalhealthNeeds_DyslexiaDyspraxiaDyscalculia'),
    array('GeneralisedLearning',__( 'Generalised learning disabilities', 'openinclusion' ),'_CognitiveAndMentalhealthNeeds[]', '_CognitiveAndMentalhealthNeeds_GeneralisedLearning'),
    array('SocialSensoryChallenges',__( 'Social and/or sensory challenges (e.g. autism spectrum)', 'openinclusion' ),'_CognitiveAndMentalhealthNeeds[]', '_CognitiveAndMentalhealthNeeds_SocialSensoryChallenges'),
    array('Anxiety',__( 'Heightened anxiety', 'openinclusion' ),'_CognitiveAndMentalhealthNeeds[]', '_CognitiveAndMentalhealthNeeds_Anxiety'),
    array('Depression',__( 'Depression', 'openinclusion' ),'_CognitiveAndMentalhealthNeeds[]', '_CognitiveAndMentalhealthNeeds_Depression'),
    array('PTSD',__( 'Post-traumatic stress disorder', 'openinclusion' ),'_CognitiveAndMentalhealthNeeds[]', '_CognitiveAndMentalhealthNeeds_PTSD'),
    array('EatingDisorder',__( 'Eating disorders', 'openinclusion' ),'_CognitiveAndMentalhealthNeeds[]', '_CognitiveAndMentalhealthNeeds_EatingDisorder'),
    array('SubstanceAbuseAddiction',__( 'Substance abuse or other addictions', 'openinclusion' ),'_CognitiveAndMentalhealthNeeds[]', '_CognitiveAndMentalhealthNeeds_SubstanceAbuseAddiction'),
    array('OtherMentalHealth',__( 'Other mental health conditions (OCD, bipolar, personality disorders etc.)', 'openinclusion' ),'_CognitiveAndMentalhealthNeeds[]', '_CognitiveAndMentalhealthNeeds_OtherMentalHealth'),
 );   
 
 function get_cognitive_and_mentalhealth_needs() {
    global $cognitive_and_mentalhealth_needs_array;
    return $cognitive_and_mentalhealth_needs_array; 
 }
 
 $communication_needs_array = array(
    array('NonverbalAtAll',__( 'Nonverbal', 'openinclusion' ),'_CommunicationNeeds[]', '_CommunicationNeeds_NonverbalAtAll'),
    array('OcasionallyNonverbal',__( 'Occasionally nonverbal', 'openinclusion' ),'_CommunicationNeeds[]', '_CommunicationNeeds_OcasionallyNonverbal'),
    array('SpeechImpairment',__( 'Speech impairment (stutter, articulation etc.)', 'openinclusion' ),'_CommunicationNeeds[]', '_CommunicationNeeds_SpeechImpairment'),
    array('DifficultyWordRecall',__( 'Difficulty with word recall (aphasia)', 'openinclusion' ),'_CommunicationNeeds[]', '_CommunicationNeeds_DifficultyWordRecall'),
 );   
 
 function get_communication_needs() {
    global $communication_needs_array;
    return $communication_needs_array; 
 }
 
 $chronichealth_needs_array = array(
    array('ChronicPain',__( 'Chronic pain', 'openinclusion' ),'_ChronichealthNeeds[]', '_ChronichealthNeeds_ChronicPain'),
    array('HeartLungCondition',__( 'Heart or lung conditions', 'openinclusion' ),'_ChronichealthNeeds[]', '_ChronichealthNeeds_HeartLungCondition'),
    array('PostStroke',__( 'Post stroke', 'openinclusion' ),'_ChronichealthNeeds[]', '_ChronichealthNeeds_PostStroke'),
    array('Cancer',__( 'Cancer (current treatment / post treatment)', 'openinclusion' ),'_ChronichealthNeeds[]', '_ChronichealthNeeds_Cancer'),
    array('AutoImmuneDisease',__( 'Auto-immune disease (MS, Celiac, Arthritis, Crohns, diabetes etc.)', 'openinclusion' ),'_ChronichealthNeeds[]', '_ChronichealthNeeds_AutoImmuneDisease'),
    array('OtherLongTermCondition',__( 'Other long-term condition (epilepsy, ME etc.)', 'openinclusion' ),'_ChronichealthNeeds[]', '_ChronichealthNeedsNeeds_OtherLongTermCondition'),
 );   
 
 function get_chronichealth_needs() {
    global $chronichealth_needs_array;
    return $chronichealth_needs_array; 
 }
 
 $other_needs_array = array(
    array('NoneoftheAbove',__( 'None of the above', 'openinclusion' ),'_OtherNeedsNoneoftheAbove', '_OtherNeeds_NoneoftheAbove'),
    array('PreferNotToSay',__( 'Prefer not to say', 'openinclusion' ),'_OtherNeedsPreferNotToSay', '_OtherNeeds_PreferNotToSay'),
    array('OtherPleaseSpecify',__( 'Other (please specify) ' , 'openinclusion' ),'_OtherNeedsOtherPleaseSpecify', '_OtherNeeds_OtherPleaseSpecify'),
 );   
 
 function get_other_needs() {
    global $other_needs_array;
    return $other_needs_array; 
 }
 
 $phonecode_array = array(
    array('44',__( 'UK +44', 'openinclusion' ),'UK_44'),
    array('1',__( 'USA +1', 'openinclusion' ),'USA_1'),
    array('+61',__( 'Australia +61', 'openinclusion' ),'Australia_61'),
    array('+353',__( 'Ireland +353', 'openinclusion' ),'Ireland_353'),
    array('+1',__( 'Canada +1', 'openinclusion' ),'Canada_1'),    
    array('+64',__( 'NewZealand +64', 'openinclusion' ),'NewZealand_64'),
    array('+',__( 'Other', 'openinclusion' ),'Other'),
 );
 function get_phoneCodes() {
    global $phonecode_array;
    return $phonecode_array; 
 }
 
 $country_array = array(
    array('',__( 'Please select from list', 'openinclusion' ),'inf_option_country_0'),
    array('01007',__( 'UK', 'openinclusion' ),'inf_option_country_01007'),
    array('01008',__( 'USA', 'openinclusion' ),'inf_option_country_01008'),
    array('01009',__( 'Australia', 'openinclusion' ),'inf_option_country_01009'),
    array('01010',__( 'Ireland', 'openinclusion' ),'inf_option_country_01010'),
    array('01011',__( 'Canada', 'openinclusion' ),'inf_option_country_01011'),
    array('01012',__( 'NewZealand', 'openinclusion' ),'inf_option_country_01012'),
    array('Afghanistan',__( 'Afghanistan', 'openinclusion' ),'inf_option_country_Afghanistan'),
    array('Albania',__( 'Albania', 'openinclusion' ),'inf_option_country_Albania'),
    array('Algeria',__( 'Algeria', 'openinclusion' ),'inf_option_country_Algeria'),
    array('Andorra',__( 'Andorra', 'openinclusion' ),'inf_option_country_Andorra'),
    array('Angola',__( 'Angola', 'openinclusion' ),'inf_option_country_Angola'),
    array('Antigua & Deps',__( 'Antigua & Deps', 'openinclusion' ),'inf_option_country_Antigua_and_Deps'),
    array('Argentina',__( 'Argentina', 'openinclusion' ),'inf_option_country_Argentina'),
    array('Armenia',__( 'Armenia', 'openinclusion' ),'inf_option_country_Armenia'),
    array('Austria',__( 'Austria', 'openinclusion' ),'inf_option_country_Austria'),
    array('Azerbaijan',__( 'Azerbaijan', 'openinclusion' ),'inf_option_country_Azerbaijan'),
    array('Bahamas',__( 'Bahamas', 'openinclusion' ),'inf_option_country_Bahamas'),
    array('Bahrain',__( 'Bahrain', 'openinclusion' ),'inf_option_country_Bahrain'),
    array('Bangladesh',__( 'Bangladesh', 'openinclusion' ),'inf_option_country_Bangladesh'),
    array('Barbados',__( 'Barbados', 'openinclusion' ),'inf_option_country_Barbados'),
    array('Belarus',__( 'Belarus', 'openinclusion' ),'inf_option_country_Belarus'),
    array('Belgium',__( 'Belgium', 'openinclusion' ),'inf_option_country_Belgium'),
    array('Belize',__( 'Belize', 'openinclusion' ),'inf_option_country_Belize'),
    array('Benin',__( 'Benin', 'openinclusion' ),'inf_option_country_Benin'),
    array('Bhutan',__( 'Bhutan', 'openinclusion' ),'inf_option_country_Bhutan'),
    array('Bolivia',__( 'Bolivia', 'openinclusion' ),'inf_option_country_Bolivia'),
    array('Bosnia Herzegovina',__( 'Bosnia Herzegovina', 'openinclusion' ),'inf_option_country_Bosnia Herzegovina'),
    array('Botswana',__( 'Botswana', 'openinclusion' ),'inf_option_country_Botswana'),
    array('Brazil',__( 'Brazil', 'openinclusion' ),'inf_option_country_Brazil'),
    array('Brunei',__( 'Brunei', 'openinclusion' ),'inf_option_country_Brunei'),
    array('Bulgaria',__( 'Bulgaria', 'openinclusion' ),'inf_option_country_Bulgaria'),
    array('Burkina',__( 'Burkina', 'openinclusion' ),'inf_option_country_Burkina'),
    array('Burundi',__( 'Burundi', 'openinclusion' ),'inf_option_country_Burundi'),
    array('Cambodia',__( 'Cambodia', 'openinclusion' ),'inf_option_country_Cambodia'),
    array('Cameroon',__( 'Cameroon', 'openinclusion' ),'inf_option_country_Cameroon'),
    array('Cape Verde',__( 'Cape Verde', 'openinclusion' ),'inf_option_country_Cape Verde'),
    array('Central African Rep',__( 'Central African Rep', 'openinclusion' ),'inf_option_country_Central African Rep'),
    array('Chad',__( 'Chad', 'openinclusion' ),'inf_option_country_Chad'),
    array('Chile',__( 'Chile', 'openinclusion' ),'inf_option_country_Chile'),
    array('China',__( 'China', 'openinclusion' ),'inf_option_country_China'),
    array('Colombia',__( 'Colombia', 'openinclusion' ),'inf_option_country_Colombia'),
    array('Comoros',__( 'Comoros', 'openinclusion' ),'inf_option_country_Comoros'),
    array('Congo',__( 'Congo', 'openinclusion' ),'inf_option_country_Congo'),
    array('Congo (Democratic Rep)',__( 'Congo (Democratic Rep)', 'openinclusion' ),'inf_option_country_Congo _Democratic Rep_'),
    array('Costa Rica',__( 'Costa Rica', 'openinclusion' ),'inf_option_country_Costa Rica'),
    array('Croatia',__( 'Croatia', 'openinclusion' ),'inf_option_country_Croatia'),
    array('Cuba',__( 'Cuba', 'openinclusion' ),'inf_option_country_Cuba'),
    array('Cyprus',__( 'Cyprus', 'openinclusion' ),'inf_option_country_Cyprus'),
    array('Czech Republic',__( 'Czech Republic', 'openinclusion' ),'inf_option_country_Czech Republic'),
    array('Denmark',__( 'Denmark', 'openinclusion' ),'inf_option_country_Denmark'),
    array('Djibouti',__( 'Djibouti', 'openinclusion' ),'inf_option_country_Djibouti'),
    array('Dominica',__( 'Dominica', 'openinclusion' ),'inf_option_country_Dominica'),
    array('Dominican Republic',__( 'Dominican Republic', 'openinclusion' ),'inf_option_country_Dominican Republic'),
    array('East Timor',__( 'East Timor', 'openinclusion' ),'inf_option_country_East Timor'),
    array('Ecuador',__( 'Ecuador', 'openinclusion' ),'inf_option_country_Ecuador'),
    array('Egypt',__( 'Egypt', 'openinclusion' ),'inf_option_country_Egypt'),
    array('El Salvador',__( 'El Salvador', 'openinclusion' ),'inf_option_country_El Salvador'),
    array('Equatorial Guinea',__( 'Equatorial Guinea', 'openinclusion' ),'inf_option_country_Equatorial Guinea'),
    array('Eritrea',__( 'Eritrea', 'openinclusion' ),'inf_option_country_Eritrea'),
    array('Estonia',__( 'Estonia', 'openinclusion' ),'inf_option_country_Estonia'),
    array('Ethiopia',__( 'Ethiopia', 'openinclusion' ),'inf_option_country_Ethiopia'),
    array('Fiji',__( 'Fiji', 'openinclusion' ),'inf_option_country_Fiji'),
    array('Finland',__( 'Finland', 'openinclusion' ),'inf_option_country_Finland'),
    array('France',__( 'France', 'openinclusion' ),'inf_option_country_France'),
    array('Gabon',__( 'Gabon', 'openinclusion' ),'inf_option_country_Gabon'),
    array('Gambia',__( 'Gambia', 'openinclusion' ),'inf_option_country_Gambia'),
    array('Georgia',__( 'Georgia', 'openinclusion' ),'inf_option_country_Georgia'),
    array('Germany',__( 'Germany', 'openinclusion' ),'inf_option_country_Germany'),
    array('Ghana',__( 'Ghana', 'openinclusion' ),'inf_option_country_Ghana'),
    array('Greece',__( 'Greece', 'openinclusion' ),'inf_option_country_Greece'),
    array('Grenada',__( 'Grenada', 'openinclusion' ),'inf_option_country_Grenada'),
    array('Guatemala',__( 'Guatemala', 'openinclusion' ),'inf_option_country_Guatemala'),
    array('Guinea',__( 'Guinea', 'openinclusion' ),'inf_option_country_Guinea'),
    array('Guinea',__( 'Guinea', 'openinclusion' ),'inf_option_country_Guinea'),
    array('Guyana',__( 'Guyana', 'openinclusion' ),'inf_option_country_Guyana'),
    array('Haiti',__( 'Haiti', 'openinclusion' ),'inf_option_country_Haiti'),
    array('Honduras',__( 'Honduras', 'openinclusion' ),'inf_option_country_Honduras'),
    array('Hungary',__( 'Hungary', 'openinclusion' ),'inf_option_country_Hungary'),
    array('Iceland',__( 'Iceland', 'openinclusion' ),'inf_option_country_Iceland'),
    array('India',__( 'India', 'openinclusion' ),'inf_option_country_India'),
    array('Indonesia',__( 'Indonesia', 'openinclusion' ),'inf_option_country_Indonesia'),
    array('Iran',__( 'Iran', 'openinclusion' ),'inf_option_country_Iran'),
    array('Iraq',__( 'Iraq', 'openinclusion' ),'inf_option_country_Iraq'),
    array('Israel',__( 'Israel', 'openinclusion' ),'inf_option_country_Israel'),
    array('Italy',__( 'Italy', 'openinclusion' ),'inf_option_country_Italy'),
    array('Ivory Coast',__( 'Ivory Coast', 'openinclusion' ),'inf_option_country_Ivory Coast'),
    array('Jamaica',__( 'Jamaica', 'openinclusion' ),'inf_option_country_Jamaica'),
    array('Japan',__( 'Japan', 'openinclusion' ),'inf_option_country_Japan'),
    array('Jordan',__( 'Jordan', 'openinclusion' ),'inf_option_country_Jordan'),
    array('Kazakhstan',__( 'Kazakhstan', 'openinclusion' ),'inf_option_country_Kazakhstan'),
    array('Kenya',__( 'Kenya', 'openinclusion' ),'inf_option_country_Kenya'),
    array('Kiribati',__( 'Kiribati', 'openinclusion' ),'inf_option_country_Kiribati'),
    array('Korea North',__( 'Korea North', 'openinclusion' ),'inf_option_country_Korea North'),
    array('Korea South',__( 'Korea South', 'openinclusion' ),'inf_option_country_Korea South'),
    array('Kosovo',__( 'Kosovo', 'openinclusion' ),'inf_option_country_Kosovo'),
    array('Kuwait',__( 'Kuwait', 'openinclusion' ),'inf_option_country_Kuwait'),
    array('Kyrgyzstan',__( 'Kyrgyzstan', 'openinclusion' ),'inf_option_country_Kyrgyzstan'),
    array('Laos',__( 'Laos', 'openinclusion' ),'inf_option_country_Laos'),
    array('Latvia',__( 'Latvia', 'openinclusion' ),'inf_option_country_Latvia'),
    array('Lebanon',__( 'Lebanon', 'openinclusion' ),'inf_option_country_Lebanon'),
    array('Lesotho',__( 'Lesotho', 'openinclusion' ),'inf_option_country_Lesotho'),
    array('Liberia',__( 'Liberia', 'openinclusion' ),'inf_option_country_Liberia'),
    array('Libya',__( 'Libya', 'openinclusion' ),'inf_option_country_Libya'),
    array('Liechtenstein',__( 'Liechtenstein', 'openinclusion' ),'inf_option_country_Liechtenstein'),
    array('Lithuania',__( 'Lithuania', 'openinclusion' ),'inf_option_country_Lithuania'),
    array('Luxembourg',__( 'Luxembourg', 'openinclusion' ),'inf_option_country_Luxembourg'),
    array('Macedonia',__( 'Macedonia', 'openinclusion' ),'inf_option_country_Macedonia'),
    array('Madagascar',__( 'Madagascar', 'openinclusion' ),'inf_option_country_Madagascar'),
    array('Malawi',__( 'Malawi', 'openinclusion' ),'inf_option_country_Malawi'),
    array('Malaysia',__( 'Malaysia', 'openinclusion' ),'inf_option_country_Malaysia'),
    array('Maldives',__( 'Maldives', 'openinclusion' ),'inf_option_country_Maldives'),
    array('Mali',__( 'Mali', 'openinclusion' ),'inf_option_country_Mali'),
    array('Malta',__( 'Malta', 'openinclusion' ),'inf_option_country_Malta'),
    array('Marshall Islands',__( 'Marshall Islands', 'openinclusion' ),'inf_option_country_Marshall Islands'),
    array('Mauritania',__( 'Mauritania', 'openinclusion' ),'inf_option_country_Mauritania'),
    array('Mauritius',__( 'Mauritius', 'openinclusion' ),'inf_option_country_Mauritius'),
    array('Mexico',__( 'Mexico', 'openinclusion' ),'inf_option_country_Mexico'),
    array('Micronesia',__( 'Micronesia', 'openinclusion' ),'inf_option_country_Micronesia'),
    array('Moldova',__( 'Moldova', 'openinclusion' ),'inf_option_country_Moldova'),
    array('Monaco',__( 'Monaco', 'openinclusion' ),'inf_option_country_Monaco'),
    array('Mongolia',__( 'Mongolia', 'openinclusion' ),'inf_option_country_Mongolia'),
    array('Montenegro',__( 'Montenegro', 'openinclusion' ),'inf_option_country_Montenegro'),
    array('Morocco',__( 'Morocco', 'openinclusion' ),'inf_option_country_Morocco'),
    array('Mozambique',__( 'Mozambique', 'openinclusion' ),'inf_option_country_Mozambique'),
    array('Myanmar, (Burma)',__( 'Myanmar, (Burma)', 'openinclusion' ),'inf_option_country_Myanmar_Burma_'),
    array('Namibia',__( 'Namibia', 'openinclusion' ),'inf_option_country_Namibia'),
    array('Nauru',__( 'Nauru', 'openinclusion' ),'inf_option_country_Nauru'),
    array('Nepal',__( 'Nepal', 'openinclusion' ),'inf_option_country_Nepal'),
    array('Netherlands',__( 'Netherlands', 'openinclusion' ),'inf_option_country_Netherlands'),
    array('Nicaragua',__( 'Nicaragua', 'openinclusion' ),'inf_option_country_Nicaragua'),
    array('Niger',__( 'Niger', 'openinclusion' ),'inf_option_country_Niger'),
    array('Nigeria',__( 'Nigeria', 'openinclusion' ),'inf_option_country_Nigeria'),
    array('Norway',__( 'Norway', 'openinclusion' ),'inf_option_country_Norway'),
    array('Oman',__( 'Oman', 'openinclusion' ),'inf_option_country_Oman'),
    array('Pakistan',__( 'Pakistan', 'openinclusion' ),'inf_option_country_Pakistan'),
    array('Palau',__( 'Palau', 'openinclusion' ),'inf_option_country_Palau'),
    array('Panama',__( 'Panama', 'openinclusion' ),'inf_option_country_Panama'),
    array('Papua New Guinea',__( 'Papua New Guinea', 'openinclusion' ),'inf_option_country_Papua New Guinea'),
    array('Paraguay',__( 'Paraguay', 'openinclusion' ),'inf_option_country_Paraguay'),
    array('Peru',__( 'Peru', 'openinclusion' ),'inf_option_country_Peru'),
    array('Philippines',__( 'Philippines', 'openinclusion' ),'inf_option_country_Philippines'),
    array('Poland',__( 'Poland', 'openinclusion' ),'inf_option_country_Poland'),
    array('Portugal',__( 'Portugal', 'openinclusion' ),'inf_option_country_Portugal'),
    array('Qatar',__( 'Qatar', 'openinclusion' ),'inf_option_country_Qatar'),
    array('Romania',__( 'Romania', 'openinclusion' ),'inf_option_country_Romania'),
    array('Russian Federation',__( 'Russian Federation', 'openinclusion' ),'inf_option_country_Russian Federation'),
    array('Rwanda',__( 'Rwanda', 'openinclusion' ),'inf_option_country_Rwanda'),
    array('St Kitts & Nevis',__( 'St Kitts & Nevis', 'openinclusion' ),'inf_option_country_St Kitts_and_Nevis'),
    array('St Lucia',__( 'St Lucia', 'openinclusion' ),'inf_option_country_St Lucia'),
    array('Saint Vincent & the Grenadines',__( 'Saint Vincent & the Grenadines', 'openinclusion' ),'inf_option_country_Saint Vincent_and_the Grenadines'),
    array('Samoa',__( 'Samoa', 'openinclusion' ),'inf_option_country_Samoa'),
    array('San Marino',__( 'San Marino', 'openinclusion' ),'inf_option_country_San Marino'),
    array('Sao Tome & Principe',__( 'Sao Tome & Principe', 'openinclusion' ),'inf_option_country_Sao Tome_and_Principe'),
    array('Saudi Arabia',__( 'Saudi Arabia', 'openinclusion' ),'inf_option_country_Saudi Arabia'),
    array('Senegal',__( 'Senegal', 'openinclusion' ),'inf_option_country_Senegal'),
    array('Serbia',__( 'Serbia', 'openinclusion' ),'inf_option_country_Serbia'),
    array('Seychelles',__( 'Seychelles', 'openinclusion' ),'inf_option_country_Seychelles'),
    array('Sierra Leone',__( 'Sierra Leone', 'openinclusion' ),'inf_option_country_Sierra Leone'),
    array('Singapore',__( 'Singapore', 'openinclusion' ),'inf_option_country_Singapore'),
    array('Slovakia',__( 'Slovakia', 'openinclusion' ),'inf_option_country_Slovakia'),
    array('Slovenia',__( 'Slovenia', 'openinclusion' ),'inf_option_country_Slovenia'),
    array('Solomon Islands',__( 'Solomon Islands', 'openinclusion' ),'inf_option_country_Solomon Islands'),
    array('Somalia',__( 'Somalia', 'openinclusion' ),'inf_option_country_Somalia'),
    array('South Africa',__( 'South Africa', 'openinclusion' ),'inf_option_country_South Africa'),
    array('South Sudan',__( 'South Sudan', 'openinclusion' ),'inf_option_country_South Sudan'),
    array('Spain',__( 'Spain', 'openinclusion' ),'inf_option_country_Spain'),
    array('Sri Lanka',__( 'Sri Lanka', 'openinclusion' ),'inf_option_country_Sri Lanka'),
    array('Sudan',__( 'Sudan', 'openinclusion' ),'inf_option_country_Sudan'),
    array('Suriname',__( 'Suriname', 'openinclusion' ),'inf_option_country_Suriname'),
    array('Swaziland',__( 'Swaziland', 'openinclusion' ),'inf_option_country_Swaziland'),
    array('Sweden',__( 'Sweden', 'openinclusion' ),'inf_option_country_Sweden'),
    array('Switzerland',__( 'Switzerland', 'openinclusion' ),'inf_option_country_Switzerland'),
    array('Syria',__( 'Syria', 'openinclusion' ),'inf_option_country_Syria'),
    array('Taiwan',__( 'Taiwan', 'openinclusion' ),'inf_option_country_Taiwan'),
    array('Tajikistan',__( 'Tajikistan', 'openinclusion' ),'inf_option_country_Tajikistan'),
    array('Tanzania',__( 'Tanzania', 'openinclusion' ),'inf_option_country_Tanzania'),
    array('Thailand',__( 'Thailand', 'openinclusion' ),'inf_option_country_Thailand'),
    array('Togo',__( 'Togo', 'openinclusion' ),'inf_option_country_Togo'),
    array('Tonga',__( 'Tonga', 'openinclusion' ),'inf_option_country_Tonga'),
    array('Trinidad & Tobago',__( 'Trinidad & Tobago', 'openinclusion' ),'inf_option_country_Trinidad_and_Tobago'),
    array('Tunisia',__( 'Tunisia', 'openinclusion' ),'inf_option_country_Tunisia'),
    array('Turkey',__( 'Turkey', 'openinclusion' ),'inf_option_country_Turkey'),
    array('Turkmenistan',__( 'Turkmenistan', 'openinclusion' ),'inf_option_country_Turkmenistan'),
    array('Tuvalu',__( 'Tuvalu', 'openinclusion' ),'inf_option_country_Tuvalu'),
    array('Uganda',__( 'Uganda', 'openinclusion' ),'inf_option_country_Uganda'),
    array('Ukraine',__( 'Ukraine', 'openinclusion' ),'inf_option_country_Ukraine'),
    array('United Arab Emirates',__( 'United Arab Emirates', 'openinclusion' ),'inf_option_country_United Arab Emirates'),
    array('Uruguay',__( 'Uruguay', 'openinclusion' ),'inf_option_country_Uruguay'),
    array('Uzbekistan',__( 'Uzbekistan', 'openinclusion' ),'inf_option_country_Uzbekistan'),
    array('Vanuatu',__( 'Vanuatu', 'openinclusion' ),'inf_option_country_Vanuatu'),
    array('Vatican City',__( 'Vatican City', 'openinclusion' ),'inf_option_country_Vatican City'),
    array('Venezuela',__( 'Venezuela', 'openinclusion' ),'inf_option_country_Venezuela'),
    array('Vietnam',__( 'Vietnam', 'openinclusion' ),'inf_option_country_Vietnam'),
    array('Yemen',__( 'Yemen', 'openinclusion' ),'inf_option_country_Yemen'),
    array('Zambia',__( 'Zambia', 'openinclusion' ),'inf_option_country_Zambia'),
    array('Zimbabwe',__( 'Zimbabwe', 'openinclusion' ),'inf_option_country_Zimbabwe'),
      
 );
 function get_countries() {
    global $country_array;
    return $country_array; 
 }
 
 $region_array = array(
    array('',__( 'Please select from list', 'openinclusion' ),'inf_option_country_0'),
    array('01101-Community-Region-UK-London',__( 'London', 'openinclusion'),'inf_option_Whatregiondoyoulivein_01101_Community_Region_UK_London','UK'),
    array('01102-Community-Region-UK-SouthEast',__( 'SouthEast', 'openinclusion'),'inf_option_Whatregiondoyoulivein_01102_Community_Region_UK_SouthEast','UK'),
    array('01103-Community-Region-UK-SouthWest',__( 'SouthWest', 'openinclusion'),'inf_option_Whatregiondoyoulivein_01103_Community_Region_UK_SouthWest','UK'),
    array('01104-Community-Region-UK-EastEng',__( 'EastEng', 'openinclusion'),'inf_option_Whatregiondoyoulivein_01104_Community_Region_UK_EastEng','UK'),
    array('01105-Community-Region-UK-EastMidlands',__( 'EastMidlands', 'openinclusion'),'inf_option_Whatregiondoyoulivein_01105_Community_Region_UK_EastMidlands','UK'),
    array('01106-Community-Region-UK-WestMidlands',__( 'WestMidlands', 'openinclusion'),'inf_option_Whatregiondoyoulivein_01106_Community_Region_UK_WestMidlands','UK'),
    array('01107-Community-Region-UK-YorksHumber',__( 'YorksHumber', 'openinclusion'),'inf_option_Whatregiondoyoulivein_01107_Community_Region_UK_YorksHumber','UK'),
    array('01108-Community-Region-UK-NorthEast',__( 'NorthEast', 'openinclusion'),'inf_option_Whatregiondoyoulivein_01108_Community_Region_UK_NorthEast','UK'),
    array('01109-Community-Region-UK-NorthWest',__( 'NorthWest', 'openinclusion'),'inf_option_Whatregiondoyoulivein_01109_Community_Region_UK_NorthWest','UK'),
    array('01110-Community-Region-UK-Scotland',__( 'Scotland', 'openinclusion'),'inf_option_Whatregiondoyoulivein_01110_Community_Region_UK_Scotland','UK'),
    array('01111-Community-Region-UK-Wales',__( 'Wales', 'openinclusion'),'inf_option_Whatregiondoyoulivein_01111_Community_Region_UK_Wales','UK'),
    array('01112-Community-Region-UK-Nireland',__( 'Nireland', 'openinclusion'),'inf_option_Whatregiondoyoulivein_01112_Community_Region_UK_Nireland','UK'),
    array('01113-Community-Region-USA-AL',__( 'Alabama', 'openinclusion'),'inf_option_Whatregiondoyoulivein_01113_Community_Region_USA_AL','USA'),
    array('01113-Community-Region-USA-AK',__( 'Alaska', 'openinclusion'),'inf_option_Whatregiondoyoulivein_01113_Community_Region_USA_AK','USA'),
    array('01113-Community-Region-USA-AZ',__( 'Arizona', 'openinclusion'),'inf_option_Whatregiondoyoulivein_01113_Community_Region_USA_AZ','USA'),
    array('01113-Community-Region-USA-AR',__( 'Arkansas', 'openinclusion'),'inf_option_Whatregiondoyoulivein_01113_Community_Region_USA_AR','USA'),
    array('01113-Community-Region-USA-CA',__( 'California', 'openinclusion'),'inf_option_Whatregiondoyoulivein_01113_Community_Region_USA_CA','USA'),
    array('01113-Community-Region-USA-CO',__( 'Colorado', 'openinclusion'),'inf_option_Whatregiondoyoulivein_01113_Community_Region_USA_CO','USA'),
    array('01113-Community-Region-USA-CT',__( 'Connecticut', 'openinclusion'),'inf_option_Whatregiondoyoulivein_01113_Community_Region_USA_CT','USA'),
    array('01113-Community-Region-USA-DE',__( 'Delaware', 'openinclusion'),'inf_option_Whatregiondoyoulivein_01113_Community_Region_USA_DE','USA'),
    array('01113-Community-Region-USA-FL',__( 'Florida', 'openinclusion'),'inf_option_Whatregiondoyoulivein_01113_Community_Region_USA_FL','USA'),
    array('01113-Community-Region-USA-GA',__( 'Georgia', 'openinclusion'),'inf_option_Whatregiondoyoulivein_01113_Community_Region_USA_GA','USA'),
    array('01113-Community-Region-USA-HI',__( 'Hawaii', 'openinclusion'),'inf_option_Whatregiondoyoulivein_01113_Community_Region_USA_HI','USA'),
    array('01113-Community-Region-USA-ID',__( 'Idaho', 'openinclusion'),'inf_option_Whatregiondoyoulivein_01113_Community_Region_USA_ID','USA'),
    array('01113-Community-Region-USA-IL',__( 'Illinois', 'openinclusion'),'inf_option_Whatregiondoyoulivein_01113_Community_Region_USA_IL','USA'),
    array('01113-Community-Region-USA-IN',__( 'Indiana', 'openinclusion'),'inf_option_Whatregiondoyoulivein_01113_Community_Region_USA_IN','USA'),
    array('01113-Community-Region-USA-IA',__( 'Iowa', 'openinclusion'),'inf_option_Whatregiondoyoulivein_01113_Community_Region_USA_IA','USA'),
    array('01113-Community-Region-USA-KS',__( 'Kansas', 'openinclusion'),'inf_option_Whatregiondoyoulivein_01113_Community_Region_USA_KS','USA'),
    array('01113-Community-Region-USA-KY',__( 'Kentucky', 'openinclusion'),'inf_option_Whatregiondoyoulivein_01113_Community_Region_USA_KY','USA'),
    array('01113-Community-Region-USA-LA',__( 'Louisiana', 'openinclusion'),'inf_option_Whatregiondoyoulivein_01113_Community_Region_USA_LA','USA'),
    array('01113-Community-Region-USA-ME',__( 'Maine', 'openinclusion'),'inf_option_Whatregiondoyoulivein_01113_Community_Region_USA_ME','USA'),
    array('01113-Community-Region-USA-MD',__( 'Maryland', 'openinclusion'),'inf_option_Whatregiondoyoulivein_01113_Community_Region_USA_MD','USA'),
    array('01113-Community-Region-USA-MA',__( 'Massachusetts', 'openinclusion'),'inf_option_Whatregiondoyoulivein_01113_Community_Region_USA_MA','USA'),
    array('01113-Community-Region-USA-MI',__( 'Michigan', 'openinclusion'),'inf_option_Whatregiondoyoulivein_01113_Community_Region_USA_MI','USA'),
    array('01113-Community-Region-USA-MN',__( 'Minnesota', 'openinclusion'),'inf_option_Whatregiondoyoulivein_01113_Community_Region_USA_MN','USA'),
    array('01113-Community-Region-USA-MS',__( 'Mississippi', 'openinclusion'),'inf_option_Whatregiondoyoulivein_01113_Community_Region_USA_MS','USA'),
    array('01113-Community-Region-USA-MO',__( 'Missouri', 'openinclusion'),'inf_option_Whatregiondoyoulivein_01113_Community_Region_USA_MO','USA'),
    array('01113-Community-Region-USA-MT',__( 'Montana', 'openinclusion'),'inf_option_Whatregiondoyoulivein_01113_Community_Region_USA_MT','USA'),
    array('01113-Community-Region-USA-NE',__( 'Nebraska', 'openinclusion'),'inf_option_Whatregiondoyoulivein_01113_Community_Region_USA_NE','USA'),
    array('01113-Community-Region-USA-NV',__( 'Nevada', 'openinclusion'),'inf_option_Whatregiondoyoulivein_01113_Community_Region_USA_NV','USA'),
    array('01113-Community-Region-USA-NH',__( 'New Hampshire', 'openinclusion'),'inf_option_Whatregiondoyoulivein_01113_Community_Region_USA_NH','USA'),
    array('01113-Community-Region-USA-NJ',__( 'New Jersey', 'openinclusion'),'inf_option_Whatregiondoyoulivein_01113_Community_Region_USA_NJ','USA'),
    array('01113-Community-Region-USA-NM',__( 'New Mexico', 'openinclusion'),'inf_option_Whatregiondoyoulivein_01113_Community_Region_USA_NM','USA'),
    array('01113-Community-Region-USA-NY',__( 'New York', 'openinclusion'),'inf_option_Whatregiondoyoulivein_01113_Community_Region_USA_NY','USA'),
    array('01113-Community-Region-USA-NC',__( 'North Carolina', 'openinclusion'),'inf_option_Whatregiondoyoulivein_01113_Community_Region_USA_NC','USA'),
    array('01113-Community-Region-USA-ND',__( 'North Dakota', 'openinclusion'),'inf_option_Whatregiondoyoulivein_01113_Community_Region_USA_ND','USA'),
    array('01113-Community-Region-USA-OH',__( 'Ohio', 'openinclusion'),'inf_option_Whatregiondoyoulivein_01113_Community_Region_USA_OH','USA'),
    array('01113-Community-Region-USA-OK',__( 'Oklahoma', 'openinclusion'),'inf_option_Whatregiondoyoulivein_01113_Community_Region_USA_OK','USA'),
    array('01113-Community-Region-USA-OR',__( 'Oregon', 'openinclusion'),'inf_option_Whatregiondoyoulivein_01113_Community_Region_USA_OR','USA'),
    array('01113-Community-Region-USA-PA',__( 'Pennsylvania', 'openinclusion'),'inf_option_Whatregiondoyoulivein_01113_Community_Region_USA_PA','USA'),
    array('01113-Community-Region-USA-RI',__( 'Rhode Island', 'openinclusion'),'inf_option_Whatregiondoyoulivein_01113_Community_Region_USA_RI','USA'),
    array('01113-Community-Region-USA-SC',__( 'South Carolina', 'openinclusion'),'inf_option_Whatregiondoyoulivein_01113_Community_Region_USA_SC','USA'),
    array('01113-Community-Region-USA-SD',__( 'South Dakota', 'openinclusion'),'inf_option_Whatregiondoyoulivein_01113_Community_Region_USA_SD','USA'),
    array('01113-Community-Region-USA-TN',__( 'Tennessee', 'openinclusion'),'inf_option_Whatregiondoyoulivein_01113_Community_Region_USA_TN','USA'),
    array('01113-Community-Region-USA-TX',__( 'Texas', 'openinclusion'),'inf_option_Whatregiondoyoulivein_01113_Community_Region_USA_TX','USA'),
    array('01113-Community-Region-USA-UT',__( 'Utah', 'openinclusion'),'inf_option_Whatregiondoyoulivein_01113_Community_Region_USA_UT','USA'),
    array('01113-Community-Region-USA-VT',__( 'Vermont', 'openinclusion'),'inf_option_Whatregiondoyoulivein_01113_Community_Region_USA_VT','USA'),
    array('01113-Community-Region-USA-VA',__( 'Virginia', 'openinclusion'),'inf_option_Whatregiondoyoulivein_01113_Community_Region_USA_VA','USA'),
    array('01113-Community-Region-USA-WA',__( 'Washington', 'openinclusion'),'inf_option_Whatregiondoyoulivein_01113_Community_Region_USA_WA','USA'),
    array('01113-Community-Region-USA-WV',__( 'West Virginia', 'openinclusion'),'inf_option_Whatregiondoyoulivein_01113_Community_Region_USA_WV','USA'),
    array('01113-Community-Region-USA-WI',__( 'Wisconsin', 'openinclusion'),'inf_option_Whatregiondoyoulivein_01113_Community_Region_USA_WI','USA'),
    array('01113-Community-Region-USA-WY',__( 'Wyoming', 'openinclusion'),'inf_option_Whatregiondoyoulivein_01113_Community_Region_USA_WY','USA'),
    array('01113-Community-Region-USA-DC',__( 'Washington DC', 'openinclusion'),'inf_option_Whatregiondoyoulivein_01113_Community_Region_USA_DC','USA'),
    array('011xx-Community-Region-Australia-ACT',__( 'Australian Capital Territory', 'openinclusion'),'inf_option_Whatregiondoyoulivein_011xx_Community_Region_Australia_ACT','Australia'),
    array('011xx-Community-Region-Australia-NSW',__( 'New South Wales', 'openinclusion'),'inf_option_Whatregiondoyoulivein_011xx_Community_Region_Australia_NSW','Australia'),
    array('011xx-Community-Region-Australia-NT',__( 'Northern Territory', 'openinclusion'),'inf_option_Whatregiondoyoulivein_011xx_Community_Region_Australia_NT','Australia'),
    array('011xx-Community-Region-Australia-QLD',__( 'Queensland', 'openinclusion'),'inf_option_Whatregiondoyoulivein_011xx_Community_Region_Australia_QLD','Australia'),
    array('011xx-Community-Region-Australia-SA',__( 'South Australia', 'openinclusion'),'inf_option_Whatregiondoyoulivein_011xx_Community_Region_Australia_SA','Australia'),
    array('011xx-Community-Region-Australia-TAS',__( 'Tasmania', 'openinclusion'),'inf_option_Whatregiondoyoulivein_011xx_Community_Region_Australia_TAS','Australia'),
    array('011xx-Community-Region-Australia-VIC',__( 'Victoria', 'openinclusion'),'inf_option_Whatregiondoyoulivein_011xx_Community_Region_Australia_VIC','Australia'),
    array('011xx-Community-Region-Australia-WA',__( 'Western Australia', 'openinclusion'),'inf_option_Whatregiondoyoulivein_011xx_Community_Region_Australia_WA','Australia'),
    array('011xx-Community-Region-Ireland-Leinster',__( 'Leinster', 'openinclusion'),'inf_option_Whatregiondoyoulivein_011xx_Community_Region_Ireland_Leinster','Ireland'),
    array('011xx-Community-Region-Ireland-Ulster',__( 'Ulster', 'openinclusion'),'inf_option_Whatregiondoyoulivein_011xx_Community_Region_Ireland_Ulster','Ireland'),
    array('011xx-Community-Region-Ireland-Munster',__( 'Munster', 'openinclusion'),'inf_option_Whatregiondoyoulivein_011xx_Community_Region_Ireland_Munster','Ireland'),
    array('011xx-Community-Region-Ireland-Connacht',__( 'Connacht', 'openinclusion'),'inf_option_Whatregiondoyoulivein_011xx_Community_Region_Ireland_Connacht','Ireland'),
    array('011xx-Community-Region-Canada-AB',__( 'Alberta', 'openinclusion'),'inf_option_Whatregiondoyoulivein_011xx_Community_Region_Canada_AB','Canada'),
    array('011xx-Community-Region-Canada-BC',__( 'British Columbia', 'openinclusion'),'inf_option_Whatregiondoyoulivein_011xx_Community_Region_Canada_BC','Canada'),
    array('011xx-Community-Region-Canada-MB',__( 'Manitoba', 'openinclusion'),'inf_option_Whatregiondoyoulivein_011xx_Community_Region_Canada_MB','Canada'),
    array('011xx-Community-Region-Canada-NB',__( 'New Brunswick', 'openinclusion'),'inf_option_Whatregiondoyoulivein_011xx_Community_Region_Canada_NB','Canada'),
    array('011xx-Community-Region-Canada-NL',__( 'Newfoundland And Labrador ', 'openinclusion'),'inf_option_Whatregiondoyoulivein_011xx_Community_Region_Canada_NL','Canada'),
    array('011xx-Community-Region-Canada-NT',__( 'Northwest Territories', 'openinclusion'),'inf_option_Whatregiondoyoulivein_011xx_Community_Region_Canada_NT','Canada'),
    array('011xx-Community-Region-Canada-NS',__( 'Nova Scotia', 'openinclusion'),'inf_option_Whatregiondoyoulivein_011xx_Community_Region_Canada_NS','Canada'),
    array('011xx-Community-Region-Canada-NU',__( 'Nunavut', 'openinclusion'),'inf_option_Whatregiondoyoulivein_011xx_Community_Region_Canada_NU','Canada'),
    array('011xx-Community-Region-Canada-ON',__( 'Ontario', 'openinclusion'),'inf_option_Whatregiondoyoulivein_011xx_Community_Region_Canada_ON','Canada'),
    array('011xx-Community-Region-Canada-PE',__( 'Prince Edward Island', 'openinclusion'),'inf_option_Whatregiondoyoulivein_011xx_Community_Region_Canada_PE','Canada'),
    array('011xx-Community-Region-Canada-QC',__( 'Quebec', 'openinclusion'),'inf_option_Whatregiondoyoulivein_011xx_Community_Region_Canada_QC','Canada'),
    array('011xx-Community-Region-Canada-SK',__( 'Saskatchewan', 'openinclusion'),'inf_option_Whatregiondoyoulivein_011xx_Community_Region_Canada_SK','Canada'),
    array('011xx-Community-Region-Canada-YT',__( 'Yukon ', 'openinclusion'),'inf_option_Whatregiondoyoulivein_011xx_Community_Region_Canada_YT','Canada'),
    array('011xx-Community-Region-NewZealand-Auckland',__( 'Auckland', 'openinclusion'),'inf_option_Whatregiondoyoulivein_011xx_Community_Region_NewZealand_Auckland','NewZealand'),
    array('011xx-Community-Region-NewZealand-NewPlymouth',__( 'NewPlymouth', 'openinclusion'),'inf_option_Whatregiondoyoulivein_011xx_Community_Region_NewZealand_NewPlymouth','NewZealand'),
    array('011xx-Community-Region-NewZealand-Wellington',__( 'Wellington', 'openinclusion'),'inf_option_Whatregiondoyoulivein_011xx_Community_Region_NewZealand_Wellington','NewZealand'),
    array('011xx-Community-Region-NewZealand-Nelson',__( 'Nelson', 'openinclusion'),'inf_option_Whatregiondoyoulivein_011xx_Community_Region_NewZealand_Nelson','NewZealand'),
    array('011xx-Community-Region-NewZealand-Canterbury',__( 'Canterbury', 'openinclusion'),'inf_option_Whatregiondoyoulivein_011xx_Community_Region_NewZealand_Canterbury','NewZealand'),
    array('011xx-Community-Region-NewZealand-Otago',__( 'Otago', 'openinclusion'),'inf_option_Whatregiondoyoulivein_011xx_Community_Region_NewZealand_Otago','NewZealand'),
    array('Other',__( 'Not Applicable', 'openinclusion'),'inf_option_Whatregiondoyoulivein_011xx_Community_Region_NA','NA'),
 );
 function get_regions() {
    global $region_array;
    return $region_array; 
 }
 
 $gender_array = array(
    array('507',__( 'Female', 'openinclusion' ),'inf_option_Gender_507'),
    array('505',__( 'Male', 'openinclusion' ),'inf_option_Gender_505'),
    array('782',__( 'Non-binary', 'openinclusion' ),'inf_option_Gender_782'),
    array('783',__( 'Transgender man', 'openinclusion' ),'inf_option_Gender_783'),
    array('784',__( 'Transgender woman', 'openinclusion' ),'inf_option_Gender_784'),
    array('776',__( 'Let me type my own answer', 'openinclusion' ),'inf_option_Gender_776','inf_option_Gender_opentext',"Please enter your gender"),
    array('774',__( 'Would prefer not to say', 'openinclusion' ),'inf_option_Gender_774'),
 );
 
 function get_genders() {
    global $gender_array;
    return $gender_array; 
 }
 
 
    //array('511',__( 'Asian or British Asian', 'openinclusion' ),'inf_option_Ethnicity_511'),
    //array('515',__( 'Chinese or other ethnic group', 'openinclusion' ),'inf_option_Ethnicity_515'),
 
 $ethnicity_array = array(
    array('509',__( 'White', 'openinclusion' ),'inf_option_Ethnicity_509'),
    array('1248',__( 'South Asian or British South Asian', 'openinclusion' ),'1248-Panel-Ethnicity-South-Asian'),
    array('1248',__( 'East Asian or British East Asian', 'openinclusion' ),'1249-Panel-Ethnicity-East-Asian'),
    array('1248',__( 'Middle Eastern or British Middle Eastern', 'openinclusion' ),'1250-Panel-Ethnicity-Middle-Eastern'),
    array('513',__( 'Black, African, Caribbean or Black British', 'openinclusion' ),'inf_option_Ethnicity_513'),
    array('517',__( 'Mixed', 'openinclusion' ),'inf_option_Ethnicity_517'),
    array('1240',__( 'Other ethnic group', 'openinclusion' ),'1240-Panel-Ethnicity-Other'),
 );
 function get_ethnicities() {
    global $ethnicity_array;
    return $ethnicity_array; 
 }
 $conditions_array = array(
    array('519',__( 'Just getting older', 'openinclusion' ),'inf_option_Justgettingolder'),
    array('521',__( 'Partially sighted', 'openinclusion' ),'inf_option_Partiallysighted'),
    array('523',__( 'Blind with some useful vision', 'openinclusion' ),'inf_option_Blindwithsomeusefulvision'),
    array('525',__( 'Blind without useful vision', 'openinclusion' ),'inf_option_Blindwithoutusefulvision'),
    array('527',__( 'Deaf', 'openinclusion' ),'inf_option_Deaf'),
    array('529',__( 'Hard of hearing', 'openinclusion' ),'inf_option_Hardofhearing'),
    array('531',__( 'Mobility impaired', 'openinclusion' ),'inf_option_Mobilityimpaired'),
    array('533',__( 'Manual dexterity difficulties', 'openinclusion' ),'inf_option_Manualdexteritydifficulties'),
    array('535',__( 'Speech impaired', 'openinclusion' ),'inf_option_Speechimpaired'),
    array('537',__( 'Learning difficulties / disability', 'openinclusion' ),'inf_option_Learningdifficultiesdisability'),
    array('539',__( 'Cognitive impaired or Learn differently', 'openinclusion' ),'inf_option_Cognitiveloss'),
    array('541',__( 'Dyslexia', 'openinclusion' ),'inf_option_Dyslexia'),
    array('543',__( 'Mental health issues or ADHD/ASD', 'openinclusion' ),'inf_option_Mentalhealthissues'),
    array('545',__( 'Colour blind', 'openinclusion' ),'inf_option_Colourblind'),
    array('547',__( 'Left handed', 'openinclusion' ),'inf_option_Lefthanded'),
    array('549',__( 'Under 4 feet 11 inches tall ', 'openinclusion' ),'inf_option_Under49'),
    array('551',__( 'Over 6 feet 2 inches tall', 'openinclusion' ),'inf_option_Over66'),
 );
 function get_conditions() {
    global $conditions_array;
    return $conditions_array; 
 }
 $supports_array = array(
    array('553',__( 'Manual wheelchair user', 'openinclusion' ),'inf_option_Manualwheelchairuser'),
    array('555',__( 'Powered wheelchair user', 'openinclusion' ),'inf_option_Poweredwheelchairuser'),
    array('557',__( 'Mobility scooter user', 'openinclusion' ),'inf_option_Mobilityscooteruser'),
    array('559',__( 'Assistance dog user', 'openinclusion' ),'inf_option_Assistancedoguser'),
    array('561',__( 'Hearing aid user', 'openinclusion' ),'inf_option_Hearingaiduser'),
    array('563',__( 'Induction loop', 'openinclusion' ),'inf_option_Inductionloop'),
    array('565',__( 'BSL', 'openinclusion' ),'inf_option_BSL'),
    array('567',__( 'Level / ramped access', 'openinclusion' ),'inf_option_Levelrampedaccess'),
    array('569',__( 'Accessible parking', 'openinclusion' ),'inf_option_Accessibleparking'),
    array('571',__( 'Braille', 'openinclusion' ),'inf_option_Braille'),
    array('573',__( 'Large print', 'openinclusion' ),'inf_option_Largeprint'),
    array('575',__( 'Assistive technology on your computer / tablet / phone', 'openinclusion' ),'inf_option_Assistivetechnologyonyourcomputertabletphone'),
    array('577',__( 'PA / carer', 'openinclusion' ),'inf_option_PAcarer'),
 );
 function get_supports() {
    global $supports_array;
    return $supports_array; 
 }
 
 $legals_array = array(
    array('1206',__( 'I consent to be notified about community events, wider opportunities and discussions moderated by Open Inclusion.', 'openinclusion' ),'_consent[]','inf_option_consenttobenotified'),
    array('1203',__( 'I understand there is no obligation to participate or accept work opportunities offered and that I can ask to be removed from the research panel anytime.', 'openinclusion' ),'_consent[]','inf_option_noobligation'),
    array('1204',__( 'I understand that I am responsible for any tax I may be liable for on incentive payments I receive from Open inclusion Limited.', 'openinclusion' ),'_consent[]','inf_option_responsibletax'),
    array('1207',__( 'I agree to abide by the rules of being honest and respectful when using the Open Inclusion Community website, surveys, and forums. See terms and conditions here.', 'openinclusion' ),'_consent[]','inf_option_agreetorules'),
    
 );
 function get_legals() {
    global $legals_array;
    return $legals_array; 
 }
 
 $days_array = array(
    array('1',__( 'Monday', 'openinclusion' )),
    array('2',__( 'Tuesday', 'openinclusion' )),
    array('3',__( 'Wednesday', 'openinclusion' )),
    array('4',__( 'Thursday', 'openinclusion' )),
    array('5',__( 'Friday', 'openinclusion' )),
    array('6',__( 'Saturday', 'openinclusion' )),
    array('7',__( 'Sunday', 'openinclusion' )),
 );
 function get_days_array() {
    global $days_array;
    
    return $days_array;
 }
 
 $pnagegroups_array = array(
    array('',__( 'Please select age bracket', 'openinclusion' ),'inf_option_AgeIncurredPrimaryNeed_'),
    array('01014',__( '0-7', 'openinclusion' ),'inf_option_AgeIncurredPrimaryNeed_0_7'),
    array('01015',__( '8-14', 'openinclusion' ),'inf_option_AgeIncurredPrimaryNeed_8_14'),
    array('01016',__( '15-25', 'openinclusion' ),'inf_option_AgeIncurredPrimaryNeed_15_25'),
    array('01017',__( '26-40', 'openinclusion' ),'inf_option_AgeIncurredPrimaryNeed_26_40'),
    array('01018',__( '41-60', 'openinclusion' ),'inf_option_AgeIncurredPrimaryNeed_41_60'),
    array('01019',__( '61-70', 'openinclusion' ),'inf_option_AgeIncurredPrimaryNeed_61_70'),
    array('01020',__( '71-80', 'openinclusion' ),'inf_option_AgeIncurredPrimaryNeed_71_80'),
    array('01021',__( '81+', 'openinclusion' ),'inf_option_AgeIncurredPrimaryNeed_81_plus'),
 );
 
 function get_pnagegroups() {
    global $pnagegroups_array;
    return $pnagegroups_array;
 }
 
 $digitalandscreentechnologies_array = array(
    array('ScreenReader',__( 'Screen reader software (VoiceOver, TalkBack, JAWS, NVDA etc.)', 'openinclusion' ),'_DigitalandScreenTechnologies[]', '_DigitalandScreenTechnologies_ScreenReader'),
    array('ScreenMagnifier',__( 'Screen magnification (e.g. ZoomText)', 'openinclusion' ),'_DigitalandScreenTechnologies[]', '_DigitalandScreenTechnologies_ScreenMagnifier'),
    array('Textresizedigital',__( 'Resizing / enlargement of text', 'openinclusion' ),'_DigitalandScreenTechnologies[]', '_DigitalandScreenTechnologies_Textresizedigital'),
    array('ColourChangesandContrast',__( 'High contrast mode, dark mode, or other colour changes', 'openinclusion' ),'_DigitalandScreenTechnologies[]', '_DigitalandScreenTechnologies_ColourChangesandContrast'),
    array('BrailleDisplay',__( 'Refreshable Braille device', 'openinclusion' ),'_DigitalandScreenTechnologies[]', '_DigitalandScreenTechnologies_BrailleDisplay'),
    array('AudioDescription',__( 'Audio description', 'openinclusion' ),'_DigitalandScreenTechnologies[]', '_DigitalandScreenTechnologies_AudioDescription'),
    array('Dragonandother',__( 'Speech recognition software (Dragon, Braina, Voice Finger etc.)', 'openinclusion' ),'_DigitalandScreenTechnologies[]', '_DigitalandScreenTechnologies_Dragonandother'),
    array('MainstreamVoiceAssistants',__( 'Mainstream voice assistants (Siri, Alexa, Cortana, Google Assistant)', 'openinclusion' ),'_DigitalandScreenTechnologies[]', '_DigitalandScreenTechnologies_MainstreamVoiceAssistants'),
    array('ReadAloudSoftware',__( 'Read aloud software (text to speech such as Read&Write, Natural Reader, Speechify, TextHelp)', 'openinclusion' ),'_DigitalandScreenTechnologies[]', '_DigitalandScreenTechnologies_ReadAloudSoftware'),
    array('ClosedCaptionsSubtitles',__( 'Closed captions / subtitles for audio content', 'openinclusion' ),'_DigitalandScreenTechnologies[]', '_DigitalandScreenTechnologies_ClosedCaptionsSubtitles'),
    array('TDDTTY',__( 'Telecommunications device for the deaf (TDD) or teletypewriter (TTY)', 'openinclusion' ),'_DigitalandScreenTechnologies[]', '_DigitalandScreenTechnologies_TDDTTY'),
    array('AlternativeKeyboard',__( 'Alternative keyboard', 'openinclusion' ),'_DigitalandScreenTechnologies[]', '_DigitalandScreenTechnologies_AlternativeKeyboard'),
    array('AlternativeMouseStylus',__( 'Alternative mouse or stylus', 'openinclusion' ),'_DigitalandScreenTechnologies[]', '_DigitalandScreenTechnologies_AlternativeMouseStylus'),
    array('AlternativeTouchscreenInteraction',__( 'Alternative touch screen interaction (doesn\'t use forefinger / thumb)', 'openinclusion' ),'_DigitalandScreenTechnologies[]', '_DigitalandScreenTechnologies_AlternativeTouchscreenInteraction'),
    array('SwitchNavigation',__( 'Switch navigation', 'openinclusion' ),'_DigitalandScreenTechnologies[]', '_DigitalandScreenTechnologies_SwitchNavigation'),
    array('JoystickTrackball',__( 'Joystick or trackball', 'openinclusion' ),'_DigitalandScreenTechnologies[]', '_DigitalandScreenTechnologies_JoystickTrackball'),
    array('HeadPointerMouthStickEyeTracking',__( 'Head pointers, mouth stick or eye tracking', 'openinclusion' ),'_DigitalandScreenTechnologies[]', '_DigitalandScreenTechnologies_HeadPointerMouthStickEyeTracking'),
    array('NoiseCancellationHeadphones',__( 'Noise cancellation headphones used for sensory sound control', 'openinclusion' ),'_DigitalandScreenTechnologies[]', '_DigitalandScreenTechnologies_NoiseCancellationHeadphones'),
    array('NoiseMagnifyingGlass',__( 'Magnifying glass', 'openinclusion' ),'_DigitalandScreenTechnologies[]', '_DigitalandScreenTechnologies_NoiseMagnifyingGlass'),
 );
 
 function get_digitalandscreentechnologies() {
    global $digitalandscreentechnologies_array;
    return $digitalandscreentechnologies_array;
 }
 
 $movementcanesandserviceanimals_array = array(
     array('WheelchairPowered',__( 'Power wheelchair user', 'openinclusion' ),'_MovementCanesandServiceAnimals[]', '_MovementCanesandServiceAnimals_WheelchairPowered'),
     array('WheelchairManual',__( 'Manual wheelchair user', 'openinclusion' ),'_MovementCanesandServiceAnimals[]', '_MovementCanesandServiceAnimals_WheelchairManual'),
     array('MobilityScooter',__( 'Mobility scooter user', 'openinclusion' ),'_MovementCanesandServiceAnimals[]', '_MovementCanesandServiceAnimals_MobilityScooter'),
     array('Adaptedvehicle',__( 'Adapted private vehicle', 'openinclusion' ),'_MovementCanesandServiceAnimals[]', '_MovementCanesandServiceAnimals_Adaptedvehicle'),
     array('ProstheticUpperLimb',__( 'Prosthetic/s (upper limb)', 'openinclusion' ),'_MovementCanesandServiceAnimals[]', '_MovementCanesandServiceAnimals_ProstheticUpperLimb'),
     array('ProstheticLowerLimb',__( ' Prosthetic/s (lower limb)', 'openinclusion' ),'_MovementCanesandServiceAnimals[]', '_MovementCanesandServiceAnimals_ProstheticLowerLimb'),
     array('Walkingaid',__( 'Walking aids – stability cane, crutcher/s, frame', 'openinclusion' ),'_MovementCanesandServiceAnimals[]', '_MovementCanesandServiceAnimals_Walkingaid'),
     array('Dog',__( 'Service animal (guide dog for navigational support)', 'openinclusion' ),'_MovementCanesandServiceAnimals[]', '_MovementCanesandServiceAnimals_Dog'),
     array('OtherServiceAnimal',__( 'Service animal (other)', 'openinclusion' ),'_MovementCanesandServiceAnimals[]', '_MovementCanesandServiceAnimals_OtherServiceAnimal'),
     array('Cane',__( 'Navigational, guide or symbol mobility cane', 'openinclusion' ),'_MovementCanesandServiceAnimals[]', '_MovementCanesandServiceAnimals_Cane'),
     array('OtherNavigationalMobilityAid',__( 'Other navigational or mobility aid', 'openinclusion' ),'_MovementCanesandServiceAnimals[]', '_MovementCanesandServiceAnimals_OtherNavigationalMobilityAid'),
 );
 
 function get_movementcanesandserviceanimals() {
    global $movementcanesandserviceanimals_array;
    return $movementcanesandserviceanimals_array;   
 }
 
 $communicationpreferences_array = array(
     array('SignLanguage',__( 'Sign language user', 'openinclusion' ),'_CommunicationPreferences[]', '_CommunicationPreferences_SignLanguage'),
     array('Lipreader',__( 'Lip reader', 'openinclusion' ),'_CommunicationPreferences[]', '_CommunicationPreferences_Lipreader'),
     array('CochlearImplantBionic',__( 'Cochlear Implant / Bionic ear', 'openinclusion' ),'_CommunicationPreferences[]', '_CommunicationPreferences_CochlearImplantBionic'),
     array('HearingAid',__( 'Hearing aid/s', 'openinclusion' ),'_CommunicationPreferences[]', '_CommunicationPreferences_HearingAid'),
     array('AAC',__( 'Augmented assistive communication (manual or digital AAC)', 'openinclusion' ),'_CommunicationPreferences[]', '_CommunicationPreferences_AAC'),
     array('BraillePrintedMaterial',__( 'Printed material preferred in Braille', 'openinclusion' ),'_CommunicationPreferences[]', '_CommunicationPreferences_BraillePrintedMaterial'),
     array('LargePrintHighContrastPrinted',__( 'Printed material preferred in large text / high contrast', 'openinclusion' ),'_CommunicationPreferences[]', '_CommunicationPreferences_LargePrintHighContrastPrinted'),
     array('EasyRead',__( 'Easy read content preferred', 'openinclusion' ),'_CommunicationPreferences[]', '_CommunicationPreferences_EasyRead'),
 );
 
 function get_communicationpreferences() {
    global $communicationpreferences_array;
    return $communicationpreferences_array;     
 }
 
 $personalsupportandhome_array = array(
     array('PACarerPaidFulltime',__( 'I have a full time professional career', 'openinclusion' ),'_PersonalSupportandHome[]', '_PersonalSupportandHome_PACarerPaidFulltime'),
     array('PACarerPaidParttime',__( 'I have a part time/ occasional professional career', 'openinclusion' ),'_PersonalSupportandHome[]', '_PersonalSupportandHome_PACarerPaidParttime'),
     array('PACarerUnpaid',__( 'I have a unpaid carer support (family or other)', 'openinclusion' ),'_PersonalSupportandHome[]', '_PersonalSupportandHome_PACarerUnpaid'),
     array('PACarerIsOne',__( 'I am a carer', 'openinclusion' ),'_PersonalSupportandHome[]', '_PersonalSupportandHome_PACarerIsOne'),
     array('SmartHomeAdaptation',__( 'Smart home adaptations', 'openinclusion' ),'_PersonalSupportandHome[]', '_PersonalSupportandHome_SmartHomeAdaptation'),
     array('Hoist',__( 'Hoist', 'openinclusion' ),'_PersonalSupportandHome[]', '_PersonalSupportandHome_Hoist'),
     array('AdaptedSpaces',__( 'Adapted spaces (bathroom, kitchen, access etc.)', 'openinclusion' ),'_PersonalSupportandHome[]', '_PersonalSupportandHome_AdaptedSpaces'),
     array('AdaptedProducts',__( 'Adapted products (kitchen tools, clothing/dressing etc.)', 'openinclusion' ),'_PersonalSupportandHome[]', '_PersonalSupportandHome_AdaptedProducts'),
 );
 
 function get_personalsupportandhome() {
    global $personalsupportandhome_array;
    return $personalsupportandhome_array;     
 }
 
 $othertechnologies_array = array(
    array('NoneoftheAbove',__( 'None of the above', 'openinclusion'),'_OtherTechnologiesNoneoftheAbove', '_OtherTechnologies_NoneoftheAbove'),
    array('PreferNotToSay',__( 'Prefer not to say', 'openinclusion'),'_OtherTechnologiesPreferNotToSay', '_OtherTechnologies_PreferNotToSay'),
    array('OtherPleaseSpecify',__( 'Other (please specify)', 'openinclusion'),'_OtherTechnologiesOtherPleaseSpecify', '_OtherTechnologies_OtherPleaseSpecify'),
 );   
 
 function get_othertechnologies() {
    global $othertechnologies_array;
    return $othertechnologies_array; 
 }
 
 /////////////////// Arrays for date of birth //////////////////
 $arrDobD = array();
 $arrDobM = array('January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December');
 $arrDobY = array();
 
 function getDayArray() {
    global $arrDobD;
    
    //Populate if not already done so
    if (count($arrDobD) == 0) {
       for ($i = 1; $i <= 31; $i++) {
          $arrDobD[] = $i;
       }
    }
    
    return $arrDobD;
 }
 function getMonthArray() {
    global $arrDobM;
    
    return $arrDobM;
 }
 
 function getYearArray() {
    global $arrDobY;
    
    //Populate if not already done so
    if (count($arrDobY) == 0) {
       $thisYr = (int)date('Y');
       for ($i = $thisYr; $i >= ($thisYr - 100); $i--) {
          $arrDobY[] = $i;
       }
    }
    
    return $arrDobY;
 }
 
 
 
 ///////////////////////////  Form related functions ///////////////////////////////
 //Cope with magic quotes
 if(function_exists("get_magic_quotes_gpc")) {
    if (get_magic_quotes_gpc()) {
       function co_stripslashes_deep($value)	{
          $value = is_array($value) ?
                array_map('co_stripslashes_deep', $value) :
                stripslashes($value);
    
          return $value;
       }
    
       $_POST = array_map('co_stripslashes_deep', $_POST);
       $_GET = array_map('co_stripslashes_deep', $_GET);
       $_COOKIE = array_map('co_stripslashes_deep', $_COOKIE);
       $_REQUEST = array_map('co_stripslashes_deep', $_REQUEST);
    }
 }   
 
 
 // Sanitise email addresses
 function safeEmail($str) {
    return  filter_var($str, FILTER_SANITIZE_EMAIL);
 }
 
 function outScrn($inTxt) {
 // Takes a varible name and applies necessary protection
    $inTxt = htmlspecialchars($inTxt, ENT_QUOTES, 'UTF-8');
 
    return $inTxt;
 }
 function errInd($fieldName) {
    $path = get_bloginfo('template_url').'/images/';
    $html = '<img src="'.$path.'error-ind.gif" height="16" width="16" alt="Error Indicator" title="Error Indicator" id="'.$fieldName.'-err-ind" /> ';
 
    return $html;
 }
 
 function getErrorMsg($arr, $field) {
    foreach($arr as $item) {
       if ($item[0] == $field) return $item[1];
    }
    
    return '';
 
 }
 ?>