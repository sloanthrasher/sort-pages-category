# Sort Pages And Set Category

- **Name:** Sort Pages, Set Categories
- **Short Description:** Organize pages with drag-and-drop and set categories.
- **Version:** 1.0
- **Stable Version:** 1.0
- **Author:** Sloan Thrasher
- **Author URI:** https://sloansweb.com/page-4/
- **License:** GPLv3 or later
- **License URI:** http://www.gnu.org/licenses/gpl-3.0.html


## Description

Sort Pages Set Category is a WordPress plugin that allows you to organize your pages with drag-and-drop functionality and assign categories to them. This plugin adds a menu item under the Appearance menu in the WordPress admin dashboard, where you can easily reorder pages and save their order and category.

## Features

- ***Drag-and-drop page sorting***
- ***Assign categories to pages using a dropdown menu***
- ***Save the page order and categories with a single click***
- ***Display pages in the saved order***


## Installation

- Download the plugin files and upload them to your WordPress site's `wp-content/plugins` directory.
- Activate the plugin through the `Plugins` menu in WordPress.

*_OR_*

- In the admin menu, select `Plugins`, then click the `Add Plugin` button. 
- A form will appear that will allow you to upload the zip file. 
- Click the `Browse` button and select the file from your local drive.
- Once uploaded, you can Click on the `Install` button. 
- The last step is to click on the `Activate` button.

## Usage

- After activating the plugin, go to `Appearance` > `Sort and Categorize Pages` in the WordPress admin dashboard.
- You will see a list of your pages. Drag and drop the rows to reorder the pages as desired.
- Use the category dropdown to assign a category to each page.
- Click the "Save Changes" button to save the order and categories.

## Files

### `sort-pages-set-cat.php`

This is the main plugin file that handles the functionality of the plugin. It registers the admin menu, enqueues scripts and styles, displays the admin page, and defines the REST API endpoint for saving the order and categories.

### `css/styles.css`

This file contains the CSS styles for the plugin. It styles the table, order number, page title, category dropdown, and save button.

### `js/scripts.js`

This file contains the JavaScript for the plugin. It initializes the sortable functionality for the table rows, collects the order and category data, and sends it to the REST API endpoint for saving.

