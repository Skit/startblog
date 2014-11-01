<?php

/**
 * Created by PhpStorm.
 * User: Bhakta
 * Date: 01.11.2014
 * Time: 18:05
 */
class GoogleTranslate
{

    /**
     * @param $str
     * @param string $from
     * @param string $to
     * @return mixed
     * @throws CHttpException
     */
    public static function get($str, $from = 'ru', $to = 'en')
    {
        $exclude = array('/', '\\', '.', '`', '~', '!', '<', '>', ':', ';', '|', "'",
            '"', '@', '#', '$', '%', '&', '?', ',', '-', '_', '=', '+', '(', ')', '*', '{', '}', '[', ']');
        $str = str_replace($exclude, '', $str);
        $str = str_replace(' ', '%20', $str);
        $url = "http://mymemory.translated.net/api/get?q={$str}&langpair={$from}|{$to}";
        $json = self::getUrl($url);

        if ($json == null)
            throw new CHttpException(400, 'Не верный запрос!');

        $data = json_decode($json);

        $result = str_replace(' ', '+', $data->responseData->translatedText);
        return $result;
    }

    /**
     * @param $url
     * @return mixed
     */
    public static function getUrl($url)
    {
        $useragent = 'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.1) Gecko/20061204 Firefox/2.0.0.1';
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_USERAGENT, $useragent);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        $html = curl_exec($ch);
        curl_close($ch);
        return $html;
    }

} 