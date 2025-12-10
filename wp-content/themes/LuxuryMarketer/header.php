<?php

/**
 * The template for displaying the header
 *
 * Displays all of the head element and everything up until the "site-content" div.
 *
 * @package WordPress
 * @subpackage Twenty_Sixteen
 * @since Twenty Sixteen 1.0
 */

$file_root = dirname(__FILE__);
$url_root = get_template_directory_uri();

?>
<!DOCTYPE html>
<html <?php language_attributes(); ?> class="no-js">

<head>

  <?php
  $single_term_title = get_the_archive_title();

  if (!$single_term_title) {
    $single_term_title = get_the_title();
  }

  if ($single_term_title == 'Archives') {
    $single_term_title = 'Luxury Marketer';
  }

  ?>

  <!-- Facebook Pixel Code -->
  <script>
    ! function(f, b, e, v, n, t, s) {
      if (f.fbq) return;
      n = f.fbq = function() {
        n.callMethod ?
          n.callMethod.apply(n, arguments) : n.queue.push(arguments)
      };
      if (!f._fbq) f._fbq = n;
      n.push = n;
      n.loaded = !0;
      n.version = '2.0';
      n.queue = [];
      t = b.createElement(e);
      t.async = !0;
      t.src = v;
      s = b.getElementsByTagName(e)[0];
      s.parentNode.insertBefore(t, s)
    }(window, document, 'script',
      'https://connect.facebook.net/en_US/fbevents.js');
    fbq('init', '1775042012546562');
    fbq('track', 'PageView');
  </script>
  <noscript>
    <img height="1" width="1" src="https://www.facebook.com/tr?id=1775042012546562&ev=PageView
		&noscript=1" />
  </noscript>
  <!-- End Facebook Pixel Code -->
  <script type="text/javascript">
    //var $appV = window.location.href;
    //if($appV.indexOf('file:///') == 0){
    if (navigator.serviceWorker.controller) {
      console.log('[PWA Builder] active service worker found, no need to register');
    } else {
      navigator.serviceWorker.register('/swrker.js?' + Math.floor(Math.random() * 1000), {
        scope: '/'
      }).then(function(reg) {
        console.log('Service worker has been registered for scope:' + reg.scope);
      });
    }
    //}
  </script>
  <meta charset="<?php bloginfo('charset'); ?>">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="id" content="<?= get_the_ID(); ?>">
  <link rel="manifest" href="manifest.json">
  </link>
  <?php if (is_singular() && pings_open(get_queried_object())) : ?>
    <link rel="pingback" href="<?php bloginfo('pingback_url'); ?>">
  <?php endif; ?>

  <?php wp_head(); ?>
  <?php require_once($file_root . "/inc/head.php"); ?>

  <?php $url_root = ld16_cdn(get_template_directory_uri()); ?>


</head>

<body <?php if (ld16_is_pdf()) {
        print 'class="pdf"';
      } ?> <?php if (is_home()) {
              print 'class="home"';
            } else {
              print 'class="home inner"';
            } ?>>
  <?php


  $isMobileApp = isset($_GET['mobile']) ? $_GET['mobile'] : '';
  $isMobileApp = (!empty($isMobileApp) && $isMobileApp == 'true' ? TRUE : FALSE);


  ?>















<div id="lr-header" class="navbar navbar-default navbar-fixed-top">





    <button class="navbar-toggle <?php echo (is_home() && $isMobileApp ? 'no-back-btn' : '') ?>" type="button" data-toggle="collapse" data-target="#navbar-main">
      <div class="navbar-toggle-inner"></div>
    </button>


    <div class="navbar-header">

      <div class="container-fluid logo-container">

        <div class="logo-row">
          <div class="col-lg-2 logo-side">

              <br />
              <a href="/" class="initials">
                <img src="/wp-content/themes/LuxuryMarketer/img/lm-initials-50.png">
              </a>

              <div class="navbar">

                <ul class="nav navbar-nav">
                  <li class="dropdown">
                    <a href="#" class="desktop dropdown-toggle" type="button" data-toggle="dropdown" role="button" aria-expanded="false">
                      <ul class="clr">
                        <li class="bars">
                          <div class="bar bg-grey"></div>
                          <div class="bar bg-grey"></div>
                          <div class="bar bg-grey"></div>
                        </li>
                      </ul>
                    </a>

                    <ul class="dropdown-menu">
                      <li><a class="reverse" href="/">Home</a></li>
                      <li class="reverse"><a href="/category/research/fashion-and-leather-goods" class="grey">Fashion &amp; Leather Goods</a></li>
                      <li class="reverse"><a href="/category/research/real-estate" class="grey">Real Estate &amp; Design</a></li>
                      <li class="reverse"><a href="/category/research/retail" class="grey">Retail</a></li>
                      <li class="reverse"><a href="/category/research/cars-jets-and-yachts/">Cars, jets and yachts</a></li>
                      <li class="reverse"><a href="/category/research/travel-and-hospitality/" title="View all posts filed under Travel and hospitality">Travel and hospitality</a></li>
                      <li class="reverse"><a href="/category/research/watches-and-jewelry/" title="View all posts filed under Watches and jewelry">Watches and jewelry</a></li>
                      <li class="reverse"><a href="/category/research/wealth-management/" title="View all posts filed under Wealth management">Wealth management</a></li>
                      <li class="reverse"><a href="/category/research">Research</a></li>
                      <li class="reverse"><a href="/category/news/columns">Columns</a></li>
                      <li><a href="/category/networking-and-events">Events</a></li>
                    </ul>

                  </li>
                  <li>
                    <a href="#" class="header-magnify">
                      <img width="16" src="/wp-content/themes/LuxuryMarketer/img/magnify-new.png"></a>
                  </li>
                </ul>
              </div>

              <div class="label date grey">New York,
                  <script>
                    var options = {
                      year: 'numeric',
                      month: 'long',
                      day: 'numeric'
                    };
                    var today = new Date();

                    document.write(today.toLocaleDateString("en-US", options)); // September 17, 2016
                  </script>

              </div>
            

          </div>
          <div class="col-lg-8">
            <center>
              <a href="/" id="logo"><img class="main-logo home" src="<?= $url_root ?>/img/LuxuryMarketer-home.png"></a>
              <a href="/" id="logo"><img class="main-logo" src="<?= $url_root ?>/img/LuxuryMarketer.png"></a>
            </center>
          </div>
          <div class="col-lg-2 logo-side">
              <br />

              <ul class="nav navbar-nav" style="float: right;">
                <li class="label subscribe">

                  <a class="sign-in-subscribe loggedout pop-subscribe grey bold" href="/subscription-form" style="">
                    Subscribe</a>
                </li>

              </ul>
          </div>
        </div>


        <div class="navbar-collapse collapse" id="navbar-main">
          <ul class="no-bullet mobile-menu" style="float: left;">
            <li class="reverse"><a href="/category/research/fashion-and-leather-goods" class="grey">Fashion &amp; Leather Goods</a></li>
            <li class="reverse"><a href="/category/research/real-estate" class="grey">Real Estate &amp; Design</a></li>
            <li class="reverse"><a href="/category/research/retail" class="grey">Retail</a></li>
            <li class="reverse"><a href="/category/research/cars-jets-and-yachts/">Cars, jets and yachts</a></li>
            <li class="reverse"><a href="/category/research/travel-and-hospitality/" title="View all posts filed under Travel and hospitality">Travel and hospitality</a></li>
            <li class="reverse"><a href="/category/research/watches-and-jewelry/" title="View all posts filed under Watches and jewelry">Watches and jewelry</a></li>
            <li class="reverse"><a href="/category/research/wealth-management/" title="View all posts filed under Wealth management">Wealth management</a></li>
            <li class="reverse"><a href="/category/research">Research</a></li>
            <li class="reverse"><a href="/category/news/columns">Columns</a></li>
            <li><a href="/category/networking-and-events">Events</a></li>
            <li><a class="reverse join" href="<?php
                                              echo ($isMobileApp ? 'https://join.luxurymarketer.com/LXR/?f=paid' : 'https://join.luxurymarketer.com/LXR/?f=paid'); ?>">Subscribe</a></li>
          </ul>
        </div>

    </div>

  </div>


<div class="navbar-header menu-row">

<div class="container-fluid desktop-menu">

  <div class="page-header logo">

    <div class="navbar collapse navbar-collapse menu">
      <ul class="nav navbar-nav" style="float: none; margin: 0 auto; width: auto; gap:1rem; display:flex; justify-content: center;">

        <li class="item"><a href="/category/research/fashion-and-leather-goods" class="grey">Fashion &amp; Leather Goods</a></li>
        <li class="item"><a href="/category/research/real-estate" class="grey">Real Estate &amp; Design</a></li>
        <li class="item"><a href="/category/research/retail" class="grey">Retail</a></li>
        <li class="item"><a href="/category/research/cars-jets-and-yachts/" class="grey">Cars, jets and yachts</a></li>
        <li class="item"><a href="/category/research/travel-and-hospitality/" class="grey" title="View all posts filed under Travel and hospitality">Travel and hospitality</a></li>
        <li class="item"><a href="/category/research/watches-and-jewelry/" class="grey" title="View all posts filed under Watches and jewelry">Watches and jewelry</a></li>

                      
      </ul>
    </div>
  </div>
  <div class="page-header logo">
    <div class="navbar collapse navbar-collapse menu">

       <ul class="nav navbar-nav" style="float: none; margin: 0 auto; width: auto; gap:1rem; display:flex; justify-content: center;">
    
          <li class="item"><a href="/category/research/wealth-management/" class="grey" title="View all posts filed under Wealth management">Wealth management</a></li>

          <li class="item"><a href="/category/research" class="grey">Research</a></li>
          <li class="item"><a href="/category/news/columns" class="grey">Columns</a></li>
    
          <li class="dropdown item"> <a href="/category/news" class="dropdown-toggle grey" data-toggle="dropdown" role="button" aria-expanded="false">
            More <span class="caret"></span></a>
          <ul class="dropdown-menu">
          <li><a href="/category/research/china">China</a></li>
            <li><a href="/category/research/education">Education</a></li>
            <li><a href="/category/research/entertainment">Entertainment</a></li>
            <li><a href="/category/research/health-and-wellness/" title="View all posts filed under Health and wellness">Health &amp wellness</a></li>
            <li><a href="/category/news/legal-and-regulation">Legal & Regulation</a></li>
            <li><a href="/category/research/media-and-publishing">Media & Publishing</a></li>
            <li><a href="/category/research/outlook">Outlook</a></li>
            <li><a href="/category/research/philanthropy-foundations-and-nonprofits/" title="View all posts filed under Philanthropy, foundations and nonprofits">Philanthropy, foundations and nonprofits</a></li>
            <li><a href="/category/research/software-and-technology">Tech</a></li>
            <li><a href="/category/research/ai-and-automation">AI & Automation</a></li>
            <li><a href="/category/research/sports">Sports</a></li>
            <li><a href="/category/research/environment-and-sustainability">Sustainability</a></li> 
          </ul>
        </li>
        <li class="dropdown item"> <a href="/category/networking-and-events" class="dropdown-toggle grey" data-toggle="dropdown" role="button" aria-expanded="false">
            Events <span class="caret"></span></a>
          <ul class="dropdown-menu">
          <li><a href="/category/networking-and-events/webinars">Webinars</a></li>
          <li><a href="/category/networking-and-events/podcasts">Podcasts</a></li>
          <li><a href="/category/networking-and-events/conferences">Conferences</a></li>
          </ul>
        </li>

        <li class="dropdown item most-read"> <a href="#" class="dropdown-toggle grey" data-toggle="dropdown" role="button" aria-expanded="false">
            Most Read<span class="caret"></span></a>
          <ul class="dropdown-menu">
            <?php include('/tmp/most_popular_2016.lr.cache'); ?>
          </ul>
        </li>

        <li class="dropdown item most-read"> <a href="#" class="dropdown-toggle grey" data-toggle="dropdown" role="button" aria-expanded="false">
            Latest Headlines<span class="caret"></span></a>
          <ul class="dropdown-menu">
            <?php include($file_root.'/inc/sidebar/nav-latest.php') ?>
          </ul>
        </li>

        <li class="item"><a href="/category/networking-and-events/profiles" class="grey">Profiles</a></li>
        <li class="item"><a href="/category/master-class" class="grey">Master Class</a></li>
        
                      
      </ul>
    </div>
  </div>
  <div class="navbar collapse navbar-collapse sections">

    <ul class="nav navbar-nav">
      <li class="dropdown">
        <a href="#" class="desktop dropdown-toggle" type="button" data-toggle="dropdown" role="button" aria-expanded="false">
          <ul class="clr">
            <li class="bars">
              <div class="bar"></div>
              <div class="bar"></div>
              <div class="bar"></div>
            </li>
            <li class="label grey">Sections</li>
          </ul>
        </a>

        <ul class="dropdown-menu">
          <li class="reverse"><a href="/category/research/fashion-and-leather-goods" class="grey">Fashion &amp; Leather Goods</a></li>
          <li class="reverse"><a href="/category/research/real-estate" class="grey">Real Estate &amp; Design</a></li>
          <li class="reverse"><a href="/category/research/retail" class="grey">Retail</a></li>
          <li class="reverse"><a href="/category/research/cars-jets-and-yachts/">Cars, jets and yachts</a></li>
          <li class="reverse"><a href="/category/research/travel-and-hospitality/" title="View all posts filed under Travel and hospitality">Travel and hospitality</a></li>
          <li class="reverse"><a href="/category/research/watches-and-jewelry/" title="View all posts filed under Watches and jewelry">Watches and jewelry</a></li>
          <li class="reverse"><a href="/category/research/wealth-management/" title="View all posts filed under Wealth management">Wealth management</a></li>
          <li class="reverse"><a href="/category/research">Research</a></li>
          <li class="reverse"><a href="/category/news/columns">Columns</a></li>
          <li><a href="/category/networking-and-events">Events</a></li>
          <li><a class="reverse join" href="<?php
                                          echo ($isMobileApp ? 'https://join.luxurymarketer.com/LXR/?f=paid' : 'https://join.luxurymarketer.com/LXR/?f=paid'); ?>">Subscribe</a></li>
        </ul>
      </li>
    </li>
    </ul>
  </div>


  </div>











  <a href="#" class="mobile magnify" target="search">
    <div class="magnify-inner"></div>
  </a>
  <div class="navbar-collapse collapse" id="navbar-main">
    <ul class="no-bullet mobile-menu" style="float: left;">
      <li><a class="reverse" href="/">Home</a></li>
      <li><a class="reverse" href="/about">About</a></li>
      <li><a class="reverse" href="/about-membership">About Membership</a></li>
      <li><a class="reverse" href="/category/networking-and-events">Networking & Events</a></li>
      <li><a class="reverse" href="/category/research">Research</a></li>
      <li><a class="reverse" href="/category/news">News</a></li>
      <li><a class="reverse" href="/subscription-form">Free Newsletter</a></li>
      <li><a class="reverse" href="/category/luxury-class">Luxury Class</a></li>
      <li><a class="reverse" href="/partners">Partners</a></li>
      <li><a class="reverse join" href="<?php
                                        echo ($isMobileApp ? 'https://join.luxurymarketer.com/LXR/?f=paid' : 'https://join.luxurymarketer.com/LXR/?f=paid'); ?>">Join Our Luxury Program</a></li>
    </ul>
  </div>
  <div id="mobile-sub-menu">
    <span class="label subscribe">
      <a href="#" id="popular-mobile" target="popular">Most Read</a>
      <span style="color: #ccc;"> &nbsp; &nbsp; </span>

      <a class="sign-in-subscribe pop-subscribe loggedout" 
      style="display: none;" href="<?php
        echo ($isMobileApp ? '#' : 'https://join.luxurymarketer.com/LXR/?f=paid'); ?>" 
        id="subscribe-mobile">
        Subscribe
      </a>

    </span>
  </div>
</div>

</div>




<div class="navbar fold search">

  <div class="search-inner">

    <form class="navbar-form navbar-left search" role="search">
      <div class="form-group">
        <ul class="clr">
          <li>
            <input type="text" class="form-control" placeholder="Search">
          </li>
          <li>
            <a href="#" class="popup-magnify"><img src="<?= $url_root ?>/img/magnify-new.png"></a>

          </li>
        </ul>
      </div>
    </form>

  </div>
</div>
<div class="navbar fold popular">
  <div class="popular-inner">
    <ol class="">
      <?php include('/tmp/most_popular_2016.lr.cache'); ?>
    </ol>
  </div>
</div>
</div>









  <div class="<?= ((is_front_page() || is_home()) ? "container-fluid main" : "container main") ?>">

    <div class="overlay"></div>