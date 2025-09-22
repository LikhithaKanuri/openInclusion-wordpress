<?php
/**
 * Update Part 2 Step 1 data in Keap
 */

require_once(__DIR__ . '/processv2.php');

try {
    $keapIntegration = new KeapIntegration();
    
    // Get current user data
    $current_user = wp_get_current_user();
    if (!$current_user) {
        error_log("No current user found for Part 2 Step 1 update");
        return;
    }
    
    $userid = $current_user->ID;
    $user_info = get_user_meta($userid);
    
    // Prepare user meta data for Keap
    $userMetaData = array();
    
    // Add email for contact lookup
    if (isset($user_info['Email'][0])) {
        $userMetaData['Email'] = $user_info['Email'][0];
    } else {
        $userMetaData['Email'] = $current_user->user_email;
    }
    
    // Add Part 2 Step 1 specific fields
    if (isset($user_info['Country'][0])) {
        $userMetaData['Country'] = $user_info['Country'][0];
    }
    if (isset($user_info['Region'][0])) {
        $userMetaData['Region'] = $user_info['Region'][0];
    }
    if (isset($user_info['Year_Born'][0])) {
        $userMetaData['Year_Born'] = $user_info['Year_Born'][0];
    }
    if (isset($user_info['Has Disability'][0])) {
        $userMetaData['Has Disability'] = $user_info['Has Disability'][0];
    }
    if (isset($user_info['Relationship to Disability'][0])) {
        $userMetaData['Relationship to Disability'] = $user_info['Relationship to Disability'][0];
    }
    
    // Process the data
    $result = $keapIntegration->processPart2Step1($userMetaData);
    
    if ($result) {
        error_log("Successfully updated Part 2 Step 1 data in Keap for user ID: " . $userid);
    } else {
        error_log("Failed to update Part 2 Step 1 data in Keap for user ID: " . $userid);
    }
    
} catch (Exception $e) {
    error_log("Error in updatePart2Step1.php: " . $e->getMessage());
}
?> 