<?php
/**
 * Created by JetBrains PhpStorm.
 * User: ICHIKAWA
 * Date: 12/07/11
 * Time: 19:49
 * To change this template use File | Settings | File Templates.
 */

class Widget_Socialbutton_Plugin implements Widget_PluginInterface
{
    public static function execute(Widget_InstanceObject &$object)
    {
        $headerScript = XCube_Root::getSingleton()->mContext->getAttribute('headerScript');
        $headerScript->addLibrary(XOOPS_URL."/common/js/jquery.socialbutton-1.9.0.min.js", false);
        $buttons = explode(',', $object->getOptionValue('p_services'));
        foreach($buttons as $button){
            $button = trim($button);
            $headerScript->addScript(sprintf('
$("#socialbuttons .%s").socialbutton("%s", {
  button : "%s",
}).width(%d);', $button, $button, self::getButtonType($button, $object->getOptionValue('p_form')), self::getButtonWidth($button, $object->getOptionValue('p_form'))));
        }
    }

    public static function prepareEditform(Widget_InstanceEditForm $form, Widget_InstanceObject $object)
    {
    }

    public static function fetch(Widget_InstanceObject $object, $request)
    {

    }

    public static function getImageNumber(Widget_InstanceObject $obj)
    {
        return 0;
    }

    protected static function getButtonType($button, $form='horizontal')
    {
        $array = array(
            'twitter'=>array('horizontal'=>'horizontal', 'vertical'=>'vertical'),
            'facebook_like'=>array('horizontal'=>'button_count', 'vertical'=>'box_count'),
            'google_plusone'=>array('horizontal'=>'standard', 'vertical'=>'tall'),
            'hatena'=>array('horizontal'=>'standard', 'vertical'=>'vertical'),
        );
        return $array[$button][$form];
    }

    protected static function getButtonWidth($button, $form='horizontal')
    {
        $array = array(
            'twitter'=>array('horizontal'=>95, 'vertical'=>71),
            'facebook_like'=>array('horizontal'=>110, 'vertical'=>70),
            'google_plusone'=>array('horizontal'=>70, 'vertical'=>50),
            'hatena'=>array('horizontal'=>60, 'vertical'=>50),
        );
        return $array[$button][$form];
    }

    protected static function getButtonHeight($form='horizontal')
    {
        $array = array(
            'horizontal'=>33,
            'vertical'=>70
        );
        return $array[$form];
    }
}

