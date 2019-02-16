<?php
	if(!defined('APP')) die('...');

	$action_type	= 'add';
	$action_submit	= 'Kayıt Ekle';

	foreach($array_user_status as $k => $v)
	{
		$option_user_status.= '<option value="'.$k.'">'.$v.'</option>'. "\n";
	}

	foreach($array_editor as $k => $v)
	{
		$option_user_editor.= '<option '.$selected.' value="'.$k.'">'.$v.'</option>'. "\n";
	}

	$liste_avatar = $_user->get_avatar_list($user_avatar);
