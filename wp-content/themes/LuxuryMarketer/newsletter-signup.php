<?php
/*
Template Name: Newsletter Signup Template
	Project:		MobileRetailNews
	Description:		Gravitymail Newsletter Signup
	Date:			6/22/2009
*/

/*if(isset($_SERVER['HTTPS'])){
    $redirect = "http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];

    header("Location: $redirect");
	die();

}*/

//error_reporting(E_ALL);
//ini_set('display_errors',1);


// Old MySQL capture code removed - now using Mailchimp via subscribe.php
// if (array_key_exists('capture', $_POST)) {
// 	mysql_connect(DB_HOST, DB_USER, DB_PASSWORD);
// 	mysql_select_db(DB_NAME);
// 	$sql = "INSERT INTO subscribers set first_name = '" . $_POST['FIRST_NAME'] . "', last_name = '" . $_POST['LAST_NAME'] . "', email = '" . $_POST['EMAIL'] . "', title = '" . $_POST['TITLE'] . "', company = '" . $_POST['COMPANY'] . "', country = '" . $_POST['COUNTRY'] . "', industry = '" . $_POST['INDUSTRY'] . "', postal_code = '" . $_POST['ZIP'] . "'";
// 	mysql_query($sql);
// 	exit;
// }

?>



<?php get_header(); ?>

<div id="contentGroup">
	<div id="content" class="narrowcolumn">

		<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
				<div class="post" id="post-<?php the_ID(); ?>">





					<h1>Subscribe to Luxury Marketer newsletters for free</h1>
					<div class="entry">
						<?php the_content('<p class="serif">Read the rest of this page &raquo;</p>'); ?>










						<h2 class="signup">Subscription details</h2>
						<div id="newsletterlist">

							<?php 
							// Display error messages
							$error_message = '';
							$success_message = '';
							
							// Debug: Show all GET parameters for troubleshooting
							if (isset($_GET['debug'])) {
								echo '<div style="background:#fff3cd; padding:10px; margin-bottom:15px; border:1px solid #ffc107;">';
								echo '<strong>Debug Info:</strong><br>';
								echo 'GET params: ' . print_r($_GET, true);
								echo '</div>';
							}
							
							if (isset($_GET['error'])) {
								$error_type = $_GET['error'];
								if ($error_type === 'validation' && isset($_GET['fields'])) {
									$error_fields = explode(',', $_GET['fields']);
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
									$error_field_labels = array();
									foreach ($error_fields as $field) {
										$error_field_labels[] = isset($field_labels[$field]) ? $field_labels[$field] : $field;
									}
									$error_message = 'Please check the following fields: ' . htmlspecialchars(implode(', ', $error_field_labels));
								} elseif ($error_type === 'api') {
									$error_message = 'There was an error submitting your subscription. Please try again.';
									if (isset($_GET['msg'])) {
										$error_message .= '<br><small>' . htmlspecialchars(urldecode($_GET['msg'])) . '</small>';
									}
								} elseif ($error_type === 'config') {
									$error_message = 'Subscription service is temporarily unavailable. Please try again later.';
								} elseif ($error_type === 'update') {
									$error_message = 'There was an error updating your subscription. Please try again.';
								} elseif ($error_type === 'exception') {
									$error_message = 'An unexpected error occurred. Please try again.';
								} else {
									$error_message = 'An error occurred. Please try again.';
								}
							} elseif (isset($_GET['status'])) {
								$error_message = htmlspecialchars($_GET['status']);
							} elseif (isset($_GET['step']) && $_GET['step'] === 'thankyou') {
								$success_message = 'Thank you! Your subscription has been confirmed.';
							}
							
							if (!empty($error_message)) {
								echo '<div style="color:#d32f2f; background-color:#ffebee; margin-bottom:15px; padding:15px; border:2px solid #f44336; border-radius:4px; font-weight:bold; font-size:14px;">';
								echo '<strong>Error:</strong> ' . $error_message;
								echo '</div>';
							}
							if (!empty($success_message)) {
								echo '<div style="color:#2e7d32; background-color:#e8f5e9; margin-bottom:15px; padding:15px; border:2px solid #4caf50; border-radius:4px; font-weight:bold; font-size:14px;">';
								echo $success_message;
								echo '</div>';
							}
							?>
							<?php 
							// Get email from GET parameter - preserve plus signs
							$prefill_email = '';
							if (isset($_GET['email'])) {
								// PHP's $_GET automatically converts + to space, so we need to parse the raw query string
								$raw_email = $_GET['email'];
								// If email contains space, check if replacing with + makes it a valid email
								if (strpos($raw_email, ' ') !== false) {
									$test_email = str_replace(' ', '+', $raw_email);
									if (is_email($test_email)) {
										$raw_email = $test_email;
									}
								}
								// Only sanitize if it's a valid email (sanitize_email preserves plus signs)
								$prefill_email = is_email($raw_email) ? sanitize_email($raw_email) : '';
							}
							?>
							<form method="POST" action="<?php echo esc_url(home_url('/wp-content/themes/LuxuryMarketer/subscribe.php')); ?>" id="newsletter-form">


								<table cellspacing=3>
									<tr>
										<td width=130> <label for="first_name">First name<span class='requiredLabelRight'>*</span></label> </td>
										<td> <span class='input_left'><input type='textbox' name='FNAME' id='first_name' class='form_element' style='' value='<?php echo isset($_POST['FNAME']) ? htmlspecialchars(stripslashes($_POST['FNAME']), ENT_QUOTES) : ''; ?>' /></span> </td>
									</tr>


									<tr>
										<td width=130> <label for="last_name">Last name<span class='requiredLabelRight'>*</span></label> </td>
										<td> <span class='input_left'><input type='textbox' name='LNAME' id='last_name' class='form_element' style='' value='<?php echo isset($_POST['LNAME']) ? htmlspecialchars(stripslashes($_POST['LNAME']), ENT_QUOTES) : ''; ?>' /></span> </td>
									</tr>


									<tr>
										<td width=130> <label for="email">Email address<span class='requiredLabelRight'>*</span></label> </td>
										<td> <span class='input_left'><input type='textbox' name='EMAIL' id='email' class='form_element' style='' value='<?php echo !empty($prefill_email) ? htmlspecialchars($prefill_email, ENT_QUOTES) : (isset($_POST['EMAIL']) ? htmlspecialchars(wp_unslash($_POST['EMAIL']), ENT_QUOTES) : ''); ?>' /></span> </td>
									</tr>
									
									<!-- Email2 field for email confirmation - user must enter manually -->
									<tr>
										<td width=130> <label for="email2">Confirm Email<span class='requiredLabelRight'>*</span></label> </td>
										<td> <span class='input_left'><input type='textbox' name='email2' id='email2' class='form_element' style='' value='<?php echo isset($_POST['email2']) ? htmlspecialchars(wp_unslash($_POST['email2']), ENT_QUOTES) : ''; ?>' /></span> </td>
									</tr>


									<tr>
										<td width=130> <label for="title">Title<span class='requiredLabelRight'>*</span></label> </td>
										<td> <span class='input_left'><input type='textbox' name='TITLE' id='title' class='form_element' style='' value='' /></span> </td>
									</tr>


									<tr>
										<td width=130> <label for="company">Company<span class='requiredLabelRight'>*</span></label> </td>
										<td> <span class="input_left"><input type='textbox' name='COMPANY' id='company' class='form_element' style='' value='' /></span> </td>
									</tr>


									<tr>
										<td width=130> <label for="country_id">Country<span class='requiredLabelRight'>*</span></label> </td>
										<td> <span class="input_left"><select name='COUNTRY' class='form_element' id='country_id'>
													<option value=''>Please select</option>
													<option value='Afghanistan'> Afghanistan </option>
													<option value='Albania'> Albania </option>
													<option value='Algeria'> Algeria </option>
													<option value='American Samoa'> American Samoa </option>
													<option value='Andorra'> Andorra </option>
													<option value='Angola'> Angola </option>
													<option value='Anguilla'> Anguilla </option>
													<option value='Antarctica'> Antarctica </option>
													<option value='Antigua and Barbuda'> Antigua and Barbuda </option>
													<option value='Argentina'> Argentina </option>
													<option value='Armenia'> Armenia </option>
													<option value='Aruba'> Aruba </option>
													<option value='Australia'> Australia </option>
													<option value='Austria'> Austria </option>
													<option value='Azerbaijan'> Azerbaijan </option>
													<option value='Bahamas'> Bahamas </option>
													<option value='Bahrain'> Bahrain </option>
													<option value=' Bangladesh'> Bangladesh </option>
													<option value='Barbados'> Barbados </option>
													<option value='Belarus'> Belarus </option>
													<option value='Belgium'> Belgium </option>
													<option value='Belize'> Belize </option>
													<option value='Benin'> Benin </option>
													<option value='Bermuda'> Bermuda </option>
													<option value='Bhutan'> Bhutan </option>
													<option value='Bolivia'> Bolivia </option>
													<option value='Bosnia and Herzegovina'> Bosnia and Herzegovina </option>
													<option value='Botswana'> Botswana </option>
													<option value='Bouvet Island'> Bouvet Island </option>
													<option value='Brazil'> Brazil </option>
													<option value='British Indian Ocean Territory'> British Indian Ocean Territory </option>
													<option value='Brunei'> Brunei </option>
													<option value='Bulgaria'> Bulgaria </option>
													<option value='Burkina Faso'> Burkina Faso </option>
													<option value='Burundi'> Burundi </option>
													<option value='Cambodia'> Cambodia </option>
													<option value='Cameroon'> Cameroon </option>
													<option value='Canada'> Canada </option>
													<option value='Cape Verde'> Cape Verde </option>
													<option value='Cayman Islands'> Cayman Islands </option>
													<option value='Central African Republic'> Central African Republic </option>
													<option value='Chad'> Chad </option>
													<option value='Chile'> Chile </option>
													<option value='China'> China </option>
													<option value='Christmas Island'> Christmas Island </option>
													<option value='Cocos'> Cocos </option>
													<option value='Colombia'> Colombia </option>
													<option value='Comoros Congo (Brazzaville)'> Comoros Congo (Brazzaville) </option>
													<option value='Congo, Democratic Republic of the'> Congo, Democratic Republic of the </option>
													<option value='Cook Islands'> Cook Islands </option>
													<option value='Costa Rica'> Costa Rica </option>
													<option value='Cote dIvoire'> Cote dIvoire </option>
													<option value='Croatia'> Croatia </option>
													<option value='Cuba'> Cuba </option>
													<option value='Cyprus'> Cyprus </option>
													<option value='Czech Republic'> Czech Republic </option>
													<option value='Denmark'> Denmark </option>
													<option value='Djibouti'> Djibouti </option>
													<option value='Dominica'> Dominica </option>
													<option value='Dominican Republic'> Dominican Republic </option>
													<option value='East Timor (Timor Timur)'> East Timor (Timor Timur) </option>
													<option value='Ecuador'> Ecuador </option>
													<option value='Education'> Education </option>
													<option value='Egypt'> Egypt </option>
													<option value='El Salvador'> El Salvador </option>
													<option value='Equatorial Guinea'> Equatorial Guinea </option>
													<option value='Eritrea'> Eritrea </option>
													<option value='Estonia'> Estonia </option>
													<option value='Ethiopia'> Ethiopia </option>
													<option value='Falkland Islands'> Falkland Islands </option>
													<option value='Faroe Islands'> Faroe Islands </option>
													<option value='Fiji'> Fiji </option>
													<option value='Finland'> Finland </option>
													<option value='France'> France </option>
													<option value='French Guiana'> French Guiana </option>
													<option value=' French Polynesia'> French Polynesia </option>
													<option value=' French Reunion Island'> French Reunion Island </option>
													<option value=' French Southern Territories'> French Southern Territories </option>
													<option value=' Gabon'> Gabon </option>
													<option value='Gambia, The'> Gambia, The </option>
													<option value='Georgia'> Georgia </option>
													<option value='Germany'> Germany </option>
													<option value='Ghana'> Ghana </option>
													<option value='Gibraltar'> Gibraltar </option>
													<option value='Great Britain'> Great Britain </option>
													<option value='Greece'> Greece </option>
													<option value='Greenland'> Greenland </option>
													<option value='Grenada'> Grenada </option>
													<option value='Guadaloupe'> Guadaloupe </option>
													<option value='Guam'> Guam </option>
													<option value='Guatemala'> Guatemala </option>
													<option value='Guernsey'> Guernsey </option>
													<option value='Guinea'> Guinea </option>
													<option value='Guinea-Bissau'> Guinea-Bissau </option>
													<option value='Guyana'> Guyana </option>
													<option value='Haiti'> Haiti </option>
													<option value='Heard and McDonald Islands'> Heard and McDonald Islands </option>
													<option value='Honduras'> Honduras </option>
													<option value='Hong Kong'> Hong Kong </option>
													<option value='Hungary'> Hungary </option>
													<option value=' Iceland'> Iceland </option>
													<option value=' India'> India </option>
													<option value='Indonesia'> Indonesia </option>
													<option value=' International'> International </option>
													<option value=' Iran'> Iran </option>
													<option value=' Iraq'> Iraq </option>
													<option value=' Ireland'> Ireland </option>
													<option value=' Isle of Man'> Isle of Man </option>
													<option value=' Israel'> Israel </option>
													<option value=' Italy'> Italy </option>
													<option value=' Jamaica'> Jamaica </option>
													<option value=' Japan'> Japan </option>
													<option value=' Jersey'> Jersey </option>
													<option value=' Jordan'> Jordan </option>
													<option value=' Kazakhstan'> Kazakhstan </option>
													<option value=' Kenya'> Kenya </option>
													<option value=' Kiribati'> Kiribati </option>
													<option value=' Korea, North'> Korea, North </option>
													<option value=' Korea, South'> Korea, South </option>
													<option value=' Kuwait'> Kuwait </option>
													<option value=' Kyrgyzstan'> Kyrgyzstan </option>
													<option value=' Lao Peoples Democratic Republic'> Lao Peoples Democratic Republic </option>
													<option value=' Laos'> Laos </option>
													<option value=' Latvia'> Latvia </option>
													<option value=' Lebanon'> Lebanon </option>
													<option value=' Lesotho'> Lesotho </option>
													<option value=' Liberia'> Liberia </option>
													<option value=' Libya'> Libya </option>
													<option value=' Liechtenstein'> Liechtenstein </option>
													<option value=' Lithuania'> Lithuania </option>
													<option value=' Luxembourg'> Luxembourg </option>
													<option value=' Macau'> Macau </option>
													<option value=' Macedonia, Former Yugoslav Republic of'> Macedonia, Former Yugoslav Republic of </option>
													<option value=' Madagascar'> Madagascar </option>
													<option value=' Malawi'> Malawi </option>
													<option value=' Malaysia'> Malaysia </option>
													<option value=' Maldives'> Maldives </option>
													<option value=' Mali'> Mali </option>
													<option value=' Malta'> Malta </option>
													<option value=' Marshall Islands'> Marshall Islands </option>
													<option value=' Martinique'> Martinique </option>
													<option value=' Mauritania'> Mauritania </option>
													<option value=' Mauritius'> Mauritius </option>
													<option value=' Mayotte'> Mayotte </option>
													<option value=' Mexico'> Mexico </option>
													<option value=' Micronesia, Federated States of'> Micronesia, Federated States of </option>
													<option value=' Military'> Military </option>
													<option value=' Moldova'> Moldova </option>
													<option value=' Monaco'> Monaco </option>
													<option value=' Mongolia'> Mongolia </option>
													<option value=' Montserrat'> Montserrat </option>
													<option value=' Morocco'> Morocco </option>
													<option value=' Mozambique'> Mozambique </option>
													<option value=' Myanmar'> Myanmar </option>
													<option value=' Namibia'> Namibia </option>
													<option value=' Nauru'> Nauru </option>
													<option value=' Nepal'> Nepal </option>
													<option value=' Netherlands'> Netherlands </option>
													<option value='etherlands Antilles'> etherlands Antilles </option>
													<option value=' Network'> Network </option>
													<option value=' New Caledonia'> New Caledonia </option>
													<option value=' New Zealand'> New Zealand </option>
													<option value=' Nicaragua'> Nicaragua </option>
													<option value=' Niger'> Niger </option>
													<option value=' Nigeria'> Nigeria </option>
													<option value=' Niue'> Niue </option>
													<option value=' Norfolk Island'> Norfolk Island </option>
													<option value=' Northern Mariana Islands'> Northern Mariana Islands </option>
													<option value=' Norway'> Norway </option>
													<option value=' Oman'> Oman </option>
													<option value=' Organization'> Organization </option>
													<option value=' Pakistan'> Pakistan </option>
													<option value=' Palau'> Palau </option>
													<option value=' Panama'> Panama </option>
													<option value=' Papua New Guinea'> Papua New Guinea </option>
													<option value=' Paraguay'> Paraguay </option>
													<option value=' Peru'> Peru </option>
													<option value=' Philippines'> Philippines </option>
													<option value=' Pitcairn Island'> Pitcairn Island </option>
													<option value=' Poland'> Poland </option>
													<option value=' Portugal'> Portugal </option>
													<option value=' Puerto Rico'> Puerto Rico </option>
													<option value=' Qatar'> Qatar </option>
													<option value=' Romania'> Romania </option>
													<option value=' Russia'> Russia </option>
													<option value=' Rwanda'> Rwanda </option>
													<option value=' Saint Kitts and Nevis'> Saint Kitts and Nevis </option>
													<option value=' Saint Lucia'> Saint Lucia </option>
													<option value=' Saint Vincent and The Grenadines'> Saint Vincent and The Grenadines </option>
													<option value=' Saint-Pierre and Miquelon'> Saint-Pierre and Miquelon </option>
													<option value=' Samoa'> Samoa </option>
													<option value=' San Marino'> San Marino </option>
													<option value=' Sao Tome and Principe'> Sao Tome and Principe </option>
													<option value=' Saudi Arabia'> Saudi Arabia </option>
													<option value=' Senegal'> Senegal </option>
													<option value=' Serbia and Montenegro'> Serbia and Montenegro </option>
													<option value=' Seychelles'> Seychelles </option>
													<option value=' Sierra Leone'> Sierra Leone </option>
													<option value=' Singapore'> Singapore </option>
													<option value=' Slovakia'> Slovakia </option>
													<option value=' Slovenia'> Slovenia </option>
													<option value=' Solomon Islands'> Solomon Islands </option>
													<option value=' Somalia'> Somalia </option>
													<option value=' South Africa'> South Africa </option>
													<option value='South Georgia and the South Sandwich Islands'> South Georgia and the South Sandwich Islands </option>
													<option value=' Soviet Union'> Soviet Union </option>
													<option value='Spain'> Spain </option>
													<option value=' Sri Lanka'> Sri Lanka </option>
													<option value=' St. Helena'> St. Helena </option>
													<option value=' Sudan'> Sudan </option>
													<option value=' Suriname'> Suriname </option>
													<option value=' Svalbard and Jan Mayen Islands'> Svalbard and Jan Mayen Islands </option>
													<option value=' Swaziland'> Swaziland </option>
													<option value=' Sweden'> Sweden </option>
													<option value='Switzerland'> Switzerland </option>
													<option value=' Syria'> Syria </option>
													<option value=' Taiwan'> Taiwan </option>
													<option value=' Tajikistan'> Tajikistan </option>
													<option value=' Tanzania'> Tanzania </option>
													<option value=' Thailand'> Thailand </option>
													<option value=' Togo'> Togo </option>
													<option value=' Tokelau Islands'> Tokelau Islands </option>
													<option value=' Tonga'> Tonga </option>
													<option value=' Trinidad and Tobago'> Trinidad and Tobago </option>
													<option value=' Tunisia'> Tunisia </option>
													<option value=' Turkey'> Turkey </option>
													<option value=' Turkmenistan'> Turkmenistan </option>
													<option value=' Turks and Caicos Islands'> Turks and Caicos Islands </option>
													<option value=' Tuvalu'> Tuvalu </option>
													<option value=' Uganda'> Uganda </option>
													<option value=' Ukraine'> Ukraine </option>
													<option value='United Arab Emirates'> United Arab Emirates </option>
													<option value=' United Kingdom'> United Kingdom </option>
													<option value='United States' SELECTED>United States </option>
													<option value=' Uruguay'> Uruguay </option>
													<option value=' US Minor Outlying Islands'> US Minor Outlying Islands </option>
													<option value=' Uzbekistan'> Uzbekistan </option>
													<option value=' Vanuatu'> Vanuatu </option>
													<option value=' Vatican City'> Vatican City </option>
													<option value=' Venezuela'> Venezuela </option>
													<option value=' Vietnam'> Vietnam </option>
													<option value=' Virgin Islands'> Virgin Islands </option>
													<option value=' Virgin Islands'> Virgin Islands </option>
													<option value=' Wallis and Futuna Islands'> Wallis and Futuna Islands </option>
													<option value=' Western Sahara'> Western Sahara </option>
													<option value=' Yemen'> Yemen </option>
													<option value=' Yugoslavia'> Yugoslavia </option>
													<option value=' Zaire'> Zaire </option>
													<option value=' Zambia'> Zambia </option>
													<option value=' Zimbabwe'> Zimbabwe </option>
												</select></span> </td>
									</tr>


									<tr>
										<td width=130> <label for="job_industry_id">Industry<span class='requiredLabelRight'>*</span></label> </td>
										<td> <span class="input_left"><select name='CATEGORY' class='form_element' id='job_industry_id'>
													<option value=''>Please select</option>
													<option value='Ad networks'>Ad networks</option>
													<option value='Advertising'>Advertising</option>
													<option value='Advertising agencies'>Advertising agencies</option>
													<option value='Apparel and accessories'>Apparel and accessories</option>
													<option value='Arts and entertainment'>Arts and entertainment</option>
													<option value='Associations'>Associations</option>
													<option value='Automotive'>Automotive</option>
													<option value='Banking and payments'>Banking and payments</option>
													<option value='Business to business'>Business to business</option>
													<option value='Carrier networks'>Carrier networks</option>
													<option value='Commerce'>Commerce</option>
													<option value='Consumer electronics'>Consumer electronics</option>
													<option value='Consumer packaged goods'>Consumer packaged goods</option>
													<option value='Content'>Content</option>
													<option value='Database/CRM'>Database/CRM</option>
													<option value='Education'>Education</option>
													<option value='Email'>Email</option>
													<option value='Financial services'>Financial services</option>
													<option value='Food and beverage'>Food and beverage</option>
													<option value='Gaming'>Gaming</option>
													<option value='Government'>Government</option>
													<option value='Healthcare'>Healthcare</option>
													<option value='Home furnishings'>Home furnishings</option>
													<option value='Human resources'>Human resources</option>
													<option value='Legal/privacy'>Legal/privacy</option>
													<option value='Marketing'>Marketing</option>
													<option value='Manufacturers'>Manufacturers</option>
													<option value='Media/publishing'>Media/publishing</option>
													<option value='Messaging'>Messaging</option>
													<option value='Mobile services'>Mobile services</option>
													<option value='Music'>Music</option>
													<option value='Nonprofits'>Nonprofits</option>
													<option value='Politics'>Politics</option>
													<option value='Real estate'>Real estate</option>
													<option value='Research'>Research</option>
													<option value='Retail'>Retail</option>
													<option value='Search'>Search</option>
													<option value='Social networks'>Social networks</option>
													<option value='Software and technology'>Software and technology</option>
													<option value='Sports'>Sports</option>
													<option value='Telecommunications'>Telecommunications</option>
													<option value='Television'>Television</option>
													<option value='Travel'>Travel</option>
													<option value='Video'>Video</option>
													<option value='Other'>Other</option>
												</select></span> </td>
									</tr>


									<tr>
										<td width=130> <label for="city">City<span class='requiredLabelRight'>*</span></label> </td>
										<td> <span class='input_left'><input type='textbox' name='CITY' id='city' class='form_element' style='' value='<?php echo isset($_POST['CITY']) ? htmlspecialchars(stripslashes($_POST['CITY']), ENT_QUOTES) : ''; ?>' /></span> </td>
									</tr>
									
									<tr>
										<td width=130> <label for="state">State<span class='requiredLabelRight'>*</span></label> </td>
										<td> <span class='input_left'><input type='textbox' name='STATE' id='state' class='form_element' style='' value='<?php echo isset($_POST['STATE']) ? htmlspecialchars(stripslashes($_POST['STATE']), ENT_QUOTES) : ''; ?>' /></span> </td>
									</tr>
									
									<tr>
										<td width=130> <label for="postal_code">ZIP/post code<span class='requiredLabelRight'>*</span></label> </td>
										<td> <span class='input_left'><input type='textbox' name='ZIPCODE' id='postal_code' class='form_element' style='' value='<?php echo isset($_POST['ZIPCODE']) ? htmlspecialchars(stripslashes($_POST['ZIPCODE']), ENT_QUOTES) : ''; ?>' /></span> </td>

									</tr>


									<tr>
										<td width=130> </td>

										<td>





										</td>
									</tr>


									</fieldset>
								</table>

								<hr><br />
								
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
								
								<span class='input_button'><input name="Submit" value="Subscribe" border="0" name="imageField" width="78" height="29" type="submit"></span>
							</form>
						</div>



						<?php wp_link_pages(array('before' => '<p><strong>Pages:</strong> ', 'after' => '</p>', 'next_or_number' => 'number')); ?>
					</div>

				</div>
		<?php endwhile;
		endif; ?>



	</div>

	<?php include(TEMPLATEPATH . '/sidebar-right.php'); ?>

</div>

<?php get_sidebar(); ?>
<?php get_footer(); ?>