<?php
	if(!defined('APP')) die('...');

	$action_type	= 'edit';
	$action_submit	= 'Düzenle';

	$page_id		= $list[0]['page_id'];
	$page_title		= $list[0]['page_title'];
	$page_text		= $list[0]['page_text'];
	$page_status	= $list[0]['page_status'];

	//select alanı optionları
	foreach($array_content_status as $k => $v)
	{
		$selected = ''; if($page_status <> '' && $page_status == $k) $selected = 'selected';
		$option_page_status.= '<option '.$selected.' value="'.$k.'">'.$v.'</option>'. "\n";
	}
