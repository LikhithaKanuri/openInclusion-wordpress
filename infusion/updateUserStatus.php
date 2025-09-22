<?php
/**
 * Update User Status in Keap based on registration progress
 */

require_once(__DIR__ . '/processv2.php');
require_once(__DIR__ . '/keap-config.php');

try {
    $keapIntegration = new KeapIntegration();
    
    // Get current user data
    $current_user = wp_get_current_user();
    if (!$current_user) {
        error_log("No current user found for status update");
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
    
    // Determine registration status based on completed steps
    $registrationStatus = 'Incomplete';
    $completedSteps = array();
    
    // Check which steps have been completed
    if (get_user_meta($userid, 'Part2Step1Completed', true) === 'Yes') {
        $completedSteps[] = 'Step1';
    }
    if (get_user_meta($userid, 'Part2Step2Completed', true) === 'Yes') {
        $completedSteps[] = 'Step2';
    }
    if (get_user_meta($userid, 'Part2Step3Completed', true) === 'Yes') {
        $completedSteps[] = 'Step3';
    }
    if (get_user_meta($userid, 'Part2Step4Completed', true) === 'Yes') {
        $completedSteps[] = 'Step4';
    }
    if (get_user_meta($userid, 'Part2Step5Completed', true) === 'Yes') {
        $completedSteps[] = 'Step5';
    }
    if (get_user_meta($userid, 'Part2Step6Completed', true) === 'Yes') {
        $completedSteps[] = 'Step6';
    }
    if (get_user_meta($userid, 'Part2Step7Completed', true) === 'Yes') {
        $completedSteps[] = 'Step7';
    }
    if (get_user_meta($userid, 'Part2Step8Completed', true) === 'Yes') {
        $completedSteps[] = 'Step8';
    }
    
    // Determine overall status
    if (count($completedSteps) >= 6) { // Steps 1-6 are minimum for completion
        $registrationStatus = 'Complete';
    } elseif (count($completedSteps) >= 3) {
        $registrationStatus = 'In Progress - Advanced';
    } elseif (count($completedSteps) >= 1) {
        $registrationStatus = 'In Progress - Basic';
    }
    
    // Check if user has been screened out
    if (get_user_meta($userid, 'ScreenedOut', true) === 'Yes') {
        $registrationStatus = 'Screened Out';
        $screenedOutReason = get_user_meta($userid, 'ScreenedOutReason', true);
        if ($screenedOutReason) {
            $userMetaData['ScreenedOutReason'] = $screenedOutReason;
        }
    }
    
    // Prepare status update data
    $statusData = array(
        'Email' => $userMetaData['Email'],
        '_RegistrationStatus' => $registrationStatus,
        '_CompletedSteps' => implode(',', $completedSteps),
        '_LastStatusUpdate' => date('Y-m-d H:i:s')
    );
    
    // Add screened out info if applicable
    if (isset($userMetaData['ScreenedOutReason'])) {
        $statusData['_ScreenedOutReason'] = $userMetaData['ScreenedOutReason'];
    }
    
    // Find contact and update
    $contactId = $keapIntegration->findContactByEmail($userMetaData['Email']);
    if ($contactId) {
        $result = $keapIntegration->updateContact($contactId, $statusData);
        
        if ($result) {
            error_log("Successfully updated user status in Keap for user ID: " . $userid . " Status: " . $registrationStatus);
            
            // Add appropriate tags based on status
            $tagsToAdd = array();
            switch ($registrationStatus) {
                case 'Complete':
                    $tagId = getKeapTagId('registration_complete_status');
                    if ($tagId) $tagsToAdd[] = $tagId;
                    break;
                case 'In Progress - Advanced':
                    $tagId = getKeapTagId('in_progress_advanced');
                    if ($tagId) $tagsToAdd[] = $tagId;
                    break;
                case 'In Progress - Basic':
                    $tagId = getKeapTagId('in_progress_basic');
                    if ($tagId) $tagsToAdd[] = $tagId;
                    break;
                case 'Screened Out':
                    $tagId = getKeapTagId('screened_out');
                    if ($tagId) $tagsToAdd[] = $tagId;
                    break;
            }
            
            if (!empty($tagsToAdd)) {
                $keapIntegration->addTagsToContact($contactId, $tagsToAdd);
            }
        } else {
            error_log("Failed to update user status in Keap for user ID: " . $userid);
        }
    } else {
        error_log("Contact not found in Keap for user email: " . $userMetaData['Email']);
    }
    
} catch (Exception $e) {
    error_log("Error in updateUserStatus.php: " . $e->getMessage());
}

/**
 * Helper function to get contact by email (for direct access)
 */
function findContactByEmail($email) {
    try {
        $keapIntegration = new KeapIntegration();
        return $keapIntegration->findContactByEmail($email);
    } catch (Exception $e) {
        error_log("Error finding contact by email: " . $e->getMessage());
        return false;
    }
}
?> 