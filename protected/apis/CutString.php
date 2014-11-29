<?php
/**
 * Created by PhpStorm.
 * User: Bhakta
 * Date: 15.10.2014
 * Time: 13:19
 */

class CutString {

    public $locale = 'UTF-8';
    /**
     * Настройки компонента
     * @var integer $_characters количество символов для обрезки
     * @var bool $_dot добавлять ли многоточие вконце
     * @var string $_string строка для обработки
     */
    private $_characters;
    private $_dot;
    private $_string;
    private $_allowTag;
    private $_replaseTag;

    /**
     * Инициализация объекта и его свойств
     */
    public function __construct($string, $characters, $dot = false, $allowTag = NULL, $replaseTag = false)
    {

        $this->_characters=$characters;
        $this->_dot=$dot;
        $this->_allowTag = $allowTag;
        $this->_replaseTag = $replaseTag;

        if ($allowTag != NULL)
            $this->_string = $string;
        else
            $this->_string = ltrim(strip_tags($string));
    }

    /**
     * @return string возвращает обрезанную строку
     */
    public function getShortText(){
        return self::_cut();
    }

    /**
     * Метод обрезки статьи
     * @return string возвращает обрезанную строку в соответствии с параметрами
     */
    private function _cut(){

        if ($this->_allowTag != NULL) {
            $openTagPattern = "<([a-z]{3,})(?: (?:[a-z]{2,5}=(?:\"|')(?:lang:)? ?[a-z]{2,}(?:\"|')) ?)?>";
            $closeTagPattern = "</\\2 ?>";

            $lenTagTextPattern = "~({$openTagPattern})(?:.*)({$closeTagPattern})~siuU";

            // Достаем из текста теги, текст между тегами
            $result = preg_match_all($lenTagTextPattern, $this->_string, $matches);
            $closeTagFalse = false;

            if ($result != 0) {
                $i = 0;
                $entryPos = 0;

                foreach ($matches[0] as $k) {
                    $openTag[$i] = $matches[1][$i];
                    $closeTag[$i] = $matches[3][$i];

                    // Вхождение тега
                    $entryPos = mb_strpos($this->_string, $k, $entryPos, $this->locale);

                    // Вычисляем ближайший тег сзади
                    if ($entryPos <= $this->_characters) {
                        // Первое вхождение тега и текст
                        $textLenWithTags = $entryPos + mb_strlen($k, $this->locale);

                        // Приходится ли обрезка на текст между тегами
                        if (($entryPos < $this->_characters) && ($textLenWithTags > $this->_characters)) {
                            $lenOpenTag = mb_strlen($openTag[$i], $this->locale);
                            $textToTag = $entryPos + $lenOpenTag;

                            // Нужно ли закрывать тег
                            if ($textToTag <= $this->_characters)
                                $closeTagFalse = true;

                            // Обрежем текст, до вхождения тега, если
                            // обрезка приходиться на тело тега
                            if (($this->_characters > $entryPos) && ($this->_characters < $textToTag))
                                $this->_characters = $entryPos;

                            break;
                        }
                    } else // если вхождение первого тега больше обрезки
                        break;

                    $i++;
                }
            }
            $this->_string = self::_substr();

            if ($closeTagFalse == true)
                $this->_string = $this->_string . end($closeTag);
        } else
            $this->_string = self::_substr();

        if (($this->_replaseTag != false) && $result != 0)
            $this->_string = self::_replaceTag($openTag, $closeTag);

        return $this->_string;
    }

    /**
     * Выполняет обрезку строки
     * @return string
     */
    private function _substr()
    {
        $this->_string = mb_substr($this->_string, 0, $this->_characters, $this->locale);

        // Режим по границе слова
        $wordBoundary = mb_strpos($this->_string, ' ', $this->_characters - 15, $this->locale);

        if ($wordBoundary !== false)
            $this->_string = mb_substr($this->_string, 0, $wordBoundary, $this->locale);

        // Проверим, вдруг остался один символ(предлог) в конце строки
        $result = preg_match("~(?:\n|\r|\r\n| )[a-zа-я]{1}$~siuU", $this->_string, $match);

        if ($result != 0)
            $this->_string = mb_substr($this->_string, 0, $wordBoundary - 1);

        $this->_string = rtrim($this->_string);
        //$this->_string = str_replace(array("\r\n", "\r", "\n"), '',$this->_string);

        if ($this->_dot != false)
            $this->_string = rtrim($this->_string, ',') . $this->_dot;

        return self::_cleanTag(
            self::_closeTag($this->_string));
    }

    private function _cleanTag($str)
    {
        $cleanTags = ['<h1>', '</h1>', '<h2>', '</h2>', '<h3>', '</h3>', '<h4>', '</h4>', '<h5>', '<h5>'];
        return str_replace($cleanTags, '', $str);
    }

    /**
     * Закрывает открытые теги <p>
     * @param $str
     * @return string
     */
    private function _closeTag($str)
    {
        $tagToClose = ['p', 'div', 'span'];

        $result = NULL;

        foreach ($tagToClose as $tag) {
            $resultMatches = preg_match_all("~</?{$tag}>~", $str, $matches);

            if ($resultMatches != 0) {
                $compare = array_count_values($matches[0]);

                if (isset($compare["</{$tag}>"])) {
                    // Разница между закрывающими и открывающими тегами
                    $compare = $compare["<{$tag}>"] - $compare["</{$tag}>"];
                } else
                    // Если закрывающих тегов нет
                    // Закрываем, по количеству открытых
                    $compare = count($compare["<{$tag}>"]);

                $result = $str . str_repeat("</{$tag}>", $compare);
            }
        }
        return $result == NULL ? $str : $result;
    }

    /**
     * Выполняет замену тегов подсветки
     * @param $openTag
     * @param $closeTag
     * @return mixed|string
     */
    private function _replaceTag($openTag, $closeTag)
    {
        for ($c = 0; $c < count($openTag); $c++) {

            // Разрешим тег цитат
            if ($openTag != '<blockquote>')
                $result = preg_replace("~{$openTag[$c]}(.*?){$closeTag[$c]}~si",
                    "<{$this->_replaseTag}>\\1</{$this->_replaseTag}>", $this->_string);
            else
                $result = $this->_string;
        }

        return $result;
    }
} 