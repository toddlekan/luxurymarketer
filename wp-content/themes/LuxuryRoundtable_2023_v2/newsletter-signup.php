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


if (array_key_exists('capture', $_POST)) {

	//insert it into mysql
	mysql_connect(DB_HOST, DB_USER, DB_PASSWORD);
	mysql_select_db(DB_NAME);
	$sql = "INSERT INTO subscribers set first_name = '" . $_POST['FIRST_NAME'] . "', last_name = '" . $_POST['LAST_NAME'] . "', email = '" . $_POST['EMAIL'] . "', title = '" . $_POST['TITLE'] . "', company = '" . $_POST['COMPANY'] . "', country = '" . $_POST['COUNTRY'] . "', industry = '" . $_POST['INDUSTRY'] . "', postal_code = '" . $_POST['ZIP'] . "'";
	//print $sql;
	mysql_query($sql);
	exit;
}

?>



<?php get_header(); ?>

<div id="contentGroup">
	<div id="content" class="narrowcolumn">

		<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
				<div class="post" id="post-<?php the_ID(); ?>">





					<h1>Subscribe to Luxury Roundtable newsletters for free</h1>
					<div class="entry">
						<?php the_content('<p class="serif">Read the rest of this page &raquo;</p>'); ?>







						<script language="javascript">
							<!--
							function validateForm() {
								var submitForm = true;
								var errors = "We need a little more information. Please enter:\n\n";
								var notvalid = "=";
								var ok1 = "yes";
								var temp1;
								for (var i = 0; i < document.form.comments.value.length; i++) {
									temp1 = "" + document.form.comments.value.substring(i, i + 1);
									if (notvalid.indexOf(temp1) == "0") {
										errors += "Remove Invalid Characters from comments, \n";
										submitForm = false;
									}

								}

								if (document.form.firstname.value == "") {
									errors += " - Your name,\n";
									submitForm = false;
								}

								if (document.form.address.value == " ") {
									errors += " - Your address,\n";
									submitForm = false;
								}

								if (document.form.url.value == "") {
									errors += " - Your website,\n";
									submitForm = false;
								}

								if (document.form.company.value == "") {
									errors += " - Your compnay name,\n";
									submitForm = false;
								}

								if (document.form.email.value != "") {

									if ((document.form.email.value.indexOf("@") == -1) ||
										(document.form.email.value.indexOf(".") == -1)) {

										errors += " - Your valid email address needs a @ and .,\n";
										submitForm = false;
									}

								} else

								{

									errors += " - Your email address is blank,\n";
									submitForm = false;
								}

								if (!submitForm) {
									errors += "\nand then re-submit the form.\n\nThanks!";
									alert(errors);
								}

								return (submitForm);
							}


							function captureData(docForm) {

								var strSubmit = '';
								var formElem;
								var strLastElemName = '';

								for (i = 0; i < docForm.elements.length; i++) {
									formElem = docForm.elements[i];
									switch (formElem.type) {
										// Text, select, hidden, password, textarea elements
										case 'text':
										case 'select-one':
										case 'hidden':
										case 'password':
										case 'textarea':
											strSubmit += formElem.name +
												'=' + escape(formElem.value) + '&'
											break;
									}
								}

								strSubmit += 'capture=yes';

								var xmlHttpReq = false;

								// Mozilla/Safari
								if (window.XMLHttpRequest) {
									xmlHttpReq = new XMLHttpRequest();
									xmlHttpReq.overrideMimeType('text/xml');
								}
								// IE
								else if (window.ActiveXObject) {
									xmlHttpReq = new ActiveXObject("Microsoft.XMLHTTP");
								}

								xmlHttpReq.open('POST', '/newsletter', true);
								xmlHttpReq.setRequestHeader('Content-Type',
									'application/x-www-form-urlencoded');
								xmlHttpReq.onreadystatechange = function() {
									if (xmlHttpReq.readyState == 4) {

										if (xmlHttpReq.status == 200) {

											//eval(strResultFunc + '(xmlHttpReq.responseText;);');
											docForm.submit();

										}
									}
								}

								xmlHttpReq.send(strSubmit);
							}
							// 
							-->
						</script>



						<h2 class="signup">Subscription details</h2>
						<div id="newsletterlist">

							<div style="color:red"><?= $_GET['status']; ?></div>
							<form method="POST" action="/wp-content/themes/LD2016/subscribe.php" onsubmit="captureData(this); return false;">


								<table cellspacing=3>
									<tr>
										<td width=130> <label for="first_name">First name<span class='requiredLabelRight'>*</span></label> </td>
										<td> <span class='input_left'><input type='textbox' name='FIRST_NAME' id='first_name' class='form_element' style='' value='' /></span> </td>
									</tr>


									<tr>
										<td width=130> <label for="last_name">Last name<span class='requiredLabelRight'>*</span></label> </td>
										<td> <span class='input_left'><input type='textbox' name='LAST_NAME' id='last_name' class='form_element' style='' value='' /></span> </td>
									</tr>


									<tr>
										<td width=130> <label for="email">Email address<span class='requiredLabelRight'>*</span></label> </td>
										<td> <span class='input_left'><input type='textbox' name='EMAIL' id='email' class='form_element' style='' value='<?= htmlspecialchars(stripslashes(strip_tags($_POST['email'])), ENT_QUOTES) ?>' /></span> </td>
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
										<td> <span class="input_left"><select name='INDUSTRY' class='form_element' id='job_industry_id'>
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
										<td width=130> <label for="postal_code">ZIP/post code<span class='requiredLabelRight'>*</span></label> </td>
										<td> <span class='input_left'><input type='textbox' name='ZIP' id='postal_code' class='form_element' style='' value='' /></span> </td>

									</tr>


									<tr>
										<td width=130> </td>

										<td>





										</td>
									</tr>


									</fieldset>
								</table>

								<hr><br />
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