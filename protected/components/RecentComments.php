<?php

class RecentComments extends CWidget
{
	public $title='Recent Comments';
	public $maxComments=10;

	public function getRecentComments()
	{
		return Comment::model()->findRecentComments($this->maxComments);
	}

    public function run()
	{
		$this->render('recentComments');
	}
}