=== Zen Custom Fields ===
Contributors: grzecho
Tags: custom fields, custom templates, post meta, custom values, custom keys
Requires at least: 3.1
Tested up to: 5.2
Stable tag: trunk
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Easy to implement and use custom fields for WordPress templates.

== Description ==

The plugin converts regular HTML tables, embedded in your page, into arrays of values that you can use in your templates to output the data.
Custom fields can hold text and any HTML code (except for tables). Array of values can be simple index based or more complex key value pairs.
You can embed many tables with values on your page with various data.

= Usage =

1. Place the table with values you want to use on your page between '[zen-fields] ... [/zen-fields]' short-tags
2. In your template use 'zen_field()' function to output values from above table

If your would like to use multiple tables with values you should name ech table and use table name as the last parameter of `zen_field()` function
`<table data-name="some table name">...</table>`

= Iteration over values in tables =

It is possible to iterate over values from your tables. The variable '$zen_fields->tables' holds the array with values from all the tables on the page.

= Escaping output =

By default output is not escaped which allows you to use links, images and another HTML content in your custom fields. If you would like to escape the output use 'zen_field_esc()' function instead of 'zen_field()'.

For more details check 'Screenshots' section

== Installation ==

1. Download zen-custom-fields.zip and unzip its content to the /wp-content/plugins/ directory in your Wordpress installation folder
2. Activate the plugin through the 'Plugins' menu in WordPress

== Screenshots ==

1. Basic usage - simple table with values only.
2. A better approach is to use key/value pairs. In this case you should use table header tags <th> for field names.
3. Using 2 dimensional tables

== Frequently Asked Questions ==

= How to use this plugin? =

Simply add table to your page between [zen-fileds] short-tags. And use `zen_field()` function to output value from that table.

e.g. `<?php echo zen_field(1) ?>` - will output value from second row of your table that contains values only

`<?php echo zen_field(1, 2) ?>` - output values from 2 dimensional tables

`<?php echo zen_field('field name 2') ?>` - output value from table with key names defined. Key names should be defined in `<th>` - table header tags.

`<?php echo zen_field('field name 2','column 1') ?>` - output values from 2 dimensional table with key names defined.

Check 'Screenshots' section for more details.

= How to use multiple tables? =

Firstly define the name of each table e.g. `<table data-name="some table name">...</table>`, secondly use the table name in the last parameter of `zen_field()` function

e.g. `<?php echo zen_field('field name 1','column name 2', 'some table name') ?>`

= How to iterate over table values =

The variable `$zen_fields->tables` holds an array with values from all the tables on the page. You can use this variable to iterate the data. The way varies depending on table structure. You can output  structure of the variable for testing using `<pre><?php print_r($zen_fields->tables) ?></pre>` code.

= How to report errors or submit feature requests? =

You can submit an issue on GitHub page, where the main repository of the plugin is held. GitHub account is needed.

https://github.com/Grzegorzsa/zen-custom-fields/issues

=  How to escape output from values of the table =

By default output is not escaped which allows you to echo html tags e.g. links or images. If you would like to escape your output use 'zen_field_esc()' instead of 'zen_field()' function.

=  How to get image src attribute =

'zen_field_src()' function can be used to extract link from the image placed in custom field.

== Changelog ==

= 1.16 =
* Fix issues with `<br>` tags in new version of Wordpress
= 1.15 =
* Fix get variables outside of post loop
= 1.14 =
* Fix critical bug
= 1.13 =
* Prevent from showing errors when no custom fields are defined
= 1.12 =
* Improve data iteration
* Fix problem with plugin initalization
* Add parse image source function
= 1.11 =
* Fix critical bug
= 1.1 =
* Fix bugs
* Allow attributes in `<th>` element
