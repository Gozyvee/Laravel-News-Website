<?php if(isset($links) && is_array($links)) : ?>
<div style="padding: 10px; clear: both; text-align: center;">
    <?php foreach($links as $link): ?>
    <a href="<?= $link[1] ?>">
        <button class="btn <?= $link[2] ? 'btn-primary' : '' ?> "><?=$link[0]?></button>
    </a>
    <?php endforeach; ?>
</div>
<?php endif; ?>