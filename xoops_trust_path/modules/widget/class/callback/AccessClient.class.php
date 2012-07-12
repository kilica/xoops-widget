<?php
/**
 * @package widget
 * @version $Id: AccessClient.class.php,v 1.0 $
 */

if (!defined('XOOPS_ROOT_PATH')) exit();

/**
 * category client delegate
**/
class Widget_CatClientDelegate implements Legacy_iCategoryClientDelegate
{
    /**
     * getClientList
     *
     * @param mixed[]   &$list
     *  @list[]['dirname']
     *  @list[]['dataname']
     *  @list[]['fieldname']
     * @param string    $cDirname   Legacy_Category module's dirname
     *
     * @return  void
     */ 
    public static function getClientList(/*** mixed[] ***/ &$list, /*** string ***/ $cDirname)
    {
        //don't call this method multiple times when site owner duplicate.
        static $isCalled = false;
        if($isCalled === true){
            return;
        }
    
        //get dirname list of widget
        $dirnames = Legacy_Utils::getDirnameListByTrustDirname(basename(dirname(dirname(dirname(__FILE__)))));
    
        foreach($dirnames as $dir){
            //setup client module info
            if(Widget_Utils::getModuleConfig($dir, 'access_controller')==$cDirname){
           $list[] = array('dirname'=>$dir, 'dataname'=>'instance', 'fieldname'=>'category_id');

            }
        }
    
        $isCalled = true;
    }

    /**
     * getClientData
     *
     * @param mixed     &$list
     * @param string    $dirname
     * @param string    $dataname
     * @param string    $fieldname
     * @param int       $catId
     *
     * @return  void
     */ 
    public static function getClientData(/*** mixed ***/ &$list, /*** string ***/ $dirname, /*** string ***/ $dataname, /*** string ***/ $fieldname, /*** int ***/ $catId)
    {
        //default
        $limit = 5;
        $start =0;
    
        $handler = Legacy_Utils::getModuleHandler($dataname, $dirname);
        if(! $handler){
            return;
        }
        //setup client module info
        $cri = Widget_Utils::getListCriteria($dirname, $catId);
        $objs = $handler->getObjects($cri, $limit, $start);
        if(count($objs)>0){
	        $list['dirname'][] = $dirname;
	        $list['dataname'][] = $dataname;
	        $list['data'][] = $objs;
	        $handler = xoops_gethandler('module');
	        $module = $handler->getByDirname($dirname);
	        $list['title'][] = $module->name();
	        $list['template_name'][] = 'db:'.$dirname . '_'. $dataname .'_inc.html';
	    }
    }
}

/**
 * group client delegate
**/
class Widget_GroupClientDelegate implements Legacy_iGroupClientDelegate
{
    /**
     * getClientList
     *
     * @param mixed[]   &$list
     *  @list[]['dirname']
     *  @list[]['dataname']
     *  @list[]['fieldname']
     * @param string    $gDirname   Legacy_Group module's dirname
     *
     * @return  void
     */ 
    public static function getClientList(/*** mixed[] ***/ &$list, /*** string ***/ $gDirname)
    {
        //don't call this method multiple times when site owner duplicate this module.
        static $isCalled = false;
        if($isCalled === true){
            return;
        }
    
        //get dirname list of widget
        $dirnames = Legacy_Utils::getDirnameListByTrustDirname(basename(dirname(dirname(dirname(__FILE__)))));
    
        foreach($dirnames as $dir){
            //setup client module info
            if(Widget_Utils::getModuleConfig($dir, 'access_controller')==$gDirname){
                $list[] = array('dirname'=>$dir, 'dataname'=>'{tablename}', 'fieldname'=>'category_id');
            }
        }
    
        $isCalled = true;
    }

    /**
     * getClientData
     *
     * @param mixed     &$list
     * @param string    $dirname
     * @param string    $dataname
     * @param string    $fieldname
     * @param int       $groupId
     *
     * @return  void
     */ 
    public static function getClientData(/*** mixed ***/ &$list, /*** string ***/ $dirname, /*** string ***/ $dataname, /*** string ***/ $fieldname, /*** int ***/ $groupId)
    {
        //default
        $limit = 5;
        $start =0;
    
        $handler = Legacy_Utils::getModuleHandler($dataname, $dirname);
        if(! $handler){
            return;
        }
        //setup client module info
        $cri = Widget_Utils::getListCriteria($dirname, $groupId);
        $objs = $handler->getObjects($cri, $limit, $start);
        if(count($objs)>0){
	        $list['dirname'][] = $dirname;
	        $list['dataname'][] = $dataname;
	        $list['data'][] = $objs;
	        $handler = xoops_gethandler('module');
	        $module = $handler->getByDirname($dirname);
	        $list['title'][] = $module->name();
	        $list['template_name'][] = 'db:'.$dirname . '_' .$dataname. '_inc.html';
	    }
    }

    /**
     * getActionList
     * Get client module's actions(view, edit, etc) to set their permission
     * by member's group rank.
     *
     * @param mixed[]   &$list
     *  $list['action'][]   string
     *  $list['rank'][]     Lenum_GroupRank
     *  $list['title'][]    string
     *  $list['desctiption'][]  string
     * @param string    $dirname
     * @param string    $dataname
     *
     * @return  void
     */ 
    public static function getActionList(&$list, $dirname, $dataname)
    {
        $dirnames = Legacy_Utils::getDirnameListByTrustDirname(basename(dirname(dirname(dirname(__FILE__)))));
        if(! in_array($dirname, $dirnames)){
            return;
        }
    
        //don't call this method multiple times when site owner duplicate.
        static $isCalled = false;
        if($isCalled === true){
            return;
        }
        XCube_Root::getSingleton()->mLanguageManager->loadModuleMessageCatalog($dirname);
    
        //view
        $list['action'][] = 'view';
        $list['rank'][] = Lenum_GroupRank::GUEST;
        $list['title'][] = _MD_WIDGET_TITLE_ACTION_VIEW;
        $list['description'][] = _MD_WIDGET_DESC_ACTION_VIEW;
    
        //post
        $list['action'][] = 'post';
        $list['rank'][] = Lenum_GroupRank::REGULAR;
        $list['title'][] = _MD_WIDGET_TITLE_ACTION_POST;
        $list['description'][] = _MD_WIDGET_DESC_ACTION_POST;
    
        //review
        $list['action'][] = 'review';
        $list['rank'][] = Lenum_GroupRank::STAFF;
        $list['title'][] = _MD_WIDGET_TITLE_ACTION_REVIEW;
        $list['description'][] = _MD_WIDGET_DESC_ACTION_REVIEW;
    
        //manage
        $list['action'][] = 'manage';
        $list['rank'][] = Lenum_GroupRank::STAFF;
        $list['title'][] = _MD_WIDGET_TITLE_ACTION_MANAGE;
        $list['description'][] = _MD_WIDGET_DESC_ACTION_MANAGE;
    
        $isCalled = true;
    }
}
?>
