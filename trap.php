<?php

	//tuzağa düşenleri kayıt edelim
	//çünkü bu url'ye sadece disallow olarak atıf yapıyoruz
	//uyarımızı dikkate almayan zaten şüplelidir
// 	print_r($_SERVER);

	function get_my_ip()
	{
		if(isset($_SERVER['HTTP_X_FORWARDED_FOR']) && $_SERVER['HTTP_X_FORWARDED_FOR'] != '')
		{
			return $_SERVER['HTTP_X_FORWARDED_FOR'];
		}
		else
		{
			return $_SERVER['REMOTE_ADDR'];
		}
	}

	$uri = get_my_ip().' - ['.date('d/M/Y:H:i:s').'] "'.$_SERVER['REQUEST_METHOD'].' '.$_SERVER['REQUEST_URI'].' '.$_SERVER['SERVER_PROTOCOL'].'"';
	$uri.=' - "'.$_SERVER['HTTP_USER_AGENT'].'" - '.$_SERVER['HTTP_REFERER'].' - '.$ban_reason."\n";
	$uri.= file_get_contents('cache/trap.errors');
	file_put_contents('cache/trap.errors', $uri);
