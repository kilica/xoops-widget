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

require_once WIDGET_TRUST_PATH . '/class/AbstractEditAction.class.php';
require_once WIDGET_TRUST_PATH . "/forms/BlockEditForm.class.php";

/**
 * Widget_InstanceEditAction
**/
class Widget_BlockEditAction extends Widget_AbstractEditAction
{
    const DATANAME = 'block';
    protected $_mOptionForm = null;

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
        $this->mActionForm =new Widget_BlockEditForm();
        $this->mActionForm->prepare();
    }

    function getDefaultView()
    {
        $this->mObject->loadGroup();
        $this->mObject->loadBmodule();

        return parent::getDefaultView();
    }

    /**
     * prepare
     * 
     * @param   void
     * 
     * @return  bool
    **/
    public function prepare()
    {
        $this->mRoot->mLanguageManager->loadModuleMessageCatalog('legacy');
        $this->mRoot->mLanguageManager->loadModinfoMessageCatalog('legacy');
        $this->mRoot->mLanguageManager->loadModuleAdminMessageCatalog('legacy');
        return parent::prepare();
    }

    public function execute()
    {
        $ret = parent::execute();
        if($ret != WIDGET_FRAME_VIEW_SUCCESS){
            return $ret;
        }

        //
        // Reset block_module_link.
        //
        $handler =& xoops_getmodulehandler('block_module_link', 'legacy');
        $handler->deleteAll(new Criteria('block_id', $this->mObject->get('bid')));
        foreach ($this->mObject->mBmodule as $bmodule) {
            //
            // If object is new, $bmodule isn't set bid yet.
            //
            $bmodule->set('block_id', $this->mObject->get('bid'));
            $handler->insert($bmodule);
        }

        //
        // Insert group permissions.
        //
        $currentGroupid = array();
        foreach ($this->mObject->mGroup as $group) {
            $currentGroupid[] = $group->get('groupid');
        }

        $permHandler =& xoops_gethandler('groupperm');
        $criteria =new CriteriaCompo();
        $criteria->add(new Criteria('gperm_modid', 1));
        $criteria->add(new Criteria('gperm_itemid', $this->mObject->get('bid')));
        $criteria->add(new Criteria('gperm_name', 'block_read'));

        $gpermArr =&  $permHandler->getObjects($criteria);
        foreach ($gpermArr as $gperm) {
            if (!in_array($gperm->get('gperm_groupid'), $currentGroupid)) {
                $permHandler->delete($gperm);
            }
        }

        foreach ($this->mObject->mGroup as $group) {
            $insertFlag = true;
            foreach ($gpermArr as $gperm) {
                if ($gperm->get('gperm_groupid') == $group->get('groupid')) {
                    $insertFlag = false;
                }
            }

            if ($insertFlag) {
                $gperm =& $permHandler->create();
                $gperm->set('gperm_modid', 1);
                $gperm->set('gperm_groupid', $group->get('groupid'));
                $gperm->set('gperm_itemid', $this->mObject->get('bid'));
                $gperm->set('gperm_name', 'block_read');

                $permHandler->insert($gperm);
            }
        }

        return $ret;
    }

    public function executeViewInput(&$render)
    {
        $render->setTemplateName($this->mAsset->mDirname . '_block_edit.html');
        $render->setAttribute('dirname', $this->mAsset->mDirname);
        $render->setAttribute('actionForm', $this->mActionForm);

        //
        // lazy loading
        //
        $this->mObject->loadModule();

        $render->setAttribute('object', $this->mObject);

        //
        // Build active modules list and set.
        //
        $handler =& xoops_gethandler('module');
        $moduleArr[0] =& $handler->create();
        $moduleArr[0]->set('mid', -1);
        $moduleArr[0]->set('name', _AD_LEGACY_LANG_TOPPAGE);

        $moduleArr[1] =& $handler->create();
        $moduleArr[1]->set('mid', 0);
        $moduleArr[1]->set('name', _AD_LEGACY_LANG_ALL_MODULES);

        $criteria =new CriteriaCompo();
        $criteria->add(new Criteria('hasmain', 1));
        $criteria->add(new Criteria('isactive', 1));

        $t_Arr =& $handler->getObjects($criteria);
        $moduleArr = array_merge($moduleArr, $t_Arr);
        $render->setAttribute('moduleArr', $moduleArr);

        $handler =& xoops_getmodulehandler('columnside', 'legacy');
        $columnSideArr =& $handler->getObjects();
        $render->setAttribute('columnSideArr', $columnSideArr);

        $handler =& xoops_gethandler('group');
        $groupArr =& $handler->getObjects();
        $render->setAttribute('groupArr', $groupArr);

        //
        // Build cachetime list and set.
        //
        $handler =& xoops_gethandler('cachetime');
        $cachetimeArr =& $handler->getObjects();
        $render->setAttribute('cachetimeArr', $cachetimeArr);

        //
        // Get html of option form rendered.
        //
        $this->_mOptionForm = $this->_getOptionForm();
        $render->setAttribute('hasVisibleOptionForm', $this->_hasVisibleOptionForm());
        $render->setAttribute('optionForm', $this->_mOptionForm);
    }

    /**
     * @private
     * Gets a value indicating whether the option form needs the row in the table to display its form.
     * @remark This method is requred for the compatibility with XOOPS2.
     * @return bool
     */
    function _hasVisibleOptionForm()
    {
        $block =& Legacy_Utils::createBlockProcedure($this->mObject);
        return $block->_hasVisibleOptionForm();
    }

    /**
     * Gets rendered HTML buffer of the block optional edit form.
     */
    function _getOptionForm()
    {
        $block =& Legacy_Utils::createBlockProcedure($this->mObject);
        return $block->getOptionForm();
    }


}
?>
