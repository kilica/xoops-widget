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
class Widget_PluginListAction extends Widget_AbstractListAction
{
	const DATANAME = 'plugin';

	/**
	 * getDefaultView
	 *
	 * @param	void
	 *
	 * @return	Enum
	 **/
	public function getDefaultView()
	{
		$this->mPlugins = Widget_Utils::getPluginList();

		return WIDGET_FRAME_VIEW_INDEX;
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
		$render->setTemplateName($this->mAsset->mDirname . '_plugin_list.html');
		$render->setAttribute('plugins', $this->mPlugins);
		$render->setAttribute('dirname', $this->mAsset->mDirname);
		$render->setAttribute('dataname', self::DATANAME);
	}
}

?>
