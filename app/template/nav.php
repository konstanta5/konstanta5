<?php
$nav[] = array('link' => '/', 'anchor' => 'Main');

// for content-manager
if (in_array(App::$user->status, array('content', 'moderator', 'admin'))) {
//    $nav[] = array('link' => '/pages', 'anchor' => 'Страницы');
}
// for moderator
if (in_array(App::$user->status, array('moderator', 'admin'))) {
//    $nav[] = array('link' => '/pages', 'anchor' => 'Страницы');
}

$nav[] = array('link' => '/help', 'anchor' => 'Помощь');
$nav[] = array('link' => '/kontakty', 'anchor' => 'Контакты');
if (!App::$user->id) {
//    $nav[] = array('link' => '/users/login', 'anchor' => 'Вход');
//    $nav[] = array('link' => '/users/registration', 'anchor' => 'Регистрация');
}
?>
<nav class="navbar navbar-blue">
    <div class="alter-logo hidden-md-up">
        <a href="/">
            <span class="fa fa-home" aria-hidden="true"></span>
        </a>
    </div>
    <button class="navbar-toggler hidden-md-up pull-xs-right" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
        <span class="fa fa-bars" aria-hidden="true"></span>
    </button>
    <div class="collapse navbar-toggleable-sm" id="navbarResponsive">
        <ul class="nav navbar-nav">

            <?php
            $uri_pars = explode('/', getenv('REQUEST_URI'));
            foreach ($nav as $item) :
                $active = $current = '';
                $uri = '/' . $uri_pars[1] . (($uri_pars[2] && Route::$routes[2] != 'index') ? '/' . $uri_pars[2] : '');
                if ($item['link'] == $uri) {
                    $active = 'active';
                    $current = '<span class="sr-only">(current)</span>';
                }
                ?>
                <li class="nav-item <?= $active ?>">
                    <a class="nav-link" href="<?= $item['link'] ?>">
                        <?= $item['anchor'] ?> <?= $current ?><i class="fa fa-long-arrow-right pull-right arrow-icon hidden-md-up" aria-hidden="true"></i>
                    </a>
                </li>
            <?php endforeach; ?>

            <?php if (App::$user->id) : ?>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="testDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <?= App::$user->login ?>
                    </a>
                    <div class="dropdown-menu" aria-labelledby="testDropdown">
                        <?php if (in_array(App::$user->status, App::param('content'))) : ?>
                            <a class="dropdown-item" href="/news/add">Добавить новость</a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item" href="/news/my">Мои новости</a>
                            <a class="dropdown-item" href="/statistic/yesterday/news/<?= App::$user->login ?>">За вчера</a>
                            <a class="dropdown-item" href="/statistic/month/news/<?= App::$user->login ?>">За месяц</a>
                            <a class="dropdown-item" href="/statistic/lastmonth/news/<?= App::$user->login ?>">За прошлый месяц</a>
                            <div class="dropdown-divider"></div>
                        <?php endif ?>
                        <a class="dropdown-item" href="/users/logout" title="Выход">
                            Выход
                            <span class="fa fa-sign-out" aria-hidden="true"></span>
                        </a>
                    </div>
                </li>
            <?php endif ?>

            <?php if (in_array(App::$user->status, array('moderator', 'admin'))) : ?>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="testDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Админ</a>
                    <div class="dropdown-menu" aria-labelledby="pagesDropdown">
                        <a class="dropdown-item" href="/news">Все новости</a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="/pages/info">Обычные страницы</a>
                        <a class="dropdown-item" href="/pages">Все страницы</a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="/comments">Отзывы</a>
                        <a class="dropdown-item" href="/categories">Категории</a>
                        <a class="dropdown-item" href="/link">История ЧПУ</a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="/users">Пользователи</a>
                    </div>
                </li>
            <?php endif ?>

            <?php if (App::$user->id == 1) : ?>
                <li class="nav-item dropdown">
                    <a class="nav-link text-warning dropdown-toggle" href="#" id="testDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">test</a>
                    <div class="dropdown-menu" aria-labelledby="testDropdown">
                        <a class="dropdown-item" href="/test/sqlresult">sqlResult</a>
                        <a class="dropdown-item" href="/test/query">sqlQuery</a>
                        <a class="dropdown-item" href="/test/phpinfo">phpinfo</a>
                        <a class="dropdown-item" href="/test/test">test</a>
                    </div>
                </li>
            <?php endif ?>
        </ul>
    </div>
</nav>