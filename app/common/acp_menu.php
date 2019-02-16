<?php if(!defined('APP')) die('...'); ?>
	<aside class="main-sidebar">
		<section class="sidebar">
			<div class="user-panel">
				<div class="pull-left image">
					<img src="<?=G_IMGLINK_DEV?>Avatar/<?=$_SESSION[SES]['user_avatar']?>" class="img-circle" alt="User Image">
				</div>
				<div class="pull-left info">
					<p><?=$_SESSION[SES]['user_name']?></p>
					<a href="#"><i class="ion ion-record text-success"></i> Online</a>
				</div>
			</div>
			<ul class="sidebar-menu">
				<li> <a href="<?=LINK_ACP?>"><i class="ion ion-android-home"></i> <span>Ana Sayfa</span></a> </li>
				<li> <a href="<?=LINK_INDEX?>"><i class="ion ion-reply"></i> <span>Siteye Dön</span></a> </li>
				<?php if($_auth['content_list'] == 1) : ?>
				<li class="treeview<?php if($view == 'content') echo " active";?>">
					<a href="#">
						<i class="ion ion-document"></i>
						<span>Haber Yönetimi</span>
						<i class="ion ion-ios-arrow-left pull-right"></i>
					</a>
					<ul class="treeview-menu">
						<?php if(RC_MansetOrder == 1) : ?>
							<?php if($_auth['content_manset'] == 1) : ?>
								<li><a href="<?=LINK_ACP?>&amp;view=content&amp;do=manset"><i class="ion ion-drag"></i> Manşet Sıralaması</a></li>
							<?php endif?>
						<?php endif?>
						<?php if($_auth['content_add'] == 1) : ?>
							<li><a href="<?=LINK_ACP?>&amp;view=content&amp;do=add"><i class="ion ion-android-add"></i> Haber Ekle</a></li>
							<li><a href="<?=LINK_ACP?>&amp;view=content&amp;do=add&amp;type=sondakika"><i class="ion ion-android-alarm-clock"></i> Son Dakika</a></li>
							<li><a href="<?=LINK_ACP?>&amp;view=content&amp;do=add&amp;type=flash"><i class="ion ion-flash"></i> Flash Haber</a></li>
							<li><a href="<?=LINK_ACP?>&amp;view=content&amp;do=add&amp;type=sehit"><i class="ion ion-alert"></i> Şehit Haberi</a></li>
							<li><a href="<?=LINK_ACP?>&amp;view=content&amp;do=add&amp;type=deprem"><i class="ion ion-android-walk"></i> Deprem Haberi</a></li>
							<li><a href="<?=LINK_ACP?>&amp;view=content&amp;do=add&amp;redirect=true"><i class="ion ion-forward"></i> Yönlendirme Ekle</a></li>
							<?php if(RC_InternalVideo == 1) : ?>
								<li><a href="<?=LINK_ACP?>&amp;view=content&amp;do=add&amp;video=true"><i class="ion ion-play"></i> Video Ekle</a></li>
							<?php endif ?>
							<?php if(RC_ExternalVideo == 1) : ?>
								<li><a target="_blank" rel="noopener noreferrer" href="http://video.superhaber.tv/index.php?page=acp&amp;view=content&amp;do=add"><i class="ion ion-play"></i> Video Ekle</a></li>
							<?php endif ?>
						<?php endif?>
						<?php if($_auth['content_list'] == 1) : ?>
								<li><a href="<?=LINK_ACP?>&amp;view=content&amp;do=list&amp;filter=1&amp;keyword=&amp;time=&amp;limit=100&amp;template=-1&amp;type=-1&amp;cat=-1&amp;user=-1&amp;status=-1"><i class="ion ion-android-list"></i> Haber Listesi</a></li>
						<?php endif?>
						<?php if($_auth['content_list'] == 1) : ?>
							<?php if(RC_InternalVideo == 1) : ?>
								<li><a href="<?=LINK_ACP?>&amp;view=content&amp;do=list&amp;filter=1&amp;keyword=&amp;time=&amp;limit=100&amp;template=4&amp;type=-1&amp;cat=-1&amp;user=-1&amp;status=-1"><i class="ion ion-play"></i> Video Listesi</a></li>
							<?php endif ?>
							<?php if(RC_ExternalVideo == 1) : ?>
								<li><a target="_blank" rel="noopener noreferrer" href="http://video.superhaber.tv/index.php?page=acp&amp;view=content&amp;do=list&amp;filter=1&amp;keyword=&amp;time=&amp;limit=100&amp;type=-1&amp;cat=-1&amp;user=-1&amp;status=1"><i class="ion ion-android-list"></i> Video Listesi</a></li>
							<?php endif ?>
						<?php endif?>
						<?php if($_auth['content_list'] == 1) : ?>
							<li><a href="<?=LINK_ACP?>&amp;view=content&amp;do=list&amp;filter=1&amp;keyword=&amp;time=&amp;limit=100&amp;template=2&amp;type=-1&amp;cat=-1&amp;user=-1&amp;status=-1"><i class="ion ion-forward"></i> Yönlendirme Listesi</a></li>
						<?php endif?>
						<?php if($_auth['content_truncate'] == 1) : ?>
							<li><a onclick="javascript:return confirm('Pasif Haberleri temizlemek üzeresiniz?')" href="<?=LINK_ACP?>&amp;view=content&amp;action=truncate"><i class="ion ion-android-delete"></i> Pasif Haberleri Temizle</a></li>
						<?php endif?>
					</ul>
				</li>
				<?php endif ?>

				<?php if($_auth['gallery_list'] == 1) : ?>
				<li class="treeview<?php if($view == 'gallery') echo " active";?>">
					<a href="#">
						<i class="ion ion-image"></i>
						<span>Galeri Yönetimi</span>
						<i class="ion ion-ios-arrow-left pull-right"></i>
					</a>
					<ul class="treeview-menu">
						<?php if($_auth['gallery_add'] == 1) : ?>
							<li><a href="<?=LINK_ACP?>&amp;view=gallery&amp;do=add"><i class="ion ion-android-add"></i> Galeri Ekle</a></li>
						<?php endif?>
						<?php if($_auth['gallery_list'] == 1) : ?>
							<li><a href="<?=LINK_ACP?>&amp;view=gallery&amp;do=list"><i class="ion ion-android-list"></i> Galeri Listesi</a></li>
						<?php endif?>
					</ul>
				</li>
				<?php endif ?>

				<?php if($_auth['article_list'] == 1) : ?>
				<li class="treeview<?php if($view == 'article') echo " active";?>">
					<a href="#">
						<i class="ion ion-compose"></i>
						<span>Makale Yönetimi</span>
						<i class="ion ion-ios-arrow-left pull-right"></i>
					</a>
					<ul class="treeview-menu">
						<?php if($_auth['article_add'] == 1) : ?>
							<li><a href="<?=LINK_ACP?>&amp;view=article&amp;do=add"><i class="ion ion-android-add"></i> Makale Ekle</a></li>
						<?php endif?>
						<?php if($_auth['article_list'] == 1) : ?>
							<li><a href="<?=LINK_ACP?>&amp;view=article&amp;do=list"><i class="ion ion-android-list"></i> Makale Listesi</a></li>
						<?php endif?>
					</ul>
				</li>
				<?php endif ?>

				<?php if($_auth['author_list'] == 1) : ?>
				<li class="treeview<?php if($view == 'author') echo " active";?>">
					<a href="#">
						<i class="ion ion-android-document"></i>
						<span>Yazar Yönetimi</span>
						<i class="ion ion-ios-arrow-left pull-right"></i>
					</a>
					<ul class="treeview-menu">
						<?php if($_auth['author_add'] == 1) : ?>
							<li><a href="<?=LINK_ACP?>&amp;view=author&amp;do=add"><i class="ion ion-android-add"></i> Yazar Ekle</a></li>
						<?php endif?>
						<?php if($_auth['author_list'] == 1) : ?>
							<li><a href="<?=LINK_ACP?>&amp;view=author&amp;do=list"><i class="ion ion-android-list"></i> Yazar Listesi</a></li>
						<?php endif?>
					</ul>
				</li>
				<?php endif ?>

				<?php if($_auth['comment_list'] == 1) : ?>
				<li class="treeview<?php if($view == 'comment') echo " active";?>">
					<a href="#">
						<i class="ion ion-chatbox"></i>
						<span>Yorum Yönetimi</span>
						<i class="ion ion-ios-arrow-left pull-right"></i>
					</a>
					<ul class="treeview-menu">
						<?php if($_auth['comment_list'] == 1) : ?>
							<li><a href="<?=LINK_ACP?>&amp;view=comment&amp;do=list"><i class="ion ion-android-list"></i> Yorum Listesi</a></li>
						<?php endif?>
						<?php if($_auth['comment_truncate'] == 1) : ?>
							<li><a onclick="javascript:return confirm('Pasif yorumları temizlemek üzeresiniz?')" href="<?=LINK_ACP?>&amp;view=comment&amp;action=truncate"><i class="ion ion-android-delete"></i> Pasif Yorumları Temizle</a></li>
						<?php endif?>
					</ul>
				</li>
				<?php endif ?>

				<?php if($_auth['contact_list'] == 1) : ?>
				<li class="treeview<?php if($view == 'contact') echo " active";?>">
					<a href="#">
						<i class="ion ion-ios-chatbubble"></i>
						<span>İletişim Formu Yönetimi</span>
						<i class="ion ion-ios-arrow-left pull-right"></i>
					</a>
					<ul class="treeview-menu">
						<?php if($_auth['contact_list'] == 1) : ?>
							<li><a href="<?=LINK_ACP?>&amp;view=contact&amp;do=list"><i class="ion ion-android-list"></i> İletişim Formu Mesajları</a></li>
						<?php endif?>
						<?php if($_auth['contact_truncate'] == 1) : ?>
							<li><a onclick="javascript:return confirm('Pasif iletişim formu mesajlarını temizlemek üzeresiniz')" href="<?=LINK_ACP?>&amp;view=contact&amp;action=truncate"><i class="ion ion-android-delete"></i> Pasif Mesajları Temizle</a></li>
						<?php endif?>
					</ul>
				</li>
				<?php endif ?>



				<?php if($_auth['stats_list'] == 1) : ?>
				<li class="treeview<?php if($view == 'stats') echo " active";?>">
					<a href="#">
						<i class="ion ion-stats-bars"></i>
						<span>İstatistik Yönetimi</span>
						<i class="ion ion-ios-arrow-left pull-right"></i>
					</a>
					<ul class="treeview-menu">
						<?php if($_auth['stats_add'] == 1) : ?>
							<li><a href="<?=LINK_ACP?>&amp;view=stats&amp;do=add"><i class="ion ion-android-add"></i> Güncel İstatistik</a></li>
						<?php endif?>
						<?php if($_auth['stats_list'] == 1) : ?>
							<li><a href="<?=LINK_ACP?>&amp;view=stats&amp;do=list"><i class="ion ion-android-list"></i> İstatistik Arşivi</a></li>
						<?php endif?>
					</ul>
				</li>
				<?php endif ?>

				<?php if($_auth['page_list'] == 1) : ?>
				<li class="treeview<?php if($view == 'page') echo " active";?>">
					<a href="#">
						<i class="ion ion-android-document"></i>
						<span>Sayfa Yönetimi</span>
						<i class="ion ion-ios-arrow-left pull-right"></i>
					</a>
					<ul class="treeview-menu">
						<?php if($_auth['page_list'] == 1) : ?>
							<li><a href="<?=LINK_ACP?>&amp;view=page&amp;do=list"><i class="ion ion-android-list"></i> Sayfa Listesi</a></li>
						<?php endif?>
					</ul>
				</li>
				<?php endif ?>


				<?php if($_auth['user_list'] == 1) : ?>
					<li class="treeview<?php if($view == 'user') echo " active";?>">
						<a href="#">
							<i class="ion ion-android-person"></i>
							<span><?=$modul_title['user']?></span>
							<i class="ion ion-ios-arrow-left pull-right"></i>
						</a>
						<ul class="treeview-menu">
							<?php if($_auth['user_add'] == 1) : ?>
								<li><a href="<?=LINK_ACP?>&amp;view=user&amp;do=add"><i class="ion ion-android-add"></i> Kullanıcı Ekle</a></li>
							<?php endif?>
							<?php if($_auth['user_list'] == 1) : ?>
								<li><a href="<?=LINK_ACP?>&amp;view=user&amp;do=list"><i class="ion ion-android-list"></i> Kullanıcı Listesi</a></li>
							<?php endif?>
							<?php if($_auth['user_truncate'] == 1) : ?>
								<li><a onclick="javascript:return confirm('Erişim Loglarını temizlemek üzeresiniz?')" href="<?=LINK_ACP?>&amp;view=user&amp;action=truncate"><i class="ion ion-android-delete"></i> Erişim Loglarını Temizle</a></li>
							<?php endif?>
						</ul>
					</li>
				<?php endif ?>

				<?php if($_auth['developer_list'] == 1) : ?>
					<li class="treeview<?php if($view == 'developer') echo " active";?>">
						<a href="#">
							<i class="ion ion-code"></i>
							<span><?=$modul_title['developer']?></span>
							<i class="ion ion-ios-arrow-left pull-right"></i>
						</a>
						<ul class="treeview-menu">
							<?php if($_auth['developer_list'] == 1) : ?>
								<li><a href="<?=LINK_ACP?>&amp;view=developer&amp;do=phpinfo"><i class="ion ion-code"></i> PHP Info</a></li>
							<?php endif?>
							<?php if($_auth['developer_list'] == 1) : ?>
								<li><a href="<?=LINK_ACP?>&amp;view=developer&amp;do=opcache"><i class="ion ion-code"></i> OpCache Info</a></li>
							<?php endif?>
							<?php if($_auth['developer_list'] == 1) : ?>
								<li><a href="<?=LINK_ACP?>&amp;view=developer&amp;do=memcache"><i class="ion ion-code"></i> MemCache Info</a></li>
							<?php endif?>
						</ul>
					</li>
				<?php endif ?>

				<?php if($_SESSION[SES]['user_id'] == 1) : ?>
				<?php
					$endtime = microtime(true);
					$endtime = substr(($endtime - $starttime),0,6);

					$kullanim = memory_get_peak_usage(true);
					$kullanim = number_format($kullanim / 1024);
				?>
				<li class="treeview">
					<a href="#">
						<i class="ion ion-android-alert"></i>
						<span>Runtime Info</span>
						<i class="ion ion-ios-arrow-left pull-right"></i>
					</a>
					<ul class="treeview-menu">
						<li><a href=""><i class="ion ion-android-time"></i> SÜS : <?=$endtime?></a></li>
						<li><a href=""><i class="ion ion-flash"></i> MEM : <?=$kullanim?></a></li>
					</ul>
				</li>
				<?php endif ?>
			</ul>
		</section>
	</aside>
