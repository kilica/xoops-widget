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

require_once WIDGET_TRUST_PATH . '/class/AbstractAccessController.class.php';

/**
 * Widget_CattAccessController
**/
class Widget_CatAccessController extends Widget_AbstractAccessController
{
	public $mType = 'cat';

	/**
	 * __construct
	 * 
	 * @param	string	$categoryDir
	 * @param	string	$dirname	this module's dirname
	 * @param	string	$dataname	this module's dataname
	 * 
	 * @return	void
		**/
	public function __construct(/*** string ***/ $categoryDir, /*** string ***/ $dirname, /*** string ***/ $dataname=null)
	{
		$this->_mDirname = $dirname;
		$this->_mCategoryDir = $categoryDir;
	}

	/**
	 * check
	 * 
	 * @param	int		$categoryId
	 * @param	string	$action
	 * 
	 * @return	bool
	**/
	public function check(/*** int ***/ $categoryId, /*** string ***/ $action)
	{
		$check = null;
		XCube_DelegateUtils::call('Legacy_Category.'.$this->_mCategoryDir.'.HasPermission', new XCube_Ref($check), $this->_mCategoryDir, $categoryId, $this->_getAuthType($action));
		return $check;
	}

	/**
	 * getTree
	 * 
	 * @param	string	$action
	 * 
	 * @return	Legacy_AbstractCatObject[]
	**/
	public function getTree($action)
	{
		$tree = null;
		XCube_DelegateUtils::call('Legacy_Category.'.$this->_mCategoryDir.'.GetTree', new XCube_Ref($tree), $this->_mCategoryDir, $this->_getAuthType($action));
		return $tree;
	}

	/**
	 * _getAuthType
	 * 
	 * @param	string $action
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
	 * getTitle
	 * 
	 * @param	int		$categoryId
	 * 
	 * @return	string
	**/
	public function getTitle(/*** int ***/ $categoryId)
	{
		$ret = null;
		XCube_DelegateUtils::call('Legacy_Category.'.$this->_mCategoryDir.'.GetTitle', new XCube_Ref($ret), $this->_mCategoryDir, $categoryId);
		return $ret;
	}

	/**
	 * getTitleList
	 * 
	 * @param	void
	 * 
	 * @return	string[]
	**/
	public function getTitleList()
	{
		$list = null;
		XCube_DelegateUtils::call('Legacy_Category.'.$this->_mCategoryDir.'.GetTitleList', new XCube_Ref($list), $this->_mCategoryDir);
		return $list;
	}

	/**
	 * getPermittedIdList
	 * 
	 * @param	string	$action
	 * @param	int		$categoryId
	 * 
	 * @return	int[]
	**/
	public function getPermittedIdList(/*** string ***/ $action, /*** int ***/ $categoryId=0)
	{
		$idList = array();
		XCube_DelegateUtils::call('Legacy_Category.'.$this->_mCategoryDir.'.GetPermittedIdList', new XCube_Ref($idList), $this->_mCategoryDir, $this->_getAuthType($action), Legacy_Utils::getUid(), $categoryId);
		return $idList;
	}
}

?>
