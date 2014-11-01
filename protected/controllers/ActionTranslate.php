<?php

/**
 * Created by PhpStorm.
 * User: Bhakta
 * Date: 01.11.2014
 * Time: 18:35
 */
class ActionTranslate extends CAction
{

    /**
     * @throws CException
     * Initialize the extension
     * check to see if CURL is enabled and the format used is a valid one
     */
    public function run()
    {
        if (!function_exists('curl_init')) {
            throw new CException(Yii::t('Curl', 'You must have CURL enabled in order to use this extension.'));
        }

        if (Yii::app()->request->isAjaxRequest) {
            Yii::import('application.apis.GoogleTranslate');
            $post = isset($_POST['str']) ? $_POST['str'] : false;
            echo GoogleTranslate::get($post);
        } else return false;
    }

} 