<?php
/**
 * Created by JetBrains PhpStorm.
 * User: ICHIKAWA
 * Date: 12/07/11
 * Time: 19:49
 * To change this template use File | Settings | File Templates.
 */

class Widget_Minicalendar_Plugin implements Widget_PluginInterface
{
    public static function execute(Widget_InstanceObject &$object)
    {
        //set parameters
        $month = date('m');
        $year = date('Y');
        $dirname = $object->getOptionValue('p_dirname');
        $dateName = $object->getOptionValue('p_datename');

        $event = self::_getEvents($dirname, $dateName, $year, $month);

        //get closed days and holiday
        $closedDays = self::_parseDays($object->getOptionValue('p_closed'), $year, $month);
        $holidays = self::_parseDays($object->getOptionValue('p_holiday'), $year, $month);

        $firstWeek = date('w', mktime(0, 0, 0, $month, 1, $year));
        $result = array();
        $week = 0;
        for($f=0; $f<$firstWeek; $f++){
            $result[$week][] = array('day'=>0, 'url'=>'', 'class'=>'');
        }

        $baseUrl = XOOPS_MODULE_URL . '/'.$dirname.'/index.php?action=PageSearch&amp;'.$dateName.'%s=%d&amp;'.$dateName.'%s=2';

        $i = 1;
        while(checkdate($month,$i,$year)===true){
            if(in_array($i, $closedDays)){
                $result[$week][] = array('day'=>$i, 'url'=>'', 'class'=>'close');
            }
            elseif(in_array($i, array_keys($event))){    //events exists
                $result[$week][] = array('day'=>$i, 'url'=>sprintf($baseUrl, rawurlencode('[0][0]'), $event[$i], rawurlencode('[0][1]')), 'class'=>in_array($i, $holidays) ? 'holiday' : '');
                //delete events of the same day
            }
            else{    //no event in this day
                $result[$week][] = array('day'=>$i, 'url'=>'', 'class'=>in_array($i, $holidays) ? 'holiday' : '');
            }
            if(count($result[$week])==7){
                $week++;
            }
            $i++;
        }
        $lastWeek = 6-date('w', mktime(0, 0, 0, $month, $i-1, $year));
        for($l=0; $l<$lastWeek; $l++){
            $result[$week][] = array('day'=>0, 'url'=>'', 'class'=>'');
        }
        $object->setParameter('days', $result);
        $object->setParameter('month', $month);

        $nextYmUnixtime = strtotime("+1 month", mktime(0,0,0,date('m'),1,date('Y')));
        $nextNextYmUnixtime = strtotime("+2 month", mktime(0,0,0,date('m'),1,date('Y')));
    }

    protected static function _getEvents($dirname, $dateName, $year, $month)
    {
        $event = array();
        if(! $dirname || ! $dateName){
            return $event;
        }
        $cri = new CriteriaCompo();
        $thisUnixtime = mktime(0,0,0,$month,1,$year);
        $nextUnixtime = strtotime("+1 month", $thisUnixtime);
        $cri->add(new Criteria($dateName, $thisUnixtime, '>='));
        $cri->add(new Criteria($dateName, $nextUnixtime, '<'));
        $cri->setSort($dateName, 'ASC');
        $handler = Legacy_Utils::getModuleHandler('page', $dirname);
        $objects = $handler->getObjects($cri);

        foreach(array_keys($objects) as $k) {
            $event[date('j', $objects[$k]->get($dateName))] = date('Ymd', $objects[$k]->get($dateName));
        }
    }

    protected  static function _parseDays($iniString, $year, $month)
    {
        $dayArr = parse_ini_string($iniString, true);
        return explode(',', @$dayArr[$year][$month]);
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

}
