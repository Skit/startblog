<?php

/**
 * This is the model class for table "{{category}}".
 *
 * The followings are the available columns in table '{{category}}':
 * @property integer $id
 * @property integer $post_id
 * @property string $title
 * @property string $image
 * @property string $description
 * @property string $meta_tags
 * @property string $alias
 *
 * The followings are the available model relations:
 * @property Post $post
 */
class Category extends CActiveRecord
{
    /**
     * @var $modelRelations string to use in behavior
     * @var $tableRelations string to use in behavior
     * @var $_imageFilePath string path to file save folder
     */
    public $modelRelations = 'ImageCat';
    public $tableRelations = 'id_category';
    public $meta_description;
    public $meta_keywords;
    private $_imageFilePath = 'webroot.media.images';

    /**
     * @return array categories for use html dropdownlist tag
     */
    public static function allCategory()
    {
        return CHtml::listData(
            self::model()->findAll(), 'id', 'title');
    }

	/**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return Category the static model class
	 */
    public static function model($className = __CLASS__)
	{
        return parent::model($className);
	}

    /**
     * @return string the associated database table name
	 */
    public function tableName()
	{
        return '{{category}}';
	}

	/**
     * @return array validation rules for model attributes.
	 */
    public function rules()
	{
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
		return array(
            array('title', 'required'),
            array('title', 'length', 'max' => 50),
            array('image', 'length', 'max' => 128),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('title, image, description', 'safe', 'on' => 'search'),
            array('meta_description, meta_keywords', 'safe'),
		);
	}

	/**
     * @return array relational rules.
	 */
    public function relations()
	{
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
		return array(
            'imageCats' => array(self::MANY_MANY, 'Images', 'tbl_image_cat(id_categoty, id_images)',
                'select' => 'alt, title, source'),
            'posts' => array(self::HAS_MANY, 'Post', 'category_id', 'select' => 'title'),
		);
	}

	/**
     * @return array customized attribute labels (name=>label)
	 */
    public function attributeLabels()
	{
        return array(
            'title' => 'Заголовок',
            'alias' => 'Алисас заголовка',
            'description' => 'Описание',
            'meta_description' => 'Мета описание',
            'meta_keywords' => 'Ключевые слова',
            'image' => 'Изображение',
        );
    }

    /**
     * Retrieves a list of models based on the current search/filter conditions.
     *
     * Typical usecase:
     * - Initialize the model fields with values from filter form.
     * - Execute this method to get CActiveDataProvider instance which will filter
     * models according to data in model fields.
     * - Pass data provider to CGridView, CListView or any similar widget.
     *
     * @return CActiveDataProvider the data provider that can return the models
     * based on the search/filter conditions.
     */
    public function search()
    {
        // @todo Please modify the following code to remove attributes that should not be searched.

        $criteria = new CDbCriteria;

        $criteria->compare('id', $this->id);
        $criteria->compare('title', $this->title, true);
        $criteria->compare('Description', $this->description, true);
        $criteria->compare('image', $this->image, true);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
	}

    /**
     * @return string link to category view
     */
    public function getUrl()
    {
        return Yii::app()->createUrl('category/view', array(
            'id' => $this->id,
            'title' => $this->title,
        ));
    }

    /**
     * @return bool
     */
    public function beforeSave()
    {
        if (parent::beforeSave() && ($this->isNewRecord == false)) {
            if ($this->meta_description != '')
                $meta_array['meta_description'] = $this->meta_description;

            if ($this->meta_keywords != '')
                $meta_array['meta_keywords'] = $this->meta_keywords;

            $this->meta_tags = serialize($meta_array);

            return true;
        } else
            return false;
    }

    /**
     * This behavior provide image manipulation
     * @return array
     */
    public function behaviors()
    {
        return array(
            // наше поведение для работы с файлом
            'uploadableFile' => array(
                'class' => 'application.components.UploadableFileBehavior',
                'fileTypes' => 'jpg,jpeg,png',
                'attributeName' => 'image',
                'savePathAlias' => $this->_imageFilePath,
            ),
        );
    }
}
