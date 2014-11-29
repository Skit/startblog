<div class="form">
    <?php
    Yii::app()->getClientScript()->registerCoreScript('jquery');
    $form = $this->beginWidget('CActiveForm',
        array('htmlOptions' => array('enctype' => 'multipart/form-data'))); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo CHtml::errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'title'); ?>
        <?php echo $form->textField($model, 'title', array('size' => 50, 'maxlength' => 50, 'id' => 'ajaxAlias')); ?>
		<?php echo $form->error($model,'title'); ?>
        <div id="translateGo" style="
                                color: blue;
                                cursor: pointer;
                                float: right;
                                position: relative;
                                right: 210px;
                                padding: 5px;
                                text-decoration: underline;">Перевести
            <img src="http://localhost/startblog/images/728.GIF" onclick="alert('yes!')" id="loader"
                 style="display:none"/>
        </div>
	</div>

    <div class="row">
        <?php echo $form->labelEx($model, 'alias'); ?>
        <?php echo $form->textField($model, 'alias', array('size' => 50, 'maxlength' => 50, 'id' => 'translateNow')); ?>
        <?php echo $form->error($model, 'alias'); ?>
    </div>

    <div class="row">
        <?php echo $form->labelEx($model,'preview'); ?>
        <?php echo CHtml::activeTextArea($model,'preview',array('rows'=>5, 'cols'=>82)); ?>
        <p class="hint">Если оставить пустым будет использоваться часть основного текста (<?php echo Yii::app()->params['shortText'];?>) в соответствии с настройками</p>
        <?php echo $form->error($model,'preview'); ?>
    </div>

	<div class="row">
        <?php echo $form->labelEx($model,'content'); ?>
        <?php //echo CHtml::activeTextArea($model,'content',array('rows'=>5, 'cols'=>82)); ?>
        <?php echo $form->error($model,'content'); ?>
        <?php
        Yii::import('ext.imperavi-redactor-widget.ImperaviRedactorWidget');
        $this->widget('ImperaviRedactorWidget', array(
            'model' => $model,
            'attribute' => 'content',
            //'selector' => '.content',
            'options' => array(
                'focus' => true,
                'lang' => 'ru',
                'imageLink' => true,
                'imageUpload' => $this->createUrl('post/image/upload'),
                'imageManager' => $this->createUrl('post/image/list'),
                'buttonSource' => true,
                //'iframe' => false,
                'convertLinksImages' => true,
                //'imageEditable' => true,
                'dragImageUpload' => false,
                'pastePlainText' => true,
                'cleanOnPaste' => true,
            ),
            'plugins' => array(
                'fullscreen' => array(
                    'js' => array('fullscreen.js')),
                'filemanager' => array(
                    //'basePath' => 'ext.imperavi.assets.plugins.filemanager',
                    //'baseUrl' => '/js/filemanager',
                    'js' => array('filemanager.js'),
                    'imagemanager' => array(
                        //'basePath' => 'ext.imperavi.assets.plugins.filemanager',
                        //'baseUrl' => '/js/filemanager',
                        'js' => array('imagemanager.js')),
                    'foncolor' => array(
                        'js' => array('foncolor.js')),
                ),
            ),
        ));
        ?>
    </div>

    <div class="row">
        <?php echo $form->labelEx($model, 'syntax'); ?>
        <?php echo $form->dropDownList($model, 'syntax', Lookup::items('SyntaxHighlight')); ?>
        <?php echo $form->error($model, 'syntax'); ?>
    </div>

    <div class="row">
        <?php echo $form->labelEx($model,'other_content'); ?>
        <?php echo CHtml::activeTextArea($model,'other_content',array('rows'=>5, 'cols'=>82)); ?>
        <p class="hint">Дополнительно по теме. Поле которое будет скрыто от поисковиков.</p>
        <?php echo $form->error($model,'other_content'); ?>
    </div>

    <div class="row">
        <?php echo $form->labelEx($model,'category_id'); ?>
        <?php echo $form->dropDownList($model,'category_id',Category::allCategory()); ?>
        <?php echo $form->error($model,'category_id'); ?>
    </div>

	<div class="row">
		<?php echo $form->labelEx($model,'tags'); ?>
		<?php $this->widget('CAutoComplete', array(
			'model'=>$model,
			'attribute'=>'tags',
			'url'=>array('suggestTags'),
			'multiple'=>true,
			'htmlOptions'=>array('size'=>50),
		)); ?>
		<p class="hint">Please separate different tags with commas.</p>
		<?php echo $form->error($model,'tags'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'status'); ?>
		<?php echo $form->dropDownList($model,'status',Lookup::items('PostStatus')); ?>
		<?php echo $form->error($model,'status'); ?>
	</div>

    <?php /* поле для загрузки файла */ ?>
    <div class="field">
        <?php if ($model->image): ?>
            <p><?php echo CHtml::image($imageSource); ?></p>
        <?php endif; ?>
        <?php echo $form->labelEx($model, 'image'); ?>
        <?php echo $form->fileField($model, 'image'); ?>
        <?php echo $form->error($model, 'image'); ?>
    </div>

    <div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Создать' : 'Сохранить'); ?>
	</div>
    <p class="hint">You may use <a target="_blank" href="http://daringfireball.net/projects/markdown/syntax">Markdown
            syntax</a>.</p>

<?php $this->endWidget(); ?>

</div><!-- form -->

<script>
    jQuery.noConflict();
    jQuery(document).ready(function ($) {

        var input = $("#ajaxAlias");
        var output = $("#translateNow");
        var button = $("#translateGo");
        var loader = $('#loader');

        button.click(function () {
            loader.attr("style", "display:block");
            translateNow();
        })

        input.change(function () {
            loader.attr("style", "display:block");
            translateNow();
        });

        function translateNow() {
            $.ajax({
                type: "POST",
                url: '<?=$this->createUrl('post/translate')?>',
                data: 'str=' + input.val(),
                success: function (data) {
                    output.val(data);
                    loader.attr("style", "display:none");
                }
            });
        }
    });
</script>