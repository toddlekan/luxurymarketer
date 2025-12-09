<?php
/* Short and sweet */
define('WP_USE_THEMES', false);
require(dirname(__FILE__).'/../../../wp-blog-header.php');

$posts = get_posts(array('post__in' => array($_GET['id'])));
foreach ($posts as $post) : setup_postdata( $post );

  $content = get_the_content();
  $content = '<p>' . str_replace("\r\n", '</p><p>', $content) . '</p>';
  $content = str_replace("</strong></p><p>", '</strong><br />', $content);

  $paragraphAfter= 5; //shows the ad after paragraph 1

  $content = explode("</p>", $content);
  for ($i = 0; $i <count($content); $i++) {

    if ($i == $paragraphAfter) {

  ?>

    <p class="banner">

      <script type='text/javascript'>
        googletag.cmd.push(function() {
          googletag.defineSlot('/60923973/mid-article-micro-bar', [234, 60], 'div-gpt-ad-1466024462178-0').addService(googletag.pubads());
          googletag.pubads().enableSingleRequest();
          googletag.pubads().collapseEmptyDivs();
          googletag.enableServices();
        });
      </script>

      <!-- /60923973/mid-article-micro-bar -->
      <div id='div-gpt-ad-1466024462178-0' class="hide-iframe" style='height:60px; width:234px;margin: 0 auto;'>
      <script type='text/javascript'>
      googletag.cmd.push(function() { googletag.display('div-gpt-ad-1466024462178-0'); });
      </script>
      </div>


    </p>

  <?

    }

    echo $content[$i] . "</p>";

  }

endforeach;

?>
