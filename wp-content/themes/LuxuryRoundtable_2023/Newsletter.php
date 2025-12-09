<?php
/*
 Template Name: LRT Newsletter HTML Template
 */

if (array_key_exists('src', $_GET)) {
  header('Content-Type: text/plain'); // plain text file
}

$file_root = ld16_get_file_root();
$url_root = ld16_cdn(get_template_directory_uri());

if (array_key_exists('post_status', $_GET)) {

  $post_status = $_GET['post_status'];
} else {
  $post_status = 'future';
}

$meta_query = array();

if (array_key_exists('date', $_GET)) {

  $date = $_GET['date'];

  $meta_query = array(
    array(
      'key'    => 'post_modified',
      'compare'  => '>=',
      'value'    => $date . " 00:00:00",
    ),
    array(
      'key'    => 'post_modified',
      'compare'  => '<=',
      'value'    => $date . " 23:59:59",
    )
  );
}

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">

<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Your Message Subject or Title</title>
  <style type="text/css">

    @media only screen and (max-width:500px){
      img.main_img_newsletter{
        width: 100%;
      }
    }

    /***********
		Originally based on The MailChimp Reset from Fabio Carneiro, MailChimp User Experience Design
		More info and templates on Github: https://github.com/mailchimp/Email-Blueprints
		http://www.mailchimp.com &amp; http://www.fabio-carneiro.com
		INLINE: Yes.
		***********/
    /* Client-specific Styles */

    #outlook a {
      padding: 0;
    }

    /* Force Outlook to provide a "view in browser" menu link. */

    body {
      width: 100% !important;
      -webkit-text-size-adjust: 100%;
      -ms-text-size-adjust: 100%;
      margin: 0;
      padding: 0;
    }

    /* Prevent Webkit and Windows Mobile platforms from changing default font sizes, while not breaking desktop design. */

    .ExternalClass {
      width: 100%;
    }

    /* Force Hotmail to display emails at full width */

    .ExternalClass,
    .ExternalClass p,
    .ExternalClass span,
    .ExternalClass font,
    .ExternalClass td,
    .ExternalClass div {
      line-height: 100%;
    }

    /* Force Hotmail to display normal line spacing.  More on that: http://www.emailonacid.com/forum/viewthread/43/ */

    #backgroundTable {
      margin: 0;
      padding: 0;
      width: 100% !important;
      line-height: 100% !important;
    }

    /* End reset */
    /* Some sensible defaults for images
		1. "-ms-interpolation-mode: bicubic" works to help ie properly resize images in IE. (if you are resizing them using the width and height attributes)
		2. "border:none" removes border when linking images.
		3. Updated the common Gmail/Hotmail image display fix: Gmail and Hotmail unwantedly adds in an extra space below images when using non IE browsers. You may not always want all of your images to be block elements. Apply the "image_fix" class to any image you need to fix.
		Bring inline: Yes.
		*/

    img {
      outline: none;
      text-decoration: none;
      -ms-interpolation-mode: bicubic;
    }

    a img {
      border: none;
    }

    .image_fix {
      display: block;
    }

    /** Yahoo paragraph fix: removes the proper spacing or the paragraph (p) tag. To correct we set the top/bottom margin to 1em in the head of the document. Simple fix with little effect on other styling. NOTE: It is also common to use two breaks instead of the paragraph tag but I think this way is cleaner and more semantic. NOTE: This example recommends 1em. More info on setting web defaults: http://www.w3.org/TR/CSS21/sample.html or http://meiert.com/en/blog/20070922/user-agent-style-sheets/
		Bring inline: Yes.
		**/

    p {
      margin: 1em 0;
    }

    /** Hotmail header color reset: Hotmail replaces your header color styles with a green color on H2, H3, H4, H5, and H6 tags. In this example, the color is reset to black for a non-linked header, blue for a linked header, red for an active header (limited support), and purple for a visited header (limited support).  Replace with your choice of color. The !important is really what is overriding Hotmail's styling. Hotmail also sets the H1 and H2 tags to the same size.
		Bring inline: Yes.
		**/

    h1,
    h2,
    h3,
    h4,
    h5,
    h6 {
      color: black !important;
    }

    h1 a,
    h2 a,
    h3 a,
    h4 a,
    h5 a,
    h6 a {
      color: blue !important;
    }

    h1 a:active,
    h2 a:active,
    h3 a:active,
    h4 a:active,
    h5 a:active,
    h6 a:active {
      color: red !important;
      /* Preferably not the same color as the normal header link color.  There is limited support for psuedo classes in email clients, this was added just for good measure. */
    }

    h1 a:visited,
    h2 a:visited,
    h3 a:visited,
    h4 a:visited,
    h5 a:visited,
    h6 a:visited {
      color: purple !important;
      /* Preferably not the same color as the normal header link color. There is limited support for psuedo classes in email clients, this was added just for good measure. */
    }

    /** Outlook 07, 10 Padding issue: These "newer" versions of Outlook add some padding around table cells potentially throwing off your perfectly pixeled table.  The issue can cause added space and also throw off borders completely.  Use this fix in your header or inline to safely fix your table woes.
		More info: http://www.ianhoar.com/2008/04/29/outlook-2007-borders-and-1px-padding-on-table-cells/
		http://www.campaignmonitor.com/blog/post/3392/1px-borders-padding-on-table-cells-in-outlook-07/
		H/T @edmelly
		Bring inline: No.
		**/

    table td {
      border-collapse: collapse;
    }

    /** Remove spacing around Outlook 07, 10 tables
		More info : http://www.campaignmonitor.com/blog/post/3694/removing-spacing-from-around-tables-in-outlook-2007-and-2010/
		Bring inline: Yes
		**/

    table {
      border-collapse: collapse;
      mso-table-lspace: 0pt;
      mso-table-rspace: 0pt;
    }

    /* Styling your links has become much simpler with the new Yahoo.  In fact, it falls in line with the main credo of styling in email, bring your styles inline.  Your link colors will be uniform across clients when brought inline.
		Bring inline: Yes. */

    a {
      color: orange;
    }

    /* Or to go the gold star route...
		a:link { color: orange; }
		a:visited { color: blue; }
		a:hover { color: green; }
		*/
    /***************************************************
		****************************************************
		MOBILE TARGETING
		Use @media queries with care.  You should not bring these styles inline -- so it's recommended to apply them AFTER you bring the other stlying inline.
		Note: test carefully with Yahoo.
		Note 2: Don't bring anything below this line inline.
		****************************************************
		***************************************************/
    /* NOTE: To properly use @media queries and play nice with yahoo mail, use attribute selectors in place of class, id declarations.
		table[class=classname]
		Read more: http://www.campaignmonitor.com/blog/post/3457/media-query-issues-in-yahoo-mail-mobile-email/
		*/

    @media only screen and (max-device-width: 480px) {

      /* A nice and clean way to target phone numbers you want clickable and avoid a mobile phone from linking other numbers that look like, but are not phone numbers.  Use these two blocks of code to "unstyle" any numbers that may be linked.  The second block gives you a class to apply with a span tag to the numbers you would like linked and styled.
			Inspired by Campaign Monitor's article on using phone numbers in email: http://www.campaignmonitor.com/blog/post/3571/using-phone-numbers-in-html-email/.
			Step 1 (Step 2: line 224)
			*/
      a[href^="tel"],
      a[href^="sms"] {
        text-decoration: none;
        color: black;
        /* or whatever your want */
        pointer-events: none;
        cursor: default;
      }

      .mobile_link a[href^="tel"],
      .mobile_link a[href^="sms"] {
        text-decoration: default;
        color: orange !important;
        /* or whatever your want */
        pointer-events: auto;
        cursor: default;
      }
    }

    /* More Specific Targeting */

    @media only screen and (min-device-width: 768px) and (max-device-width: 1024px) {

      /* You guessed it, ipad (tablets, smaller screens, etc) */
      /* Step 1a: Repeating for the iPad */
      a[href^="tel"],
      a[href^="sms"] {
        text-decoration: none;
        color: blue;
        /* or whatever your want */
        pointer-events: none;
        cursor: default;
      }

      .mobile_link a[href^="tel"],
      .mobile_link a[href^="sms"] {
        text-decoration: default;
        color: orange !important;
        pointer-events: auto;
        cursor: default;
      }
    }

    @media only screen and (-webkit-min-device-pixel-ratio: 2) {
      /* Put your iPhone 4g styles in here */
    }

    /* Following Android targeting from:
		http://developer.android.com/guide/webapps/targeting.html
		https://pugetworks.com/2011/04/css-media-queries-for-targeting-different-mobile-devices/  */

    @media only screen and (-webkit-device-pixel-ratio:.75) {
      /* Put CSS for low density (ldpi) Android layouts in here */
    }

    @media only screen and (-webkit-device-pixel-ratio:1) {
      /* Put CSS for medium density (mdpi) Android layouts in here */
    }

    @media only screen and (-webkit-device-pixel-ratio:1.5) {
      /* Put CSS for high density (hdpi) Android layouts in here */
    }

    /* end Android targeting */
  </style>

  <!-- Targeting Windows Mobile -->
  <!--[if IEMobile 7]>
	<style type="text/css">
	</style>
	<![endif]-->

  <!-- ***********************************************
	****************************************************
	END MOBILE TARGETING
	****************************************************
	************************************************ -->

  <!--[if gte mso 9]>
	<style>
		/* Target Outlook 2007 and 2010 */
	</style>
	<![endif]-->


      
  <style>


    @font-face{
        font-family:"Neue-Frutiger-W04-Light";
        src:url("<?= $url_root ?>/css/FrutigerWebfonts/1123415/cc1ddd05-3327-4c9f-ac92-9f39e7cd9705.woff2") format("woff2"),url("<?= $url_root ?>/css/FrutigerWebfonts/1123415/b7c08596-80c2-4b7e-82ce-291775ee0059.woff") format("woff");
    }

  </style>


  <!--[RAW]     -->
  <style type="text/css">
    body,
    p,
    td,
    b,
    strong,
    ul,
    ol {
      font-family: 'Neue-Frutiger-W04-Light', arial, serif;
      font-size: 13px;
      color: #333333
    }

    a:link,
    a:visited,
    a:active {
      text-decoration: none;
      color: #1169AA;
    }

    a:hover {
      text-decoration: underline;
    }

    ul#sidebar {
      padding: 0px;
    }

    @media screen and (min-width: 601px) {
      container {
        width: 600px !important;
      }
    }
  </style>

</head>

<body>

  <center>
    <!--[if (gte mso 9)|(IE)]><table width="600" align="center" cellpadding="0" cellspacing="0" border="0"><tr><td><![endif]-->



    <!-- Wrapper/Container Table: Use a wrapper table to control the width and the background color consistently of your email. Use this approach instead of setting attributes on the body tag. -->
    <table cellpadding="0" cellspacing="0" border="0" id="backgroundTable" width="100%">
      <tr>
        <td align="center">



          <table id="container" width="" border="0" align="center" cellpadding="1" cellspacing="0" bgcolor="#FFFFFF" style="max-width: 600px; border-collapse: collapse; border: 0px solid #000000;">
            <tr>
              <td align="center">
                <table width="100%" border="0" cellspacing="0" cellpadding="7">
                  <tr>
                    <td>
                      <table width="100%" border="0" cellspacing="0" cellpadding="7" bgcolor="#FFFFFF">
                        <tr>
                          <td bgcolor="#F3F3F3">
                            <span style="font-family: 'Neue-Frutiger-W04-Light', arial, serif;
		            							  font-size: 11px; color:#333333"><a href="https://www.luxuryroundtable.com/" target="_blank" style="text-decoration:none; color:#333333">
                                &nbsp;VOICE OF LUXURY</a>
                            </span>
                          </td>
                          <td align="right" bgcolor="#F3F3F3" width="100"><span style="font-family: 'Neue-Frutiger-W04-Light', arial, serif; font-size: 11px; color:#333333"><?= date("F j, Y", strtotime('tomorrow')) ?></span></td>
                        </tr>
                      </table>
                    </td>
                  </tr>

                  <!--table width="100%" border="0" cellspacing="0" cellpadding="0" bgcolor="#FFFFFF"-->


                  <tr>
                    <td align="left" bgcolorx="#F2F7F9">
                      <table width="100%" border="0" cellspacing="0" cellpadding="0">
                        <tr>
                          <td align="left" bgcolorx="#F2F7F9">
                            <table width="100%" border="0" cellspacing="0" cellpadding="5">
                              <tr>
                                <td valign="top">
                                  <a style="text-decoration:none;" href="https://www.luxuryroundtable.com" target="_blank"><img width="400" src=<?= $url_root . "/img/LuxuryRoundtable.png" ?> border="0"></a>
                                </td>
                              </tr>
                              <tr>
                                <td>

                                  <table border="0" align="" cellpadding="" cellspacing="0">
                                    <tr>
                                      <td align="center"><a href="https://www.luxuryroundtable.com" target="_blank" style="text-decoration: none; font-size: 12px; font-weight: bold; color:#1169aa; font-family: 'Neue-Frutiger-W04-Light', arial, serif;">Read more on www.luxuryroundtable.com</a></td>
                                      <td align="center"><span style="font-size: 14px; font-weight: bold; color: #ACACAC;"> | </span></td>

                                      <td align="center">
                                        <a href="https://join.luxuryroundtable.com/LXR/?f=paid" target="_blank" style="font-size: 12px; font-weight: bold; color:#1169aa; font-family: 'Neue-Frutiger-W04-Light', arial, serif; text-decoration:none;">JOIN</a>

                                      </td>


                                    </tr>
                                  </table>
                                </td>
                              </tr>



                            </table>
                          </td>
                        </tr>



                      </table>
                    </td>
                  </tr>
                  <tr>
                    <td align="left" valign="top">
                      <table width="100%" border="0" cellspacing="0" cellpadding="5">
                        <tr>
                          <td width="100%" align="left" valign="top">

                            <?php $counter = 0; ?>

                            <?php $counter++; ?>
                            <?php

                            if ($date) {
                              $querystr = "
                                  SELECT DISTINCT $wpdb->posts.*
                                  FROM $wpdb->posts

                                  INNER JOIN wp_term_relationships
                                  ON (wp_posts.ID = wp_term_relationships.object_id)
                                  INNER JOIN wp_term_taxonomy
                                  ON (wp_term_relationships.term_taxonomy_id = wp_term_taxonomy.term_taxonomy_id)
                                  JOIN wp_postmeta ON (wp_posts.ID = wp_postmeta.post_id)

                                  WHERE

                                    wp_term_taxonomy.taxonomy = 'category'
                                  AND wp_term_taxonomy.term_id IN ('437')

                                  AND $wpdb->posts.post_status = 'publish'
                                    AND $wpdb->posts.post_type = 'post'

                                    AND $wpdb->posts.post_date >= '$date 00:00:00'
                                    AND $wpdb->posts.post_date <= '$date 23:59:59'



                                    ORDER BY $wpdb->posts.post_date DESC
                                    LIMIT 15
                              ";

                              $posts = $wpdb->get_results($querystr, OBJECT);
                            } else {

                              $args = array(
                                'numberposts' => 15,
                                'order' => 'DESC',
                                'category' => 437,
                                'post_status' => $post_status

                              );

                              $posts = get_posts($args);
                            }

                            $post_arr = array();
                            $oldest_post_date = "";

                            foreach ($posts as $post) : setup_postdata($post);

                              $post_date = $post->post_date;

                              $id = $post->ID;

                              $key = $post_date . '_' . $id;

                              $post_arr[$key] = $id;

                              $oldest_post_date = $post_date;


                            endforeach;

                            $post_arr_2 = ld16_merge_stickies($oldest_post_date, $post_arr);

                            $cat_font = 15;
                            $title_font = 23;
                            $blurb_font = 16;

                            foreach ($post_arr_2 as $post_id) {

                              $post = get_post($post_id);
                              setup_postdata($post);

                            ?>
                              <?php $counter++; ?>
                              <?php $cat = get_post_meta($post->ID, 'cat', true); ?>
                              <?php $catname = get_post_meta($post->ID, 'catname', true); ?>

                              <table width="100%" border="0" cellspacing="4" cellpadding="4" style="border-top: 1px solid #ccc;">
                                <tr>
                                  <td colspan=2 style='padding:0in 0in 0in 0in'>
                                    <p>
                                      <a style="text-decoration: none; font-size:<?= $cat_font ?>px; color:grey; font-weight:normal;" href="<?php echo $cat; ?>">
                                        <?php echo $catname; ?>
                                      </a>

                                    <div style="height: 1px;"></div>

                                    <a style="text-decoration: none; font-family: 'Neue-Frutiger-W04-Light', arial, serif; font-size:<?= $title_font ?>px; color: #000; font-weight: bold; line-height: 22px;" href="<?= ld16_permalink($post->ID) ?>">

                                      <?php the_title(); ?>

                                    </a>
                                    </p>
                                  </td>
                                </tr>
                                <tr>
                                  <td valign="top">

                                  
                                      <a style="text-decoration:none;" href="<?php the_permalink() ?>">
                                        <img class="main_img_newsletter" style="padding-right: 20px; padding-bottom: 20px;" border="0" align="left" src="<?= get_post_meta($post->ID, 'main_img_newsletter', true); ?>" />
                                      </a>

                                   
                                      <span style="font-size: <?= $blurb_font ?>px; line-height: <?= $blurb_font ?>px;">
                                        <?= ld16_get_the_excerpt() ?>
                                      </span>

                                      <p>
                                        <span style='font-size:10.0pt;font-family: 'Neue-Frutiger-W04-Light', arial, serif;
                                        color:#333333'>
                                          <a style="text-decoration: none; color: grey;" href="<?php the_permalink() ?>">Entire
                                            article</a>
                                      </p>

                                                                     

                                  </td>
                                </tr>
                              </table>

                            <?php }?>

                          </td>

                        </tr>
                      </table>
                    </td>

                  </tr>
                  <!-- /table-->

              </td>
            </tr>


            <tr>
              <td>
                <table width="100%" border="0" cellspacing="0" cellpadding="10" style="font-family: 'Neue-Frutiger-W04-Light', arial, serif; font-size:13px; border-top: 1px solid #D3D3D3; border-bottom: 1px solid #D3D3D3;">
                  <tr>
                    <td bgcolor="#FFFFFF">Read all the news on <a style="text-decoration:none;" href="https://www.luxuryroundtable.com/" target="_blank">www.luxuryroundtable.com</a> |
                      <a style="text-decoration:none;" href="https://www.luxuryroundtable.com/subscription-form">Subscribe</a> |
                      <a style="text-decoration:none;" href="https://www.luxuryroundtable.com/?s=%20">Search</a>
                    </td>
                  </tr>
                </table>
              </td>
            </tr>



            <tr>
              <td>
                <table width="100%" cellspacing="0" cellpadding="10" border="0" bgcolor="#FFFFFF">
                  <tbody>
                    <tr>
                      <td>


                        <p style="font-family: 'Neue-Frutiger-W04-Light', arial, serif; font-size:13px">

                          <a style="text-decoration:none;" target="_blank" href="https://www.instagram.com/luxuryroundtable/">
                          <img src="<?= $url_root ?>/img/sharing/instagram.jpg" width="24" height="24" /></a> &nbsp;
                         
                          <a style="text-decoration:none;" target="_blank" 
                            href="http://www.linkedin.com/shareArticle?mini=true&amp;url=https%3A%2F%2Fwww.luxuryroundtable.com">
                            <img src="<?= $url_root ?>/img/sharing/linkedin.png" width="24" height="24" /></a> &nbsp;

                        </p>


                        <p style="font-family: 'Neue-Frutiger-W04-Light', arial, serif; font-size:13px">
                          <a style="text-decoration:none;" target="_blank" href="https://www.luxuryroundtable.com/about/">About</a>
                          <a style="text-decoration:none;" target="_blank" href="https://www.luxuryroundtable.com/category/networking-and-events">Networking & Events</a> |
                          <a style="text-decoration:none;" target="_blank" href="https://www.luxuryroundtable.com/category/research">Research</a> |
                          <a style="text-decoration:none;" target="_blank" href="https://www.luxuryroundtable.com/category/news">News</a> |
                          <a style="text-decoration:none;" target="_blank" href="https://www.luxuryroundtable.com/subscription-form/">Free Newsletter</a> |
                          <a style="text-decoration:none;" target="_blank" href="https://www.luxuryroundtable.com/category/luxury-class">Luxury Class</a> |
                          <a style="text-decoration:none;" target="_blank" href="https://www.luxuryroundtable.com/partners/">Partners</a> |
                          <a style="text-decoration:none;" target="_blank" href="https://join.luxuryroundtable.com/LXR/?f=paid">JOIN</a> |
                        </p>


                        <p style="font-family: 'Neue-Frutiger-W04-Light', arial, serif; font-size:13px">
                        </p>

                        <p style="font-family: 'Neue-Frutiger-W04-Light', arial, serif; font-size:13px">

                          <a style="text-decoration:none;" target="_blank" href="https://www.luxuryroundtable.com/privacy-policy">Privacy Policy</a> |

                          <a style="text-decoration:none;" target="_blank" href="https://www.luxuryroundtable.com/copyright-policy">Copyright Policy</a> |

                          <a style="text-decoration:none;" target="_blank" href="https://www.luxuryroundtable.com/cookie-policy">Cookie Policy</a> |

                          <a style="text-decoration:none;" target="_blank" href="https://www.luxuryroundtable.com/terms">Member Agreement and Terms of Use</a> |

                          <a style="text-decoration:none;" target="_blank" href="https://www.luxuryroundtable.com/contact-us">Contact Us</a> 
                        </p>

                        <p style="font-family: 'Neue-Frutiger-W04-Light', arial, serif; font-size:11px">

                          News tips: <a style="text-decoration:none;" href="mailto:news@napean.com">news@napean.com</a>

                          <br />

                          Advertising: <a style="text-decoration:none;" href="mailto:ads@napean.com">ads@napean.com</a>

                          <br />

                          Customer service: <a style="text-decoration:none;" href="mailto:help@napean.com">help@napean.com</a>

                        <p style="font-family: 'Neue-Frutiger-W04-Light', arial, serif; font-size:11px">

                          Copyright © <?= date('Y') ?> Napean LLC. All rights reserved.

                          <a style="text-decoration:none;" target="_blank" href="https://www.luxuryroundtable.com/privacy-policy/">
                            Privacy policy</a> |

                          <a style="text-decoration:none;" target="_blank" href="https://www.luxuryroundtable.com/contact-us/">
                            Contact us</a>

                          <br />

                          590 Madison Avenue, 21st Floor, New York, NY 10022

                        </p>

                        <table width="100%" cellspacing="1" border="0">

                          <tbody>

                            <tr>

                              <td style="font-family: 'Neue-Frutiger-W04-Light', arial, serif; font-size:11px">

                                <strong style="font-size:12px;">

                                
                                  Luxury Roundtable is a subsidiary of Napean LLC. &nbsp;Thank you for reading us. &nbsp;Please click here to <a style="text-decoration:none;" target="_blank" href="*|UNSUB|*">unsubscribe</a>.
                                </strong>

                              </td>
                            </tr>
                          </tbody>

                        </table>



                      </td>
                    </tr>
                  </tbody>
                </table>
              </td>
            </tr>

          </table>
          <!--[/RAW]     -->




        </td>
      </tr>
    </table>
    <!-- End of wrapper table -->


    <!--[if (gte mso 9)|(IE)]></td></tr></table><![endif]-->

  </center>

</body>

</html>