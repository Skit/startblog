<?php
/* @var $this CategoryController */
/* @var $model Category */
/* @var $form CActiveForm */
?>

<div class="form">

    <?php  Yii::app()->getClientScript()->registerCoreScript('jquery');
    $form = $this->beginWidget('CActiveForm', array(
	'id'=>'category-form',
	// Please note: When you enable ajax validation, make sure the corresponding
	// controller action is handling ajax validation correctly.
	// There is a call to performAjaxValidation() commented in generated controller code.
	// See class documentation of CActiveForm for details on this.
	'enableAjaxValidation'=>false,
    'htmlOptions'=>array('enctype'=>'multipart/form-data'),
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
        <?php echo $form->labelEx($model, 'title'); ?>
        <?php echo $form->textField($model, 'title', array('size' => 50, 'maxlength' => 50, 'id' => 'ajaxAlias')); ?>
        <?php echo $form->error($model, 'title'); ?>
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
        <?php echo $form->labelEx($model,'description'); ?>
        <?php echo CHtml::activeTextArea($model,'description',array('rows'=>10, 'cols'=>51)); ?>
        <p class="hint">You may use <a target="_blank" href="http://daringfireball.net/projects/markdown/syntax">Markdown syntax</a>.</p>
        <?php echo $form->error($model,'description'); ?>
    </div>

    <div class="row">
        <?php echo $form->labelEx($model, 'meta_description'); ?>
        <?php echo CHtml::activeTextArea($model, 'meta_description', array('rows' => 3, 'cols' => 51)); ?>
        <?php echo $form->error($model, 'meta_description'); ?>
    </div>

    <div class="row">
        <?php echo $form->labelEx($model, 'meta_keywords'); ?>
        <?php echo CHtml::activeTextArea($model, 'meta_keywords', array('rows' => 3, 'cols' => 51)); ?>
        <?php echo $form->error($model, 'meta_keywords'); ?>
    </div>

    <?php /* поле для загрузки файла */ ?>
    <div class="field">
        <?php if($model->image): ?>
            <p><?php echo CHtml::image($imageSource); ?></p>
        <?php endif; ?>
        <?php echo $form->labelEx($model,'image'); ?>
        <?php echo $form->fileField($model,'image'); ?>
        <?php echo $form->error($model,'image'); ?>
    </div>
	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Создать' : 'Сохранить'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->

<script>
    $(document).ready(function () {

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
                url: '<?=$this->createUrl('category/translate')?>',
                data: 'str=' + input.val(),
                success: function (data) {
                    output.val(data);
                    loader.attr("style", "display:none");
                }
            });
        }
    });
</script>