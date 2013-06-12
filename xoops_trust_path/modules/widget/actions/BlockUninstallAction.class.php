<?php
/**
 *
 * @package Legacy
 * @version $Id: BlockUninstallAction.class.php,v 1.3 2008/09/25 15:11:53 kilica Exp $
 * @copyright Copyright 2005-2007 XOOPS Cube Project  <https://github.com/xoopscube/legacy>
 * @license https://github.com/xoopscube/legacy/blob/master/docs/GPL_V2.txt GNU GENERAL PUBLIC LICENSE Version 2
 *
 */

if (!defined('XOOPS_ROOT_PATH')) exit();

require_once WIDGET_TRUST_PATH . "/class/AbstractEditAction.class.php";
require_once WIDGET_TRUST_PATH . "/forms/BlockUninstallForm.class.php";

class Widget_BlockUninstallAction extends Widget_AbstractEditAction
{
    function _getId()
    {
        $bid = $this->mRoot->mContext->mRequest->getRequest('bid');
        if($bid>0){
            return $bid;
        }
        $instanceId = $this->mRoot->mContext->mRequest->getRequest('instance_id');
        return isset($instanceId) ? Widget_Utils::getBid($this->mAsset->mDirname, $instanceId) : 0;
    }

    function &_getHandler()
    {
        $handler =& xoops_getmodulehandler('newblocks', 'legacy');
        return $handler;
    }

    function _setupActionForm()
    {
        $this->mActionForm =new Widget_BlockUninstallForm();
        $this->mActionForm->prepare();
    }
    
    function _isEditable()
    {
        if (is_object($this->mObject)) {
            return ($this->mObject->get('visible') == 1);
        }
        else {
            return false;
        }
    }

    function executeViewInput(&$render)
    {
        $this->mRoot->mLanguageManager->loadModuleAdminMessageCatalog('legacy');
        $render->setTemplateName($this->mAsset->mDirname."_block_uninstall.html");
        $render->setAttribute('actionForm', $this->mActionForm);
        
        //
        // lazy loading
        //
        $this->mObject->loadModule();
        $this->mObject->loadColumn();
        $this->mObject->loadCachetime();
        
        $render->setAttribute('object', $this->mObject);
    }

    /**
     * executeViewSuccess
     *
     * @param   XCube_RenderTarget  &$render
     *
     * @return  void
     **/
    public function executeViewSuccess(/*** XCube_RenderTarget ***/ &$render)
    {
        $this->mRoot->mController->executeForward(XOOPS_URL.'/modules/'.$this->mAsset->mDirname);
    }

    /**
     * executeViewError
     *
     * @param   XCube_RenderTarget  &$render
     *
     * @return  void
     **/
    public function executeViewError(/*** XCube_RenderTarget ***/ &$render)
    {
        $this->mRoot->mController->executeRedirect(XOOPS_URL.'/modules/'.$this->mAsset->mDirname, 1, _MD_WIDGET_ERROR_DBUPDATE_FAILED);
    }

}

?>
