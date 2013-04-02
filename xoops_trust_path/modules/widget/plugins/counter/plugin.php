<?php
/**
 * Created by JetBrains PhpStorm.
 * User: ICHIKAWA
 * Date: 12/07/11
 * Time: 19:49
 * To change this template use File | Settings | File Templates.
 */

class Widget_Counter_Plugin implements Widget_PluginInterface
{
	public static function execute(Widget_InstanceObject &$object)
	{
		if($object->getOptionValue('p_perpage')==1){
			self::_countupPage($object);
			self::_countupTotal($object);
			$object->mPageCounter = self::getThisCounter($object->getOptionValue('p_pagecounter'));
		}
		else{
			self::_countupTotal($object);
		}

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

	protected static function _countup(Widget_InstanceObject $instance)
	{
		$handler = Legacy_Utils::getModuleHandler('instance', $instance->getDirname());
		return $handler->insert($instance, true);
	}

	protected static function _countupTotal(Widget_InstanceObject $instance)
	{
		$counter = $instance->getOptionValue('p_counter');
		$instance->setOptionValue('p_counter', $counter+1);
		return self::_countup($instance);
	}

	protected static function _countupPage(Widget_InstanceObject $instance)
	{
		$output = array();
		$existPage = false;
		$pageCounter = $instance->getOptionValue('p_pagecounter');
		$pageArr = explode("\n", $pageCounter);
		foreach($pageArr as $page){
			$page = $page ? $page : '0:'.$_SERVER['REQUEST_URI'];
			$items = explode(':', $page);
			if($items[1]==trim($_SERVER['REQUEST_URI'])){
				$items[0] = $items[0]+1;
				$existPage = true;
			}
			$output[] = implode(':', $items);
		}
		if($existPage===false){
			$output[] = '1:'.$_SERVER['REQUEST_URI'];
		}
		$instance->setOptionValue('p_pagecounter', implode("\n", $output));
		return self::_countup($instance);
	}

	public static function getThisCounter($pageCounter)
	{
		$pageArr = explode("\n", $pageCounter);
		foreach($pageArr as $page){
			$items = explode(':', $page);
			if($items[1]==$_SERVER['REQUEST_URI']){
				return $items[0];
			}
		}
		return 0;
	}
}
