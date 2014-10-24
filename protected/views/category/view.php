<?php
/* @var $this CategoryController */
/* @var $model Category */

$this->breadcrumbs=array(
	'Categories'=>array('index'),
	$model->title,
);

$this->menu=array(
	array('label'=>'List Category', 'url'=>array('index')),
	array('label'=>'Create Category', 'url'=>array('create')),
	array('label'=>'Update Category', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete Category', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage Category', 'url'=>array('admin')),
);
?>

<?php
$this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'title',
        array(
            'label'=>'Описание',
            'value'=> ($model->description !== null) ? $model->description : 'Нет описания категории'
        ),
        array(
            'label'=>'Картинка категории',
            'type'=>'image',
            'value'=> $imageSource
        ),
    ),
));

$this->widget('application.components.RelaitedPostWidget', array('posts'=>$model->posts));
?>