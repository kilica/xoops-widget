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
	public static function execute(Widget_InstanceObject $object)
	{
		$headerScript = XCube_Root::getSingleton()->mContext->getAttribute('headerScript');
		$headerScript->addLibrary("http://widgets.twimg.com/j/2/widget.js", false);
	}

	public static function prepareEditform(Widget_InstanceObject $object)
	{
		$headerScript = XCube_Root::getSingleton()->mContext->getAttribute('headerScript');
		$wtype = $object->getOptionValue('p_type') ? $object->getOptionValue('p_type') : 'profile';
		$headerScript->addScript('
$(".'.$wtype.' input").removeAttr("disabled");
$("#p_type").change(function(){
  $(".profile input, .search input, .faves input, .list input").attr("disabled", "disabled");
  $("."+$(this).val()+" input").removeAttr("disabled");
});
');
	}

	public static function fetch(Widget_InstanceObject $object, $request)
	{

	}

	public static function getImageNumber(Widget_InstanceObject $obj)
	{
		return 0;
	}
}
