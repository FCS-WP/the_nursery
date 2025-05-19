<?php 
	if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

	$share_link = get_the_permalink();
	$share_title = get_the_title();

	$thumb_id = get_post_thumbnail_id();
	$thumb_url = wp_get_attachment_image_src( $thumb_id, 'thumbnail-size', true );

	$social_button_list = [
		'facebook' => [
			'title' => esc_html__( 'Facebook', 'woolentor' ),
			'url' 	=> 'https://www.facebook.com/sharer/sharer.php?u='.$share_link,
		],
		'twitter' => [
			'title' => esc_html__( 'Twitter', 'woolentor' ),
			'url' 	=> 'https://twitter.com/share?url=' . $share_link.'&amp;text='.$share_title,
		],
		'pinterest' => [
			'title' => esc_html__( 'Pinterest', 'woolentor' ),
			'url' 	=> 'https://pinterest.com/pin/create/button/?url='.$share_link.'&media='.$thumb_url[0],
		],
		'linkedin' => [
			'title' => esc_html__( 'Linkedin', 'woolentor' ),
			'url' 	=> 'https://www.linkedin.com/shareArticle?mini=true&url='.$share_link.'&amp;title='.$share_title,
		],
		'email' => [
			'title' => esc_html__( 'Email', 'woolentor' ),
			'url' 	=> 'mailto:?subject='.esc_html__('Check%20this%20', 'woolentor') . $share_link,
		],
		'reddit' => [
			'title' => esc_html__( 'Reddit', 'woolentor' ),
			'url' 	=> 'http://reddit.com/submit?url='.$share_link.'&amp;title='.$share_title,
		],
		'telegram' => [
			'title' => esc_html__( 'Telegram', 'woolentor' ),
			'url' 	=> 'https://telegram.me/share/url?url=' . $share_link,
		],
		'odnoklassniki' => [
			'title' => esc_html__( 'Odnoklassniki', 'woolentor' ),
			'url' 	=> 'https://www.odnoklassniki.ru/dk?st.cmd=addShare&st.s=1&st._surl=' . $share_link,
		],
		'whatsapp' => [
			'title' => esc_html__( 'WhatsApp', 'woolentor' ),
			'url' 	=> 'https://wa.me/?text=' . $share_link,
		],
		'vk' => [
			'title' => esc_html__( 'VK', 'woolentor' ),
			'url' 	=> 'https://vk.com/share.php?url=' . $share_link,
		],
	];


	$default_buttons = [
        'facebook'   => esc_html__( 'Facebook', 'woolentor' ),
        'twitter'    => esc_html__( 'Twitter', 'woolentor' ),
        'pinterest'  => esc_html__( 'Pinterest', 'woolentor' ),
        'linkedin'   => esc_html__( 'Linkedin', 'woolentor' ),
        'telegram'   => esc_html__( 'Telegram', 'woolentor' ),
    ];
	$button_list = woolentor_get_option( 'social_share_buttons','woolentor_quickview_settings', $default_buttons );
	$button_text = woolentor_get_option( 'social_share_button_title','woolentor_quickview_settings', 'Share:' );

?>

<div class="woolentor-quickview-social-share">
	<span class="woolentor-quickview-social-title"><?php esc_html_e( $button_text, 'woolentor' ); ?></span>
	<ul>
		<?php
			foreach ( $button_list as $buttonkey => $button ) {
				?>
				<li>
					<a rel="nofollow" href="<?php echo esc_url( $social_button_list[$buttonkey]['url'] ); ?>" <?php echo ( $buttonkey === 'email' ? '' : 'target="_blank"' ) ?>>
						<span class="woolentor-quickview-social-icon">
							<?php echo woolentor_quickview_icon_list( $buttonkey ); ?>
						</span>
					</a>
				</li>
				<?php
			}
		?>
	</ul>
</div>
