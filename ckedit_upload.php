<?php

require_once 'core/includes.php';

$return = [];


$file = $_FILES['file'];


$info = pathinfo($file['name']);
$fileName = uniqid().uniqid();
$saveResponse = saveFile($file, 'ckedit', $fileName);
$fileName .= '.'.$info['extension'];

$return['fileName'] = $fileName;
$return['url'] = _URL.'files/ckedit/'.$fileName;
$return['success'] = ($saveResponse == 'Success');
$return['serverResponse'] = $saveResponse;

die(json_encode($return));