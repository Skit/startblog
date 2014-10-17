<div class="post">
	<div class="title">
		<?php
            if($this->action->id == 'index') echo CHtml::link(CHtml::encode($data->title), $data->url);
            elseif ($this->action->id == 'view') echo '<h3>'.$data->title.'</h3>';
        ?>
	</div>
	<div class="content">
		<?php
			$this->beginWidget('CMarkdown', array('purifyOutput'=>true));

                // NOTE: Выводим превью в списке и полный текст в просмотре
                if($this->action->id == 'index') echo $data->preview;
                elseif ($this->action->id == 'view') {

                    // NOTE: по умолчанию поле $data->content_highlight равно NULL
                    // обычный текст хранится в $data->content. Выводим текст с
                    // подсветкой кода если таковой есть.
                    if($data->content_highlight != NULL) echo $data->content_highlight;
                    else echo $data->content;
                }
			$this->endWidget();
		?>
	</div>
	<div class="nav">
		<b>Tags:</b>
		<?php echo implode(', ', $data->tagLinks); ?>
		<p>
		<?php
        // NOTE: Выводим превью в списке и полный текст в просмотре
        if($this->action->id == 'index') {
            echo CHtml::link("Comments ({$data->commentCount})",$data->url.'#comments'); }

        else {
            echo "Comments ({$data->commentCount}) | Last updated on ".date('F j, Y',$data->update_time); } ?>
        </p>
	</div>
</div>
