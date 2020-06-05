<?php
$text = $_POST['search'];
$url = $_POST['url'];
$array = [];
$allfiles = scandir($url.'/');
foreach ($allfiles as $file) {
    if (strstr(strtoupper($file), $text)) {
        array_push($array,$file);
    }
}
//print_r($array);
$json = json_encode($array);
echo $json;