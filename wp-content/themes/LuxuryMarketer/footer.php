<?php

/**
 * The template for displaying the footer
 *
 * Contains the closing of the #content div and all content after
 *
 * @package WordPress
 * @subpackage Twenty_Sixteen
 * @since Twenty Sixteen 1.0
 */

$url_root = ld16_cdn(get_template_directory_uri());
?>

<div id="footer-divider" class="section divider"></div>
<footer class="thicken container">

	<div class="row sharing">

		<div class="col-lg-12">
			<span class="heading">FOLLOW US: </span>

			<span class="mr_social_sharing"> <a href="https://www.instagram.com/luxurymarketer/" class="mr_social_sharing_popup_link" rel="nofollow"> <img src="<?= $url_root ?>/img/sharing/instagram.jpg" class="nopin" alt="Share on Instagram" title="Share on Instagram"> </a> </span>

			&nbsp;



			<span class="mr_social_sharing"> <a href="http://www.linkedin.com/shareArticle?mini=true&amp;url=https%3A%2F%2Fwww.luxurymarketer.com" class="mr_social_sharing_popup_link" rel="nofollow"> <img src="<?= $url_root ?>/img/sharing/linkedin.png" class="nopin" alt="Share on LinkedIn" title="Share on LinkedIn"> </a> </span>


			&nbsp;

		</div>
	</div>

	<div class="row subscribe">

		<div class="col-lg-12">
			<div class="col-lg-8 subscribe">


				<ul class="clr">
					<li><span class="heading">SUBSCRIBE TO FREE NEWSLETTERS: </span> &nbsp;</li>
					<li>
						<input type="text" class="form-control" placeholder="EMAIL ADDRESS">
					</li>
					<li>
						<a href="#" class="footer-subscribe"><img src="<?= $url_root ?>/img/subscribe.png"></a>
					</li>
				</ul>
			</div>
		</div>

	</div>

	<div class="row service">

		<div class="col-lg-12">

			<span class="heading">CUSTOMER SERVICE: </span> &nbsp;

			<a class="reverse" href="mailto:help@napean.com">help@napean.com</a>

		</div>

	</div>

	<div class="row footer-nav">
		<div class="col-lg-12">



		</div>
	</div>

	<div class="row emails">

		<div class="col-lg-12">

			<span class="heading">NEWS TIPS: </span> &nbsp;

			<a class="reverse" href="mailto:news@napean.com">news@napean.com</a> &nbsp;

			<br class="mobile" />

			<span class="heading">SPONSORSHIPS: </span> &nbsp;

			<a class="reverse" href="mailto:ads@napean.com">ads@napean.com</a> &nbsp;

		</div>

	</div>

	<div class="row search">

		<div class="col-lg-12">
			<div class="col-lg-8">


				<ul class="clr">
					<li><span class="heading">SEARCH: </span> &nbsp;</li>
					<li><input type="text" class="form-control" placeholder="SEARCH"></li>
					<li><a href="#" class="footer-magnify"><img src="<?= $url_root ?>/img/magnify-new.png"></a></li>
				</ul>
			</div>
		</div>

	</div>
	<div class="row footer-nav">
		<div class="col-lg-12">

			<ul class="list-unstyled">

				<li>
					<a href="/privacy-policy">Privacy Policy</a>
				</li>
				<li>
					<a href="/copyright-policy">Copyright Policy</a>
				</li>
				<li>
					<a href="/cookie-policy">Cookie Policy</a>
				</li>
				<li>
					<a href="/terms">Member Agreement and Terms of Use</a>
				</li>
				<li>
					<a href="/contact-us">Contact Us</a>
				</li>
			</ul>

		</div>
	</div>

	<div class="row copyright">
		<div class="col-lg-12">

			&copy; <?= date('Y') ?> Napean LLC.&nbsp;Luxury Marketer is a subsidiary of Napean LLC.&nbsp;All rights reserved.

		</div>

		<div class="pull-right">
			<a class="reverse" href="#top">Back to top</a>
		</div>
	</div>
	<div class="cookie-policy-box hidden">
		<p>
			We use cookies and tracking tags to offer you a better user experience. Please continue browsing if you are
			<button class="accept">OK</button> with this or<a href="/cookie-policy">find out more here</a>. <button class="cancel">Close</button>
		</p>
	</div>
</footer>

<?php $url_root = get_template_directory_uri(); ?>

<?php wp_footer(); ?>


</body>

</html>