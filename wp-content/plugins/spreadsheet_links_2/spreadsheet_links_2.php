<?php
/*
Plugin Name: Spreadsheet Links 2

License: GPL
*/

/*
add_action( 'admin_menu', 'spreadsheet_menu' );

function my_plugin_menu() {
	add_options_page( 'Spreadsheet Links', 'Spreadsheet Links', 'manage_options', 'ld-spreadsheet-links', 'spreadsheet_links' );
}

add_menu_page( 'redirecting', 'View Site', 'read', 'my-top-level-handle', 'spreadsheet_links');

function spreadsheet_links() {
	if ( !current_user_can( 'manage_options' ) )  {
		wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
	}
	echo '<div class="wrap">';
	echo '<p><a href="https://store.americanmarketer.com/toddlekan/reports/perproduct.php?product_id=36" target="_blank">Luxury Dailys State of Luxury 2017: The Insider View report</a></p>';
	echo '</div>';
}
*/

/**
 * Register a custom menu page.
 */
/*function wpdocs_register_my_custom_menu_page() {
    add_menu_page(
        __( 'Spreadsheet Links', 'textdomain' ),
        'Spreadsheet Links',
        'manage_options',
        'spreadsheet_links_2/spreadsheet_links_2.php',
        'spreadsheet_links',
        plugins_url( 'spreadsheet_links_2/icon.png' ),
        6
    );
}
add_action( 'admin_menu', 'wpdocs_register_my_custom_menu_page' );

function admin_menu_items() {
    global $menu;
    //$menu[102]=$menu[20];//make menu 102 be the same as menu 20 (pages)
    //$menu[20]=array();//make original pages menu disappear


    //print_r($menu);

}
add_action('admin_menu', 'admin_menu_items');
*/


//add_menu_page( $page_title, $menu_title, $capability, $menu_slug, $function, $icon_url, $position );

add_action( 'admin_menu', 'my_custom_menu' );

function my_custom_menu()
{
	add_menu_page( 'Spreadsheet Links', 'Spreadsheet Links', 'manage_options', 'spreadsheet_links_2/spreadsheet_links_2.php', 'spreadsheet_links', '');
}

function spreadsheet_links() {


	if ( !current_user_can( 'manage_options' ) )  {
		wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
	}

	echo '<div class="wrap">';

	echo '<h2>American Marketer Conference Spreadsheets</h2>';

	echo '<p><a href="https://store.americanmarketer.com/toddlekan/reports/perproduct.php?product_id=58" target="_blank">China Outlook 2020 Discounted (AM)</a></p>';

	echo '<p><a href="https://store.americanmarketer.com/toddlekan/reports/perproduct.php?product_id=59" target="_blank">China Outlook 2020 (AM)</a></p>';

	echo '<p><a href="https://store.americanmarketer.com/toddlekan/reports/perproduct.php?product_id=53" target="_blank">AMCX: Customer Experience Discounted</a></p>';

	echo '<p><a href="https://store.americanmarketer.com/toddlekan/reports/perproduct.php?product_id=52" target="_blank">AMCX: Customer Experience</a></p>';

	echo '<h2>Luxury Daily Conference Spreadsheets</h2>';


	echo '<p><a href="https://store.americanmarketer.com/toddlekan/reports/perproduct.php?product_id=64" target="_blank">Women in Luxury 2020 No Charge</a></p>';

	echo '<p><a href="https://store.americanmarketer.com/toddlekan/reports/perproduct.php?product_id=63" target="_blank">Women in Luxury 2020 Discounted</a></p>';

	echo '<p><a href="https://store.americanmarketer.com/toddlekan/reports/perproduct.php?product_id=62" target="_blank">Women in Luxury 2020</a></p>';


	echo '<p><a href="https://store.americanmarketer.com/toddlekan/reports/perproduct.php?product_id=61" target="_blank">Luxury FirstLook 2020 Discounted</a></p>';

	echo '<p><a href="https://store.americanmarketer.com/toddlekan/reports/perproduct.php?product_id=60" target="_blank">Luxury FirstLook 2020</a></p>';

	echo '<p><a href="https://store.americanmarketer.com/toddlekan/reports/perproduct.php?product_id=57" target="_blank">China Outlook 2020 Discounted(LD)</a></p>';

	echo '<p><a href="https://store.americanmarketer.com/toddlekan/reports/perproduct.php?product_id=56" target="_blank">China Outlook 2020 (LD)</a></p>';


	echo '<p><a href="https://store.americanmarketer.com/toddlekan/reports/perproduct.php?product_id=55" target="_blank">LuxeCX: Customer Experience in Luxury Discounted</a></p>';

	echo '<p><a href="https://store.americanmarketer.com/toddlekan/reports/perproduct.php?product_id=54" target="_blank">LuxeCX: Customer Experience in Luxury</a></p>';


	echo '<p><a href="https://store.americanmarketer.com/toddlekan/reports/perproduct.php?product_id=51" target="_blank">Women in Luxury 2019 Discounted</a></p>';

	echo '<p><a href="https://store.americanmarketer.com/toddlekan/reports/perproduct.php?product_id=50" target="_blank">Women in Luxury 2019 </a></p>';



	echo '<p><a href="https://store.americanmarketer.com/toddlekan/reports/perproduct.php?product_id=48" target="_blank">Luxury FirstLook 2019 Discounted</a></p>';

	echo '<p><a href="https://store.americanmarketer.com/toddlekan/reports/perproduct.php?product_id=47" target="_blank">Luxury FirstLook 2019</a></p>';


	echo '<p><a href="https://store.americanmarketer.com/toddlekan/reports/perproduct.php?product_id=46" target="_blank">Luxury Marketing Forum 2018 Discounted</a></p>';

	echo '<p><a href="https://store.americanmarketer.com/toddlekan/reports/perproduct.php?product_id=45" target="_blank">Luxury Marketing Forum 2018</a></p>';

	echo '<p><a href="https://store.americanmarketer.com/toddlekan/reports/perproduct.php?product_id=44" target="_blank">Women in Luxury 2018 conference Discounted</a></p>';

	echo '<p><a href="https://store.americanmarketer.com/toddlekan/reports/perproduct.php?product_id=43" target="_blank">Women in Luxury 2018 conference</a></p>';

	echo '<p><a href="https://store.americanmarketer.com/toddlekan/reports/perproduct.php?product_id=42" target="_blank">Luxury Dailys State of Luxury 2018: The Insider View report</a></p>';

	echo '<p><a href="https://store.americanmarketer.com/toddlekan/reports/perproduct.php?product_id=41" target="_blank">Luxury FirstLook 2018 Discount</a></p>';

	echo '<p><a href="https://store.americanmarketer.com/toddlekan/reports/perproduct.php?product_id=40" target="_blank">Luxury FirstLook 2018</a></p>';

	echo '<p><a href="https://store.americanmarketer.com/toddlekan/reports/perproduct.php?product_id=39" target="_blank">Women in Luxury Discount</a></p>';

	echo '<p><a href="https://store.americanmarketer.com/toddlekan/reports/perproduct.php?product_id=38" target="_blank">Women in Luxury</a></p>';

	echo '<p><a href="https://store.americanmarketer.com/toddlekan/reports/perproduct.php?product_id=37" target="_blank">Luxury Roundtable 2017: Engaging Gens X, Y & Z</a></p>';

	echo '<p><a href="https://store.americanmarketer.com/toddlekan/reports/perproduct.php?product_id=36" target="_blank">Luxury Dailys State of Luxury 2017: The Insider View report</a></p>';

	echo '</div>';
	echo '<h2>Mobile Conference Spreadsheets</h2>';

	$mobile_arr = array(
		'LD Stats Archive' => 'https://www.luxurydaily.com/LD_Stats_Archive.zip',
	'Registration Summary' => 'https://store.luxurydaily.com/toddlekan/reports/summary.php',
'Registrations By Date' => '/bydate.php',
			'MCommerce Summit New York 2010' => '/conference_spreadsheet.php',
			'MCommerce Summit New York 2011' => '/conference_spreadsheet.php?conference_id=1',
			'Mobile Marketing Summit 2011' => '/conference_spreadsheet.php?conference_id=2',
'Mobile FirstLook 2012' => '/conference_spreadsheet.php?conference_id=10',
			'MCommerce Summit New York 2012' => '/conference_spreadsheet.php?conference_id=11',
'Mcommerce Summit: State of Mobile Commerce 2013' => 'https://store.luxurydaily.com/toddlekan/reports/perproduct.php?product_id=6',
'Mobile Marketing Summit 2012' => '/conference_spreadsheet.php?conference_id=12',
'Mobile FirstLook 2013' => '/conference_spreadsheet.php?conference_id=14',
'Luxury FirstLook 2013' => '/conference_spreadsheet.php?conference_id=15',
'Luxury Roundtable: State of Luxury 2013' => 'https://store.luxurydaily.com/toddlekan/reports/perproduct.php?product_id=5',
'Luxury Retail Summit: Holiday Focus 2013 ' => 'https://store.luxurydaily.com/toddlekan/reports/perproduct.php?product_id=7',
'Mobile Marketing Summit: Holiday Focus 2013' => 'https://store.luxurydaily.com/toddlekan/reports/perproduct.php?product_id=8',
'Mobile Women to Watch 2014 Summit' => 'https://store.luxurydaily.com/toddlekan/reports/perproduct.php?product_id=9',
'Mobile Women to Watch 2014 Summit (discounted price)' => 'https://store.luxurydaily.com/toddlekan/reports/perproduct.php?product_id=11',
'Mobile FirstLook: Strategy 2014' => 'https://store.luxurydaily.com/toddlekan/reports/perproduct.php?product_id=10',
'Luxury FirstLook: 2014' => 'https://store.luxurydaily.com/toddlekan/reports/perproduct.php?product_id=12',
'Mcommerce Summit: State of Mobile Commerce 2014' => 'https://store.luxurydaily.com/toddlekan/reports/perproduct.php?product_id=13',
'Luxury Roundtable: State of Luxury 2014' => 'https://store.luxurydaily.com/toddlekan/reports/perproduct.php?product_id=14',
	'Mobile Research Summit 2014' => 'https://store.luxurydaily.com/toddlekan/reports/perproduct.php?product_id=16',
	'Mobile Marketing Summit 2014' => 'https://store.luxurydaily.com/toddlekan/reports/perproduct.php?product_id=17',
	'Luxury Retail Summit 2014' => 'https://store.luxurydaily.com/toddlekan/reports/perproduct.php?product_id=18',
	'Mobile Women to Watch Summit' => 'https://store.luxurydaily.com/toddlekan/reports/perproduct.php?product_id=19',
	'Mobile FirstLook 2015' => 'https://store.luxurydaily.com/toddlekan/reports/perproduct.php?product_id=21',
	'Luxury FirstLook: Strategy 2015' => 'https://store.luxurydaily.com/toddlekan/reports/perproduct.php?product_id=22',
	'Mcommerce Summit: State of Mobile Commerce 2015 ' => 'https://store.luxurydaily.com/toddlekan/reports/perproduct.php?product_id=23',
	'Luxury Insights Summit' => 'https://store.luxurydaily.com/toddlekan/reports/perproduct.php?product_id=24',
	'Mobile Research Summit: Data & Insights Insights 2015' => 'https://store.luxurydaily.com/toddlekan/reports/perproduct.php?product_id=25',
	'Mobile Marketing Summit: Wearables and Holiday Focus 2015' => 'https://store.luxurydaily.com/toddlekan/reports/perproduct.php?product_id=26',
	'Luxury Retail Summit 2015' => 'https://store.luxurydaily.com/toddlekan/reports/perproduct.php?product_id=27',
	'Mobile FirstLook 2016' => 'https://store.luxurydaily.com/toddlekan/reports/perproduct.php?product_id=28',
	'Luxury FirstLook: Strategy 2016' => 'https://store.luxurydaily.com/toddlekan/reports/perproduct.php?product_id=29',
	'Mcommerce Summit: State of Mobile Commerce 2016' => 'https://store.luxurydaily.com/toddlekan/reports/perproduct.php?product_id=31',
	'Mobile Insights Summit 2016' => 'https://store.luxurydaily.com/toddlekan/reports/perproduct.php?product_id=32',
	'Mobile FirstLook 2017' => 'https://store.luxurydaily.com/toddlekan/reports/perproduct.php?product_id=35',
	'Luxury Roundtable 2017' => 'https://store.luxurydaily.com/toddlekan/reports/perproduct.php?product_id=34'     );
	
	foreach($mobile_arr as $key => $val){

		echo '<p><a href="'.$val.'" target="_blank">'.$key.'</a></p>';
	}

	echo '</div>';

}

?>
