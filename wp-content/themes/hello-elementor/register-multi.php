<?php
/**
 * Template for multi-stage community registration form.
 * Shortcode: [community_registration]
 */

// Ensure required files are included (definitions, elements, functions)
include_once get_template_directory() . '/reg-panel-new-form-definitions.php';
include_once get_template_directory() . '/reg-panel-new-form-elements.php';
include_once get_template_directory() . '/reg-panel-new-form-functions.php';

if (!function_exists('render_form_element')) {
    /**
     * Fallback rendering function if not defined in external file
     */
    function render_form_element($field_key) {
        return '<p><label>' . esc_html($field_key) . '</label><input type="text" name="' . esc_attr($field_key) . '" /></p>';
    }
}

function render_community_registration_form($atts = [], $content = null, $tag = '') {
    // Start output buffer
    ob_start();

    // Determine current step
    $step = isset($_POST['registration_step']) ? intval($_POST['registration_step']) : 1;

    // Handle form submission for each step
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['community_registration_submit'])) {
        // Validate current step fields
        $errors = function_exists('validate_registration_step') ? validate_registration_step($step, $_POST) : [];

        if (empty($errors)) {
            // Save data
            if (function_exists('save_registration_step_data')) {
                save_registration_step_data($step, $_POST);
            }

            // Advance to next step
            $step++;
        } else {
            // Display errors
            echo '<div class="registration-errors"><ul>';
            foreach ($errors as $error) {
                echo '<li>' . esc_html($error) . '</li>';
            }
            echo '</ul></div>';
        }
    }

    // If all steps complete, finalize registration
    $total_steps = count($GLOBALS['registration_steps']);
    if ($step > $total_steps) {
        echo function_exists('render_registration_complete') ? render_registration_complete() : '<p>Registration complete!</p>';
        return ob_get_clean();
    }

    // Render step form
    echo '<form method="post" class="community-registration-form">';
    echo '<input type="hidden" name="registration_step" value="' . esc_attr($step) . '">';
    echo '<h2>Step ' . esc_html($step) . ' of ' . esc_html($total_steps) . '</h2>';

    // Render fields for current step
    if (!empty($GLOBALS['registration_steps'][$step])) {
        foreach ($GLOBALS['registration_steps'][$step] as $field_key) {
            echo render_form_element($field_key);
        }
    } else {
        echo '<p>No fields configured for this step.</p>';
    }

    // Navigation buttons
    if ($step > 1) {
        echo '<button type="submit" name="community_registration_prev" value="1" class="button prev-step">Previous</button>';
    }
    echo '<button type="submit" name="community_registration_submit" class="button next-step">';
    echo ($step === $total_steps) ? 'Submit' : 'Next';
    echo '</button>';

    echo '</form>';

    return ob_get_clean();
}

add_shortcode('community_registration', 'render_community_registration_form');

// JavaScript for handling Previous button (go back a step)
add_action('wp_footer', function() {
    $post = get_post();
    if ($post && has_shortcode($post->post_content, 'community_registration')): ?>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const prevBtn = document.querySelector('.prev-step');
        if (prevBtn) {
            prevBtn.addEventListener('click', function(e) {
                e.preventDefault();
                const form = this.closest('form');
                let stepInput = form.querySelector('[name="registration_step"]');
                stepInput.value = parseInt(stepInput.value) - 1;
                form.submit();
            });
        }
    });
    </script>
    <?php endif;
});