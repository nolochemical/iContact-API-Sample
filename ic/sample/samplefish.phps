<?php
/* 
 * Copyright (c) 2011 Victor Phelemba
 * 
 * samplefish is free software: you can redistribute it and/or modify
 * 
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, version 3 of the License.
 * 
 * samplefish is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 * 
 * You should have received a copy of the GNU General Public License
 * along with samplefish. If not, see <http://www.gnu.org/licenses/>.
 * 
 * samplefish.phps *
 * 
 * We used this old curl class as a stepping stone; yet its always 
 * advisable to trim or expand upon it were necessary.Its also 
 * open source.
 * 
 * [ Dick Munroe (munroe AT csworks.com)]
 * [ http://phpclasses.cfappsinc.ca/browse/file/7871.html ]
 * 
 */
 
include "class.curl.php";

$api_base_path = 'https://app.sandbox.icontact.com/icp';
$api_username = '_api_USER_';
$api_password = '_api_PASS_';
$api_appId = '_api_ID_';
//API - Get account Id
$api_path = '/a/';
$api_conf['method'] = ''; // BLANK = default GET | POST | PUT | DELETE
$api_conf['data'] = array(
	array(
		'name' => "kodemind",
		'email'=> "nolochemical kodemind.com",
		);
);//sample post data

//==END CONFIG========================================================

define("SY_API_APPID",$api_appId,true);
define("SY_API_USERNAME",$api_username,true);
define("SY_API_PASSWORD",$api_password,true);
define("SY_API_PATH",$api_path,true);
define("SY_API_METHOD",$api_method,true);
define("SY_API_BASE_PATH",$api_base_path,true);

$api_conf['headers'] = array(
	'Accept: application/json',
	'Content-Type: application/json',
	'Api-Version: 2.0',
	'Api-AppId: ' . SY_API_APPID,
	'Api-Username: ' . SY_API_USERNAME,
	'Api-Password: ' . SY_API_PASSWORD,
);
/**
 * @param array api_conf - a few required configuration items
 */
function getAcctId($ac)
{

$c = new curl(SY_API_BASE_PATH.SY_API_PATH) ;
$c->setopt(CURLOPT_FOLLOWLOCATION, true) ;
$c->setopt(CURLOPT_HTTPHEADER, $ac['headers']) ;
$c->setopt(CURLOPT_HEADER, true);
$c->setopt(CURLOPT_RETURNTRANSFER, true);
// Yes I know who I am, do you know who you are?
$c->setopt(CURLOPT_SSL_VERIFYHOST, true); 
// Essentialy your bypassing secure peer check - Live vs Testing Modes
$c->setopt(CURLOPT_SSL_VERIFYPEER, false); 

switch ($ac['method']) {
	case 'POST':
		//$c->setopt(CURLOPT_POST, true);
		//$c->setopt(CURLOPT_POSTFIELDS, json_encode($ac['data']));
	break;
	case 'PUT':
		//$c->setopt(CURLOPT_PUT, true);
		//$file_handle = fopen($data, 'r');
		//$c->setopt(CURLOPT_INFILE, $file_handle);
	break;
	case 'DELETE':
		//$c->setopt(CURLOPT_CUSTOMREQUEST, 'DELETE');
	break;
}

$jData = json_decode( $c->exec(),true);
//echo "<pre>",print_r($jData,true),"</pre>";

// Check to see if there was an error print.
if ($theError = $c->hasError())
{
  //echo $theError ;
  return FALSE;
}
else
{
	return $jData['accounts'][0]['accountId'];
}

// Done with the cURL, so get rid of the cURL related resources.
$c->close() ;
}
echo getAcctId($api_conf); 
