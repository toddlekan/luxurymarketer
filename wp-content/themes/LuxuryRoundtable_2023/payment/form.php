<?
$dir_url = dirname(__FILE__)."/..";
$dir_url = substr($dir_url, strpos($dir_url, '/wp-content'));

$email = $_POST['create_account_email'];

if(!$email){

	$current_user = wp_get_current_user();
	
	$email =  $current_user->user_email;

}
?>

<script type="text/javascript" src="<?=$dir_url?>/js/magento-products-shortcode.js?<?=time();?>"></script>	

<link rel="stylesheet" href="<?=$dir_url?>/css/magento-products-shortcode.css" type="text/css" media="screen" />

<style>
.magento ul{margin-left: -39px;}

.magento ul li{margin-bottom: 10px;}

.magento ul li input, .magento ul li select{width: 250px;}

.magento ul li #cc_exp_month, .magento ul li #cc_exp_year, .magento ul li #cc_cid{width: 80px;}

</style>

<div class="magento">
	
	
	
	<form id="magentoForm" class="stepTwo" action="<?=$dir_url?>/magento-products-shortcode.php" method="post">
		<input type="hidden" name="product" value="job" />
		<input type="hidden" value="job" name="sku" />
		<input type="hidden" name="email" value="<?=$email?>" />
		
			<fieldset id="payment" class="">
				<legend>Payment Details</legend>

				<ul>

					<li class="errors"><div></div></li>
					
					
					<li>
						<label>&nbsp;</label>
						<img src="<?=$dir_url?>/img/cc_type.gif" alt="CC Type">
						<input type="hidden" name="cc_type" id="cc_type" />
					</li>

					<li>
						<label for="cc_number">Credit Card Number<span class="requiredLabelRight">*</span></label>
						<input name="cc_number" id="cc_number" class="required" label="Credit Card Number" value="" type="text" autocomplete="off">
					</li>

					<li class="adjacent-elements">
						<label for="cc_exp_month">Credit Card Expiration<span class="requiredLabelRight">*</span></label>
						<select name="cc_exp_month" id="cc_exp_month" class='required'>
									<option value="">Month</option>
									<option value="1">01</option>
									<option value="2">02</option>
									<option value="3">03</option>
									<option value="4">04</option>

									<option value="5">05</option>
									<option value="6">06</option>
									<option value="7">07</option>
									<option value="8">08</option>
									<option value="9">09</option>
									<option value="10">10</option>

									<option value="11">11</option>
									<option value="12">12</option></select>
							&nbsp;
							
							<select name="cc_exp_year" id="cc_exp_year" class='required'>
									<option value="">Year</option>
									<?

									$years = years();
										foreach($years as $year){
									?>
										<option value="<?=$year?>"><?=$year?></option>
									<?}?>
							</select>	
						
					</li>
					
					<li>
						<label for="cc_cid">Card Verification Number<span class="requiredLabelRight">*</span></label>
						
						<input name="cc_cid" id="cc_cid" class="required" label="Card Verification Number" type="text" autocomplete="off">
					
						<a class="cvv-what-is-this" href="#">What is this?</a>
						
						<div style="display: none;">
							<div id="payment-tool-tip" class="tool-tip" style="top: 5%; left: 5%;">
								<div class="btn-close"><a title="Close" id="payment-tool-tip-close" href="#">Close</a></div>
								<div class="tool-tip-content">
									<img title="Card Verification Number Visual Reference" alt="Card Verification Number Visual Reference" src="<?=$dir_url?>/img/cvv.gif">
								</div>
							</div>
						</div>
					
					</li>
					<li>
						<label for="cc_owner">Cardholder Name<span class="requiredLabelRight">*</span></label>
						<input name="cc_owner" id="cc_owner" class="required" label="Cardholder Name" type="text">
					</li>
					
				</ul>
			</fieldset>

			<fieldset id="billingAddress" class="">
				<legend>Billing Address</legend>
				<p>Must match the address associated with your credit card</p>
				<ul>
					<li>
						<label for="bill_telephone">Telephone<span class='requiredLabelRight'>*</span></label>

						<input type='text' name='bill_telephone' id='bill_telephone' class='required'  label="Telephone" />
					</li>

					<li>
						<label for="bill_street">Address<span class="requiredLabelRight">*</span></label>
						<input name="bill_street" id="bill_street" class="required" label="Address" type="text">			
					</li>

					<li>
						<label for="bill_street2">Address 2</label>
						<input name="bill_street2" id="bill_street2" label="Address" type="text">			
					</li>

					<li>
						<label for="bill_city">City<span class="requiredLabelRight">*</span></label>
						<input name="bill_city" id="bill_city" class="required" label="City" type="text">			
					</li>

					<li>

						<label for="bill_country_id">Country<span class="requiredLabelRight">*</span></label>
						<select name="bill_country_id" class='required bill country' label="Country"  id="bill_country_id">
							<?= countries() ?>
						</select>
					</li>
					
					<li>
						<label for="bill_region_id">State/Province<span class="requiredLabelRight">*</span></label>

						<select name="bill_region_id_select" id="bill_region_id_select" label="Region" class="bill select">
							<option value="0" country_id="0" region_name=""></option>
							<?= regions() ?>

						</select>
						<input name="bill_region" id="bill_region" class="required bill text" value="" type="text" style="display:none" />
						<input name="bill_region_id" id="bill_region_id" class="bill id" type="hidden" />
					</li>

					<li>
						<label for="bill_postcode">Zip<span class="requiredLabelRight">*</span></label>
						<input name="bill_postcode" id="bill_postcode" class="required" label="Zip" value="" type="text">
					</li>

					<li>
						<ul class="clr" style="margin-left:0;">
							<li>
								<button type="button" id="stepTwoSubmit">Submit Payment Now</button>
							</li>
							<li>  &nbsp;  
							</li>
							<li>
								<button type="button" id="stepTwoBack">Go Back</button>
							</li>
						</ul>
					</li>
					
					
					
				</ul>

			</fieldset>
			
		</form>
</div>		

	<?

	function countries() {
		$countries = '
<option value="AF">Afghanistan</option>
						<option value="US">United States</option>
						<option value="AX">Åland Islands</option>
						<option value="AL">Albania</option>
						<option value="DZ">Algeria</option>
						<option value="AS">American Samoa</option>
						<option value="AD">Andorra</option>
						<option value="AO">Angola</option>
						<option value="AI">Anguilla</option>
						<option value="AQ">Antarctica</option>
						<option value="AG">Antigua and Barbuda</option>
						<option value="AR">Argentina</option>
						<option value="AM">Armenia</option>
						<option value="AW">Aruba</option>
						<option value="AU">Australia</option>
						<option value="AT">Austria</option>
						<option value="AZ">Azerbaijan</option>
						<option value="BS">Bahamas</option>
						<option value="BH">Bahrain</option>
						<option value="BD">Bangladesh</option>
						<option value="BB">Barbados</option>
						<option value="BY">Belarus</option>
						<option value="BE">Belgium</option>
						<option value="BZ">Belize</option>
						<option value="BJ">Benin</option>
						<option value="BM">Bermuda</option>
						<option value="BT">Bhutan</option>
						<option value="BO">Bolivia</option>
						<option value="BA">Bosnia and Herzegovina</option>
						<option value="BW">Botswana</option>
						<option value="BV">Bouvet Island</option>
						<option value="BR">Brazil</option>
						<option value="IO">British Indian Ocean Territory</option>
						<option value="VG">British Virgin Islands</option>
						<option value="BN">Brunei</option>
						<option value="BG">Bulgaria</option>
						<option value="BF">Burkina Faso</option>
						<option value="BI">Burundi</option>
						<option value="KH">Cambodia</option>
						<option value="CM">Cameroon</option>
						<option value="CA">Canada</option>
						<option value="CV">Cape Verde</option>
						<option value="KY">Cayman Islands</option>
						<option value="CF">Central African Republic</option>
						<option value="TD">Chad</option>
						<option value="CL">Chile</option>
						<option value="CN">China</option>
						<option value="CX">Christmas Island</option>
						<option value="CC">Cocos [Keeling] Islands</option>
						<option value="CO">Colombia</option>
						<option value="KM">Comoros</option>
						<option value="CG">Congo - Brazzaville</option>
						<option value="CD">Congo - Kinshasa</option>
						<option value="CK">Cook Islands</option>
						<option value="CR">Costa Rica</option>
						<option value="CI">Côte d’Ivoire</option>
						<option value="HR">Croatia</option>
						<option value="CU">Cuba</option>
						<option value="CY">Cyprus</option>
						<option value="CZ">Czech Republic</option>
						<option value="DK">Denmark</option>
						<option value="DJ">Djibouti</option>
						<option value="DM">Dominica</option>
						<option value="DO">Dominican Republic</option>
						<option value="EC">Ecuador</option>
						<option value="EG">Egypt</option>
						<option value="SV">El Salvador</option>
						<option value="GQ">Equatorial Guinea</option>
						<option value="ER">Eritrea</option>
						<option value="EE">Estonia</option>
						<option value="ET">Ethiopia</option>
						<option value="FK">Falkland Islands</option>
						<option value="FO">Faroe Islands</option>
						<option value="FJ">Fiji</option>
						<option value="FI">Finland</option>
						<option value="FR">France</option>
						<option value="GF">French Guiana</option>
						<option value="PF">French Polynesia</option>
						<option value="TF">French Southern Territories</option>
						<option value="GA">Gabon</option>
						<option value="GM">Gambia</option>
						<option value="GE">Georgia</option>
						<option value="DE">Germany</option>
						<option value="GH">Ghana</option>
						<option value="GI">Gibraltar</option>
						<option value="GR">Greece</option>
						<option value="GL">Greenland</option>
						<option value="GD">Grenada</option>
						<option value="GP">Guadeloupe</option>
						<option value="GU">Guam</option>
						<option value="GT">Guatemala</option>
						<option value="GG">Guernsey</option>
						<option value="GN">Guinea</option>
						<option value="GW">Guinea-Bissau</option>
						<option value="GY">Guyana</option>
						<option value="HT">Haiti</option>
						<option value="HM">Heard Island and McDonald Islands</option>
						<option value="HN">Honduras</option>
						<option value="HK">Hong Kong SAR China</option>
						<option value="HU">Hungary</option>
						<option value="IS">Iceland</option>
						<option value="IN">India</option>
						<option value="ID">Indonesia</option>
						<option value="IR">Iran</option>
						<option value="IQ">Iraq</option>
						<option value="IE">Ireland</option>
						<option value="IM">Isle of Man</option>
						<option value="IL">Israel</option>
						<option value="IT">Italy</option>
						<option value="JM">Jamaica</option>
						<option value="JP">Japan</option>
						<option value="JE">Jersey</option>
						<option value="JO">Jordan</option>
						<option value="KZ">Kazakhstan</option>
						<option value="KE">Kenya</option>
						<option value="KI">Kiribati</option>
						<option value="KW">Kuwait</option>
						<option value="KG">Kyrgyzstan</option>
						<option value="LA">Laos</option>
						<option value="LV">Latvia</option>
						<option value="LB">Lebanon</option>
						<option value="LS">Lesotho</option>
						<option value="LR">Liberia</option>
						<option value="LY">Libya</option>
						<option value="LI">Liechtenstein</option>
						<option value="LT">Lithuania</option>
						<option value="LU">Luxembourg</option>
						<option value="MO">Macau SAR China</option>
						<option value="MK">Macedonia</option>
						<option value="MG">Madagascar</option>
						<option value="MW">Malawi</option>
						<option value="MY">Malaysia</option>
						<option value="MV">Maldives</option>
						<option value="ML">Mali</option>
						<option value="MT">Malta</option>
						<option value="MH">Marshall Islands</option>
						<option value="MQ">Martinique</option>
						<option value="MR">Mauritania</option>
						<option value="MU">Mauritius</option>
						<option value="YT">Mayotte</option>
						<option value="MX">Mexico</option>
						<option value="FM">Micronesia</option>
						<option value="MD">Moldova</option>
						<option value="MC">Monaco</option>
						<option value="MN">Mongolia</option>
						<option value="ME">Montenegro</option>
						<option value="MS">Montserrat</option>
						<option value="MA">Morocco</option>
						<option value="MZ">Mozambique</option>
						<option value="MM">Myanmar [Burma]</option>
						<option value="NA">Namibia</option>
						<option value="NR">Nauru</option>
						<option value="NP">Nepal</option>
						<option value="NL">Netherlands</option>
						<option value="AN">Netherlands Antilles</option>
						<option value="NC">New Caledonia</option>
						<option value="NZ">New Zealand</option>
						<option value="NI">Nicaragua</option>
						<option value="NE">Niger</option>
						<option value="NG">Nigeria</option>
						<option value="NU">Niue</option>
						<option value="NF">Norfolk Island</option>
						<option value="MP">Northern Mariana Islands</option>
						<option value="KP">North Korea</option>
						<option value="NO">Norway</option>
						<option value="OM">Oman</option>
						<option value="PK">Pakistan</option>
						<option value="PW">Palau</option>
						<option value="PS">Palestinian Territories</option>
						<option value="PA">Panama</option>
						<option value="PG">Papua New Guinea</option>
						<option value="PY">Paraguay</option>
						<option value="PE">Peru</option>
						<option value="PH">Philippines</option>
						<option value="PN">Pitcairn Islands</option>
						<option value="PL">Poland</option>
						<option value="PT">Portugal</option>
						<option value="PR">Puerto Rico</option>
						<option value="QA">Qatar</option>
						<option value="RE">Réunion</option>
						<option value="RO">Romania</option>
						<option value="RU">Russia</option>
						<option value="RW">Rwanda</option>
						<option value="BL">Saint Barthélemy</option>
						<option value="SH">Saint Helena</option>
						<option value="KN">Saint Kitts and Nevis</option>
						<option value="LC">Saint Lucia</option>
						<option value="MF">Saint Martin</option>
						<option value="PM">Saint Pierre and Miquelon</option>
						<option value="VC">Saint Vincent and the Grenadines</option>
						<option value="WS">Samoa</option>
						<option value="SM">San Marino</option>
						<option value="ST">São Tomé and Príncipe</option>
						<option value="SA">Saudi Arabia</option>
						<option value="SN">Senegal</option>
						<option value="RS">Serbia</option>
						<option value="SC">Seychelles</option>
						<option value="SL">Sierra Leone</option>
						<option value="SG">Singapore</option>
						<option value="SK">Slovakia</option>
						<option value="SI">Slovenia</option>
						<option value="SB">Solomon Islands</option>
						<option value="SO">Somalia</option>
						<option value="ZA">South Africa</option>
						<option value="GS">South Georgia and the South Sandwich Islands</option>
						<option value="KR">South Korea</option>
						<option value="ES">Spain</option>
						<option value="LK">Sri Lanka</option>
						<option value="SD">Sudan</option>
						<option value="SR">Suriname</option>
						<option value="SJ">Svalbard and Jan Mayen</option>
						<option value="SZ">Swaziland</option>
						<option value="SE">Sweden</option>
						<option value="CH">Switzerland</option>
						<option value="SY">Syria</option>
						<option value="TW">Taiwan</option>
						<option value="TJ">Tajikistan</option>
						<option value="TZ">Tanzania</option>
						<option value="TH">Thailand</option>
						<option value="TL">Timor-Leste</option>
						<option value="TG">Togo</option>
						<option value="TK">Tokelau</option>
						<option value="TO">Tonga</option>
						<option value="TT">Trinidad and Tobago</option>
						<option value="TN">Tunisia</option>
						<option value="TR">Turkey</option>
						<option value="TM">Turkmenistan</option>
						<option value="TC">Turks and Caicos Islands</option>
						<option value="TV">Tuvalu</option>
						<option value="UG">Uganda</option>
						<option value="UA">Ukraine</option>
						<option value="AE">United Arab Emirates</option>
						<option value="GB">United Kingdom</option>
						<option value="US" selected="selected">United States</option>
						<option value="UY">Uruguay</option>
						<option value="UM">U.S. Minor Outlying Islands</option>
						<option value="VI">U.S. Virgin Islands</option>
						<option value="UZ">Uzbekistan</option>
						<option value="VU">Vanuatu</option>
						<option value="VA">Vatican City</option>
						<option value="VE">Venezuela</option>
						<option value="VN">Vietnam</option>
						<option value="WF">Wallis and Futuna</option>
						<option value="EH">Western Sahara</option>
						<option value="YE">Yemen</option>
						<option value="ZM">Zambia</option>
						<option value="ZW">Zimbabwe</option>
';

		return $countries;
	}

	function regions() {
		$regions = '
<option value="1" country_id="US" region_name="Alabama">Alabama</option>
<option value="2" country_id="US" region_name="Alaska">Alaska</option>
<option value="3" country_id="US" region_name="American Samoa">American Samoa</option>
<option value="4" country_id="US" region_name="Arizona">Arizona</option>
<option value="5" country_id="US" region_name="Arkansas">Arkansas</option>
<option value="6" country_id="US" region_name="Armed Forces Africa">Armed Forces Africa</option>
<option value="7" country_id="US" region_name="Armed Forces Americas">Armed Forces Americas</option>
<option value="8" country_id="US" region_name="Armed Forces Canada">Armed Forces Canada</option>
<option value="9" country_id="US" region_name="Armed Forces Europe">Armed Forces Europe</option>
<option value="10" country_id="US" region_name="Armed Forces Middle East">Armed Forces Middle East</option>
<option value="11" country_id="US" region_name="Armed Forces Pacific">Armed Forces Pacific</option>
<option value="12" country_id="US" region_name="California">California</option>
<option value="13" country_id="US" region_name="Colorado">Colorado</option>
<option value="14" country_id="US" region_name="Connecticut">Connecticut</option>
<option value="15" country_id="US" region_name="Delaware">Delaware</option>
<option value="16" country_id="US" region_name="District of Columbia">District of Columbia</option>
<option value="17" country_id="US" region_name="Federated States Of Micronesia">Federated States Of Micronesia</option>
<option value="18" country_id="US" region_name="Florida">Florida</option>
<option value="19" country_id="US" region_name="Georgia">Georgia</option>
<option value="20" country_id="US" region_name="Guam">Guam</option>
<option value="21" country_id="US" region_name="Hawaii">Hawaii</option>
<option value="22" country_id="US" region_name="Idaho">Idaho</option>
<option value="23" country_id="US" region_name="Illinois">Illinois</option>
<option value="24" country_id="US" region_name="Indiana">Indiana</option>
<option value="25" country_id="US" region_name="Iowa">Iowa</option>
<option value="26" country_id="US" region_name="Kansas">Kansas</option>
<option value="27" country_id="US" region_name="Kentucky">Kentucky</option>
<option value="28" country_id="US" region_name="Louisiana">Louisiana</option>
<option value="29" country_id="US" region_name="Maine">Maine</option>
<option value="30" country_id="US" region_name="Marshall Islands">Marshall Islands</option>
<option value="31" country_id="US" region_name="Maryland">Maryland</option>
<option value="32" country_id="US" region_name="Massachusetts">Massachusetts</option>
<option value="33" country_id="US" region_name="Michigan">Michigan</option>
<option value="34" country_id="US" region_name="Minnesota">Minnesota</option>
<option value="35" country_id="US" region_name="Mississippi">Mississippi</option>
<option value="36" country_id="US" region_name="Missouri">Missouri</option>
<option value="37" country_id="US" region_name="Montana">Montana</option>
<option value="38" country_id="US" region_name="Nebraska">Nebraska</option>
<option value="39" country_id="US" region_name="Nevada">Nevada</option>
<option value="40" country_id="US" region_name="New Hampshire">New Hampshire</option>
<option value="41" country_id="US" region_name="New Jersey">New Jersey</option>
<option value="42" country_id="US" region_name="New Mexico">New Mexico</option>
<option value="43" country_id="US" region_name="New York">New York</option>
<option value="44" country_id="US" region_name="North Carolina">North Carolina</option>
<option value="45" country_id="US" region_name="North Dakota">North Dakota</option>
<option value="46" country_id="US" region_name="Northern Mariana Islands">Northern Mariana Islands</option>
<option value="47" country_id="US" region_name="Ohio">Ohio</option>
<option value="48" country_id="US" region_name="Oklahoma">Oklahoma</option>
<option value="49" country_id="US" region_name="Oregon">Oregon</option>
<option value="50" country_id="US" region_name="Palau">Palau</option>
<option value="51" country_id="US" region_name="Pennsylvania">Pennsylvania</option>
<option value="52" country_id="US" region_name="Puerto Rico">Puerto Rico</option>
<option value="53" country_id="US" region_name="Rhode Island">Rhode Island</option>
<option value="54" country_id="US" region_name="South Carolina">South Carolina</option>
<option value="55" country_id="US" region_name="South Dakota">South Dakota</option>
<option value="56" country_id="US" region_name="Tennessee">Tennessee</option>
<option value="57" country_id="US" region_name="Texas">Texas</option>
<option value="58" country_id="US" region_name="Utah">Utah</option>
<option value="59" country_id="US" region_name="Vermont">Vermont</option>
<option value="60" country_id="US" region_name="Virgin Islands">Virgin Islands</option>
<option value="61" country_id="US" region_name="Virginia">Virginia</option>
<option value="62" country_id="US" region_name="Washington">Washington</option>
<option value="63" country_id="US" region_name="West Virginia">West Virginia</option>
<option value="64" country_id="US" region_name="Wisconsin">Wisconsin</option>
<option value="65" country_id="US" region_name="Wyoming">Wyoming</option>
<option value="66" country_id="CA" region_name="Alberta">Alberta</option>
<option value="67" country_id="CA" region_name="British Columbia">British Columbia</option>
<option value="68" country_id="CA" region_name="Manitoba">Manitoba</option>
<option value="69" country_id="CA" region_name="Newfoundland and Labrador">Newfoundland and Labrador</option>
<option value="70" country_id="CA" region_name="New Brunswick">New Brunswick</option>
<option value="71" country_id="CA" region_name="Nova Scotia">Nova Scotia</option>
<option value="72" country_id="CA" region_name="Northwest Territories">Northwest Territories</option>
<option value="73" country_id="CA" region_name="Nunavut">Nunavut</option>
<option value="74" country_id="CA" region_name="Ontario">Ontario</option>
<option value="75" country_id="CA" region_name="Prince Edward Island">Prince Edward Island</option>
<option value="76" country_id="CA" region_name="Quebec">Quebec</option>
<option value="77" country_id="CA" region_name="Saskatchewan">Saskatchewan</option>
<option value="78" country_id="CA" region_name="Yukon Territory">Yukon Territory</option>
<option value="79" country_id="DE" region_name="Niedersachsen">Niedersachsen</option>
<option value="80" country_id="DE" region_name="Baden-Württemberg">Baden-Württemberg</option>
<option value="81" country_id="DE" region_name="Bayern">Bayern</option>
<option value="82" country_id="DE" region_name="Berlin">Berlin</option>
<option value="83" country_id="DE" region_name="Brandenburg">Brandenburg</option>
<option value="84" country_id="DE" region_name="Bremen">Bremen</option>
<option value="85" country_id="DE" region_name="Hamburg">Hamburg</option>
<option value="86" country_id="DE" region_name="Hessen">Hessen</option>
<option value="87" country_id="DE" region_name="Mecklenburg-Vorpommern">Mecklenburg-Vorpommern</option>
<option value="88" country_id="DE" region_name="Nordrhein-Westfalen">Nordrhein-Westfalen</option>
<option value="89" country_id="DE" region_name="Rheinland-Pfalz">Rheinland-Pfalz</option>
<option value="90" country_id="DE" region_name="Saarland">Saarland</option>
<option value="91" country_id="DE" region_name="Sachsen">Sachsen</option>
<option value="92" country_id="DE" region_name="Sachsen-Anhalt">Sachsen-Anhalt</option>
<option value="93" country_id="DE" region_name="Schleswig-Holstein">Schleswig-Holstein</option>
<option value="94" country_id="DE" region_name="Thüringen">Thüringen</option>
<option value="95" country_id="AT" region_name="Wien">Wien</option>
<option value="96" country_id="AT" region_name="Niederösterreich">Niederösterreich</option>
<option value="97" country_id="AT" region_name="Oberösterreich">Oberösterreich</option>
<option value="98" country_id="AT" region_name="Salzburg">Salzburg</option>
<option value="99" country_id="AT" region_name="Kärnten">Kärnten</option>
<option value="100" country_id="AT" region_name="Steiermark">Steiermark</option>
<option value="101" country_id="AT" region_name="Tirol">Tirol</option>
<option value="102" country_id="AT" region_name="Burgenland">Burgenland</option>
<option value="103" country_id="AT" region_name="Voralberg">Voralberg</option>
<option value="104" country_id="CH" region_name="Aargau">Aargau</option>
<option value="105" country_id="CH" region_name="Appenzell Innerrhoden">Appenzell Innerrhoden</option>
<option value="106" country_id="CH" region_name="Appenzell Ausserrhoden">Appenzell Ausserrhoden</option>
<option value="107" country_id="CH" region_name="Bern">Bern</option>
<option value="108" country_id="CH" region_name="Basel-Landschaft">Basel-Landschaft</option>
<option value="109" country_id="CH" region_name="Basel-Stadt">Basel-Stadt</option>
<option value="110" country_id="CH" region_name="Freiburg">Freiburg</option>
<option value="111" country_id="CH" region_name="Genf">Genf</option>
<option value="112" country_id="CH" region_name="Glarus">Glarus</option>
<option value="113" country_id="CH" region_name="Graubünden">Graubünden</option>
<option value="114" country_id="CH" region_name="Jura">Jura</option>
<option value="115" country_id="CH" region_name="Luzern">Luzern</option>
<option value="116" country_id="CH" region_name="Neuenburg">Neuenburg</option>
<option value="117" country_id="CH" region_name="Nidwalden">Nidwalden</option>
<option value="118" country_id="CH" region_name="Obwalden">Obwalden</option>
<option value="119" country_id="CH" region_name="St. Gallen">St. Gallen</option>
<option value="120" country_id="CH" region_name="Schaffhausen">Schaffhausen</option>
<option value="121" country_id="CH" region_name="Solothurn">Solothurn</option>
<option value="122" country_id="CH" region_name="Schwyz">Schwyz</option>
<option value="123" country_id="CH" region_name="Thurgau">Thurgau</option>
<option value="124" country_id="CH" region_name="Tessin">Tessin</option>
<option value="125" country_id="CH" region_name="Uri">Uri</option>
<option value="126" country_id="CH" region_name="Waadt">Waadt</option>
<option value="127" country_id="CH" region_name="Wallis">Wallis</option>
<option value="128" country_id="CH" region_name="Zug">Zug</option>
<option value="129" country_id="CH" region_name="Zürich">Zürich</option>
<option value="130" country_id="ES" region_name="A Coruña">A Coruña</option>
<option value="131" country_id="ES" region_name="Alava">Alava</option>
<option value="132" country_id="ES" region_name="Albacete">Albacete</option>
<option value="133" country_id="ES" region_name="Alicante">Alicante</option>
<option value="134" country_id="ES" region_name="Almeria">Almeria</option>
<option value="135" country_id="ES" region_name="Asturias">Asturias</option>
<option value="136" country_id="ES" region_name="Avila">Avila</option>
<option value="137" country_id="ES" region_name="Badajoz">Badajoz</option>
<option value="138" country_id="ES" region_name="Baleares">Baleares</option>
<option value="139" country_id="ES" region_name="Barcelona">Barcelona</option>
<option value="140" country_id="ES" region_name="Burgos">Burgos</option>
<option value="141" country_id="ES" region_name="Caceres">Caceres</option>
<option value="142" country_id="ES" region_name="Cadiz">Cadiz</option>
<option value="143" country_id="ES" region_name="Cantabria">Cantabria</option>
<option value="144" country_id="ES" region_name="Castellon">Castellon</option>
<option value="145" country_id="ES" region_name="Ceuta">Ceuta</option>
<option value="146" country_id="ES" region_name="Ciudad Real">Ciudad Real</option>
<option value="147" country_id="ES" region_name="Cordoba">Cordoba</option>
<option value="148" country_id="ES" region_name="Cuenca">Cuenca</option>
<option value="149" country_id="ES" region_name="Girona">Girona</option>
<option value="150" country_id="ES" region_name="Granada">Granada</option>
<option value="151" country_id="ES" region_name="Guadalajara">Guadalajara</option>
<option value="152" country_id="ES" region_name="Guipuzcoa">Guipuzcoa</option>
<option value="153" country_id="ES" region_name="Huelva">Huelva</option>
<option value="154" country_id="ES" region_name="Huesca">Huesca</option>
<option value="155" country_id="ES" region_name="Jaen">Jaen</option>
<option value="156" country_id="ES" region_name="La Rioja">La Rioja</option>
<option value="157" country_id="ES" region_name="Las Palmas">Las Palmas</option>
<option value="158" country_id="ES" region_name="Leon">Leon</option>
<option value="159" country_id="ES" region_name="Lleida">Lleida</option>
<option value="160" country_id="ES" region_name="Lugo">Lugo</option>
<option value="161" country_id="ES" region_name="Madrid">Madrid</option>
<option value="162" country_id="ES" region_name="Malaga">Malaga</option>
<option value="163" country_id="ES" region_name="Melilla">Melilla</option>
<option value="164" country_id="ES" region_name="Murcia">Murcia</option>
<option value="165" country_id="ES" region_name="Navarra">Navarra</option>
<option value="166" country_id="ES" region_name="Ourense">Ourense</option>
<option value="167" country_id="ES" region_name="Palencia">Palencia</option>
<option value="168" country_id="ES" region_name="Pontevedra">Pontevedra</option>
<option value="169" country_id="ES" region_name="Salamanca">Salamanca</option>
<option value="170" country_id="ES" region_name="Santa Cruz de Tenerife">Santa Cruz de Tenerife</option>
<option value="171" country_id="ES" region_name="Segovia">Segovia</option>
<option value="172" country_id="ES" region_name="Sevilla">Sevilla</option>
<option value="173" country_id="ES" region_name="Soria">Soria</option>
<option value="174" country_id="ES" region_name="Tarragona">Tarragona</option>
<option value="175" country_id="ES" region_name="Teruel">Teruel</option>
<option value="176" country_id="ES" region_name="Toledo">Toledo</option>
<option value="177" country_id="ES" region_name="Valencia">Valencia</option>
<option value="178" country_id="ES" region_name="Valladolid">Valladolid</option>
<option value="179" country_id="ES" region_name="Vizcaya">Vizcaya</option>
<option value="180" country_id="ES" region_name="Zamora">Zamora</option>
<option value="181" country_id="ES" region_name="Zaragoza">Zaragoza</option>
<option value="182" country_id="FR" region_name="Ain">Ain</option>
<option value="183" country_id="FR" region_name="Aisne">Aisne</option>
<option value="184" country_id="FR" region_name="Allier">Allier</option>
<option value="185" country_id="FR" region_name="Alpes-de-Haute-Provence">Alpes-de-Haute-Provence</option>
<option value="186" country_id="FR" region_name="Hautes-Alpes">Hautes-Alpes</option>
<option value="187" country_id="FR" region_name="Alpes-Maritimes">Alpes-Maritimes</option>
<option value="188" country_id="FR" region_name="Ardèche">Ardèche</option>
<option value="189" country_id="FR" region_name="Ardennes">Ardennes</option>
<option value="190" country_id="FR" region_name="Ariège">Ariège</option>
<option value="191" country_id="FR" region_name="Aube">Aube</option>
<option value="192" country_id="FR" region_name="Aude">Aude</option>
<option value="193" country_id="FR" region_name="Aveyron">Aveyron</option>
<option value="194" country_id="FR" region_name="Bouches-du-Rhône">Bouches-du-Rhône</option>
<option value="195" country_id="FR" region_name="Calvados">Calvados</option>
<option value="196" country_id="FR" region_name="Cantal">Cantal</option>
<option value="197" country_id="FR" region_name="Charente">Charente</option>
<option value="198" country_id="FR" region_name="Charente-Maritime">Charente-Maritime</option>
<option value="199" country_id="FR" region_name="Cher">Cher</option>
<option value="200" country_id="FR" region_name="Corrèze">Corrèze</option>
<option value="201" country_id="FR" region_name="Corse-du-Sud">Corse-du-Sud</option>
<option value="202" country_id="FR" region_name="Haute-Corse">Haute-Corse</option>
<option value="203" country_id="FR" region_name="Côte-d\'Or">Côte-d\'Or</option>
<option value="204" country_id="FR" region_name="Côtes-d\'Armor">Côtes-d\'Armor</option>
<option value="205" country_id="FR" region_name="Creuse">Creuse</option>
<option value="206" country_id="FR" region_name="Dordogne">Dordogne</option>
<option value="207" country_id="FR" region_name="Doubs">Doubs</option>
<option value="208" country_id="FR" region_name="Drôme">Drôme</option>
<option value="209" country_id="FR" region_name="Eure">Eure</option>
<option value="210" country_id="FR" region_name="Eure-et-Loir">Eure-et-Loir</option>
<option value="211" country_id="FR" region_name="Finistère">Finistère</option>
<option value="212" country_id="FR" region_name="Gard">Gard</option>
<option value="213" country_id="FR" region_name="Haute-Garonne">Haute-Garonne</option>
<option value="214" country_id="FR" region_name="Gers">Gers</option>
<option value="215" country_id="FR" region_name="Gironde">Gironde</option>
<option value="216" country_id="FR" region_name="Hérault">Hérault</option>
<option value="217" country_id="FR" region_name="Ille-et-Vilaine">Ille-et-Vilaine</option>
<option value="218" country_id="FR" region_name="Indre">Indre</option>
<option value="219" country_id="FR" region_name="Indre-et-Loire">Indre-et-Loire</option>
<option value="220" country_id="FR" region_name="Isère">Isère</option>
<option value="221" country_id="FR" region_name="Jura">Jura</option>
<option value="222" country_id="FR" region_name="Landes">Landes</option>
<option value="223" country_id="FR" region_name="Loir-et-Cher">Loir-et-Cher</option>
<option value="224" country_id="FR" region_name="Loire">Loire</option>
<option value="225" country_id="FR" region_name="Haute-Loire">Haute-Loire</option>
<option value="226" country_id="FR" region_name="Loire-Atlantique">Loire-Atlantique</option>
<option value="227" country_id="FR" region_name="Loiret">Loiret</option>
<option value="228" country_id="FR" region_name="Lot">Lot</option>
<option value="229" country_id="FR" region_name="Lot-et-Garonne">Lot-et-Garonne</option>
<option value="230" country_id="FR" region_name="Lozère">Lozère</option>
<option value="231" country_id="FR" region_name="Maine-et-Loire">Maine-et-Loire</option>
<option value="232" country_id="FR" region_name="Manche">Manche</option>
<option value="233" country_id="FR" region_name="Marne">Marne</option>
<option value="234" country_id="FR" region_name="Haute-Marne">Haute-Marne</option>
<option value="235" country_id="FR" region_name="Mayenne">Mayenne</option>
<option value="236" country_id="FR" region_name="Meurthe-et-Moselle">Meurthe-et-Moselle</option>
<option value="237" country_id="FR" region_name="Meuse">Meuse</option>
<option value="238" country_id="FR" region_name="Morbihan">Morbihan</option>
<option value="239" country_id="FR" region_name="Moselle">Moselle</option>
<option value="240" country_id="FR" region_name="Nièvre">Nièvre</option>
<option value="241" country_id="FR" region_name="Nord">Nord</option>
<option value="242" country_id="FR" region_name="Oise">Oise</option>
<option value="243" country_id="FR" region_name="Orne">Orne</option>
<option value="244" country_id="FR" region_name="Pas-de-Calais">Pas-de-Calais</option>
<option value="245" country_id="FR" region_name="Puy-de-Dôme">Puy-de-Dôme</option>
<option value="246" country_id="FR" region_name="Pyrénées-Atlantiques">Pyrénées-Atlantiques</option>
<option value="247" country_id="FR" region_name="Hautes-Pyrénées">Hautes-Pyrénées</option>
<option value="248" country_id="FR" region_name="Pyrénées-Orientales">Pyrénées-Orientales</option>
<option value="249" country_id="FR" region_name="Bas-Rhin">Bas-Rhin</option>
<option value="250" country_id="FR" region_name="Haut-Rhin">Haut-Rhin</option>
<option value="251" country_id="FR" region_name="Rhône">Rhône</option>
<option value="252" country_id="FR" region_name="Haute-Saône">Haute-Saône</option>
<option value="253" country_id="FR" region_name="Saône-et-Loire">Saône-et-Loire</option>
<option value="254" country_id="FR" region_name="Sarthe">Sarthe</option>
<option value="255" country_id="FR" region_name="Savoie">Savoie</option>
<option value="256" country_id="FR" region_name="Haute-Savoie">Haute-Savoie</option>
<option value="257" country_id="FR" region_name="Paris">Paris</option>
<option value="258" country_id="FR" region_name="Seine-Maritime">Seine-Maritime</option>
<option value="259" country_id="FR" region_name="Seine-et-Marne">Seine-et-Marne</option>
<option value="260" country_id="FR" region_name="Yvelines">Yvelines</option>
<option value="261" country_id="FR" region_name="Deux-Sèvres">Deux-Sèvres</option>
<option value="262" country_id="FR" region_name="Somme">Somme</option>
<option value="263" country_id="FR" region_name="Tarn">Tarn</option>
<option value="264" country_id="FR" region_name="Tarn-et-Garonne">Tarn-et-Garonne</option>
<option value="265" country_id="FR" region_name="Var">Var</option>
<option value="266" country_id="FR" region_name="Vaucluse">Vaucluse</option>
<option value="267" country_id="FR" region_name="Vendée">Vendée</option>
<option value="268" country_id="FR" region_name="Vienne">Vienne</option>
<option value="269" country_id="FR" region_name="Haute-Vienne">Haute-Vienne</option>
<option value="270" country_id="FR" region_name="Vosges">Vosges</option>
<option value="271" country_id="FR" region_name="Yonne">Yonne</option>
<option value="272" country_id="FR" region_name="Territoire-de-Belfort">Territoire-de-Belfort</option>
<option value="273" country_id="FR" region_name="Essonne">Essonne</option>
<option value="274" country_id="FR" region_name="Hauts-de-Seine">Hauts-de-Seine</option>
<option value="275" country_id="FR" region_name="Seine-Saint-Denis">Seine-Saint-Denis</option>
<option value="276" country_id="FR" region_name="Val-de-Marne">Val-de-Marne</option>
<option value="277" country_id="FR" region_name="Val-d\'Oise">Val-d\'Oise</option>
<option value="278" country_id="RO" region_name="Alba">Alba</option>
<option value="279" country_id="RO" region_name="Arad">Arad</option>
<option value="280" country_id="RO" region_name="Argeş">Argeş</option>
<option value="281" country_id="RO" region_name="Bacău">Bacău</option>
<option value="282" country_id="RO" region_name="Bihor">Bihor</option>
<option value="283" country_id="RO" region_name="Bistriţa-Năsăud">Bistriţa-Năsăud</option>
<option value="284" country_id="RO" region_name="Botoşani">Botoşani</option>
<option value="285" country_id="RO" region_name="Braşov">Braşov</option>
<option value="286" country_id="RO" region_name="Brăila">Brăila</option>
<option value="287" country_id="RO" region_name="Bucureşti">Bucureşti</option>
<option value="288" country_id="RO" region_name="Buzău">Buzău</option>
<option value="289" country_id="RO" region_name="Caraş-Severin">Caraş-Severin</option>
<option value="290" country_id="RO" region_name="Călăraşi">Călăraşi</option>
<option value="291" country_id="RO" region_name="Cluj">Cluj</option>
<option value="292" country_id="RO" region_name="Constanţa">Constanţa</option>
<option value="293" country_id="RO" region_name="Covasna">Covasna</option>
<option value="294" country_id="RO" region_name="Dâmboviţa">Dâmboviţa</option>
<option value="295" country_id="RO" region_name="Dolj">Dolj</option>
<option value="296" country_id="RO" region_name="Galaţi">Galaţi</option>
<option value="297" country_id="RO" region_name="Giurgiu">Giurgiu</option>
<option value="298" country_id="RO" region_name="Gorj">Gorj</option>
<option value="299" country_id="RO" region_name="Harghita">Harghita</option>
<option value="300" country_id="RO" region_name="Hunedoara">Hunedoara</option>
<option value="301" country_id="RO" region_name="Ialomiţa">Ialomiţa</option>
<option value="302" country_id="RO" region_name="Iaşi">Iaşi</option>
<option value="303" country_id="RO" region_name="Ilfov">Ilfov</option>
<option value="304" country_id="RO" region_name="Maramureş">Maramureş</option>
<option value="305" country_id="RO" region_name="Mehedinţi">Mehedinţi</option>
<option value="306" country_id="RO" region_name="Mureş">Mureş</option>
<option value="307" country_id="RO" region_name="Neamţ">Neamţ</option>
<option value="308" country_id="RO" region_name="Olt">Olt</option>
<option value="309" country_id="RO" region_name="Prahova">Prahova</option>
<option value="310" country_id="RO" region_name="Satu-Mare">Satu-Mare</option>
<option value="311" country_id="RO" region_name="Sălaj">Sălaj</option>
<option value="312" country_id="RO" region_name="Sibiu">Sibiu</option>
<option value="313" country_id="RO" region_name="Suceava">Suceava</option>
<option value="314" country_id="RO" region_name="Teleorman">Teleorman</option>
<option value="315" country_id="RO" region_name="Timiş">Timiş</option>
<option value="316" country_id="RO" region_name="Tulcea">Tulcea</option>
<option value="317" country_id="RO" region_name="Vaslui">Vaslui</option>
<option value="318" country_id="RO" region_name="Vâlcea">Vâlcea</option>
<option value="319" country_id="RO" region_name="Vrancea">Vrancea</option>
<option value="320" country_id="FI" region_name="Lappi">Lappi</option>
<option value="321" country_id="FI" region_name="Pohjois-Pohjanmaa">Pohjois-Pohjanmaa</option>
<option value="322" country_id="FI" region_name="Kainuu">Kainuu</option>
<option value="323" country_id="FI" region_name="Pohjois-Karjala">Pohjois-Karjala</option>
<option value="324" country_id="FI" region_name="Pohjois-Savo">Pohjois-Savo</option>
<option value="325" country_id="FI" region_name="Etelä-Savo">Etelä-Savo</option>
<option value="326" country_id="FI" region_name="Etelä-Pohjanmaa">Etelä-Pohjanmaa</option>
<option value="327" country_id="FI" region_name="Pohjanmaa">Pohjanmaa</option>
<option value="328" country_id="FI" region_name="Pirkanmaa">Pirkanmaa</option>
<option value="329" country_id="FI" region_name="Satakunta">Satakunta</option>
<option value="330" country_id="FI" region_name="Keski-Pohjanmaa">Keski-Pohjanmaa</option>
<option value="331" country_id="FI" region_name="Keski-Suomi">Keski-Suomi</option>
<option value="332" country_id="FI" region_name="Varsinais-Suomi">Varsinais-Suomi</option>
<option value="333" country_id="FI" region_name="Etelä-Karjala">Etelä-Karjala</option>
<option value="334" country_id="FI" region_name="Päijät-Häme">Päijät-Häme</option>
<option value="335" country_id="FI" region_name="Kanta-Häme">Kanta-Häme</option>
<option value="336" country_id="FI" region_name="Uusimaa">Uusimaa</option>
<option value="337" country_id="FI" region_name="Itä-Uusimaa">Itä-Uusimaa</option>
<option value="338" country_id="FI" region_name="Kymenlaakso">Kymenlaakso</option>
<option value="339" country_id="FI" region_name="Ahvenanmaa">Ahvenanmaa</option>
<option value="340" country_id="EE" region_name="Harjumaa">Harjumaa</option>
<option value="341" country_id="EE" region_name="Hiiumaa">Hiiumaa</option>
<option value="342" country_id="EE" region_name="Ida-Virumaa">Ida-Virumaa</option>
<option value="343" country_id="EE" region_name="Jõgevamaa">Jõgevamaa</option>
<option value="344" country_id="EE" region_name="Järvamaa">Järvamaa</option>
<option value="345" country_id="EE" region_name="Läänemaa">Läänemaa</option>
<option value="346" country_id="EE" region_name="Lääne-Virumaa">Lääne-Virumaa</option>
<option value="347" country_id="EE" region_name="Põlvamaa">Põlvamaa</option>
<option value="348" country_id="EE" region_name="Pärnumaa">Pärnumaa</option>
<option value="349" country_id="EE" region_name="Raplamaa">Raplamaa</option>
<option value="350" country_id="EE" region_name="Saaremaa">Saaremaa</option>
<option value="351" country_id="EE" region_name="Tartumaa">Tartumaa</option>
<option value="352" country_id="EE" region_name="Valgamaa">Valgamaa</option>
<option value="353" country_id="EE" region_name="Viljandimaa">Viljandimaa</option>
<option value="354" country_id="EE" region_name="Võrumaa">Võrumaa</option>
<option value="355" country_id="LV" region_name="Daugavpils">Daugavpils</option>
<option value="356" country_id="LV" region_name="Jelgava">Jelgava</option>
<option value="357" country_id="LV" region_name="Jēkabpils">Jēkabpils</option>
<option value="358" country_id="LV" region_name="Jūrmala">Jūrmala</option>
<option value="359" country_id="LV" region_name="Liepāja">Liepāja</option>
<option value="360" country_id="LV" region_name="Liepājas novads">Liepājas novads</option>
<option value="361" country_id="LV" region_name="Rēzekne">Rēzekne</option>
<option value="362" country_id="LV" region_name="Rīga">Rīga</option>
<option value="363" country_id="LV" region_name="Rīgas novads">Rīgas novads</option>
<option value="364" country_id="LV" region_name="Valmiera">Valmiera</option>
<option value="365" country_id="LV" region_name="Ventspils">Ventspils</option>
<option value="366" country_id="LV" region_name="Aglonas novads">Aglonas novads</option>
<option value="367" country_id="LV" region_name="Aizkraukles novads">Aizkraukles novads</option>
<option value="368" country_id="LV" region_name="Aizputes novads">Aizputes novads</option>
<option value="369" country_id="LV" region_name="Aknīstes novads">Aknīstes novads</option>
<option value="370" country_id="LV" region_name="Alojas novads">Alojas novads</option>
<option value="371" country_id="LV" region_name="Alsungas novads">Alsungas novads</option>
<option value="372" country_id="LV" region_name="Alūksnes novads">Alūksnes novads</option>
<option value="373" country_id="LV" region_name="Amatas novads">Amatas novads</option>
<option value="374" country_id="LV" region_name="Apes novads">Apes novads</option>
<option value="375" country_id="LV" region_name="Auces novads">Auces novads</option>
<option value="376" country_id="LV" region_name="Babītes novads">Babītes novads</option>
<option value="377" country_id="LV" region_name="Baldones novads">Baldones novads</option>
<option value="378" country_id="LV" region_name="Baltinavas novads">Baltinavas novads</option>
<option value="379" country_id="LV" region_name="Balvu novads">Balvu novads</option>
<option value="380" country_id="LV" region_name="Bauskas novads">Bauskas novads</option>
<option value="381" country_id="LV" region_name="Beverīnas novads">Beverīnas novads</option>
<option value="382" country_id="LV" region_name="Brocēnu novads">Brocēnu novads</option>
<option value="383" country_id="LV" region_name="Burtnieku novads">Burtnieku novads</option>
<option value="384" country_id="LV" region_name="Carnikavas novads">Carnikavas novads</option>
<option value="385" country_id="LV" region_name="Cesvaines novads">Cesvaines novads</option>
<option value="386" country_id="LV" region_name="Ciblas novads">Ciblas novads</option>
<option value="387" country_id="LV" region_name="Cēsu novads">Cēsu novads</option>
<option value="388" country_id="LV" region_name="Dagdas novads">Dagdas novads</option>
<option value="389" country_id="LV" region_name="Daugavpils novads">Daugavpils novads</option>
<option value="390" country_id="LV" region_name="Dobeles novads">Dobeles novads</option>
<option value="391" country_id="LV" region_name="Dundagas novads">Dundagas novads</option>
<option value="392" country_id="LV" region_name="Durbes novads">Durbes novads</option>
<option value="393" country_id="LV" region_name="Engures novads">Engures novads</option>
<option value="394" country_id="LV" region_name="Garkalnes novads">Garkalnes novads</option>
<option value="395" country_id="LV" region_name="Grobiņas novads">Grobiņas novads</option>
<option value="396" country_id="LV" region_name="Gulbenes novads">Gulbenes novads</option>
<option value="397" country_id="LV" region_name="Iecavas novads">Iecavas novads</option>
<option value="398" country_id="LV" region_name="Ikšķiles novads">Ikšķiles novads</option>
<option value="399" country_id="LV" region_name="Ilūkstes novads">Ilūkstes novads</option>
<option value="400" country_id="LV" region_name="Inčukalna novads">Inčukalna novads</option>
<option value="401" country_id="LV" region_name="Jaunjelgavas novads">Jaunjelgavas novads</option>
<option value="402" country_id="LV" region_name="Jaunpiebalgas novads">Jaunpiebalgas novads</option>
<option value="403" country_id="LV" region_name="Jaunpils novads">Jaunpils novads</option>
<option value="404" country_id="LV" region_name="Jelgavas novads">Jelgavas novads</option>
<option value="405" country_id="LV" region_name="Jēkabpils novads">Jēkabpils novads</option>
<option value="406" country_id="LV" region_name="Kandavas novads">Kandavas novads</option>
<option value="407" country_id="LV" region_name="Kokneses novads">Kokneses novads</option>
<option value="408" country_id="LV" region_name="Krimuldas novads">Krimuldas novads</option>
<option value="409" country_id="LV" region_name="Krustpils novads">Krustpils novads</option>
<option value="410" country_id="LV" region_name="Krāslavas novads">Krāslavas novads</option>
<option value="411" country_id="LV" region_name="Kuldīgas novads">Kuldīgas novads</option>
<option value="412" country_id="LV" region_name="Kārsavas novads">Kārsavas novads</option>
<option value="413" country_id="LV" region_name="Lielvārdes novads">Lielvārdes novads</option>
<option value="414" country_id="LV" region_name="Limbažu novads">Limbažu novads</option>
<option value="415" country_id="LV" region_name="Lubānas novads">Lubānas novads</option>
<option value="416" country_id="LV" region_name="Ludzas novads">Ludzas novads</option>
<option value="417" country_id="LV" region_name="Līgatnes novads">Līgatnes novads</option>
<option value="418" country_id="LV" region_name="Līvānu novads">Līvānu novads</option>
<option value="419" country_id="LV" region_name="Madonas novads">Madonas novads</option>
<option value="420" country_id="LV" region_name="Mazsalacas novads">Mazsalacas novads</option>
<option value="421" country_id="LV" region_name="Mālpils novads">Mālpils novads</option>
<option value="422" country_id="LV" region_name="Mārupes novads">Mārupes novads</option>
<option value="423" country_id="LV" region_name="Naukšēnu novads">Naukšēnu novads</option>
<option value="424" country_id="LV" region_name="Neretas novads">Neretas novads</option>
<option value="425" country_id="LV" region_name="Nīcas novads">Nīcas novads</option>
<option value="426" country_id="LV" region_name="Ogres novads">Ogres novads</option>
<option value="427" country_id="LV" region_name="Olaines novads">Olaines novads</option>
<option value="428" country_id="LV" region_name="Ozolnieku novads">Ozolnieku novads</option>
<option value="429" country_id="LV" region_name="Preiļu novads">Preiļu novads</option>
<option value="430" country_id="LV" region_name="Priekules novads">Priekules novads</option>
<option value="431" country_id="LV" region_name="Priekuļu novads">Priekuļu novads</option>
<option value="432" country_id="LV" region_name="Pārgaujas novads">Pārgaujas novads</option>
<option value="433" country_id="LV" region_name="Pāvilostas novads">Pāvilostas novads</option>
<option value="434" country_id="LV" region_name="Pļaviņu novads">Pļaviņu novads</option>
<option value="435" country_id="LV" region_name="Raunas novads">Raunas novads</option>
<option value="436" country_id="LV" region_name="Riebiņu novads">Riebiņu novads</option>
<option value="437" country_id="LV" region_name="Rojas novads">Rojas novads</option>
<option value="438" country_id="LV" region_name="Ropažu novads">Ropažu novads</option>
<option value="439" country_id="LV" region_name="Rucavas novads">Rucavas novads</option>
<option value="440" country_id="LV" region_name="Rugāju novads">Rugāju novads</option>
<option value="441" country_id="LV" region_name="Rundāles novads">Rundāles novads</option>
<option value="442" country_id="LV" region_name="Rēzeknes novads">Rēzeknes novads</option>
<option value="443" country_id="LV" region_name="Rūjienas novads">Rūjienas novads</option>
<option value="444" country_id="LV" region_name="Salacgrīvas novads">Salacgrīvas novads</option>
<option value="445" country_id="LV" region_name="Salas novads">Salas novads</option>
<option value="446" country_id="LV" region_name="Salaspils novads">Salaspils novads</option>
<option value="447" country_id="LV" region_name="Saldus novads">Saldus novads</option>
<option value="448" country_id="LV" region_name="Saulkrastu novads">Saulkrastu novads</option>
<option value="449" country_id="LV" region_name="Siguldas novads">Siguldas novads</option>
<option value="450" country_id="LV" region_name="Skrundas novads">Skrundas novads</option>
<option value="451" country_id="LV" region_name="Skrīveru novads">Skrīveru novads</option>
<option value="452" country_id="LV" region_name="Smiltenes novads">Smiltenes novads</option>
<option value="453" country_id="LV" region_name="Stopiņu novads">Stopiņu novads</option>
<option value="454" country_id="LV" region_name="Strenču novads">Strenču novads</option>
<option value="455" country_id="LV" region_name="Sējas novads">Sējas novads</option>
<option value="456" country_id="LV" region_name="Talsu novads">Talsu novads</option>
<option value="457" country_id="LV" region_name="Tukuma novads">Tukuma novads</option>
<option value="458" country_id="LV" region_name="Tērvetes novads">Tērvetes novads</option>
<option value="459" country_id="LV" region_name="Vaiņodes novads">Vaiņodes novads</option>
<option value="460" country_id="LV" region_name="Valkas novads">Valkas novads</option>
<option value="461" country_id="LV" region_name="Valmieras novads">Valmieras novads</option>
<option value="462" country_id="LV" region_name="Varakļānu novads">Varakļānu novads</option>
<option value="463" country_id="LV" region_name="Vecpiebalgas novads">Vecpiebalgas novads</option>
<option value="464" country_id="LV" region_name="Vecumnieku novads">Vecumnieku novads</option>
<option value="465" country_id="LV" region_name="Ventspils novads">Ventspils novads</option>
<option value="466" country_id="LV" region_name="Viesītes novads">Viesītes novads</option>
<option value="467" country_id="LV" region_name="Viļakas novads">Viļakas novads</option>
<option value="468" country_id="LV" region_name="Viļānu novads">Viļānu novads</option>
<option value="469" country_id="LV" region_name="Vārkavas novads">Vārkavas novads</option>
<option value="470" country_id="LV" region_name="Zilupes novads">Zilupes novads</option>
<option value="471" country_id="LV" region_name="Ādažu novads">Ādažu novads</option>
<option value="472" country_id="LV" region_name="Ērgļu novads">Ērgļu novads</option>
<option value="473" country_id="LV" region_name="Ķeguma novads">Ķeguma novads</option>
<option value="474" country_id="LV" region_name="Ķekavas novads">Ķekavas novads</option>
<option value="475" country_id="LT" region_name="Alytaus Apskritis">Alytaus Apskritis</option>
<option value="476" country_id="LT" region_name="Kauno Apskritis">Kauno Apskritis</option>
<option value="477" country_id="LT" region_name="Klaipėdos Apskritis">Klaipėdos Apskritis</option>
<option value="478" country_id="LT" region_name="Marijampolės Apskritis">Marijampolės Apskritis</option>
<option value="479" country_id="LT" region_name="Panevėžio Apskritis">Panevėžio Apskritis</option>
<option value="480" country_id="LT" region_name="Šiaulių Apskritis">Šiaulių Apskritis</option>
<option value="481" country_id="LT" region_name="Tauragės Apskritis">Tauragės Apskritis</option>
<option value="482" country_id="LT" region_name="Telšių Apskritis">Telšių Apskritis</option>
<option value="483" country_id="LT" region_name="Utenos Apskritis">Utenos Apskritis</option>
<option value="484" country_id="LT" region_name="Vilniaus Apskritis">Vilniaus Apskritis</option>

		';

		return $regions;
	}
	
	function years() {
		$date = date('Y');
		$return = array($date);
		
		for ($i = 1; $i < 7; $i++){
			$return[] = $date + $i;
		}
		return $return;
	}	
	?>