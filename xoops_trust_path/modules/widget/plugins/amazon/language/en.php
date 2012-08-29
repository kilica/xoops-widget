<?php
/**
 * Created by JetBrains PhpStorm.
 * User: ICHIKAWA
 * Date: 12/07/11
 * Time: 17:03
 * To change this template use File | Settings | File Templates.
 */
define('_WIDGET_PLUGIN_AMAZON_ASIN', 'Amazon Item ID (ASIN)');
define('_WIDGET_PLUGIN_AMAZON_REQUIRED_SETTINGS', 'To use this widget, you must register Amazon Product Advertising API then set AWSAccessKeyId, AssociateTag, Secret Key in XOOPS_TRUST_PATH/settings/site_custom.ini like below(make the file if not exists):

<pre>
[amazon]
AWSAccessKeyId=ABCDEFGHIJKLMN123
secret_key=AbCdEfg0+HijKlMn1/oPqRstuvWXyz234
AssociateTag=yourassociatetag-22
</pre>
');