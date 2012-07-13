<?php
/**
 * Created by JetBrains PhpStorm.
 * User: ICHIKAWA
 * Date: 12/07/12
 * Time: 15:06
 * To change this template use File | Settings | File Templates.
 */
function smarty_function_xoops_widget($params, &$smarty)
{
	$dirname = $params['dirname'];
	$widgetId = $params['widget_id'];

	$handler = Legacy_Utils::getModuleHandler('instance', $dirname);
	$widget = $handler->get($widgetId);
	if(! ($widget instanceof Widget_InstanceObject)){
		return;
	}
	$widget->loadPlugin();
	// overwrite by parameters
	foreach($widget->mPlugin['options']['field'] as $field){
		if(isset($params[$field])){
			$widget->mOptions[$field] = $params[$field];
		}
	}
	$pluginFile = WIDGET_TRUST_PATH.'/plugins/'.$widget->getShow('type').'/plugin.php';
	if(file_exists($pluginFile) && $widget instanceof Widget_InstanceObject){
		require_once $pluginFile;
		call_user_func(array('Widget_'.ucfirst($widget->getShow('type')).'_Plugin', 'execute'), &$widget);
	}

	//render template
	$render = new XCube_RenderTarget();
	$render->setTemplateName($widget->getTemplateName());
	$render->setAttribute('legacy_buffertype',XCUBE_RENDER_TARGET_TYPE_MAIN);
	$render->setAttribute('block', $widget);
	XCube_Root::getSingleton()->getRenderSystem('Legacy_RenderSystem')->render($render);

	echo $render->getResult();
}