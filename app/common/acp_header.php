<?php if(!defined('APP')) die('...');

	if($_auth['comment_edit'] == 1)			$commentDraft = $_comment->get_comment_draft();
	if($_auth['contact_edit'] == 1)			$contactDraft = $_contact->get_contact_draft();
?>
	<header class="main-header">
		<a href="<?=LINK_ACP?>">
			<div class="logo">
				<span class="logo-mini"></span>
				<span class="logo-lg"><b>Yönetim</b> Paneli</span>
			</div>
		</a>
		<!-- Header Navbar: style can be found in header.less -->
		<nav class="navbar navbar-static-top" role="navigation">
			<a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
				<span class="ion ion-navicon-round"></span>
			</a>
			<div class="navbar-custom-menu">
				<ul class="nav navbar-nav">
					<?php if($_auth['comment_edit'] == 1 && $commentDraft > 0) :?>
						<li class="dropdown notifications-menu">
							<a href="<?=LINK_ACP?>&amp;view=comment&amp;do=list" title="<?=$commentDraft?> yorum denetim için bekliyor">
								<i class="ion ion-chatbox"></i> <span class="label label-danger"><?=$commentDraft?></span>
							</a>
						</li>
					<?php endif; ?>
					<?php if($_auth['contact_edit'] == 1 && $contactDraft > 0) :?>
						<li class="dropdown notifications-menu">
							<a href="<?=LINK_ACP?>&amp;view=contact&amp;do=list" title="<?=$contactDraft?> iletişim formu mesajı denetim için bekliyor">
								<i class="ion ion-ios-chatbubble"></i> <span class="label label-warning"><?=$contactDraft?></span>
							</a>
						</li>
					<?php endif; ?>
					<li class="dropdown user user-menu">
						<a href="#" class="dropdown-toggle" data-toggle="dropdown">
							<img src="<?=G_IMGLINK_DEV?>Avatar/<?=$_SESSION[SES]['user_avatar']?>" class="user-image" alt="User Image">
							<span class="hidden-xs"><?=$_SESSION[SES]['user_name']?></span>
						</a>
						<ul class="dropdown-menu">
							<!-- User image -->
							<li class="user-header">
								<img src="<?=G_IMGLINK_DEV?>Avatar/<?=$_SESSION[SES]['user_avatar']?>" class="img-circle" alt="User Image">
								<p><?=$_SESSION[SES]['user_name']?></p>
								<p><?=$_SESSION[SES]['user_email']?></p>
							</li>
							<li class="user-footer">
								<div class="pull-left">
									<a href="<?=LINK_ACP?>&amp;view=password" class="btn btn-default btn-flat">Bilgilerim</a>
								</div>
								<div class="pull-right">
									<a href="<?=LINK_CIKIS?>" class="btn btn-default btn-flat">Çıkış</a>
								</div>
							</li>
						</ul>
					</li>
				</ul>
			</div>
		</nav>
	</header>
