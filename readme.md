xoops-widget
============

XOOPS-Widget is a XOOPS Cube Legacy module.

You can add small widgets to your site like map, twitter, text, etc. with this module.

Requirement
-----------
XOOPS Cube Legacy 2.2 or later


Included Plugins
----------------
* html
* Google Maps
* Twitter

Terms of this module
--------------------
* Plugin: Plugin contains settings, language file, templates for some sort of function. For example, showing Google map, showing Tweet, showing social buttons.
* Instance: Installed Plugin. Also called 'Widget'. A Plugin can installed many times with different config value.

How to Use
----------
* From the top page of this module, click Plugin List(プラグイン一覧)
* Plugins you can install are listed. Click "Add"（追加） Button you want to install.
* Input options for the plugin then click "Send" button. You don't have to input "template" field.
* New instance of the plugin are installed now.
* Add a new block of Widget module from admin panel. Input instance id you installed.

Use with Smarty in template
---------------------------
You can show widget on template, instead of block.

Use 'xoops_widget' smarty function.

ex)
`<{xoops_widget dirname="widget" widget_id=3}>`

You can overwrite option value by smarty parameter like below.
`<{xoops_widget dirname="widget" widget_id=3 p_username="kilica"}>`
In this case, "p_username" option is replaced by "kilica".
