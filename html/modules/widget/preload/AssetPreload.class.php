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

require_once XOOPS_TRUST_PATH . '/modules/widget/preload/AssetPreload.class.php';
Widget_AssetPreloadBase::prepare(basename(dirname(dirname(__FILE__))));

?>
