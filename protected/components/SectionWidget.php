<?php
/**
 * Class SectionWidget отображает список категорий/разделов
 */
class SectionWidget extends CWidget
{
    public $title;

    public function getSection()
    {
        return Category::model()->findAll();
    }

    public function run()
    {
        $this->render('SectionWidget');
    }
} 