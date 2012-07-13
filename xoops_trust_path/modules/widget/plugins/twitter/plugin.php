<?php
/**
 * Created by JetBrains PhpStorm.
 * User: ICHIKAWA
 * Date: 12/07/11
 * Time: 19:49
 * To change this template use File | Settings | File Templates.
 */

class Widget_Twitter_Plugin implements Widget_PluginInterface
{
	public static function execute(Widget_InstanceObject &$object)
	{
		$headerScript = XCube_Root::getSingleton()->mContext->getAttribute('headerScript');
		$headerScript->addLibrary("http://widgets.twimg.com/j/2/widget.js", false);
	}

	public static function fetch(Widget_InstanceObject &$object, $request)
	{

	}

}
