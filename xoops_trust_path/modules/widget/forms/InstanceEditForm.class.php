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

require_once XOOPS_ROOT_PATH . '/core/XCube_ActionForm.class.php';
require_once XOOPS_MODULE_PATH . '/legacy/class/Legacy_Validator.class.php';

/**
 * Widget_InstanceEditForm
**/
class Widget_InstanceEditForm extends XCube_ActionForm
{
	/**
	 * getTokenName
	 * 
	 * @param	void
	 * 
	 * @return	string
	**/
	public function getTokenName()
	{
		return "module.widget.InstanceEditForm.TOKEN";
	}

	/**
	 * prepare
	 * 
	 * @param	void
	 * 
	 * @return	void
	**/
	public function prepare()
	{
		//
		// Set form properties
		//
		$this->mFormProperties['instance_id'] = new XCube_IntProperty('instance_id');
		$this->mFormProperties['title'] = new XCube_StringProperty('title');
		$this->mFormProperties['category_id'] = new XCube_IntProperty('category_id');
		$this->mFormProperties['type'] = new XCube_StringProperty('type');
		$this->mFormProperties['template'] = new XCube_StringProperty('template');
		$this->mFormProperties['options'] = new XCube_TextProperty('options');
		$this->mFormProperties['posttime'] = new XCube_IntProperty('posttime');

	
		//
		// Set field properties
		//
		$this->mFieldProperties['instance_id'] = new XCube_FieldProperty($this);
$this->mFieldProperties['instance_id']->setDependsByArray(array('required'));
$this->mFieldProperties['instance_id']->addMessage('required', _MD_WIDGET_ERROR_REQUIRED, _MD_WIDGET_LANG_INSTANCE_ID);
		$this->mFieldProperties['title'] = new XCube_FieldProperty($this);
		$this->mFieldProperties['title']->setDependsByArray(array('required','maxlength'));
		$this->mFieldProperties['title']->addMessage('required', _MD_WIDGET_ERROR_REQUIRED, _MD_WIDGET_LANG_TITLE);
		$this->mFieldProperties['title']->addMessage('maxlength', _MD_WIDGET_ERROR_MAXLENGTH, _MD_WIDGET_LANG_TITLE, '255');
		$this->mFieldProperties['title']->addVar('maxlength', '255');
	   $this->mFieldProperties['category_id'] = new XCube_FieldProperty($this);
$this->mFieldProperties['category_id']->setDependsByArray(array('required'));
$this->mFieldProperties['category_id']->addMessage('required', _MD_WIDGET_ERROR_REQUIRED, _MD_WIDGET_LANG_CATEGORY_ID);
	   $this->mFieldProperties['type'] = new XCube_FieldProperty($this);
		$this->mFieldProperties['type']->setDependsByArray(array('required','maxlength'));
		$this->mFieldProperties['type']->addMessage('required', _MD_WIDGET_ERROR_REQUIRED, _MD_WIDGET_LANG_TYPE);
		$this->mFieldProperties['type']->addMessage('maxlength', _MD_WIDGET_ERROR_MAXLENGTH, _MD_WIDGET_LANG_TYPE, '50');
		$this->mFieldProperties['type']->addVar('maxlength', '50');
		$this->mFieldProperties['posttime'] = new XCube_FieldProperty($this);
	}

	/**
	 * load
	 * 
	 * @param	XoopsSimpleObject  &$obj
	 * 
	 * @return	void
	**/
	public function load(/*** XoopsSimpleObject ***/ &$obj)
	{
		$this->set('instance_id', $obj->get('instance_id'));
		$this->set('title', $obj->get('title'));
		$this->set('category_id', $obj->get('category_id'));
		$this->set('type', $obj->get('type'));
		$this->set('template', $obj->get('template'));
		$this->set('options', $obj->get('options'));
		$this->set('posttime', $obj->get('posttime'));
	}

	/**
	 * update
	 * 
	 * @param	XoopsSimpleObject  &$obj
	 * 
	 * @return	void
	**/
	public function update(/*** XoopsSimpleObject ***/ &$obj)
	{
		$obj->set('title', $this->get('title'));
		$obj->set('category_id', $this->get('category_id'));
		$obj->set('type', $this->get('type'));
		$obj->set('template', $this->get('template'));

		$req = XCube_Root::getSingleton()->mContext->mRequest;
		foreach($obj->mPlugin['options']['field'] as $inputName){
			$obj->setOptionValue($inputName, $req->getRequest($inputName));
		}
		$pluginFile = WIDGET_TRUST_PATH.'/plugins/'.$obj->getShow('type').'/plugin.php';
		if(file_exists($pluginFile)){
			require_once $pluginFile;
			call_user_func(array('Widget_'.ucfirst($obj->get('type')).'_Plugin', 'fetch'), $obj, $req);
		}
		$obj->set('options', serialize($obj->mOptions));
	}

	/**
	 * _makeDateString
	 *
	 * @param	string	$key
	 * @param	XoopsSimpleObject	$obj
	 *
	 * @return	string
	 **/
	protected function _makeDateString($key, $obj)
	{
		return $obj->get($key) ? date(_PHPDATEPICKSTRING, $obj->get($key)) : '';
	}

	/**
	 * _makeUnixtime
	 * 
	 * @param	string	$key
	 * 
	 * @return	unixtime
	**/
	protected function _makeUnixtime($key)
	{
		if(! $this->get($key)){
			return 0;
		}
		$timeArray = explode('-', $this->get($key));
		return mktime(0, 0, 0, $timeArray[1], $timeArray[2], $timeArray[0]);
	}
}

?>
