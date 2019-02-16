<?php
	if(!defined('APP')) die('...');

	$action_type	= 'edit';
	$action_submit	= 'Düzenle';

	$_id			= $list[0]['user_id'];
	$user_name		= $list[0]['user_name'];
	$user_realname	= $list[0]['user_realname'];
	$user_email		= $list[0]['user_email'];
	$user_avatar	= $list[0]['user_avatar'];
	$user_editor	= $list[0]['user_editor'];
	$user_status	= $list[0]['user_status'];

	//select alanı optionları
	foreach($array_user_status as $k => $v)
	{
		//selected etiketi
		$selected = ''; if($user_status <> '' && $user_status == $k) $selected = 'selected';
		$option_user_status.= '<option '.$selected.' value="'.$k.'">'.$v.'</option>'. "\n";
	}

	//select alanı optionları
	foreach($array_editor as $k => $v)
	{
		//selected etiketi
		$selected = ''; if($user_editor <> '' && $user_editor == $k) $selected = 'selected';
		$option_user_editor.= '<option '.$selected.' value="'.$k.'">'.$v.'</option>'. "\n";
	}

	$liste_avatar	= $_user->get_avatar_list($user_avatar);
	$auth			= $_user->get_user_auth($_id);
	$array_authors	= $_author->author_list_array();

