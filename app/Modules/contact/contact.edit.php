<?php
	if(!defined('APP')) die('...');

	$action_type	= 'edit';

	$contact_id		= $list[0]['contact_id'];
	$contact_email	= $list[0]['contact_email'];
	$contact_text	= $list[0]['contact_text'];
	$contact_status	= $list[0]['contact_status'];
	$create_time	= $list[0]['create_time_f'];

	//küçük bir hile katıyoruz
	$contact_text	= str_replace('<table>', '<table class="table table-bordered table-striped">',$contact_text);
