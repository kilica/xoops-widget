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
 * Widget_InstanceObject
**/
class Widget_InstanceObject extends Legacy_AbstractObject
{
	const PRIMARY = 'instance_id';
	const DATANAME = 'instance';
	public $mPlugin = array();
	public $mOptions = array();
	protected $_mPluginLoaded = false;

	/**
	 * __construct
	 * 
	 * @param   void
	 * 
	 * @return  void
	**/
	public function __construct()
	{
		parent::__construct();  
		$this->initVar('instance_id', XOBJ_DTYPE_INT, '', false);
		$this->initVar('title', XOBJ_DTYPE_STRING, '', false, 255);
		$this->initVar('category_id', XOBJ_DTYPE_INT, '', false);
		$this->initVar('type', XOBJ_DTYPE_STRING, '', false, 50);
		$this->initVar('template', XOBJ_DTYPE_STRING, '', false);
		$this->initVar('options', XOBJ_DTYPE_TEXT, '', false);
		$this->initVar('posttime', XOBJ_DTYPE_INT, time(), false);
   }

	/**
	 * loadPlugin
	 * 
	 * @param   void
	 * 
	 * @return  void
	**/
	public function loadPlugin()
	{
		if($this->_mPluginLoaded===true) return;
		if(! $this->get('type')) return false;

		//load plugin language
		$chandler = xoops_gethandler('config');
		$configs = $chandler->getConfigList(/*** module id ***/ 0, /*** conf_cat ***/ 1);
		$langfile = WIDGET_TRUST_PATH.'/plugins/'.$this->get('type').'/language/'.$configs['language'].'.php';
		if(file_exists($langfile)){
			include_once $langfile;
		}
		else{
			include_once WIDGET_TRUST_PATH.'/plugins/'.$this->get('type').'/language/en.php';
		}

		//load plugin configs
		$this->mPlugin = parse_ini_file(WIDGET_TRUST_PATH.'/plugins/'.$this->get('type').'/config.ini', true);

		//load plugin option edit form template
		$this->mOptionForm = file_get_contents(WIDGET_TRUST_PATH.'/plugins/'.$this->get('type').'/option_form.html');
		if($this->isNew()){
			$this->set('title', $this->mPlugin['title']);
			foreach(array_keys($this->mPlugin['options']['field']) as $key){
				if(isset($this->mPlugin['options']['default'][$key])){
					$this->mOptions[$this->mPlugin['options']['field'][$key]] = $this->mPlugin['options']['default'][$key];
				}
			}
		}
		else{
			//load and unserialize options
			$this->loadOptionValues();
		}

		$this->_mPluginLoaded = true;
	}

	public function loadOptionValues()
	{
		$this->mOptions = unserialize($this->get('options'));
	}

	public function getOptionValue($key)
	{
		return $this->mOptions[$key];
	}

	public function setOptionValue($key, $value)
	{
		return $this->mOptions[$key] = $value;
	}

	public function getOptionTitle($key)
	{
		if(substr($key, 0, strlen(WIDGET_PREFIX))==WIDGET_PREFIX){
			$key = substr($key, strlen(WIDGET_PREFIX));
		}
		return constant('_WIDGET_PLUGIN_'.strtoupper($this->get('type')).'_'.strtoupper($key));
	}

	public function getTemplateName($isOriginal=false)
	{
		if($isOriginal===true){
			return 'plugin.html';
		}
		else{
			if($this->get('template')){
				return $this->get('template');
			}
			else{
				return $this->getDirname().'_plugin_'.$this->getShow('type').'_'.$this->getShow('instance_id').'.html';
			}
		}
	}

	public function getTemplatePath()
	{
		return WIDGET_TRUST_PATH.'/plugins/'.$this->getShow('type').'/'.$this->getTemplateName(true);
	}

	public function getImageNumber()
	{
		$class = sprintf("Widget_%s_Plugin", ucfirst($this->get('type')));
		return $class::getImageNumber($this);
	}

}

/**
 * Widget_InstanceHandler
**/
class Widget_InstanceHandler extends Legacy_AbstractClientObjectHandler
{
	public /*** string ***/ $mTable = '{dirname}_instance';
	public /*** string ***/ $mPrimary = 'instance_id';
	public /*** string ***/ $mClass = 'Widget_InstanceObject';

	/**
	 * __construct
	 * 
	 * @param   XoopsDatabase  &$db
	 * @param   string  $dirname
	 * 
	 * @return  void
	**/
	public function __construct(/*** XoopsDatabase ***/ &$db,/*** string ***/ $dirname)
	{
		$this->mTable = strtr($this->mTable,array('{dirname}' => $dirname));
		parent::XoopsObjectGenericHandler($db);
	}

	public function insert(Widget_InstanceObject $obj, $force=false)
	{
		$obj->set('options', serialize($obj->mOptions));
		parent::insert($obj, $force);
	}
}

?>
