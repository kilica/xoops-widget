<?php
/**
 * Created by JetBrains PhpStorm.
 * User: ICHIKAWA
 * Date: 12/07/11
 * Time: 19:49
 * To change this template use File | Settings | File Templates.
 */

class Widget_Amazon_Plugin implements Widget_PluginInterface
{
	public static function execute(Widget_InstanceObject $object)
	{
		$root = XCube_Root::getSingleton();
		$AWSAccessKeyId = $root->getSiteConfig('amazon', 'AWSAccessKeyId');
		$AssociateTag = $root->getSiteConfig('amazon', 'AssociateTag');
		$secretKey = $root->getSiteConfig('amazon', 'secret_key');
		if(! $AWSAccessKeyId || ! $AssociateTag || ! $secretKey){
			die('Set AWSAccessKeyID, AssociateTag and secret key !');
		}
		$arr = Array(
				'locale' => 'http://ecs.amazonaws.jp/onca/xml',
				'Service' => 'AWSECommerceService',
				'Operation' => 'ItemLookup',
				'ResponseGroup' => 'Large',
				'AWSAccessKeyId' => $AWSAccessKeyId,
				'AssociateTag' => $AssociateTag,
				'ItemId' => $object->getOptionValue('p_asin'),
				'Version' => '2009-01-06',
				'secret_key' => $secretKey
			);

		$ret = self::getItem($arr);
		$object->mItem = $ret->Items->Item;
		//var_dump($ret);die;
	}

	public static function prepareEditform(Widget_InstanceObject $object)
	{
	}

	public static function fetch(Widget_InstanceObject $object, $request)
	{

	}

	public static function getImageNumber(Widget_InstanceObject $obj)
	{
		return 0;
	}

	/**
	 * @static
	 * @param $array
	 * @return bool|object
	 * http://weble.org/2011/10/11/aws-product-advertising-api
	 */
	public static function getItem($array)
	{
		foreach($array as $key => $value) {
			if($key != 'secret_key' && $key != 'locale') {
				if(isset($params)) {
					$params .= sprintf('&%s=%s', $key, $value);
				} else {
					$params = sprintf('%s=%s', $key, $value);
				}
			}
		}
		$url = $array['locale'] . '?' . $params;
		$url_array = parse_url($url);
		parse_str($url_array['query'], $param_array);
		$param_array['Timestamp'] = gmdate('Y-m-d\TH:i:s\Z');
		ksort($param_array);
		$str = sprintf("GET\n%s\n%s\n", $url_array['host'], $url_array['path']);
		$str_param = '';
		while(list($key, $value) = each($param_array))
			$str_param .= sprintf('%s=%s&', strtr($key, '_', '.'), rawurlencode($value));
		$str .= substr($str_param, 0, strlen($str_param) - 1);
		$signature = base64_encode(hash_hmac('sha256', $str, $array['secret_key'], true));
		$url_sig =  sprintf('%s://%s?%sSignature=%s', $url_array['scheme'], $url_array['host'] . $url_array['path'], $str_param, rawurlencode($signature));
		$xml = file_get_contents($url_sig);
		if($xml) {
			return simplexml_load_string($xml);
		} else {
			return false;
		}
	}
}

