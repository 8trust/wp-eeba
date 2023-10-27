<?php

namespace Inavii\Instagram\Includes\Dependence;

use  Inavii\Instagram\INAVII_SOCIAL_FEED_E ;
use  Inavii\Instagram\Utils\VersionChecker ;
class RegisterAssets
{
    public function __construct()
    {
        add_action( 'elementor/frontend/before_enqueue_styles', array( $this, 'inaviiStyles' ) );
        add_action( 'elementor/frontend/before_enqueue_scripts', array( $this, 'enqueuePluginAssets' ) );
        add_action( 'elementor/editor/after_enqueue_styles', array( $this, 'inaviiStyles' ) );
        add_action( 'elementor/editor/after_enqueue_styles', array( $this, 'customIconsStyles' ) );
        add_action( 'elementor/preview/enqueue_styles', array( $this, 'customIconsStyles' ) );
        add_action( 'elementor/widgets/widgets_registered', array( $this, 'customIconsStyles' ) );
        add_action( 'admin_enqueue_scripts', array( $this, 'loadSettingsSssetsAdmin' ) );
    }
    
    public function enqueuePluginAssets() : void
    {
        wp_enqueue_script(
            'inavii-widget-handlers',
            INVII_INSTAGRAM_URL . $this->getScriptPath(),
            array( 'jquery', 'elementor-frontend' ),
            INAVII_SOCIAL_FEED_E::VERSION,
            true
        );
    }
    
    public function customIconsStyles() : void
    {
        wp_enqueue_style(
            'inavii-widgets-icons',
            INVII_INSTAGRAM_URL . 'assets/css/inavii-icons.css',
            array(),
            INAVII_SOCIAL_FEED_E::VERSION
        );
    }
    
    public function inaviiStyles() : void
    {
        wp_enqueue_style(
            'inavii-styles',
            INVII_INSTAGRAM_URL . $this->getStylePath(),
            array(),
            INAVII_SOCIAL_FEED_E::VERSION
        );
        if ( wp_script_is( 'swiper', 'registered' ) === false ) {
            wp_enqueue_style( 'slider-swiper-css', INVII_INSTAGRAM_URL . 'assets/vendors/swiper-bundle.min.css' );
        }
    }
    
    public function loadSettingsSssetsAdmin( $pageSlug ) : void
    {
        
        if ( $pageSlug === 'toplevel_page_inavii-instagram-settings' ) {
            wp_enqueue_style(
                'inavii-social-feed-app-style',
                INVII_INSTAGRAM_URL . 'assets/react/static/css/inavii-social-feed-app.min.css',
                array(),
                INAVII_SOCIAL_FEED_E::VERSION
            );
            wp_enqueue_media();
            wp_enqueue_script(
                'inavii-social-feed-app-script',
                INVII_INSTAGRAM_URL . 'assets/react/static/js/inavii-social-feed-app.min.js',
                array(),
                INAVII_SOCIAL_FEED_E::VERSION
            );
        }
        
        wp_enqueue_style( 'inavii_icons_styles', INVII_INSTAGRAM_URL . 'assets/css/inavii-icons.css' );
        wp_localize_script( 'inavii-social-feed-app-script', 'inaviiSocialFeedConfig', array(
            'url'          => get_rest_url(),
            'nonce'        => wp_create_nonce( 'wp_rest' ),
            'redirect_url' => admin_url(),
            'mediaUrl'     => INVII_INSTAGRAM_URL . 'assets/react',
        ) );
    }
    
    private function getStylePath() : string
    {
        return 'assets/dist/css/inavii-styles.min.css';
    }
    
    private function getScriptPath() : string
    {
        return 'assets/dist/js/inavii-js.min.js';
    }

}