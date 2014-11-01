<?php

class CategoryController extends Controller
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout='//layouts/column2';

    public $meta_description;
    public $meta_keywords;

	/**
	 * @return array action filters
	 */
	public function filters()
	{
		return array(
			'accessControl', // perform access control for CRUD operations
			'postOnly + delete', // we only allow deletion via POST request
		);
	}

    public function actions()
    {
        return array(
            'translate' => array(
                'class' => 'application.controllers.ActionTranslate',
            ),
        );
    }

	/**
	 * Specifies the access control rules.
	 * This method is used by the 'accessControl' filter.
	 * @return array access control rules
	 */
	public function accessRules()
	{
		return array(
			array('allow',  // allow all users to perform 'index' and 'view' actions
                'actions' => array('index', 'view', 'translate'),
				'users'=>array('*'),
			),
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'=>array('create','update'),
				'users'=>array('@'),
			),
			array('allow', // allow admin user to perform 'admin' and 'delete' actions
				'actions'=>array('admin','delete'),
				'users'=>array('@'),
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}

	/**
	 * Displays a particular model.
	 * @param integer $id the ID of the model to be displayed
	 */
	public function actionView($id)
	{
        $model=$this->relationPost($id);

        if ($model->attributes['meta_tags'] != null) {
            $meta_tags = unserialize($model->attributes['meta_tags']);

            $meta = Yii::app()->getClientScript();

            $meta->registerMetaTag($meta_tags['meta_keywords'], 'Keywords');
            $meta->registerMetaTag($meta_tags['meta_description'], 'description');
        }

        $this->render('view', array(
			'model'=> $model,
            'imageSource' => self::_getImage($model),
		));
	}

    /**
     * @param $id
     * @return mixed
     * @throws CHttpException
     */
    public function relationPost($id)
    {
        //$model=Category::model()->with('posts')->find('category_id=:ID', array(':ID'=>$id));
        $model = Category::model()->with('imageCats', 'posts')->find('owner_id=:ID AND owner=:OW AND category_id=:ID', array(':ID' => $id, ':OW' => 'Category', ':ID' => $id));

        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }

    /**
     *
     */
    private function _getImage($model)
    {

        if (!empty($model->imageCats)) {
            foreach ($model->imageCats as $k => $images) {
                if ($images->id == $model->image)
                    $imageSource = $images->source;
            }
        } else
            $imageSource = 'no_image.jpg';

        return Yii::app()->request->baseUrl . '/media/images/' . $imageSource;
    }

	/**
     * Creates a new model.
     * If creation is successful, the browser will be redirected to the 'view' page.
	 */
    public function actionCreate()
	{
        $model = new Category;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Category']))
		{
			$model->attributes=$_POST['Category'];
			if($model->save())
				$this->redirect(array('view','id'=>$model->id));
		}

        $this->render('create', array(
			'model'=>$model,
		));
	}

	/**
     * Updates a particular model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id the ID of the model to be updated
	 */
    public function actionUpdate($id)
	{


        $model = $this->loadModel($id);

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if (isset($_POST['Category'])) {
            $model->attributes = $_POST['Category'];
            if ($model->save())
                $this->redirect(array('view', 'id' => $model->id));
        }

        $this->render('update', array(
            'model' => $model,
            'imageSource' => self::_getImage($model),
        ));
	}

	/**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer $id the ID of the model to be loaded
     * @return Category the loaded model
     * @throws CHttpException
	 */
    public function loadModel($id)
	{
        $model = Category::model()->findByPk($id);
        // Рабочий пример
        //$model=Category::model()->with('imageCats')->find('id_images=:ID', array(':ID'=>93));

        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
	}

	/**
     * Deletes a particular model.
     * If deletion is successful, the browser will be redirected to the 'admin' page.
     * @param integer $id the ID of the model to be deleted
	 */
    public function actionDelete($id)
	{
        $this->loadModel($id)->delete();

        // if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
        if (!isset($_GET['ajax']))
            $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
	}

	/**
     * Lists all models.
	 */
    public function actionIndex()
	{
        $meta = Yii::app()->getClientScript();

        $metaDefault = Yii::app()->params['defaultMeta'];
        $meta->registerMetaTag($metaDefault['keywords'], 'Keywords');
        $meta->registerMetaTag($metaDefault['description'], 'description');

        $dataProvider = new CActiveDataProvider('Category');
        $this->render('index', array(
            'dataProvider' => $dataProvider,
        ));
	}

    /**
     * Manages all models.
     */
    public function actionAdmin()
    {
        $model = new Category('search');
        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['Category']))
            $model->attributes = $_GET['Category'];

        $this->render('admin', array(
            'model' => $model,
        ));
    }

	/**
	 * Performs the AJAX validation.
	 * @param Category $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='category-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
