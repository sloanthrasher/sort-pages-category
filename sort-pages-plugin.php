<?php
/*
	Plugin Name: Sort Pages with Categories
	Description: A very simple plugin to organize pages with drag-and-drop and assign categories. Use if your theme does not support setting the order of pages.
	Version: 1.0
	Author: Sloan Thrasher
	License: GPL v2 or later
	License URI: http://www.gnu.org/licenses/gpl-2.0.html
*/

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

// Enqueue necessary scripts and styles.
function spp_enqueue_scripts($hook_suffix) {
    if ( $hook_suffix !== 'appearance_page_sort-pages' ) {
        return;
    }
    wp_enqueue_style( 'spp-styles', plugin_dir_url( __FILE__ ) . 'css/styles.css' );
    wp_enqueue_script( 'jquery-ui-sortable' );
    wp_enqueue_script( 'spp-scripts', plugin_dir_url( __FILE__ ) . 'js/scripts.js', array( 'jquery' ), null, true );
    wp_localize_script( 'spp-scripts', 'sppRestApi', array(
        'root'  => esc_url_raw( rest_url() ),
        'nonce' => wp_create_nonce( 'wp_rest' ),
    ));
}
add_action( 'admin_enqueue_scripts', 'spp_enqueue_scripts' );

// Add menu item under Appearance
function spp_add_menu_item() {
    add_submenu_page(
        'themes.php',
        'Sort And Categorize Pages',
        'Sort And Categorize Pages',
        'manage_options',
        'sort-pages',
        'spp_display_admin_page'
    );
}
add_action( 'admin_menu', 'spp_add_menu_item' );

// Display admin page
function spp_display_admin_page() {
    ?>
    <div class="wrap">
        <h1>Set Page Order, Set Page Categories</h1>
        <table id="the-list" class="widefat fixed">
            <thead>
                <tr>
                    <th><?php _e( 'Order', 'spp_textdomain' ); ?></th>
                    <th><?php _e( 'Title', 'spp_textdomain' ); ?></th>
                    <th><?php _e( 'Category', 'spp_textdomain' ); ?></th>
                </tr>
            </thead>
            <tbody>
                <?php
                $pages = get_pages( array( 'sort_column' => 'menu_order' ) );
                $categories = get_categories( array( 'hide_empty' => false ) );
                foreach ( $pages as $page ) {
                    $order = $page->menu_order;
                    $category = get_post_meta( $page->ID, '_page_category', true );
                    echo '<tr data-id="' . esc_attr( $page->ID ) . '">';
                    echo '<td class="order-number">' . esc_html( $order ) . '</td>';
                    echo '<td class="page-title">' . esc_html( $page->post_title ) . '</td>';
                    echo '<td>';
                    echo '<select class="category-selector">';
                    echo '<option value="">' . __( 'Select Category', 'spp_textdomain' ) . '</option>';
                    foreach ( $categories as $cat ) {
                        $selected = ( $category == $cat->term_id ) ? 'selected' : '';
                        echo '<option value="' . esc_attr( $cat->term_id ) . '" ' . $selected . '>' . esc_html( $cat->name ) . '</option>';
                    }
                    echo '</select>';
                    echo '</td>';
                    echo '</tr>';
                }
                ?>
            </tbody>
        </table>
        <button id="save-changes" class="button button-primary"><?php _e( 'Save Changes', 'spp_textdomain' ); ?></button>
    </div>
    <?php
}

// Register REST API routes
function spp_register_rest_routes() {
    register_rest_route( 'spp/v1', '/save-order-and-category', array(
        'methods'  => 'POST',
        'callback' => 'spp_save_order_and_category',
        'permission_callback' => function () {
            return current_user_can( 'edit_pages' );
        },
    ));
}
add_action( 'rest_api_init', 'spp_register_rest_routes' );

function spp_save_order_and_category( WP_REST_Request $request ) {
    $params = $request->get_json_params();
    $order = $params['order'];
    $categories = $params['categories'];

    foreach ( $order as $index => $page_id ) {
		error_log("Page ID: " . $page_id . " Index: " . $index);
        wp_update_post( array(
            'ID' => $page_id,
            'menu_order' => $index + 1
        ));
    }

    foreach ( $categories as $page_id => $category_id ) {
        update_post_meta( $page_id, '_page_category', sanitize_text_field( $category_id ) );
    }

    return new WP_REST_Response( array( 'status' => 'success' ), 200 );
}