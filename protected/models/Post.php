<?php

class Post extends CActiveRecord
{
	/**
	 * The followings are the available columns in table 'tbl_post':
	 * @var integer $id
	 * @var string $title
     * @var string $alias
	 * @var string $content
	 * @var string $preview
	 * @var string $tags
	 * @var integer $status
	 * @var integer $syntax
	 * @var integer $create_time
	 * @var integer $update_time
	 * @var integer $author_id
	 */
	const STATUS_DRAFT=1;
	const STATUS_PUBLISHED=2;
	const STATUS_ARCHIVED=3;

    const STATUS_PENDING = 1;
    const STATUS_APPROVED = 2;

    const SYNTAX_PLAIN=1; // В специфики приложения не используется
    const SYNTAX_TAG=2;
    const SYNTAX_NONE=3;
    public $syntax;
    /**
     *
     */
    public $modelRelations = 'PostCat';
    public $tableRelations = 'id_post';
	private $_oldTags;
    /**
     *
     */
    private $_imageFilePath = 'webroot.media.images';

    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return '{{post}}';
    }

	/**
     * @return array validation rules for model attributes.
	 */
    public function rules()
	{
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('title, alias, content, status, syntax, category_id', 'required'),
            array('status, syntax', 'in', 'range' => array(1, 2, 3)),
            array('title', 'length', 'max' => 128),
            array('tags', 'match', 'pattern' => '/^([\w\s,]|[\x{0400}-\x{04FF}])+$/ui', 'message' => 'Tags can only contain word characters.'),
            array('tags', 'normalizeTags'),
            array('other_content, preview, category_id', 'safe'),
            array('content', 'safe'),

            array('title, status, image', 'safe', 'on' => 'search'),
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
            'author' => array(self::BELONGS_TO, 'User', 'author_id'),
            'category' => array(self::BELONGS_TO, 'Category', 'category_id'),
            'comments' => array(self::HAS_MANY, 'Comment', 'post_id', 'condition' => 'comments.status=' . Comment::STATUS_APPROVED, 'order' => 'comments.create_time DESC'),
            'commentCount' => array(self::STAT, 'Comment', 'post_id', 'condition' => 'status=' . Comment::STATUS_APPROVED),
            'postCats' => array(self::MANY_MANY, 'Images', 'tbl_post_cat(id_post, id_images)'),

        );
	}

	/**
     * @return array customized attribute labels (name=>label)
	 */
    public function attributeLabels()
	{
		return array(
            'id' => 'Id',
            'title' => 'Заголовок',
            'content' => 'Статья',
            'preview' => 'Предописание',
            'other_content' => 'Дополнительно по теме',
            'tags' => 'Тэги',
            'status' => 'Статус',
            'create_time' => 'Create Time',
            'update_time' => 'Update Time',
            'author_id' => 'Автор',
            'syntax' => 'Подсветка синтаксиса',
            'category_id' => 'Категория',
            'image' => 'Изображение',
		);
	}

	/**
     * @return string the URL that shows the detail of the post
	 */
    public function getUrl()
	{
        return Yii::app()->createUrl('post/view', array(
            'id' => $this->id,
            'title' => $this->alias,
        ));
	}

	/**
     * @return array a list of links that point to the post list filtered by every tag of this post
	 */
    public function getTagLinks()
	{
        $links = array();
        foreach (Tag::string2array($this->tags) as $tag)
            $links[] = CHtml::link(CHtml::encode($tag), array('post/index', 'tag' => $tag));
        return $links;
	}

	/**
     * Normalizes the user-entered tags.
	 */
    public function normalizeTags($attribute, $params)
	{
        $this->tags = Tag::array2string(array_unique(Tag::string2array($this->tags)));
	}

	/**
     * Adds a new comment to this post.
     * This method will set status and post_id of the comment accordingly.
     * @param Comment the comment to be added
     * @return boolean whether the comment is saved successfully
	 */
    public function addComment($comment)
	{
        if (Yii::app()->params['commentNeedApproval'])
            $comment->status = Comment::STATUS_PENDING;
        else
            $comment->status = Comment::STATUS_APPROVED;
        $comment->post_id = $this->id;
        return $comment->save();
	}

	/**
     * Retrieves the list of posts based on the current search/filter conditions.
     * @return CActiveDataProvider the data provider that can return the needed posts.
	 */
    public function search()
	{
        $criteria = new CDbCriteria;

        $criteria->compare('title', $this->title, true);

        $criteria->compare('status', $this->status);

        return new CActiveDataProvider('Post', array(
            'criteria' => $criteria,
            'sort' => array(
                'defaultOrder' => 'status, update_time DESC',
            ),
        ));
	}

    /**
     *
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

    /**
     * @return integer the number of post that are approval
     */
    public function getPendingPostCount($id)
    {
        $criteria = new CDbCriteria;
        $criteria->select = 'id';
        $criteria->condition = 'category_id=:CATID AND status=:STAT';
        $criteria->params = array(':CATID' => $id, ':STAT' => self::STATUS_APPROVED);

        return Post::model()->count($criteria);
    }

    /**
     * Returns the static model of the specified AR class.
     * @return CActiveRecord the static model class
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
	}

	/**
     * This is invoked when a record is populated with data from a find() call.
	 */
    protected function afterFind()
	{
        parent::afterFind();
        $this->_oldTags = $this->tags;
	}

	/**
     * This is invoked before the record is saved.
     * @return boolean whether the record should be saved.
	 */
    protected function beforeSave()
    {
        if (parent::beforeSave()) {
            // Добавляем превью по умолчанию.
            // Обрезаем основной текст `content` для `preview`
            if ((strlen($this->content) > $param = Yii::app()->params['shortText']) && ($this->preview == '')) {
                Yii::import('application.apis.CutString');
                $shortText = new CutString($this->content, $param, '...', 'code', 'pre');
                $this->preview = $shortText->getShortText();
            }

            if ($this->isNewRecord) {
                $this->create_time = $this->update_time = time();
                $this->author_id = Yii::app()->user->id;
            } else
                $this->update_time = time();
            /*
             * Подсветка синтаксиса
             */
            if ($this->syntax != Post::SYNTAX_NONE) // Включена ли подсветка
            {
                Yii::import('application.apis.ReplaceHighlight');
                $replace = new ReplaceHighlight();
                $replace->_setContent($this->content); // Задаем контент для подсветки

                // NOTE: переводим из числовых значений констант в текстовые значения
                // типа подсветки синтаксиса
                if ($this->syntax == Post::SYNTAX_TAG)
                    $type = 'ByTag';
                else
                    $type = 'PlainText';

                $replace->_setSyntaxHighlightType($type);

                $result_highlight = $replace->_getReplace(); // Выполняем подсветку

                // Проверяем, была ли выполнена подсветка
                if ($result_highlight != NULL || $result_highlight != '') {
                    $this->content_highlight = $result_highlight;
                    // NOTICE: сжимаем оригинальный текст, т.к. использоваться будет поле content_highlight
                    // и отправляем архив в специальное поле
                    $this->content_gzip = self::stringCompress($this->content);
                    $this->content = NULL;
                }
            } else {
                // NOTE: если подсветка синтаксиса не нужна
                // обнуляем поле с подсветкой кода, для вывода простой статьи
                // Распаковываем данные поля content и обнуляем поле `content_gzip`
                if ($this->content_gzip != '') {
                    $this->content = self::stringUnCompress($this->content_gzip);
                    $this->content_gzip = NULL;
                }
                $this->content_highlight = NULL;
            }
            return true;
        } else
            return false;
	}

    /**
     * Сжимает строку, степень сжатия максимальная, т.к.
     * функция выполняется только при редактировании материала
     * @param string $string получает строку
     * @return string возвращает сжатые данные
     */
    public function stringCompress($string){
        return gzdeflate($string,9);
    }

    /**
     * Выполняет распаковку сжатой строки
     * @param string $string получает сжатые данные
     * @return string возвращает распакованную строку
     */
    public function stringUnCompress($string){
            return @gzinflate($string);
    }

    /**
     * This is invoked after the record is saved.
     */
    protected function afterSave()
    {
        parent::afterSave();
        Tag::model()->updateFrequency($this->_oldTags, $this->tags);
    }

    /**
     * This is invoked after the record is deleted.
     */
    protected function afterDelete()
    {
        parent::afterDelete();
        Comment::model()->deleteAll('post_id=' . $this->id);
        Tag::model()->updateFrequency($this->tags, '');
    }
}