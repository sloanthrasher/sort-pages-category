# Sort Pages Plugin

**Version:** 1.0  
**Author:** Sloan Thrasher  
**License:** GPL v2 or later  
**License URI:** http://www.gnu.org/licenses/gpl-2.0.html  

## Description

The Sort Pages Plugin is a WordPress plugin that allows you to organize your pages with drag-and-drop functionality and assign categories to them. This plugin adds a menu item under the Appearance menu in the WordPress admin dashboard, where you can easily reorder pages and save their order and category.

## Features

- Drag-and-drop page sorting
- Assign categories to pages using a dropdown menu
- Save the page order and categories with a single click
- Display pages in the saved order

## Installation

1. Download the plugin files and upload them to your WordPress site's `wp-content/plugins` directory.
1. Activate the plugin through the `Plugins` menu in WordPress.

*_OR_*

1. In the admin menu, select `Plugins`, then click the `Add Plugin` button. 
1. A form will appear that will allow you to upload the zip file. 
1. Click the `Browse` button and select the file from your local drive.
1. Once uploaded, you can Click on the `Install` button. 
1. The last step is to click on the `Activate` button.

## Usage

1. After activating the plugin, go to `Appearance` > `Sort and Categorize Pages` in the WordPress admin dashboard.
1. You will see a list of your pages. Drag and drop the rows to reorder the pages as desired.
1. Use the category dropdown to assign a category to each page.
1. Click the "Save Changes" button to save the order and categories.

## Files

### `sort-pages-plugin.php`

This is the main plugin file that handles the functionality of the plugin. It registers the admin menu, enqueues scripts and styles, displays the admin page, and defines the REST API endpoint for saving the order and categories.

### `css/styles.css`

This file contains the CSS styles for the plugin. It styles the table, order number, page title, category dropdown, and save button.

### `js/scripts.js`

This file contains the JavaScript for the plugin. It initializes the sortable functionality for the table rows, collects the order and category data, and sends it to the REST API endpoint for saving.

