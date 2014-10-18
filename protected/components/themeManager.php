<?php
/**
 * Created by PhpStorm.
 * User: Bhakta
 * Date: 18.10.2014
 * Time: 20:32
 */

class themeManager extends CThemeManager{

    public function getTheme($theme) {


        if(!Yii::app()->user->isGuest) {

                $theme='Classic';
        }

        return parent::getTheme($theme);
    }

} 