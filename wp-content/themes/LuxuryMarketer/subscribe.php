<?php
/**
 * Mailchimp Subscription Handler
 * Processes subscription form submissions and adds subscribers to Mailchimp via API
 */

// Enable ALL error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
ini_set('html_errors', 0);

// Start output buffering to catch any WordPress errors
ob_start();

// Detect if this is an AJAX request (check early, before any output)
$is_ajax = (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') || 
           (!empty($_POST['ajax']) && $_POST['ajax'] == '1');

// Global debug array to collect log messages
$GLOBALS['subscribe_debug_log'] = array();

// Custom error log function that also stores to debug array
function subscribe_debug_log($message) {
    if (!isset($GLOBALS['subscribe_debug_log'])) {
        $GLOBALS['subscribe_debug_log'] = array();
    }
    error_log($message);
    $GLOBALS['subscribe_debug_log'][] = $message;
}

// Function to read error log file
function read_error_log_file() {
    $log_entries = array();
    
    // Try common error log locations
    $log_paths = array(
        ini_get('error_log'),
        WP_CONTENT_DIR . '/debug.log',
        ABSPATH . 'wp-content/debug.log',
        dirname(ABSPATH) . '/error_log',
        '/var/log/apache2/error.log',
        '/var/log/nginx/error.log',
    );
    
    foreach ($log_paths as $log_path) {
        if (empty($log_path)) continue;
        
        if (file_exists($log_path) && is_readable($log_path)) {
            // Read last 100 lines of the error log
            $lines = file($log_path);
            if ($lines) {
                $recent_lines = array_slice($lines, -100);
                // Filter for reCAPTCHA-related entries
                foreach ($recent_lines as $line) {
                    if (stripos($line, 'recaptcha') !== false || stripos($line, 'reCAPTCHA') !== false) {
                        $log_entries[] = trim($line);
                    }
                }
                // If we found entries, break
                if (!empty($log_entries)) {
                    break;
                }
            }
        }
    }
    
    return $log_entries;
}

// Function to send JSON response (define early)
function send_json_response($success, $message, $errors = array(), $data = array()) {
    // Get any errors/output from buffer
    $buffer_content = '';
    while (ob_get_level() > 0) {
        $buffer_content .= ob_get_clean();
    }
    
    // Always include buffer content for debugging
    if (!empty($buffer_content)) {
        $data['debug_output'] = $buffer_content;
    }
    
    // Include debug log messages
    if (isset($GLOBALS['subscribe_debug_log']) && !empty($GLOBALS['subscribe_debug_log'])) {
        $data['debug_log'] = $GLOBALS['subscribe_debug_log'];
    }
    
    // Try to read error log file for reCAPTCHA entries
    if (defined('WP_CONTENT_DIR') || defined('ABSPATH')) {
        $error_log_entries = read_error_log_file();
        if (!empty($error_log_entries)) {
            $data['error_log_file'] = $error_log_entries;
        }
    }
    
    // Get last PHP error if any
    $last_error = error_get_last();
    if ($last_error) {
        $data['php_error'] = $last_error;
    }
    
    header('Content-Type: application/json; charset=utf-8');
    $response = array(
        'success' => $success,
        'message' => $message,
        'errors' => $errors,
        'data' => $data
    );
    echo json_encode($response, JSON_PRETTY_PRINT);
    exit;
}

// Custom error handler to catch fatal errors
function subscribe_error_handler($errno, $errstr, $errfile, $errline) {
    if (!(error_reporting() & $errno)) {
        return false;
    }
    
    $error_msg = "Error [$errno]: $errstr in $errfile on line $errline";
    error_log($error_msg);
    
    // If this is a fatal error, send JSON response
    if ($errno === E_ERROR || $errno === E_CORE_ERROR || $errno === E_COMPILE_ERROR || $errno === E_PARSE || $errno === E_USER_ERROR) {
        send_json_response(false, 'PHP Fatal Error: ' . $errstr, array('fatal'), array(
            'error_type' => $errno,
            'error_file' => $errfile,
            'error_line' => $errline,
            'error_message' => $errstr
        ));
    }
    
    return false; // Let PHP's default error handler also run
}

// Set custom error handler
set_error_handler('subscribe_error_handler');

// Custom shutdown function to catch fatal errors
function subscribe_shutdown_handler() {
    $error = error_get_last();
    if ($error !== NULL && in_array($error['type'], array(E_ERROR, E_CORE_ERROR, E_COMPILE_ERROR, E_PARSE))) {
        // Clear any output
        while (ob_get_level() > 0) {
            ob_end_clean();
        }
        
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode(array(
            'success' => false,
            'message' => 'Fatal Error: ' . $error['message'],
            'errors' => array('fatal'),
            'data' => array(
                'error_type' => $error['type'],
                'error_file' => $error['file'],
                'error_line' => $error['line'],
                'error_message' => $error['message']
            )
        ), JSON_PRETTY_PRINT);
        exit;
    }
}

// Register shutdown function
register_shutdown_function('subscribe_shutdown_handler');

// Disable WordPress fatal error handler if possible (before loading)
if (!defined('WP_SANDBOX_SCRAPING')) {
    define('WP_SANDBOX_SCRAPING', false);
}
if (!defined('WP_DEBUG')) {
    define('WP_DEBUG', true);
}
if (!defined('WP_DEBUG_DISPLAY')) {
    define('WP_DEBUG_DISPLAY', true);
}
if (!defined('WP_DEBUG_LOG')) {
    define('WP_DEBUG_LOG', true);
}

// Load WordPress if not already loaded
if (!defined('ABSPATH')) {
    $wp_load_path = dirname(dirname(dirname(dirname(__FILE__)))) . '/wp-load.php';
    if (file_exists($wp_load_path)) {
        // Clear any output so far
        ob_clean();
        
        // Capture any output during WordPress load
        ob_start();
        try {
            require_once($wp_load_path);
        } catch (Exception $e) {
            ob_end_clean();
            send_json_response(false, 'WordPress load error: ' . $e->getMessage(), array('config'), array('exception' => $e->getTraceAsString()));
        } catch (Error $e) {
            ob_end_clean();
            send_json_response(false, 'WordPress fatal error: ' . $e->getMessage(), array('config'), array('file' => $e->getFile(), 'line' => $e->getLine(), 'trace' => $e->getTraceAsString()));
        }
        
        // Get any output from WordPress loading
        $wp_load_output = ob_get_clean();
        
        // Check if WordPress loaded successfully by checking for a core function
        if (!defined('ABSPATH') || !function_exists('get_option')) {
            // WordPress failed to load properly - send error with details
            send_json_response(false, 'System error: WordPress failed to load. ABSPATH: ' . (defined('ABSPATH') ? 'defined' : 'not defined') . ', get_option exists: ' . (function_exists('get_option') ? 'yes' : 'no'), array('config'), array('wp_load_output' => $wp_load_output));
        }
        
        // If WordPress output something, log it
        if (!empty($wp_load_output)) {
            error_log('WordPress load output: ' . substr($wp_load_output, 0, 500));
        }
    } else {
        // WordPress file not found
        send_json_response(false, 'System error: WordPress file not found at: ' . $wp_load_path, array('config'));
    }
}

// Include reCAPTCHA library for validation
$recaptcha_options = function_exists('get_option') ? get_option('recaptcha_options', array()) : array();
$recaptcha_secret = isset($recaptcha_options['secret']) ? $recaptcha_options['secret'] : '';
$recaptcha_site_key = isset($recaptcha_options['site_key']) ? $recaptcha_options['site_key'] : '';

// Log reCAPTCHA configuration for debugging
subscribe_debug_log('reCAPTCHA configuration - Site key: ' . (!empty($recaptcha_site_key) ? $recaptcha_site_key : 'NOT SET'));
subscribe_debug_log('reCAPTCHA configuration - Secret key: ' . (!empty($recaptcha_secret) ? substr($recaptcha_secret, 0, 10) . '...' . substr($recaptcha_secret, -4) : 'NOT SET'));
if (!empty($recaptcha_secret)) {
    // Path: from wp-content/themes/LuxuryMarketer to wp-content/plugins/wp-recaptcha
    $recaptcha_lib_path = dirname(dirname(dirname(__FILE__))) . '/plugins/wp-recaptcha/recaptchalib.php';
    if (file_exists($recaptcha_lib_path)) {
        @require_once($recaptcha_lib_path);
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
        $redirect_url = function_exists('home_url') ? home_url('/subscription-form/') : '/subscription-form/';
        header("Location: " . $redirect_url . "?error=config");
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
// Helper function to sanitize text fields
function subscribe_sanitize_text_field($value) {
    if (function_exists('sanitize_text_field')) {
        return sanitize_text_field($value);
    } else {
        return htmlspecialchars(strip_tags(stripslashes($value)), ENT_QUOTES, 'UTF-8');
    }
}

$first_name = isset($_POST['FNAME']) ? subscribe_sanitize_text_field($_POST['FNAME']) : '';
$last_name = isset($_POST['LNAME']) ? subscribe_sanitize_text_field($_POST['LNAME']) : '';
$title = isset($_POST['TITLE']) ? subscribe_sanitize_text_field($_POST['TITLE']) : '';
$company = isset($_POST['COMPANY']) ? subscribe_sanitize_text_field($_POST['COMPANY']) : '';
$city = isset($_POST['CITY']) ? subscribe_sanitize_text_field($_POST['CITY']) : '';
$state = isset($_POST['STATE']) ? subscribe_sanitize_text_field($_POST['STATE']) : '';
$zipcode = isset($_POST['ZIPCODE']) ? subscribe_sanitize_text_field($_POST['ZIPCODE']) : '';
$country = isset($_POST['COUNTRY']) ? subscribe_sanitize_text_field($_POST['COUNTRY']) : '';
$phone = isset($_POST['PHONE']) ? subscribe_sanitize_text_field($_POST['PHONE']) : '';
$category = isset($_POST['CATEGORY']) ? subscribe_sanitize_text_field($_POST['CATEGORY']) : '';
$category_other = isset($_POST['MMERGE7']) ? subscribe_sanitize_text_field($_POST['MMERGE7']) : '';

// Basic validation
$errors = array();

if (empty($email) || !(function_exists('is_email') ? is_email($email) : filter_var($email, FILTER_VALIDATE_EMAIL))) {
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
    // Get the token and ensure it's properly decoded (wp_unslash handles magic quotes if enabled)
    $recaptcha_token = isset($_POST['g-recaptcha-response']) ? $_POST['g-recaptcha-response'] : '';
    // Don't use wp_unslash here - the token should be URL-encoded from the form, PHP will decode it automatically
    // Just trim whitespace
    $recaptcha_token = trim($recaptcha_token);
    
    // Log the raw token before any processing
    subscribe_debug_log('reCAPTCHA token raw POST value length: ' . strlen($recaptcha_token));
    subscribe_debug_log('reCAPTCHA token raw POST value (first 50): ' . substr($recaptcha_token, 0, 50));
    subscribe_debug_log('reCAPTCHA token raw POST value (last 50): ' . substr($recaptcha_token, -50));
    
    subscribe_debug_log('reCAPTCHA validation - Token received: ' . (!empty($recaptcha_token) ? 'Yes (length: ' . strlen($recaptcha_token) . ', first 30 chars: ' . substr($recaptcha_token, 0, 30) . ')' : 'No'));
    subscribe_debug_log('reCAPTCHA validation - Secret key: ' . (!empty($recaptcha_secret) ? 'Yes (length: ' . strlen($recaptcha_secret) . ')' : 'No'));
    
    if (empty($recaptcha_token)) {
        subscribe_debug_log('reCAPTCHA validation failed: Token is empty');
        if ($is_ajax) {
            send_json_response(false, 'reCAPTCHA token is missing. Please complete the reCAPTCHA verification.', array('captcha'), array('debug' => 'Token empty', 'post_keys' => array_keys($_POST)));
        }
        $errors[] = 'captcha';
    } else {
        try {
            if (!class_exists('ReCaptcha')) {
                subscribe_debug_log('reCAPTCHA validation failed: ReCaptcha class not found');
                if ($is_ajax) {
                    send_json_response(false, 'reCAPTCHA class not found', array('captcha'), array('debug' => 'ReCaptcha class missing'));
                }
                $errors[] = 'captcha';
            } else {
                $recaptcha = new ReCaptcha($recaptcha_secret);
                $remote_ip = isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : '';
                subscribe_debug_log('reCAPTCHA validation - Calling verifyResponse with IP: ' . $remote_ip);
                subscribe_debug_log('reCAPTCHA validation - Token (first 50 chars): ' . substr($recaptcha_token, 0, 50));
                subscribe_debug_log('reCAPTCHA validation - Current domain: ' . (isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : 'NOT SET'));
                subscribe_debug_log('reCAPTCHA validation - Request URI: ' . (isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : 'NOT SET'));
                
                // Create a wrapper to capture the API response
                $recaptcha_api_response_capture = array();
                
                $recaptcha_response = $recaptcha->verifyResponse($remote_ip, $recaptcha_token);
                
                subscribe_debug_log('reCAPTCHA validation - Response object: ' . print_r($recaptcha_response, true));
                
                // Log the raw API response if available
                if (isset($recaptcha_response->rawResponse)) {
                    subscribe_debug_log('reCAPTCHA validation - Raw API response: ' . $recaptcha_response->rawResponse);
                }
                
                // Log the decoded API response if available
                if (isset($recaptcha_response->decodedResponse)) {
                    subscribe_debug_log('reCAPTCHA validation - Decoded API response: ' . json_encode($recaptcha_response->decodedResponse, JSON_PRETTY_PRINT));
                }
                
                // Also log the error codes if available
                if (isset($recaptcha_response->errorCodes) && is_array($recaptcha_response->errorCodes)) {
                    subscribe_debug_log('reCAPTCHA validation - Error codes: ' . implode(', ', $recaptcha_response->errorCodes));
                    
                    // Check server error log for the actual API response (it's logged there)
                    // For now, log what we know
                    subscribe_debug_log('reCAPTCHA validation - Note: Check server error logs for detailed API response from Google');
                    subscribe_debug_log('reCAPTCHA validation - Common causes of invalid-input-response:');
                    subscribe_debug_log('  1. Token expired (tokens expire after ~2 minutes)');
                    subscribe_debug_log('  2. Token already used (tokens are single-use)');
                    subscribe_debug_log('  3. Token format corrupted during transmission');
                    subscribe_debug_log('  4. Domain mismatch (even if domain looks correct)');
                    subscribe_debug_log('  5. Secret key mismatch (even if keys look correct)');
                }
                
                if (!$recaptcha_response) {
                    subscribe_debug_log('reCAPTCHA validation failed: Response object is null');
                    if ($is_ajax) {
                        send_json_response(false, 'reCAPTCHA verification failed: No response from server', array('captcha'), array('debug' => 'Response null'));
                    }
                    $errors[] = 'captcha';
                } elseif (!isset($recaptcha_response->success) || !$recaptcha_response->success) {
                    $error_codes = isset($recaptcha_response->errorCodes) ? $recaptcha_response->errorCodes : 'unknown';
                    subscribe_debug_log('reCAPTCHA validation failed: ' . print_r($error_codes, true));
                    if ($is_ajax) {
                        send_json_response(false, 'reCAPTCHA verification failed: ' . (is_array($error_codes) ? implode(', ', $error_codes) : $error_codes), array('captcha'), array('debug' => 'Validation failed', 'error_codes' => $error_codes));
                    }
                    $errors[] = 'captcha';
                } else {
                    subscribe_debug_log('reCAPTCHA validation successful');
                }
            }
        } catch (Exception $e) {
            subscribe_debug_log('reCAPTCHA validation exception: ' . $e->getMessage());
            subscribe_debug_log('reCAPTCHA validation exception trace: ' . $e->getTraceAsString());
            if ($is_ajax) {
                send_json_response(false, 'reCAPTCHA verification error: ' . $e->getMessage(), array('captcha'), array('debug' => 'Exception', 'message' => $e->getMessage()));
            }
            $errors[] = 'captcha';
        } catch (Error $e) {
            subscribe_debug_log('reCAPTCHA validation error: ' . $e->getMessage());
            subscribe_debug_log('reCAPTCHA validation error trace: ' . $e->getTraceAsString());
            if ($is_ajax) {
                send_json_response(false, 'reCAPTCHA verification error: ' . $e->getMessage(), array('captcha'), array('debug' => 'Error', 'message' => $e->getMessage()));
            }
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
