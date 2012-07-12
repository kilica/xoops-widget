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
 * Widget_InstanceDeleteForm
**/
class Widget_InstanceDeleteForm extends XCube_ActionForm
{
    /**
     * getTokenName
     * 
     * @param   void
     * 
     * @return  string
    **/
    public function getTokenName()
    {
        return "module.widget.InstanceDeleteForm.TOKEN";
    }

    /**
     * prepare
     * 
     * @param   void
     * 
     * @return  void
    **/
    public function prepare()
    {
        //
        // Set form properties
        //
        $this->mFormProperties['instance_id'] = new XCube_IntProperty('instance_id');
    
        //
        // Set field properties
        //
        $this->mFieldProperties['instance_id'] = new XCube_FieldProperty($this);
        $this->mFieldProperties['instance_id']->setDependsByArray(array('required'));
        $this->mFieldProperties['instance_id']->addMessage('required', _MD_WIDGET_ERROR_REQUIRED, _MD_WIDGET_LANG_INSTANCE_ID);
    }

    /**
     * load
     * 
     * @param   XoopsSimpleObject  &$obj
     * 
     * @return  void
    **/
    public function load(/*** XoopsSimpleObject ***/ &$obj)
    {
        $this->set('instance_id', $obj->get('instance_id'));
    }

    /**
     * update
     * 
     * @param   XoopsSimpleObject  &$obj
     * 
     * @return  void
    **/
    public function update(/*** XoopsSimpleObject ***/ &$obj)
    {
        $obj->set('instance_id', $this->get('instance_id'));
    }
}

?>
