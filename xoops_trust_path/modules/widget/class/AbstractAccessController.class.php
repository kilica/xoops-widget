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

/**
 * Widget_AbstractCagtegoryInstance
**/
Abstract class Widget_AbstractAccessController
{
	const VIEW = "view";
	const POST = "post";
	const MANAGE = "manage";
	protected $_mCategoryDirKey = null;
	protected $_mDirname = null;

	/**
	 * __construct
	 * 
	 * @param	string	$categoryDir
	 * @param	string	$dirname	this module's dirname
	 * @param	string	$dataname	this module's dataname
	 * 
	 * @return	void
	**/
	abstract public function __construct(/*** string ***/ $CategoryDir, /*** string ***/ $dirname, /*** string ***/ $dataname=null);

	/**
	 * getAccessControllerType	cat/group
	 * 
	 * @param	void
	 * 
	 * @return	string
	**/
	public function getAccessControllerType()
	{
		return $this->mType;
	}

	/**
	 * _getAuthType
	 * 
	 * @param	string $action	//view, edit, manage, etc
	 * 
	 * @return	string
	**/
	protected function _getAuthType(/*** string ***/ $action)
	{
		@$authSetting = XCube_Root::getSingleton()->mContext->mModuleConfig['auth_type'];
		$authType = isset($authSetting) ? explode('|', $authSetting) : array('viewer', 'poster', 'manager');
		switch($action){
			case self::VIEW:
				return trim($authType[0]);
				break;
			case self::POST:
				return trim($authType[1]);
				break;
			case self::MANAGE:
				return trim($authType[2]);
				break;
		}
	}

	/**
	 * check
	 * 
	 * @param	int		$categoryId
	 * @param	string	$action	action
	 * 
	 * @return	string
	**/
	abstract public function check(/*** int ***/ $categoryId, /*** string ***/ $action);

	/**
	 * getTree
	 * 
	 * @param	string	$action		edit, view, manage, etc
	 * 
	 * @return	XoopsSimpleObject[]
	**/
	abstract public function getTree($action);

	/**
	 * getTitle
	 * 
	 * @param	int		$categoryId
	 * 
	 * @return	string
	**/
	abstract public function getTitle(/*** int ***/ $categoryId);

	/**
	 * getTitleList
	 * 
	 * @param	void
	 * 
	 * @return	string[]
	**/
	abstract public function getTitleList();

	/**
	 * getPermittedIdList
	 * 
	 * @param	string	$action		//edit, view, manage, etc
	 * @param	int		$categoryId
	 * 
	 * @return	int[]
	**/
	abstract public function getPermittedIdList(/*** string ***/ $action, /*** int ***/ $categoryId=0);
}

?>
