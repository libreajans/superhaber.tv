<?php
	if(!defined('APP')) die('...');

	$modul_title = array(
		'welcome' 			=> 'Hoşgeldiniz',
		'password' 			=> 'Bilgilerim',
		'user' 				=> 'Kullanıcı Yönetimi',
		'developer' 		=> 'Geliştirici Özel',
		//
		'page' 				=> 'Sayfa Yönetimi',
		'content' 			=> 'Haber Yönetimi',
		'comment' 			=> 'Yorum Yönetimi',
		'stats' 			=> 'İstatistik Yönetimi',
		'contact' 			=> 'İletişim Formu Yönetimi',
		'gallery' 			=> 'Galeri Yönetimi',
 		'author' 			=> 'Yazar Yönetimi',
 		'article' 			=> 'Makale Yönetimi',
	);

	$modul_name = array(
		'welcome' 			=> 'welcome/',
		'password' 			=> 'password/',
		'user' 				=> 'user/',
		'developer' 		=> 'developer/',
		//
		'page' 				=> 'page/',
		'content' 			=> 'content/',
		'comment' 			=> 'comment/',
		'stats' 			=> 'stats/',
		'contact' 			=> 'contact/',
		'gallery' 			=> 'gallery/',
 		'author' 			=> 'author/',
 		'article' 			=> 'article/',
	);

	$array_user_status = array(
		1 => 'Aktif Kullanıcı',
		0 => 'Pasif Kullanıcı',
		9 => 'Yönetici',
	);

	$array_content_template = array(
		0 => 'Metin',
		1 => 'Galeri',
		2 => 'Yönlendirme',
 		3 => 'Makale',
		4 => 'Video',
	);

	$array_content_ext = array(
		0 => '-haber', //haber
		1 => '-galeri', //galeri
		2 => '-haber', //yönlendirme
 		3 => '-makale', //makale
		4 => '-video',	//video
	);

	$array_editor = array(
		0 => 'Hızlı Düzenle',
		1 => 'Gelişmiş Özellikler',
		2 => 'Tüm Özellikler Aktif',
		3 => 'HAM ÖZELLİKLER',
	);

	$array_limits = array(
		100 => '100 Kayıt Göster',
		250 => '250 Kayıt Göster',
		500 => '500 Kayıt Göster',
		0	=> 'Tüm Kayıtları Göster',
	);

	$array_cat_name = array(
		100 => 'Gündem',
		101 => 'Spor',
		102 => 'Yaşam',
		103 => 'Ekonomi',
		104 => 'Dünya',
		105 => 'Teknoloji',
		106 => 'Sağlık',
		107 => 'Seyahat',
		108 => 'TV',
		109 => 'Kültür Sanat',
		110 => 'Emlak',
		111 => 'Siyaset',
		112 => 'Magazin',
		113 => 'Medya',
		114 => 'Röportaj',
		115 => 'Yemek',
		200 => 'Diğer',
	);

	$array_cat_url = array(
		100 => SITELINK.'gundem',
		101 => SITELINK.'spor',
		102 => SITELINK.'yasam',
		103 => SITELINK.'ekonomi',
		104 => SITELINK.'dunya',
		105 => SITELINK.'teknoloji',
		106 => SITELINK.'saglik',
		107 => SITELINK.'seyahat',
		108 => SITELINK.'tv',
		109 => SITELINK.'kultur-sanat',
		110 => SITELINK.'emlak',
		111 => SITELINK.'siyaset',
		112 => SITELINK.'magazin',
		113 => SITELINK.'medya',
		114 => SITELINK.'roportaj',
		115 => SITELINK.'yemek',
		200 => SITELINK.'diger',
	);

	$array_cat_url_slug = array(
		100 => 'gundem',
		101 => 'spor',
		102 => 'yasam',
		103 => 'ekonomi',
		104 => 'dunya',
		105 => 'teknoloji',
		106 => 'saglik',
		107 => 'seyahat',
		108 => 'tv',
		109 => 'kultur-sanat',
		110 => 'emlak',
		111 => 'siyaset',
		112 => 'magazin',
		113 => 'medya',
		114 => 'roportaj',
		115 => 'yemek',
		200 => 'diger',
	);

	$array_page_name = array(
		//HTML Sayfalar
		101 => 'Künye',
		102 => 'Kullanım Şartları',
		103 => 'Gizlilik Bildirimi',
		104 => 'Hukuki Bildirim',
        106 => 'Tanıtım',
        107 => 'Sitene Ekle',
        //Modüler Sayfalar
        201 => 'İletişim',
        202 => 'Arşiv',
        203 => 'Yazarlar',
        204 => 'Galeri',
	);

	$array_page_url = array(
		//HTML Sayfalar
		101 => SITELINK.'kunye',
		102 => SITELINK.'kullanim-sartlari',
		103 => SITELINK.'gizlilik-bildirimi',
		104 => SITELINK.'hukuka-aykirilik-bildirimi',
		106 => SITELINK.'tanitim',
        107 => SITELINK.'sitene-ekle',
        //Modüler Sayfalar
        201 => SITELINK.'iletisim',
        202 => SITELINK.'arsiv',
        203 => SITELINK.'yazarlar',
        204 => SITELINK.'galeri',
	);

	if(RC_InternalVideo == 1)
	{
		$array_page_name[205]	= 'Video';
		$array_page_url[205]	=  SITELINK.'video';
	}

	if(RC_ExternalVideo == 1)
	{
		$array_page_name[205]	= 'Video';
		$array_page_url[205]	= 'http://video.superhaber.tv';
	}


	$array_social_media = array(
		'twitter_name'		=> 'superhabertv',
		'twitter_hashtag'	=> 'superhaber',

		'facebook'			=> 'https://www.facebook.com/SuperhaberTV-321014511409514/',
		'twitter'			=> 'https://twitter.com/superhabertv',
 		'instagram'			=> 'https://instagram.com/superhaber',
		'google'			=> 'https://plus.google.com/111897686187400089428',
 		'youtube'			=> 'https://www.youtube.com/channel/UCi75VPqiRygDZDGXp0vo1ng',

		'applink_ios'		=> 'https://itunes.apple.com/app/s%C3%BCperhaber-tv/id1204198607',
		'applink_android'	=> 'https://play.google.com/store/apps/details?id=com.elluga.superhabertv',
		'feed'				=> SITELINK.'feed',
	);

	//bu iki diziyi yayın durumlarını görselleştirirken kullanıyoruz
	$array_content_status = array(
 		0 => 'Pasif',
		2 => 'Taslak',
		1 => 'Yayında',
		3 => 'Silinmiş',
	);

	$array_content_status_bar = array(
		1 => '<i class="ion ion-checkmark-circled text-green"></i>',
		0 => '<i class="ion ion-alert-circled text-yellow"></i>',
		2 => '<i class="ion ion-help-circled text-light-blue"></i>',
		3 => '<i class="ion ion-alert-circled text-yellow"></i>',
		4 => '<i class="ion ion-android-cancel text-maroon"></i>',
	);

	$array_author_index_status_bar = array(
		1 => '<i class="ion ion-checkmark-circled text-green"></i> Göster',
		0 => '<i class="ion ion-alert-circled text-maroon"></i> Gizle',
	);

	$array_contact_status = array(
		1 => 'Okundu',
		2 => 'Bekliyor',
		4 => 'Silinmiş',
		3 => 'Spam',
	);

	$array_comment_status = array(
		1 => 'Onaylanmış',
		2 => 'Onay Bekliyor',
		4 => 'Silinmiş',
		3 => 'Spam',
	);

	$array_content_comment_status = array(
		1 => 'Yorum Özelliği Açık',
		0 => 'Yorum Özelliği Kapalı',
	);

	$array_icon_comment_status = $array_icon_content_status;

	$array_content_type = array(
		0 => 'Normal Haber',
		1 => 'Ana Manşet',
		2 => 'Sür Manşet',
		3 => 'Son Dakika',
		//
		4 => 'Alt Manşet Bir',
		5 => 'Alt Manşet İki',
		//
		6 => 'Öne Çıkanlar',
		7 => 'Seo Modülü'
	);

	//bu manşet tipi için manşet resmi zorunlu mu?
	$array_content_type_required = array(
		0 => false,
		1 => true,
		2 => false,
		3 => false,
		//
		4 => false,
		5 => false,
		//
		6 => false,
		7 => false,
	);

	//ana manşet
	$array_content_manset_wh[1]['w'] 		= 962;
	$array_content_manset_wh[1]['h'] 		= 437;

	//İçerik resmi
	$array_content_image_wh['w'] 			= 630;
	$array_content_image_wh['h'] 			= 370;

	//Yazar Resimleri, Tasarıma göre genelde kare resimlerdir
	$array_author_image_wh['w'] 			= 200;
	$array_author_image_wh['h'] 			= 200;

	$array_maxlength = array(
		'h1' => 70,
		'h2' => 100,
	);

	$L = array(
		'pIndex_Company' 					=> 'Superhaber.tv',
		'pIndex_CompanyDesc' 				=> 'Algı yönetmiyoruz... Sadece doğru bilgi, doğru haber',
		'pIndex_CompanyLink' 				=> 'superhaber.tv',
		//iletişim kısmı
		'pIletisim_ContactMail'				=> 'info@superhaber.tv',
		'pIletisim_ContactPhone'			=> '0216 495 90 58',
		'pIletisim_ContactAddress'			=> 'Hakimiyet-i Milliye Cd. Vedat Kancal İş Merkezi No:58/49-A Üsküdar / İstanbul',
		'pIletisim_ContactStartText' 		=> 'Mesajınız gönderilmiştir. İlginiz için teşekkür ederiz.',
	);

	$config = array(
		//desktop interface
		'desktop_manset_sur' 				=> 4,
		'desktop_manset_main' 				=> 20,
		'desktop_manset_sondakika' 			=> 5,
		'desktop_manset_alt_bir' 			=> 7,
		'desktop_manset_alt_iki' 			=> 11,
		'desktop_block_roportaj' 			=> 4,

		'desktop_block_galeri' 				=> 5,

		'desktop_block_gundem' 				=> 6,
		'desktop_block_spor' 				=> 8,
		'desktop_block_magazin' 			=> 5,

		'desktop_block_video' 				=> 5,

		'desktop_block_dunya' 				=> 4,
		'desktop_block_teknoloji' 			=> 5,
		'desktop_block_yasam' 				=> 4,
		'desktop_block_seo' 				=> 18,


		'desktop_limit_cat' 				=> 36,
		'desktop_limit_gallery' 			=> 40,

		//mobile interface
		'mobile_manset_sur' 				=> 4,
		'mobile_manset_main' 				=> 20,
		'mobile_manset_sondakika' 			=> 4,
		'mobile_manset_alt_bir' 			=> 6,
		'mobile_manset_alt_iki' 			=> 10,

		'mobile_block_galeri' 				=> 4,
		'mobile_block_video' 				=> 4,

		'mobile_block_roportaj' 			=> 4,
		'mobile_block_gundem' 				=> 6,
		'mobile_block_spor' 				=> 8,
		'mobile_block_teknoloji' 			=> 4,
		'mobile_block_magazin' 				=> 4,
		'mobile_block_yasam' 				=> 4,
		'mobile_block_dunya' 				=> 4,

		'mobile_limit_cat' 					=> 36,
		'mobile_limit_gallery' 				=> 40,

		//app interface
		'app_manset_sur' 					=> 4,
		'app_manset_main' 					=> 20,
		'app_manset_sondakika' 				=> 4,
		'app_manset_alt_bir' 				=> 7,
		'app_manset_alt_iki' 				=> 4,

		'app_block_galeri' 					=> 5,
		'app_block_video' 					=> 5,

		'app_block_kategori' 				=> 4,

		'app_limit_cat' 					=> 36,
		'app_limit_gallery' 				=> 40,

		//diğer
		'limit_100' 						=> 100,
		'meta_tags'		 					=> 'Algı yönetmiyoruz, Sadece doğru bilgi, doğru haber',
	);

	// Sistem mesajları
	$messages = array();

	$messages['error_add']						= array('type' => 'error', 	'text' => 'Kayıt eklenirken bir hata oluştu.');
	$messages['error_edit']						= array('type' => 'error', 	'text' => 'Kayıt değiştirilirken bir hata oluştu.');
	$messages['error_delete']					= array('type' => 'error', 	'text' => 'Kayıt silinirken bir hata oluştu.');
	$messages['error_order']					= array('type' => 'error', 	'text' => 'Haberler sıralanırken bir hata oluştu.');
	$messages['error_truncate']					= array('type' => 'error', 	'text' => 'Kayıtlar temizlenirken bir hata oluştu.');
	$messages['error_kurucuUyeSilinemez']		= array('type' => 'error', 	'text' => 'Kurucu Üye Silinemez.');
	$messages['error_yetki']					= array('type' => 'error', 	'text' => 'Yetkisiz İşlem Denemesi.');
	$messages['error_gallery']					= array('type' => 'error',	'text' => 'Galeriye resim eklenirken hata oluştu.');
	$messages['error_gallery_delete']			= array('type' => 'error',	'text' => 'Galeri resmi silinirken hata oluştu.');
	$messages['error_gallery_order']			= array('type' => 'error',	'text' => 'Galeri resimleri sıralanıp, metinler güncellenirken hata oluştu.');

	$messages['info_add']						= array('type' => 'info',	'text' => 'Kayıt başarıyla eklendi');
	$messages['info_edit']						= array('type' => 'info',	'text' => 'Kayıt başarıyla değiştirildi.');
	$messages['info_delete']					= array('type' => 'info',	'text' => 'Kayıt başarıyla silindi.');
	$messages['info_truncate']					= array('type' => 'info',	'text' => 'Tablo başarıyla temizlendi.');
	$messages['info_truncate_passive_content']	= array('type' => 'info',	'text' => 'Pasif içerikler başarıyla temizlendi.');
	$messages['info_truncate_passive_comment']	= array('type' => 'info',	'text' => 'Pasif yorumlar başarıyla temizlendi.');
	$messages['info_truncate_acces_logs']		= array('type' => 'info',	'text' => 'Erişim logları başarıyla temizlendi.');
	$messages['info_gallery']					= array('type' => 'info',	'text' => 'Galeriye resim başarıyla eklendi.');
	$messages['info_gallery_delete']			= array('type' => 'info',	'text' => 'Galeri resmi başarıyla silindi.');
	$messages['info_gallery_order']				= array('type' => 'info',	'text' => 'Galeri resimleri sıralandı, metinler başarıyla güncellendi.');

	//İmageMagick desteği durumuna göre farklı dosya türlerine izin veriyoruz
	if(RC_Imagemagick <> 1)
	{
		$allowed_image_types = array(
			'image/pjpeg',
			'image/jpeg',
			'image/jpg'
		);
	}
	else
	{
		$allowed_image_types = array(
			'image/pjpeg',
			'image/jpeg',
			'image/jpg',
			'image/png',
			'image/gif'
		);
	}

	$array_cat_seo_title = array(
		100 => 'Gündem Haberleri',
		101 => 'Spor Haberleri, Son dakika Spor haberleri',
		102 => 'Yaşam haberleri, Son dakika yaşamdan haberler',
		103 => 'Ekonomi haberleri, Son dakika ekonomi haberleri',
		104 => 'Dünya haberleri, Son dakika dünya haberleri',
		105 => 'Teknoloji haberleri, teknolojiden en son haberler',
		106 => 'Sağlık haberleri, Sağlıkla ilgili tüm sorular',
		107 => 'Seyahat haberleri ve sefer bilgileri',
		108 => 'Televizyon dizileri ve Tv programları',
		109 => 'Kültür ve Sanat haberleri',
		110 => 'Emlak haberleri ve konut fiyatları',
		111 => 'Siyaset haberleri, son dakika siyaset gündemi',
		112 => 'Magazin haberleri, son dakika magazin haberleri',
		113 => 'Medya haberleri ve sosyal medya gündemi',
		114 => 'Ünlü isimlerle ropörtajlar',
		115 => 'En güzel yemek tarifleri',
		200 => 'Diğer ilgii çekici haberler',
	);

	$array_cat_seo_desc = array(
		100 => 'Gündem haberleri, son gelişmeleri takip edebileceğiniz gündem haberlerinden bilgileri ve gündemde yer alan tüm içerikleri bulabileceğiniz sitemiz.',
		101 => 'Spor haberleri, son dakika spor haberlerini görebileceğiniz, transferler listesini, puan durumunu, maç takvimi ve spordan son haberleri bulabileceğiniz sayfamız.',
		102 => 'Yaşam haberleri, son dakika yaşam haberlerini görebileceğiniz, yeni gelişmeleri ve yaşamın içinden tüm haberleri bulabileceğiniz sayfamız.',
		103 => 'Ekonomi haberleri, son dakika ekonomi haberlerini bulabileceğiniz, piyasalardan gelişmeleri ve ekonomiye dair ayrıntılı bilgilerin yer aldığı sayfamız.',
		104 => 'Dünya haberleri, son dakika dünya haberleri hakkındaki flaş gelişmelerin yaşandığı, dünyadaki tüm ülkelerden çeşitli bilgilerin bulunduğu sitemiz.',
		105 => 'Teknoloji haberleri, teknolojiden en son dakika haberlerin bulunduğu, yeni çıkan telefonlar, bilgisayarlar ve uygulamalar hakkındaki bilgilerin yer aldığı sitemiz.',
		106 => 'Sağlık haberleri, sağlıklar ilgili tüm soruların yanıtını öğrenebileceğiniz,  hastalıklar ve tedavi yöntemleri ile ilgili bilgilerin bulunduğu detaylar.',
		107 => 'Seyahat haberler, sefer bilgilerinin yer aldığı seyahat haberleri ile ilgili tüm detayları bulabileceğiniz, uçak firmaları hakkındaki gelişmelerin yer aldığı sitemiz.',
		108 => 'Televizyon dizileri ve TVprogramlarının yer aldığı, en çok izlenen ve reyting alan programlardan bilgiler.',
		109 => 'Kültür ve Sanat haberlerini bulabileceğiniz, kültürden ve sanattan son gelişmeleri görebileceğiniz detaylı sayfamız.',
		110 => 'Emlak haberleri, emlak haberleri ve konut fiyatları ile ilgili tüm bilgileri görebileceğiniz, projeleri takip ederek güncel emlak değerleri hakkındaki açıklamaların yer aldığı sayfamız.',
		111 => 'Siyaset haberleri, son dakika siyaset gündeminde yaşanan tüm gelişmeleri görebileceğiniz, partiler, seçimler ve devlet yönetimi ile ilgili detaylı bilgilerin yer aldığı sitemiz.',
		112 => 'Magazin haberleri, son dakika magazin haberleri ile ünlü isimler hakkındaki gelişmeleri takip edebileceğiniz ve yeni magazin haberleri.',
		113 => 'Medya haberleri, sosyal medya haberlerinden en son gelişmelerin yaşandığı, çok konuşulan, programlar, yarışmalar ve açıklamaların yer aldığı sitemiz.',
		114 => 'Ropörtajları görebileceğiniz, ünlü isimlerle yapılan tüm yeni ropörtajların bulunduğu ve merak edilenlerin yanıtlandığı sayfamız.',
		115 => 'Yemek tariflerini görebileceğiniz, yeni yemek tarifleriyle mutfağınızı zenginleştirebileceğiniz ve merak edilenlerin yanıtlandığı sayfamız.',
		200 => 'İlgi çekici haberler sayfamız.',
	);

	$array_cat_seo_meta = array(
		100 => 'Gündem haberleri',
		101 => 'Spor haberleri, son dakika spor haberleri',
		102 => 'Yaşam haberleri, son dakika yaşam',
		103 => 'Ekonomi haberleri, son dakika ekonomi',
		104 => 'Dünya haberleri, son dakika dünya',
		105 => 'Teknoloji haberleri',
		106 => 'Sağlık haberleri, sağlıkla soruları',
		107 => 'Seyahat haberleri, sefer bilgileri',
		108 => 'Televizyon dizileri, tv programları',
		109 => 'Kültür Sanat haberleri',
		110 => 'Emlak haberleri, konut fiyatları',
		111 => 'Siyaset haberleri, son dakika siyaset',
		112 => 'Magazin haberleri, son dakika magazin',
		113 => 'Medya haberleri, sosyal medya gündemi',
		114 => 'Ropörtajlar, söyleşiler, özel görüşmeler',
		115 => 'Yemek tarifleri, gurme, en yeni lezzetler',
		200 => 'Diğer Haberler',
	);
