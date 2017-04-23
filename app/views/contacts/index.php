<div itemscope itemtype="http://schema.org/Organization" class="contacts">
    <h1><?= $title ?></h1>
	<meta itemprop="name" content="pro-obmen.ru">
	<div itemprop="address" itemscope itemtype="http://schema.org/PostalAddress" class="hidden-xs-up">
	    Адрес:
	    <span itemprop="streetAddress">улица Кулакова, 20к1</span>
	    <span itemprop="addressLocality">Москва</span>
  	</div>
    <?php if ($row['content']) : ?>
        <?= $view->setDir('main')->renderView('_content', array('row' => $row)) ?>
    <?php endif ?>

    <p>Наш телефон: <span itemprop="telephone"><a href="callto:+7(925)604-11-63">+7 (925) 	604-11-63</a></span><br>
    Наш e-mail:    		
			<script>
				suem = "ma";
			    suem += "il";
			    suem += "@";
			    suem += "pro";
			    suem += "-obm";
			    suem += "en.ru";
			    mt = "mai";
			    mt += "lto:";
			    document.write('<a href="' + mt + suem + '">' + suem + '</a>');
			</script>
		
    </p>
    <p>Используйте эту форму для отправки сообщения. Обратите внимание, что если вы укажете несуществующий или некорректный адрес электронной почты, мы не сможем отправить вам ответ.</p>
    <?=$view->setDir('contacts')->renderView('_form', array('row' => $row)) ?>

    <div class="content">
        <?= $row['content_after'] ?>
    </div>

</div>