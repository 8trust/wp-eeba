<?php

namespace Inavii\Instagram\Includes\Integration;

use  Inavii\Instagram\Includes\Integration\Widgets\WidgetsBase ;
use  Inavii\Instagram\Media\Media ;
use  Inavii\Instagram\Utils\VersionChecker ;
class WidgetSettings
{
    private  $settings ;
    public function __construct( array $settings )
    {
        $this->settings = $settings;
    }
    
    private function setting( $name )
    {
        if ( isset( $this->settings[$name] ) && !empty($this->settings[$name]) ) {
            return $this->settings[$name];
        }
        return false;
    }
    
    protected function feeds_contains( $haystack, $needle ) : bool
    {
        return $haystack === $needle;
    }
    
    public function feedId() : int
    {
        $feed = $this->setting( 'feeds_layout' );
        $feedId = explode( ':', $feed );
        return ( isset( $feedId[0] ) ? (int) $feedId[0] : 0 );
    }
    
    public function enableFollowButton() : bool
    {
        return $this->setting( 'enable_follow_button' ) === 'yes';
    }
    
    public function followButtonText()
    {
        return $this->setting( 'follow_button_text' );
    }
    
    public function followButtonIcon()
    {
        return $this->setting( 'follow_icon_button' );
    }
    
    public function enableHeaderFollowButton() : bool
    {
        return $this->setting( 'enable_header_follow_button' ) === 'yes';
    }
    
    public function headerFollowButtonText()
    {
        return $this->setting( 'header_follow_button_text' );
    }
    
    public function headerFollowButtonIcon()
    {
        return $this->setting( 'header_follow_icon_button' );
    }
    
    public function postsCountDefault() : int
    {
        return $this->setting( 'posts_counter' );
    }
    
    public function postsCountGridDefault() : int
    {
        return $this->setting( 'posts_counter_grid' );
    }
    
    public function postsCountGalleryDefault() : int
    {
        return $this->setting( 'posts_counter_gallery' );
    }
    
    public function postsCountRowDefault() : int
    {
        return 5;
    }
    
    public function postsCountMobile() : int
    {
        return $this->setting( 'posts_counter_mobile' );
    }
    
    public function postsCountMobile7() : int
    {
        return $this->setting( 'posts_counter_mobile_7' );
    }
    
    public function postsCountMobile5() : int
    {
        return $this->setting( 'posts_counter_mobile_5' );
    }
    
    public function postsCount() : int
    {
        if ( wp_is_mobile() ) {
            return $this->postsCountMobile();
        }
        $feedsValue = $this->feedType();
        if ( $this->feeds_contains( $feedsValue, WidgetsBase::GRID ) ) {
            return $this->postsCountGridDefault();
        }
        if ( $this->feeds_contains( $feedsValue, WidgetsBase::GALLERY ) ) {
            return $this->postsCountGalleryDefault();
        }
        if ( $this->feeds_contains( $feedsValue, WidgetsBase::ROW ) || $this->feeds_contains( $feedsValue, WidgetsBase::WAVE ) ) {
            return $this->postsCountRowDefault();
        }
        return $this->postsCountDefault();
    }
    
    public function feedType() : string
    {
        $feedsValue = $this->setting( 'feeds_layout' );
        $layout = explode( ':', $feedsValue );
        return $layout[1] ?? 'grid';
    }
    
    public function imgAnimation() : string
    {
        return ( $this->setting( 'hover_animation' ) ? 'elementor-animation-' . $this->setting( 'hover_animation' ) : '' );
    }
    
    public function target() : string
    {
        if ( $this->setting( 'post_link_target' ) === 'yes' ) {
            return '_blank';
        }
        return '_self';
    }
    
    public function enablePhotolinking() : string
    {
        return $this->setting( 'post_linking' );
    }
    
    public function imageSize() : string
    {
        if ( Media::checkGDLibraryAvailability() ) {
            return $this->setting( 'image_size_feeds' );
        }
        return 'full';
    }
    
    public function layoutView()
    {
        return $this->setting( 'layout' );
    }
    
    public function enablePopupFollowButton() : bool
    {
        return $this->setting( 'enable_popup_follow_button' ) === 'yes';
    }
    
    public function popupFollowButtonText()
    {
        return $this->setting( 'popup_follow_button_text' );
    }
    
    public function enablePopupIconFollowButton() : bool
    {
        return $this->setting( 'enable_popup_icon_follow_button' ) === 'yes';
    }
    
    public function popupFollowButtonIcon()
    {
        return $this->setting( 'popup_follow_icon_button' );
    }
    
    public function enableLightboxFollowButton() : bool
    {
        return $this->setting( 'enable_lightbox_follow_button' ) === 'yes';
    }
    
    public function lightboxFollowButtonText()
    {
        return $this->setting( 'lightbox_follow_button_text' );
    }
    
    public function enableLightboxIconFollowButton() : bool
    {
        return $this->setting( 'enable_lightbox_icon_follow_button' ) === 'yes';
    }
    
    public function lightboxFollowButtonIcon()
    {
        return $this->setting( 'lightbox_follow_icon_button' );
    }
    
    public function enableAvatarLightbox() : bool
    {
        return $this->setting( 'avatar_lightbox_switch' ) === 'yes';
    }
    
    public function enableAvatarHeaderBox() : bool
    {
        return $this->setting( 'avatar_header_switch' ) === 'yes';
    }
    
    public function enableBioHeaderBox() : bool
    {
        return $this->setting( 'bio_header_switch' ) === 'yes';
    }
    
    public function enableUserNameLightbox() : bool
    {
        return $this->setting( 'user_name_lightbox_switch' ) === 'yes';
    }
    
    public function enableUserNameHeaderBox() : bool
    {
        return $this->setting( 'user_name_header_switch' ) === 'yes';
    }

}