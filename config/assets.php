<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}
add_action('wp_enqueue_scripts', 'understrap_remove_scripts', 20);
add_action('wp_enqueue_scripts', 'theme_enqueue_styles');

/**
 * Remove parent assets
 */
function understrap_remove_scripts()
{
    wp_dequeue_style('understrap-styles');
    wp_deregister_style('understrap-styles');
    wp_dequeue_script('understrap-scripts');
    wp_deregister_script('understrap-scripts');
}

/**
 * Add Theme assets
 */
function theme_enqueue_styles()
{
    // Get the theme data
    foreach (glob(get_stylesheet_directory() . '/css/*.css') as $file) {
        // $file contains the name and extension of the file
        wp_enqueue_style($file, get_stylesheet_directory_uri() . '/css/' . basename($file), '', null);
    }
    foreach (glob(get_stylesheet_directory() . '/js/*.js') as $file) {
        // $file contains the name and extension of the file
        wp_enqueue_script($file, get_stylesheet_directory_uri() . '/js/' . basename($file), '', null);
        wp_localize_script($file, 'ajaxurl', '//' . $_SERVER['HTTP_HOST'] . '/wp-admin/admin-ajax.php');
    }
    if (is_singular() && comments_open() && get_option('thread_comments')) {
        wp_enqueue_script('comment-reply');
    }
    foreach (glob(get_stylesheet_directory() . '/dist/*') as $file) {
        // $file contains the name and extension of the file
        if (endsWith($file, 'js')) {
            wp_enqueue_script($file, get_stylesheet_directory_uri() . '/dist/' . basename($file), '', null);
        } else if (endsWith($file, 'css')) {
            wp_enqueue_style($file, get_stylesheet_directory_uri() . '/dist/' . basename($file), '', null);
        }

    }
}

