<?php

class TagCloud extends CWidget
{
	public $title='Tags';
	public $maxTags=20;
    public $tags = array();

    public function run()
	{
        $this->tags = Tag::model()->findTagWeights($this->maxTags);

        $this->render('TagCloud');
    }
}