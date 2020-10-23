<?php

$user = getUserObject();
if(!$user or $user['admin'] != true){
	header("Location: "._URL.'admin-panel');
	die();
}