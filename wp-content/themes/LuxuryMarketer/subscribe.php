<?php
/**
 * Mailchimp Subscription Handler
 * Processes subscription form submissions and adds subscribers to Mailchimp via API
 */

// Load WordPress FIRST - before any output or function calls
if (!defined('ABSPATH')) {
    require_once(dirname(dirname(dirname(dirname(__FILE__)))) . '/wp-load.php');
}

// Now we can safely use WordPress functions
// Disable error display in production (errors go to log only)
if (!WP_DEBUG) {
    error_reporting(0);
    ini_set('display_errors', 0);
}

// Function to make Mailchimp API call using curl
function lm_mailchimp_api_request($url, $method, $api_key, $data = null) {
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
function lm_mailchimp_subscriber_hash($email) {
    return md5(strtolower($email));
}

// Function to verify reCAPTCHA using Google's API directly
function lm_verify_recaptcha($secret, $response, $remote_ip) {
    $url = 'https://www.google.com/recaptcha/api/siteverify';
    $data = array(
        'secret' => $secret,
        'response' => $response,
        'remoteip' => $remote_ip
    );
    
    $options = array(
        'http' => array(
            'header' => "Content-type: application/x-www-form-urlencoded\r\n",
            'method' => 'POST',
            'content' => http_build_query($data)
        )
    );
    
    $context = stream_context_create($options);
    $result = file_get_contents($url, false, $context);
    
    if ($result === false) {
        return false;
    }
    
    $json = json_decode($result, true);
    return isset($json['success']) && $json['success'] === true;
}

// Get Mailchimp API key from wp-config or environment variable
$mailchimp_api_key = defined('MAILCHIMP_API_KEY') ? MAILCHIMP_API_KEY : (getenv('MAILCHIMP_API_KEY') ?: '');

if (empty($mailchimp_api_key) || $mailchimp_api_key === 'YOUR_MAILCHIMP_API_KEY_HERE') {
    wp_safe_redirect(home_url('/subscription-form/') . '?error=config');
    exit;
}

// Extract data center from API key (format: xxxxxxxx-us12)
$data_center = 'us12'; // Default
if (strpos($mailchimp_api_key, '-') !== false) {
    list(, $data_center) = explode('-', $mailchimp_api_key, 2);
}

// Mailchimp list ID and API endpoint
$list_id = '066a49a9fa';
$api_endpoint = "https://{$data_center}.api.mailchimp.com/3.0/lists/{$list_id}/members";

// Sanitize and validate form data
$email = isset($_POST['EMAIL']) ? sanitize_email($_POST['EMAIL']) : '';
$email2 = isset($_POST['email2']) ? sanitize_email($_POST['email2']) : '';
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

// Validate reCAPTCHA
$recaptcha_options = get_option('recaptcha_options', array());
$recaptcha_secret = isset($recaptcha_options['secret']) ? $recaptcha_options['secret'] : '';

if (!empty($recaptcha_secret)) {
    if (empty($_POST['g-recaptcha-response'])) {
        $errors[] = 'captcha';
    } else {
        $captcha_valid = lm_verify_recaptcha(
            $recaptcha_secret,
            $_POST['g-recaptcha-response'],
            $_SERVER['REMOTE_ADDR']
        );
        
        if (!$captcha_valid) {
            $errors[] = 'captcha';
        }
    }
}

// If there are validation errors, redirect back to form
if (!empty($errors)) {
    $error_params = implode(',', $errors);
    wp_safe_redirect(home_url('/subscription-form/') . '?error=validation&fields=' . urlencode($error_params));
    exit;
}

// Use "Other" category value if category is "Other"
if ($category === 'Other' && !empty($category_other)) {
    $category = $category_other;
}

// Prepare merge fields for Mailchimp
$merge_fields = array(
    'FNAME' => $first_name,
    'LNAME' => $last_name,
);

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
    $response = lm_mailchimp_api_request($api_endpoint, 'POST', $mailchimp_api_key, $subscriber_data);
    
    if ($response['success']) {
        wp_safe_redirect(home_url('/subscription-form/') . '?step=thankyou');
        exit;
    } else {
        $formatted_response = json_decode($response['body'], true);
        $http_code = $response['http_code'];
        
        // Check if subscriber already exists
        $is_duplicate = false;
        if ($formatted_response) {
            $error_title = isset($formatted_response['title']) ? $formatted_response['title'] : '';
            $error_detail = isset($formatted_response['detail']) ? $formatted_response['detail'] : '';
            $is_duplicate = (
                strpos($error_title, 'already a list subscriber') !== false ||
                strpos($error_title, 'Subscriber Exists') !== false ||
                strpos($error_detail, 'already a list subscriber') !== false ||
                strpos($error_detail, 'Subscriber Exists') !== false ||
                ($http_code === 400 && strpos($error_title, 'Member Exists') !== false)
            );
        }
        
        if ($is_duplicate) {
            $subscriber_hash = lm_mailchimp_subscriber_hash($email);
            $update_url = "https://{$data_center}.api.mailchimp.com/3.0/lists/{$list_id}/members/{$subscriber_hash}";
            $update_response = lm_mailchimp_api_request($update_url, 'PATCH', $mailchimp_api_key, $subscriber_data);
            
            if ($update_response['success']) {
                wp_safe_redirect(home_url('/subscription-form/') . '?step=thankyou');
                exit;
            } else {
                wp_safe_redirect(home_url('/subscription-form/') . '?error=update');
                exit;
            }
        } else {
            $error_message = 'api_error';
            if ($formatted_response && isset($formatted_response['detail'])) {
                $error_message = urlencode($formatted_response['detail']);
            }
            wp_safe_redirect(home_url('/subscription-form/') . '?error=api&msg=' . $error_message);
            exit;
        }
    }
} catch (Exception $e) {
    error_log('Mailchimp Exception: ' . $e->getMessage());
    wp_safe_redirect(home_url('/subscription-form/') . '?error=exception');
    exit;
}

