<?php if(is_array($alert) && count($alert) > 0) : ?>
<div class="alert alert-<?=$alert['type']?>">
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
    <?=$alert['msg']?>
</div>
<?php endif?>