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
 * Widget_NoneAccessController
**/
class Widget_NoneAccessController extends Widget_AbstractAccessController
{
	public $mType = 'none';

	/**
	 * __construct
	 * 
	 * @param	string	$categoryDir	category module's dirname
	 * @param	string	$dirname	this module's dirname
	 * @param	string	$dataname	this module's dataname
	 * 
	 * @return	void
		**/
	public function __construct(/*** string ***/ $categoryDir, /*** string ***/ $dirname, /*** string ***/ $dataname)
	{
		$this->_mDirname = $dirname;
	}

	/**
	 * check
	 * 
	 * @param	int		$categoryId
	 * @param	string	$type
	 * 
	 * @return	bool
	**/
	public function check(/*** int ***/ $categoryId, /*** string ***/ $action)
	{
		$check = false;
		$context = XCube_Root::getSingleton()->mContext;
		switch($action){
		case self::POST:
			$check = ($context->mUser->isInRole('Site.RegisteredUser')) ? true : false;
			break;
		case self::MANAGE:
			$check = ($context->mUser->isInRole('Site.Owner')) ? true : false;
			break;
		case self::VIEW:
		default:
			$check = true;
			break;
		}
		return $check;
	}

	/**
	 * getTree
	 * 
	 * @param	string	$type
	 * 
	 * @return	array[]
	**/
	public function getTree($type)
	{
		return array();
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
		return null;
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
		return array();
	}

	/**
	 * getPermittedIdList
	 * 
	 * @param	string	$type
	 * @param	int		$categoryId
	 * 
	 * @return	int[]
	**/
	public function getPermittedIdList(/*** string ***/ $type, /*** int ***/ $categoryId=0)
	{
		return array();
	}
}

?>
