<?php

/**
 * Plugin Name: Inavii for Elementor Social Feed
 * Description: Add Instagram to your website in less than a minute with our dedicated plugin for Elementor. Just 4 simple steps will allow you to display your Instagram profile on your site, captivating visitors with beautiful photos and layouts.
 * Plugin URI:  https://www.inavii.com/
 * Version:     2.2.1
 * Author:      INAVII
 * Author URI:  https://www.inavii.com/
 * Text Domain: inavii-social-feed-e
 * Elementor tested up to: 3.13.2
 * Domain Path: /languages
  */

namespace Inavii\Instagram;

if (!defined('ABSPATH')) {
    exit;
}

if (!class_exists('Inavii\Instagram\INAVII_SOCIAL_FEED_E')) {
    define('INVII_INSTAGRAM_URL', trailingslashit(plugin_dir_url(__FILE__)));
    define('INVII_INSTAGRAM_DIR', trailingslashit(plugin_dir_path(__FILE__)));

    final class INAVII_SOCIAL_FEED_E
    {

        private static $_instance;

        /**
         * Plugin Version
         *
         * @var string The plugin version.
         */
        public const VERSION = '2.2.1';

        /**
         * @since 1.0.0
         * @var string
         */
        public const TEXT_DOMAIN = 'inavii-social-feed-e';

        /**
         * Minimum Elementor Version
         *
         * @since 1.0.0
         *
         * @var string Minimum Elementor version required to run the plugin.
         */
        public const MINIMUM_ELEMENTOR_VERSION = '3.1.0';

        /**
         * Minimum PHP Version
         *
         * @since 1.0.0
         *
         * @var string Minimum PHP version required to run the plugin.
         */
        public const MINIMUM_PHP_VERSION = '7.2';

        public function __construct()
        {
            add_action('plugins_loaded', array($this, 'init'));
            add_action('admin_init', array($this, 'inavii_social_feed_plugin_activate_redirect'));
            add_action('init', array($this, 'load_textdomain'));

            register_activation_hook(__FILE__, array($this, 'register_actions'));
            register_deactivation_hook(__FILE__, array($this, 'deactivate_actions'));
        }

        public function load_textdomain(): void
        {
            load_plugin_textdomain('inavii-social-feed-e', false, dirname(plugin_basename(__FILE__)) . '/languages');
        }

        public function init(): void
        {
            require_once INVII_INSTAGRAM_DIR . '/vendor/autoload.php';

            if (!function_exists('inavii_social_feed_e_fs')) {
                require_once __DIR__ . '/freemius.php';
            }

            require_once INVII_INSTAGRAM_DIR . '/app.php';

            add_filter('plugin_action_links_' . plugin_basename(__FILE__), [$this, 'addActionLink']);
        }

        public function addActionLink($links)
        {
            $links[] = '<a href="' . esc_url(get_admin_url(null, 'admin.php?page=inavii-instagram-settings')) . '">Settings</a>';
            return $links;
        }

        public function register_actions(): void
        {

            update_option('inavii_social_feed_e_version', self::VERSION);

            if (!wp_next_scheduled('inavii_social_feed_update_media')) {
                wp_schedule_event(time(), 'hourly', 'inavii_social_feed_update_media');
            }

            if (!wp_next_scheduled('inavii_social_feed_refresh_token')) {
                wp_schedule_event(time(), 'weekly', 'inavii_social_feed_refresh_token');
            }

            if ($this->redirect_on_activation()) {
                add_option('inavii_social_feed_plugin_do_activation_redirect', sanitize_text_field(__FILE__));
            }
        }

        public function redirect_on_activation(): bool
        {
            if (is_network_admin()) {
                return false;
            }

            if (!current_user_can('manage_options')) {
                return false;
            }

            if (defined('WP_DEBUG') && WP_DEBUG) {
                return false;
            }

            $maybe_multi = filter_input(INPUT_GET, 'activate-multi', FILTER_VALIDATE_BOOLEAN);

            if ($maybe_multi) {
                return false;
            }

            return true;
        }

        public function inavii_social_feed_plugin_activate_redirect(): void
        {
            if (!$this->redirect_on_activation() && !is_admin()) {
                return;
            }

            if (__FILE__ === get_option('inavii_social_feed_plugin_do_activation_redirect')) {

                delete_option('inavii_social_feed_plugin_do_activation_redirect');

                wp_safe_redirect(esc_url(admin_url('admin.php?page=inavii-instagram-settings')));
                exit;
            }
        }

        public function deactivate_actions(): void
        {
            if (!current_user_can('activate_plugins')) {
                return;
            }
            wp_clear_scheduled_hook('inavii_social_feed_update_media');
            wp_clear_scheduled_hook('inavii_social_feed_refresh_token');
            wp_clear_scheduled_hook('wp_importer-instagram-media_cron_interval');
            wp_clear_scheduled_hook('wp_generate-thumbnails-media_cron_interval');
        }

        public static function instance(): INAVII_SOCIAL_FEED_E
        {
            if (null === self::$_instance) {
                self::$_instance = new self();
            }

            return self::$_instance;
        }
    }

    INAVII_SOCIAL_FEED_E::instance();
}