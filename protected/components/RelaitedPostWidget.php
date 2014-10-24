<?php
/**
 * Created by PhpStorm.
 * User: Bhakta
 * Date: 24.10.2014
 * Time: 23:21
 */

/**
 * Class RelaitedPostWidget отображаем материалы
 * связанные с текущей категорией/разделом
 */
class RelaitedPostWidget extends CWidget{

    public $posts=array();

    public function run()
    {
        $this->render('RelaitedPostWidget', array('posts'=>$this->posts));
    }
} 