<?php
/*
	Plugin Name: Sort Pages, Set Categories
	Short Description: Organize pages with drag-and-drop and set categories.
	Description: A very simple plugin to organize pages with drag-and-drop and set categories. Use if your theme does not support setting the order of pages.
	Version: 1.0
	Stable Tag: 1.0
	Author: Sloan Thrasher
	Author URI: https://sloansweb.com/page-4/
	License: GPLv3 or later
	License URI: http://www.gnu.org/licenses/gpl-3.0.html
*/

if (!defined('ABSPATH')) {
	exit; // Exit if accessed directly.
}

/**
 * Enqueue necessary scripts and styles for the Sort Pages, Set Categories admin page.
 *
 * @param string $hook_suffix The current admin page.
 */
function spp_enqueue_scripts($hook_suffix) {
	// Only enqueue scripts and styles on the Sort and Categorize Pages admin page.
	if ($hook_suffix !== 'appearance_page_sort-pages') {
		return;
	}

	$ver = '1.0';

	// Enqueue the plugin's stylesheet.
	wp_enqueue_style(
		'spp-styles',
		plugin_dir_url(__FILE__) . 'css/styles.css',
		array(),
		$ver
	);

	// Enqueue jQuery UI Sortable for drag-and-drop functionality.
	wp_enqueue_script(
		'jquery-ui-sortable',
		'https://sloansweb.com/wp-includes/js/jquery/ui/sortable.min.js?ver=1.13.2',
		array('jquery'),
		$ver,
		true
	);

	// Enqueue the plugin's JavaScript file.
	wp_enqueue_script(
		'spp-scripts',
		plugin_dir_url(__FILE__) . 'js/scripts.js',
		array('jquery'),
		$ver,
		true
	);

	// Localize the JavaScript file with REST API root and nonce.
	wp_localize_script(
		'spp-scripts',
		'sppRestApi',
		array(
			'root'  => esc_url_raw(rest_url()),
			'nonce' => wp_create_nonce('wp_rest'),
		)
	);
}
add_action('admin_enqueue_scripts', 'spp_enqueue_scripts');

/**
 * Adds a menu item under Appearance for the Sort and Categorize Pages admin page.
 *
 * This function is hooked to the 'admin_menu' action.
 */
function spp_add_menu_item() {
	// Add a submenu page under Appearance.
	// The submenu page is titled "Sort And Categorize Pages".
	// The capability required to access this page is 'manage_options'.
	// The menu slug is 'sort-pages'.
	// The function to display the admin page is 'spp_display_admin_page'.
	add_submenu_page(
		'themes.php', // The parent menu slug.
		'Sort And Categorize Pages', // The page title.
		'Sort And Categorize Pages', // The menu title.
		'manage_options', // The required capability.
		'sort-pages', // The menu slug.
		'spp_display_admin_page' // The function to display the admin page.
	);
}
add_action('admin_menu', 'spp_add_menu_item');

/**
 * Display admin page for sorting and categorizing pages.
 *
 * This function generates the admin page for sorting and categorizing pages.
 * It displays a table of all pages with their current order and category.
 * The user can drag and drop the pages to change their order.
 * The user can also select a category for each page.
 *
 * @return void
 */
function spp_display_admin_page() {
	// Get hierarchical categories options for the select dropdown
	$opts = get_hierarchical_categories_options(array(0));

	// Start the admin page HTML
	?>
	<div class="wrap">
		<div class="spp-header">
			<div class="shove-left">
				<h1><b></b>Set Page Order &amp; Assign Categories to Pages</b></h1>
				<h3>Drag and drop page sorting, assign category to page.</h3>
				<h4>Be sure to click the Save button when done.</h4>
			</div>
			<div class="shove-right">
				<p>This is Donate-Ware. No payment requuired for full features, there is no <i>"Pro"</i> version with extra features, all are included!<br />Use on as many sites as you want, and use forever!</p>
				<p>But, if you find it useful, and want to encourage me to write other plug-ins, it wold be appreciated if you would considering making a donation.</p>
			</div>
		</div>
		
		<!-- Display the table of pages -->
		<table id="the-list" class="widefat fixed">
			<thead>
				<tr>
					<th><?php esc_html_e('Order', 'spp_textdomain'); ?></th>
					<th><?php esc_html_e('Page Title', 'spp_textdomain'); ?></th>
					<th><?php esc_html_e('Category', 'spp_textdomain'); ?></th>
				</tr>
			</thead>
			<tbody>
				<?php


				// Get all pages sorted by menu order
				$pages = get_pages(array('sort_column' => 'menu_order'));
				$order = 0;
				foreach ($pages as $page) {

					$page_categories = wp_get_post_terms($page->ID, 'category', array('fields' => 'ids'));
					echo '<tr data-id="' . esc_attr($page->ID) . '">';
					echo '<td class="order-number">' . esc_html($order) . '</td>';
					echo '<td class="page-title">' . esc_html($page->post_title) . '</td>';
					echo '<td>';
					echo '<select class="category-selector" autocomplete="off">';
					echo "<option value='0'>None Selected</option>";
					

					foreach ($opts as $opt) {
						$selected = in_array(esc_html($opt['id']), $page_categories) ? ' selected' : '';
						$indent = "├" . str_repeat('─', esc_html($opt['level']) * 4);
						echo '<option value="' . esc_attr($opt['id']) . '"' . esc_attr($selected) . '>' . esc_html($indent . $opt['name']) . '</option>';
					}
					echo '</select>';
					echo '</td>';
					echo '</tr>';
					$order++;
				}
				?>
			</tbody>
		</table>
		
		<!-- Add the Save Changes button -->
		<button id="save-changes" class="button button-primary"><?php esc_html_e('Save Changes', 'spp_textdomain'); ?></button>
	</div>
	<?php
}

/**
 * Get hierarchical categories options.
 *
 * This function retrieves all categories and organizes them in a hierarchical structure.
 * The resulting array contains the categories with their parent and child categories.
 *
 * @param array $selected_categories The array of selected category IDs.
 * @return array The hierarchical categories options.
 */
function get_hierarchical_categories_options($selected_categories = array()) {
	// Get all categories
	$categories = get_categories(array(
		'hide_empty' => false, // Include empty categories
		'orderby' => 'name', // Order by name
		'order' => 'ASC' // Ascending order
	));

	// Build a nested array of categories by parent
	$categories_by_parent = array();
	foreach ($categories as $category) {
		$categories_by_parent[$category->parent][] = $category;
	}

	/**
	 * Recursive function to generate the hierarchical list.
	 *
	 * @param int $parent_id The parent category ID.
	 * @param array $categories_by_parent The nested array of categories by parent.
	 * @param int $level The level of the category.
	 * @param array $selected_categories The array of selected category IDs.
	 * @return array The options for the hierarchical list.
	 */
	function build_category_options($parent_id, $categories_by_parent, $level = 0, $selected_categories = array()) {
		$options = array();
		if (!isset($categories_by_parent[$parent_id])) {
			return $options;
		}
		foreach ($categories_by_parent[$parent_id] as $category) {
			$options[] = array(
				'id' => $category->term_id, // Category ID
				'name' => $category->name, // Category name
				'level' => $level, // Category level
				'selected' => in_array($category->term_id, $selected_categories) ? 1 : 0 // Selected status
			);
			$options = array_merge(
				$options,
				build_category_options($category->term_id, $categories_by_parent, $level + 1, $selected_categories)
			);
		}
		return $options;
	}

	// Generate the hierarchical list starting from the root (parent_id = 0)
	$hierarchical_categories = build_category_options(0, $categories_by_parent, 0, $selected_categories);
	return $hierarchical_categories;
}

// Assign category to page
function assign_category_to_page($page_id, $category_ids) {
	if (!is_array($category_ids) || empty($category_ids)) {
		return;
	}
	$cat_ids = $category_ids[$page_id];
	$result = wp_set_post_terms($page_id, $cat_ids, 'category');
	if (is_wp_error($result)) {
		error_log("assign_category_to_page: Error setting page categories for page ID $page_id: " . $result->get_error_message());
	} else {
//		error_log("assign_category_to_page: Page categories set successfully for page ID $page_id: " . print_r($cat_ids, true));
	}

	return $result;
}

/**
 * Registers a custom taxonomy for pages.
 *
 * Registers the 'category' taxonomy for the 'page' post type.
 * This allows for the creation of hierarchical categories for pages.
 *
 * @return void
 */
function register_page_categories() {
	// Register the 'category' taxonomy
	register_taxonomy(
		'category',	// Taxonomy name
		'page',		// Object type (post type)
		array(
			'label'		=> __('Page Categories'),  // Display name for the taxonomy
			'rewrite'	  => array('slug' => 'page-category'),  // Custom slug for the taxonomy URL
			'hierarchical' => true,  // Enable hierarchical terms
		)
	);
}

add_action('init', 'register_page_categories');

// Register REST API routes
function spp_register_rest_routes() {
	register_rest_route('spp/v1', '/save-order-and-category', array(
		'methods'  => 'POST',
		'callback' => 'spp_saveOrderAndCategory',
		'permission_callback' => function () {
			return current_user_can('edit_pages');
		},
	));
}
add_action('rest_api_init', 'spp_register_rest_routes');

// Save order and category via REST API
function spp_saveOrderAndCategory(WP_REST_Request $request) {
	$params = $request->get_json_params();
	$order = $params['order'];
	$categories = $params['categories'];

	foreach ($order as $index => $pageId) {
		wp_update_post(array(
			'ID' => $pageId,
			'menu_order' => $index + 1,
		));

		if (isset($categories[$pageId])) {
			assign_category_to_page($pageId, $categories);
		}
	}

	return new WP_REST_Response(array('status' => 'success'), 200);
}
?>
