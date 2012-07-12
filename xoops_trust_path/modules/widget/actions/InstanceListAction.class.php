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

require_once WIDGET_TRUST_PATH . '/class/AbstractListAction.class.php';

/**
 * Widget_InstanceListAction
**/
class Widget_InstanceListAction extends Widget_AbstractListAction
{
	const DATANAME = 'instance';

	/**
	 * getDefaultView
	 *
	 * @param	void
	 *
	 * @return	Enum
	 **/
	public function getDefaultView()
	{
		$this->mFilter =& $this->_getFilterForm();
		$this->mFilter->fetch();

		$handler =& $this->_getHandler();
		$criteria=$this->mFilter->getCriteria();

		$this->mObjects = $handler->getObjects($criteria);

		return WIDGET_FRAME_VIEW_INDEX;
	}

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
		$this->_setupAccessController('instance');

		return true;
	}

	/**
	 * executeViewIndex
	 *
	 * @param	XCube_RenderTarget	&$render
	 *
	 * @return	void
	 **/
	public function executeViewIndex(/*** XCube_RenderTarget ***/ &$render)
	{
		$render->setTemplateName($this->mAsset->mDirname . '_instance_list.html');
		$render->setAttribute('objects', $this->mObjects);
		$render->setAttribute('dirname', $this->mAsset->mDirname);
		$render->setAttribute('dataname', self::DATANAME);
		$render->setAttribute('pageNavi', $this->mFilter->mNavi);
		$render->setAttribute('accessController', $this->mAccessController['main']);
	}
}
?>
