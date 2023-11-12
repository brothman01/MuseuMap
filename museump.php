<?php
/**
* Museum Map
*
* @category  WordPressPlugin
* @package   museumap
* @author    brothman01
* @copyright 2023
* @license   GPL-2.0+ https://www.gnu.org/licenses/old-licenses/gpl-2.0.en.html
*
* @wordpress-plugin
* Plugin Name:  MuseuMap
* Plugin URI:  https://www.benrothman.org
* Description: Just a simple WordPress plugin to display a museum map that can be managed from the dashboard.
* Version:     1.0.0
* Author:      Ben ROthman
* Author URI:  https://www.benrothman.org
* Text Domain: museumap
* License:     GPL-2.0+
**/

// Prevent direct file access.
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/* Require Composer autoloader */
require_once dirname( __FILE__ ) . '/vendor/autoload.php';

// Register Custom Post Type
function mm_map_cpt() {

    $labels = array(
        'name'                  => _x( 'Maps', 'Post Type General Name', 'text_domain' ),
        'singular_name'         => _x( 'Map', 'Post Type Singular Name', 'text_domain' ),
        'menu_name'             => __( 'Museum Maps', 'text_domain' ),
        'name_admin_bar'        => __( 'Museum Maps', 'text_domain' ),
        'archives'              => __( 'Map Archives', 'text_domain' ),
        'attributes'            => __( 'Map Attributes', 'text_domain' ),
        'parent_item_colon'     => __( 'Parent Map:', 'text_domain' ),
        'all_items'             => __( 'All Maps', 'text_domain' ),
        'add_new_item'          => __( 'Add New Map', 'text_domain' ),
        'add_new'               => __( 'Add New', 'text_domain' ),
        'new_item'              => __( 'New Map', 'text_domain' ),
        'edit_item'             => __( 'Edit Map', 'text_domain' ),
        'update_item'           => __( 'Update Map', 'text_domain' ),
        'view_item'             => __( 'View Map', 'text_domain' ),
        'view_items'            => __( 'View Maps', 'text_domain' ),
        'search_items'          => __( 'Search Map', 'text_domain' ),
        'not_found'             => __( 'Not found', 'text_domain' ),
        'not_found_in_trash'    => __( 'Not found in Trash', 'text_domain' ),
        'featured_image'        => __( 'Featured Image', 'text_domain' ),
        'set_featured_image'    => __( 'Set featured image', 'text_domain' ),
        'remove_featured_image' => __( 'Remove featured image', 'text_domain' ),
        'use_featured_image'    => __( 'Use as featured image', 'text_domain' ),
        'insert_into_item'      => __( 'Insert into item', 'text_domain' ),
        'uploaded_to_this_item' => __( 'Uploaded to this map', 'text_domain' ),
        'items_list'            => __( 'Maps list', 'text_domain' ),
        'items_list_navigation' => __( 'Maps list navigation', 'text_domain' ),
        'filter_items_list'     => __( 'Filter maps list', 'text_domain' ),
    );
    $args = array(
        'label'                 => __( 'Map', 'text_domain' ),
        'description'           => __( 'A Map of a Floor', 'text_domain' ),
        'labels'                => $labels,
        'supports'              => array( 'title' ),
        'taxonomies'            => array( 'category', 'post_tag' ),
        'hierarchical'          => false,
        'public'                => true,
        'show_ui'               => true,
        'show_in_menu'          => true,
        'menu_position'         => 5,
        'show_in_admin_bar'     => true,
        'show_in_nav_menus'     => true,
        'can_export'            => true,
        'has_archive'           => true,
        'exclude_from_search'   => false,
        'publicly_queryable'    => true,
        'capability_type'       => 'page',
        'menu_icon'             => 'dashicons-location-alt',
    );
    register_post_type( 'mod_map', $args );

}
add_action( 'init', 'mm_map_cpt', 0 );



		// load and start CMB2.
		if ( file_exists( dirname( __FILE__ ) . '/vendor/cmb2/init.php' ) ) {
			require_once dirname( __FILE__ ) . '/vendor/cmb2/init.php';
		} elseif ( file_exists( dirname( __FILE__ ) . '/vendor/CMB2/init.php' ) ) {
			require_once dirname( __FILE__ ) . '/vendor/CMB2/init.php';
		}

// add fields via CMB2 to map CPT
function mm_register_map_metabox__fields() {
    $prefix = 'chatpress_channel_';

    $cmb_demo = new_cmb2_box( [
        'id'            => $prefix . 'metabox',
        'title'         => esc_html__( 'ChatPress', 'cmb2' ),
        'object_types'  => [ 'mod_map' ], // Post type.
    ] );

        $cmb_demo->add_field( [
            'name'       => esc_html__( 'Display Name', 'cmb2' ),
            'desc'       => esc_html__( ' ', 'cmb2' ),
            'id'         => $prefix . 'display_name',
            'type'       => 'text',
        ] );

    $cmb_demo->add_field( [
        'name' => esc_html__( 'Color', 'cmb2' ),
        'desc' => esc_html__( 'The color of the pin and the title.', 'cmb2' ),
        'id'   => $prefix . 'color',
        'type' => 'colorpicker',
    ] );

    $cmb_demo->add_field( [
        'name' => esc_html__( 'Coordinates', 'cmb2' ),
        'desc' => esc_html__( 'Enter in format (X,Y)', 'cmb2' ),
        'id'   => $prefix . 'coordinates',
        'type' => 'text_small',
    ] );

}

add_action( 'cmb2_admin_init', 'mm_register_map_metabox__fields' );


    /**
	* Add the block to the WordPress block editor
	*
	* @since 1.0
	*/
	function mm_create_block() {

		register_block_type( __DIR__ . '/wordpress-block/build' );

	}
    add_action( 'init', 'mm_create_block' );


    /**
	* Enqueue the block to the fronmt end after react has been loaded.
	*
	* @since 1.0
	*/
    function mm_create_block_enqueue_styles() {

		// enqueue the react to be used on the front end.

		wp_register_script( 'index', plugin_dir_url( __FILE__ ) . 'wordpress-block/build/index.js', array( 'wp-element' ), '1.0.0', true );
		wp_enqueue_script( 'index' );

		// enqueue bootstrap.
		wp_enqueue_style( 'bootstrap', 'https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta/css/bootstrap.min.css', array(), '1.0.0' );
	}

    add_action( 'wp_enqueue_scripts', 'mm_create_block_enqueue_styles' );
