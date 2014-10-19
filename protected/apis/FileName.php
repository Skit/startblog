<?php
/**
 * Created by PhpStorm.
 * User: Bhakta
 * Date: 19.10.2014
 * Time: 10:43
 */

class FileName extends CComponent{

    /**
     * @var string путь вложенной директории для сохранения файла
     */
    private $_dir;
    /**
     * @var string путь до директории где сохраняются файлы
     */
    private $_path;
    /**
     *@var string путь к временному файлу
     */
    private $_tmp;

    /**
     * @param $path array() массив содержащий данные для построения пути
     * @param $tmp string путь к файлу
     */
    public function __construct($path, $tmp){

        // Инициализируем парамерты
        foreach($path as $k=>$v)
            $this->{'_'.$k} = $v;

        $this->_tmp = $tmp;
    }

    /**
     * Создаем имя файла для загрузки, на основе расширения
     * включая ID добавленой записи дату и прочие параметры
     * @param array() $template шаблон для создания имени файла
     * @return string возвращаем имя файла
     */
    public function createFileName($template){

        $file = $this->_tmp;

        $pattern = '/.+(\.[a-z]{2,5})$/i';

        preg_match($pattern,$file,$match); // извлекаем расширение файла с точкой

        $extWithDot = strtolower($match[1]); // для эстетики все расширения будут в нижнем регистре

        $newFileName = $template['id'].'_'.$template['date'].'_'.$template['template'].$extWithDot;

        return self::_recursiveDuplicateFileName($newFileName, $template, $extWithDot);
    }

    /**
     * Функция добавляет номер дубликата при повторении имени файла
     * file.ext -> file_1.ext -> file_2.ext
     * @param string $newFileName имя файла для переименования
     * @param string $extWithDot расширение файла с точкой (.ext)
     * @param array() $template шаблон для создания имени файла
     * @return string имя файла
     */
    private function _recursiveDuplicateFileName($newFileName, $template, $extWithDot){

        if(file_exists($search=self::_createDir($this->_dir).$newFileName))
        {
            $pattern = '/.+[0-9]+_[0-9]{6,}_[a-z]+_([0-9]+)\.[a-z]{2,5}$/i';

            $duplicateNum = '1';

            $constantFileName = $template['id'].'_'.$template['date'].'_'.$template['template'].'_';

            preg_match($pattern,$search,$match);

            if(!empty($match))
            {
                $duplicateNum = $match[1]+1;
                $newFileName = $constantFileName.$duplicateNum.$extWithDot;
            }
            else
                $newFileName = $constantFileName.$duplicateNum.$extWithDot;

            $newFileName = self::_recursiveDuplicateFileName($newFileName, $template, $extWithDot);

            return $newFileName;
        }
        else
            return $newFileName;
    }

    /**
     * Выполняет переименование файла
     * @throws CException при ошибке создания или перемещения файла
     */
    public function renameFile($renameFile){

        if(file_exists($this->_tmp))
        {
            if(!copy($this->_tmp,
                $this->_path.$this->_dir.DS.$renameFile))
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

        $path = $this->_path.$dirName;

        if(!is_dir($path)){
            if(mkdir($path))
                return $path;
            else
                throw new CException('Не удалось создать директорию');
        }
        else
            return $path.DS;
    }
} 