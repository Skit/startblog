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
     * @var integer $_characters количество символов для обрезки
     * @var bool $_dot добавлять ли многоточие вконце
     * @var string $_string строка для обработки
     */
    private $_characters;
    private $_dot;
    private $_string;

    /**
     * Инициализация объекта и его свойств
     */
    public function __construct($string, $characters, $dot = false){

        $this->_characters=$characters;
        $this->_string = ltrim(strip_tags($string));
        $this->_dot=$dot;
    }

    /**
     *
     */
    public function getShortText(){
        return self::_cut();
    }

    /**
     * Метод обрезки статьи
     * @return string возвращает обрезанную строку в соответствии с параметрами
     */
    private function _cut(){

        $this->_string = substr($this->_string, 0, $this->_characters);

        $this->_string = substr($this->_string, 0, strrpos($this->_string,' '));

        if($this->_dot)
            $this->_string = $this->_string.$this->_dot;

        return $this->_string;
    }

} 