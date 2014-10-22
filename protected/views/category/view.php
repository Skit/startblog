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

<h1>View Category #<?php echo $model->id; ?></h1>

<?php
foreach($model->imageCats as $k => $images){
    if($images->templates=='view')
    {
        $image['source'] = $images->source;
        $image['title'] = $images->title;
    }
}
$this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'title',
        array(
            'label'=>'Image',
            'type'=>'image',
            'value'=> Yii::app()->request->baseUrl.DS.'media'.DS.'images'.DS.$image['source']),
        ),
)); ?>
