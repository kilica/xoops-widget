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
 * Widget_Utils
**/
class Widget_Utils
{
    /**
     * getModuleConfig
     * 
     * @param   string  $name
     * @param   bool  $optional
     * 
     * @return  XoopsObjectHandler
    **/
    public static function getModuleConfig(/*** string ***/ $dirname, /*** mixed ***/ $key)
    {
        $handler = self::getXoopsHandler('config');
        $conf = $handler->getConfigsByDirname($dirname);
        return (isset($conf[$key])) ? $conf[$key] : null;
    }

    /**
     * &getXoopsHandler
     * 
     * @param   string  $name
     * @param   bool  $optional
     * 
     * @return  XoopsObjectHandler
    **/
    public static function &getXoopsHandler(/*** string ***/ $name,/*** bool ***/ $optional = false)
    {
        // TODO will be emulated xoops_gethandler
        return xoops_gethandler($name,$optional);
    }

    /**
     * getPermittedIdList
     * 
     * @param   string  $dirname
     * @param   string  $action
     * @param   int     $categeoryId
     * 
     * @return  XoopsObjectHandler
    **/
    public static function getPermittedIdList(/*** string ***/ $dirname, /*** string ***/ $action=null, /*** int ***/ $categoryId=0)
    {
        $action = isset($action) ? $action : Widget_AuthType::VIEW;
    
        $accessController = self::getAccessControllerModule($dirname);
    
        if(! is_object($accessController)) return;
    
        $role = $accessController->get('role');
        $idList = array();
        if($role=='cat'){
            $delegateName = 'Legacy_Category.'.$accessController->dirname().'.GetPermittedIdList';
            XCube_DelegateUtils::call($delegateName, new XCube_Ref($idList), $accessController->dirname(), self::getActor($dirname, Widget_AuthType::VIEW), Legacy_Utils::getUid(), $categoryId);
        }
        elseif($role=='group'){
            $delegateName = 'Legacy_Group.'.$accessController->dirname().'.GetGroupIdListByAction';
            XCube_DelegateUtils::call($delegateName, new XCube_Ref($idList), $accessController->dirname(), $dirname, 'page', Widget_AuthType::VIEW);
        }
        else{   //has user group permission ?
            ///TODO
        }
        return $idList;
    }

    /**
     * getAccessControllerModule
     * 
     * @param   string  $dirname
     * 
     * @return  XoopsModule
    **/
    public static function getAccessControllerModule(/*** string ***/ $dirname)
    {
        $handler = self::getXoopsHandler('module');
        return $handler->getByDirname(self::getModuleConfig($dirname, 'access_controller'));
    }

    /**
     * getAccessControllerObject
     * 
     * @param   string  $dirname
     * @param   string  $dataname
     * 
     * @return  Widget_AbstractAccessController
    **/
    public static function getAccessControllerObject(/*** string ***/ $dirname, /*** string ***/ $dataname)
    {
        $server = self::getModuleConfig($dirname, 'access_controller');
    
        //get server's role
        $handler = self::getXoopsHandler('module');
        $module = $handler->getByDirname($server);
        if(! $module){
            require_once WIDGET_TRUST_PATH . '/class/NoneAccessController.class.php';
            $accessController = new Widget_NoneAccessController($server, $dirname, $dataname);
            return $accessController;
        }
        $role = $module->get('role');
    
        switch($role){
        case 'cat':
            require_once WIDGET_TRUST_PATH . '/class/CatAccessController.class.php';
            $accessController = new Widget_CatAccessController($server, $dirname, $dataname);
            break;
        case 'group':
            require_once WIDGET_TRUST_PATH . '/class/GroupAccessController.class.php';
            $accessController = new Widget_GroupAccessController($server, $dirname, $dataname);
            break;
        case 'none':
        default:
            require_once WIDGET_TRUST_PATH . '/class/NoneAccessController.class.php';
            $accessController = new Widget_NoneAccessController($server, $dirname, $dataname);
            break;
        }
        return $accessController;
    }

    /**
     * getActor
     * 
     * @param   string  $dirname
     * @param   string  $action
     * 
     * @return  string
    **/
    public static function getActor(/*** string ***/ $dirname, /*** string ***/ $action)
    {
        $authSetting = self::getModuleConfig($dirname, 'auth_type');
        $authType = isset($authSetting) ? explode('|', $authSetting) : array('viewer', 'poster', 'manager', 'manager');
        switch($action){
            case Widget_AuthType::VIEW:
                return trim($authType[0]);
                break;
            case Widget_AuthType::POST:
                return trim($authType[1]);
                break;
            case Widget_AuthType::MANAGE:
                return trim($authType[3]);
                break;
        }
    }

    /**
     * getListCriteria
     * 
     * @param   string  $dirname
     * @param   int     $categoryId
     * @param   int     $order
     * @param   Lenum_Status    $status
     * 
     * @return  XoopsObjectHandler
    **/
    public static function getListCriteria(/*** string ***/ $dirname, /*** int ***/ $categoryId=null, /*** int ***/ $order=null, /*** int ***/ $status=Lenum_Status::PUBLISHED)
    {
        $accessController = self::getAccessControllerModule($dirname);
    
        $cri = new CriteriaCompo();
    
        //category
        if(isset($categoryId)){
            $cri->add(new Criteria('category_id', $categoryId));
        }
        else{
            //get permitted categories to show
            if($accessController instanceof XoopsModule && ($accessController->get('role')=='cat' || $accessController->get('role')=='group')){
                $idList = self::getPermittedIdList($dirname);
                if(count($idList)>0){
                    $cri->add(new Criteria('category_id', $idList, 'IN'));
                }
            }
        }
    
        return $cri;
    }

    public static function getPluginList()
    {
        $files = glob(WIDGET_TRUST_PATH."/plugins/*/config.ini");
        foreach($files as $file){
            $config = parse_ini_file($file, true);
            $pluginArr[$config['type']] = $config;
        }
        return $pluginArr;
    }

    /**
     * Inserts the specified template to DB.
     *
     * @warning
     *
     * This function depends the specific spec of Legacy_RenderSystem, but this
     * static function is needed by the 2nd installer of Legacy System.
     *
     * @static
     * @param Widget_InstanceObject $instance
     * @return bool
     *
     * @note This is not usefull a litte for custom-installers.
     * @todo We'll need the way to specify the template by identity or others.
     */
    public static function installWidgetTemplate(Widget_InstanceObject $instance)
    {
        $instance->loadOptionValues();
        $dirname = $instance->getDirname();
        $mid = self::_getMid($dirname);

        $tplHandler =& xoops_gethandler('tplfile');

        $tpldata = file_get_contents($instance->getTemplatePath());
        if ($tpldata == false){
            return false;
        }
        preg_match_all('/\[\[(.*)\]\]/', $tpldata, $matches);
        foreach($matches[1] as $field){
            $tpldata = str_replace('[['.$field.']]', $instance->getOptionValue($field), $tpldata);
        }
        //
        // Create template file object, then store it.
        //
        if($instance->isNew()){
            $tplfile = $tplHandler->create();
        }
        else{
            $tplfile = array_shift($tplHandler->find('default', 'widget', $mid, null, $instance->getTemplateName()));
            if(! $tplfile){
                $tplfile = $tplHandler->create();
            }
        }
        $tplfile->setVar('tpl_refid', $mid);
        $tplfile->setVar('tpl_lastimported', 0);
        $tplfile->setVar('tpl_lastmodified', time());

        $tplfile->setVar('tpl_type', 'widget');

        $tplfile->setVar('tpl_source', $tpldata, true);
        $tplfile->setVar('tpl_module', $dirname);
        $tplfile->setVar('tpl_tplset', 'default');
        $tplfile->setVar('tpl_file', $instance->getTemplateName(), true);

        $description = isset($plugin['description']) ? $plugin['description'] : '';
        $tplfile->setVar('tpl_desc', $description, true);

        return $tplHandler->insert($tplfile);
    }

    public static function uninstallWidgetTemplates(Widget_InstanceObject $instance)
    {
        $tplHandler =& xoops_gethandler('tplfile');
        $dirname = $instance->getDirname();

        $mid = self::_getMid($dirname);
        $delTemplates =& $tplHandler->find(null, 'widget', $mid, null, $instance->getTemplateName());

        if (is_array($delTemplates) && count($delTemplates) > 0) {
            //
            // clear cache
            //
            $xoopsTpl =new XoopsTpl();
            $xoopsTpl->clear_cache(null, "mod_" . $mid);

            foreach ($delTemplates as $tpl) {
                $tplHandler->delete($tpl);
            }
        }
    }

    public static function installBlock(Widget_InstanceObject $instance)
    {
        $handler = self::getXoopsHandler('block');
        $mid = self::_getMid($instance->getDirname());

        $blockList = $handler->getByModule($mid);
        $max = 0;
        foreach($blockList as $item){
            $max = ( $item->get('func_num')>$max) ? $item->get('func_num') : $max;
        }

        $info = array(
            'func_num'          => $max+1,
            'file'              => 'ViewBlock.class.php',
            'show_func'             => 'cl::ViewBlock',
            'name'              => $instance->getShow('title'),
            'description'       => $instance->getShow('description'),
            'options'           => $instance->get('instance_id'),
            'template'          => $instance->getTemplateName(),
            'show_all_module'   => false,
            'visible_any'       => false,
            'can_clone'            => true,
        );
        $block = $handler->createByInfo($info);
        $block->set('func_num', $max+1);
        $block->set('dirname', $instance->getDirname());
        $block->set('mid', $mid);
        return $handler->insert($block);
    }

    public static function getBid($dirname, $instanceId)
    {
        $mid = self::_getMid($dirname);
        $handler = self::getXoopsHandler('block');
        $blockList = $handler->getByModule($mid);
        foreach($blockList as $block){
            $options = explode('|',$block->get('options'));
            if($options[0]==$instanceId){
                return $block->get('bid');
            }
        }
    }

    protected static function _getMid($dirname)
    {
        $moduleHandler = xoops_gethandler('module');
        $module = $moduleHandler->getByDirname($dirname);
        return $module->get('mid');
    }
}

?>
