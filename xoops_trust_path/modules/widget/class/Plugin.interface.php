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
	/**
	 * @static
	 * @abstract
	 * @param Widget_InstanceObject $object
	 * @return mixed
	 */
	public static function execute(Widget_InstanceObject $object);

	/**
	 * @static
	 * @abstract
	 * @param Widget_InstanceObject $object
	 * @return void
	 */
	public static function prepareEditform(Widget_InstanceObject $object);

	/**
	 * @static
	 * @abstract
	 * @param Widget_InstanceObject $object
	 * @param $request
	 * @return void
	 */
	public static function fetch(Widget_InstanceObject $object, $request);

	//public static function getImageNumber(Widget_InstanceObject $obj);

}