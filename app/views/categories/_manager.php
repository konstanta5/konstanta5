<a href="/categories/rankup/<?= $row['id'] ?>" data-type="ajax" data-after="moveRank" title="Вверх">
    <span class="fa fa-chevron-up"></span>
</a>
<a href="/categories/rankdown/<?= $row['id'] ?>" data-type="ajax" data-after="moveRank" data-direct="down" title="Вниз">
    <span class="fa fa-chevron-down"></span>
</a>

<a class="m-l-1" href="/categories/remove/<?=$row['id']?>" data-type="ajax" data-before="removeConfirm" data-after="remove" data-parent="li" title="Удалить">
    <span class="fa fa-trash text-danger"></span>
</a>