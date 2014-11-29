<div class="sibmenu">
    <h3>Разделы</h3>
    <ul>
        <?php foreach ($this->getSection() as $section): ?>
            <li>
                <?= CHtml::link(CHtml::encode($section->title), $section->getUrl()); ?>
                (<?= Post::model()->getPendingPostCount($section->id) ?>)
            </li>
        <?php endforeach; ?>
    </ul>
</div>