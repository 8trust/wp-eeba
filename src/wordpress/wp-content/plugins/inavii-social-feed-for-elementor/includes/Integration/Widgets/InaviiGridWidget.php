<?php

namespace Inavii\Instagram\Includes\Integration\Widgets;

use  Inavii\Instagram\INAVII_SOCIAL_FEED_E ;
use  Inavii\Instagram\Includes\Integration\WidgetSettings ;
use  Inavii\Instagram\PostTypes\Account\AccountPostType ;
use  Inavii\Instagram\PostTypes\Feed\FeedPostType ;
use  Inavii\Instagram\Utils\VersionChecker ;
use  Timber\Timber ;
class InaviiGridWidget extends WidgetsBase
{
    public function get_name() : string
    {
        return 'inavii-grid';
    }
    
    public function get_title() : string
    {
        return esc_html__( 'Inavii Social Feed', INAVII_SOCIAL_FEED_E::TEXT_DOMAIN );
    }
    
    public function get_icon() : string
    {
        return 'inavii-icon-instagram';
    }
    
    /**
     * Render text editor widget output on the frontend.
     *
     * Written in PHP and used to generate the final HTML.r
     *
     * @since  1.0.0
     * @access protected
     */
    protected function render() : void
    {
        $this->settings = $this->get_settings_for_display();
        $widgetSettings = new WidgetSettings( $this->settings );
        $posts = ( new FeedPostType() )->get( $widgetSettings->feedId(), true, $widgetSettings->postsCount() );
        $account = ( new AccountPostType() )->getAccountRelatedWithFeed( $widgetSettings->feedId() );
        
        if ( $widgetSettings->feedId() === 0 ) {
            Timber::render( 'view/no-posts.twig', [
                'message' => '<span>Please select </span> a feed',
            ] );
            return;
        }
        
        
        if ( empty($posts) ) {
            Timber::render( 'view/no-posts.twig', [
                'message' => '<span>No posts</span> to display',
            ] );
            return;
        }
        
        $widgetData = [
            'class'                              => 'inavii-grid__item grid-item',
            'items'                              => $posts,
            'items_mobile'                       => $widgetSettings->postsCountMobile(),
            'img_animation_class'                => $widgetSettings->imgAnimation(),
            'enable_follow_button'               => $widgetSettings->enableFollowButton(),
            'follow_button_icon'                 => $this->icon( $widgetSettings->followButtonIcon() ),
            'follow_button_text'                 => $widgetSettings->followButtonText(),
            'enable_header_follow_button'        => $widgetSettings->enableHeaderFollowButton(),
            'header_follow_button_icon'          => $this->icon( $widgetSettings->headerFollowButtonIcon() ),
            'header_follow_button_text'          => $widgetSettings->headerFollowButtonText(),
            'follow_button_url'                  => $account->instagramProfileLink(),
            'enable_photo_linking'               => $widgetSettings->enablePhotolinking(),
            'target'                             => $widgetSettings->target(),
            'layoutView'                         => $widgetSettings->layoutView(),
            'imageSize'                          => $widgetSettings->imageSize(),
            'enable_avatar_lightbox'             => $widgetSettings->enableAvatarLightbox(),
            'enable_avatar_header_box'           => $widgetSettings->enableAvatarHeaderBox(),
            'avatar_url'                         => ( $account->avatarOverwritten() ?: $account->avatar() ),
            'username'                           => ( $account->name() ?: $account->userName() ),
            'username_lightbox_switch'           => $widgetSettings->enableUserNameLightbox(),
            'username_header_box'                => $widgetSettings->enableUserNameHeaderBox(),
            'widget_id'                          => $this->get_id(),
            'feed_type'                          => $widgetSettings->feedType(),
            'enable_popup_follow_button'         => $widgetSettings->enablePopupFollowButton(),
            'enable_popup_icon_follow_button'    => $widgetSettings->enablePopupIconFollowButton(),
            'follow_popup_button_icon'           => $this->icon( $widgetSettings->popupFollowButtonIcon() ),
            'follow_popup_button_text'           => $widgetSettings->popupFollowButtonText(),
            'enable_lightbox_follow_button'      => $widgetSettings->enableLightboxFollowButton(),
            'enable_lightbox_icon_follow_button' => $widgetSettings->enableLightboxIconFollowButton(),
            'follow_lightbox_button_icon'        => $this->icon( $widgetSettings->lightboxFollowButtonIcon() ),
            'follow_lightbox_button_text'        => $widgetSettings->lightboxFollowButtonText(),
            'is_pro'                             => VersionChecker::version()->can_use_premium_code(),
        ];
        Timber::render( 'view/index.twig', $widgetData );
    }

}