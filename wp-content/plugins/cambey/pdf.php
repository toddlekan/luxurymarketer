<?php
require(dirname(__FILE__)."/shared.php");

error_reporting(E_ALL);
ini_set('display_errors', 1);

$debug = true;

$token = "";

$post_id = "-1";



//check for cached file

$cache_path = dirname(__FILE__)."/../../uploads/pdf_cache";

if(!file_exists($cache_path)){
  mkdir($cache_path);
}

$str = (string) $post_id;

for($i = 0; $i < strlen($str); $i++){

  $chr = $str[$i];

  $cache_path .= "/$chr";

  if(!file_exists($cache_path)){
    mkdir($cache_path);
  }

}

$filename = "$cache_path/$post_id.pdf";

if(!file_exists($filename) || array_key_exists('nocache', $_GET)){

  $cmd = "/usr/local/bin/wkhtmltopdf \"https://www.luxuryroundtable.com/?p=$post_id&format=pdf\" $filename";
  //$cmd = "/usr/local/bin/wkhtmltopdf https://beta.luxuryroundtable.com/?p=204922&format=pdf /tmp/test.pdf";

  $result = shell_exec($cmd);

}
