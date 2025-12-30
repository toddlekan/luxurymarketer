<?php
/**
 * Mailchimp Subscription Handler
 * Processes subscription form submissions and adds subscribers to Mailchimp via API
 */

// Enable error reporting for debugging (but don't display if AJAX)
error_reporting(E_ALL);
$is_ajax_check = (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') || 
                 (!empty($_POST['ajax']) && $_POST['ajax'] == '1');
if (!$is_ajax_check) {
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
} else {
    ini_set('display_errors', 0);
    ini_set('display_startup_errors', 0);
}

// Detect if this is an AJAX request
$is_ajax = (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') || 
           (!empty($_POST['ajax']) && $_POST['ajax'] == '1');

// Function to send JSON response
function send_json_response($success, $message, $errors = array(), $data = array()) {
    // Clear any previous output
    if (ob_get_length()) {
        ob_clean();
    }
    header('Content-Type: application/json; charset=utf-8');
    $response = array(
        'success' => $success,
        'message' => $message,
        'errors' => $errors,
        'data' => $data
    );
    echo json_encode($response);
    exit;
}

// Load WordPress if not already loaded
if (!defined('ABSPATH')) {
    require_once(dirname(dirname(dirname(dirname(__FILE__)))) . '/wp-load.php');
}

// Include reCAPTCHA library for validation
$recaptcha_options = get_option('recaptcha_options', array());
$recaptcha_secret = isset($recaptcha_options['secret']) ? $recaptcha_options['secret'] : '';
if (!empty($recaptcha_secret)) {
    // Path: from wp-content/themes/LuxuryMarketer to wp-content/plugins/wp-recaptcha
    $recaptcha_lib_path = dirname(dirname(dirname(__FILE__))) . '/plugins/wp-recaptcha/recaptchalib.php';
    if (file_exists($recaptcha_lib_path)) {
        require_once($recaptcha_lib_path);
    } else {
        error_log('reCAPTCHA library not found at: ' . $recaptcha_lib_path);
        // Try alternative path using WP_CONTENT_DIR if available
        if (defined('WP_CONTENT_DIR')) {
            $alt_path = WP_CONTENT_DIR . '/plugins/wp-recaptcha/recaptchalib.php';
            if (file_exists($alt_path)) {
                require_once($alt_path);
            } else {
                error_log('reCAPTCHA library not found at alternative path: ' . $alt_path);
            }
        }
    }
}

// Get Mailchimp API key from wp-config or environment variable
$mailchimp_api_key = defined('MAILCHIMP_API_KEY') ? MAILCHIMP_API_KEY : (getenv('MAILCHIMP_API_KEY') ?: '');

// Log API key status for debugging (first 10 chars only for security)
$api_key_status = empty($mailchimp_api_key) ? 'NOT SET' : substr($mailchimp_api_key, 0, 10) . '...';
error_log('Mailchimp API Key Check: ' . $api_key_status);
if (!$is_ajax) {
    echo "<!-- Mailchimp API Key Check: " . htmlspecialchars($api_key_status) . " -->\n";
}

if (empty($mailchimp_api_key) || $mailchimp_api_key === 'YOUR_MAILCHIMP_API_KEY_HERE') {
    $error_msg = 'Mailchimp API Key not configured properly';
    error_log($error_msg);
    if ($is_ajax) {
        send_json_response(false, 'Subscription service is temporarily unavailable. Please try again later.', array('config'));
    } else {
        if (!$is_ajax) {
            echo "<!-- ERROR: " . htmlspecialchars($error_msg) . " -->\n";
        }
        header("Location: " . home_url('/subscription-form/') . "?error=config");
        exit;
    }
}

// Extract data center from API key (format: xxxxxxxx-us12)
$data_center = 'us12'; // Default
if (strpos($mailchimp_api_key, '-') !== false) {
    list(, $data_center) = explode('-', $mailchimp_api_key, 2);
}

// Mailchimp list ID and API endpoint
$list_id = '066a49a9fa';
$api_endpoint = "https://{$data_center}.api.mailchimp.com/3.0/lists/{$list_id}/members";

// Function to make Mailchimp API call using curl
function mailchimp_api_request($url, $method, $api_key, $data = null) {
    $ch = curl_init();
    
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        'Content-Type: application/json',
        'Authorization: apikey ' . $api_key
    ));
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 30);
    
    if ($method === 'POST') {
        curl_setopt($ch, CURLOPT_POST, true);
        if ($data) {
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        }
    } elseif ($method === 'PATCH') {
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PATCH');
        if ($data) {
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        }
    }
    
    $response = curl_exec($ch);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $curl_error = curl_error($ch);
    curl_close($ch);
    
    return array(
        'success' => ($http_code >= 200 && $http_code < 300),
        'http_code' => $http_code,
        'body' => $response,
        'error' => $curl_error
    );
}

// Function to generate subscriber hash (MD5 of lowercase email)
function mailchimp_subscriber_hash($email) {
    return md5(strtolower($email));
}

// Sanitize and validate form data
// Preserve plus signs by getting raw POST data first, then sanitizing
$email_raw = isset($_POST['EMAIL']) ? wp_unslash($_POST['EMAIL']) : '';
$email2_raw = isset($_POST['email2']) ? wp_unslash($_POST['email2']) : '';
// Sanitize email addresses (sanitize_email preserves plus signs in valid emails)
$email = sanitize_email($email_raw);
$email2 = sanitize_email($email2_raw);
$first_name = isset($_POST['FNAME']) ? sanitize_text_field($_POST['FNAME']) : '';
$last_name = isset($_POST['LNAME']) ? sanitize_text_field($_POST['LNAME']) : '';
$title = isset($_POST['TITLE']) ? sanitize_text_field($_POST['TITLE']) : '';
$company = isset($_POST['COMPANY']) ? sanitize_text_field($_POST['COMPANY']) : '';
$city = isset($_POST['CITY']) ? sanitize_text_field($_POST['CITY']) : '';
$state = isset($_POST['STATE']) ? sanitize_text_field($_POST['STATE']) : '';
$zipcode = isset($_POST['ZIPCODE']) ? sanitize_text_field($_POST['ZIPCODE']) : '';
$country = isset($_POST['COUNTRY']) ? sanitize_text_field($_POST['COUNTRY']) : '';
$phone = isset($_POST['PHONE']) ? sanitize_text_field($_POST['PHONE']) : '';
$category = isset($_POST['CATEGORY']) ? sanitize_text_field($_POST['CATEGORY']) : '';
$category_other = isset($_POST['MMERGE7']) ? sanitize_text_field($_POST['MMERGE7']) : '';

// Basic validation
$errors = array();

if (empty($email) || !is_email($email)) {
    $errors[] = 'email';
}

if ($email !== $email2) {
    $errors[] = 'email_mismatch';
}

if (empty($first_name)) {
    $errors[] = 'first_name';
}

if (empty($last_name)) {
    $errors[] = 'last_name';
}

if (empty($title)) {
    $errors[] = 'title';
}

if (empty($company)) {
    $errors[] = 'company';
}

if (empty($city)) {
    $errors[] = 'city';
}

if (empty($state) || $state === 'Select State') {
    $errors[] = 'state';
}

if (empty($zipcode)) {
    $errors[] = 'zipcode';
}

if (empty($country)) {
    $errors[] = 'country';
}

if (empty($category)) {
    $errors[] = 'category';
}

// Validate reCAPTCHA if configured
if (!empty($recaptcha_secret)) {
    if (empty($_POST['g-recaptcha-response'])) {
        $errors[] = 'captcha';
    } else {
        $recaptcha = new ReCaptcha($recaptcha_secret);
        $recaptcha_response = $recaptcha->verifyResponse(
            $_SERVER['REMOTE_ADDR'],
            $_POST['g-recaptcha-response']
        );
        
        if (!$recaptcha_response->success) {
            $errors[] = 'captcha';
        }
    }
}

// If there are validation errors, return error response
if (!empty($errors)) {
    $error_params = implode(',', $errors);
    $validation_error = 'Validation errors: ' . $error_params;
    error_log($validation_error);
    
    $field_labels = array(
        'email' => 'Email Address',
        'email_mismatch' => 'Email addresses do not match',
        'first_name' => 'First Name',
        'last_name' => 'Last Name',
        'title' => 'Title',
        'company' => 'Company',
        'city' => 'City',
        'state' => 'State',
        'zipcode' => 'ZIP/Post Code',
        'country' => 'Country',
        'category' => 'Industry',
        'captcha' => 'reCAPTCHA verification'
    );
    $error_messages = array();
    foreach ($errors as $error) {
        $error_messages[] = isset($field_labels[$error]) ? $field_labels[$error] : ucfirst(str_replace('_', ' ', $error));
    }
    
    if ($is_ajax) {
        send_json_response(false, 'Please check the following fields: ' . implode(', ', $error_messages), $errors);
    } else {
        if (!$is_ajax) {
            echo "<!-- VALIDATION ERROR: " . htmlspecialchars($validation_error) . " -->\n";
        }
        header("Location: " . home_url('/subscription-form/') . "?error=validation&fields=" . urlencode($error_params));
        exit;
    }
}

// Use "Other" category value if category is "Other"
if ($category === 'Other' && !empty($category_other)) {
    $category = $category_other;
}

// Prepare merge fields for Mailchimp
// Note: You may need to adjust these merge field tags to match your Mailchimp list
$merge_fields = array(
    'FNAME' => $first_name,
    'LNAME' => $last_name,
);

// Add optional merge fields if they exist in your Mailchimp list
// Common merge field tags: TITLE, COMPANY, CITY, STATE, ZIPCODE, COUNTRY, PHONE, CATEGORY
// You'll need to check your Mailchimp list settings to see the exact merge field tags
if (!empty($title)) {
    $merge_fields['TITLE'] = $title;
}
if (!empty($company)) {
    $merge_fields['COMPANY'] = $company;
}
if (!empty($city)) {
    $merge_fields['CITY'] = $city;
}
if (!empty($state)) {
    $merge_fields['STATE'] = $state;
}
if (!empty($zipcode)) {
    $merge_fields['ZIPCODE'] = $zipcode;
}
if (!empty($country)) {
    $merge_fields['COUNTRY'] = $country;
}
if (!empty($phone)) {
    $merge_fields['PHONE'] = $phone;
}
if (!empty($category)) {
    $merge_fields['CATEGORY'] = $category;
}

// Prepare subscriber data
$subscriber_data = array(
    'email_address' => $email,
    'status' => 'subscribed',
    'merge_fields' => $merge_fields
);

// Try to add or update subscriber
try {
    // First, try to add the subscriber
    $response = mailchimp_api_request($api_endpoint, 'POST', $mailchimp_api_key, $subscriber_data);
    
    // Check if the request was successful
    if ($response['success']) {
        // Success
        if ($is_ajax) {
            send_json_response(true, 'Thank you! Your subscription has been confirmed.');
        } else {
            header("Location: " . home_url('/subscription-form/') . "?step=thankyou");
            exit;
        }
    } else {
        // Get error details
        $formatted_response = json_decode($response['body'], true);
        $http_code = $response['http_code'];
        $curl_error = $response['error'];
        
        // Check if subscriber already exists (error code 400 with specific message)
        $is_duplicate = false;
        if ($formatted_response) {
            $error_title = isset($formatted_response['title']) ? $formatted_response['title'] : '';
            $error_detail = isset($formatted_response['detail']) ? $formatted_response['detail'] : '';
            $is_duplicate = (
                strpos($error_title, 'already a list member') !== false ||
                strpos($error_title, 'Member Exists') !== false ||
                strpos($error_detail, 'already a list member') !== false ||
                strpos($error_detail, 'Member Exists') !== false ||
                ($http_code === 400 && isset($formatted_response['title']) && 
                 (strpos($formatted_response['title'], 'Member Exists') !== false))
            );
        }
        
        // If subscriber already exists, try to update them
        if ($is_duplicate) {
            // Get subscriber hash
            $subscriber_hash = mailchimp_subscriber_hash($email);
            
            // Update existing subscriber
            $update_url = "https://{$data_center}.api.mailchimp.com/3.0/lists/{$list_id}/members/{$subscriber_hash}";
            $update_response = mailchimp_api_request($update_url, 'PATCH', $mailchimp_api_key, $subscriber_data);
            
            if ($update_response['success']) {
                // Success
                if ($is_ajax) {
                    send_json_response(true, 'Thank you! Your subscription has been confirmed.');
                } else {
                    header("Location: " . home_url('/subscription-form/') . "?step=thankyou");
                    exit;
                }
            } else {
                // Log error
                $update_error_response = json_decode($update_response['body'], true);
                $update_error_msg = 'Mailchimp Update Error: ' . print_r($update_error_response, true);
                $update_http_code = 'HTTP Code: ' . $update_response['http_code'];
                error_log($update_error_msg);
                error_log($update_http_code);
                if ($is_ajax) {
                    send_json_response(false, 'There was an error updating your subscription. Please try again.', array('update'));
                } else {
                    if (!$is_ajax) {
                        echo "<!-- UPDATE ERROR: " . htmlspecialchars($update_error_msg) . " -->\n";
                        echo "<!-- " . htmlspecialchars($update_http_code) . " -->\n";
                    }
                    header("Location: " . home_url('/subscription-form/') . "?error=update");
                    exit;
                }
            }
        } else {
            // Other error occurred - log detailed error information
            $api_error_details = array(
                'HTTP Code' => $http_code,
                'Formatted Response' => $formatted_response,
                'cURL Error' => $curl_error,
                'API Key (first 10 chars)' => substr($mailchimp_api_key, 0, 10) . '...',
                'List ID' => $list_id,
                'Subscriber Data' => $subscriber_data
            );
            
            error_log('Mailchimp API Error Details:');
            foreach ($api_error_details as $key => $value) {
                error_log($key . ': ' . print_r($value, true));
                if (!$is_ajax) {
                    echo "<!-- API ERROR - " . htmlspecialchars($key) . ": " . htmlspecialchars(print_r($value, true)) . " -->\n";
                }
            }
            
            // Extract error message from formatted response
            $error_message = 'api_error';
            if ($formatted_response) {
                if (isset($formatted_response['detail'])) {
                    $error_message = urlencode($formatted_response['detail']);
                } elseif (isset($formatted_response['title'])) {
                    $error_message = urlencode($formatted_response['title']);
                }
            }
            if ($error_message === 'api_error' && !empty($curl_error)) {
                $error_message = urlencode($curl_error);
            }
            
            if ($is_ajax) {
                send_json_response(false, 'There was an error submitting your subscription. Please try again.', array('api'));
            } else {
                header("Location: " . home_url('/subscription-form/') . "?error=api&msg=" . $error_message);
                exit;
            }
        }
    }
} catch (Exception $e) {
    // Log exception
    $exception_msg = 'Mailchimp Exception: ' . $e->getMessage();
    $exception_trace = $e->getTraceAsString();
    error_log($exception_msg);
    error_log('Exception Trace: ' . $exception_trace);
    if ($is_ajax) {
        send_json_response(false, 'An unexpected error occurred. Please try again.', array('exception'));
    } else {
        if (!$is_ajax) {
            echo "<!-- EXCEPTION: " . htmlspecialchars($exception_msg) . " -->\n";
            echo "<!-- EXCEPTION TRACE: " . htmlspecialchars($exception_trace) . " -->\n";
        }
        header("Location: " . home_url('/subscription-form/') . "?error=exception");
        exit;
    }
}
?>
