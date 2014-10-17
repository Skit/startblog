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
     * @var string путь сохранения файла
     */
    private $_saveFilePath;
    /**
     * @var string серверное имя файла
     */
    private $_newFileName;
    /**
     * @var string путь новой вложенной директории для сохранения файла
     */
    private $_newDir;

    /**
     * Шорткат для Yii::getPathOfAlias($this->savePathAlias).DIRECTORY_SEPARATOR.
     * Возвращает путь к директории, в которой будут сохраняться файлы.
     * @return string путь к директории, в которой сохраняем файлы
     */
    public function getSavePath(){
        return $this->_saveFilePath=Yii::getPathOfAlias($this->savePathAlias).DS;
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

            // Создаем директорию по имени модели
            if(self::_createDir(get_class($this->owner)))
                $file->saveAs($this->savePath.$this->_newDir.$file->name);
        }
        return true;
    }

    // имейте ввиду, что методы-обработчики событий в поведениях должны иметь
    // public-доступ начиная с 1.1.13RC
    public function beforeDelete($event){
        $this->deleteFile(); // удалили модель? удаляем и файл, связанный с ней
    }

    /**
     * Удаляет файл по атрибуту
     */
    public function deleteFile(){
        $filePath=$this->savePath.$this->_newDir.$this->owner->getAttribute($this->attributeName);
        if(@is_file($filePath))
            @unlink($filePath);
    }

    /**
     * После сохранения статьи и файла, переименуем файл и обновим название файла в поле БД
     * @param object $event
     */
    public function afterSave($event){

        self::_renameFile();

        $this->owner->updateByPk(Yii::app()->db->lastInsertID, array('image'=>$this->_newDir.$this->_newFileName));
    }

    /**
     * Создаем имя файла для загрузки, на основе расширения
     * включая ID добавленой записи дату и прочие параметры
     * @param string $file имя файла
     * @return string возвращаем имя файла
     */
    private function _createFileName($file){

        $pattern = '/\.[a-z]{2,}$/i';

        preg_match($pattern,$file,$match); // извлекаем расширение файла с точкой

        $extWithDot = strtolower($match[0]); // для эстетики все расширения будут в нижнем регистре

        $postId = Yii::app()->db->lastInsertID;

        $currentDate = date('dmy');

        $templateImg = 'img';

        $newFileName = $postId.'_'.$currentDate.'_'.$templateImg.$extWithDot;

        return self::_recursiveDuplicateFileName($newFileName,$postId,$currentDate,$templateImg,$extWithDot);
    }

    /**
     * Функция добавляет номер дубликата при повторении имени файла
     * file.ext -> file_1.ext -> file_2.ext
     * @param string $newFileName имя файла для переименования
     * @param integer $postId ID записи сохраненной записи для добавления к имени файла
     * @param integer $currentDate текущая дата NOTICE: необходимо получать ее из модели
     * @param string $ext расширение файла с точкой (.ext)
     * @param string $templateImg шаблон преобразования изображения
     * @return string имя файла
     */
    private function _recursiveDuplicateFileName($newFileName,$postId,$currentDate,$templateImg,$ext){

        if(file_exists($newFileName))
        {
            $pattern = '/[0-9]+_[0-9]{6,}_([0-9]+)\.[a-z]+$/'; // ищем номер дубликата

            $duplicateNum = '1'; // присваивается первому дубликату

            $constantFileName = $postId.'_'.$currentDate.'_'.$templateImg.'_';

            preg_match($pattern,$this->owner->attributes['image'],$match);

            if(!empty($match))
            {
                $duplicateNum = $match[1]+1;
                $newFileName = $constantFileName.$duplicateNum.$ext;
            }
            else
                $newFileName = $constantFileName.$duplicateNum.$ext;

            self::_recursiveDuplicateFileName($newFileName,$postId,$currentDate,$templateImg,$ext);
        }
        else
            return $this->_newFileName = $newFileName;
    }

    /**
     * Выполняет переименование файла
     * @throws CException при ошибке создания или перемещения файла
     */
    private function _renameFile(){

        $file = $this->savePath.$this->_newDir.$this->owner->attributes['image'];

        if(file_exists($file))
        {
            if(!rename($file,
                $this->savePath.$this->_newDir.self::_createFileName($this->owner->attributes['image'])))
                throw new CException('Перемещение файла вызвало ошибку! Проверьте конечный путь');
        }
        else
            throw new CException('Переименование файла не удалось. Исходный файл не найден!');
    }

    /**
     * Создает директорию для сохранения файла, от текущей
     * установки $this->savePath в нижнем регистре
     * @var string $dirName принимает имя папки для создания
     * @return string возвращает имя директории с разделителем
     * @throws CException если каталог создать не удалось
     */
    private function _createDir($dirName){

        $this->_newDir = strtolower($dirName).DS;
        $path = $this->savePath.$dirName;

        if(!is_dir($path)){
            if(mkdir($path))
                return $this->_newDir;
            else
                throw new CException('Не удалось создать директорию');
        }
        else
            return $this->_newDir;
    }
}