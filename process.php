<?php
$name = $_POST["name"];
$phone = $_POST["phone"];
$title = $_POST["title"];

//API bitrix24
//$queryUrl = 'https://bitrix.psk-info.ru/crm/configs/import/lead.php?LOGIN=webmaster@psk-info.ru&PASSWORD=Dejled123&TITLE='.$title.'&NAME='.$name.'&PHONE_MOBILE='.$phone.'&ASSIGNED_BY_ID=3';
//$queryUrlBlog = 'https://bitrix.psk-info.ru/rest/9/6lyqbgu1ku6k1jwe/log.blogpost.add.json?POST_MESSAGE=Новая%20бронь%20с%20сайта%20LIKE.HOUSE%20имя%20'.$name.'%20телефон%20'.$phone.'';
//file_get_contents($queryUrl);
//file_get_contents($queryUrlBlog);

define('CRM_HOST', 'bitrix.psk-info.ru');
define('CRM_PORT', '443');
define('CRM_PATH', '/crm/configs/import/lead.php');
 
define('CRM_LOGIN', 'webmaster@psk-info.ru');
define('CRM_PASSWORD', 'Dejled_123');
 
$postData = array(
  'TITLE' => $title,
  'STATUS_ID' => 'NEW',
  'SOURCE_ID' => '1',
  'NAME' => $name,
  'PHONE_MOBILE' => $phone,
  'UF_CRM_1489066363'=>'13449'
);

if (defined('CRM_AUTH'))
{
  $postData['AUTH'] = CRM_AUTH;
}
else
{
  $postData['LOGIN'] = CRM_LOGIN;
  $postData['PASSWORD'] = CRM_PASSWORD;
}

$fp = fsockopen("ssl://".CRM_HOST, CRM_PORT, $errno, $errstr, 30);
if ($fp)
{
  $strPostData = '';
  foreach ($postData as $key => $value)
     $strPostData .= ($strPostData == '' ? '' : '&').$key.'='.urlencode($value);

  $str = "POST ".CRM_PATH." HTTP/1.0\r\n";
  $str .= "Host: ".CRM_HOST."\r\n";
  $str .= "Content-Type: application/x-www-form-urlencoded\r\n";
  $str .= "Content-Length: ".strlen($strPostData)."\r\n";
  $str .= "Connection: close\r\n\r\n";

  $str .= $strPostData;

  fwrite($fp, $str);

  $result = '';
  while (!feof($fp))
  {
     $result .= fgets($fp, 128);
  }
  fclose($fp);

  $response = explode("\r\n\r\n", $result);

  $output = '<pre>'.print_r($response[1], 1).'</pre>';
}
else
{
  echo 'Connection Failed! '.$errstr.' ('.$errno.')';
}
?>
