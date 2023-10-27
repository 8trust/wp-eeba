<?php

if ( !function_exists( 'inavii_social_feed_e_fs' ) ) {
    // Create a helper function for easy SDK access.
    function inavii_social_feed_e_fs()
    {
        global  $inavii_social_feed_e_fs ;
        
        if ( !isset( $inavii_social_feed_e_fs ) ) {
            // Include Freemius SDK.
            require_once dirname( __FILE__ ) . '/vendor/freemius/wordpress-sdk/start.php';
            $inavii_social_feed_e_fs = fs_dynamic_init( array(
                'id'             => '11571',
                'slug'           => 'inavii-social-feed-for-elementor',
                'premium_slug'   => 'inavii-social-feed-for-elementor-pro',
                'type'           => 'plugin',
                'public_key'     => 'pk_bc943060ac350be6fc2fd329db473',
                'is_premium'     => false,
                'premium_suffix' => '(PRO)',
                'trial'          => array(
                'days'               => 14,
                'is_require_payment' => true,
            ),
                'has_addons'     => false,
                'has_paid_plans' => true,
                'menu'           => array(
                'slug'    => 'inavii-instagram-settings',
                'support' => false,
            ),
                'is_live'        => true,
            ) );
        }
        
        return $inavii_social_feed_e_fs;
    }
    
    function show_trial()
    {
        return 0;
    }
    
    inavii_social_feed_e_fs()->add_filter( 'show_first_trial_after_n_sec', 'show_trial' );
    // Init Freemius.
    inavii_social_feed_e_fs();
    // Signal that SDK was initiated.
    do_action( 'inavii_social_feed_e_fs_loaded' );
}
