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

require_once WIDGET_TRUST_PATH . '/class/AbstractFilterForm.class.php';

define('WIDGET_INSTANCE_SORT_KEY_INSTANCE_ID', 1);
define('WIDGET_INSTANCE_SORT_KEY_TITLE', 2);
define('WIDGET_INSTANCE_SORT_KEY_CATEGORY_ID', 3);
define('WIDGET_INSTANCE_SORT_KEY_TYPE', 4);
define('WIDGET_INSTANCE_SORT_KEY_TEMPLATE', 5);
define('WIDGET_INSTANCE_SORT_KEY_OPTIONS', 6);
define('WIDGET_INSTANCE_SORT_KEY_POSTTIME', 7);

define('WIDGET_INSTANCE_SORT_KEY_DEFAULT', WIDGET_INSTANCE_SORT_KEY_INSTANCE_ID);

/**
 * Widget_InstanceFilterForm
**/
class Widget_InstanceFilterForm extends Widget_AbstractFilterForm
{
    public /*** string[] ***/ $mSortKeys = array(
 	   WIDGET_INSTANCE_SORT_KEY_INSTANCE_ID => 'instance_id',
 	   WIDGET_INSTANCE_SORT_KEY_TITLE => 'title',
 	   WIDGET_INSTANCE_SORT_KEY_CATEGORY_ID => 'category_id',
 	   WIDGET_INSTANCE_SORT_KEY_TYPE => 'type',
 	   WIDGET_INSTANCE_SORT_KEY_TEMPLATE => 'template',
 	   WIDGET_INSTANCE_SORT_KEY_OPTIONS => 'options',
 	   WIDGET_INSTANCE_SORT_KEY_POSTTIME => 'posttime',

    );

    /**
     * getDefaultSortKey
     * 
     * @param   void
     * 
     * @return  void
    **/
    public function getDefaultSortKey()
    {
        return WIDGET_INSTANCE_SORT_KEY_DEFAULT;
    }

    /**
     * fetch
     * 
     * @param   void
     * 
     * @return  void
    **/
    public function fetch()
    {
        parent::fetch();
    
        $root =& XCube_Root::getSingleton();
    
		if (($value = $root->mContext->mRequest->getRequest('instance_id')) !== null) {
			$this->mNavi->addExtra('instance_id', $value);
			$this->_mCriteria->add(new Criteria('instance_id', $value));
		}
		if (($value = $root->mContext->mRequest->getRequest('title')) !== null) {
			$this->mNavi->addExtra('title', $value);
			$this->_mCriteria->add(new Criteria('title', $value));
		}
		if (($value = $root->mContext->mRequest->getRequest('category_id')) !== null) {
			$this->mNavi->addExtra('category_id', $value);
			$this->_mCriteria->add(new Criteria('category_id', $value));
		}
		if (($value = $root->mContext->mRequest->getRequest('type')) !== null) {
			$this->mNavi->addExtra('type', $value);
			$this->_mCriteria->add(new Criteria('type', $value));
		}
		if (($value = $root->mContext->mRequest->getRequest('template')) !== null) {
			$this->mNavi->addExtra('template', $value);
			$this->_mCriteria->add(new Criteria('template', $value));
		}
		if (($value = $root->mContext->mRequest->getRequest('options')) !== null) {
			$this->mNavi->addExtra('options', $value);
			$this->_mCriteria->add(new Criteria('options', $value));
		}
		if (($value = $root->mContext->mRequest->getRequest('posttime')) !== null) {
			$this->mNavi->addExtra('posttime', $value);
			$this->_mCriteria->add(new Criteria('posttime', $value));
		}

    
        $this->_mCriteria->addSort($this->getSort(), $this->getOrder());
    }
}

?>
