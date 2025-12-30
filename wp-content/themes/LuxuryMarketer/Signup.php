<?php
/*
 Template Name: Mainchimp Free Subscription Page
 */

// Load WordPress if not already loaded
if (!defined('ABSPATH')) {
    require_once(dirname(dirname(dirname(__FILE__))) . '/wp-load.php');
}

$url_root = ld16_cdn(get_template_directory_uri()); ?>


<!DOCTYPE html>

<html xmlns="http://www.w3.org/1999/xhtml" lang="en-GB">
<meta name="viewport" content="width=device-width, initial-scale=1">

<head id="theHead">
	<meta charset="utf-8" />
	<title>
		Luxury Marketer
	</title>
	<link rel="shortcut icon" href="https://www.luxurymarketer.com/wp-content/themes/LuxuryMarketer/favicon.ico" type="image/x-icon" />

	<link href="/wp-content/themes/LuxuryMarketer/css/general.css" type="text/css" rel="stylesheet" />

	<style>
		@media only screen and (max-width: 43em) {

			.control_cell_nameaddr input,
			.txtbox_referrals input,
			.control_cell_nameaddr select,
			.txtbox_referrals select,
			.control_cell_billto_nameaddr input,
			.txtbox_referrals input,
			.control_cell_billto_nameaddr select,
			.txtbox_referrals select {
				font-size: 1em;
				width: 93% !important;
			}
		}
	</style>

	<link rel="stylesheet" href="<?= ld16_cdn($url_root) ?>/css/fonts.css">

	<link rel="stylesheet" type="text/css" href="<?= ld16_cdn($url_root) ?>/css/MyFontsWebfontsKit.css" />

	<style type="text/css">
		.CheltenhamStd-Bold {
			font-family: CheltenhamStd-Bold;
			font-weight: normal;
			font-style: normal;
		}

		.CheltenhamStd-BoldItalic {
			font-family: CheltenhamStd-BoldItalic;
			font-weight: normal;
			font-style: normal;
		}

		.CheltenhamStd-Book {
			font-family: CheltenhamStd-Book;
			font-weight: normal;
			font-style: normal;
		}

		.CheltenhamStd-BookItalic {
			font-family: CheltenhamStd-BookItalic;
			font-weight: normal;
			font-style: normal;
		}

		.CheltenhamStd-Light {
			font-family: CheltenhamStd-Light;
			font-weight: normal;
			font-style: normal;
		}

		.CheltenhamStd-LightItalic {
			font-family: CheltenhamStd-LightItalic;
			font-weight: normal;
			font-style: normal;
		}

		.CheltenhamStd-Ultra {
			font-family: CheltenhamStd-Ultra;
			font-weight: normal;
			font-style: normal;
		}

		.CheltenhamStd-UltraItalic {
			font-family: CheltenhamStd-UltraItalic;
			font-weight: normal;
			font-style: normal;
		}

		/*CheltenhamStd-Light*/
		@import url("https://fast.fonts.net/lt/1.css?apiType=css&c=1251ad9c-b83f-40c6-ab0f-4d59a447038b&fontids=5675029");

		@font-face {
			font-family: "ITC Cheltenham W03 Light";
			src: url("<?= ld16_cdn($url_root) ?>/css/Fonts/5675029/14c1a467-8fb4-4917-ad51-b65b60dc1f70.eot?#iefix");
			src: url("<?= ld16_cdn($url_root) ?>/css/Fonts/5675029/14c1a467-8fb4-4917-ad51-b65b60dc1f70.eot?#iefix") format("eot"),
				url("<?= ld16_cdn($url_root) ?>/css/Fonts/5675029/64a08d6f-8ae7-49c6-9502-726c709d7825.woff2") format("woff2"),
				url("<?= ld16_cdn($url_root) ?>/css/Fonts/5675029/0c9e1d03-606f-4b4c-a9ed-18376802c5b3.woff") format("woff"),
				url("<?= ld16_cdn($url_root) ?>/css/Fonts/5675029/0b7c6632-ef51-4df8-84ab-27041f8ad0df.ttf") format("truetype");
		}
	</style>

	<script src="/wp-content/themes/LuxuryMarketer/js/jquery.js" type="text/javascript"></script>

	<script>
		$(document).ready(function() {

			if (location.href.indexOf('?step=thankyou') > -1) {

				$('.thankyou').show();
			} else {

				$('form').show();
			}

			var searchParams = new URLSearchParams(location.search)

			$('#email').val(searchParams.get('email'));
			$('#email2').val(searchParams.get('email'));

			$(document).on('change', '#bcode', function() {

				if ($(this).val() === 'Other') {
					$('.demos_answer_other').show();
				} else {
					$('.demos_answer_other').hide();
				}

			});

			$(document).on('click', '#submit_btn', function(e) {

				e.preventDefault();

				var submit = true;

				$('#email2_compare').hide();
				$('#email_regex').hide();

				$('.reqmsg').hide();

				var form = $('#mc-embedded-subscribe-form');
				var url = form.attr('action');
				var formContainer = $('#thePanel');
				var submitButton = $('#submit_btn');
				var originalButtonValue = submitButton.val();

				var email = $('#email');
				var email2 = $('#email2');

				$('.required').each(function() {

					var reqmsg = $(this).closest('td').find('.reqmsg');

					if (!$(this).val()) {
						reqmsg.show();
						submit = false;
					}

				});

				var re = /\S+@\S+\.\S+/;

				if (email.val() && !re.test(email.val())) {
					$('#email_regex').show();
					submit = false;
				}

				if (email2.val() && email2.val() !== email.val()) {
					$('#email2_compare').show();
					submit = false;
				}

				if (submit) {

					var bcode = $('#bcode').val();

					if (bcode !== 'Other') {
						$('#bcode_other').val(bcode);
					}

					// Validate reCAPTCHA if it exists
					var recaptchaWidget = $('.g-recaptcha');
					if (recaptchaWidget.length > 0) {
						// For reCAPTCHA v2, the token is stored in a hidden textarea
						// It can be anywhere in the DOM, not necessarily in the form
						var recaptchaResponse = $('textarea[name="g-recaptcha-response"]').val();
						
						console.log('reCAPTCHA token from textarea:', recaptchaResponse ? 'Found (' + recaptchaResponse.substring(0, 20) + '...)' : 'Not found');
						
						// Also try using grecaptcha.getResponse() if available
						if ((!recaptchaResponse || recaptchaResponse === '') && typeof grecaptcha !== 'undefined') {
							try {
								// Try with widget ID 0 (default for first widget)
								recaptchaResponse = grecaptcha.getResponse(0);
								console.log('reCAPTCHA token from getResponse(0):', recaptchaResponse ? 'Found (' + recaptchaResponse.substring(0, 20) + '...)' : 'Not found');
							} catch (e) {
								console.log('reCAPTCHA getResponse error:', e);
							}
						}
						
						if (!recaptchaResponse || recaptchaResponse === '') {
							var errorHtml = '<div class="ajax-message" style="color:#d32f2f; background-color:#ffebee; padding:15px; border:2px solid #f44336; border-radius:4px; font-weight:bold; font-size:14px; margin-bottom:20px;">';
							errorHtml += '<strong>Error:</strong> Please complete the reCAPTCHA verification.';
							errorHtml += '</div>';
							formContainer.find('.ajax-message').remove();
							formContainer.prepend(errorHtml);
							return false;
						}
					}

					// Disable submit button and show loading state
					submitButton.prop('disabled', true).val('Submitting...');
					
					// Remove any existing error/success messages
					formContainer.find('.ajax-message').remove();
					
					// Get reCAPTCHA token using the API (most reliable method)
					var recaptchaToken = '';
					if (recaptchaWidget.length > 0 && typeof grecaptcha !== 'undefined') {
						try {
							// Use grecaptcha.getResponse() to get the current token
							// Widget ID 0 is the default for the first widget
							recaptchaToken = grecaptcha.getResponse(0);
							if (!recaptchaToken) {
								// If getResponse(0) fails, try to find widget ID from the element
								var widgetId = recaptchaWidget.data('widget-id');
								if (widgetId !== undefined) {
									recaptchaToken = grecaptcha.getResponse(widgetId);
								} else {
									// Fallback to textarea if API method fails
									recaptchaToken = $('textarea[name="g-recaptcha-response"]').val();
								}
							}
						} catch (e) {
							console.log('Error getting reCAPTCHA token from API:', e);
							// Fallback to textarea
							recaptchaToken = $('textarea[name="g-recaptcha-response"]').val();
						}
					}
					
					// Serialize form data (this will include the textarea if it's in the form)
					var formData = form.serialize();
					
					// Always replace the token with the one from the API to ensure we have the latest/active token
					if (recaptchaToken) {
						// Remove any existing g-recaptcha-response from serialized data
						formData = formData.replace(/[&]?g-recaptcha-response=[^&]*/g, '');
						// Add the fresh token from the API
						formData += '&g-recaptcha-response=' + encodeURIComponent(recaptchaToken);
						console.log('reCAPTCHA token added from API (first 30 chars):', recaptchaToken.substring(0, 30));
					} else {
						console.log('Warning: reCAPTCHA token not found for form submission');
					}
					
					formData += '&ajax=1';
					
					console.log('Submitting form data to:', url);
					console.log('Form data length:', formData.length);
					
					// Submit via AJAX
					$.ajax({
						url: url,
						type: 'POST',
						data: formData,
						dataType: 'json',
						beforeSend: function(xhr) {
							xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
						},
						success: function(response) {
							console.log('AJAX Success Response:', response);
							if (response && response.success) {
								// Success - replace form with success message
								var successHtml = '<div class="ajax-message" style="color:#2e7d32; background-color:#e8f5e9; padding:30px; border:2px solid #4caf50; border-radius:4px; text-align:center; margin:20px 0;">';
								successHtml += '<h2 style="color:#2e7d32; margin-top:0;">Success!</h2>';
								successHtml += '<p style="font-size:16px; margin-bottom:0;">' + (response.message || 'Thank you! Your subscription has been confirmed.') + '</p>';
								successHtml += '<p style="margin-top:20px;"><a href="https://www.luxurymarketer.com">Return to the Luxury Marketer homepage</a></p>';
								successHtml += '</div>';
								formContainer.html(successHtml);
							} else {
								// Error - show error message
								console.log('AJAX Success but response.success is false:', response);
								var errorMessage = response && response.message ? response.message : 'There was an error submitting your subscription. Please try again.';
								var errorHtml = '<div class="ajax-message" style="color:#d32f2f; background-color:#ffebee; padding:15px; border:2px solid #f44336; border-radius:4px; font-weight:bold; font-size:14px; margin-bottom:20px;">';
								errorHtml += '<strong>Error:</strong> ' + errorMessage;
								
								// Display debug log if available
								if (response && response.data && response.data.debug_log && response.data.debug_log.length > 0) {
									errorHtml += '<div style="margin-top:15px; padding:10px; background-color:#fff3cd; border:1px solid #ffc107; border-radius:4px; font-size:12px; font-family:monospace; max-height:300px; overflow-y:auto;">';
									errorHtml += '<strong>Debug Log:</strong><br>';
									response.data.debug_log.forEach(function(logEntry) {
										errorHtml += '<div>' + logEntry.replace(/</g, '&lt;').replace(/>/g, '&gt;') + '</div>';
									});
									errorHtml += '</div>';
								}
								
								// Display full response data for debugging
								if (response && response.data) {
									errorHtml += '<details style="margin-top:10px;"><summary style="cursor:pointer; color:#666; font-size:12px;">Show Debug Info</summary>';
									errorHtml += '<pre style="margin-top:10px; padding:10px; background-color:#f5f5f5; border:1px solid #ddd; border-radius:4px; font-size:11px; max-height:400px; overflow-y:auto;">' + JSON.stringify(response.data, null, 2) + '</pre>';
									errorHtml += '</details>';
								}
								
								errorHtml += '</div>';
								formContainer.prepend(errorHtml);
							
							// Re-enable submit button
							submitButton.prop('disabled', false).val(originalButtonValue);
							
							// Reset reCAPTCHA if it exists
							if (typeof grecaptcha !== 'undefined' && $('.g-recaptcha').length > 0) {
								grecaptcha.reset();
							}
						}
						},
						error: function(xhr, status, error) {
							console.log('AJAX Error:', status, error);
							console.log('Response Text:', xhr.responseText);
							console.log('Status Code:', xhr.status);
							
							// Try to parse JSON error response
							var errorMessage = 'There was an error submitting your subscription. Please try again.';
							var errorResponse = null;
							try {
								errorResponse = JSON.parse(xhr.responseText);
								if (errorResponse && errorResponse.message) {
									errorMessage = errorResponse.message;
								}
							} catch (e) {
								// Not JSON, check if it's HTML with error info
								if (xhr.responseText && xhr.responseText.indexOf('<!--') !== -1) {
									// Try to extract error from HTML comments
									var match = xhr.responseText.match(/<!--\s*(?:ERROR|EXCEPTION|VALIDATION ERROR):\s*([^>]+)\s*-->/);
									if (match && match[1]) {
										errorMessage = 'Server error: ' + match[1].substring(0, 100);
									}
								}
							}
							
							var errorHtml = '<div class="ajax-message" style="color:#d32f2f; background-color:#ffebee; padding:15px; border:2px solid #f44336; border-radius:4px; font-weight:bold; font-size:14px; margin-bottom:20px;">';
							errorHtml += '<strong>Error:</strong> ' + errorMessage;
							
							// Display debug log if available
							if (errorResponse && errorResponse.data && errorResponse.data.debug_log && errorResponse.data.debug_log.length > 0) {
								errorHtml += '<div style="margin-top:15px; padding:10px; background-color:#fff3cd; border:1px solid #ffc107; border-radius:4px; font-size:12px; font-family:monospace; max-height:300px; overflow-y:auto;">';
								errorHtml += '<strong>Debug Log:</strong><br>';
								errorResponse.data.debug_log.forEach(function(logEntry) {
									errorHtml += '<div>' + logEntry.replace(/</g, '&lt;').replace(/>/g, '&gt;') + '</div>';
								});
								errorHtml += '</div>';
							}
							
							// Display full response data for debugging
							if (errorResponse && errorResponse.data) {
								errorHtml += '<details style="margin-top:10px;"><summary style="cursor:pointer; color:#666; font-size:12px;">Show Debug Info</summary>';
								errorHtml += '<pre style="margin-top:10px; padding:10px; background-color:#f5f5f5; border:1px solid #ddd; border-radius:4px; font-size:11px; max-height:400px; overflow-y:auto;">' + JSON.stringify(errorResponse.data, null, 2) + '</pre>';
								errorHtml += '</details>';
							}
							
							errorHtml += '</div>';
							formContainer.prepend(errorHtml);
							
							// Re-enable submit button
							submitButton.prop('disabled', false).val(originalButtonValue);
							
							// Reset reCAPTCHA if it exists
							if (typeof grecaptcha !== 'undefined' && $('.g-recaptcha').length > 0) {
								grecaptcha.reset();
							}
						}
					});

				} else {
					return false;
				}

			});

		});
	</script>

</head>

<body id="thebody" class="body">


	<header>
		<div class="header">
			<a href="https://www.luxurymarketer.com/">
				<img id="logo-image" alt="Home" src="https://www.luxurymarketer.com/wp-content/themes/LuxuryMarketer/img/LuxuryMarketer.png" border="0" style="width: 700px;" />
			</a>
		</div>
	</header>

	<form style="display: none;" action="<?php echo esc_url(get_template_directory_uri() . '/subscribe.php'); ?>" method="post" id="mc-embedded-subscribe-form" name="mc-embedded-subscribe-form" class="validate" novalidate>

		<table class="tblMain">
			<tr>
				<td>
					<div id="thePanel">

						<div id="newsletter_blurb_1" class="module_blurb">
							<h1>Subscribe to Luxury Marketer newsletters for free
							</h1>
							<p>
							</p>
						</div>
						<div id="moduleHdr_nameaddr" class="moduleHdr">
							Your Information
						</div>
						<div id="module_nameaddr_wrapper" class="module_wrapper">
							<div id="module_nameaddr_top" class="module-nameaddr-top">

							</div>
							<div id="module_nameaddr" class="module">
								<table border="0" style="width:100%;">
									<tr>
										<td id="tblOutCell1">
											<table id="tbl_nameaddr" class="tbl" border="0">
												<tr id="row_nameaddr_0">
													<td id="blurb0_nameaddr" class="blurb" colspan="2"><i>Fields with * are required.</i></td>
												</tr>
												<tr id="row_nameaddr_1">
													<td style="display:none;"></td>
													<td></td>
												</tr>
												<tr id="row_nameaddr_3">
													<td class="cell1_nameaddr" style="display:none;"><label for="f_name" id="lblf_name">First Name</label></td>
													<td class="control_cell_nameaddr">
														<input name="FNAME" type="text" maxlength="20" size="30" id="f_name" class="txtbox_nameaddr required" placeholder="First Name" />
														<span style="color:red;font-weight:bold;">*</span>
														<span id="f_name_req" class="reqmsg" style="color:Red;display:none;"> Required</span>
														<span id="rgValidatorf_name182131" class="sizemsg" style="color:Red;display:none;">Maximum 20 characters allowed.</span>
													</td>
												</tr>
												<tr id="row_nameaddr_4">
													<td class="cell1_nameaddr" style="display:none;"><label for="l_name" id="lbll_name">Last Name</label></td>
													<td class="control_cell_nameaddr">
														<input name="LNAME" type="text" maxlength="20" size="30" id="l_name" class="txtbox_nameaddr required" placeholder="Last Name" />
														<span style="color:red;font-weight:bold;">*</span>
														<span id="l_name_req" class="reqmsg" style="color:Red;display:none;"> Required</span><span id="rgValidatorl_name182131" class="sizemsg" style="color:Red;display:none;">Maximum 20 characters allowed.</span>
													</td>
												</tr>
												<tr id="row_nameaddr_5">
													<td class="cell1_nameaddr" style="display:none;"><label for="title" id="lbltitle">Title</label></td>
													<td class="control_cell_nameaddr">
														<input name="TITLE" type="text" maxlength="38" size="40" id="title" class="txtbox_nameaddr required" placeholder="Title" />
														<span style="color:red;font-weight:bold;">*</span>
														<span id="title_req" class="reqmsg" style="color:Red;display:none;"> Required</span><span id="rgValidatortitle182131" class="sizemsg" style="color:Red;display:none;">Maximum 38 characters allowed.</span>
													</td>
												</tr>

												<tr id="row_nameaddr_6">
													<td class="cell1_nameaddr" style="display:none;"><label for="company" id="lblcompany">Company</label></td>
													<td class="control_cell_nameaddr">
														<input name="COMPANY" type="text" maxlength="38" size="40" id="company" class="txtbox_nameaddr required" placeholder="Company" />
														<span style="color:red;font-weight:bold;">*</span>
														<span id="company_req" class="reqmsg" style="color:Red;display:none;"> Required</span><span id="rgValidatorcompany182131" class="sizemsg" style="color:Red;display:none;">Maximum 38 characters allowed.</span>
													</td>
												</tr>

												<tr id="row_nameaddr_6">
													<td class="cell1_nameaddr" style="display:none;"><label for="city" id="lblcompany">City</label></td>
													<td class="control_cell_nameaddr">
														<input name="CITY" type="text" maxlength="38" size="40" id="city" class="txtbox_nameaddr required" placeholder="City" />
														<span style="color:red;font-weight:bold;">*</span>
														<span id="city" class="reqmsg" style="color:Red;display:none;"> Required</span>
														<span id="rgValidatorcompany182131" class="sizemsg" style="color:Red;display:none;">Maximum 38 characters allowed.</span>
													</td>
												</tr>

												<tr id="row_nameaddr_10">
													<td class="cell1_nameaddr" style="display:none;"><label for="state" id="lblstate">State/Province</label></td>
													<td id="dropdown_state" class="control_cell_nameaddr">
														<select name="STATE" id="state" class="dropdown_nameaddr required">
															<option disabled="" selected="selected">Select State</option>
															<option value="--" disabled="">--------US States--------</option>
															<option value="AK">Alaska</option>
															<option value="AL">Alabama</option>
															<option value="AR">Arkansas</option>
															<option value="AZ">Arizona</option>
															<option value="CA">California</option>
															<option value="CO">Colorado</option>
															<option value="CT">Connecticut</option>
															<option value="DC">District of Columbia</option>
															<option value="DE">Delaware</option>
															<option value="FL">Florida</option>
															<option value="GA">Georgia</option>
															<option value="HI">Hawaii</option>
															<option value="IA">Iowa</option>
															<option value="ID">Idaho</option>
															<option value="IL">Illinois</option>
															<option value="IN">Indiana</option>
															<option value="KS">Kansas</option>
															<option value="KY">Kentucky</option>
															<option value="LA">Louisiana</option>
															<option value="MA">Massachusetts</option>
															<option value="MD">Maryland</option>
															<option value="ME">Maine</option>
															<option value="MI">Michigan</option>
															<option value="MN">Minnesota</option>
															<option value="MO">Missouri</option>
															<option value="MS">Mississippi</option>
															<option value="MT">Montana</option>
															<option value="NC">North Carolina</option>
															<option value="ND">North Dakota</option>
															<option value="NE">Nebraska</option>
															<option value="NH">New Hampshire</option>
															<option value="NJ">New Jersey</option>
															<option value="NM">New Mexico</option>
															<option value="NV">Nevada</option>
															<option value="NY">New York</option>
															<option value="OH">Ohio</option>
															<option value="OK">Oklahoma</option>
															<option value="OR">Oregon</option>
															<option value="PA">Pennsylvania</option>
															<option value="RI">Rhode Island</option>
															<option value="SC">South Carolina</option>
															<option value="SD">South Dakota</option>
															<option value="TN">Tennessee</option>
															<option value="TX">Texas</option>
															<option value="UT">Utah</option>
															<option value="VA">Virginia</option>
															<option value="VT">Vermont</option>
															<option value="WA">Washington</option>
															<option value="WI">Wisconsin</option>
															<option value="WV">West Virginia</option>
															<option value="WY">Wyoming</option>
															<option value="--" disabled="">--------US Territories--------</option>
															<option value="AA">Armed Forces Americas</option>
															<option value="AE">Armed Forces Europe</option>
															<option value="AP">Armed Forces Pacific AP</option>
															<option value="AS">American Samoa</option>
															<option value="GU">Guam</option>
															<option value="MP">Mariana Islands</option>
															<option value="PW">Palau</option>
															<option value="PR">Puerto Rico</option>
															<option value="VI">Virgin Islands</option>
															<option value="--" disabled="">------Canadian Provinces------</option>
															<option value="AB">Alberta</option>
															<option value="BC">British Columbia</option>
															<option value="MB">Manitoba</option>
															<option value="NB">New Brunswick</option>
															<option value="NL">Newfoundland and Labrador</option>
															<option value="NS">Nova Scotia</option>
															<option value="NT">Northwest Territories</option>
															<option value="NU">Nunavut</option>
															<option value="ON">Ontario</option>
															<option value="PE">Prince Edward Island</option>
															<option value="QC">Quebec</option>
															<option value="SK">Saskatchewan</option>
															<option value="YT">Yukon</option>
															<option value="--" disabled="">--------Other--------</option>
															<option>Other - Not Listed</option>

														</select>
														<span style="color:red;font-weight:bold;">*</span>
														<span id="state_req" class="reqmsg" style="color:Red;display:none;"> Required</span>
													</td>
												</tr>
												<tr id="row_nameaddr_11">
													<td class="cell1_nameaddr" style="display:none;"><label for="zip" id="lblzip">ZIP Code/Postal Code</label></td>
													<td class="control_cell_nameaddr">
														<input name="ZIPCODE" type="text" maxlength="10" size="10" id="zip" class="txtbox_nameaddr" placeholder="ZIP Code/Postal Code" />
														<span style="color:red;font-weight:bold;">*</span>
														<span id="zip_req" class="invalidmsg" style="color:Red;display:none;"> Invalid Code</span><span id="rgValidatorzip182131" class="sizemsg" style="color:Red;display:none;">Maximum 10 characters allowed.</span>
													</td>
												</tr>
												<tr id="row_nameaddr_12">
													<td class="cell1_nameaddr" style="display:none;"><label for="country" id="lblcountry">Country</label></td>
													<td id="dropdown_country" class="control_cell_nameaddr"><select name="COUNTRY" id="country" class="dropdown_locked">
															<option value="USA">United States</option>
															<option value="CAN">Canada</option>
															<option value="MEX">Mexico</option>
															<option value="AFG">Afghanistan</option>
															<option value="ALB">Albania</option>
															<option value="ALG">Algeria</option>
															<option value="AND">Andorra</option>
															<option value="ANG">Angola</option>
															<option value="ANU">Anguilla</option>
															<option value="ANT">Antigua</option>
															<option value="ARG">Argentina</option>
															<option value="ARM">Armenia</option>
															<option value="ARU">Aruba</option>
															<option value="ASC">Ascension</option>
															<option value="AUT">Australia</option>
															<option value="AUS">Austria</option>
															<option value="AZE">Azerbaijan</option>
															<option value="BAH">Bahamas</option>
															<option value="BAA">Bahrain</option>
															<option value="BAN">Bangladesh</option>
															<option value="BAR">Barbados</option>
															<option value="BRB">Barbuda</option>
															<option value="BEA">Belarus</option>
															<option value="BLU">Belau</option>
															<option value="BEL">Belgium</option>
															<option value="BEI">Belize</option>
															<option value="BEN">Benin</option>
															<option value="BER">Bermuda</option>
															<option value="BHU">Bhutan</option>
															<option value="BOL">Bolivia</option>
															<option value="BHE">Bosnia-Herzegovina</option>
															<option value="BOT">Botswana</option>
															<option value="BRA">Brazil</option>
															<option value="BVI">British Virgin Islands</option>
															<option value="BRU">Brunei</option>
															<option value="BUL">Bulgaria</option>
															<option value="BUK">Burkina Faso</option>
															<option value="BUM">Burma</option>
															<option value="BUR">Burundi</option>
															<option value="CAE">Cambodia</option>
															<option value="CAM">Cameroon</option>
															<option value="CVE">Cape Verde</option>
															<option value="CIS">Cayman Islands</option>
															<option value="CAR">Central African Republic</option>
															<option value="CHA">Chad</option>
															<option value="CSI">Channel Islands</option>
															<option value="CHL">Chile</option>
															<option value="CHI">China</option>
															<option value="COL">Colombia</option>
															<option value="COM">Comoros</option>
															<option value="CON">Congo</option>
															<option value="CKI">Cook Island</option>
															<option value="CRI">Costa Rica</option>
															<option value="CRO">Croatia</option>
															<option value="CUB">Cuba</option>
															<option value="CYP">Cyprus</option>
															<option value="CZE">Czech Republic</option>
															<option value="DEN">Denmark</option>
															<option value="DJI">Djibouti</option>
															<option value="DOM">Dominica</option>
															<option value="DRI">Dominican Republic</option>
															<option value="ECU">Ecuador</option>
															<option value="EGY">Egypt</option>
															<option value="ELS">El Salvador</option>
															<option value="EGU">Equatorial Guinea</option>
															<option value="ERI">Eritrea</option>
															<option value="EST">Estonia</option>
															<option value="ETH">Ethiopia</option>
															<option value="FAR">Faroe Islands</option>
															<option value="FIJ">Fiji Islands</option>
															<option value="FIN">Finland</option>
															<option value="FRA">France</option>
															<option value="FGU">French Guiana</option>
															<option value="FRE">French Polynesia</option>
															<option value="GAB">Gabon</option>
															<option value="GAM">Gambia</option>
															<option value="GEO">Georgia</option>
															<option value="GER">Germany</option>
															<option value="GHA">Ghana</option>
															<option value="GIB">Gibraltar</option>
															<option value="GRE">Greece</option>
															<option value="GRN">Greenland</option>
															<option value="GRA">Grenada</option>
															<option value="GUD">Guadeloupe</option>
															<option value="GUA">Guatemala</option>
															<option value="GUI">Guinea</option>
															<option value="GBI">Guinea-Bissau</option>
															<option value="GUY">Guyana</option>
															<option value="HAI">Haiti</option>
															<option value="HON">Honduras</option>
															<option value="HKO">Hong Kong</option>
															<option value="HUN">Hungary</option>
															<option value="ICE">Iceland</option>
															<option value="IND">India</option>
															<option value="INO">Indonesia</option>
															<option value="IRA">Iran</option>
															<option value="IRQ">Iraq</option>
															<option value="IRE">Ireland</option>
															<option value="IOM">Isle Of Man</option>
															<option value="ISR">Israel</option>
															<option value="ITA">Italy</option>
															<option value="IVO">Ivory Coast</option>
															<option value="JAM">Jamaica</option>
															<option value="JAP">Japan</option>
															<option value="JOR">Jordan</option>
															<option value="KAZ">Kazakhstan</option>
															<option value="KEN">Kenya</option>
															<option value="KTO">Kingdom Of Tonga</option>
															<option value="KIR">Kiribati</option>
															<option value="KOS">Kosovo</option>
															<option value="KUW">Kuwait</option>
															<option value="KYR">Kyrgyzstan</option>
															<option value="LAO">Laos</option>
															<option value="LAT">Latvia</option>
															<option value="LEB">Lebanon</option>
															<option value="LES">Lesotho</option>
															<option value="LIB">Liberia</option>
															<option value="LIY">Libya</option>
															<option value="LIE">Liechtenstein</option>
															<option value="LIT">Lithuania</option>
															<option value="LUX">Luxembourg</option>
															<option value="MAC">Macao</option>
															<option value="MAE">Macedonia</option>
															<option value="MAD">Madagascar</option>
															<option value="MAW">Malawi</option>
															<option value="MAL">Malaysia</option>
															<option value="MAV">Maldives</option>
															<option value="MAI">Mali</option>
															<option value="MAA">Malta</option>
															<option value="MRS">Marshall Islands</option>
															<option value="MAT">Martinique</option>
															<option value="MAR">Mauritania</option>
															<option value="MAU">Mauritius</option>
															<option value="MOL">Moldova</option>
															<option value="MOA">Monaco</option>
															<option value="MOG">Mongolia</option>
															<option value="MON">Monserrat</option>
															<option value="MOT">Montenegro</option>
															<option value="MOR">Morocco</option>
															<option value="MOZ">Mozambique</option>
															<option value="MYA">Myanmar</option>
															<option value="NAM">Namibia</option>
															<option value="NAU">Nauru</option>
															<option value="NEP">Nepal</option>
															<option value="NET">Netherlands</option>
															<option value="NAN">Netherlands Antilles</option>
															<option value="NCA">New Caledonia</option>
															<option value="NZE">New Zealand</option>
															<option value="NIC">Nicaragua</option>
															<option value="NIE">Niger</option>
															<option value="NIG">Nigeria</option>
															<option value="NOR">Norway</option>
															<option value="OMA">Oman</option>
															<option value="PAK">Pakistan</option>
															<option value="PAU">Palau</option>
															<option value="PAL">Palestine</option>
															<option value="PAN">Panama</option>
															<option value="PNG">Papua New Guinea</option>
															<option value="PAR">Paraguay</option>
															<option value="PER">Peru</option>
															<option value="PHI">Philippines</option>
															<option value="PIT">Pitcairn Is</option>
															<option value="POL">Poland</option>
															<option value="POR">Portugal</option>
															<option value="QAT">Qatar</option>
															<option value="REU">Reunion</option>
															<option value="ROM">Romania</option>
															<option value="RUS">Russia</option>
															<option value="RWA">Rwanda</option>
															<option value="SMR">San Marino</option>
															<option value="SAO">Sao Tome &amp; Principe</option>
															<option value="SAU">Saudi Arabia</option>
															<option value="SEN">Senegal</option>
															<option value="SER">Serbia</option>
															<option value="SEY">Seychelles</option>
															<option value="SIE">Sierra Leone</option>
															<option value="SIN">Singapore</option>
															<option value="SLO">Slovakia</option>
															<option value="SLV">Slovenia</option>
															<option value="SOL">Solomon Islands</option>
															<option value="SOM">Somalia</option>
															<option value="SAF">South Africa</option>
															<option value="SKO">South Korea</option>
															<option value="SPA">Spain</option>
															<option value="SRI">Sri Lanka</option>
															<option value="SKI">St Kitts</option>
															<option value="SLU">St Lucia</option>
															<option value="SMA">St Martin</option>
															<option value="STO">St Pierre</option>
															<option value="SVI">St Vincent And The Grenadines</option>
															<option value="SUD">Sudan</option>
															<option value="SUR">Suriname</option>
															<option value="SWA">Swaziland</option>
															<option value="SWE">Sweden</option>
															<option value="SWI">Switzerland</option>
															<option value="SYR">Syria</option>
															<option value="TAI">Taiwan</option>
															<option value="TAJ">Tajikistan</option>
															<option value="TAN">Tanzania</option>
															<option value="THA">Thailand</option>
															<option value="TOG">Togo</option>
															<option value="TON">Tonga</option>
															<option value="TRI">Trinidad &amp; Tobago</option>
															<option value="TUN">Tunisia</option>
															<option value="TUR">Turkey</option>
															<option value="TUK">Turkmenistan</option>
															<option value="TCI">Turks And Caicos Islands</option>
															<option value="TUV">Tuvalu</option>
															<option value="UGA">Uganda</option>
															<option value="UKR">Ukraine</option>
															<option value="UAE">United Arab Emirates</option>
															<option value="UNK">United Kingdom</option>
															<option value="URU">Uruguay</option>
															<option value="UZB">Uzbekistan</option>
															<option value="VAN">Vanuatu</option>
															<option value="VCI">Vatican City</option>
															<option value="VEN">Venezuela</option>
															<option value="VIE">Vietnam</option>
															<option value="WSA">Western Samoa</option>
															<option value="YEM">Yemen</option>
															<option value="ZAM">Zambia</option>
															<option value="ZIM">Zimbabwe</option>

														</select>
														<span style="color:red;font-weight:bold;">*</span>
														<span id="country_req" class="reqmsg" style="color:Red;display:none;"> Required</span>

													</td>
												</tr>
												<tr id="row_nameaddr_13">
													<td class="cell1_nameaddr" style="display:none;"><label for="phone" id="lblphone">Telephone</label></td>
													<td class="control_cell_nameaddr">
														<input name="PHONE" type="text" maxlength="20" size="12" id="phone" class="txtbox_nameaddr" placeholder="Telephone" /><span id="rgValidatorphone182131" class="sizemsg" style="color:Red;display:none;">Maximum 20 characters allowed.</span>
													</td>
												</tr>

												<tr id="row_nameaddr_16">
													<td class="cell1_nameaddr" style="display:none;">
														<label for="email" id="lblemail">Email<span style='color:red;font-weight:bold;'>*</span>
															<span class='reqstar'>*</span>
														</label>
													</td>
													<td class="control_cell_nameaddr">
														<input name="EMAIL" type="text" size="50" id="email" class="txtbox_nameaddr" placeholder="Email" />
														<span style="color:red;font-weight:bold;">*</span>
														<span id="email_req" class="reqmsg" style="color:Red;display:none;"> Required</span>
														<span id="email_regex" class="invalidmsg" style="color:Red;display:none;"> Invalid Email Address</span>
													</td>
												</tr>

												<tr id="row_nameaddr_17">
													<td class="cell1_nameaddr" style="display:none;">
														<label for="email2" id="lblemail2">
															Confirm Email
														</label>
													</td>
													<td class="control_cell_nameaddr">
														<input name="email2" type="text" size="50" id="email2" class="txtbox_nameaddr" placeholder="Confirm Email" />
														<span style="color:red;font-weight:bold;">*</span>
														<span id="email2_req" class="reqmsg" style="color:Red;display:none;"> Required</span>
														<span id="email2_compare" class="nomatchmsg" style="color:Red;display:none;"> Email does not match</span>
													</td>
												</tr>


												<tr id="row_nameaddr_18">
													<td id="blurb18_nameaddr" class="blurb" colspan="2"></td>
												</tr>
											</table>
										</td>
										<td id="tblOutCell2" class="tblOutCell2" valign="top"></td>
									</tr>
								</table>
							</div>
							<div id="module_nameaddr_bottom" class="module-nameaddr-bottom">

							</div>
						</div>
						<div id="moduleHdr_demos" class="moduleHdr">
							Research Sector
						</div>
						<div id="module_demos_wrapper" class="module_wrapper">
							<div id="module_demos_top" class="module-demos-top">

							</div>
							<div id="module_demos" class="module">
								<table>
									<tr>
										<td>
											<div id="demos_question_0" class="demos_question">
												<span>Please check the ONE category that best describes your industry sector:</span>
												<span id="bcode_req" class="reqmsg" style="color:Red;display:none;"> Required</span>
											</div>
											<div id="demos_answer_0" class="demos_answer">
												<select name="CATEGORY" id="bcode" class="dropdown_demos required">
													<option selected="selected"></option>
													<option>Advertising, marketing and PR</option>
													<option>Architecture, home and design</option>
													<option>Art and auctions</option>
													<option>Associations</option>
													<option>Beauty, perfumes and cosmetics</option>
													<option>Cars, jets and yachts</option>
													<option>China</option>
													<option>Consumer electronics</option>
													<option>Couture, fashion and leather goods</option>
													<option>Education</option>
													<option>Entertainment</option>
													<option>Environment and sustainability</option>
													<option>Financial services and wealth management</option>
													<option>Food, fine dining, wines and spirits</option>
													<option>Government</option>
													<option>Health and wellness</option>
													<option>Legal</option>
													<option>Manufacturing</option>
													<option>Media and publishing</option>
													<option>Philanthropy, foundations and nonprofits</option>
													<option>Real estate</option>
													<option>Research</option>
													<option>Retail</option>
													<option>Software and technology</option>
													<option>Sports</option>
													<option>Travel and hospitality</option>
													<option>Watches and jewelry</option>
													<option>Other</option>
												</select>
												<span style="color:red;font-weight:bold;">*</span>
												<div id="demos_answer_other_0" class="demos_answer_other" style="display: none;">
													<label for="bcode_other">If Other, please specify <br>
													</label>
													<input name="MMERGE7" type="text" maxlength="20" id="bcode_other" /><span id="rgValidatorbcode182131" class="sizemsg" style="color:Red;display:none;">Maximum 20 characters allowed.</span><span id="bcode_other_req" class="reqmsg" style="color:Red;display:none;"> Required</span>
												</div>
											</div>
										</td>
									</tr>
								</table>
							</div>
							<div id="module_demos_bottom" class="module-demos-bottom">

							</div>
						</div>
						<div id="module_submit_wrapper" class="module_wrapper">
							<div id="module_submit_top" class="module-submit-top">

							</div>
							<div class="module_submit">
								<?php 
								// Display reCAPTCHA if configured
								$recaptcha_options = get_option('recaptcha_options', array());
								$recaptcha_site_key = isset($recaptcha_options['site_key']) ? $recaptcha_options['site_key'] : '';
								$recaptcha_theme = isset($recaptcha_options['comments_theme']) ? $recaptcha_options['comments_theme'] : 'light';
								$recaptcha_language = isset($recaptcha_options['recaptcha_language']) ? $recaptcha_options['recaptcha_language'] : 'en';
								
								if (!empty($recaptcha_site_key)) {
									echo '<div style="margin: 15px 0;">';
									echo '<div class="g-recaptcha" data-sitekey="' . esc_attr($recaptcha_site_key) . '" data-theme="' . esc_attr($recaptcha_theme) . '"></div>';
									echo '<script type="text/javascript" src="https://www.google.com/recaptcha/api.js?hl=' . esc_attr($recaptcha_language) . '"></script>';
									echo '</div>';
								}
								?>
								<input type="submit" name="submit_btn" value="Submit" id="submit_btn" />
							</div>
							<div id="module_submit_bottom" class="module-submit-bottom">

							</div>
						</div>
					</div>
				</td>
			</tr>
		</table>

	</form>

	<table class="tblMain thankyou" style="display:none;">
		<tbody>
			<tr>
				<td>
					<div id="thePanel">

						<div id="news_confirm_blurb_1" class="module_blurb">
							<p>Thank you for subscribing to the complimentary <i>Luxury Marketer</i> newsletter. You should receive your first newsletter within a few days.
							</p>
						</div>
						<div id="news_confirm_blurb_3" class="module_blurb">
							<p><a href="https://www.luxurymarketer.com">Return to the Luxury Marketer homepage</a>.
							</p>
						</div>
					</div>
				</td>
			</tr>
		</tbody>
	</table>



	<section>
		<table>
			<tr>
				<td>
					<span class="heading">FOLLOW US: </span>
					<span class="mr_social_sharing"> <a href="https://www.instagram.com/luxurymarketer/" class="mr_social_sharing_popup_link" rel="nofollow"> <img src="<?= $url_root ?>/img/sharing/instagram.jpg" class="nopin" alt="Share on Instagram" title="Share on Instagram"> </a> </span>

					&nbsp;
					<span class="mr_social_sharing">
						<a target="_blank" href="http://www.linkedin.com/shareArticle?mini=true&amp;url=http%3A%2F%2Fwww.luxurymarketer.com" class="mr_social_sharing_popup_link" rel="nofollow">
							<img src="/wp-content/themes/LuxuryMarketer/img/sharing/linkedin.png" class="nopin" alt="Share on LinkedIn" title="Share on LinkedIn"> </a>
					</span>

				</td>
			</tr>
		</table>
	</section>
	<footer>
		<div><i>Luxury Marketer</i> </div>

		<div>
			<a href="mailto:news@napean.com?subject=Luxury Marketer Customer Service">news@napean.com</a>
			<br />
			© Napean LLC. All rights reserved.<br />
			Luxury Marketer is published each business day. Thank you for reading us.
		</div>
	</footer>


	</form>
</body>

</html>