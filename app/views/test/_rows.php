<table class="table table-striped table-hover table-bordered">
    <thead>
        <tr>
            <?php foreach ($rows[0] as $field => $temp): ?>
                <th><?= $field ?></th>
            <?php endforeach; ?>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($rows as $row) : ?>
            <tr>
                <?php foreach ($row as $value): ?>
                    <td><?= $value ?></td>
                <?php endforeach; ?>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>