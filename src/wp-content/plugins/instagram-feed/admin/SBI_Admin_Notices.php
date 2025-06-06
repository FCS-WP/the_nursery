<?php

/**
 * SBI Admin Notices.
 *
 * @since 6.0
 */

namespace InstagramFeed\Admin;

if (!defined('ABSPATH')) {
	exit; // Exit if accessed directly.
}

use InstagramFeed\Builder\SBI_Db;
use InstagramFeed\Helpers\Util;
use InstagramFeed\SBI_Response;

class SBI_Admin_Notices
{
	/**
	 * CFF License Key
	 */
	public $sbi_license;

	public function __construct()
	{
		$this->init();
	}

	/**
	 * Determining if the user is viewing the our page, if so, party on.
	 *
	 * @since 4.0
	 */
	public function init()
	{
		if (!is_admin()) {
			return;
		}
		add_action('in_admin_header', [$this, 'remove_admin_notices']);
		add_action('wp_ajax_sbi_check_license', [$this, 'sbi_check_license']);

		add_action('sbi_header_notices', array($this, 'header_notices'));
		add_action('wp_ajax_sbi_dismiss_upgrade_notice', array($this, 'dismiss_upgrade_notice'));
		add_action('admin_init', array($this, 'sbi_admin_notices'));
		add_action('sb_notice_custom_feed_templates_dismissed', array($this, 'sbi_dismiss_notice'));
		add_action('admin_notices', array($this, 'clicksocial_upsell_notice'));
		add_action('wp_ajax_sbi_dismiss_clicksocial_upsell', array($this, 'sbi_dismiss_clicksocial_upsell'));
	}

	/**
	 * Header Notices
	 *
	 * @since 6.0
	 */
	public function header_notices()
	{
		$lite_notice_dismissed = get_transient('instagram_feed_dismiss_lite');
		if ($lite_notice_dismissed) {
			return;
		}

		$output = '';

		$upgrade_url = 'https://smashballoon.com/instagram-feed/demo/?utm_campaign=instagram-free&utm_source=lite-upgrade-bar';
		$output .= '<div id="sbi-notice-bar" class="sbi-header-notice">';
		$output .= sprintf(
			'<span class="sbi-notice-bar-message">%s <a href="%s" target="_blank" rel="noopener">%s</a></span>',
			__('You\'re using Instagram Feed Lite. To unlock more features consider', 'instagram-feed'),
			$upgrade_url,
			__('upgrading to Pro', 'instagram-feed')
		);

		$output .= sprintf(
			'<button type="button" class="sbi-dismiss" id="sbi-dismiss-header-notice" title="%s" data-page="overview">%s</button>',
			__('Dismiss this message', 'instagram-feed'),
			'<svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M15.8327 5.34175L14.6577 4.16675L9.99935 8.82508L5.34102 4.16675L4.16602 5.34175L8.82435 10.0001L4.16602 14.6584L5.34102 15.8334L9.99935 11.1751L14.6577 15.8334L15.8327 14.6584L11.1744 10.0001L15.8327 5.34175Z" fill="white"/></svg>'
		);

		$output .= '</div>';

		echo $output;
	}

	/**
	 * Dismiss Upgrade Notice
	 *
	 * @return SBI_Response
	 * @since 6.0
	 */
	public function dismiss_upgrade_notice()
	{
		// Run a security check.
		check_ajax_referer('sbi_nonce', 'sbi_nonce');

		$cap = current_user_can('manage_instagram_feed_options') ? 'manage_instagram_feed_options' : 'manage_options';
		$cap = apply_filters('sbi_settings_pages_capability', $cap);
		if (!current_user_can($cap)) {
			wp_send_json_error(); // This auto-dies.
		}
		// set the transient so it will hide for next 7 days
		set_transient('instagram_feed_dismiss_lite', 'dismiss', 1 * WEEK_IN_SECONDS);

		$response = new SBI_Response(true, array());
		$response->send();
	}

	/**
	 * Remove admin notices from inside our plugin screens so we can show our customized notices
	 *
	 * @since 4.0
	 */
	public function remove_admin_notices()
	{
		$current_screen = get_current_screen();
		$not_allowed_screens = array(
			'instagram-feed_page_sbi-feed-builder',
			'instagram-feed_page_sbi-settings',
			'instagram-feed_page_sbi-oembeds-manager',
			'instagram-feed_page_sbi-extensions-manager',
			'instagram-feed_page_sbi-about-us',
			'instagram-feed_page_sbi-support',
		);

		if (in_array($current_screen->base, $not_allowed_screens) || strpos($current_screen->base, 'sbi-') !== false) {
			remove_all_actions('admin_notices');
			remove_all_actions('all_admin_notices');
		}
	}

	/**
	 * CFF Get Renew License URL
	 *
	 * @return string $url
	 * @since 4.0
	 */
	public function get_renew_url()
	{
		global $sbi_download_id;

		$license_key = get_option('sbi_license_key') ? get_option('sbi_license_key') : null;

		$url = sprintf(
			'https://smashballoon.com/checkout/?edd_license_key=%s&download_id=%s&utm_campaign=instagram-free&utm_source=expired-notice&utm_medium=renew-license',
			esc_attr($license_key),
			$sbi_download_id
		);

		return $url;
	}

	/**
	 * CFF Check License
	 *
	 * @return SBI_Response
	 * @since 4.0
	 */
	public function sbi_check_license()
	{
		// Run a security check.
		check_ajax_referer('sbi_nonce', 'sbi_nonce');

		$cap = current_user_can('manage_instagram_feed_options') ? 'manage_instagram_feed_options' : 'manage_options';
		$cap = apply_filters('sbi_settings_pages_capability', $cap);
		if (!current_user_can($cap)) {
			wp_send_json_error(); // This auto-dies.
		}

		$sbi_license = trim(get_option('sbi_license_key'));

		// Check the API
		$sbi_api_params = array(
			'edd_action' => 'check_license',
			'nocache' => '1',
			'license' => $sbi_license,
			'item_name' => urlencode(SBI_PLUGIN_NAME) // the name of our product in EDD
		);
		$sbi_response = wp_safe_remote_get(add_query_arg($sbi_api_params, SBI_STORE_URL), array('timeout' => 60));
		$sbi_license_data = (array)json_decode(wp_remote_retrieve_body($sbi_response));
		// Update the updated license data
		update_option('sbi_license_data', $sbi_license_data);

		$sbi_todays_date = date('Y-m-d');
		// Check whether it's active
		if ($sbi_license_data['license'] !== 'expired' && (strtotime($sbi_license_data['expires']) > strtotime($sbi_todays_date))) {
			// if the license is active then lets remove the ignore check for dashboard so next time it will show the expired notice in dashboard screen
			update_user_meta(get_current_user_id(), 'sbi_ignore_dashboard_license_notice', false);

			$response = new SBI_Response(true, array(
				'msg' => 'License Active',
				'content' => $this->get_renewed_license_notice_content()
			));
			$response->send();
		} else {
			$content = 'Your Instagram Feed Pro license key has expired';
			$response = new SBI_Response(false, array(
				'msg' => 'License Not Renewed',
				'content' => $content
			));
			$response->send();
		}
	}

	/**
	 * Get content for successfully renewed license notice
	 *
	 * @return string $output
	 * @since 4.0
	 */
	public function get_renewed_license_notice_content()
	{
		$output = '<span class="sb-notice-icon sb-error-icon">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M12 2C6.5 2 2 6.5 2 12C2 17.5 6.5 22 12 22C17.5 22 22 17.5 22 12C22 6.5 17.5 2 12 2ZM10 17L5 12L6.41 10.59L10 14.17L17.59 6.58L19 8L10 17Z" fill="#59AB46"/>
                </svg>
            </span>
            <div class="sb-notice-body">
                <h3 class="sb-notice-title">Thanks! Your license key is valid.</h3>
                <p>You can safely dismiss this modal.</p>
                <div class="license-action-btns">
                    <a target="_blank" class="sbi-license-btn sbi-btn-blue sbi-notice-btn" id="sbi-hide-notice">
                        <svg width="10" height="10" viewBox="0 0 10 10" fill="none" xmlns="http://www.w3.org/2000/svg">
						<path d="M9.66659 1.27325L8.72659 0.333252L4.99992 4.05992L1.27325 0.333252L0.333252 1.27325L4.05992 4.99992L0.333252 8.72659L1.27325 9.66659L4.99992 5.93992L8.72659 9.66659L9.66659 8.72659L5.93992 4.99992L9.66659 1.27325Z" fill="white"/>
						</svg>
                        Dismiss
                    </a>
                </div>
            </div>';

		return $output;
	}

	/**
	 * Get modal content that will trigger by "Why Renew" button
	 *
	 * @return string $output
	 * @since 4.0
	 */
	public function get_modal_content()
	{
		$output = '<div class="sbi-sb-modal license-details-modal">
            <div class="sbi-modal-content">
                <button type="button" class="cancel-btn sbi-btn" id="sbi-sb-close-modal">
                    <svg width="15" height="15" viewBox="0 0 15 15" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M14.2084 2.14275L12.8572 0.791504L7.50008 6.14859L2.143 0.791504L0.791748 2.14275L6.14883 7.49984L0.791748 12.8569L2.143 14.2082L7.50008 8.85109L12.8572 14.2082L14.2084 12.8569L8.85133 7.49984L14.2084 2.14275Z" fill="#141B38">
                        </path>
                    </svg>
                </button>
                <div class="sbi-sb-modal-body">
                    <h2 class="sb-modal-title">Why Renew?</h2>
                    <p class="sb-modal-description">See below for why it\'s so important to keep an active plugin license.</p>
                    <div class="sb-why-renew-list-parent">
                        <div class="sb-why-renew-list">
                            <div class="sb-icon">
                                <svg width="32" height="32" viewBox="0 0 32 32" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M16 1.33325L4 6.66659V14.6666C4 22.0666 9.12 28.9866 16 30.6666C22.88 28.9866 28 22.0666 28 14.6666V6.66659L16 1.33325Z" fill="#59AB46"/>
                                    <path d="M10.3433 16.5525L14.1145 20.3237L21.657 12.7813" stroke="white" stroke-width="2.66667"/>
                                </svg>
                            </div>
                            <div class="sb-list-item">
                                <h4>Protected Against All Upcoming Instagram Platform Updates and API Changes</h4>
                                <p>Don\'t worry about your Instagram feeds breaking due to constant changes in the Instagram platform. Stay protected with access to continual plugin updates, giving you peace of mind that the software will always be up to date.</p>
                            </div>
                        </div>
                        <div class="sb-why-renew-list">
                            <div class="sb-icon">
                                <svg width="32" height="32" viewBox="0 0 32 32" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M15.9998 2.66675C8.63984 2.66675 2.6665 8.64008 2.6665 16.0001C2.6665 23.3601 8.63984 29.3334 15.9998 29.3334C23.3598 29.3334 29.3332 23.3601 29.3332 16.0001C29.3332 8.64008 23.3598 2.66675 15.9998 2.66675ZM25.9465 12.1601L22.2398 13.6934C21.9059 12.7949 21.3814 11.9793 20.7025 11.3027C20.0235 10.626 19.2061 10.1043 18.3065 9.77341L19.8398 6.06675C22.6398 7.13341 24.8665 9.36008 25.9465 12.1601ZM15.9998 20.0001C13.7865 20.0001 11.9998 18.2134 11.9998 16.0001C11.9998 13.7867 13.7865 12.0001 15.9998 12.0001C18.2132 12.0001 19.9998 13.7867 19.9998 16.0001C19.9998 18.2134 18.2132 20.0001 15.9998 20.0001ZM12.1732 6.05341L13.7332 9.76008C12.8229 10.0918 11.9959 10.6179 11.3097 11.3018C10.6235 11.9857 10.0946 12.811 9.75984 13.7201L6.05317 12.1734C6.58782 10.7816 7.40887 9.51764 8.46313 8.46338C9.5174 7.40911 10.7814 6.58806 12.1732 6.05341ZM6.05317 19.8267L9.75984 18.2934C10.0923 19.2002 10.619 20.0233 11.303 20.705C11.9871 21.3868 12.812 21.9107 13.7198 22.2401L12.1598 25.9467C10.771 25.4097 9.51009 24.5876 8.45831 23.5335C7.40653 22.4795 6.58722 21.2167 6.05317 19.8267ZM19.8398 25.9467L18.3065 22.2401C19.2103 21.9052 20.0304 21.3775 20.7097 20.6936C21.3889 20.0098 21.9111 19.1862 22.2398 18.2801L25.9465 19.8401C25.4101 21.2272 24.5898 22.4869 23.5382 23.5385C22.4866 24.59 21.2269 25.4103 19.8398 25.9467Z" fill="#59AB46"/>
                                </svg>
                            </div>
                            <div class="sb-list-item">
                                <h4>Expert Technical Support</h4>
                                <p>Without a valid license key you will no longer be able to receive updates or support for the Instagram Feed plugin. A renewed license key grants you access to our top-notch, quick and effective support for another full year.</p>
                            </div>
                        </div>
                        <div class="sb-why-renew-list">
                            <div class="sb-icon">
                            <svg width="32" height="32" viewBox="0 0 32 32" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd" clip-rule="evenodd" d="M16 0C7.16343 0 0 7.16342 0 16C0 24.8365 7.16343 32 16 32C24.8366 32 32 24.8365 32 16C32 7.16342 24.8366 0 16 0ZM16 0.96C18.0308 0.96 20.0004 1.35753 21.8539 2.14152C22.7449 2.51837 23.6044 2.98488 24.4084 3.528C25.205 4.06617 25.9541 4.68427 26.6349 5.3651C27.3157 6.04593 27.9338 6.79507 28.472 7.59163C29.0152 8.39563 29.4816 9.25507 29.8585 10.146C30.6425 11.9996 31.04 13.9692 31.04 16C31.04 18.0308 30.6425 20.0003 29.8585 21.8539C29.4816 22.7449 29.0152 23.6043 28.472 24.4083C27.9338 25.2049 27.3157 25.954 26.6349 26.6349C25.9541 27.3157 25.205 27.9338 24.4084 28.472C23.6044 29.0151 22.7449 29.4816 21.8539 29.8584C20.0004 30.6425 18.0308 31.04 16 31.04C13.9692 31.04 11.9996 30.6425 10.1461 29.8584C9.25508 29.4816 8.39564 29.0151 7.59164 28.472C6.79508 27.9338 6.04594 27.3157 5.36511 26.6349C4.68428 25.954 4.06618 25.2049 3.528 24.4083C2.98488 23.6043 2.51837 22.7449 2.14152 21.8539C1.35754 20.0003 0.960001 18.0308 0.960001 16C0.960001 13.9692 1.35754 11.9996 2.14152 10.146C2.51837 9.25507 2.98488 8.39563 3.528 7.59163C4.06618 6.79507 4.68428 6.04593 5.36511 5.3651C6.04594 4.68427 6.79508 4.06617 7.59164 3.528C8.39564 2.98488 9.25508 2.51837 10.1461 2.14152C11.9996 1.35753 13.9692 0.96 16 0.96Z" fill="#0068A0"/>
                                <path fill-rule="evenodd" clip-rule="evenodd" d="M27.7008 9.60322C27.7581 10.0278 27.7904 10.4834 27.7904 10.9742C27.7904 12.3266 27.537 13.8476 26.7762 15.7497L22.7039 27.5239C26.6679 25.2129 29.3337 20.9184 29.3337 15.9996C29.3337 13.6814 28.7413 11.5022 27.7008 9.60322ZM16.2346 17.1658L12.2335 28.7901C13.4284 29.1417 14.6917 29.3334 16.0004 29.3334C17.553 29.3334 19.0425 29.0654 20.4283 28.5774C20.3926 28.5204 20.3598 28.4598 20.3326 28.3937L16.2346 17.1658ZM25.0015 15.3271C25.0015 13.6788 24.4094 12.5379 23.9023 11.6501C23.2264 10.5513 22.5925 9.62166 22.5925 8.52289C22.5925 7.29745 23.5219 6.15659 24.8316 6.15659C24.8908 6.15659 24.9468 6.16369 25.0042 6.16734C22.632 3.9938 19.4715 2.66675 16.0004 2.66675C11.342 2.66675 7.24413 5.05691 4.85997 8.6762C5.17303 8.68614 5.46799 8.69238 5.71807 8.69238C7.11237 8.69238 9.27175 8.52289 9.27175 8.52289C9.99012 8.48079 10.075 9.53674 9.357 9.62166C9.357 9.62166 8.63445 9.70628 7.83113 9.74833L12.6863 24.1908L15.6046 15.4399L13.5274 9.74833C12.809 9.70628 12.129 9.62166 12.129 9.62166C11.4102 9.57922 11.4944 8.48079 12.2135 8.52289C12.2135 8.52289 14.415 8.69238 15.725 8.69238C17.1193 8.69238 19.279 8.52289 19.279 8.52289C19.9978 8.48079 20.0824 9.53674 19.364 9.62166C19.364 9.62166 18.6407 9.70628 17.8381 9.74833L22.6566 24.0807L24.0321 19.7223C24.6432 17.8175 25.0015 16.468 25.0015 15.3271ZM2.66699 15.9996C2.66699 21.2769 5.73372 25.838 10.1819 27.999L3.82154 10.5734C3.08171 12.2315 2.66699 14.0665 2.66699 15.9996Z" fill="#0068A0"/>
                            </svg>
                            </div>
                            <div class="sb-list-item">
                                <h4>WordPress Compatibility Updates</h4>
                                <p>With WordPress updates being released continually, we make sure the plugin is always compatible with the latest version so you can update WordPress without needing to worry.</p>
                            </div>
                        </div>
                        <div class="sb-why-renew-list">
                            <div class="sb-icon">
                            <svg width="32" height="32" viewBox="0 0 32 32" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd" clip-rule="evenodd" d="M15.1583 8.40195C12.8183 7.39434 10.3809 5.88954 8.10558 5.18909C8.91398 7.03648 9.59628 9.00467 10.3023 10.9501C8.89406 11.9642 7.2491 12.7514 5.67754 13.6091C7.0149 14.8758 8.9089 15.609 10.418 16.7112C9.20919 18.1404 6.83433 19.6258 6.25565 20.9211C8.758 20.6207 11.6739 20.1336 14.0021 20.0348C14.5137 22.4989 14.7776 25.2005 15.5052 27.4577C16.5887 24.4706 17.5684 21.384 18.8581 18.5945C20.8485 19.3834 23.2742 20.3453 25.2172 20.8103C23.8539 18.9776 22.6098 17.0307 21.4018 15.0493C23.1895 13.8079 24.9976 12.5862 26.7202 11.2824C24.2854 11.0675 21.7627 10.9367 19.205 10.8394C18.7985 8.31133 18.9053 5.29159 18.28 2.97334C17.3339 4.87343 16.2174 6.61017 15.1583 8.40195ZM16.3145 29.3411C15.993 30.6598 17.0524 31.2007 16.8926 32C15.8465 31.6546 15.0596 31.4771 13.6553 31.6676C13.6992 30.6387 14.6649 30.4932 14.4646 29.2303C-0.500692 27.5999 -0.530751 1.68764 14.349 0.0928438C32.9539 -1.90125 33.5377 28.8829 16.3145 29.3411Z" fill="#FE544F"/>
                                <path fill-rule="evenodd" clip-rule="evenodd" d="M18.2802 2.97314C18.9055 5.2914 18.7987 8.31114 19.2052 10.8391C21.7629 10.9365 24.2856 11.0672 26.7204 11.2823C24.9978 12.586 23.1896 13.8077 21.4019 15.0491C22.61 17.0305 23.8541 18.9774 25.2174 20.8101C23.2744 20.3451 20.8487 19.3832 18.8583 18.5943C17.5686 21.3838 16.5889 24.4704 15.5054 27.4575C14.7778 25.2003 14.5139 22.4987 14.0023 20.0346C11.6741 20.1334 8.7582 20.6205 6.25584 20.9209C6.83452 19.6256 9.20937 18.1402 10.4181 16.7109C8.90907 15.6088 7.01509 14.8756 5.67773 13.6089C7.24929 12.7512 8.89422 11.964 10.3025 10.9499C9.59646 9.00448 8.91419 7.03628 8.10578 5.18889C10.381 5.88935 12.8185 7.39414 15.1585 8.40176C16.2176 6.60997 17.3341 4.87324 18.2802 2.97314Z" fill="white"/>
                            </svg>
                            </div>
                            <div class="sb-list-item">
                                <h4>All Pro Instagram Feed Features</h4>
                                <p>Photos & Albums, Videos (HD, 360, Live), Instagram Events, Popup Lightbox, Likes, Shares, & Reactions, Comments and Replies, Filter Posts, Post Layouts, Load More Posts, Multi-column Grid Layout, Background Caching, and more!</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>';

		return $output;
	}

	/**
	 * Display admin notices in the plugin's pages
	 *
	 * @since 6.3
	 */
	public function sbi_admin_notices()
	{
		$allowed_screens = array(
			'sbi-feed-builder',
			'sbi-settings',
			'sbi-oembeds-manager',
			'sbi-extensions-manager',
			'sbi-about-us',
			'sbi-support',
		);
		$current_screen = isset($_GET['page']) ? sanitize_text_field(wp_unslash($_GET['page'])) : '';
		$is_allowed = in_array($current_screen, $allowed_screens);

		// We will display the notice only on those allowed screens.
		if (!$current_screen || !$is_allowed) {
			return;
		}

		// Only display notice to admins.
		if (!sbi_current_user_can('manage_instagram_feed_options')) {
			return;
		}

		$this->sbi_custom_feed_templates_notice();
		$this->sbi_personal_api_deprecation_notice();
	}

	/**
	 * Custom Feed Templates Notice
	 *
	 * @since 6.3
	 */
	public function sbi_custom_feed_templates_notice()
	{
		$has_custom_templates = Util::sbi_has_custom_templates();
		$sbi_statuses = get_option('sbi_statuses', array());

		if (!$has_custom_templates) {
			$sbi_statuses['custom_templates_notice'] = true;
			update_option('sbi_statuses', $sbi_statuses);
			return;
		}

		if (true == get_option('sbi_custom_templates_notice_dismissed') || isset($sbi_statuses['custom_templates_notice'])) {
			return;
		}

		global $sbi_notices;
		$title = __('Heads Up! Feed Item Files and CSS Have Changed', 'instagram-feed');
		$message = '<p>' . __('Version 6.3 includes changes to the HTML and CSS files that make up your feeds. If you have customized your feed through custom theme templates, custom CSS, or custom JavaScript, your customizations may have been affected.', 'instagram-feed') . '</p>';
		$message .= '<p>' . __('You can use the CSS file from previous versions if needed. Enable the related setting on the Advanced tab of the settings page.', 'instagram-feed') . '</p>';

		$error_args = array(
			'class' => 'sbi-admin-notices sbi-admin-notices-spaced-p',
			'title' => array(
				'text' => $title,
				'class' => 'sb-notice-title',
				'tag' => 'h4',
			),
			'message' => $message,
			'dismissible' => true,
			'dismiss' => array(
				'class' => 'sbi-notice-dismiss',
				'icon' => SBI_PLUGIN_URL . 'admin/assets/img/sbi-dismiss-icon.svg',
				'tag' => 'a',
				'href' => array(
					'args' => array(
						'sb-dismiss-notice' => 'custom_feed_templates'
					),
					'action' => 'sb_dismiss_notice_nonce',
					'nonce' => '_sb_notice_nonce',
				)
			),
			'buttons' => array(
				array(
					'text' => __('Sounds good!', 'instagram-feed'),
					'class' => 'button button-primary',
					'id' => 'custom_feed_templates_dismiss',
					'url' => array(
						'args' => array(
							'sb-dismiss-notice' => 'custom_feed_templates'
						),
						'action' => 'sb_dismiss_notice_nonce',
						'nonce' => '_sb_notice_nonce',
					),
					'tag' => 'a',
				),
				array(
					'text' => __('Learn More', 'instagram-feed'),
					'class' => 'button button-secondary',
					'id' => 'custom_feed_templates_learn',
					'url' => 'https://smashballoon.com/doc/instagram-css-layout-changes/?utm_source=instagram-pro&utm_medium=dashboard-notice&utm_campaign=63changes&utm_content=LearnMore',
					'target' => 'blank',
					'tag' => 'a',
				),
			),
			'buttons_wrap_start' => '<p class="sbi-error-directions">',
			'buttons_wrap_end' => '</p>',
			'priority' => 1,
			'page' => array(
				'sbi-feed-builder',
				'sbi-settings',
				'sbi-oembeds-manager',
				'sbi-extensions-manager',
				'sbi-about-us',
				'sbi-support',
			),
			'icon' => array(
				'src' => SBI_PLUGIN_URL . 'admin/assets/img/balloon.svg',
				'wrap' => '<span class="sb-notice-icon sb-error-icon"><img {src}></span>',
			),
			'wrap_schema' => '<div {id} {class}>{icon}<div class="sbi-notice-body">{title}{message}</div>{dismiss}{buttons}</div>',
		);

		$sbi_notices->add_notice('custom_feed_templates', 'information', $error_args);

		$sbi_statuses['custom_templates_notice'] = true;
		update_option('sbi_statuses', $sbi_statuses);
	}

	/**
	 * Display notice for personal API deprecation
	 *
	 * @since 6.3
	 */
	public function sbi_personal_api_deprecation_notice()
	{
		global $sbi_notices;
		$personal_accounts = SBI_Db::source_query(array('type' => 'basic'));
		if (empty($personal_accounts)) {
			$personal_api_notice = $sbi_notices->get_notice('personal_api_deprecation');
			if (!empty($personal_api_notice)) {
				$sbi_notices->remove_notice('personal_api_deprecation');
			}
			return;
		}

		$title = __('Deprecation notice', 'instagram-feed');
		$message = '<p>' . __('Due to changes by Instagram, all “Personal” accounts will stop working as of December 2024. To continue to use the plugin, reconnect all personal accounts as a “Business” account.', 'instagram-feed') . '</p>';

		$notice_args = array(
			'class' => 'sbi-admin-notices',
			'title' => array(
				'text' => $title,
				'class' => 'sb-notice-title',
				'tag' => 'h4',
			),
			'message' => $message,
			'buttons' => array(
				array(
					'text' => __('Update Sources', 'instagram-feed'),
					'url' => admin_url('admin.php?page=sbi-settings'),
					'class' => 'sbi-notice-btn',
					'tag' => 'a',
					'condition' => array(
						'key' => 'screen',
						'compare' => '!==',
						'value' => 'sbi-settings',
					),
				),
				array(
					'text' => __('Learn More', 'instagram-feed'),
					'class' => 'sbi-notice-btn',
					'url' => 'https://smashballoon.com/doc/instagram-business-basic/?instagram&utm_campaign=instagram-free&utm_source=notice&utm_medium=basicdisplaydeprecation&utm_content=learnmore',
					'target' => 'blank',
					'tag' => 'a',
				),
			),
			'buttons_wrap_start' => '<div class="sb-notice-buttons">',
			'buttons_wrap_end' => '</div>',
			'priority' => 1,
			'page' => array(
				'sbi-feed-builder',
				'sbi-settings',
				'sbi-oembeds-manager',
				'sbi-extensions-manager',
				'sbi-about-us',
				'sbi-support',
			),
			'icon' => array(
				'src' => SBI_PLUGIN_URL . 'admin/assets/img/sbi-exclamation.svg',
				'wrap' => '<span class="sb-notice-icon sb-error-icon"><img {src}></span>',
			),
			'styles' => array(
				'display' => 'flex',
				'justify-content' => 'space-between',
				'gap' => '2rem',
			),
			'wrap_schema' => '<div {id} {class}>{icon}<div class="sbi-notice-wrap" {styles}><div class="sbi-notice-body">{title}{message}</div>{buttons}</div></div>',
		);

		$sbi_notices->add_notice('personal_api_deprecation', 'information', $notice_args);
	}

	/**
	 * Dismiss custom feeds template admin notices
	 *
	 * @since 6.3
	 */
	public function sbi_dismiss_notice($notice_id)
	{
		if ('custom_feed_templates' === $notice_id) {
			update_option('sbi_custom_templates_notice_dismissed', true);
		}
	}

	/**
	 * Display upsell notice
	 *
	 * @since 6.7.0
	 */
	public function clicksocial_upsell_notice()
	{
		if (! current_user_can('manage_options') || ! current_user_can('manage_instagram_feed_options')) {
			return;
		}

		$screen = get_current_screen();
		if (!in_array($screen->id, array('edit-page', 'edit-post'), true)) {
			return;
		}

		if (is_plugin_active('click-social/click-social.php') || true == get_option('sbi_clicksocial_upsell_dismissed')) {
			return;
		}

		$sb_plugins_info = Util::get_sb_active_plugins_info();
		$clicksocial_installed = $sb_plugins_info['is_clicksocial_installed'];

		$plugin_data = array(
			'step' => $clicksocial_installed ? 'activate' : 'install',
			'action' => $clicksocial_installed ? 'sbi_activate_addon' : 'sbi_install_addon',
			'nonce' => wp_create_nonce('sbi-admin'),
			'plugin' => 'click-social/click-social.php',
			'download_plugin' => 'https://downloads.wordpress.org/plugin/click-social.zip',
			'redirect' => admin_url('admin.php?page=click-social'),
		);

		?>
		<div class="notice notice-info is-dismissible" id="sbi-clicksocial-notice">
			<p>
				<strong><?php esc_html_e('Schedule social media posts to promote your blog with ClickSocial', 'instagram-feed'); ?></strong><br>
				<?php esc_html_e('ClickSocial allows you to auto-schedule posts on Instagram, Facebook, Twitter and more with just a click.', 'instagram-feed'); ?>
			</p>
			<p class="sbi-notice-btns">
				<button class="button button-primary sbi-install-plugin-btn" id='sbi_install_op_btn'
						data-plugin-atts="<?php echo esc_attr(sbi_json_encode($plugin_data)); ?>">
					<?php echo esc_html__($clicksocial_installed ? 'Activate ClickSocial' : 'Install ClickSocial', 'instagram-feed'); ?>
				</button>
				<a href="https://clicksocial.com/?utm_campaign=instagram-free&utm_source=all-posts&utm_medium=revival-campaign&utm_content=tryfree"
				   target="_blank" class="button button-secondary">
					<?php esc_html_e('Learn More', 'instagram-feed'); ?>
				</a>
			</p>
		</div>
		<?php
	}

	/**
	 * Dismiss ClickSocial upsell notice
	 *
	 * @since 6.7.0
	 */
	public function sbi_dismiss_clicksocial_upsell()
	{
		check_ajax_referer('sbi_nonce', 'sbi_nonce');

		if (!current_user_can('manage_options') || !current_user_can('manage_instagram_feed_options')) {
			wp_send_json_error();
		}

		update_option('sbi_clicksocial_upsell_dismissed', true);
		wp_send_json_success();
	}
}
