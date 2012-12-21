<?php
/**
 * @file
 * @package widget
 * @version $Id$
**/

if(!defined('XOOPS_ROOT_PATH'))
{
	exit;
}

if(!defined('WIDGET_TRUST_PATH'))
{
	define('WIDGET_TRUST_PATH',XOOPS_TRUST_PATH . '/modules/widget');
}

require_once WIDGET_TRUST_PATH . '/class/WidgetUtils.class.php';

//
// Define a basic manifesto.
//
$modversion['name'] = $myDirName;
$modversion['version'] = 0.31;
$modversion['description'] = _MI_WIDGET_DESC_WIDGET;
$modversion['author'] = _MI_WIDGET_LANG_AUTHOR;
$modversion['credits'] = _MI_WIDGET_LANG_CREDITS;
$modversion['help'] = 'help.html';
$modversion['license'] = 'GPL';
$modversion['official'] = 0;
$modversion['image'] = 'images/module_icon.png';
$modversion['dirname'] = $myDirName;
$modversion['trust_dirname'] = 'widget';

$modversion['cube_style'] = true;
$modversion['legacy_installer'] = array(
	'installer'   => array(
		'class'	 => 'Installer',
		'namespace' => 'Widget',
		'filepath'  => WIDGET_TRUST_PATH . '/admin/class/installer/WidgetInstaller.class.php'
	),
	'uninstaller' => array(
		'class'	 => 'Uninstaller',
		'namespace' => 'Widget',
		'filepath'  => WIDGET_TRUST_PATH . '/admin/class/installer/WidgetUninstaller.class.php'
	),
	'updater' => array(
		'class'	 => 'Updater',
		'namespace' => 'Widget',
		'filepath'  => WIDGET_TRUST_PATH . '/admin/class/installer/WidgetUpdater.class.php'
	)
);
$modversion['disable_legacy_2nd_installer'] = false;

$modversion['sqlfile']['mysql'] = 'sql/mysql.sql';
$modversion['tables'] = array(
//	'{prefix}_{dirname}_xxxx',
##[cubson:tables]
	'{prefix}_{dirname}_instance',

##[/cubson:tables]
);

//
// Templates. You must never change [cubson] chunk to get the help of cubson.
//
$modversion['templates'] = array(
/*
	array(
		'file'		=> '{dirname}_xxx.html',
		'description' => _MI_WIDGET_TPL_XXX
	),
*/
##[cubson:templates]
		array('file' => '{dirname}_instance_delete.html','description' => _MI_WIDGET_TPL_INSTANCE_DELETE),
		array('file' => '{dirname}_instance_edit.html','description' => _MI_WIDGET_TPL_INSTANCE_EDIT),
		array('file' => '{dirname}_instance_list.html','description' => _MI_WIDGET_TPL_INSTANCE_LIST),
		array('file' => '{dirname}_instance_view.html','description' => _MI_WIDGET_TPL_INSTANCE_VIEW),
		array('file' => '{dirname}_plugin_list.html','description' => 'Plugin List'),
		array('file' => '{dirname}_inc_menu.html','description' => 'menu'),
##[/cubson:templates]
);

//
// Admin panel setting
//
$modversion['hasAdmin'] = 1;
$modversion['adminindex'] = 'admin/index.php?action=Index';
$modversion['adminmenu'] = array(
/*
	array(
		'title'	=> _MI_WIDGET_LANG_XXXX,
		'link'	 => 'admin/index.php?action=xxx',
		'keywords' => _MI_WIDGET_KEYWORD_XXX,
		'show'	 => true,
		'absolute' => false
	),
*/
##[cubson:adminmenu]
##[/cubson:adminmenu]
);

//
// Public side control setting
//
$modversion['hasMain'] = 1;
$modversion['hasSearch'] = 0;
$modversion['sub'] = array(
/*
	array(
		'name' => _MI_WIDGET_LANG_SUB_XXX,
		'url'  => 'index.php?action=XXX'
	),
*/
##[cubson:submenu]
##[/cubson:submenu]
);

//
// Config setting
//
$modversion['config'] = array(
/*
	array(
		'name'		  => 'xxxx',
		'title'		 => '_MI_WIDGET_TITLE_XXXX',
		'description'   => '_MI_WIDGET_DESC_XXXX',
		'formtype'	  => 'xxxx',
		'valuetype'	 => 'xxx',
		'options'	   => array(xxx => xxx,xxx => xxx),
		'default'	   => 0
	),
*/

	array(
		'name'		  => 'css_file' ,
		'title'		 => "_MI_WIDGET_LANG_CSS_FILE" ,
		'description'   => "_MI_WIDGET_DESC_CSS_FILE" ,
		'formtype'	  => 'textbox' ,
		'valuetype'	 => 'text' ,
		'default'	   => '/modules/'.$myDirName.'/style.css',
		'options'	   => array()
	) ,
##[cubson:config]
##[/cubson:config]
);

//
// Block setting
//
$modversion['blocks'] = array(
	1 => array(
		'func_num'		  => 1,
		'file'			  => 'ViewBlock.class.php',
		'class'			 => 'ViewBlock',
		'name'			  => 'Widget',
		'description'	   => 'Show Widget',
		'options'		   => '',
		'template'		  => '',
		'show_all_module'   => true,
		'visible_any'	   => false,
		'can_clone'			=> true,
	),
##[cubson:block]
##[/cubson:block]
);

?>
