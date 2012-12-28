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

if(!defined('WIDGET_TRUST_PATH'))
{
    define('WIDGET_TRUST_PATH',XOOPS_TRUST_PATH . '/modules/widget');
}

define('WIDGET_PREFIX', 'p_');

require_once WIDGET_TRUST_PATH . '/class/WidgetUtils.class.php';
require_once WIDGET_TRUST_PATH . '/class/Enum.class.php';
require_once WIDGET_TRUST_PATH . '/class/Plugin.interface.php';

/**
 * Widget_AssetPreloadBase
**/
class Widget_AssetPreloadBase extends XCube_ActionFilter
{
    public $mDirname = null;

    /**
     * prepare
     * 
     * @param   string  $dirname
     * 
     * @return  void
    **/
    public static function prepare(/*** string ***/ $dirname)
    {
        static $setupCompleted = false;
        if(!$setupCompleted)
        {
            $setupCompleted = self::_setup($dirname);
        }
    }

    /**
     * _setup
     * 
     * @param   void
     * 
     * @return  bool
    **/
    public static function _setup(/*** string ***/ $dirname)
    {
        $root =& XCube_Root::getSingleton();
        $instance = new self($root->mController);
        $instance->mDirname = $dirname;
        $root->mController->addActionFilter($instance);
        return true;
    }

    /**
     * preBlockFilter
     * 
     * @param   void
     * 
     * @return  void
    **/
    public function preBlockFilter()
    {
        $file = WIDGET_TRUST_PATH . '/class/callback/DelegateFunctions.class.php';
        $this->mRoot->mDelegateManager->add('Module.widget.Global.Event.GetAssetManager','Widget_AssetPreloadBase::getInstance');
        $this->mRoot->mDelegateManager->add('Legacy_Utils.CreateModule','Widget_AssetPreloadBase::getModule');
        $this->mRoot->mDelegateManager->add('Legacy_Utils.CreateBlockProcedure','Widget_AssetPreloadBase::getBlock');
        $this->mRoot->mDelegateManager->add('Module.'.$this->mDirname.'.Global.Event.GetNormalUri','Widget_CoolUriDelegate::getNormalUri', $file);

        // client delegates
        $cfile = WIDGET_TRUST_PATH . '/class/callback/ClientDelegateFunctions.class.php';
        $this->mRoot->mDelegateManager->add('Legacy_ImageClient.GetClientList','Widget_ImageClientDelegate::getClientList', $cfile);
    }

    /**
     * getInstance
     * 
     * @param   Widget_AssetManager  &$obj
     * @param   string  $dirname
     * 
     * @return  void
    **/
    public static function getInstance(/*** Widget_AssetManager ***/ &$obj,/*** string ***/ $dirname)
    {
        require_once WIDGET_TRUST_PATH . '/class/AssetManager.class.php';
        $obj = Widget_AssetManager::getInstance($dirname);
    }

    /**
     * getModule
     * 
     * @param   Legacy_AbstractModule  &$obj
     * @param   XoopsModule  $module
     * 
     * @return  void
    **/
    public static function getModule(/*** Legacy_AbstractModule ***/ &$obj,/*** XoopsModule ***/ $module)
    {
        if($module->getInfo('trust_dirname') == 'widget')
        {
            require_once WIDGET_TRUST_PATH . '/class/Module.class.php';
            $obj = new Widget_Module($module);
        }
    }

    /**
     * getBlock
     * 
     * @param   Legacy_AbstractBlockProcedure  &$obj
     * @param   XoopsBlock  $block
     * 
     * @return  void
    **/
    public static function getBlock(/*** Legacy_AbstractBlockProcedure ***/ &$obj,/*** XoopsBlock ***/ $block)
    {
        $moduleHandler =& Widget_Utils::getXoopsHandler('module');
        $module =& $moduleHandler->get($block->get('mid'));
        if(is_object($module) && $module->getInfo('trust_dirname') == 'widget')
        {
            require_once WIDGET_TRUST_PATH . '/blocks/' . $block->get('func_file');
            $className = 'Widget_' . substr($block->get('show_func'), 4);
            $obj = new $className($block);
        }
    }
}

?>
