# Zen Custom Fields

Easy to implement and use custom fields for WordPress templates. The plugin converts regular html tables into arrays of values.

## Usage

1. Place the table with values you want to use on your page between `[zen-fields] ... [/zen-fields]` short-tags
2. In your template use `zen_field()` function to output values from above table

## Installation

1. Download zipped plugin from this page and unzip its content to the `/wp-content/plugins/` directory in your Wordpress installation folder
2. Activate the plugin through the 'Plugins' menu in WordPress

## Examples

Basic usage - simple table with values only
  (HTML page edit mode)

  ```html
    Page content
    ...
    [zen-fields]
    <table>
    <tr><td>Value 1</td></tr>
    <tr><td>Value 2</td></tr>
    <tr><td>Value 3</td></tr>
    </table>
    [/zen-fields]
    ...
  ```

`<?php echo zen_field(1) ?>` - use in your template to output the value from second row of the table  

 Or if you are using php version 5.4 and above: `<?= zen_field(1) ?>`. Where 1 is an index of array which holds values from the table.  

`<?php echo zen_field(1, 2) ?>` - use 2 dimensional tables and extract its  values using column index in second parameter of zen_field() function.
  
A better approach is to use named key/value pairs. In this case you should use table header tags `<th>` for
 field names.

```html
  [zen-fields]
      <table>
        <tr><th>field name 1</th><td>Value 1</td></tr>
        <tr><th>field name 2</th><td>Value 2</td></tr>
        <tr><th>field name 3</th><td>Value 3</td></tr>
      </table>
    [/zen-fields]
```

`<?php echo zen_field('field name 2') ?>` - output the value from second row in your template

You can use 2 dimensional tables. The first table row then would hold column names in `<th>` tags.

(Visual edit mode)<pre><code>[zen-fields]<table>
  <tr><th></th><th>column 1</th><th>column 2</th></tr>
  <tr><th>field name 1</th><td>Value 1</td><td>Value 2</td></tr>
  <tr><th>field name 2</th><td>Value 3</td><td>Value 4</td></tr>
  <tr><th>field name 3</th><td>Value 5</td><td>Value 6</td></tr>
</table>
[/zen-fields]
</code></pre>
  
In your template use combination of row and column names to output your data:
  
`<?php echo zen_field('field name 2','column 1') ?>` - would output `Value 3` string

You can place many tables with different data between [zen-fields] short-tags. To target specific table in your template
use table name in the third parameter in zen_field function. You need to specify table name using data-name attribute.

e.g. `<table data-name="table name">...</table>`

And in template:

`<?php echo zen_field('field name','column name', 'table name') ?>`

If you have only one column with values you can put table name in second parameter.

## Iteration over values in tables

It is possible to iterate over values from your tables. The variable `$zen_fields->tables` holds an array
 with values from all the tables on the page.

## Escaping output

By default output is not escaped which allows you to use links, images and another HTML content in your custom fields.
If you would like to escape the output use `zen_field_esc()` function instead of `zen_field()`

## Extracting src attribute from an image

`zen_field_src()` function can be used to get link from the image placed in custom field.

## Changelog

- 1.16
  - Fix issues with `<br>` tags in new version of Wordpress
- 1.15
  - Fix get variables outside of post loop
- 1.14
  - Fix critical bug
- 1.13
  - Prevent from showing errors when no custom fields are defined
- 1.12
  - Improve data iteration
  - Fix problem with plugin initalization
  - Add parse image source function
- 1.11
  - Fix critical bug
- 1.1
  - Fix bugs
  - Allow attributes in &lt;th&gt; element