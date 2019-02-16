<?php if(!defined('APP')) die('...');

	$site_title					= $array_page_name[201].' - '. $L['pIndex_Company'];
	$content_metatitle 			= $array_page_name[201].', '.$L['pIndex_Company'];
	$content_metadesc 			= $array_page_name[201].', '.$L['pIndex_Company'];
	$content_metatags			= $array_page_name[201].', '.$L['pIndex_Company'];
	$site_canonical 			= $array_page_url[201];
	$content_metaimage			= G_IMGLINK_APP.'logo_sh.png';

	$action = $_REQUEST['action'];

	if($action <> '')
	{
		foreach($_REQUEST as $k => $v) $_REQUEST[$k] = trim(strip_tags(htmlspecialchars($v)));

		if(RC_ValidEmail == 1)
		{
			if(check_email_address($_REQUEST['contact_email']) <> "true")
			{
				$islem_bilgisi = '
				<div class="contact-message cat-101">
					<i class="icon">&#xe805;</i> <b>Geçersiz E-posta Hesabı</b>
				</div>';
			}
		}

		if($islem_bilgisi == '')
		{
			$metin = '<table>
						<tr><td width="150">Adı Soyadı</td><td width="1"> : </td><td>'.$_REQUEST['contact_name'].'</td></tr>
						<tr><td>E-Posta Adresi</td><td> : </td><td>'.$_REQUEST['contact_email'].'</td></tr>
						<tr><td>Mesaj</td><td> : </td><td>'.n2br($_REQUEST['contact_message']).'</td></tr>
						<tr><td colspan="3"><hr></td></tr>
						<tr><td>Tarih</td><td> : </td><td>'.date("Y-m-d H:i:s").'</td></tr>
						<tr><td>İp</td><td> : </td><td>'.$_SESSION[SES]['ip'].'</td></tr>
						</table>';

			//veritabanına kayıt atıyoruz,
			$_contact->contact_add($metin);
			//mesaj gönderildi diyelim
		}
	}

	if($_SESSION[SES]['contact'] == "1")
	{
		$islem_bilgisi = '
			<div class="contact-message cat-102">
				<i class="icon">&#xe807;</i> '.$L['pIletisim_ContactStartText'].'
			</div>';
	}

	$template = $twig->loadTemplate('page_contact.twig');
	$twig->addGlobal('sayfa_title', $array_page_name[201]);

	$content = $template->render(array(
		'contact_name'				=> $_REQUEST['contact_name'],
		'contact_email'				=> $_REQUEST['contact_email'],
		'contact_message'			=> $_REQUEST['contact_message'],
		'islem_bilgisi' 			=> $islem_bilgisi,
		'commented'					=> $_SESSION[SES]['contact'],
	));
