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

require_once WIDGET_TRUST_PATH . '/class/AbstractViewAction.class.php';

/**
 * Widget_InstanceViewAction
**/
class Widget_InstanceViewAction extends Widget_AbstractViewAction
{
	const DATANAME = 'instance';

	/**
	 * prepare
	 * 
	 * @param	void
	 * 
	 * @return	bool
	**/
	public function prepare()
	{
		parent::prepare();
		$this->mObject->loadPlugin();

		return true;
	}

	/**
	 * executeViewSuccess
	 * 
	 * @param	XCube_RenderTarget	&$render
	 * 
	 * @return	void
	**/
	public function executeViewSuccess(/*** XCube_RenderTarget ***/ &$render)
	{
		$render->setTemplateName($this->mAsset->mDirname . '_instance_view.html');
		$render->setAttribute('object', $this->mObject);
		$render->setAttribute('dirname', $this->mAsset->mDirname);
		$render->setAttribute('dataname', self::DATANAME);
        $render->setAttribute('blockList', $this->_getBlocks());
	}

    protected function _getBlocks()
    {
        $ret = array();
        $handler = xoops_gethandler('block');
        $blocks = $handler->getByModule($this->mModule->mXoopsModule->get('mid'));
        foreach($blocks as $block){
            $instanceId = array_shift(explode('|', $block->get('options')));
            if($instanceId == $this->mObject->get('instance_id')){
                $ret[] = $block;
            }
        }
        return $ret;
    }
}

?>
