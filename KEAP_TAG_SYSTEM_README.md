# Keap Tag-Based Resumable Registration System

## Overview
This system uses Keap tags to track user progress through the multi-step registration process. When a user completes a step, they receive a specific tag that indicates they can proceed to the next step. When they return, the system checks their tags to redirect them to the appropriate step.

## Tag Structure

### Step Progression Tags
Each step completion assigns a tag that enables access to the NEXT step:

| Tag Name | Tag ID | Meaning | Redirects User To |
|----------|--------|---------|-------------------|
| `initial_registration` | 1001 | Basic registration completed | `/part2-step1/` |
| `email_verified` | 1002 | Email verification completed | `/part2-step1/` |
| `step1_completed` | 1010 | Step 1 completed | `/part2-step2/` |
| `step2_completed` | 1011 | Step 2 completed | `/part2-step3/` |
| `step3_completed` | 1012 | Step 3 completed | `/part2-step4/` |
| `step4_completed` | 1013 | Step 4 completed | `/part2-step5/` |
| `step5_completed` | 1014 | Step 5 completed | `/part2-step6/` |
| `step6_completed` | 1015 | Step 6 completed | `/part2-step7/` |
| `step7_completed` | 1016 | Step 7 completed | `/part2-step8/` |
| `step8_completed` | 1017 | Step 8 completed | `/part2-step9/` |
| `registration_completed` | 1018 | All steps completed | `/part2-step9/` (stays on final page) |

### Additional Tracking Tags
| Tag Name | Tag ID | Purpose |
|----------|--------|---------|
| `community_agreement` | 1006 | User agreed to community terms |
| `privacy_consent` | 1007 | User consented to privacy policy |
| `open_verified_optin` | 1008 | User opted for identity verification |
| `community_account_created` | 1009 | User created community login |

### Status Tags
| Tag Name | Tag ID | Purpose |
|----------|--------|---------|
| `registration_complete_status` | 2001 | Registration fully completed |
| `in_progress_advanced` | 2002 | User has completed 3+ steps |
| `in_progress_basic` | 2003 | User has completed 1-2 steps |
| `screened_out` | 2004 | User was screened out (under 18, etc.) |

## How It Works

### 1. User Completes a Step
```php
// In each redirect function (e.g., redirectAfterPart2Step1)
$keapIntegration->processPart2Step1($userMetaData);
// This calls the appropriate process method which:
// 1. Saves data to Keap
// 2. Assigns the step completion tag (e.g., step1_completed)
```

### 2. User Returns/Logs In
```php
// registration-redirect-handler.php automatically:
// 1. Gets user's email
// 2. Looks up their tags in Keap
// 3. Determines highest completed step
// 4. Redirects to next appropriate step
```

### 3. Tag Lookup Process
```php
function getNextStepForUser($userEmail) {
    // Gets contact tags from Keap
    // Checks tags in reverse order (latest first):
    // registration_completed → step8_completed → step7_completed → etc.
    // Returns appropriate next step URL
}
```

## Step Mapping

### Registration Steps
1. **Part 2 Step 1**: About Me (country, age, disability status)
2. **Part 2 Step 2**: Access Needs (sensory, physical, cognitive needs)
3. **Part 2 Step 3**: Assistive Technologies (tools and software used)
4. **Part 2 Step 4**: Personal Characteristics (gender, pronouns, ethnicity)
5. **Part 2 Step 5**: Community Agreement (terms and conditions)
6. **Part 2 Step 6**: Privacy Protection (GDPR consent)
7. **Part 2 Step 7**: Identity Verification (optional verification)
8. **Part 2 Step 8**: Community Login (username/password creation)
9. **Part 2 Step 9**: Thank You & Registration Complete (final completion page)

### Data Saved at Each Step
- **Step 1**: Location, birth year, disability status, relationship to disability
- **Step 2**: All access needs categories selected
- **Step 3**: Assistive technologies, research preferences, referral info
- **Step 4**: Gender identity, pronouns, ethnic identity, sexual orientation
- **Step 5**: Community agreement consent
- **Step 6**: Privacy policy consent
- **Step 7**: Identity verification opt-in
- **Step 8**: Community username and password
- **Step 9**: Final completion status, registration_completed tag assigned

## Configuration

### Update Tag IDs
Edit `infusion/keap-config.php` to match your Keap setup:
```php
define('KEAP_TAGS', array(
    'step1_completed' => YOUR_TAG_ID,
    'step2_completed' => YOUR_TAG_ID,
    // ... etc
));
```

### Custom Field Mapping
Update custom field names in the same config file:
```php
define('KEAP_CUSTOM_FIELDS', array(
    'country' => '_Country',
    'sensory_needs' => '_SensoryNeeds',
    // ... etc
));
```

## Key Functions

### `getNextStepForUser($userEmail)`
- **Purpose**: Determines which step user should see next
- **Returns**: URL path like `/part2-step3/`
- **Logic**: Checks user's tags to find highest completed step

### `redirectUserToAppropriateStep()`
- **Purpose**: Automatically redirects users to correct step
- **When**: Runs on every page load for logged-in users
- **Scope**: Only affects registration-related pages

### `handleUserActivationRedirect()`
- **Purpose**: Handles email verification and initial login
- **When**: User clicks email verification link
- **Action**: Logs user in, assigns email_verified tag, redirects to Step 1

### `canUserAccessStep($userEmail, $stepPath)`
- **Purpose**: Checks if user can access a specific step
- **Logic**: Users can access current step and any previous steps
- **Security**: Prevents users from skipping ahead

## Resumable Registration Flow

```
1. User registers → gets initial_registration tag
2. User verifies email → gets email_verified tag → redirected to Step 1
3. User completes Step 1 → gets step1_completed tag → can access Step 2
4. User leaves and returns → system checks tags → redirects to Step 2
5. User completes Step 2 → gets step2_completed tag → can access Step 3
... and so on until Step 9 (Thank You page) where they get registration_completed tag
```

## Benefits

✅ **Seamless Resume**: Users can leave and return at any time  
✅ **Progress Tracking**: Clear visibility of user progress  
✅ **Prevents Skipping**: Users can't skip ahead to later steps  
✅ **Automatic Redirects**: No manual navigation needed  
✅ **Keap Integration**: All progress tracked in your CRM  
✅ **Error Recovery**: Graceful handling of edge cases  

## Shortcodes

### `[registration_progress]`
Displays user's current progress with a progress bar and "Continue" button.

```html
<div class="registration-progress">
    <h3>Registration Progress</h3>
    <div class="progress-bar">
        <div class="progress-fill" style="width: 65%;"></div>
    </div>
    <p>65% Complete</p>
    <p><a href="/part2-step6/" class="btn">Continue Registration</a></p>
</div>
```

## Debugging

### Enable Debug Mode
Set in `infusion/keap-config.php`:
```php
define('KEAP_DEBUG_MODE', true);
```

### Check Logs
All tag assignments and redirects are logged to WordPress error log:
```
Open Inclusion Keap: Assigned step3_completed tag to user: user@example.com
Redirecting user user@example.com from /part2-step2/ to /part2-step4/
```

### Manual Testing
1. Check user's tags in Keap admin
2. Use `getNextStepForUser($email)` to see calculated next step
3. Review error logs for any Keap connection issues

## Security Considerations

- ✅ Users can only access current step or previous steps
- ✅ Email verification required before Step 1 access
- ✅ All Keap operations have error handling
- ✅ Graceful fallbacks if Keap is unavailable
- ✅ Session-based WordPress authentication required

## Maintenance

### Adding New Steps
1. Add new tag IDs to `KEAP_TAGS` array
2. Update `STEP_PROGRESSION` mapping
3. Create new form and redirect function
4. Add step to `STEP_COMPLETION_TAGS` mapping

### Changing Tag IDs
Simply update the tag IDs in `infusion/keap-config.php` - no code changes needed.

### Monitoring
- Monitor error logs for Keap connection issues
- Check that users are properly progressing through steps
- Verify tag assignments in Keap admin interface 