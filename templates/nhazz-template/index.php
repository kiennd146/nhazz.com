<?php  
/*------------------------------------------------------------------------
# author    TVTMarine.com
# copyright Copyright © 2011 tvtmarine.com . All rights reserved.
# @license  http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
# Website   http://www.TVTMarine.com
-------------------------------------------------------------------------*/

defined( '_JEXEC' ) or die; 

// variables
$app = JFactory::getApplication();
$doc = JFactory::getDocument(); 
$params = &$app->getParams();
$pageclass = $params->get('pageclass_sfx');
$tpath = $this->baseurl.'/templates/'.$this->template;
$logo	= $this->params->get('logo');
$this->setGenerator(null);
// load sheets and scripts
$doc->addStyleSheet($tpath.'/css/template.css.php?v=1.0.0'); 
$doc->addScript($tpath.'/js/modernizr.js'); // <- this script must be in the head

// unset scripts, put them into /js/template.js.php to minify http requests
unset($doc->_scripts[$this->baseurl.'/media/system/js/mootools-core.js']);
unset($doc->_scripts[$this->baseurl.'/media/system/js/core.js']);
unset($doc->_scripts[$this->baseurl.'/media/system/js/caption.js']);

?><!DOCTYPE html>
<!--[if IEMobile]><html class="iemobile" lang="<?php echo $this->language; ?>"> <![endif]-->
<!--[if lt IE 7]> <html class="no-js ie6 oldie" lang="<?php echo $this->language; ?>"> <![endif]-->
<!--[if IE 7]>    <html class="no-js ie7 oldie" lang="<?php echo $this->language; ?>"> <![endif]-->
<!--[if IE 8]>    <html class="no-js ie8 oldie" lang="<?php echo $this->language; ?>"> <![endif]-->
<!--[if gt IE 8]>  <html class="no-js" lang="<?php echo $this->language; ?>"> <![endif]-->
<!--[if not IE]><html slick-uniqueid="3" xmlns:fb="http://www.facebook.com/2008/fbml" class=" js no-flexbox canvas canvastext postmessage no-websqldatabase indexeddb hashchange history draganddrop websockets rgba hsla multiplebgs backgroundsize borderimage borderradius boxshadow textshadow opacity cssanimations csscolumns cssgradients no-cssreflections csstransforms csstransforms3d csstransitions fontface generatedcontent video audio localstorage sessionstorage webworkers applicationcache"  class="no-js" lang="<?php echo $this->language; ?>"><![endif]-->
<head>
  <link REL="SHORTCUT ICON" HREF="<?php echo $tpath; ?>/favicon.ico">
    
  <link rel="stylesheet" href="<?php echo $tpath; ?>/css/jquery-ui-1.9.2.custom.css" />
  
  <script type="text/javascript" src="<?php echo $tpath.'/js/template.js.php'; ?>"></script>
  <jdoc:include type="head" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0" /> <!-- mobile viewport -->

	
  <link rel="stylesheet" media="only screen and (max-width: 768px)" href="<?php echo $tpath; ?>/css/tablet.css" type="text/css" />
  <link rel="stylesheet" media="only screen and (min-width: 240px) and (max-width: 480px)" href="<?php echo $tpath; ?>/css/phone.css" type="text/css" />
  <!--[if IEMobile]><link rel="stylesheet" media="screen" href="<?php echo $tpath; ?>/css/phone.css" type="text/css" /><![endif]--> <!-- iemobile -->
  <link rel="apple-touch-icon-precomposed" href="<?php echo $tpath; ?>/apple-touch-icon-57x57.png"> <!-- iphone, ipod, android -->
  <link rel="apple-touch-icon-precomposed" sizes="72x72" href="<?php echo $tpath; ?>/apple-touch-icon-72x72.png"> <!-- ipad -->
  <link rel="apple-touch-icon-precomposed" sizes="114x114" href="<?php echo $tpath; ?>/apple-touch-icon-114x114.png"> <!-- iphone retina -->
  <link rel="shortcut icon" type="image/x-icon" href="<?php echo $tpath; ?>/favicon.ico">
  <!--[if lte IE 8]>
    <style> 
      {behavior:url(<?php echo $tpath; ?>/js/PIE.htc);}
    </style>
  <![endif]-->
  
  <!--[if IE 7]>
    <style> 
      
    </style>
  <![endif]-->

	<script src="<?php echo $tpath; ?>/js/jquery-1.8.3.js"></script>
	<script>
	jQuery.noConflict();
	</script>
	
	<script src="<?php echo $tpath; ?>/js/jquery-ui-1.9.2.custom.js"></script>
	<script src="<?php echo $tpath; ?>/js/jquery.equalheights.js"></script>
	<script src="<?php echo $tpath; ?>/js/jquery.form.js"></script>
	
</head>
	
<body class="<?php echo $pageclass; ?>">
<?php
require_once( JPATH_ROOT . DS . 'components' . DS . 'com_community' . DS . 'libraries' . DS . 'core.php');
$user =& JFactory::getUser();
?>
<div id="topContainer">
        <?php if ($this->countModules('main-menu')): ?>
        <div id="menuArea">
            <div class="maxWidth" id="main-menu">
                <div class="logoBox leftWidth">
                    <?php
                    $logo = '<img src="'. $tpath . '/css/img/nhazz-logo.png' .'"/>';
                    $link = JHtml::link( JURI::root() , $logo, array("class" => "logo-link") );
                    echo $link;
                    ?>
                </div>
                <div class="noRight floatLeft">
                    <div class="floatLeft menuBox">
                    <jdoc:include type="modules" name="main-menu" style="raw" />
                    </div>
                    <div class="notify-bar">
                        <jdoc:include type="modules" name="notify_position" style="raw" />
                    </div>
                    <div class="login-logout">
                        <jdoc:include type="modules" name="login_position" style="raw" />
                    </div>
                </div>
                
            </div>
        </div>
        <?php endif;?>
        
        <div id="headerArea">
            <div class="maxWidth" id="menu-top">
                <div class="userBox leftWidth">
                    <?php
                    if ($user->get('guest')) {
                        echo '&nbsp;';
                    } else {
                        //$user = CFactory::getUser();
                        //$avatar = $user->getAvatar();
                        //$image = '<img class="userBoxAvatar" src="'. $avatar .'" alt="" border="0"/>';
                        //$username = $user->get('name');
                        //$view = JHtml::link( JRoute::_('index.php?option=com_community&view=profile') , $image . $username, array("class" => "user-link") );
                        //echo $view;
                        echo '&nbsp;';
                    }
                    ?>
                    
                </div>
                <div class="noRight floatLeft">
                    <?php if ($this->countModules('search')): ?>
                    <div class="searchBox">
                        <jdoc:include type="modules" name="search" style="xhtml" />
                    </div>
                    <?php endif;?>
                    
                    <?php if ($this->countModules('small-menu')): ?>
                    <div class="menuBox">
                        <jdoc:include type="modules" name="small-menu" style="raw" />
                    </div>
                    <?php endif;?>
                    
                    <?php if ($this->countModules('small-upload')): ?>
                    <div class="uploadlink">
                        <jdoc:include type="modules" name="small-upload" style="raw" />
                    </div>
                    <?php endif;?>
                </div>
            </div>
            <?php if ($this->countModules('sub-small-menu')): ?>
            <div class="maxWidth" id="submenu-top">
                <jdoc:include type="modules" name="sub-small-menu" style="raw" />
            </div>
            <?php endif;?>
            
        </div>

    </div>
    <div id="mainContainer">
        <?php if ($this->countModules('breadcrumbs')): ?>
        <div id="breadcrumbs">
            <div class="maxWidth">
                <jdoc:include type="modules" name="breadcrumbs" style="raw" />
            </div>
        </div>
        <?php endif;?>
        <div id="mainArea" class="floatLeft">
            <div class="maxWidth">
                <?php if ($this->countModules('top-left') || $this->countModules('middle-left') || $this->countModules('bottom-left')): ?>
                <div class="leftWidth floatLeft" id="leftMain">
                    <?php if ($this->countModules('top-left')): ?>
                    <jdoc:include type="modules" name="top-left" style="xhtml" />
                    <?php endif;?>
                    
                    <?php if ($this->countModules('middle-left')): ?>
                    <jdoc:include type="modules" name="middle-left" style="xhtml" />
                    <?php endif;?>
                    
                    <?php if ($this->countModules('bottom-left')): ?>
                    <jdoc:include type="modules" name="bottom-left" style="xhtml" />
                    <?php endif;?>
                </div>
                <?php endif;?>
                
                <?php 
    		$class = "noRightNoLeft";
    		if (
                        !$this->countModules('top-right') && 
                        !$this->countModules('middle-right') && 
                        !$this->countModules('bottom-right') && 
                        !$this->countModules('top-left') && 
                        !$this->countModules('middle-left') && 
                        !$this->countModules('bottom-left')
                   ):
    		$class = "noRightNoLeft";
    		elseif(
                        !$this->countModules('top-right') && 
                        !$this->countModules('middle-right') && 
                        !$this->countModules('bottom-right')
                        ):
                $class = "noRight";
                else :
                $class = "centerWidth";
                endif;

    		?>
                <div class="<?php echo $class;?> floatLeft" id="rightMain">
                    <?php if ($this->countModules('slider')): ?>
                    <jdoc:include type="modules" name="slider" style="xhtml" />
                    <?php endif;?>
                    
                    <?php if ($this->countModules('position-1')): ?>
                    <jdoc:include type="modules" name="position-1" style="mystyle" />
                    <?php endif;?>
                    
                    <?php if ($this->countModules('position-2')): ?>
                    <jdoc:include type="modules" name="position-2" style="mystyle" />
                    <?php endif;?>
                    
                    <?php if ($this->countModules('position-3')): ?>
                    <jdoc:include type="modules" name="position-3" style="mystyle" />
                    <?php endif;?>
                    
                    <?php if ($this->countModules('position-4')): ?>
                    <jdoc:include type="modules" name="position-4" style="xhtml" />
                    <?php endif;?>

                    <jdoc:include type="message" />
                    
                    <?php if ($this->countModules('position-5')): ?>
                    <div id="centerCol">
                        <jdoc:include type="component" />
                    </div>                    
                    <div id="rightCol">
                        <jdoc:include type="modules" name="position-5" style="xhtml" />
                    </div>
                    <?php else: ?>
                        <jdoc:include type="component" />
                    <?php endif;?>		    
                    
                </div>
                
                <?php if ($this->countModules('top-right') || $this->countModules('middle-right') || $this->countModules('bottom-right')): ?>
                <div class="rightWidth floatLeft" id="leftMain">
                    <?php if ($this->countModules('top-right')): ?>
                    <jdoc:include type="modules" name="top-right" style="xhtml" />
                    <?php endif;?>
                    
                    <?php if ($this->countModules('middle-right')): ?>
                    <jdoc:include type="modules" name="middle-right" style="xhtml" />
                    <?php endif;?>
                    
                    <?php if ($this->countModules('bottom-right')): ?>
                    <jdoc:include type="modules" name="bottom-right" style="xhtml" />
                    <?php endif;?>
                </div>
                <?php endif;?>
                
                
                
                
            </div>
        </div>
    </div>
    <div id="footerContainer">
        <div id="footerArea" class="backgroundWebsite floatLeft">
            <div class="maxWidth">
                <?php if ($this->countModules('footer-menu')): ?>
                    <div class="marginLeft floatLeft" id="leftFooter">
                    <jdoc:include type="modules" name="footer-menu" style="xhtml" />
                    </div>
                <?php endif;?>
                <div class="floatLeft" id="rightFooter">
                    
                    <jdoc:include type="modules" name="footer-link" style="xhtml" />
                    <div id="copyRight">
                    Designed by <a href="http://ma.tvtmarine.com" target="_blank" title="T.V.T Marine Automation">T.V.T Marine Automation</a>.
                    </div>
                </div>
                
            </div>
        </div>
    </div>
    
    <div id="debug">
        <div id="mainDebug" class="floatLeft">
            <div class="maxWidth floatLeft">
                
            </div>
        </div>
    </div>
</body>

</html>

