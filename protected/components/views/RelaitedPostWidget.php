<ul>
    <?php foreach($posts as $post): ?>
    <li><?=CHtml::link(CHtml::encode($post->title),
            $this->controller->createUrl('post/view', array(
            'id'=>$post->id,
            'title'=>CHtml::encode($post->title)))
        );
        ?>
    </li>
    <?php endforeach; ?>
</ul>

