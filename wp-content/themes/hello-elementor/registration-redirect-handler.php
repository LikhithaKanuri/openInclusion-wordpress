<?php
/**
 * Registration Redirect Handler
 * Handles user redirection based on their registration progress stored in Keap tags
 */

require_once(__DIR__ . '/../../../infusion/keap-config.php');

/**
 * Redirect user to appropriate registration step based on their progress
 */
function redirectUserToAppropriateStep() {
    // Only redirect logged-in users
    if (!is_user_logged_in()) {
        return;
    }
    
    $current_user = wp_get_current_user();
    $userEmail = '';
    
    // Get user email for Keap lookup
    $user_info = get_user_meta($current_user->ID);
    if (isset($user_info['Email'][0])) {
        $userEmail = $user_info['Email'][0];
    } else {
        $userEmail = $current_user->user_email;
    }
    
    if (!$userEmail) {
        return; // Can't redirect without email
    }
    
    // Check if user is trying to access a registration step directly
    $currentPath = $_SERVER['REQUEST_URI'];
    $registrationPaths = array(
        '/part2-step1/', '/part2-step2/', '/part2-step3/', '/part2-step4/',
        '/part2-step5/', '/part2-step6/', '/part2-step7/', '/part2-step8/', '/part2-step9/'
    );
    
    $isOnRegistrationPath = false;
    foreach ($registrationPaths as $path) {
        if (strpos($currentPath, $path) !== false) {
            $isOnRegistrationPath = true;
            break;
        }
    }
    
    // Only redirect if they're on a registration path or trying to access the main registration
    if (!$isOnRegistrationPath && strpos($currentPath, '/multi-step-registration') === false) {
        return;
    }
    
    try {
        // Get the next step the user should be on
        $nextStepPath = getNextStepForUser($userEmail);
        $fullNextStepUrl = buildStepUrl($nextStepPath);
        
        // Check if user is already on the correct step
        $currentStepPath = '';
        foreach ($registrationPaths as $path) {
            if (strpos($currentPath, $path) !== false) {
                $currentStepPath = $path;
                break;
            }
        }
        
        // If they're on the wrong step, redirect them
        if ($currentStepPath && $currentStepPath !== $nextStepPath) {
            error_log("Redirecting user {$userEmail} from {$currentStepPath} to {$nextStepPath}");
            wp_redirect($fullNextStepUrl);
            exit;
        }
        
        // If they're accessing the main registration page, redirect to their next step
        if (strpos($currentPath, '/multi-step-registration') !== false) {
            error_log("Redirecting user {$userEmail} from main registration to {$nextStepPath}");
            wp_redirect($fullNextStepUrl);
            exit;
        }
        
    } catch (Exception $e) {
        error_log("Error in registration redirect: " . $e->getMessage());
    }
}

/**
 * Handle user activation and redirect to appropriate step
 */
function handleUserActivationRedirect() {
    // Check if this is an activation request
    $user_id = filter_input(INPUT_GET, 'user', FILTER_VALIDATE_INT, array('options' => array('min_range' => 1)));
    $key = filter_input(INPUT_GET, 'key');
    
    if ($user_id && $key) {
        // Verify activation key
        $stored_key = get_user_meta($user_id, 'ActivationKey', true);
        if ($stored_key == $key) {
            // Remove activation key
            delete_user_meta($user_id, 'ActivationKey');
            
            // Log the user in
            wp_set_current_user($user_id);
            wp_set_auth_cookie($user_id);
            
            // Tag user as email verified in Keap
            $user = get_user_by('ID', $user_id);
            if ($user) {
                try {
                    require_once(__DIR__ . '/../../../infusion/processv2.php');
                    $keapIntegration = new KeapIntegration();
                    $contactId = $keapIntegration->findContactByEmail($user->user_email);
                    
                    if ($contactId) {
                        $tagId = getKeapTagId('email_verified');
                        if ($tagId) {
                            $keapIntegration->addTagsToContact($contactId, array($tagId));
                        }
                    }
                } catch (Exception $e) {
                    error_log("Error tagging email verified: " . $e->getMessage());
                }
            }
            
            // Redirect to appropriate registration step
            $nextStepPath = getNextStepForUser($user->user_email);
            $fullUrl = buildStepUrl($nextStepPath);
            wp_redirect($fullUrl);
            exit;
        }
    }
}

/**
 * Check if user can access a specific step
 */
function canUserAccessStep($userEmail, $stepPath) {
    try {
        $stepPath = trim($stepPath, '/');
        $nextStep = getNextStepForUser($userEmail);
        $nextStep = trim($nextStep, '/');
        
        // Allow access to current step and any previously completed steps
        $stepOrder = array(
            'part2-step1', 'part2-step2', 'part2-step3', 'part2-step4',
            'part2-step5', 'part2-step6', 'part2-step7', 'part2-step8', 'part2-step9'
        );
        
        $currentStepIndex = array_search($nextStep, $stepOrder);
        $requestedStepIndex = array_search($stepPath, $stepOrder);
        
        if ($currentStepIndex === false || $requestedStepIndex === false) {
            return true; // Allow access if we can't determine the order
        }
        
        // Allow access to current step or any previous step
        return $requestedStepIndex <= $currentStepIndex;
        
    } catch (Exception $e) {
        error_log("Error checking step access: " . $e->getMessage());
        return true; // Allow access on error
    }
}

/**
 * Get user's registration progress percentage
 */
function getUserRegistrationProgress($userEmail) {
    try {
        $nextStep = getNextStepForUser($userEmail);
        $stepOrder = array(
            '/part2-step1/' => 10,
            '/part2-step2/' => 20,
            '/part2-step3/' => 35,
            '/part2-step4/' => 50,
            '/part2-step5/' => 65,
            '/part2-step6/' => 75,
            '/part2-step7/' => 85,
            '/part2-step8/' => 90,
            '/part2-step9/' => 100  // Final completion step - thank you page
        );
        
        return isset($stepOrder[$nextStep]) ? $stepOrder[$nextStep] : 0;
        
    } catch (Exception $e) {
        error_log("Error getting registration progress: " . $e->getMessage());
        return 0;
    }
}

// Hook into WordPress actions
add_action('template_redirect', 'redirectUserToAppropriateStep', 1);
add_action('init', 'handleUserActivationRedirect', 1);

// Shortcode to display registration progress
function registration_progress_shortcode($atts) {
    if (!is_user_logged_in()) {
        return '<p>Please log in to view your registration progress.</p>';
    }
    
    $current_user = wp_get_current_user();
    $userEmail = $current_user->user_email;
    $progress = getUserRegistrationProgress($userEmail);
    $nextStep = getNextStepForUser($userEmail);
    
    $html = '<div class="registration-progress">';
    $html .= '<h3>Registration Progress</h3>';
    $html .= '<div class="progress-bar">';
    $html .= '<div class="progress-fill" style="width: ' . $progress . '%;"></div>';
    $html .= '</div>';
    $html .= '<p>' . $progress . '% Complete</p>';
    
    if ($progress < 100) {
        $nextStepUrl = buildStepUrl($nextStep);
        $html .= '<p><a href="' . $nextStepUrl . '" class="btn btn-primary">Continue Registration</a></p>';
    } else {
        $html .= '<p><strong>Registration Complete!</strong></p>';
    }
    
    $html .= '</div>';
    
    return $html;
}
add_shortcode('registration_progress', 'registration_progress_shortcode');
?> 