<?php
/**
 * Created by PhpStorm.
 * User: Bhakta
 * Date: 15.10.2014
 * Time: 10:59
 */

class StringTranslit{

    /**
     *
     */
    private $stringToTranslate;

    public function __construct($stringToTranslate){
        $this->stringToTranslate=$stringToTranslate;
    }
    /**
     *
     */
    public function getResult(){
        return self::_translit($this->stringToTranslate);
    }

    /**
     *
     */
    private function _translit($s){

        $s = mb_strtolower(trim($s),"UTF-8");
        $s = iconv("UTF-8","UTF-8//IGNORE",strtr($s,
            array('а'=>'a','б'=>'b','в'=>'v','г'=>'g','д'=>'d','е'=>'e','ё'=>'e','ж'=>'j',
                'з'=>'z','и'=>'i','й'=>'y','к'=>'k','л'=>'l','м'=>'m','н'=>'n','о'=>'o',
                'п'=>'p','р'=>'r','с'=>'s','т'=>'t','у'=>'u','ф'=>'f','х'=>'h','ц'=>'c',
                'ч'=>'ch','ш'=>'sh','щ'=>'shch','ы'=>'y','э'=>'e','ю'=>'yu','я'=>'ya','ъ'=>'','ь'=>'')));

        $s = str_replace(" ", "_", $s);

        return $s;
    }
} 