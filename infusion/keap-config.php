<?php
/**
 * Keap Configuration for Open Inclusion Registration
 * Update the tag IDs and field mappings as needed for your Keap setup
 */

// Keap Tag IDs - Update these with your actual tag IDs from Keap
define('KEAP_TAGS', array(
    // Initial registration tags
    'initial_registration' => 1001,
    'email_verified' => 1002,
    
    // Step progression tags (each step completion assigns the next step tag)
    'step1_completed' => 1010,  // User completed Step 1 - redirect to Step 2
    'step2_completed' => 1011,  // User completed Step 2 - redirect to Step 3  
    'step3_completed' => 1012,  // User completed Step 3 - redirect to Step 4
    'step4_completed' => 1013,  // User completed Step 4 - redirect to Step 5
    'step5_completed' => 1014,  // User completed Step 5 - redirect to Step 6
    'step6_completed' => 1015,  // User completed Step 6 - redirect to Step 7
    'step7_completed' => 1016,  // User completed Step 7 - redirect to Step 8
    'step8_completed' => 1017,  // User completed Step 8 - redirect to Step 9
    'registration_completed' => 1018, // All steps completed
    
    // Status tags
    'registration_complete_status' => 2001,
    'in_progress_advanced' => 2002,
    'in_progress_basic' => 2003,
    'screened_out' => 2004,
    'member_tag' => 3001,
    'partial_member_tag' => 3002,
    
    // Additional tags
    'community_agreement' => 1006,
    'privacy_consent' => 1007,
    'open_verified_optin' => 1008,
    'community_account_created' => 1009
));

// Keap Custom Field Mappings - Update these with your actual custom field names
define('KEAP_CUSTOM_FIELDS', array(
    'country_phone_code' => '_CountryPhoneCode',
    'preferred_contact_methods' => '_PreferredContactMethods',
    'country' => '_Country',
    'region' => '_Region',
    'year_born' => '_YearBorn',
    'has_disability' => '_HasDisability',
    'relationship_to_disability' => '_RelationshipToDisability',
    'sensory_needs' => '_SensoryNeeds',
    'physical_needs' => '_PhysicalNeeds',
    'cognitive_mental_health_needs' => '_CognitiveAndMentalHealthNeeds',
    'communication_needs' => '_CommunicationNeeds',
    'chronic_health_needs' => '_ChronicHealthNeeds',
    'other_needs' => '_OtherNeeds',
    'digital_screen_technologies' => '_DigitalScreenTechnologies',
    'print_media' => '_PrintMedia',
    'movement_canes_service_animals' => '_MovementCanesServiceAnimals',
    'communication_preferences' => '_CommunicationPreferences',
    'personal_support_home' => '_PersonalSupportAndHome',
    'other_technologies' => '_OtherTechnologies',
    'research_formats' => '_ResearchFormats',
    'referred' => '_Referred',
    'referrer_name' => '_ReferrerName',
    'gender' => '_Gender',
    'gender_at_birth_different' => '_GenderAtBirthDifferent',
    'sexual_orientations' => '_SexualOrientations',
    'preferred_pronouns' => '_PreferredPronouns',
    'ethnic_identity' => '_EthnicIdentity',
    'community_agreement' => '_CommunityAgreement',
    'community_agreement_date' => '_CommunityAgreementDate',
    'privacy_consent' => '_PrivacyConsent',
    'privacy_consent_date' => '_PrivacyConsentDate',
    'open_verified_opt_in' => '_OpenVerifiedOptIn',
    'open_verified_opt_in_date' => '_OpenVerifiedOptInDate',
    'community_username' => '_CommunityUsername',
    'community_account_created' => '_CommunityAccountCreated',
    'registration_completed' => '_RegistrationCompleted',
    'registration_completed_date' => '_RegistrationCompletedDate',
    'registration_status' => '_RegistrationStatus',
    'completed_steps' => '_CompletedSteps',
    'last_status_update' => '_LastStatusUpdate',
    'screened_out_reason' => '_ScreenedOutReason'
));

// Helper function to get tag ID by name
function getKeapTagId($tagName) {
    $tags = KEAP_TAGS;
    return isset($tags[$tagName]) ? $tags[$tagName] : false;
}

// Helper function to get custom field name by key
function getKeapCustomField($fieldKey) {
    $fields = KEAP_CUSTOM_FIELDS;
    return isset($fields[$fieldKey]) ? $fields[$fieldKey] : '_' . ucfirst($fieldKey);
}

// Step progression mapping - determines next step based on completion
define('STEP_PROGRESSION', array(
    'initial_registration' => '/part2-step1/',
    'email_verified' => '/part2-step1/',
    'step1_completed' => '/part2-step2/',
    'step2_completed' => '/part2-step3/',
    'step3_completed' => '/part2-step4/',
    'step4_completed' => '/part2-step5/',
    'step5_completed' => '/part2-step6/',
    'step6_completed' => '/part2-step7/',
    'step7_completed' => '/part2-step8/',
    'step8_completed' => '/part2-step9/',
    'registration_completed' => '/part2-step9/' // Final step - thank you page
));

// Step completion tags mapping
define('STEP_COMPLETION_TAGS', array(
    'part2-step1' => 'step1_completed',
    'part2-step2' => 'step2_completed',
    'part2-step3' => 'step3_completed',
    'part2-step4' => 'step4_completed',
    'part2-step5' => 'step5_completed',
    'part2-step6' => 'step6_completed',
    'part2-step7' => 'step7_completed',
    'part2-step8' => 'step8_completed',
    'part2-step9' => 'registration_completed'
));

// Additional configuration
define('KEAP_DEBUG_MODE', true); // Set to false in production
define('KEAP_LOG_PREFIX', 'Open Inclusion Keap: ');

/**
 * Get the next step URL based on user's current progress
 */
function getNextStepForUser($userEmail) {
    try {
        if (!$userEmail) return '/part2-step1/';
        
        // Get user's tags from Keap
        require_once(__DIR__ . '/processv2.php');
        $keapIntegration = new KeapIntegration();
        $contactId = $keapIntegration->findContactByEmail($userEmail);
        
        if (!$contactId) {
            return '/part2-step1/'; // Start from beginning if not found
        }
        
        // Get contact's tags
        $userTags = $keapIntegration->getContactTags($contactId);
        
        // Find the highest completed step
        $highestStep = 'initial_registration';
        $stepOrder = array(
            'registration_completed',
            'step8_completed',
            'step7_completed', 
            'step6_completed',
            'step5_completed',
            'step4_completed',
            'step3_completed',
            'step2_completed',
            'step1_completed',
            'email_verified',
            'initial_registration'
        );
        
        foreach ($stepOrder as $step) {
            $tagId = getKeapTagId($step);
            if ($tagId && in_array($tagId, $userTags)) {
                $highestStep = $step;
                break;
            }
        }
        
        // Get next step URL
        $progression = STEP_PROGRESSION;
        return isset($progression[$highestStep]) ? $progression[$highestStep] : '/part2-step1/';
        
    } catch (Exception $e) {
        error_log("Error getting next step for user: " . $e->getMessage());
        return '/part2-step1/';
    }
}

/**
 * Get step completion tag for a given step
 */
function getStepCompletionTag($stepPath) {
    $stepPath = trim($stepPath, '/');
    $completionTags = STEP_COMPLETION_TAGS;
    return isset($completionTags[$stepPath]) ? $completionTags[$stepPath] : null;
}

/**
 * Check if user has completed a specific step
 */
function hasUserCompletedStep($userEmail, $stepName) {
    try {
        require_once(__DIR__ . '/processv2.php');
        $keapIntegration = new KeapIntegration();
        $contactId = $keapIntegration->findContactByEmail($userEmail);
        
        if (!$contactId) return false;
        
        $userTags = $keapIntegration->getContactTags($contactId);
        $tagId = getKeapTagId($stepName);
        
        return $tagId && in_array($tagId, $userTags);
        
    } catch (Exception $e) {
        error_log("Error checking step completion: " . $e->getMessage());
        return false;
    }
}

/**
 * Build full URL for redirect
 */
function buildStepUrl($stepPath) {
    $host = $_SERVER['HTTP_HOST'] ?? 'localhost';
    $protocol = ($host == 'localhost') ? 'http' : 'https';
    $basePath = ($host == 'localhost') ? '/openinclusion' : '';
    
    return $protocol . '://' . $host . $basePath . $stepPath;
}
?> 