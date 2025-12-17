<?php
/**
 * Mailchimp API Test Script
 * Use this to diagnose Mailchimp API connection issues
 * Access via: https://www.luxurymarketer.com/wp-content/themes/LuxuryMarketer/test-mailchimp.php
 * 
 * IMPORTANT: Delete this file after testing for security!
 */

// Load WordPress
require_once(dirname(dirname(dirname(dirname(__FILE__)))) . '/wp-load.php');

// Include MailChimp library
require_once(dirname(__FILE__) . '/MailChimp.php');
use \DrewM\MailChimp\MailChimp;

// Get API key
$mailchimp_api_key = defined('MAILCHIMP_API_KEY') ? MAILCHIMP_API_KEY : (getenv('MAILCHIMP_API_KEY') ?: '');

?>
<!DOCTYPE html>
<html>
<head>
    <title>Mailchimp API Test</title>
    <style>
        body { font-family: Arial, sans-serif; padding: 20px; }
        .success { color: green; }
        .error { color: red; }
        .info { background: #f0f0f0; padding: 10px; margin: 10px 0; }
        pre { background: #f5f5f5; padding: 10px; overflow-x: auto; }
    </style>
</head>
<body>
    <h1>Mailchimp API Connection Test</h1>
    
    <?php
    // Check API key
    echo '<div class="info">';
    echo '<h2>API Key Status</h2>';
    if (empty($mailchimp_api_key) || $mailchimp_api_key === 'YOUR_MAILCHIMP_API_KEY_HERE') {
        echo '<p class="error">❌ API Key NOT configured!</p>';
        echo '<p>Please set MAILCHIMP_API_KEY in wp-config.php</p>';
    } else {
        echo '<p class="success">✅ API Key is set</p>';
        echo '<p>Key format: ' . substr($mailchimp_api_key, 0, 10) . '...' . substr($mailchimp_api_key, -5) . '</p>';
        
        // Check API key format
        if (strpos($mailchimp_api_key, '-') === false) {
            echo '<p class="error">⚠️ Warning: API key format may be invalid (should contain a dash, e.g., xxxxxxxx-us12)</p>';
        } else {
            list($key_part, $dc_part) = explode('-', $mailchimp_api_key, 2);
            echo '<p>Data Center: ' . htmlspecialchars($dc_part) . '</p>';
        }
    }
    echo '</div>';
    
    if (!empty($mailchimp_api_key) && $mailchimp_api_key !== 'YOUR_MAILCHIMP_API_KEY_HERE') {
        try {
            $MailChimp = new MailChimp($mailchimp_api_key);
            
            echo '<div class="info">';
            echo '<h2>Testing API Connection</h2>';
            
            // Test 1: Get account info
            echo '<h3>Test 1: Get Account Info</h3>';
            $account = $MailChimp->get('');
            
            if ($MailChimp->success()) {
                echo '<p class="success">✅ Connection successful!</p>';
                echo '<pre>' . print_r($account, true) . '</pre>';
            } else {
                echo '<p class="error">❌ Connection failed!</p>';
                echo '<p>Error: ' . htmlspecialchars($MailChimp->getLastError()) . '</p>';
                $response = $MailChimp->getLastResponse();
                echo '<pre>Response: ' . print_r($response, true) . '</pre>';
            }
            
            // Test 2: Get list info
            echo '<h3>Test 2: Get List Info (List ID: e40241a98c)</h3>';
            $list_id = '066a49a9fa';
            $list = $MailChimp->get("lists/$list_id");
            
            if ($MailChimp->success()) {
                echo '<p class="success">✅ List found!</p>';
                echo '<p>List Name: ' . htmlspecialchars($list['name'] ?? 'N/A') . '</p>';
                echo '<p>Member Count: ' . htmlspecialchars($list['stats']['member_count'] ?? 'N/A') . '</p>';
                
                // Get merge fields
                echo '<h3>Test 3: Get Merge Fields</h3>';
                $merge_fields = $MailChimp->get("lists/$list_id/merge-fields");
                if ($MailChimp->success()) {
                    echo '<p class="success">✅ Merge fields retrieved!</p>';
                    echo '<h4>Available Merge Fields:</h4>';
                    echo '<ul>';
                    foreach ($merge_fields['merge_fields'] as $field) {
                        echo '<li><strong>' . htmlspecialchars($field['tag']) . '</strong>: ' . htmlspecialchars($field['name']) . ' (Type: ' . htmlspecialchars($field['type']) . ')</li>';
                    }
                    echo '</ul>';
                } else {
                    echo '<p class="error">❌ Failed to get merge fields</p>';
                    echo '<p>Error: ' . htmlspecialchars($MailChimp->getLastError()) . '</p>';
                }
            } else {
                echo '<p class="error">❌ List not found or access denied!</p>';
                echo '<p>Error: ' . htmlspecialchars($MailChimp->getLastError()) . '</p>';
                $response = $MailChimp->getLastResponse();
                if (isset($response['body'])) {
                    $error_data = json_decode($response['body'], true);
                    echo '<pre>Error Details: ' . print_r($error_data, true) . '</pre>';
                }
            }
            
            echo '</div>';
            
        } catch (Exception $e) {
            echo '<div class="error">';
            echo '<h2>Exception</h2>';
            echo '<p>' . htmlspecialchars($e->getMessage()) . '</p>';
            echo '</div>';
        }
    }
    ?>
    
    <div class="info">
        <h2>Next Steps</h2>
        <ol>
            <li>If API key is not set, add it to wp-config.php</li>
            <li>If connection fails, check:
                <ul>
                    <li>API key is valid and active in Mailchimp</li>
                    <li>API key has proper permissions</li>
                    <li>List ID (e40241a98c) is correct</li>
                </ul>
            </li>
            <li>Check that merge fields in Mailchimp match the ones used in subscribe.php</li>
            <li><strong>Delete this test file after use for security!</strong></li>
        </ol>
    </div>
</body>
</html>


