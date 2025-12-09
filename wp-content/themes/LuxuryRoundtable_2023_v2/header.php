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
    $single_term_title = 'Luxury Roundtable';
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
              print 'class="inner"';
            } ?>>
  <?php


  $isMobileApp = isset($_GET['mobile']) ? $_GET['mobile'] : '';
  $isMobileApp = (!empty($isMobileApp) && $isMobileApp == 'true' ? TRUE : FALSE);


  ?>

  <div id="lr-header" class="navbar navbar-default navbar-fixed-top">


    <div class="logo-row">
      <div class="col-lg-12">
        <center>
          <a href="/" id="logo"><img class="main-logo home" src="<?= $url_root ?>/img/LuxuryRoundtable-home.png"></a>
          <a href="/" id="logo"><img class="main-logo" src="<?= $url_root ?>/img/LuxuryRoundtable.png"></a>
        </center>
      </div>

    </div>
    <button class="navbar-toggle <?php echo (is_home() && $isMobileApp ? 'no-back-btn' : '') ?>" type="button" data-toggle="collapse" data-target="#navbar-main">
      <div class="navbar-toggle-inner"></div>
    </button>

    <div class="navbar-header">

      <div class="container-fluid">

        <div class="page-header logo">

          <?php
          if (ld16_is_pdf()) {
          ?>
            <div class="pdf-header">
              <img src="<?= $url_root ?>/img/LuxuryRoundtable.png" style="width: 240px;" /></a>
              <div>Business at its best</div>
            </div>
          <?php
          }
          ?>
          <div class="navbar collapse navbar-collapse menu">
            <ul class="nav navbar-nav" style="float: none; margin: 0 auto; width: auto; gap:1rem; display:flex; justify-content: center;">

              <li class="item"><a href="/about" class="grey">About</a></li>

              <li class="dropdown item"> <a href="#" class="dropdown-toggle grey" data-toggle="dropdown" role="button" aria-expanded="false">Membership<span class="caret"></span></a>
                <ul class="dropdown-menu " role="menu">
                  <li><a href="/about-membership/">About Membership</a></li>
                  <li><a href="https://join.luxuryroundtable.com/LXR/?f=paid">Become a Member and Join Our Luxury Program</a></li>
                </ul>
              </li>

              <li class="dropdown item"> <a href="/category/networking-and-events" class="dropdown-toggle grey" data-toggle="dropdown" role="button" aria-expanded="false">Networking & Events<span class="caret"></span></a>
                <ul class="dropdown-menu " role="menu">
                  <li><a href="/category/networking-and-events/luxury-outlook-summit/" title="View all posts filed under Luxury Outlook Summit">Luxury Outlook Summit</a></li>
                  <li><a href="/category/networking-and-events/luxury-roundtable-annual-conference/" title="View all posts filed under Luxury Roundtable Annual Conference (LRAC)">Luxury Roundtable Annual Conference (LRAC)</a></li>
                  <li><a href="/category/networking-and-events/china-luxury-summit/" title="View all posts filed under China Luxury Summit">China Luxury Summit</a></li>
                  <li><a href="/category/networking-and-events/luxury-women-leaders-summit/" title="View all posts filed under Luxury Women Leaders Summit">Luxury Women Leaders Summit</a></li>
                  <li><a href="/category/networking-and-events/luxury-roundtable-awards/" title="View all posts filed under Luxury Roundtable Awards">Luxury Roundtable Awards</a></li>
                  <li><a href="/category/networking-and-events/podcasts/" title="View all posts filed under Podcasts">Podcasts</a></li>
                  <li><a href="/category/networking-and-events/webinars/" title="View all posts filed under Webinars">Webinars</a></li>
                  <li><a href="/category/networking-and-events/cocktail-receptions/" title="View all posts filed under Cocktail receptions">Cocktail receptions</a></li>
                </ul>
              </li>

              <li class="dropdown item"> <a href="/category/research" class="dropdown-toggle grey" data-toggle="dropdown" role="button" aria-expanded="false">Research<span class="caret"></span></a>
                <ul class="dropdown-menu multi-column columns-3" role="menu">
                  <div class="row">
                    <div class="col-sm-4">
                      <ul class="multi-column-dropdown">
                        <li><a href="/category/research/advertising-marketing/" title="View all posts filed under Advertising and marketing">Advertising and marketing</a></li>
                        <li><a href="/category/research/architecture-home-and-design/" title="View all posts filed under Architecture, home and design">Architecture, home and design</a></li>
                        <li><a href="/category/research/art-and-auctions/" title="View all posts filed under Art and auctions">Art and auctions</a></li>
                        <li><a href="/category/research/cars-jets-and-yachts/" title="View all posts filed under Cars, jets and yachts">Cars, jets and yachts</a></li>
                        <li><a href="/category/research/china/" title="View all posts filed under China">China</a></li>
                        <li><a href="/category/research/couture-fashion-and-leather-goods" title="View all posts filed under Couture, fashion and leather goods">Couture, fashion and leather goods</a></li>
                        <li><a href="/category/research/craftsmanship-and-metier" title="View all posts filed under Craftsmanship and métier">Craftsmanship and métier</a></li>
                        <li><a href="/category/research/culture-and-tradition" title="View all posts filed under Culture and tradition">Culture and tradition</a></li>
                        <li><a href="/category/research/education/" title="View all posts filed under Education">Education</a></li>
                      </ul>
                    </div>

                    <div class="col-sm-4">
                      <ul class="multi-column-dropdown">
                        <li><a href="/category/research/environment-and-sustainability/" title="View all posts filed under Environment and sustainability">Environment and sustainability</a></li>
                        <li><a href="/category/research/food-fine-dining-wines-and-spirits/" title="View all posts filed under Food, fine dining, wines and spirits">Food, fine dining, wines and spirits</a></li>
                        <li><a href="/category/research/health-and-wellness/" title="View all posts filed under Health and wellness">Health and wellness</a></li>
                        <li><a href="/category/research/legal-and-regulation/" title="View all posts filed under Legal and regulation">Legal and regulation</a></li>
                        <li><a href="/category/research/media-and-publishing/" title="View all posts filed under Media and publishing">Media and publishing</a></li>
                        <li><a href="/category/research/metaverse-web3-and-vr-ar/" title="View all posts filed under Metaverse, Web3 and VR/AR">Metaverse, Web3 and VR/AR</a></li>
                        <li><a href="/category/research/outlook/" title="View all posts filed under Outlook">Outlook</a></li>
                        <li><a href="/category/research/perfumes-and-cosmetics/" title="View all posts filed under Perfumes and cosmetics">Perfumes and cosmetics</a></li>
                      </ul>
                    </div>
                    <div class="col-sm-4">
                      <ul class="multi-column-dropdown">
                        <li><a href="/category/research/philanthropy-foundations-and-nonprofits/" title="View all posts filed under Philanthropy, foundations and nonprofits">Philanthropy, foundations and nonprofits</a></li>
                        <li><a href="/category/research/real-estate/" title="View all posts filed under Real estate">Real estate</a></li>
                        <li><a href="/category/research/retail/" title="View all posts filed under Retail">Retail</a></li>
                        <li><a href="/category/research/software-and-technology/" title="View all posts filed under Software and technology">Software and technology</a></li>
                        <li><a href="/category/research/sports/" title="View all posts filed under Sports">Sports</a></li>
                        <li><a href="/category/research/travel-and-hospitality/" title="View all posts filed under Travel and hospitality">Travel and hospitality</a></li>
                        <li><a href="/category/research/watches-and-jewelry/" title="View all posts filed under Watches and jewelry">Watches and jewelry</a></li>
                        <li><a href="/category/research/wealth-management/" title="View all posts filed under Wealth management">Wealth management</a></li>
                      </ul>
                    </div>
                  </div>
                </ul>
              </li>

              <li class="dropdown item"> <a href="/category/news" class="dropdown-toggle grey" data-toggle="dropdown" role="button" aria-expanded="false">
                  News <span class="caret"></span></a>
                <ul class="dropdown-menu">
                  <li><a href="/category/news/marketing">Marketing</a></li>
                  <li><a href="/category/news/retail-news">Retail</a></li>
                  <li><a href="/category/news/research-news">Research</a></li>
                  <li><a href="/category/news/columns">Columns</a></li>
                  <li><a href="/category/news/editorials">Editorials</a></li>
                  <li><a href="/category/news/strategy">Strategy</a></li>
                  <li><a href="/category/news/events">Events</a></li>
                  <li><a href="/category/news/editorial-calendar">Editorial calendar</a></li>
                  <li><a href="/category/news/press-releases">Press releases</a></li>
                </ul>
              </li>

              <li class="dropdown item"> <a href="#" class="dropdown-toggle grey" data-toggle="dropdown" role="button" aria-expanded="false">
                  Free Newsletter <span class="caret"></span></a>
                <ul class="dropdown-menu">
                  <li><a href="/subscription-form">Subscribe to Luxury Roundtable News</a></li>
                  <li><a href="/newsletter-archive">Luxury Roundtable Newsletter archive</a></li>
                </ul>
              </li>

              <li class="dropdown item"> <a href="/category/luxury-class" class="dropdown-toggle grey" data-toggle="dropdown" role="button" aria-expanded="false">
                  Luxury Class <span class="caret"></span></a>
                <ul class="dropdown-menu">
                  <li><a href="/category/luxury-class/luxury-class-on-embracing-the-luxury-mindset">Luxury Class on Embracing the Luxury Mindset</a></li>
                  <li><a href="/category/luxury-class/luxury-class-on-best-practices-in-luxury-marketing">Luxury Class on Best Practices in Luxury Marketing</a></li>
                  <li><a href="/category/luxury-class/luxury-class-on-best-practices-in-luxury-retail">Luxury Class on Best Practices in Luxury Retail</a></li>
                  <li><a href="/category/luxury-class/Luxury-class-on-etiquette-and-art-de-vivre">Luxury Class on Etiquette and Art de Vivre</a></li>
                </ul>
              </li>
              <li class="item"><a href="/partners" class="grey">Partners</a></li>

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

              <li class="item">
                <a href="<?php
                          echo ($isMobileApp ? 'https://join.luxuryroundtable.com/LXR/?f=paid' : 'https://join.luxuryroundtable.com/LXR/?f=paid'); ?>" class="join" role="button">
                  Join Our Luxury Program</a>
              </li>
              <li class="subscribe make-us item">
                <a class="my" href="/log-in" target="_blank" style="display: none;">My Account</a>

                <a class="sign-in loggedout pop-login grey" href="/log-in">Log In</a>
                <a class="logout-link loggedin pop-logout" style="display: none;" href="<?= $url_root ?>/../../plugins/cambey/logout.php">Log Out</a>

              </li>

              <li>
                <a href="#" class="magnify" target="search">
                  <div class="magnify-inner"></div>
                </a>
              </li>
              <!--li>
                <form class="navbar-form search" role="search">
                  <div class="form-group">
                    <ul class="clr">
                      <li>
                        <input style="width: 200px;" type="text" class="form-control" placeholder="Search">
                      </li>
                      <li>
                        <a href="#" class="header-magnify">
                          <img width="16" src="<?= $url_root ?>/img/magnify-new.png"></a>
                      </li>
                    </ul>
                  </div>
                </form>
              </li-->
            </ul>
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
                                                    echo ($isMobileApp ? 'https://join.luxuryroundtable.com/LXR/?f=paid' : 'https://join.luxuryroundtable.com/LXR/?f=paid'); ?>">Join Our Luxury Program</a></li>
                </ul>
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
                                              echo ($isMobileApp ? 'https://join.luxuryroundtable.com/LXR/?f=paid' : 'https://join.luxuryroundtable.com/LXR/?f=paid'); ?>">Join Our Luxury Program</a></li>
          </ul>
        </div>
        <div id="mobile-sub-menu">
          <span class="label subscribe">
            <a href="#" id="popular-mobile" target="popular">Most Read</a>
            <span style="color: #ccc;"> &nbsp; &nbsp; </span>

            <a class="sign-in-subscribe pop-subscribe loggedout" style="display: none;" href="<?php
                                                                                              echo ($isMobileApp ? '#' : 'https://join.luxuryroundtable.com/LXR/?f=paid'); ?>" id="subscribe-mobile">
              Join
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