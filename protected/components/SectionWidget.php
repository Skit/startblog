<?php
Yii::import('zii.widgets.CPortlet');

/**
 * Class SectionWidget отображает список категорий/разделов
 */
class SectionWidget extends CPortlet
{

    public $title = 'Разделы';

    public function getSection()
    {

        return Category::model()->findAll();
    }

    protected function renderContent()
    {
        $this->render('SectionWidget');
    }
} 