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

        // Проверяем наличие русских символов, как необходимость перевода текста
        if (preg_match("~[а-яё]+~ui", $str)) {
            $str = str_replace(' ', '%20', $str);
            //$url = "http://mymemory.translated.net/api/get?q={$str}&langpair={$from}|{$to}";
            $yandexKey = 'trnsl.1.1.20141127T171151Z.69f1ca25ee747016.63416f07eaa70035f0cc29330a7061a9be78f0a8';
            $url = "https://translate.yandex.net/api/v1.5/tr.json/translate?key={$yandexKey}&text={$str}&lang={$from}-{$to}";
            $useragent = 'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.1) Gecko/20061204 Firefox/2.0.0.1';
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_USERAGENT, $useragent);
            curl_setopt($ch, CURLOPT_HEADER, 0);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 1);
            $html = curl_exec($ch);
            curl_close($ch);
            $json = $html;

            if ($json == null)
                throw new CHttpException(400, 'Не верный запрос!');

            $data = json_decode($json);

            if ($data != NULL) {
                if ($data->code == '200')
                    $result = $data->text[0];
                //$result = $data->responseData->translatedText;
                else
                    throw new CHttpException($data->code, 'Ответ Яндекс API!');
            } else
                $result = mb_strtolower($str, 'utf-8');
        } else
            $result = strtolower($str);

        return $result;
    }
}