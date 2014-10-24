<ul>
    <?php foreach($posts as $post): ?>
    <li><?=CHtml::link(CHtml::encode($post->title),
            Yii::app()->createUrl('post/view', array(
            'id'=>$post->id,
            'title'=>CHtml::encode($post->title)))
        );
        ?>
    </li>
    <?php endforeach; ?>
</ul>

