<?php

// this contains the application parameters that can be maintained via GUI
return array(
	// this is displayed in the header section
	'title'=>'My Yii Blog',
	// this is used in error pages
	'adminEmail'=>'webmaster@example.com',
	// number of posts displayed per page
	'postsPerPage'=>20,
	// maximum number of comments that can be displayed in recent comments portlet
	'recentCommentCount'=>10,
	// maximum number of tags that can be displayed in tag cloud portlet
	'tagCloudCount'=>20,
	// whether post comments need to be approved before published
	'commentNeedApproval'=>true,
	// the copyright information displayed in the footer section
	'copyrightInfo'=>'Copyright &copy; 2009 by My Company.',
    // count charters for shortText
	'shortText'=>500,
	'imageTemplates'=>array(
        'post'=> array('article'=>'206*498'),
        'category' => array('edit'=>'100*100','test'=> '300*500','view'=>'206*498','maha'=> '800*600'),
    ),
);
