<?php
	if(!defined('APP')) die('...');

	$action_type			= 'edit';

	$comment_id				= $list[0]['comment_id'];
	$_id					= $list[0]['comment_id'];
	$comment_content		= $list[0]['comment_content'];
	$comment_author			= $list[0]['comment_author'];
	$comment_ip				= $list[0]['comment_ip'];
	$comment_text			= $list[0]['comment_text'];
	$comment_status			= $list[0]['comment_status'];
	$comment_aprover		= $list[0]['comment_aprover'];
	$create_time			= $list[0]['create_time'];

	//biçimlendirmeler yapalım
	//yazar ismini UcWords ile düzenleyelim
	$comment_author 		= tr_ucwords($comment_author);
	//eposta adresini tamamen küçük harf şeklinde düzenleyelim
	//aslında eposta adresi doğrulaması da yapabilirdik;
	//lakin zaten kayıt aşamasında bu doğrulamayı yaptığımızdan lüzumsuz olurdu
