<?php
/**
 * Locked page body: teaser paragraphs + subscriber CTA + encrypted full HTML (same behavior as single posts).
 *
 * @package WordPress
 * @subpackage LuxuryMarketer
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

global $post;

$content = get_the_content( null, false, $post );
$content = '<p>' . str_replace( "\r\n", '</p><p>', $content ) . '</p>';
$content = '<p>' . str_replace( "\n", '</p><p>', $content ) . '</p>';
$content = str_replace( '</strong></p><p>', '</strong><br />', $content );
$content = str_replace( 'src="https://www.luxurymarketer.com', 'src="https://www.luxurymarketer.com', $content );

preg_match_all( '#(\[caption.*?\[\/caption\])#', $content, $captions );

if ( ! empty( $captions[0] ) ) {
	foreach ( $captions[0] as $caption ) {
		$stripped_caption     = str_replace( '[/caption]', '', $caption );
		$stripped_caption_arr = explode( ']', $stripped_caption );
		if ( count( $stripped_caption_arr ) > 1 ) {
			$content = str_replace( $caption, '<p class="caption"><font color="gray">' . $stripped_caption_arr[1] . '</font></p>', $content );
		} else {
			$content = str_replace( $caption, '', $content );
		}
	}
}

$content           = str_replace( chr( 194 ) . chr( 160 ), ' ', $content );
$paragraph_after   = 3;
$lock_after        = ld16_is_locked( $post->ID ) ? 3 : -1;

$content = explode( '</p>', $content );
?>
<div class="body locked" post-id="<?php echo esc_attr( (string) $post->ID ); ?>" token="<?php echo esc_attr( ld16_get_token( $post->ID ) ); ?>">
<?php
for ( $i = 0; $i < count( $content ); $i++ ) {
	echo $content[ $i ] . '</p>';

	if ( $i === $lock_after && ld16_is_locked( $post->ID ) ) {
		?>
		<div class="row call-to-action" style="display:block;">
			<div class="col-lg-12">
				<center>
					This content is accessible only to subscribers of Luxury Marketer. We would love for you to become a subscriber and enjoy the many benefits soon after. <a href="https://luxurymarketer.subsmediahub.com/LXM/?f=paid" target="_blank">Please click here to enroll as a subscriber of Luxury Marketer.</a> Already a subscriber?
					<a href="/log-in?redirect=<?php echo rawurlencode( get_permalink( $post ) ); ?>" class="pop-login">Please log in.</a>
				</center>
			</div>
		</div>
		<?php
		break;
	}

	if ( $i === $paragraph_after ) {
		// Mid-article ad placeholder (parity with single.php).
	}
}
?>
</div>
<div id="encrypted" style="display:none;">
<?php
$encrypted = '';
for ( $i = 0; $i < count( $content ); $i++ ) {
	$encrypted .= $content[ $i ] . '</p>';
}
$token = ld16_get_token( $post->ID );
echo ld16_encrypt( $encrypted, $token );
?>
</div>
