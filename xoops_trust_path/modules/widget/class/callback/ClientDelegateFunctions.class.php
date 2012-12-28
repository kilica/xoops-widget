<?php
/**
 * @package widget
 */

if (!defined('XOOPS_ROOT_PATH')) exit();


class Widget_ImageClientDelegate
{
	/**
	 * getClientList
	 *
	 * @param mixed[]	&$list
	 *  @list[]['dirname']
	 *  @list[]['dataname']
	 * @param string	$dirname
	 *
	 * @return	void
	 */ 
	public static function getClientList(/*** mixed[] ***/ &$list, /*** string ***/ $dirname)
	{
		$dirnames = Legacy_Utils::getDirnameListByTrustDirname('widget');
	
		//don't call this method multiple times when site owner duplicate.
		static $isCalled = false;
		if($isCalled === true){
			return;
		}
	
		foreach($dirnames as $dir){
			$list[] = array('dirname'=>$dir, 'dataname'=>'instance');
		}
	
		$isCalled = true;
	}
}

?>
