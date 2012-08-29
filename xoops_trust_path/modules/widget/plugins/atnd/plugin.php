<?php
/**
 * Created by JetBrains PhpStorm.
 * User: ICHIKAWA
 * Date: 12/07/11
 * Time: 19:49
 * To change this template use File | Settings | File Templates.
 */

class Widget_Atnd_Plugin implements Widget_PluginInterface
{
	public static function execute(Widget_InstanceObject $object)
	{
		$res = self::_getEventResponse($object->getOptionValue('p_owner_id'), $object->getOptionValue('p_number'));
		if($res!==false){
			$object->mEvents = self::_parseResponse($res);
		}
	}

	public static function prepareEditform(Widget_InstanceObject $object)
	{
	}

	public static function fetch(Widget_InstanceObject $object, $request)
	{

	}

	public static function getImageNumber(Widget_InstanceObject $obj)
	{
		return 0;
	}

	/**
	 * get ATND Event response by ATND API
	 *
	 * @param   string	$ownerIds
	 * @param   mixed	$request
	 *
	 * @return  mixed
	 **/
	public function _getEventResponse($ownerIds, $number=3, $request=null)
	{
		$uri = 'http://api.atnd.org/events/?';
		if(! $ownerIds) return false;
		$query = 'owner_id='.$ownerIds.'&count='.$number;
//    	foreach(array_keys($request) as $key){
//   		$query .= '&'.urlencode($key).'='.urlencode($request[$key]);
//    	}
		$uri .= $query;
		if(!is_resource($handler = curl_init($uri)))
		{
			return false;
		}
		/// @todo set port?
		curl_setopt($handler, CURLOPT_RETURNTRANSFER, 1);
		if(($res = trim(curl_exec($handler))) == null)
		{
			return false;
		}
		curl_close($handler);
		return $res;
	}

	/**
	 * parse response from ATND to make Event Object
	 *
	 * @param   text	$res
	 *
	 * @return  string
	 **/
	public function _parseResponse($res)
	{//var_dump($res);die();
		$xml = simplexml_load_string($res);
		$objects = array();
		foreach($xml->events->event as $event){
			$obj = new Widget_AtndObject();
			foreach(array_keys($obj->mVars) as $key){
				$obj->assignVar($key, $event->$key);
			}
			$objects[] = $obj;
			unset($obj);
		}
		return $objects;
	}
}

/**
 * Widget_AtndObject
 **/
class Widget_AtndObject extends XoopsSimpleObject
{
	public function __construct()
	{
		$this->initVar('event_id', XOBJ_DTYPE_INT, '0', false);
		$this->initVar('title', XOBJ_DTYPE_STRING, '', false);
		$this->initVar('catch', XOBJ_DTYPE_STRING, '', false);
		$this->initVar('description', XOBJ_DTYPE_TEXT, '', false);
		$this->initVar('event_url', XOBJ_DTYPE_STRING, '', false);
		$this->initVar('started_at', XOBJ_DTYPE_STRING, '', false);
		$this->initVar('ended_at', XOBJ_DTYPE_STRING, '', false);
		$this->initVar('url', XOBJ_DTYPE_STRING, '', false);
		$this->initVar('limit', XOBJ_DTYPE_INT, '0', false);
		$this->initVar('address', XOBJ_DTYPE_TEXT, '', false);
		$this->initVar('place', XOBJ_DTYPE_STRING, '', false);
		$this->initVar('lat', XOBJ_DTYPE_STRING, '', false);
		$this->initVar('lon', XOBJ_DTYPE_STRING, '', false);
		$this->initVar('accepted', XOBJ_DTYPE_INT, '0', false);
		$this->initVar('waiting', XOBJ_DTYPE_INT, '0', false);
		$this->initVar('updated_at', XOBJ_DTYPE_STRING, '', false);
	}
}
