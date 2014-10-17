<?php
/**
 * Created by PhpStorm.
 * User: Bhakta
 * Date: 15.10.2014
 * Time: 13:19
 */

class CutString {

    /**
     * Настройки компонента
     * @var integer $_caracters количество символов для обрезки
     */
    private $_characters;

    /**
     * Инициализация объекта и его свойств
     */
    public function __construct($characters){

        $this->_characters=$characters;
    }

    /**
     * Метод обрезки статьи
     * @param string $string текст для обрезки
     * @param boolean $dot устанавливает многоточие после обрезанного текста
     */
    private function _cut($string, $dot = false){

    }

} 