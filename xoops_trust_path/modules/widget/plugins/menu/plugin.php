<?php
/**
 * Created by JetBrains PhpStorm.
 * User: ICHIKAWA
 * Date: 12/07/11
 * Time: 19:49
 * To change this template use File | Settings | File Templates.
 */

class Widget_Menu_Plugin implements Widget_PluginInterface
{
	public static function execute(Widget_InstanceObject &$object)
	{
	}

	public static function prepareEditform(Widget_InstanceEditForm $form, Widget_InstanceObject $object)
	{
	}

	public static function fetch(Widget_InstanceObject $object, $request)
	{
		$menuList = explode("\n", $request->getRequest('p_menu'));
		$topclass = $request->getRequest('p_topclass');
		$ret = '<ul class="'.$topclass.'">%s</ul>';
		$depth = 1;
		$htmlMenu = '';
		foreach($menuList as $menu){
			preg_match_all('/^-+/', $menu, $matches);
			$d = strlen($matches[0][0]);
			if($d===0) continue;
			$element = explode(' ', ltrim($menu, '-'));
			if(count($element)===1){
				$title = array_shift($element);
				if($depth<$d){
					$htmlMenu .= '<ul><li>'.$title.'</li>';
				}
				elseif($depth===$d){
					$htmlMenu .= '<li>'.$title.'</li>';
				}
				elseif($depth>$d){
					for($i=0; $depth-$d-$i>0; $i++){
						$htmlMenu .='</ul>';
					}
					$htmlMenu .= '<li>'.$title.'</li>';
				}
			}
			else{
				$link = array_shift($element);
				$title = implode(' ', $element);
				if($depth<$d){
					$htmlMenu .= '<ul><li><a href="'.$link.'">'.$title.'</a></li>';
				}
				elseif($depth===$d){
					$htmlMenu .= '<li><a href="'.$link.'">'.$title.'</a></li>';
				}
				elseif($depth>$d){
					for($i=0; $depth-$d-$i>0; $i++){
						$htmlMenu .='</ul>';
					}
					$htmlMenu .= '<li><a href="'.$link.'">'.$title.'</a></li>';
				}
			}
			$depth = $d;
		}
		for($i=1; $depth>$i; $i++){
			$htmlMenu .='</ul>';
		}
		$object->setOptionValue('p_htmlmenu', sprintf($ret, $htmlMenu));
	}

	public static function getImageNumber(Widget_InstanceObject $obj)
	{
		return 0;
	}

}
