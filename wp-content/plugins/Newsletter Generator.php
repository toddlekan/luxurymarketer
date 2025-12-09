<?php
/*
Plugin Name: Newsletter Generator
Plugin URI: Netconcepts.com
Description: Creates an admin page that generates an HTML and Text version of a Newsletter. The content is pulled based on blog posts.
Author: Kerry W Mann Jr
Author URI: http://www.KerryMann.com
*/

// Hook for adding admin menus
add_action('admin_menu', 'newsletter_page');

// action function for above hook
function newsletter_page() {

// Add a new top-level menu
add_menu_page('Newsletter', 'Newsletter', "manage_options", __FILE__, 'mt_toplevel_page',null,null);


}


function mt_toplevel_page() {

/*
// Get HTML from source page
$homepage = file_get_contents('http://www.luxuryroundtable.com/?p=63&'.time(),
//$homepage = file_get_contents('http://www.luxuryroundtable.com/newsletter-preview&'.time(),
false,
    stream_context_create(
        array(
            'http' => array(
                'ignore_errors' => true
            )
        )
    )
);
//newsletter-preview/?'.time());

// set variable for TEXT version
$text = file_get_contents('http://www.luxuryroundtable.com/?p=63&'.time(),
//$text = file_get_contents('https://www.luxuryroundtable.com/text-version-of-the-newsletter&'.time(),
false,
    stream_context_create(
        array(
            'http' => array(
                'ignore_errors' => true
            )
        )
    )
);
//text-version-of-the-newsletter/?'.time());
$text=rtrim($text);

*/


 echo "<div align=\"center\"><h2>Newsletter HTML Version</h2> ";
 //echo "<textarea rows=\"10\" cols=\"80\">$homepage</textarea> </div>";
 echo "<iframe style='width:1200px;height:400px' src='https://www.luxuryroundtable.com/?p=63&src&".time()."'></iframe></div>";


 echo "<div align=\"center\">";
 echo "<table border=\"3\" width=\"600\" bgcolor=\"000\"> <tr><td>";


 echo "<p><b>How to publish the newsletter archive:</b></p>";

 echo "
<ol>
	<li>1) Copy the Newsletter HTML Above</li>
	<li>2) Create a New Page <strong><a href=\"https://www.luxuryroundtable.com/wp-admin/post-new.php?post_type=page\">here</a></strong>,  Input the Newsletter Title and Paste the Newsletter HTML</li>
	<li>3) In the right sidebar \"Attributes\" select \"Newsletter Archive\" for the Parent and  \"Newsletter Archive Placeholder\" for the Template</li>
	<li>4) Publish</li>
</ol> </div></td></tr></table> ";




 //echo "<div align=\"center\">";
 //echo "<h2>Newsletter TEXT Version</h2> ";
 //echo "<textarea rows=\"10\" cols=\"80\">";
 //echo strip_tags($text);
 //echo "</textarea> <br/>";
 //echo "<iframe style='width:1200px;height:400px' src='https://www.luxuryroundtable.com/?p=63&src&".time()."'></iframe>";

 echo "<h2>Newsletter Preview</h2>";
 echo "<p>Use this preview to check that the ads and articles are as expected.</p>";
 echo "<iframe style='width:1200px;height:400px' src='https://www.luxuryroundtable.com/newsletter-preview/?".time()."'></iframe</div>>";

 echo "</div>";

}

?>
