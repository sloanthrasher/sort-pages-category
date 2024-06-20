=== Sort Pages, Set Categories ===
Contributors: sloanthrasher
Donate link: https://sloansweb.com/say-thanks/
Requires at least: 5.3
Tested up to: 1.0
Requires PHP: 7.3
Stable tag: 1.0
License: GPLv3 or later
License URI: http://www.gnu.org/licenses/gpl-3.0.html

Organize pages drag-and-drop and set categories.

== Description ==
What It Does
	Sort Pages Set Category is a WordPress plugin that allows you to organize your pages with drag-and-drop functionality 
	and assign categories to them. This plugin adds a menu item under the Appearance menu in the WordPress admin 
	dashboard, where you can easily reorder pages and save their order and category.

== Features ==
	Drag-and-drop page sorting
	Assign categories to pages using a dropdown menu
	Save the page order and categories with a single click
	Display pages in the saved order

== Installation ==
	Download the plugin files and upload them to your WordPress site\'s wp-content/plugins directory.
	Activate the plugin through the Plugins menu in WordPress.
	----
	 OR
	----
	In the admin menu, select Plugins, then click the Add Plugin button.
	A form will appear that will allow you to upload the zip file.
	Click the Browse button and select the file from your local drive.
	Once uploaded, you can Click on the Install button.
	The last step is to click on the Activate button.

== Frequently Asked Questions ==
I've changed the page order, but the changes aren't there when I open the maintenance page again?
	Be sure to click the Save Changes button at the bottom the listing.

= Usage =
	After activating the plugin, go to Appearance > Sort and Categorize Pages in the WordPress admin dashboard.
	You will see a list of your pages. Drag and drop the rows to reorder the pages as desired.
	Use the category dropdown to assign a category to each page.
	Click the "Save Changes" button to save the order and categories.

= How to uninstall the plugin? =
Simply deactivate and delete the plugin. 

== Upgrade Notice ==
No upgrade needed.

== Screenshots ==
1. Listing, mantenance page.

== Changelog ==
= 1.0 =
* Plugin released.

== Files ==
sort-pages-set-cat.php
	This is the main plugin file that handles the functionality of the plugin. It registers the admin menu, enqueues scripts 
	and styles, displays the admin page, and defines the REST API endpoint for saving the order and categories.

css/styles.css
	This file contains the CSS styles for the plugin. It styles the table, order number, page title, category dropdown, 
	and save button.

js/scripts.js
	This file contains the JavaScript for the plugin. It initializes the sortable functionality for the table rows, collects
	the order and category data, and sends it to the REST API endpoint for saving.



== Installation ==
	Download the plugin files and upload them to your WordPress site\'s wp-content/plugins directory.
	Activate the plugin through the Plugins menu in WordPress.
	----
	 OR
	----
	In the admin menu, select Plugins, then click the Add Plugin button.
	A form will appear that will allow you to upload the zip file.
	Click the Browse button and select the file from your local drive.
	Once uploaded, you can Click on the Install button.
	The last step is to click on the Activate button.
