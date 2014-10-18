<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
    <!--[if IE 7 ]>    <html class="ie7 oldie"> <![endif]-->
    <!--[if IE 8 ]>    <html class="ie8 oldie"> <![endif]-->
    <!--[if IE 9 ]>    <html class="ie9"> <![endif]-->
    <!--[if (gt IE 9)|!(IE)]><!--> <html> <!--<![endif]-->
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="language" content="Ru_ru" />
    <meta charset="utf-8"/>
    <meta name="description" content="">
    <meta name="author" content="">

    <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->theme->baseUrl; ?>/css/coolblue.css" media="screen" />

    <!--[if lt IE 9]>
    <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->

    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.6/jquery.min.js"></script>
    <script>window.jQuery || document.write('<script src="js/jquery-1.6.1.min.js"><\/script>')</script>

    <script src="<?php echo Yii::app()->theme->baseUrl; ?>/js/scrollToTop.js"></script>

    <title><?php echo CHtml::encode($this->pageTitle); ?></title>
</head>
<body id="top">

<!--header -->
<div id="header-wrap">
    <header>

        <hgroup>
            <h1><a href="<?php echo Yii::app()->baseUrl; ?>">Coolblue</a></h1>
            <h3><?php echo CHtml::encode(Yii::app()->name); ?></h3>
        </hgroup>

        <nav>
            <?php $this->widget('zii.widgets.CMenu',array(
                'items'=>array(
                    array('label'=>'Home', 'url'=>array('post/index')),
                    array('label'=>'About', 'url'=>array('site/page', 'view'=>'about')),
                    array('label'=>'Contact', 'url'=>array('site/contact')),
                    array('label'=>'Login', 'url'=>array('site/login'), 'visible'=>Yii::app()->user->isGuest),
                    array('label'=>'Logout ('.Yii::app()->user->name.')', 'url'=>array('site/logout'), 'visible'=>!Yii::app()->user->isGuest)
                ),
            )); ?>
        </nav>

        <div class="subscribe">
            <span>Subscribe:</span> <a href="#">Email</a> | <a href="#">RSS</a>
        </div>

        <form id="quick-search" method="get" action="index.html">
            <fieldset class="search">
                <label for="qsearch">Search:</label>
                <input class="tbox" id="qsearch" type="text" name="qsearch" value="Search..." title="Start typing and hit ENTER" />
                <button class="btn" title="Submit Search">Search</button>
            </fieldset>
        </form>
        <!--/header-->
    </header></div>

<!-- content-wrap -->
<div id="content-wrap">

<!-- content -->
<div id="content" class="clearfix">

    <?php $this->widget('zii.widgets.CBreadcrumbs', array(
        'links'=>$this->breadcrumbs,
    )); ?><!-- breadcrumbs -->

    <?php echo $content; ?>

<!-- sidebar -->
<div id="sidebar">

    <div class="about-me">

        <h3>About Me</h3>

        <p>
            <a href="index.html"><img src="images/gravatar.jpg" width="42" height="42" alt="firefox" class="align-left" /></a>
            Lorem ipsum dolor sit, consectetuer adipiscing. Donec libero. Suspendisse bibendum.
            Cras id urna. Morbi tincidunt, orci ac convallis aliquam, lectus turpis varius lorem, eu
            posuere nunc justo tempus leo suspendisse bibendum. <a href="index.html">Learn more...</a>
        </p>

    </div>

    <div class="sidemenu">

        <h3>Sidebar Menu</h3>
        <ul>
            <li><a href="index.html">Home</a></li>
            <li><a href="index.html#TemplateInfo">TemplateInfo</a></li>
            <li><a href="style.html">Style Demo</a></li>
            <li><a href="blog.html">Blog</a></li>
            <li><a href="archives.html">Archives</a></li>
            <li><a href="http://themeforest.net?ref=ealigam" title="Web Templates">Web Templates</a></li>
        </ul>

    </div>

    <div class="sidemenu">

        <h3>Sponsors</h3>

        <ul>
            <li><a href="http://themeforest.net?ref=ealigam" title="Site Templates">Themeforest
                    <span>Site Templates, Web &amp; CMS Themes.</span></a>
            </li>
            <li><a href="http://www.4templates.com/?go=228858961" title="Website Templates">4Templates
                    <span>Low Cost High-Quality Templates.</span></a>
            </li>
            <li><a href="http://store.templatemonster.com?aff=ealigam" title="Web Templates">Templatemonster
                    <span>Delivering the Best Templates on the Net!</span></a>
            </li>
            <li><a href="http://graphicriver.net?ref=ealigam" title="Stock Graphics">Graphic River
                    <span>Awesome Stock Graphics.</span></a>
            </li>
            <li><a href="http://www.dreamhost.com/r.cgi?287326|sshout" title="Webhosting">Dreamhost
                    <span>Premium Webhosting. Use the promocode <strong>sshout</strong> and save <strong>50 USD</strong>.</span></a>
            </li>
        </ul>

    </div>

    <div class="sidemenu popular">

        <h3>Most Popular</h3>
        <ul>
            <li><a href="index.html">Lorem ipsum dolor sit amet, consectetuer adipiscing elit.
                    <span>Posted on December 22, 2010</span></a>
            </li>
            <li><a href="index.html">Cras fringilla magna. Phasellus suscipit.
                    <span>Posted on December 20, 2010</span></a>
            </li>
            <li><a href="index.html">Morbi tincidunt, orci ac convallis aliquam.
                    <span>Posted on December 15, 2010</span></a>
            </li>
            <li><a href="index.html">Ipsum dolor sit amet, consectetuer adipiscing elit.
                    <span>Posted on December 14, 2010</span></a>
            </li>
            <li><a href="index.html">Morbi tincidunt, orci ac convallis aliquam, lectus turpis varius lorem
                    <span>Posted on December 12, 2010</span></a>
            </li>
        </ul>

    </div>

    <!-- /sidebar -->
</div>

<!-- content -->
</div>

<!-- /content-out -->
</div>

<!-- extra -->
<div id="extra-wrap"><div id="extra" class="clearfix">

        <div id="gallery" class="clearfix">

            <h3>Flickr Photos </h3>

            <p class="thumbs">
                <a href="index.html"><img src="images/thumb.png" width="42" height="43" alt="thumbnail" /></a>
                <a href="index.html"><img src="images/thumb.png" width="42" height="43" alt="thumbnail" /></a>
                <a href="index.html"><img src="images/thumb.png" width="42" height="43" alt="thumbnail" /></a>
                <a href="index.html"><img src="images/thumb.png" width="42" height="43" alt="thumbnail" /></a>
                <a href="index.html"><img src="images/thumb.png" width="42" height="43" alt="thumbnail" /></a>
                <a href="index.html"><img src="images/thumb.png" width="42" height="43" alt="thumbnail" /></a>
                <a href="index.html"><img src="images/thumb.png" width="42" height="43" alt="thumbnail" /></a>
                <a href="index.html"><img src="images/thumb.png" width="42" height="43" alt="thumbnail" /></a>
                <a href="index.html"><img src="images/thumb.png" width="42" height="43" alt="thumbnail" /></a>
                <a href="index.html"><img src="images/thumb.png" width="42" height="43" alt="thumbnail" /></a>
            </p>

        </div>

        <div class="col first">

            <h3>Contact Info</h3>

            <p>
                <strong>Phone: </strong>+1234567<br/>
                <strong>Fax: </strong>+123456789
            </p>

            <p><strong>Address: </strong>123 Put Your Address Here</p>
            <p><strong>E-mail: </strong>me@coolblue.com</p>
            <p>Want more info - go to our <a href="#">contact page</a></p>

            <h3>Updates</h3>

            <ul class="subscribe-stuff">
                <li><a title="RSS" href="index.html" rel="nofollow">
                        <img alt="RSS" title="RSS" src="images/social_rss.png" /></a>
                </li>
                <li><a title="Facebook" href="index.html" rel="nofollow">
                        <img alt="Facebook" title="Facebook" src="images/social_facebook.png" /></a>
                </li>
                <li><a title="Twitter" href="index.html" rel="nofollow">
                        <img alt="Twitter" title="Twitter" src="images/social_twitter.png" /></a>
                </li>
                <li><a title="E-mail this story to a friend!" href="index.html" rel="nofollow">
                        <img alt="E-mail this story to a friend!" title="E-mail this story to a friend!" src="images/social_email.png" /></a>
                </li>
            </ul>

            <p>Stay up to date. Subscribe via
                <a href="index">RSS</a>, <a href="index">Facebook</a>,
                <a href="index">Twitter</a> or <a href="index">Email</a>
            </p>

        </div>

        <div class="col">

            <h3>Site Links</h3>

            <div class="footer-list">
                <ul>
                    <li><a href="index.html">Home</a></li>
                    <li><a href="index.html">Style Demo</a></li>
                    <li><a href="index.html">Blog</a></li>
                    <li><a href="index.html">Archive</a></li>
                    <li><a href="index.html">About</a></li>
                    <li><a href="index.html">Template Info</a></li>
                    <li><a href="index.html">Site Map</a></li>
                </ul>
            </div>

            <h3>Friends</h3>

            <div class="footer-list">
                <ul>
                    <li><a href="index.html">consequat molestie</a></li>
                    <li><a href="index.html">sem justo</a></li>
                    <li><a href="index.html">semper</a></li>
                    <li><a href="index.html">magna sed purus</a></li>
                    <li><a href="index.html">tincidunt</a></li>
                    <li><a href="index.html">consequat molestie</a></li>
                    <li><a href="index.html">magna sed purus</a></li>
                </ul>
            </div>

        </div>

        <div class="col">

            <h3>Credits</h3>

            <div class="footer-list">
                <ul>
                    <li><a href="http://jasonlarose.com/blog/110-free-classy-social-media-icons">
                            110 Free Classy Social Media Icons by Jason LaRose
                        </a>
                    </li>
                    <li><a href="http://wefunction.com/2009/05/free-social-icons-app-icons/">
                            Free Social Media Icons by WeFunction
                        </a>
                    </li>
                    <li><a href="http://iconsweets2.com/">
                            Free Icons by Yummygum
                        </a>
                    </li>
                </ul>
            </div>

            <h3>Recent Comments</h3>

            <div class="recent-comments">
                <ul>
                    <li><a href="index.html" title="Comment on title">Whoa! This one is really cool...</a><br /> &#45; <cite>Erwin</cite></li>
                    <li><a href="index.html" title="Comment on title">Wow. This theme is really awesome...</a><br /> &#45; <cite>John Doe</cite></li>
                    <li><a href="index.html" title="Comment on title">Type your comment here...</a><br />&#45; <cite>Naruto</cite></li>
                    <li><a href="index.html" title="Comment on title">And don't forget this theme is free...</a><br /> &#45; <cite>Shikamaru</cite></li>
                    <li><a href="index.html" title="Comment on title">Just a simple reply test. Thanks...</a><br /> &#45; <cite>ABCD</cite></li>
                </ul>
            </div>

        </div>

        <div class="col">

            <h3>Archives</h3>

            <div class="footer-list">
                <ul>
                    <li><a href="index.html">January 2010</a></li>
                    <li><a href="index.html">December 2009</a></li>
                    <li><a href="index.html">November 2009</a></li>
                    <li><a href="index.html">October 2009</a></li>
                    <li><a href="index.html">September 2009</a></li>
                </ul>
            </div>

            <h3>Recent Bookmarks</h3>

            <div class="footer-list">
                <ul>
                    <li><a href="index.html">5 Must Have Sans Serif Fonts for Web Designers</a></li>
                    <li><a href="index.html">The Basics of CSS3</a></li>
                    <li><a href="index.html">10 Simple Tips for Launching a Website</a></li>
                    <li><a href="index.html">24 ways: Working With RGBA Colour</a></li>
                    <li><a href="index.html">30 Blog Designs with Killer Typography</a></li>
                    <li><a href="index.html">The Principles of Great Design</a></li>
                </ul>
            </div>

        </div>

        <!-- /extra -->
    </div></div>

<!-- footer -->
<footer>

    <p class="footer-left">
        Copyright &copy; <?php echo date('Y'); ?> by My Company.<br/>
        All Rights Reserved.<br/>
        <?php echo Yii::powered(); ?> Design by <a href="http://www.styleshout.com/">styleshout</a>
    </p>

    <p class="footer-right">
        <a href="index.html">Home</a> |
        <a href="index.html">Sitemap</a> |
        <a href="index.html">RSS Feed</a> |
        <a href="#top" class="back-to-top">Back to Top</a>
    </p>

    <!-- /footer -->
</footer>

</body>
</html>
