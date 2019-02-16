<?php
	if(!defined('APP')) die('...');

	//site DEBUG modunda detaylı hata geri bildirileri verir
	//0 debug modu kapalı
	//1 debug modu acık: sql sorguları + tema dosyaları
	//2 debug modu acık: sql sorguları + tema dosyaları + php hataları
	define('ST_DEBUG', 			1);

	//site yayında ise yayın veritabanını kullanır
	//0 local veritabanını kullan
	//1 yayındaki veritabanını kullan demektir
	//dikkat, yayındaki veritabanını lokalden kullanıyorsanız
	//beklenmedik hatalar ile karşılaşabilirsiniz
	define('ST_ONLINE', 		0);

	//site CDN hizmeti kullanıyorsa, CDN hizmetini açıp kapatmak için bu ayarı kullanıyoruz
	//0 CDN kapalı
	//1 CDN acık
	define('ST_CDN', 			0);

	//giriş çıkış işlemleri sırasında
	//captha/görsel doğrulama kullanılıp kullanılmayacağını belirler
	//0 kapalı
	//1 açık demektir
	define('RC_Captcha', 		0);

	//E-posta adresleri ek doğrulamadan geçirilsin mi
	//0 kapalı
	//1 açık demektir
	define('RC_ValidEmail',		1);

	//Akismet WP temelli bir yapı olup yorumlardaki spamları denetler
	//0 kapalı
	//1 açık demektir
	define('RC_Akismet',		1);

	//Kategori ve Yazar yönetimi detaylı yetki kontrolü
	//0 kapalı
	//1 açık demektir
	define('RC_Authority',		0);

	//Uygulama varsa, onunla ilintili kısımları gösteriyoruz
	//0 kapalı
	//1 açık demektir
	define('RC_Application',	1);

	//Detaylı SEO için Meta Değerlerini düzenleme özelliğini aktif eder veya kapatır
	//0 kapalı
	//1 açık demektir
	define('RC_DetailedSeo',	1);

	//Daha iyi bir SEO için h1 ve h2 değerlerini belirtilen değerlere zorlar
	//0 kapalı
	//1 açık demektir
	define('RC_ForceSeo',		0);

	//Manşetler tarihe göre değil sıralamaya göre gelsin
	//0 kapalı
	//1 açık demektir
	define('RC_MansetOrder',	1);

	//Sistemde Imagemagick yüklüyse gücünden faydalanalım
	//0 kapalı
	//1 açık demektir
	define('RC_Imagemagick',	1);

	//Hangi Temayı Kullanalım
	//Default
	//Custom (Varsa)
	define('ST_TEMPLATE', 		'default');

	//Harici Video Sitesi Aktif Mi
	//0 kapalı
	//1 açık demektir
	define('RC_ExternalVideo',	1);

	//Dahili / Kısıtlı Video Sitesi Aktif Mi
	//0 kapalı
	//1 açık demektir
	define('RC_InternalVideo',	0);

	//Daha iyi bir ana sayfa açılışı için Ana Sayfayı HTML olarak daima cachler
	//memcached kurulu olmalıdır veya uygun bir cache server ayarlanmalıdır
	//0 kapalı
	//1 açık demektir
	define('RC_SuperCache',		0);

	//Bad Bod Blocker Aktif olsun Mu?
	//Kötü amaçlı botları siteden uzak tutmaya çalışır
	//0 kapalı
	//1 açık demektir
	define('RC_BadBotBlock',		1);

	//Bad Bod Blocker Aktif olsun Mu?
	//Kötü amaçlı botları siteden uzak tutmaya çalışır
	//0 test edilmiş (tested) listeyi aktif et
	//1 ilk test listesini (öncelediklerimiz) aktif et
	//2 ikinci test listesini (ultimatebadbot) aktif et
	//3 üçüncü test listesini (stopbadbots) aktif et
	define('RC_BadBotBlock_level',	3);

	//sql cache için MEMCACHE kullanılsın mı?
	//0 pasif, 1 aktif demek oluyor
	//tavsiye edilen aktif olması yönündedir
	$memcache_status			= 1;

	//Mobil Template yönlendirmesi yapılsın mı?
	//
	//0 pasif, 1 aktif demek oluyor
	//tavsiye edilen aktif olması yönündedir
	//pasif olması durumunda responsive tasarım şart
	$checmobile					= 1;

