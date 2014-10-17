<?php

class ReplaceHighlight extends CComponent
{
    /**
     * @var string $_content
     * @var string $_syntaxHighlightType
     */
    private $_content='';
    private $_syntaxHighlightType;

    const TYPE_BYTAG='ByTag';
    const TYPE_PLAINTEXT='PlainText';

    /**
     * @param string текст для обработки
     */
    public function _setContent($value)
    {
        $this->_content=$value;
    }

    /**
     * @param принимает строку типа подсветки
     */
    public function _setSyntaxHighlightType($value)
    {
            $this->_syntaxHighlightType=$value;
    }

    /**
     * Осуществляем подсветку синтаксиса
     * @return обработанный текст с подсветкой синтаксиса
     */
    public function _getReplace()
    {
        return $this->_highlighting();
    }

    /**
     * Выполняет замену тескта между специальными тэгами
     * @return string обработанный текст
     */
    private function _highlighting()
    {
        $search = '|(<code class="lang: ?([a-z]{2,3})">)(.+?)(</code>)|is';
        return preg_replace_callback($search, array($this, '_CTextHighlighter_callback'), $this->_content);
    }

    /**
     * Callback функция. Производит подсветку синтаксиса классом Yii CTextHighlighter()
     * @param array принимает маски совпадения функции _highlighting()
     * @return string возвращает обработанный текст
     */
    private function _CTextHighlighter_callback ($matches)
    {
        $highlighter = new CTextHighlighter();

        // Проверяем тип подсветки
        if($this->_syntaxHighlightType == ReplaceHighlight::TYPE_BYTAG)
        {
            // NOTE: заменяем "js" на  "JavaScript", т.к. CTextHighlighter понимает только JAVASCRIPT
            if ($matches[2] == 'js')
                $highlighter->language = 'JavaScript';
            else
                $highlighter->language = $matches[2];
        }
        else
            $highlighter->language = ReplaceHighlight::TYPE_PLAINTEXT;


        $result = $highlighter->highlight($matches[3]);

        return $result;
    }
}