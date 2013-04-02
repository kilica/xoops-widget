<?php
/**
 * @file
 * @package widget
 * @version $Id$
**/

if(!defined('XOOPS_ROOT_PATH'))
{
	exit();
}

/**
 * Widget_ViewBlock
**/
class Widget_ViewBlock extends Legacy_BlockProcedure
{
	/**
	 * @var Widget_ItemsHandler
	 * 
	 * @private
	**/
	var $_mHandler = null;
	
	/**
	 * @var Widget_ItmesObject
	 * 
	 * @private
	**/
	var $_mObject = null;
	
	/**
	 * @var string[]
	 * 
	 * @private
	**/
	var $_mOptions = array();
	
	/**
	 * prepare
	 * 
	 * @param   void
	 * 
	 * @return  bool
	 * 
	 * @public
	**/
	function prepare()
	{
		$ret =  parent::prepare() && $this->_parseOptions() && $this->_setupObject();
		return $ret;
	}
	
	/**
	 * _parseOptions
	 * 
	 * @param   void
	 * 
	 * @return  bool
	 * 
	 * @private
	**/
	function _parseOptions()
	{
		$opts = explode('|',$this->_mBlock->get('options'));
		$this->_mOptions = array(
			'id'	=> intval($opts[0])
		);
		return true;
	}
	
	/**
	 * getBlockOption
	 * 
	 * @param   string  $key
	 * 
	 * @return  string
	 * 
	 * @public
	**/
	function getBlockOption($key)
	{
		return isset($this->_mOptions[$key]) ? $this->_mOptions[$key] : null;
	}
	
	/**
	 * getOptionForm
	 * 
	 * @param   void
	 * 
	 * @return  string
	 * 
	 * @public
	**/
	function getOptionForm()
	{
		if(!$this->prepare())
		{
			return null;
		}
		$form = '<label for="'. $this->_mBlock->get('dirname') .'block_limit">Widget ID</label>&nbsp;:
		<input type="text" size="5" name="options[0]" id="'. $this->_mBlock->get('dirname') .'block_id" value="'.$this->getBlockOption('id').'" />';
		return $form;
	}

	/**
	 * _setupObject
	 * 
	 * @param   void
	 * 
	 * @return  bool
	 * 
	 * @private
	**/
	function _setupObject()
	{
		//get module asset for handlers
		$asset = null;
		XCube_DelegateUtils::call(
			'Module.widget.Global.Event.GetAssetManager',
			new XCube_Ref($asset),
			$this->_mBlock->get('dirname')
		);

		$this->_mHandler =& $asset->getObject('handler', 'instance');
		$this->_mObject = $this->_mHandler->get($this->getBlockOption('id'));

		return true;
	}

	/**
	 * execute
	 * 
	 * @param   void
	 * 
	 * @return  void
	 * 
	 * @public
	**/
	function execute()
	{
		if(! $this->_mObject) return;
		$this->_mObject->loadPlugin();
	
		$pluginFile = WIDGET_TRUST_PATH.'/plugins/'.$this->_mObject->getShow('type').'/plugin.php';
		if(file_exists($pluginFile)){
			require_once $pluginFile;
            $obj = $this->_mObject;
			call_user_func_array(array('Widget_'.ucfirst($this->_mObject->getShow('type')).'_Plugin', 'execute'), array($obj));
		}
		$root =& XCube_Root::getSingleton();
	
		$render =& $this->getRenderTarget();
		$render->setTemplateName($this->_mObject->getTemplateName());
		$render->setAttribute('block', $this->_mObject);
		$render->setAttribute('bid', $this->_mBlock->get('bid'));
		$render->setAttribute('dirname', $this->_mBlock->get('dirname'));
		$renderSystem =& $root->getRenderSystem($this->getRenderSystemName());
		$renderSystem->renderBlock($render);
	}
}

?>
