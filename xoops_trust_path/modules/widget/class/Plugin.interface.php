<?php
/**
 * Created by JetBrains PhpStorm.
 * User: ICHIKAWA
 * Date: 12/07/11
 * Time: 19:56
 * To change this template use File | Settings | File Templates.
 */
interface Widget_PluginInterface
{
	public static function execute(Widget_InstanceObject &$object);
}