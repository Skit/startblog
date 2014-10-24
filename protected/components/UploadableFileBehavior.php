<?php
/**
 * @property string $savePath путь к директории, в которой сохраняем файлы
 */
class UploadableFileBehavior extends CActiveRecordBehavior{
    /**
     * @var string название атрибута, хранящего в себе имя файла и файл
     */
    public $attributeName='document';
    /**
     * @var string алиас директории, куда будем сохранять файлы
     */
    public $savePathAlias='webroot.media';
    /**
     * @var array сценарии валидации к которым будут добавлены правила валидации
     * загрузки файлов
     */
    public $scenarios=array('insert','update');
    /**
     * @var string типы файлов, которые можно загружать (нужно для валидации)
     */
    public $fileTypes='doc,docx,xls,xlsx,odt,pdf';
    /**
     * @var string максимальный размер загружаемого файла. По умолчанию 5 Мб (размер в байтах)
     */
    public $maxSize='5242880';
    /**
     *@var string путь к загруженному для дальнейшей обработки файлу
     */
    private $_tmpFileOriginal;

     /**
     * Шорткат для Yii::getPathOfAlias($this->savePathAlias).DIRECTORY_SEPARATOR.
     * @return string путь к директории, в которой сохраняем файлы
     */
    public function getSavePath(){
        return Yii::getPathOfAlias($this->savePathAlias).DS;
    }
    /**
     * @return string путь к диреткории где будет временно сохранятся файл
     */
    public function getTmpPath(){
        return getcwd ().DS.'assets'.DS;
    }
    /**
     * @param CComponent $owner
     */
    public function attach($owner){
        parent::attach($owner);

        if(in_array($owner->scenario,$this->scenarios)){
            // добавляем валидатор файла
            $fileValidator=CValidator::createValidator('file',$owner,$this->attributeName,
                array(
                    'types'=>$this->fileTypes,
                    'allowEmpty'=>true,
                    'maxSize'=>$this->maxSize));
            $owner->validatorList->add($fileValidator);
        }
    }
    /**
     * Создает файл. Файл будет переименован и создана новая директория
     * @param CModelEvent $event
     * @return bool|void
     */
    public function beforeSave($event){

        if(in_array($this->owner->scenario,$this->scenarios) &&
            ($file=CUploadedFile::getInstance($this->owner,$this->attributeName))){

            $this->owner->setAttribute($this->attributeName,$file->name);

            // Определяем путь до загружаемого файла и созраняем его
            $this->_tmpFileOriginal = $this->tmpPath.$file->name;
            $file->saveAs($this->_tmpFileOriginal);
        }
        return true;
    }

    /**
     * После сохранения статьи и файла, переименуем файл и обновим название файла в поле БД
     * @param object $event
     */
    public function afterSave($event){

        // NOTICE: для новой записи задаем её ID и текущее время
        // для создания имени файла
        if($this->owner->isNewRecord == true){
           $RecordId = Yii::app()->db->lastInsertID;
           $RecordDate = date('dmy');
        }
        else{
            // Берем текущее значение ID для созданной записи
            $RecordId = $this->owner->id;

            // Дату берем либо из таблицы, если такой нет - задаем свою
            $RecordDate = (isset($this->owner->create)) ? $this->owner->create : date('dmy');
        }
        // Подгружаем класс для работы с именем файла
        Yii::import('application.apis.FileName');

        // Задаём настройки для работы с классом и его методами.
        // Настройки передаются массивами. Их значения так же
        // будут использованы далее в текущем методе
        $path = array(
            'path' => $this->getSavePath(),
            'dir' => rtrim($this->owner->pathsavebd,DS),
        );
        $templateFile = array(
            'template'=> 'orig',
            'id'=>$RecordId,
            'date'=>$RecordDate,
        );

        $fileName = new FileName($path, $this->_tmpFileOriginal);
        // Создаем имя файла, если такой файл существует
        // метод создаст новое имя с числовым идентификатором new_1.ext
        $new = $fileName->createFileName($templateFile);
        // Выполняем переименование файла. Который будет скопирован в
        // дирекотрию {$path} инициализированную при создании объекта
        $fileName->renameFile($new);

        $newImage=new Images();
        $newImage->source=$path['dir'].DS.$new;
        $newImage->title='содержимое тестовой записи';
        $newImage->templates = $templateFile['template'];
        $newImage->save(false);

        // Получаем перечень шаблонов для создания ресайза
        $templates = Yii::app()->params['imageTemplates'][$path['dir']];
        // Добавляем системный шаблон
        $templates = array_merge($templates, Yii::app()->params['imageTemplates']['system']);

        // Для ID изображений
        $idImageFiles = array();

        // Обходим шаблоны, выполняя ресайз, согласно их значениям
        foreach($templates as $templateName => $templateSize){
            // компонент для работы с изображениями
            $image = new EasyImage($this->_tmpFileOriginal);

            // Преобразуем шаблон из настроек в шаблон компонента
            $image->resize(
                str_replace('*', ',',$templateSize)
            );
            // Задаем имя шаблона, для переименования файла
            $templateFile['template'] = $templateName;

            $new = $fileName->createFileName($templateFile);
            $fileName->renameFile($new);

            // Сохраняем новый файл после ресайза
            $image->save($path['path'] . $this->pathsavebd . $new);

            $imageFile=new Images();
            $imageFile->source=$this->owner->pathsavebd.$new;
            $imageFile->templates = $templateName;
            $imageFile->save(false);
            // Создаем массив ID добавленных изображений
            $idImageFiles[]=$idForCategory=Yii::app()->db->lastInsertID;

            if ($templateName == 'sys')
                $this->owner->updateByPk($RecordId, array('image'=>$idForCategory));
        }
        // Добавляем в массив ID, ID оригинаольного изображения
        array_unshift($idImageFiles,$newImage->id);
        // Записываем данные в связанную таблицу
        foreach($idImageFiles as $id){
            $imageCat = new $this->owner->modelRelations();
            $imageCat->id_images = $id;
            $imageCat->{$this->owner->tableRelations} = $RecordId;
            $imageCat->save(false);
        }
        // Удаляем оригинальный файл
       self::deleteFile($this->_tmpFileOriginal);
    }

     /**
     * Метод не работает, необходимо допилить путь удаления файла
     * в методе deleteFile()
     * @param CEvent $event
     */
    public function beforeDelete($event){
        $this->deleteFile(); // удалили модель? удаляем и файл, связанный с ней
    }

    /**
     * Удаляет файл по атрибуту
     */
    public function deleteFile($filePath = false){
        if(@is_file($filePath))
            @unlink($filePath);
    }

    /**
     * @return string. Path consisting of the class name
     * in lower case and directory separator
     */
    public function getPathSaveBD()
    {
        return strtolower(get_class($this->owner)) . DS;
    }
}