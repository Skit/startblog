<ul>
    <?php foreach ($this->getSection() as $section): ?>
        <li>
            <?= CHtml::link(CHtml::encode($section->title), $section->getUrl()); ?>
        </li>
    <?php endforeach; ?>
</ul>