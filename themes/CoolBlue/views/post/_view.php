<article class="post">
    <div class="primary">
		<?php
            if($this->action->id == 'index') echo '<h2>'.CHtml::link(CHtml::encode($data->title), $data->url).'</h2>';
            elseif ($this->action->id == 'view') echo '<h2>'.$data->title.'</h2>';
        ?>
        <p class="post-info"><span>filed under</span><?php echo CHtml::link($data->category->title, array('category/'.$data->category->id)); $data->category->title; ?></p>

        <div class="image-section">
            <img src="images/img-post.jpg" alt="image post" height="206" width="498"/>
        </div>
		<?php $this->beginWidget('CMarkdown', array('purifyOutput'=>true));

                // NOTE: Выводим превью в списке и полный текст в просмотре
                if($this->action->id == 'index') echo $data->preview;
                elseif ($this->action->id == 'view') {

                    // NOTE: по умолчанию поле $data->content_highlight равно NULL
                    // обычный текст хранится в $data->content. Выводим текст с
                    // подсветкой кода если таковой есть.
                    if($data->content_highlight != NULL) echo $data->content_highlight;
                    else echo $data->content;
                }
			$this->endWidget(); ?>
    </div>

<aside>
    <p class="dateinfo"><?php echo date('M',$data->update_time)?><span><?php echo date('d',$data->update_time)?></span></p>

    <div class="post-meta">
        <h4>Post Info</h4>
        <ul>
            <li class="user"><a href="#">Admin</a></li>
            <li class="time"><a href="#">12:30 PM</a></li>
            <li class="comment"><?php echo CHtml::link("Comments ({$data->commentCount})",$data->url.'#comments')?></li>
            <li class="permalink"><a href="#">Permalink</a></li>
        </ul>
    </div>

    <div class="post-meta">
        <h4>tags</h4>
        <ul class="tags">
            <?php
            foreach ($data->tagLinks as $link) {
                echo '<li>'.$link.'</li>';
                    }
            ?>
        </ul>
    </div>

</aside>
</article>
