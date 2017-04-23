<footer class="p-x-1 p-y-1">
    <div class="m-l-2 pull-xs-right">
        <!--LiveInternet counter--><script type="text/javascript">
            document.write("<a href='//www.liveinternet.ru/click' " +
                    "target=_blank><img src='//counter.yadro.ru/hit?t26.5;r" +
                    escape(document.referrer) + ((typeof (screen) == "undefined") ? "" :
                    ";s" + screen.width + "*" + screen.height + "*" + (screen.colorDepth ?
                            screen.colorDepth : screen.pixelDepth)) + ";u" + escape(document.URL) +
                    ";h" + escape(document.title.substring(0, 80)) + ";" + Math.random() +
                    "' alt='' title='LiveInternet: показано число посетителей за" +
                    " сегодня' " +
                    "border='0' width='88' height='15'><\/a>")
        </script><!--/LiveInternet-->
    </div>
    <?php if (!App::$user->id): ?>
        <a class="m-l-1 pull-xs-right" href="/users/login">Вход</a>
    <?php endif; ?>
    <a class="pull-xs-right" href="/map">Карта сайта</a>
    <a class="hidden-xs-down" href="/"><?= App::param('site_name') ?></a> &copy; 2009 - <?= date('Y') ?>
</footer>