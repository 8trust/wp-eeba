<?php

namespace Inavii\Instagram\Includes\Integration\Widgets;

use  Elementor\Controls_Manager ;
use  Elementor\Core\Schemes\Typography ;
use  Elementor\Group_Control_Border ;
use  Elementor\Group_Control_Box_Shadow ;
use  Elementor\Group_Control_Css_Filter ;
use  Elementor\Group_Control_Typography ;
use  Elementor\Widget_Base ;
use  Inavii\Instagram\PostTypes\Feed\FeedPostType ;
use  Inavii\Instagram\Utils\VersionChecker ;
if ( !defined( 'ABSPATH' ) ) {
    exit;
}
abstract class WidgetsBase extends Widget_Base
{
    protected  $allFeeds = [] ;
    protected  $gridCondition = [] ;
    protected  $galleryCondition = [] ;
    protected  $rowCondition = [] ;
    protected  $waveCondition = [] ;
    protected  $waveGridCondition = [] ;
    protected  $highlightCondition = [] ;
    protected  $highlightSuperCondition = [] ;
    protected  $highlightFocusCondition = [] ;
    protected  $masonryHorizontalCondition = [] ;
    protected  $masonryVerticalCondition = [] ;
    protected  $sliderCondition = [] ;
    public const  GRID = 'grid' ;
    public const  GALLERY = 'gallery' ;
    public const  ROW = 'row' ;
    public const  WAVE = 'wave' ;
    public const  WAVE_GRID = 'wave-grid' ;
    public const  HIGHLIGHT = 'highlight' ;
    public const  HIGHLIGHT_SUPER = 'highlight-super' ;
    public const  HIGHLIGHT_FOCUS = 'highlight-focus' ;
    public const  MASONRY_HORIZONTAL = 'masonry-horizontal' ;
    public const  MASONRY_VERTICAL = 'masonry-vertical' ;
    public const  SLIDER = 'slider' ;
    public function __construct( $data = [], $args = null )
    {
        parent::__construct( $data, $args );
        $this->allFeeds = ( new FeedPostType() )->getAccounts();
        $this->gridCondition = $this->get_feed_by_condition( self::GRID );
        $this->galleryCondition = $this->get_feed_by_condition( self::GALLERY );
        $this->rowCondition = $this->get_feed_by_condition( self::ROW );
        $this->waveCondition = $this->get_feed_by_condition( self::WAVE );
        $this->waveGridCondition = $this->get_feed_by_condition( self::WAVE_GRID );
        $this->highlightCondition = $this->get_feed_by_condition( self::HIGHLIGHT );
        $this->highlightSuperCondition = $this->get_feed_by_condition( self::HIGHLIGHT_SUPER );
        $this->highlightFocusCondition = $this->get_feed_by_condition( self::HIGHLIGHT_FOCUS );
        $this->masonryHorizontalCondition = $this->get_feed_by_condition( self::MASONRY_HORIZONTAL );
        $this->masonryVerticalCondition = $this->get_feed_by_condition( self::MASONRY_VERTICAL );
        $this->sliderCondition = $this->get_feed_by_condition( self::SLIDER );
    }
    
    protected  $settings = [] ;
    public function get_categories()
    {
        return array( 'inavii-social-feed-e' );
    }
    
    public function get_keywords()
    {
        return [
            'instagram',
            'social',
            'feed',
            'social feed'
        ];
    }
    
    protected function register_controls()
    {
        $this->register_content_controls();
        $this->register_style_controls();
    }
    
    protected function register_content_controls()
    {
        $this->start_controls_section( 'section_account', array(
            'label' => esc_html__( 'Instagram Feeds', 'inavii-social-feed-e' ),
        ) );
        
        if ( empty($this->allFeeds) ) {
            $this->add_control( 'no_account_connected', array(
                'type' => Controls_Manager::RAW_HTML,
                'raw'  => sprintf( esc_html__( 'You have not added your Instagram account yet, to do it go %1$s', 'inavii-social-feed-e' ), '<a target="_blank" href="' . self::settingsPageLink() . '">' . esc_html__( 'here', 'inavii-social-feed-e' ) . '</a>' ),
            ) );
        } else {
            $this->add_control( 'account_type', array(
                'label'   => esc_html__( 'Account Type', 'inavii-social-feed-e' ),
                'type'    => Controls_Manager::SELECT,
                'default' => 'personal',
                'options' => array(
                'personal' => esc_html__( 'Personal', 'inavii-social-feed-e' ),
                'business' => esc_html__( 'Business', 'inavii-social-feed-e' ),
            ),
                'classes' => 'elementor-hidden',
            ) );
            $this->add_control( 'feeds_personal', array(
                'label'     => esc_html__( 'Select Feed', 'inavii-social-feed-e' ),
                'type'      => Controls_Manager::SELECT,
                'options'   => $accounts['personal'] ?? [],
                'condition' => array(
                'account_type' => 'personal',
            ),
                'classes'   => 'elementor-hidden',
            ) );
            $this->add_control( 'feeds_business', array(
                'label'     => esc_html__( 'Select Feed', 'inavii-social-feed-e' ),
                'type'      => Controls_Manager::SELECT,
                'options'   => $accounts['business'] ?? [],
                'condition' => array(
                'account_type' => 'business',
            ),
                'classes'   => 'elementor-hidden',
            ) );
            $this->add_control( 'feeds_layout', array(
                'label'   => esc_html__( 'Feeds Layout', 'inavii-social-feed-e' ),
                'type'    => Controls_Manager::SELECT,
                'options' => $this->allFeeds,
            ) );
        }
        
        $this->end_controls_section();
        $this->start_controls_section( 'section_query', array(
            'label'      => esc_html__( 'Query', 'inavii-social-feed-e' ),
            'conditions' => array(
            'terms' => array( array(
            'name'     => 'feeds_layout',
            'operator' => '!in',
            'value'    => array_merge( array_values( $this->waveCondition ), array_values( $this->rowCondition ), array_values( $this->waveGridCondition ) ),
        ) ),
        ),
        ) );
        $this->add_control( 'posts_counter', array(
            'label'      => esc_html__( 'Number of instagram posts', 'inavii-social-feed-e' ),
            'type'       => Controls_Manager::NUMBER,
            'default'    => 20,
            'min'        => 1,
            'step'       => 1,
            'conditions' => array(
            'terms' => array( array(
            'name'     => 'feeds_layout',
            'operator' => '!in',
            'value'    => array_merge(
            array_values( $this->rowCondition ),
            array_values( $this->waveCondition ),
            array_values( $this->gridCondition ),
            array_values( $this->galleryCondition ),
            array_values( $this->highlightCondition ),
            array_values( $this->highlightSuperCondition ),
            array_values( $this->highlightFocusCondition ),
            array_values( $this->masonryHorizontalCondition ),
            array_values( $this->masonryVerticalCondition ),
            array_values( $this->waveGridCondition ),
            array_values( $this->sliderCondition )
        ),
        ) ),
        ),
        ) );
        $this->add_control( 'posts_counter_grid', array(
            'label'      => esc_html__( 'Number of instagram posts', 'inavii-social-feed-e' ),
            'type'       => Controls_Manager::NUMBER,
            'default'    => 20,
            'min'        => 1,
            'step'       => 1,
            'conditions' => array(
            'terms' => array( array(
            'name'     => 'feeds_layout',
            'operator' => 'in',
            'value'    => array_merge( array_values( $this->gridCondition ), array_values( $this->masonryVerticalCondition ) ),
        ) ),
        ),
        ) );
        $this->add_control( 'posts_counter_highlight', array(
            'label'      => esc_html__( 'Number of instagram posts', 'inavii-social-feed-e' ),
            'type'       => Controls_Manager::NUMBER,
            'default'    => 12,
            'min'        => 1,
            'step'       => 1,
            'conditions' => array(
            'terms' => array( array(
            'name'     => 'feeds_layout',
            'operator' => 'in',
            'value'    => array_values( $this->highlightCondition ),
        ) ),
        ),
        ) );
        $this->add_control( 'posts_counter_highlight_super', array(
            'label'      => esc_html__( 'Number of instagram posts', 'inavii-social-feed-e' ),
            'type'       => Controls_Manager::NUMBER,
            'default'    => 5,
            'min'        => 1,
            'step'       => 1,
            'conditions' => array(
            'terms' => array( array(
            'name'     => 'feeds_layout',
            'operator' => 'in',
            'value'    => array_values( $this->highlightSuperCondition ),
        ) ),
        ),
        ) );
        $this->add_control( 'posts_counter_highlight_focus', array(
            'label'      => esc_html__( 'Number of instagram posts', 'inavii-social-feed-e' ),
            'type'       => Controls_Manager::NUMBER,
            'default'    => 13,
            'min'        => 1,
            'step'       => 1,
            'conditions' => array(
            'terms' => array( array(
            'name'     => 'feeds_layout',
            'operator' => 'in',
            'value'    => array_values( $this->highlightFocusCondition ),
        ) ),
        ),
        ) );
        $this->add_control( 'posts_counter_masonry_horizontal', array(
            'label'      => esc_html__( 'Number of instagram posts', 'inavii-social-feed-e' ),
            'type'       => Controls_Manager::NUMBER,
            'default'    => 18,
            'min'        => 1,
            'step'       => 1,
            'conditions' => array(
            'terms' => array( array(
            'name'     => 'feeds_layout',
            'operator' => 'in',
            'value'    => array_values( $this->masonryHorizontalCondition ),
        ) ),
        ),
        ) );
        $this->add_control( 'posts_counter_slider', array(
            'label'      => esc_html__( 'Number of instagram posts', 'inavii-social-feed-e' ),
            'type'       => Controls_Manager::NUMBER,
            'default'    => 8,
            'min'        => 1,
            'step'       => 1,
            'conditions' => array(
            'terms' => array( array(
            'name'     => 'feeds_layout',
            'operator' => 'in',
            'value'    => array_values( $this->sliderCondition ),
        ) ),
        ),
        ) );
        $this->add_control( 'posts_counter_gallery', array(
            'label'      => esc_html__( 'Number of instagram posts', 'inavii-social-feed-e' ),
            'type'       => Controls_Manager::NUMBER,
            'default'    => 15,
            'min'        => 1,
            'step'       => 1,
            'conditions' => array(
            'terms' => array( array(
            'name'     => 'feeds_layout',
            'operator' => 'in',
            'value'    => array_merge( array_values( $this->galleryCondition ), array_values( $this->waveGridCondition ) ),
        ) ),
        ),
        ) );
        $this->add_control( 'posts_counter_mobile', array(
            'label'      => esc_html__( 'Number of instagram posts on mobile', 'inavii-social-feed-e' ),
            'type'       => Controls_Manager::NUMBER,
            'default'    => 6,
            'min'        => 1,
            'step'       => 1,
            'conditions' => array(
            'terms' => array( array(
            'name'     => 'feeds_layout',
            'operator' => '!in',
            'value'    => array_merge(
            array_values( $this->waveCondition ),
            array_values( $this->rowCondition ),
            array_values( $this->waveGridCondition ),
            array_values( $this->highlightFocusCondition ),
            array_values( $this->highlightSuperCondition )
        ),
        ) ),
        ),
        ) );
        $this->add_control( 'posts_counter_mobile_7', array(
            'label'      => esc_html__( 'Number of instagram posts on mobile', 'inavii-social-feed-e' ),
            'type'       => Controls_Manager::NUMBER,
            'default'    => 7,
            'min'        => 1,
            'step'       => 1,
            'conditions' => array(
            'terms' => array( array(
            'name'     => 'feeds_layout',
            'operator' => 'in',
            'value'    => array_values( $this->highlightFocusCondition ),
        ) ),
        ),
        ) );
        $this->add_control( 'posts_counter_mobile_5', array(
            'label'      => esc_html__( 'Number of instagram posts on mobile', 'inavii-social-feed-e' ),
            'type'       => Controls_Manager::NUMBER,
            'default'    => 5,
            'min'        => 1,
            'step'       => 1,
            'conditions' => array(
            'terms' => array( array(
            'name'     => 'feeds_layout',
            'operator' => 'in',
            'value'    => array_values( $this->highlightSuperCondition ),
        ) ),
        ),
        ) );
        $this->end_controls_section();
        $this->start_controls_section( 'section_general', array(
            'label' => esc_html__( 'Layout Settings', 'inavii-social-feed-e' ),
        ) );
        $this->add_control( 'layout', array(
            'label'   => __( 'Card Layout', 'inavii-social-feed-e' ),
            'type'    => Controls_Manager::SELECT,
            'default' => 'overlay',
            'options' => array(
            'overlay'  => 'Overlay',
            'flip-box' => 'Flip Box',
        ),
            'classes' => self::eventClassPro(),
        ) );
        if ( !VersionChecker::version()->can_use_premium_code() ) {
            $this->add_control( 'layout_pro', array(
                'type'            => Controls_Manager::RAW_HTML,
                'raw'             => $this->premiumInfo(),
                'classes'         => $this->eventClassProInfo(),
                'content_classes' => 'elementor-descriptor',
            ) );
        }
        $this->add_control( 'image_size_feeds', array(
            'label'   => __( 'Image Size Feeds', 'inavii-social-feed-e' ),
            'type'    => Controls_Manager::SELECT,
            'default' => 'medium',
            'options' => array(
            'small'  => 'Small - 300',
            'medium' => 'Medium - 768',
            'large'  => 'Large - 1024',
        ),
        ) );
        $this->add_control( 'post_linking', array(
            'label'   => __( 'Image Click Actions', 'inavii-social-feed-e' ),
            'type'    => Controls_Manager::SELECT,
            'default' => self::imageClickActions(),
            'options' => array(
            'none'         => 'None',
            'linking_post' => 'Instagram Link',
            'popup'        => 'Open PopUp',
            'lightbox'     => 'Open LightBox',
        ),
        ) );
        $this->add_control( 'post_link_target', array(
            'label'        => esc_html__( 'Open in a New Window ', 'inavii-social-feed-e' ),
            'type'         => Controls_Manager::SWITCHER,
            'label_on'     => esc_html__( 'Yes', 'inavii-social-feed-e' ),
            'label_off'    => esc_html__( 'No', 'inavii-social-feed-e' ),
            'return_value' => 'yes',
            'default'      => 'yes',
            'condition'    => array(
            'post_linking' => 'linking_post',
        ),
        ) );
        $this->end_controls_section();
        $this->start_controls_section( 'section_content_header_box', array(
            'label' => esc_html__( 'Header Box', 'inavii-social-feed-e' ),
        ) );
        $this->add_control( 'avatar_header_switch', array(
            'label'        => esc_html__( 'Show Avatar', 'inavii-social-feed-e' ),
            'type'         => Controls_Manager::SWITCHER,
            'label_on'     => esc_html__( 'Yes', 'inavii-social-feed-e' ),
            'label_off'    => esc_html__( 'No', 'inavii-social-feed-e' ),
            'return_value' => 'yes',
            'default'      => 'no',
        ) );
        $this->add_control( 'avatar_header_switch_pro', array(
            'type'            => Controls_Manager::RAW_HTML,
            'raw'             => __( 'You can change your avatar in <a href="./admin.php?page=inavii-instagram-settings#/accounts" target="_blank"> settings</a>.', 'inavii-social-feed-e' ),
            'classes'         => 'inavii-pro__info inavii-pro__info--v2',
            'content_classes' => 'elementor-descriptor',
        ) );
        $this->add_control( 'bio_header_switch', array(
            'label'        => esc_html__( 'Show Bio', 'inavii-social-feed-e' ),
            'type'         => Controls_Manager::SWITCHER,
            'label_on'     => esc_html__( 'Yes', 'inavii-social-feed-e' ),
            'label_off'    => esc_html__( 'No', 'inavii-social-feed-e' ),
            'return_value' => 'yes',
            'default'      => 'no',
            'classes'      => self::eventClassPro(),
        ) );
        if ( !VersionChecker::version()->can_use_premium_code() ) {
            $this->add_control( 'date_switch_pro', array(
                'type'            => Controls_Manager::RAW_HTML,
                'raw'             => $this->premiumInfo(),
                'classes'         => $this->eventClassProInfo(),
                'content_classes' => 'elementor-descriptor',
            ) );
        }
        $this->add_control( 'user_name_header_switch', array(
            'label'        => esc_html__( 'Show Username', 'inavii-social-feed-e' ),
            'type'         => Controls_Manager::SWITCHER,
            'label_on'     => esc_html__( 'Yes', 'inavii-social-feed-e' ),
            'label_off'    => esc_html__( 'No', 'inavii-social-feed-e' ),
            'return_value' => 'yes',
            'default'      => 'no',
        ) );
        $this->add_control( 'enable_header_follow_button', array(
            'label'        => esc_html__( 'Show Follow Instagram Button', 'inavii-social-feed-e' ),
            'type'         => Controls_Manager::SWITCHER,
            'label_on'     => esc_html__( 'Yes', 'inavii-social-feed-e' ),
            'label_off'    => esc_html__( 'No', 'inavii-social-feed-e' ),
            'return_value' => 'yes',
            'default'      => 'no',
        ) );
        $this->add_control( 'header_follow_button_text', array(
            'label'     => __( 'Instagram Button Text', 'inavii-social-feed-e' ),
            'type'      => Controls_Manager::TEXT,
            'default'   => 'Follow on Instagram',
            'condition' => [
            'enable_header_follow_button' => 'yes',
        ],
        ) );
        $this->end_controls_section();
        $this->start_controls_section( 'section_content', array(
            'label' => esc_html__( 'Content', 'inavii-social-feed-e' ),
        ) );
        $this->start_controls_tabs( 'section_content_style_tabs' );
        $this->start_controls_tab( 'section_content_general_style_tab', [
            'label' => esc_html__( 'General', 'inavii-social-feed-e' ),
        ] );
        $this->add_control( 'date_switch', array(
            'label'        => esc_html__( 'Show Date', 'inavii-social-feed-e' ),
            'type'         => Controls_Manager::SWITCHER,
            'label_on'     => esc_html__( 'Yes', 'inavii-social-feed-e' ),
            'label_off'    => esc_html__( 'No', 'inavii-social-feed-e' ),
            'return_value' => 'yes',
            'default'      => self::defaultValueYesNo(),
            'classes'      => self::eventClassPro(),
        ) );
        if ( !VersionChecker::version()->can_use_premium_code() ) {
            $this->add_control( 'date_switch_pro', array(
                'type'            => Controls_Manager::RAW_HTML,
                'raw'             => $this->premiumInfo(),
                'classes'         => $this->eventClassProInfo(),
                'content_classes' => 'elementor-descriptor',
            ) );
        }
        $this->add_control( 'post_caption', array(
            'label'        => esc_html__( 'Show Caption', 'inavii-social-feed-e' ),
            'type'         => Controls_Manager::SWITCHER,
            'label_on'     => esc_html__( 'Yes', 'inavii-social-feed-e' ),
            'label_off'    => esc_html__( 'No', 'inavii-social-feed-e' ),
            'return_value' => 'yes',
            'default'      => self::defaultValueYesNo(),
            'classes'      => self::eventClassPro(),
        ) );
        if ( !VersionChecker::version()->can_use_premium_code() ) {
            $this->add_control( 'post_caption_pro', array(
                'type'            => Controls_Manager::RAW_HTML,
                'raw'             => $this->premiumInfo(),
                'classes'         => $this->eventClassProInfo(),
                'content_classes' => 'elementor-descriptor',
            ) );
        }
        $this->add_control( 'likes_switch', array(
            'label'        => esc_html__( 'Show Likes Count', 'inavii-social-feed-e' ),
            'type'         => Controls_Manager::SWITCHER,
            'label_on'     => esc_html__( 'Yes', 'inavii-social-feed-e' ),
            'label_off'    => esc_html__( 'No', 'inavii-social-feed-e' ),
            'return_value' => 'yes',
            'default'      => self::defaultValueYesNo(),
            'classes'      => self::eventClassPro(),
        ) );
        if ( !VersionChecker::version()->can_use_premium_code() ) {
            $this->add_control( 'likes_switch_pro', array(
                'type'            => Controls_Manager::RAW_HTML,
                'raw'             => $this->premiumInfo(),
                'classes'         => $this->eventClassProInfo(),
                'content_classes' => 'elementor-descriptor',
            ) );
        }
        $this->add_control( 'comments_switch', array(
            'label'        => esc_html__( 'Show Comments Count', 'inavii-social-feed-e' ),
            'type'         => Controls_Manager::SWITCHER,
            'label_on'     => esc_html__( 'Yes', 'inavii-social-feed-e' ),
            'label_off'    => esc_html__( 'No', 'inavii-social-feed-e' ),
            'return_value' => 'yes',
            'default'      => self::defaultValueYesNo(),
            'classes'      => self::eventClassPro(),
        ) );
        if ( !VersionChecker::version()->can_use_premium_code() ) {
            $this->add_control( 'comments_switch_pro', array(
                'type'            => Controls_Manager::RAW_HTML,
                'raw'             => $this->premiumInfo(),
                'classes'         => $this->eventClassProInfo(),
                'content_classes' => 'elementor-descriptor',
            ) );
        }
        $this->end_controls_tab();
        $this->start_controls_tab( 'section_content_popup_tab', [
            'label'     => esc_html__( 'PopUp', 'inavii-social-feed-e' ),
            'condition' => array(
            'post_linking' => 'popup',
        ),
        ] );
        $this->add_control( 'enable_popup_follow_button', array(
            'label'        => esc_html__( 'Show Follow Post Button Text', 'inavii-social-feed-e' ),
            'type'         => Controls_Manager::SWITCHER,
            'label_on'     => esc_html__( 'Yes', 'inavii-social-feed-e' ),
            'label_off'    => esc_html__( 'No', 'inavii-social-feed-e' ),
            'return_value' => 'yes',
            'default'      => 'yes',
        ) );
        $this->add_control( 'popup_follow_button_text', array(
            'label'     => __( 'Instagram Button Text', 'inavii-social-feed-e' ),
            'type'      => Controls_Manager::TEXT,
            'default'   => 'View on Instagram',
            'condition' => [
            'enable_popup_follow_button' => 'yes',
        ],
        ) );
        $this->add_control( 'enable_popup_icon_follow_button', array(
            'label'        => esc_html__( 'Show Follow Post Button Icon', 'inavii-social-feed-e' ),
            'type'         => Controls_Manager::SWITCHER,
            'label_on'     => esc_html__( 'Yes', 'inavii-social-feed-e' ),
            'label_off'    => esc_html__( 'No', 'inavii-social-feed-e' ),
            'return_value' => 'yes',
            'default'      => 'yes',
        ) );
        $this->add_control( 'popup_follow_icon_button', array(
            'label'     => __( 'Icon', 'inavii-social-feed-e' ),
            'type'      => Controls_Manager::ICONS,
            'default'   => array(
            'value'   => 'fab fa-instagram',
            'library' => 'fa-brands',
        ),
            'condition' => [
            'enable_popup_icon_follow_button' => 'yes',
        ],
        ) );
        $this->end_controls_tab();
        $this->start_controls_tab( 'section_content_lightbox_style_tab', [
            'label'     => esc_html__( 'Lightbox', 'inavii-social-feed-e' ),
            'condition' => array(
            'post_linking' => 'lightbox',
        ),
        ] );
        $this->add_control( 'avatar_lightbox_switch', array(
            'label'        => esc_html__( 'Show Avatar', 'inavii-social-feed-e' ),
            'type'         => Controls_Manager::SWITCHER,
            'label_on'     => esc_html__( 'Yes', 'inavii-social-feed-e' ),
            'label_off'    => esc_html__( 'No', 'inavii-social-feed-e' ),
            'return_value' => 'yes',
            'default'      => 'yes',
        ) );
        $this->add_control( 'avatar_lightbox_switch_pro', array(
            'type'            => Controls_Manager::RAW_HTML,
            'raw'             => __( 'You can change your avatar in <a href="./admin.php?page=inavii-instagram-settings#/accounts" target="_blank"> settings</a>.', 'inavii-social-feed-e' ),
            'classes'         => 'inavii-pro__info inavii-pro__info--v2',
            'content_classes' => 'elementor-descriptor',
        ) );
        $this->add_control( 'user_name_lightbox_switch', array(
            'label'        => esc_html__( 'Show Username', 'inavii-social-feed-e' ),
            'type'         => Controls_Manager::SWITCHER,
            'label_on'     => esc_html__( 'Yes', 'inavii-social-feed-e' ),
            'label_off'    => esc_html__( 'No', 'inavii-social-feed-e' ),
            'return_value' => 'yes',
            'default'      => 'yes',
        ) );
        $this->add_control( 'post_lightbox_caption', array(
            'label'        => esc_html__( 'Show Caption', 'inavii-social-feed-e' ),
            'type'         => Controls_Manager::SWITCHER,
            'label_on'     => esc_html__( 'Yes', 'inavii-social-feed-e' ),
            'label_off'    => esc_html__( 'No', 'inavii-social-feed-e' ),
            'return_value' => 'yes',
            'default'      => self::defaultValueYesNo(),
            'classes'      => self::eventClassPro(),
        ) );
        if ( !VersionChecker::version()->can_use_premium_code() ) {
            $this->add_control( 'post_lightbox_caption_pro', array(
                'type'            => Controls_Manager::RAW_HTML,
                'raw'             => $this->premiumInfo(),
                'classes'         => $this->eventClassProInfo(),
                'content_classes' => 'elementor-descriptor',
            ) );
        }
        $this->add_control( 'date_lightbox_switch', array(
            'label'        => esc_html__( 'Show Date', 'inavii-social-feed-e' ),
            'type'         => Controls_Manager::SWITCHER,
            'label_on'     => esc_html__( 'Yes', 'inavii-social-feed-e' ),
            'label_off'    => esc_html__( 'No', 'inavii-social-feed-e' ),
            'return_value' => 'yes',
            'default'      => self::defaultValueYesNo(),
            'classes'      => self::eventClassPro(),
        ) );
        if ( !VersionChecker::version()->can_use_premium_code() ) {
            $this->add_control( 'date_lightbox_switch_pro', array(
                'type'            => Controls_Manager::RAW_HTML,
                'raw'             => $this->premiumInfo(),
                'classes'         => $this->eventClassProInfo(),
                'content_classes' => 'elementor-descriptor',
            ) );
        }
        $this->add_control( 'likes_lightbox_switch', array(
            'label'        => esc_html__( 'Show Likes Count', 'inavii-social-feed-e' ),
            'type'         => Controls_Manager::SWITCHER,
            'label_on'     => esc_html__( 'Yes', 'inavii-social-feed-e' ),
            'label_off'    => esc_html__( 'No', 'inavii-social-feed-e' ),
            'return_value' => 'yes',
            'default'      => self::defaultValueYesNo(),
            'classes'      => self::eventClassPro(),
        ) );
        if ( !VersionChecker::version()->can_use_premium_code() ) {
            $this->add_control( 'likes_lightbox_switch_pro', array(
                'type'            => Controls_Manager::RAW_HTML,
                'raw'             => $this->premiumInfo(),
                'classes'         => $this->eventClassProInfo(),
                'content_classes' => 'elementor-descriptor',
            ) );
        }
        $this->add_control( 'comments_lightbox_switch', array(
            'label'        => esc_html__( 'Show Comments Count', 'inavii-social-feed-e' ),
            'type'         => Controls_Manager::SWITCHER,
            'label_on'     => esc_html__( 'Yes', 'inavii-social-feed-e' ),
            'label_off'    => esc_html__( 'No', 'inavii-social-feed-e' ),
            'return_value' => 'yes',
            'default'      => self::defaultValueYesNo(),
            'classes'      => self::eventClassPro(),
        ) );
        if ( !VersionChecker::version()->can_use_premium_code() ) {
            $this->add_control( 'comments_lightbox_switch_pro', array(
                'type'            => Controls_Manager::RAW_HTML,
                'raw'             => $this->premiumInfo(),
                'classes'         => $this->eventClassProInfo(),
                'content_classes' => 'elementor-descriptor',
            ) );
        }
        $this->add_control( 'enable_lightbox_follow_button', array(
            'label'        => esc_html__( 'Show Follow Post Button Text', 'inavii-social-feed-e' ),
            'type'         => Controls_Manager::SWITCHER,
            'label_on'     => esc_html__( 'Yes', 'inavii-social-feed-e' ),
            'label_off'    => esc_html__( 'No', 'inavii-social-feed-e' ),
            'return_value' => 'yes',
            'default'      => 'yes',
        ) );
        $this->add_control( 'lightbox_follow_button_text', array(
            'label'     => __( 'Instagram Button Text', 'inavii-social-feed-e' ),
            'type'      => Controls_Manager::TEXT,
            'default'   => 'View on Instagram',
            'condition' => [
            'enable_lightbox_follow_button' => 'yes',
        ],
        ) );
        $this->add_control( 'enable_lightbox_icon_follow_button', array(
            'label'        => esc_html__( 'Show Follow Post Button Icon', 'inavii-social-feed-e' ),
            'type'         => Controls_Manager::SWITCHER,
            'label_on'     => esc_html__( 'Yes', 'inavii-social-feed-e' ),
            'label_off'    => esc_html__( 'No', 'inavii-social-feed-e' ),
            'return_value' => 'yes',
            'default'      => 'yes',
        ) );
        $this->add_control( 'lightbox_follow_icon_button', array(
            'label'     => __( 'Icon', 'inavii-social-feed-e' ),
            'type'      => Controls_Manager::ICONS,
            'default'   => array(
            'value'   => 'fab fa-instagram',
            'library' => 'fa-brands',
        ),
            'condition' => [
            'enable_lightbox_icon_follow_button' => 'yes',
        ],
        ) );
        $this->end_controls_tab();
        $this->end_controls_tabs();
        $this->end_controls_section();
        $this->start_controls_section( 'section_content_footer_box', array(
            'label' => esc_html__( 'Footer Box', 'inavii-social-feed-e' ),
        ) );
        $this->add_control( 'enable_follow_button', array(
            'label'        => esc_html__( 'Show Follow Instagram Button', 'inavii-social-feed-e' ),
            'type'         => Controls_Manager::SWITCHER,
            'label_on'     => esc_html__( 'Yes', 'inavii-social-feed-e' ),
            'label_off'    => esc_html__( 'No', 'inavii-social-feed-e' ),
            'return_value' => 'yes',
            'default'      => 'no',
        ) );
        $this->add_control( 'follow_button_text', array(
            'label'     => __( 'Instagram Button Text', 'inavii-social-feed-e' ),
            'type'      => Controls_Manager::TEXT,
            'default'   => 'Follow on Instagram',
            'condition' => [
            'enable_follow_button' => 'yes',
        ],
        ) );
        $this->end_controls_section();
    }
    
    protected function register_style_controls()
    {
        $this->start_controls_section( 'section_general_style', array(
            'label' => __( 'Layout Settings', 'inavii-social-feed-e' ),
            'tab'   => Controls_Manager::TAB_STYLE,
        ) );
        $this->add_responsive_control( 'item-column', array(
            'label'      => __( 'Column', 'inavii-social-feed-e' ),
            'type'       => Controls_Manager::SELECT,
            'options'    => array(
            '1'  => '1',
            '2'  => '2',
            '3'  => '3',
            '4'  => '4',
            '5'  => '5',
            '6'  => '6',
            '7'  => '7',
            '8'  => '8',
            '9'  => '9',
            '10' => '10',
        ),
            'selectors'  => array(
            '{{WRAPPER}}.elementor-widget-inavii-grid .inavii-grid' => 'grid-template-columns: repeat({{VALUE}}, 1fr);',
        ),
            'conditions' => array(
            'terms' => array( array(
            'name'     => 'feeds_layout',
            'operator' => '!in',
            'value'    => array_merge(
            array_values( $this->rowCondition ),
            array_values( $this->waveCondition ),
            array_values( $this->waveGridCondition ),
            array_values( $this->highlightCondition ),
            array_values( $this->highlightSuperCondition ),
            array_values( $this->highlightFocusCondition ),
            array_values( $this->masonryHorizontalCondition ),
            array_values( $this->masonryVerticalCondition ),
            array_values( $this->sliderCondition )
        ),
        ) ),
        ),
        ) );
        $this->add_responsive_control( 'item-column-row', array(
            'label'          => __( 'Column', 'inavii-social-feed-e' ),
            'type'           => Controls_Manager::SELECT,
            'default'        => '5',
            'tablet_default' => '4',
            'mobile_default' => '3',
            'selectors'      => array(
            '{{WRAPPER}}.elementor-widget-inavii-grid .inavii-grid__type-wave,
                    {{WRAPPER}}.elementor-widget-inavii-grid .inavii-grid__type-wave-grid,
                    {{WRAPPER}}.elementor-widget-inavii-grid .inavii-grid__type-row' => 'grid-template-columns: repeat({{VALUE}}, 1fr);',
        ),
            'classes'        => 'elementor-hidden',
            'conditions'     => array(
            'terms' => array( array(
            'name'     => 'feeds_layout',
            'operator' => 'in',
            'value'    => array_merge( array_values( $this->rowCondition ), array_values( $this->waveCondition ), array_values( $this->waveGridCondition ) ),
        ) ),
        ),
        ) );
        $this->add_responsive_control( 'items-gap', array(
            'label'      => __( 'Items gap', 'inavii-social-feed-e' ),
            'type'       => Controls_Manager::SLIDER,
            'size_units' => array( 'px' ),
            'range'      => array(
            'px' => array(
            'min' => 0,
            'max' => 100,
        ),
        ),
            'default'    => array(
            'size' => 10,
            'unit' => 'px',
        ),
            'selectors'  => array(
            '{{WRAPPER}} .inavii-grid__type-masonry-vertical .grid-item'   => 'margin-bottom: {{SIZE}}{{UNIT}};',
            '{{WRAPPER}}.elementor-widget-inavii-grid .inavii-grid'        => 'gap: {{SIZE}}{{UNIT}};',
            '{{WRAPPER}} .inavii-grid__type-masonry-horizontal .grid-item' => '--gap: {{SIZE}}{{UNIT}};',
        ),
            'conditions' => array(
            'terms' => array( array(
            'name'     => 'feeds_layout',
            'operator' => 'in',
            'value'    => array_merge(
            array_values( $this->gridCondition ),
            array_values( $this->waveCondition ),
            array_values( $this->waveGridCondition ),
            array_values( $this->highlightCondition ),
            array_values( $this->highlightSuperCondition ),
            array_values( $this->highlightFocusCondition ),
            array_values( $this->masonryHorizontalCondition ),
            array_values( $this->masonryVerticalCondition )
        ),
        ) ),
        ),
        ) );
        $this->add_responsive_control( 'items_no_gap', array(
            'label'      => __( 'Items gap', 'inavii-social-feed-e' ),
            'type'       => Controls_Manager::SLIDER,
            'size_units' => array( 'px' ),
            'range'      => array(
            'px' => array(
            'min' => 0,
            'max' => 100,
        ),
        ),
            'default'    => array(
            'size' => 0,
            'unit' => 'px',
        ),
            'selectors'  => array(
            '{{WRAPPER}}.elementor-widget-inavii-grid .inavii-grid' => 'gap: {{SIZE}}{{UNIT}};',
        ),
            'conditions' => array(
            'terms' => array( array(
            'name'     => 'feeds_layout',
            'operator' => 'in',
            'value'    => array_merge( array_values( $this->galleryCondition ), array_values( $this->rowCondition ) ),
        ) ),
        ),
        ) );
        $this->add_responsive_control( 'items_height', array(
            'label'          => __( 'Items Height', 'inavii-social-feed-e' ),
            'type'           => Controls_Manager::SLIDER,
            'default'        => array(
            'unit' => 'px',
        ),
            'tablet_default' => array(
            'unit' => 'px',
        ),
            'mobile_default' => array(
            'unit' => 'px',
        ),
            'size_units'     => array( 'px', 'vw' ),
            'range'          => array(
            'px' => array(
            'min' => 1,
            'max' => 1000,
        ),
            'vh' => array(
            'min' => 1,
            'max' => 100,
        ),
        ),
            'selectors'      => array(
            '{{WRAPPER}} .inavii-grid.swiper-container-horizontal .grid-item,
                        {{WRAPPER}}.elementor-widget-inavii-grid .inavii-grid .grid-item' => 'height: {{SIZE}}{{UNIT}};--height-value: {{SIZE}};',
        ),
            'conditions'     => array(
            'terms' => array( array(
            'name'     => 'feeds_layout',
            'operator' => '!in',
            'value'    => array_merge(
            array_values( $this->highlightCondition ),
            array_values( $this->highlightSuperCondition ),
            array_values( $this->highlightFocusCondition ),
            array_values( $this->masonryVerticalCondition )
        ),
        ) ),
        ),
        ) );
        $this->end_controls_section();
        $this->start_controls_section( 'section_images_style', array(
            'label' => __( 'General', 'inavii-social-feed-e' ),
            'tab'   => Controls_Manager::TAB_STYLE,
        ) );
        $this->add_responsive_control( 'object-fit', [
            'label'     => __( 'Object Fit', 'inavii-social-feed-e' ),
            'type'      => Controls_Manager::SELECT,
            'options'   => array(
            ''        => 'Default',
            'fill'    => 'Fill',
            'cover'   => 'Cover',
            'contain' => 'Contain',
        ),
            'default'   => '',
            'selectors' => [
            '{{WRAPPER}} .inavii-grid__item-box .inavii-grid__image' => 'object-fit: {{VALUE}};',
        ],
        ] );
        $this->add_control( 'items_border_radius', array(
            'label'      => __( 'Items border radius', 'inavii-social-feed-e' ),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => array( 'px', '%' ),
            'selectors'  => array(
            '{{WRAPPER}} .inavii-grid__item,
					{{WRAPPER}} .inavii-grid__item .inavii-grid__image-box,
					{{WRAPPER}} .inavii-grid__item .inavii-grid__item-box,
					{{WRAPPER}} .inavii-grid .inavii-grid__top-box' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
        ),
            'conditions' => array(
            'terms' => array( array(
            'name'     => 'feeds_layout',
            'operator' => 'in',
            'value'    => array_merge(
            array_values( $this->gridCondition ),
            array_values( $this->waveCondition ),
            array_values( $this->waveGridCondition ),
            array_values( $this->highlightCondition ),
            array_values( $this->highlightSuperCondition ),
            array_values( $this->highlightFocusCondition ),
            array_values( $this->masonryHorizontalCondition ),
            array_values( $this->masonryVerticalCondition )
        ),
        ) ),
        ),
        ) );
        $this->add_control( 'items_border_no_radius', array(
            'label'      => __( 'Items border radius', 'inavii-social-feed-e' ),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => array( 'px', '%' ),
            'default'    => array(
            'top'    => 0,
            'right'  => 0,
            'bottom' => 0,
            'left'   => 0,
            'unit'   => 'px',
        ),
            'selectors'  => array(
            '{{WRAPPER}} .inavii-grid__item,
					{{WRAPPER}} .inavii-grid__item .inavii-grid__image-box,
					{{WRAPPER}} .inavii-grid__item .inavii-grid__item-box,
					{{WRAPPER}} .inavii-grid .inavii-grid__top-box' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
        ),
            'conditions' => array(
            'terms' => array( array(
            'name'     => 'feeds_layout',
            'operator' => 'in',
            'value'    => array_merge( array_values( $this->galleryCondition ), array_values( $this->rowCondition ) ),
        ) ),
        ),
        ) );
        $this->add_control( 'separator-style', [
            'type' => Controls_Manager::DIVIDER,
        ] );
        $this->start_controls_tabs( 'image-effects' );
        $this->start_controls_tab( 'normal', [
            'label' => __( 'Normal', 'inavii-social-feed-e' ),
        ] );
        $this->add_control( 'opacity', [
            'label'     => __( 'Opacity', 'inavii-social-feed-e' ),
            'type'      => Controls_Manager::SLIDER,
            'range'     => [
            'px' => [
            'max'  => 1,
            'min'  => 0.1,
            'step' => 0.01,
        ],
        ],
            'selectors' => [
            '{{WRAPPER}} .inavii-grid__item-box .inavii-grid__image' => 'opacity: {{SIZE}};',
        ],
        ] );
        $this->add_responsive_control( 'grayscale', array(
            'label'          => __( 'Grayscale', 'inavii' ),
            'type'           => Controls_Manager::SLIDER,
            'default'        => array(
            'unit' => '%',
        ),
            'tablet_default' => array(
            'unit' => '%',
        ),
            'mobile_default' => array(
            'unit' => '%',
        ),
            'size_units'     => array( '%' ),
            'range'          => array(
            '%' => array(
            'min' => 0,
            'max' => 100,
        ),
        ),
            'selectors'      => [
            '{{WRAPPER}} .inavii-grid__item-box' => 'filter: grayscale({{SIZE}}{{UNIT}});',
        ],
        ) );
        $this->add_group_control( Group_Control_Css_Filter::get_type(), [
            'name'     => 'css_filters',
            'selector' => '{{WRAPPER}} .inavii-grid__item-box .inavii-grid__image',
        ] );
        $this->add_control( 'transition', [
            'label'     => __( 'Transition (ms)', 'inavii-social-feed-e' ),
            'type'      => Controls_Manager::SLIDER,
            'range'     => [
            'px' => [
            'max'  => 3000,
            'min'  => 100,
            'step' => 100,
        ],
        ],
            'selectors' => [
            '{{WRAPPER}} .inavii-grid__item .inavii-grid__item-box .inavii-grid__image' => 'transition: all {{SIZE}}ms;',
        ],
        ] );
        $this->add_group_control( Group_Control_Box_Shadow::get_type(), array(
            'name'     => 'items_box_shadow',
            'selector' => '{{WRAPPER}} .inavii-grid__item',
        ) );
        $this->add_group_control( Group_Control_Border::get_type(), array(
            'name'     => 'items_border_normal',
            'selector' => '{{WRAPPER}} .inavii-grid__item-box',
        ) );
        $this->end_controls_tab();
        $this->start_controls_tab( 'hover', [
            'label' => __( 'Hover', 'inavii-social-feed-e' ),
        ] );
        $this->add_control( 'opacity_hover', [
            'label'     => __( 'Opacity', 'inavii-social-feed-e' ),
            'type'      => Controls_Manager::SLIDER,
            'range'     => [
            'px' => [
            'max'  => 1,
            'min'  => 0.1,
            'step' => 0.01,
        ],
        ],
            'selectors' => [
            '{{WRAPPER}} .inavii-grid__item:hover .inavii-grid__item-box .inavii-grid__image' => 'opacity: {{SIZE}};',
        ],
        ] );
        $this->add_responsive_control( 'grayscale_hover', array(
            'label'          => __( 'Grayscale', 'inavii-social-feed-e' ),
            'type'           => Controls_Manager::SLIDER,
            'default'        => array(
            'unit' => '%',
        ),
            'tablet_default' => array(
            'unit' => '%',
        ),
            'mobile_default' => array(
            'unit' => '%',
        ),
            'size_units'     => array( '%' ),
            'range'          => array(
            '%' => array(
            'min' => 0,
            'max' => 100,
        ),
        ),
            'selectors'      => [
            '{{WRAPPER}} .inavii-grid__item:hover .inavii-grid__item-box' => 'filter: grayscale({{SIZE}}{{UNIT}});',
        ],
        ) );
        $this->add_responsive_control( 'scale_hover', array(
            'label'     => __( 'Scale', 'inavii-social-feed-e' ),
            'type'      => Controls_Manager::SLIDER,
            'range'     => array(
            'px' => [
            'max'  => 6,
            'min'  => 1,
            'step' => 0.01,
        ],
        ),
            'selectors' => [
            '{{WRAPPER}} .inavii-grid__item-box .inavii-grid__image:hover' => 'transform: scale({{SIZE}});',
        ],
        ) );
        $this->add_group_control( Group_Control_Css_Filter::get_type(), [
            'name'     => 'css_filters_hover',
            'selector' => '{{WRAPPER}} .inavii-grid__item:hover .inavii-grid__item-box .inavii-grid__image',
        ] );
        $this->add_group_control( Group_Control_Box_Shadow::get_type(), array(
            'name'     => 'items_box_shadow_hover',
            'selector' => '{{WRAPPER}} .inavii-grid__item:hover',
        ) );
        $this->add_group_control( Group_Control_Border::get_type(), array(
            'name'     => 'box_content_border_hover',
            'selector' => '{{WRAPPER}} .inavii-grid__item-box:hover',
        ) );
        $this->add_control( 'background_hover_transition', [
            'label'     => __( 'Transition Duration', 'inavii-social-feed-e' ),
            'type'      => Controls_Manager::SLIDER,
            'range'     => [
            'px' => [
            'max'  => 3,
            'step' => 0.1,
        ],
        ],
            'selectors' => [
            '{{WRAPPER}} .inavii-grid__item' => 'transition-duration: {{SIZE}}s',
        ],
        ] );
        $this->add_control( 'hover_animation', [
            'label' => __( 'Hover Animation', 'inavii-social-feed-e' ),
            'type'  => Controls_Manager::HOVER_ANIMATION,
        ] );
        $this->end_controls_tab();
        $this->end_controls_tabs();
        $this->add_control( 'separator_style_bottom', [
            'type' => Controls_Manager::DIVIDER,
        ] );
        $this->start_controls_tabs( 'section_general_middle_style_tabs' );
        $this->start_controls_tab( 'section_general_overlay_style_tab', [
            'label' => esc_html__( 'Overlay Box', 'inavii-social-feed-e' ),
        ] );
        if ( !VersionChecker::version()->can_use_premium_code() ) {
            $this->add_control( 'general_overlay_pro', array(
                'type'            => Controls_Manager::RAW_HTML,
                'raw'             => $this->premiumInfo(),
                'classes'         => $this->eventClassProInfo(),
                'content_classes' => 'elementor-descriptor',
            ) );
        }
        $this->end_controls_tab();
        $this->start_controls_tab( 'section_general_description_style_tab', [
            'label' => esc_html__( 'Description', 'inavii-social-feed-e' ),
        ] );
        if ( !VersionChecker::version()->can_use_premium_code() ) {
            $this->add_control( 'general_description_pro', array(
                'type'            => Controls_Manager::RAW_HTML,
                'raw'             => $this->premiumInfo(),
                'classes'         => $this->eventClassProInfo(),
                'content_classes' => 'elementor-descriptor',
            ) );
        }
        $this->end_controls_tab();
        $this->start_controls_tab( 'section_general_date_style_tab', [
            'label' => esc_html__( 'Date', 'inavii-social-feed-e' ),
        ] );
        if ( !VersionChecker::version()->can_use_premium_code() ) {
            $this->add_control( 'date_pro', array(
                'type'            => Controls_Manager::RAW_HTML,
                'raw'             => $this->premiumInfo(),
                'classes'         => $this->eventClassProInfo(),
                'content_classes' => 'elementor-descriptor',
            ) );
        }
        $this->end_controls_tab();
        $this->end_controls_tabs();
        $this->add_control( 'separator_style_middle', [
            'type' => Controls_Manager::DIVIDER,
        ] );
        $this->start_controls_tabs( 'section_general_style_tabs' );
        $this->start_controls_tab( 'section_general_likes_style_tab', [
            'label' => esc_html__( 'Likes', 'inavii-social-feed-e' ),
        ] );
        if ( !VersionChecker::version()->can_use_premium_code() ) {
            $this->add_control( 'likes_pro', array(
                'type'            => Controls_Manager::RAW_HTML,
                'raw'             => $this->premiumInfo(),
                'classes'         => $this->eventClassProInfo(),
                'content_classes' => 'elementor-descriptor',
            ) );
        }
        $this->end_controls_tab();
        $this->start_controls_tab( 'section_general_comments_style_tab', [
            'label' => esc_html__( 'Comments', 'inavii-social-feed-e' ),
        ] );
        if ( !VersionChecker::version()->can_use_premium_code() ) {
            $this->add_control( 'comments_pro', array(
                'type'            => Controls_Manager::RAW_HTML,
                'raw'             => $this->premiumInfo(),
                'classes'         => $this->eventClassProInfo(),
                'content_classes' => 'elementor-descriptor',
            ) );
        }
        $this->end_controls_tab();
        $this->end_controls_tabs();
        $this->end_controls_section();
        $this->start_controls_section( 'section_header_box', array(
            'label'      => __( 'Header Box', 'inavii-social-feed-e' ),
            'tab'        => Controls_Manager::TAB_STYLE,
            'conditions' => array(
            'terms' => array( array(
            'relation' => 'or',
            'terms'    => array( array(
            'name'     => 'avatar_header_switch',
            'operator' => '==',
            'value'    => 'yes',
        ), array(
            'name'     => 'enable_header_follow_button',
            'operator' => '==',
            'value'    => 'yes',
        ), array(
            'name'     => 'user_name_header_switch',
            'operator' => '==',
            'value'    => 'yes',
        ) ),
        ) ),
        ),
        ) );
        $this->add_responsive_control( 'header_box_alignment', array(
            'label'     => __( 'Alignment', 'inavii-social-feed-e' ),
            'type'      => Controls_Manager::CHOOSE,
            'options'   => array(
            'flex-start'    => array(
            'title' => __( 'Left', 'inavii-social-feed-e' ),
            'icon'  => 'eicon-h-align-left',
        ),
            'center'        => array(
            'title' => __( 'Center', 'inavii-social-feed-e' ),
            'icon'  => 'eicon-h-align-center',
        ),
            'flex-end'      => array(
            'title' => __( 'Right', 'inavii-social-feed-e' ),
            'icon'  => 'eicon-h-align-right',
        ),
            'space-between' => array(
            'title' => __( 'Space Between', 'inavii-social-feed-e' ),
            'icon'  => 'eicon-h-align-stretch',
        ),
        ),
            'selectors' => array(
            '{{WRAPPER}} .inavii__header-container' => 'justify-content: {{VALUE}};',
        ),
        ) );
        $this->add_responsive_control( 'header_box_padding', array(
            'label'      => __( 'Padding', 'inavii-social-feed-e' ),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => array( 'px', 'em', '%' ),
            'selectors'  => array(
            '{{WRAPPER}} .inavii__header-container' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
        ),
        ) );
        $this->add_control( 'header_box_gap', array(
            'label'     => __( 'Gap', 'inavii-social-feed-e' ),
            'type'      => Controls_Manager::SLIDER,
            'range'     => array(
            'px' => array(
            'max' => 100,
        ),
        ),
            'default'   => array(
            'size' => 30,
            'unit' => 'px',
        ),
            'selectors' => array(
            '{{WRAPPER}} .inavii__header-container' => 'gap: {{SIZE}}{{UNIT}};',
        ),
        ) );
        $this->add_control( 'header_box_top_hr', [
            'type' => Controls_Manager::DIVIDER,
        ] );
        $this->start_controls_tabs( 'section_header_box_style_tabs' );
        $this->start_controls_tab( 'section_header_box_avatar_style_tab', [
            'label'      => esc_html__( 'Avatar', 'inavii-social-feed-e' ),
            'conditions' => array(
            'terms' => array( array(
            'relation' => 'or',
            'terms'    => array( array(
            'name'     => 'avatar_header_switch',
            'operator' => '==',
            'value'    => 'yes',
        ), array(
            'name'     => 'user_name_header_switch',
            'operator' => '==',
            'value'    => 'yes',
        ) ),
        ) ),
        ),
        ] );
        $this->add_responsive_control( 'header_avatar_size', array(
            'label'          => __( 'Avatar Size', 'inavii' ),
            'type'           => Controls_Manager::SLIDER,
            'default'        => array(
            'unit' => 'px',
        ),
            'tablet_default' => array(
            'unit' => 'px',
        ),
            'mobile_default' => array(
            'unit' => 'px',
        ),
            'size_units'     => array( 'px' ),
            'range'          => array(
            'px' => array(
            'min' => 1,
            'max' => 90,
        ),
        ),
            'selectors'      => array(
            '{{WRAPPER}} .inavii__header-avatar-content' => 'height: {{SIZE}}{{UNIT}}; min-height: {{SIZE}}{{UNIT}}; width: {{SIZE}}{{UNIT}};min-width: {{SIZE}}{{UNIT}};',
        ),
            'condition'      => array(
            'avatar_header_switch' => 'yes',
        ),
        ) );
        $this->add_group_control( Group_Control_Border::get_type(), array(
            'name'      => 'header_avatar_border',
            'selector'  => '{{WRAPPER}} .inavii__header-avatar-content',
            'condition' => array(
            'avatar_header_switch' => 'yes',
        ),
        ) );
        $this->add_control( 'header_avatar_spacing', array(
            'label'      => __( 'Spacing', 'inavii-social-feed-e' ),
            'type'       => Controls_Manager::SLIDER,
            'range'      => array(
            'px' => array(
            'max' => 50,
        ),
        ),
            'selectors'  => array(
            '{{WRAPPER}} .inavii__header-avatar-content' => 'margin-right: {{SIZE}}{{UNIT}};',
        ),
            'conditions' => array(
            'relation' => 'and',
            'terms'    => array( array(
            'name'     => 'avatar_header_switch',
            'operator' => '==',
            'value'    => 'yes',
        ), array(
            'name'     => 'user_name_header_switch',
            'operator' => '==',
            'value'    => 'yes',
        ) ),
        ),
        ) );
        $this->add_responsive_control( 'header_username_text_alignment', array(
            'label'     => __( 'Text Alignment', 'inavii-social-feed-e' ),
            'type'      => Controls_Manager::CHOOSE,
            'options'   => array(
            'flex-start' => array(
            'title' => __( 'Left', 'inavii-social-feed-e' ),
            'icon'  => 'eicon-h-align-left',
        ),
            'center'     => array(
            'title' => __( 'Center', 'inavii-social-feed-e' ),
            'icon'  => 'eicon-h-align-center',
        ),
            'flex-end'   => array(
            'title' => __( 'Right', 'inavii-social-feed-e' ),
            'icon'  => 'eicon-h-align-right',
        ),
        ),
            'selectors' => array(
            '{{WRAPPER}} .inavii__header-bio-box' => 'justify-content: {{VALUE}};',
        ),
        ) );
        $this->add_group_control( Group_Control_Typography::get_type(), array(
            'name'      => 'header_nickname_typography',
            'label'     => __( 'Typography', 'inavii-social-feed-e' ),
            'selector'  => '{{WRAPPER}} .inavii__header-username-text',
            'condition' => array(
            'user_name_header_switch' => 'yes',
        ),
        ) );
        $this->add_control( 'header_nickname_color', array(
            'label'     => __( 'Color', 'inavii-social-feed-e' ),
            'type'      => Controls_Manager::COLOR,
            'default'   => '',
            'selectors' => array(
            '{{WRAPPER}} .inavii__header-username-text' => 'color: {{VALUE}};',
        ),
            'condition' => array(
            'user_name_header_switch' => 'yes',
        ),
        ) );
        $this->end_controls_tab();
        $this->start_controls_tab( 'section_header_box_follow_button_style_tab', [
            'label'     => esc_html__( 'Follow Button', 'inavii-social-feed-e' ),
            'condition' => array(
            'enable_header_follow_button' => 'yes',
        ),
        ] );
        $this->add_group_control( Group_Control_Typography::get_type(), array(
            'name'     => 'follow_button_header_typography',
            'label'    => __( 'Typography', 'inavii-social-feed-e' ),
            'selector' => '{{WRAPPER}} .inavii-button__follow-instagram-button.inavii__header',
        ) );
        $this->add_responsive_control( 'follow_button_header_margin', array(
            'label'      => __( 'Margin', 'inavii-social-feed-e' ),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => array( 'px', 'em', '%' ),
            'selectors'  => array(
            '{{WRAPPER}} .inavii-button__follow-instagram-button.inavii__header' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
        ),
        ) );
        $this->add_responsive_control( 'follow_button_header_padding', array(
            'label'      => __( 'Padding', 'inavii-social-feed-e' ),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => array( 'px', 'em', '%' ),
            'selectors'  => array(
            '{{WRAPPER}} .inavii-button__follow-instagram-button.inavii__header' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
        ),
        ) );
        $this->add_control( 'follow_button_header_border_radius', array(
            'label'      => __( 'Border Radius', 'inavii-social-feed-e' ),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => array( 'px', '%' ),
            'selectors'  => array(
            '{{WRAPPER}} .inavii-button__follow-instagram-button.inavii__header' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
        ),
        ) );
        $this->add_control( 'follow_button_header_state_normal_top_hr', [
            'type' => Controls_Manager::DIVIDER,
        ] );
        $this->add_control( 'follow_button_header_state_normal', [
            'label'     => esc_html__( 'NORMAL', 'inavii-social-feed-e' ),
            'type'      => Controls_Manager::HEADING,
            'separator' => 'none',
        ] );
        $this->add_group_control( Group_Control_Box_Shadow::get_type(), array(
            'name'     => 'follow_button_header_box_shadow_normal',
            'selector' => '{{WRAPPER}} .inavii-button__follow-instagram-button.inavii__header',
        ) );
        $this->add_control( 'follow_button_header_color_normal', array(
            'label'     => __( 'Color', 'inavii-social-feed-e' ),
            'type'      => Controls_Manager::COLOR,
            'selectors' => array(
            '{{WRAPPER}} .inavii-button__follow-instagram-button.inavii__header' => 'color: {{VALUE}};',
        ),
        ) );
        $this->add_control( 'follow_button_header_background_normal', array(
            'label'     => __( 'Background Color', 'inavii-social-feed-e' ),
            'type'      => Controls_Manager::COLOR,
            'default'   => '',
            'selectors' => array(
            '{{WRAPPER}} .inavii-button__follow-instagram-button.inavii__header' => 'background-color: {{VALUE}};',
        ),
        ) );
        $this->add_group_control( Group_Control_Border::get_type(), array(
            'name'     => 'follow_button_header_border_normal',
            'selector' => '{{WRAPPER}} .inavii-button__follow-instagram-button.inavii__header',
        ) );
        $this->add_control( 'follow_button_header_state_hover_hr', [
            'type' => Controls_Manager::DIVIDER,
        ] );
        $this->add_control( 'follow_button_header_state_hover', [
            'label'     => esc_html__( 'HOVER', 'inavii-social-feed-e' ),
            'type'      => Controls_Manager::HEADING,
            'separator' => 'none',
        ] );
        $this->add_group_control( Group_Control_Box_Shadow::get_type(), array(
            'name'     => 'follow_button_header_box_shadow_hover',
            'selector' => '{{WRAPPER}} .inavii-button__follow-instagram-button.inavii__header:hover',
        ) );
        $this->add_control( 'follow_button_header_color_hover', array(
            'label'     => __( 'Color', 'inavii-social-feed-e' ),
            'type'      => Controls_Manager::COLOR,
            'default'   => '',
            'selectors' => array(
            '{{WRAPPER}} .inavii-button__follow-instagram-button.inavii__header:hover' => 'color: {{VALUE}};',
        ),
        ) );
        $this->add_control( 'follow_button_header_background_hover', array(
            'label'     => __( 'Background Color', 'inavii-social-feed-e' ),
            'type'      => Controls_Manager::COLOR,
            'default'   => '',
            'selectors' => array(
            '{{WRAPPER}} .inavii-button__follow-instagram-button.inavii__header:hover' => 'background-color: {{VALUE}};',
        ),
        ) );
        $this->add_group_control( Group_Control_Border::get_type(), array(
            'name'     => 'follow_button_header_border_hover',
            'selector' => '{{WRAPPER}} .inavii-button__follow-instagram-button.inavii__header:hover',
        ) );
        $this->add_control( 'follow_button_header_state_hover_bottom_hr', [
            'type' => Controls_Manager::DIVIDER,
        ] );
        $this->add_control( 'follow_header_separator-icon-title', [
            'label' => esc_html__( 'FOLLOW ICON', 'inavii-social-feed-e' ),
            'type'  => Controls_Manager::HEADING,
        ] );
        $this->add_control( 'header_follow_icon_button', array(
            'label'   => __( 'Icon', 'inavii-social-feed-e' ),
            'type'    => Controls_Manager::ICONS,
            'default' => array(
            'value'   => 'fab fa-instagram',
            'library' => 'fa-brands',
        ),
        ) );
        $this->add_control( 'follow_header_icon_size', array(
            'label'     => __( 'Icon Size', 'inavii-social-feed-e' ),
            'type'      => Controls_Manager::SLIDER,
            'range'     => array(
            'px' => array(
            'max' => 100,
        ),
        ),
            'selectors' => array(
            '{{WRAPPER}} .inavii-button__follow-instagram-button.inavii__header span i:before' => 'font-size: {{SIZE}}{{UNIT}};',
            '{{WRAPPER}} .inavii-button__follow-instagram-button.inavii__header span svg'      => 'height: {{SIZE}}{{UNIT}}; width: {{SIZE}}{{UNIT}};',
        ),
        ) );
        $this->add_control( 'follow_header_text_spacing', array(
            'label'     => __( 'Text spacing', 'inavii-social-feed-e' ),
            'type'      => Controls_Manager::SLIDER,
            'range'     => array(
            'px' => array(
            'max' => 100,
        ),
        ),
            'default'   => array(
            'size' => 5,
            'unit' => 'px',
        ),
            'selectors' => array(
            '{{WRAPPER}} .inavii-button__follow-instagram-button.inavii__header .inavii-button__text' => 'margin-right: {{SIZE}}{{UNIT}};',
        ),
        ) );
        $this->add_control( 'follow_header_icon_color', array(
            'label'     => __( 'Icon Color', 'inavii-social-feed-e' ),
            'type'      => Controls_Manager::COLOR,
            'selectors' => array(
            '{{WRAPPER}} .inavii-button__follow-instagram-button.inavii__header span i:before' => 'color: {{VALUE}};',
            '{{WRAPPER}} .inavii-button__follow-instagram-button.inavii__header span svg *'    => 'fill: {{VALUE}}!important;',
        ),
        ) );
        $this->end_controls_tab();
        $this->start_controls_tab( 'section_header_box_bio_style_tab', [
            'label'     => esc_html__( 'Bio', 'inavii-social-feed-e' ),
            'condition' => array(
            'bio_header_switch' => 'yes',
        ),
        ] );
        $this->add_responsive_control( 'bio_text_alignment', array(
            'label'     => __( 'Text Alignment', 'inavii-social-feed-e' ),
            'type'      => Controls_Manager::CHOOSE,
            'options'   => array(
            'left'   => array(
            'title' => __( 'Left', 'inavii-social-feed-e' ),
            'icon'  => 'eicon-h-align-left',
        ),
            'center' => array(
            'title' => __( 'Center', 'inavii-social-feed-e' ),
            'icon'  => 'eicon-h-align-center',
        ),
            'right'  => array(
            'title' => __( 'Right', 'inavii-social-feed-e' ),
            'icon'  => 'eicon-h-align-right',
        ),
        ),
            'selectors' => array(
            '{{WRAPPER}} .inavii__header-bio' => 'text-align: {{VALUE}};',
        ),
        ) );
        $this->add_group_control( Group_Control_Typography::get_type(), array(
            'name'     => 'bio_header_typography',
            'label'    => __( 'Typography', 'inavii-social-feed-e' ),
            'selector' => '{{WRAPPER}} .inavii__header-bio',
        ) );
        $this->add_control( 'bio_header_color_normal', array(
            'label'     => __( 'Color', 'inavii-social-feed-e' ),
            'type'      => Controls_Manager::COLOR,
            'selectors' => array(
            '{{WRAPPER}} .inavii__header-bio' => 'color: {{VALUE}};',
        ),
        ) );
        $this->add_responsive_control( 'bio_header_padding', array(
            'label'      => __( 'Padding', 'inavii-social-feed-e' ),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => array( 'px', 'em', '%' ),
            'selectors'  => array(
            '{{WRAPPER}} .inavii__header-bio' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
        ),
        ) );
        $this->end_controls_tab();
        $this->end_controls_tabs();
        $this->end_controls_section();
        $this->start_controls_section( 'section_modal_popup', array(
            'label'     => __( 'PopUp', 'inavii-social-feed-e' ),
            'tab'       => Controls_Manager::TAB_STYLE,
            'condition' => array(
            'post_linking' => 'popup',
        ),
        ) );
        $this->start_controls_tabs( 'section_popup_style_tabs' );
        $this->start_controls_tab( 'section_popup_overlay_style_tab', [
            'label' => esc_html__( 'Overlay', 'inavii-social-feed-e' ),
        ] );
        if ( !VersionChecker::version()->can_use_premium_code() ) {
            $this->add_control( 'popup_overlay_pro', array(
                'type'            => Controls_Manager::RAW_HTML,
                'raw'             => $this->premiumInfo(),
                'classes'         => $this->eventClassProInfo(),
                'content_classes' => 'elementor-descriptor',
            ) );
        }
        $this->end_controls_tab();
        $this->start_controls_tab( 'popup_follow_button_style_tab', [
            'label' => esc_html__( 'Follow Button', 'inavii-social-feed-e' ),
        ] );
        if ( !VersionChecker::version()->can_use_premium_code() ) {
            $this->add_control( 'popup_follow_button_pro', array(
                'type'            => Controls_Manager::RAW_HTML,
                'raw'             => $this->premiumInfo(),
                'classes'         => $this->eventClassProInfo(),
                'content_classes' => 'elementor-descriptor',
            ) );
        }
        $this->end_controls_tab();
        $this->start_controls_tab( 'popup_close_button_style_tab', [
            'label' => esc_html__( 'Close Button', 'inavii-social-feed-e' ),
        ] );
        if ( !VersionChecker::version()->can_use_premium_code() ) {
            $this->add_control( 'popup_close_button_pro', array(
                'type'            => Controls_Manager::RAW_HTML,
                'raw'             => $this->premiumInfo(),
                'classes'         => $this->eventClassProInfo(),
                'content_classes' => 'elementor-descriptor',
            ) );
        }
        $this->end_controls_tab();
        $this->end_controls_tabs();
        $this->end_controls_section();
        $this->start_controls_section( 'section_modal_lightbox', array(
            'label'     => __( 'Lightbox', 'inavii-social-feed-e' ),
            'tab'       => Controls_Manager::TAB_STYLE,
            'condition' => array(
            'post_linking' => 'lightbox',
        ),
        ) );
        $this->start_controls_tabs( 'section_lightbox_middle_style_tabs' );
        $this->start_controls_tab( 'section_lightbox_overlay_style_tab', [
            'label' => esc_html__( 'Overlay', 'inavii-social-feed-e' ),
        ] );
        if ( !VersionChecker::version()->can_use_premium_code() ) {
            $this->add_control( 'lightbox_overlay_pro', array(
                'type'            => Controls_Manager::RAW_HTML,
                'raw'             => $this->premiumInfo(),
                'classes'         => $this->eventClassProInfo(),
                'content_classes' => 'elementor-descriptor',
            ) );
        }
        $this->end_controls_tab();
        $this->start_controls_tab( 'lightbox_follow_button_style_tab', [
            'label' => esc_html__( 'Follow Button', 'inavii-social-feed-e' ),
        ] );
        if ( !VersionChecker::version()->can_use_premium_code() ) {
            $this->add_control( 'lightbox_follow_button_pro', array(
                'type'            => Controls_Manager::RAW_HTML,
                'raw'             => $this->premiumInfo(),
                'classes'         => $this->eventClassProInfo(),
                'content_classes' => 'elementor-descriptor',
            ) );
        }
        $this->end_controls_tab();
        $this->start_controls_tab( 'lightbox_close_button_style_tab', [
            'label' => esc_html__( 'Close Button', 'inavii-social-feed-e' ),
        ] );
        if ( !VersionChecker::version()->can_use_premium_code() ) {
            $this->add_control( 'lightbox_close_button_pro', array(
                'type'            => Controls_Manager::RAW_HTML,
                'raw'             => $this->premiumInfo(),
                'classes'         => $this->eventClassProInfo(),
                'content_classes' => 'elementor-descriptor',
            ) );
        }
        $this->end_controls_tab();
        $this->end_controls_tabs();
        $this->add_control( 'section_modal_lightbox_middle_hr', [
            'type' => Controls_Manager::DIVIDER,
        ] );
        $this->start_controls_tabs( 'section_lightbox_top_style_tabs' );
        $this->start_controls_tab( 'section_lightbox_avatar_style_tab', [
            'label' => esc_html__( 'Avatar', 'inavii-social-feed-e' ),
        ] );
        if ( !VersionChecker::version()->can_use_premium_code() ) {
            $this->add_control( 'lightbox_avatar_pro', array(
                'type'            => Controls_Manager::RAW_HTML,
                'raw'             => $this->premiumInfo(),
                'classes'         => $this->eventClassProInfo(),
                'content_classes' => 'elementor-descriptor',
            ) );
        }
        $this->end_controls_tab();
        $this->start_controls_tab( 'section_lightbox_description_style_tab', [
            'label' => esc_html__( 'Description', 'inavii-social-feed-e' ),
        ] );
        if ( !VersionChecker::version()->can_use_premium_code() ) {
            $this->add_control( 'lightbox_description_pro', array(
                'type'            => Controls_Manager::RAW_HTML,
                'raw'             => $this->premiumInfo(),
                'classes'         => $this->eventClassProInfo(),
                'content_classes' => 'elementor-descriptor',
            ) );
        }
        $this->end_controls_tab();
        $this->end_controls_tabs();
        $this->add_control( 'section_modal_lightbox_hr', [
            'type' => Controls_Manager::DIVIDER,
        ] );
        $this->start_controls_tabs( 'section_lightbox_style_tabs' );
        $this->start_controls_tab( 'section_lightbox_likes_style_tab', [
            'label' => esc_html__( 'Likes', 'inavii-social-feed-e' ),
        ] );
        if ( !VersionChecker::version()->can_use_premium_code() ) {
            $this->add_control( 'lightbox_likes_pro', array(
                'type'            => Controls_Manager::RAW_HTML,
                'raw'             => $this->premiumInfo(),
                'classes'         => $this->eventClassProInfo(),
                'content_classes' => 'elementor-descriptor',
            ) );
        }
        $this->end_controls_tab();
        $this->start_controls_tab( 'section_lightbox_comments_style_tab', [
            'label' => esc_html__( 'Comments', 'inavii-social-feed-e' ),
        ] );
        if ( !VersionChecker::version()->can_use_premium_code() ) {
            $this->add_control( 'lightbox_comments_pro', array(
                'type'            => Controls_Manager::RAW_HTML,
                'raw'             => $this->premiumInfo(),
                'classes'         => $this->eventClassProInfo(),
                'content_classes' => 'elementor-descriptor',
            ) );
        }
        $this->end_controls_tab();
        $this->start_controls_tab( 'section_lightbox_date_style_tab', [
            'label' => esc_html__( 'Date', 'inavii-social-feed-e' ),
        ] );
        if ( !VersionChecker::version()->can_use_premium_code() ) {
            $this->add_control( 'lightbox_date_pro', array(
                'type'            => Controls_Manager::RAW_HTML,
                'raw'             => $this->premiumInfo(),
                'classes'         => $this->eventClassProInfo(),
                'content_classes' => 'elementor-descriptor',
            ) );
        }
        $this->end_controls_tab();
        $this->end_controls_tabs();
        $this->end_controls_section();
        $this->start_controls_section( 'section_style_footer_box', array(
            'label'     => __( 'Footer Box', 'inavii-social-feed-e' ),
            'tab'       => Controls_Manager::TAB_STYLE,
            'condition' => array(
            'enable_follow_button' => 'yes',
        ),
        ) );
        $this->add_responsive_control( 'button_box_alignment', array(
            'label'     => __( 'Alignment', 'inavii-social-feed-e' ),
            'type'      => Controls_Manager::CHOOSE,
            'options'   => array(
            'flex-start' => array(
            'title' => __( 'Left', 'inavii-social-feed-e' ),
            'icon'  => 'eicon-h-align-left',
        ),
            'center'     => array(
            'title' => __( 'Center', 'inavii-social-feed-e' ),
            'icon'  => 'eicon-h-align-center',
        ),
            'flex-end'   => array(
            'title' => __( 'Right', 'inavii-social-feed-e' ),
            'icon'  => 'eicon-h-align-right',
        ),
        ),
            'default'   => 'center',
            'selectors' => array(
            '{{WRAPPER}} .inavii-button__box' => 'justify-content: {{VALUE}};',
        ),
        ) );
        $this->add_responsive_control( 'box_buttons_margin', array(
            'label'      => __( 'Margin', 'inavii-social-feed-e' ),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => array( 'px', 'em', '%' ),
            'selectors'  => array(
            '{{WRAPPER}} .inavii-button__box' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
        ),
        ) );
        $this->add_responsive_control( 'box_buttons_padding', array(
            'label'      => __( 'Padding', 'inavii-social-feed-e' ),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => array( 'px', 'em', '%' ),
            'selectors'  => array(
            '{{WRAPPER}} .inavii-button__box' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
        ),
        ) );
        $this->add_control( 'footer_box_top_hr', [
            'type' => Controls_Manager::DIVIDER,
        ] );
        $this->start_controls_tabs( 'section_follow_buttons_style_tabs' );
        $this->start_controls_tab( 'section_follow_buttons_image_style_tab', [
            'label'     => esc_html__( 'Follow Button', 'inavii-social-feed-e' ),
            'condition' => array(
            'enable_follow_button' => 'yes',
        ),
        ] );
        $this->add_group_control( Group_Control_Typography::get_type(), array(
            'name'     => 'follow_button_typography',
            'label'    => __( 'Typography', 'inavii-social-feed-e' ),
            'scheme'   => Typography::TYPOGRAPHY_1,
            'selector' => '{{WRAPPER}} .inavii-button__follow-instagram-button:not(.inavii__header)',
        ) );
        $this->add_responsive_control( 'follow_button_margin', array(
            'label'      => __( 'Margin', 'inavii-social-feed-e' ),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => array( 'px', 'em', '%' ),
            'selectors'  => array(
            '{{WRAPPER}} .inavii-button__follow-instagram-button:not(.inavii__header)' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
        ),
        ) );
        $this->add_responsive_control( 'follow_button_padding', array(
            'label'      => __( 'Padding', 'inavii-social-feed-e' ),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => array( 'px', 'em', '%' ),
            'selectors'  => array(
            '{{WRAPPER}} .inavii-button__follow-instagram-button:not(.inavii__header)' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
        ),
        ) );
        $this->add_control( 'follow_button_border_radius', array(
            'label'      => __( 'Border Radius', 'inavii-social-feed-e' ),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => array( 'px', '%' ),
            'selectors'  => array(
            '{{WRAPPER}} .inavii-button__follow-instagram-button:not(.inavii__header)' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
        ),
        ) );
        $this->add_control( 'follow_button_state_normal_top_hr', [
            'type' => Controls_Manager::DIVIDER,
        ] );
        $this->add_control( 'follow_button_state_normal', [
            'label'     => esc_html__( 'NORMAL', 'inavii-social-feed-e' ),
            'type'      => Controls_Manager::HEADING,
            'separator' => 'none',
        ] );
        $this->add_group_control( Group_Control_Box_Shadow::get_type(), array(
            'name'     => 'follow_button_box_shadow_normal',
            'selector' => '{{WRAPPER}} .inavii-button__follow-instagram-button:not(.inavii__header)',
        ) );
        $this->add_control( 'follow_button_color_normal', array(
            'label'     => __( 'Color', 'inavii-social-feed-e' ),
            'type'      => Controls_Manager::COLOR,
            'selectors' => array(
            '{{WRAPPER}} .inavii-button__follow-instagram-button:not(.inavii__header)' => 'color: {{VALUE}};',
        ),
        ) );
        $this->add_control( 'follow_button_background_normal', array(
            'label'     => __( 'Background Color', 'inavii-social-feed-e' ),
            'type'      => Controls_Manager::COLOR,
            'default'   => '',
            'selectors' => array(
            '{{WRAPPER}} .inavii-button__follow-instagram-button:not(.inavii__header)' => 'background-color: {{VALUE}};',
        ),
        ) );
        $this->add_group_control( Group_Control_Border::get_type(), array(
            'name'     => 'follow_button_border_normal',
            'selector' => '{{WRAPPER}} .inavii-button__follow-instagram-button:not(.inavii__header)',
        ) );
        $this->add_control( 'follow_button_state_hover_top_hr', [
            'type' => Controls_Manager::DIVIDER,
        ] );
        $this->add_control( 'follow_button_state_hover', [
            'label'     => esc_html__( 'HOVER', 'inavii-social-feed-e' ),
            'type'      => Controls_Manager::HEADING,
            'separator' => 'none',
        ] );
        $this->add_group_control( Group_Control_Box_Shadow::get_type(), array(
            'name'     => 'follow_button_box_shadow_hover',
            'selector' => '{{WRAPPER}} .inavii-button__follow-instagram-button:not(.inavii__header):hover',
        ) );
        $this->add_control( 'follow_button_color_hover', array(
            'label'     => __( 'Color', 'inavii-social-feed-e' ),
            'type'      => Controls_Manager::COLOR,
            'default'   => '',
            'selectors' => array(
            '{{WRAPPER}} .inavii-button__follow-instagram-button:not(.inavii__header):hover' => 'color: {{VALUE}};',
        ),
        ) );
        $this->add_control( 'follow_button_background_hover', array(
            'label'     => __( 'Background Color', 'inavii-social-feed-e' ),
            'type'      => Controls_Manager::COLOR,
            'default'   => '',
            'selectors' => array(
            '{{WRAPPER}} .inavii-button__follow-instagram-button:not(.inavii__header):hover' => 'background-color: {{VALUE}};',
        ),
        ) );
        $this->add_group_control( Group_Control_Border::get_type(), array(
            'name'     => 'follow_button_border_hover',
            'selector' => '{{WRAPPER}} .inavii-button__follow-instagram-button:not(.inavii__header):hover',
        ) );
        $this->add_control( 'follow_button_state_hover_bottom_hr', [
            'type' => Controls_Manager::DIVIDER,
        ] );
        $this->add_control( 'follow_separator-icon-title', [
            'label' => esc_html__( 'FOLLOW ICON', 'inavii-social-feed-e' ),
            'type'  => Controls_Manager::HEADING,
        ] );
        $this->add_control( 'follow_icon_button', array(
            'label'   => __( 'Icon', 'inavii-social-feed-e' ),
            'type'    => Controls_Manager::ICONS,
            'default' => array(
            'value'   => 'fab fa-instagram',
            'library' => 'fa-brands',
        ),
        ) );
        $this->add_control( 'follow_icon_size', array(
            'label'     => __( 'Icon Size', 'inavii-social-feed-e' ),
            'type'      => Controls_Manager::SLIDER,
            'range'     => array(
            'px' => array(
            'max' => 100,
        ),
        ),
            'selectors' => array(
            '{{WRAPPER}} .inavii-button__follow-instagram-button:not(.inavii__header) span i:before' => 'font-size: {{SIZE}}{{UNIT}};',
            '{{WRAPPER}} .inavii-button__follow-instagram-button:not(.inavii__header) span svg'      => 'height: {{SIZE}}{{UNIT}}; width: {{SIZE}}{{UNIT}};',
        ),
        ) );
        $this->add_control( 'follow_text_spacing', array(
            'label'     => __( 'Text spacing', 'inavii-social-feed-e' ),
            'type'      => Controls_Manager::SLIDER,
            'range'     => array(
            'px' => array(
            'max' => 100,
        ),
        ),
            'default'   => array(
            'size' => 5,
            'unit' => 'px',
        ),
            'selectors' => array(
            '{{WRAPPER}} .inavii-button__follow-instagram-button:not(.inavii__header) .inavii-button__text' => 'margin-right: {{SIZE}}{{UNIT}};',
        ),
        ) );
        $this->add_control( 'follow_icon_color', array(
            'label'     => __( 'Icon Color', 'inavii-social-feed-e' ),
            'type'      => Controls_Manager::COLOR,
            'selectors' => array(
            '{{WRAPPER}} .inavii-button__follow-instagram-button:not(.inavii__header) span i:before' => 'color: {{VALUE}};',
            '{{WRAPPER}} .inavii-button__follow-instagram-button:not(.inavii__header) span svg *'    => 'fill: {{VALUE}}!important;',
        ),
        ) );
        $this->end_controls_tab();
        $this->end_controls_tabs();
        $this->end_controls_section();
    }
    
    protected function premiumInfo() : string
    {
        return __( '<a href="./admin.php?page=inavii-instagram-settings-pricing&trial=true" target="_blank">Available in the <span class="inavii-pro__info--pro">PRO.</span> Try for free for 14 days.</a>', 'inavii-social-feed-e' );
    }
    
    protected function icon( $icon ) : array
    {
        if ( isset( $icon['value']['id'] ) ) {
            return [
                'inline_svg' => $this->get_inline_svg( $icon['value']['id'] ),
            ];
        }
        return [
            'icon_class' => $icon['value'] ?? '',
        ];
    }
    
    protected function get_inline_svg( $attachment_id ) : string
    {
        $svg = get_post_meta( $attachment_id, '_elementor_inline_svg', true );
        if ( !empty($svg) ) {
            return $svg;
        }
        $attachment_file = get_attached_file( $attachment_id );
        if ( !$attachment_file ) {
            return '';
        }
        $svg = file_get_contents( $attachment_file );
        if ( !empty($svg) ) {
            update_post_meta( $attachment_id, '_elementor_inline_svg', $svg );
        }
        return $svg;
    }
    
    protected static function settingsPageLink() : string
    {
        return add_query_arg( array(
            'page' => 'inavii-instagram-settings',
        ), esc_url( admin_url( 'admin.php' ) ) );
    }
    
    protected function feeds_contains( $haystack, $needle ) : bool
    {
        $layout = explode( ':', $haystack );
        return ( isset( $layout[1] ) ? $layout[1] === $needle : false );
    }
    
    protected function get_feed_by_condition( $condition ) : array
    {
        return array_filter( array_keys( $this->allFeeds ), function ( $value ) use( $condition ) {
            return $this->feeds_contains( $value, $condition );
        } );
    }
    
    protected static function eventClassPro() : string
    {
        return ( !VersionChecker::version()->can_use_premium_code() ? 'inavii-pro__field' : '' );
    }
    
    protected static function eventClassProInfo() : string
    {
        return ( !VersionChecker::version()->can_use_premium_code() ? 'inavii-pro__info' : '' );
    }
    
    protected static function defaultValueYesNo() : string
    {
        return ( VersionChecker::version()->can_use_premium_code() ? 'yes' : 'no' );
    }
    
    protected static function imageClickActions() : string
    {
        return ( VersionChecker::version()->can_use_premium_code() ? 'lightbox' : 'popup' );
    }

}