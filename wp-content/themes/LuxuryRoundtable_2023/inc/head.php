<meta charset="utf-8">
<title>Luxury Roundtable</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<link rel="shortcut icon" href="<?=ld16_cdn($url_root) ?>/favicon.ico" type="image/x-icon" />
<link rel="stylesheet" href="<?=ld16_cdn($url_root) ?>/css/bootstrap.css" media="screen">
<link rel="stylesheet" href="<?=ld16_cdn($url_root) ?>/css/custom.min.css">
<link rel="stylesheet" href="<?=ld16_cdn($url_root) ?>/css/bootstrap.icon-large.min.css">
<link rel="stylesheet" href="<?=ld16_cdn($url_root) ?>/css/fonts.css">

<link rel="stylesheet" type="text/css" href="<?=ld16_cdn($url_root) ?>/css/FrutigerNeueWebfontsKit.css"/>

<style type="text/css">

/* .CheltenhamStd-Bold {
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
  } */
  .NeueFrutiger-Light {
  	font-family: Neue-Frutiger-W04-Light;
  	font-weight: normal;
  	font-style: normal;
  }
  /* .CheltenhamStd-LightItalic {
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
  } */

  /******* End of Frutiger Neue Font **********/

  /* .CheltenhamStd-Bold {
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
  } */

		/*CheltenhamStd-Light*/
    @import url("https://fast.fonts.net/lt/1.css?apiType=css&c=1251ad9c-b83f-40c6-ab0f-4d59a447038b&fontids=5675029");
    @font-face{
        font-family:"ITC Cheltenham W03 Light";
        src:url("<?=ld16_cdn($url_root) ?>/css/Fonts/5675029/14c1a467-8fb4-4917-ad51-b65b60dc1f70.eot?#iefix");
        src:url("<?=ld16_cdn($url_root) ?>/css/Fonts/5675029/14c1a467-8fb4-4917-ad51-b65b60dc1f70.eot?#iefix") format("eot"),
					url("<?=ld16_cdn($url_root) ?>/css/Fonts/5675029/64a08d6f-8ae7-49c6-9502-726c709d7825.woff2") format("woff2"),
					url("<?=ld16_cdn($url_root) ?>/css/Fonts/5675029/0c9e1d03-606f-4b4c-a9ed-18376802c5b3.woff") format("woff"),
					url("<?=ld16_cdn($url_root) ?>/css/Fonts/5675029/0b7c6632-ef51-4df8-84ab-27041f8ad0df.ttf") format("truetype");
    }
</style>

<link rel="stylesheet" href="<?=ld16_cdn($url_root) ?>/style.css">
<link rel="stylesheet" href="<?=ld16_cdn($url_root)?>/css/ld.css?<?=time()?>">

<?php if(ld16_is_pdf()){?>
	<link rel="stylesheet" href="https://www.luxuryroundtable.com/wp-content/themes/LuxuryRoundtable_2023/css/pdf.css">

<?php }?>


<link rel="stylesheet" href='<?=ld16_cdn($url_root) ?>/css/mobile-init.css?2' />

<link rel="stylesheet" media='screen and (max-width: 500px)' href='<?=ld16_cdn($url_root)?>/css/mobile-header.css?2' />

<!--link rel="stylesheet" media='screen and (max-width: 400px)' href='<?=ld16_cdn($url_root) ?>/css/mobile.css' /-->

<style>
   /* Hide Google Recaptcha iFrame */
   iframe {
    display: none;
    opacity: 0;
    width: 0;
    height: 0;
    overflow: hidden;
	position: fixed;
	top: -10000px;
	left: -10000px;
	visibility:hidden;
  }
  /* end */

  body iframe[width="465"]{
    display: initial;
    opacity: initial;
    width: 465px;
    height: 315px;
    overflow: initial;
    position: initial;
    top: initial;
    left: initial;
    visibility:initial;
  }

  .ad iframe{display: block;}
</style>

<!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
<!--[if lt IE 9]>
<script src="<?=ld16_cdn($url_root)?>/js/html5shiv.js"></script>
<script src="<?=ld16_cdn($url_root)?>/js/respond.min.js"></script>
<![endif]-->

<!-- Global site tag (gtag.js) - Google Analytics -->
<script async src="https://www.googletagmanager.com/gtag/js?id=UA-179150706-1"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'UA-179150706-1');
</script>

<!--script type='text/javascript'>
	var googletag = googletag || {};
	googletag.cmd = googletag.cmd || [];
	(function() {
		var gads = document.createElement('script');
		gads.async = true;
		gads.type = 'text/javascript';
		var useSSL = 'https:' == document.location.protocol;
		gads.src = ( useSSL ? 'https:' : 'http:') + '//www.googletagservices.com/tag/js/gpt.js';
		var node = document.getElementsByTagName('script')[0];
		node.parentNode.insertBefore(gads, node);
	})();

</script-->

<script async='async' src='https://www.googletagservices.com/tag/js/gpt.js'></script>
<script>
  var googletag = googletag || {};
  googletag.cmd = googletag.cmd || [];
</script>

<script>
  googletag.cmd.push(function() {
    googletag.defineSlot('/60923973/am-large-rectangle-1-home', [336, 280], 'am-large-rectangle-1-home').addService(googletag.pubads());
    googletag.pubads().enableSingleRequest();
    googletag.enableServices();
  });
</script>

<script>
  googletag.cmd.push(function() {
    googletag.defineSlot('/60923973/am-large-rectangle-2-home', [336, 280], 'am-large-rectangle-2-home').addService(googletag.pubads());
    googletag.pubads().enableSingleRequest();
    googletag.enableServices();
  });
</script>

<script>
  googletag.cmd.push(function() {
    googletag.defineSlot('/60923973/am-large-rectangle-3-home', [336, 280], 'am-large-rectangle-3-home').addService(googletag.pubads());
    googletag.pubads().enableSingleRequest();
    googletag.enableServices();
  });
</script>

<script>
  googletag.cmd.push(function() {
    googletag.defineSlot('/60923973/am-large-rectangle-1-category', [336, 280], 'am-large-rectangle-1-category').addService(googletag.pubads());
    googletag.pubads().enableSingleRequest();
    googletag.enableServices();
  });
</script>

<script>
  googletag.cmd.push(function() {
    googletag.defineSlot('/60923973/am-large-rectangle-2-category', [336, 280], 'am-large-rectangle-2-category').addService(googletag.pubads());
    googletag.pubads().enableSingleRequest();
    googletag.enableServices();
  });
</script>

<script>
  googletag.cmd.push(function() {
    googletag.defineSlot('/60923973/am-large-rectangle-3-category', [336, 280], 'am-large-rectangle-3-category').addService(googletag.pubads());
    googletag.pubads().enableSingleRequest();
    googletag.enableServices();
  });
</script>

<script>
  googletag.cmd.push(function() {
    googletag.defineSlot('/60923973/large-rectangle-1-article', [336, 280], 'large-rectangle-1-article').addService(googletag.pubads());
    googletag.pubads().enableSingleRequest();
    googletag.enableServices();
  });
</script>

<script>
  googletag.cmd.push(function() {
    googletag.defineSlot('/60923973/am-large-rectangle-2-article', [336, 280], 'am-large-rectangle-2-article').addService(googletag.pubads());
    googletag.pubads().enableSingleRequest();
    googletag.enableServices();
  });
</script>

<script>
  googletag.cmd.push(function() {
    googletag.defineSlot('/60923973/am-large-rectangle-3-article', [336, 280], 'am-large-rectangle-3-article').addService(googletag.pubads());
    googletag.pubads().enableSingleRequest();
    googletag.enableServices();
  });
</script>


<!--script src="<?=ld16_cdn($url_root)?>/js/jquery-1.10.2.min.js"></script-->

<script src="<?=ld16_cdn($url_root) ?>/js/jquery-1.11.1.min.js"></script>

<script>
	$(window).load(function() {
	  // When the page has loaded
	  $("iframe").show();
	});
</script>

<script src="<?=ld16_cdn($url_root) ?>/js/jquery-ui.min.js"></script>

<script src="<?=ld16_cdn($url_root) ?>/js/jquery.form.min.js"></script>
