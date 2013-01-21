<?php
/**
 * Created by JetBrains PhpStorm.
 * User: ICHIKAWA
 * Date: 12/07/11
 * Time: 19:49
 * To change this template use File | Settings | File Templates.
 */

class Widget_Rss_Plugin implements Widget_PluginInterface
{
	public static function execute(Widget_InstanceObject $object)
	{
		include_once XOOPS_LIBRARY_PATH.'/simplepie/autoloader.php';
		$feed = new SimplePie();
		$feed->set_feed_url($object->getOptionValue('p_feed_url'));
		$feed->set_item_limit($object->getOptionValue('p_number'));
		$upload_path = XOOPS_TRUST_PATH.'/uploads';
		$widget_path = $upload_path.'/widget';
		$cache_path = $widget_path.'/rss';
		if(file_exists($upload_path)===true && is_writable($upload_path)===true && file_exists($cache_path)===false){
			if(file_exists($widget_path)===false){
				mkdir($upload_path.'/widget');
				chmod($upload_path.'/widget', 0777);
			}
			mkdir($cache_path);
			chmod($cache_path, 0777);
		}
		if(file_exists($cache_path)===true && is_writable($cache_path)===true){
			$feed->set_cache_location($cache_path);
		}
		else{
			$feed->enable_cache(false);
		}
		$feed->init();
		$feed->handle_content_type();
		$object->mFeed = $feed;
		$object->mLimit = $object->getOptionValue('p_number');
	}

	public static function prepareEditform(Widget_InstanceEditForm $form, Widget_InstanceObject $object)
	{
	}

	public static function fetch(Widget_InstanceObject $object, $request)
	{

	}

	public static function getImageNumber(Widget_InstanceObject $obj)
	{
		return 0;
	}

}
