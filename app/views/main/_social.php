<?php

$share_url = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
$share_title = ($title) ? $title: 'Последние новости | ' .App::param('site_name');
$share_desc = ($desc) ? $desc: 'Самые последние новости';

$share_vk = rawurlencode('https://vk.com/share.php?url=' . rawurlencode($share_url) . '&title=' . rawurlencode($title) . '&description=' . rawurlencode($share_desc) . '&image=' . rawurlencode('http://' .App::param('site_name'). '/img/social_poster.png'));
$share_fb = rawurlencode($share_url);
$share_tw = rawurlencode($share_url . ' - актуальные новости шоу-бизнеса.');
$share_ok = rawurlencode($share_url);

?>
<noindex>
<div class="text-xs-right social">
    <p class="social_icons_long">
        <a href="https://oauth.vk.com/authorize?client_id=-1&redirect_uri=<?= $share_vk; ?>&display=widget" target="_blank" title="Вконтакте" class="vk_background">
            <i class="fa fa-vk fa-fw"></i></a>
        <a href="https://connect.ok.ru/offer?url=<?= $share_ok; ?>" target="_blank" title="Одноклассники" class="odnoklassniki_background">
            <i class="fa fa-odnoklassniki fa-fw"></i></a>
        <a href="https://www.facebook.com/sharer/sharer.php?u=<?= $share_fb; ?>" target="_blank" title="Facebook" class="fb_background">
            <i class="fa fa-facebook fa-fw"></i></a>
        <a href="https://twitter.com/intent/tweet?status=<?= $share_tw; ?>" target="_blank" title="Twitter" class="twitter_background">
            <i class="fa fa-twitter fa-fw"></i></a>
        <a href="#favorite" charset="windows-1251" rel="sidebar" class="favorite fav_background" title="Добавить в закладки" onClick="Bookmark()"><i class="fa fa-fw fa-bookmark-o" aria-hidden="true"></i></a>
    </p>
</div>
</noindex>