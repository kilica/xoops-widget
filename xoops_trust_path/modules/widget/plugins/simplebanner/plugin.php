<?php
/**
 * Created by JetBrains PhpStorm.
 * User: ICHIKAWA
 * Date: 12/07/11
 * Time: 19:49
 * To change this template use File | Settings | File Templates.
 */

class Widget_Simplebanner_Plugin implements Widget_PluginInterface
{
	public static function execute(Widget_InstanceObject &$object)
	{

	}

	public static function prepareEditform(Widget_InstanceEditForm $form, Widget_InstanceObject $object)
	{
        if($object->isNew()){
            $object->setOptionValue('p_start', mktime(0,0,0,date('m'), date('d'), date('Y')));
            $object->setOptionValue('p_end', mktime(0,0,0,date('m'), date('d'), date('Y')));
            $form->set('p_start', mktime(0,0,0,date('m'), date('d'), date('Y')));
            $form->set('p_start', mktime(0,0,0,date('m'), date('d'), date('Y')));
        }
	}

	public static function fetch(Widget_InstanceObject $object, $request)
	{
        $object->setOptionValue('p_start', self::_makeUnixtime($request->getRequest('p_start_day'), $request->getRequest('p_start_time')));
        $object->setOptionValue('p_end', self::_makeUnixtime($request->getRequest('p_end_day'), $request->getRequest('p_end_time')));
    }

	public static function getImageNumber(Widget_InstanceObject $obj)
	{
		return 1;
	}

    /**
     * _makeUnixtime
     *
     * @param	string	$day
     *
     * @return	unixtime
     **/
    protected static function _makeUnixtime($day, $time)
    {
        if(! $day){
            return 0;
        }
        $dayArray = explode('-', $day);
        return mktime(substr($time, 0, 2), substr($time, 2, 2), 0, $dayArray[1], $dayArray[2], $dayArray[0]);
    }
}