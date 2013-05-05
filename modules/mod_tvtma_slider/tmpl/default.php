<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
JHTML::_('behavior.mootools');
$baseurl = JURI::base();
$document = &JFactory::getDocument();
$document->addStyleSheet($baseurl.'modules/mod_tvtma_slider/includes/css/style.css');
$document->addScript( $baseurl.'modules/mod_tvtma_slider/includes/jquery.bpopup-0.7.0.min.js' );
$document->addScript( $baseurl.'modules/mod_tvtma_slider/includes/galleria-1.2.7.min.js' );
$document->addCustomTag('<script type="text/javascript">jQuery.noConflict();</script>' );
$document->addScript( $baseurl.'modules/mod_tvtma_slider/includes/megamenu.js' );
// Get params
$sectionId  = $params->get('sectionId');
$width      = $params->get('width', 600);
$wait       = $params->get('wait', 600);
$height     = $params->get('height', 400);
$auto       = ($params->get('auto')) ? "true" : "false";
$queue      = ($params->get('queue') == 1) ?  : "false";
$lightbox   = ($params->get('lightbox') == 1) ? "true" : "false";
$thumbnails = ($params->get('thumbnails') == 1) ? "true" : "false";
$transition = $params->get('transition', 'fadeslide');
$imageCrop  = '"' . $params->get('imageCrop') . '"';

$preload    = $params->get('preload' , 2);
$showCounter  = ($params->get('showCounter') == 1) ? "true" : "false";
$showInfo     = ($params->get('showInfo') == 1) ? "true" : "false";
$thumbQuality = ($params->get('thumbQuality') == 1) ? "true" : "false";
$responsive   = ($params->get('responsive') == 1) ? "true" : "false";
$showImagenav = ($params->get('showImagenav') == 1) ? "true" : "false";
$trueFullscreen = ($params->get('trueFullscreen') == 1) ? "true" : "false";
$fullscreenCrop = '"' . $params->get('fullscreenCrop') . '"';
$fullscreenTransition       = '"'. $params->get('fullscreenTransition') . '"';
$lightboxFadeSpeed          = $params->get('lightboxFadeSpeed', 500);
$transitionSpeed            = $params->get('transitionSpeed', 500);
$lightboxTransitionSpeed    = $params->get('lightboxTransitionSpeed', 500);
?>
<style type="text/css">
    #tvtslider {
        height: <?php echo $height . 'px';?>;
        width: <?php echo $width . 'px';?>;
    }
    #mod_tvtma_slider_more_info {
        height: <?php echo $height - 33 . 'px';?>;
    }
    .megamenu {
        height: <?php echo $height / 1.8 . 'px';?>;
        width: <?php echo $width / 1.2 . 'px';?>;
    }
</style>
<div id="tvtslider">
<div id="mod_tvtma_slide_pictureAuthor"></div>
<div id="mod_tvtma_slider_controller">
    <a href="#" onclick="return false;" id="mod_tvtma_slide_prev">Trước</a>
    <a href="#" onclick="return false;" id="mod_tvtma_slide_next">Sau</a>
</div>
<div id="mod_tvtma_slider_cat_name" style=""></div>
<div id="mod_tvtma_slider_total" style=""></div>
<div id="mod_tvtma_slider_notice" style="display:none;"></div>
<div id="mod_tvtma_slider_more_info"></div>
<div id="mod_tvtma_slide_catList">
<!--Mega Menu Anchor-->
<a href="#" id="megaanchor" onclick="return false;"><?php echo JText::_('MOD_TVTMA_SLIDER_CATEGORIES_BUTTON'); ?></a>
<!--Mega Drop Down Menu HTML. Retain given CSS classes-->
</div>
<div id="megamenu" class="megamenu">
    <a class="close">X</a>
<?php echo $menus;?>
</div>    
<div id="galleria" style="width:<?php echo $width . 'px';?>; height: <?php echo $height . 'px';?>;"> </div>
</div>
<script type="text/javascript">
var data = [];
jQuery(document).ready(function() {
     jQuery("#menu_id").val(null);
     jQuery("#mod_tvtma_slider_offset").val(0);
     jQuery("#mod_tvtma_slider_limit").val(5);
     jQuery.ajax({
        type: "POST",
        url: "index.php",
        data: jQuery('#mod_tvtma_slider').serialize(),
        dataType: "json",
        success: function(request){
            jQuery("#mod_tvtma_slider_offset").val(5);
            jQuery("#mod_tvtma_slider_total").html(request.total);
            //var result = request;
            //jQuery('#xml_result').html(result.text());
            data = request.json;
            Galleria.loadTheme('<?php echo $baseurl;?>modules/mod_tvtma_slider/includes/themes/type/galleria.twelve.min.js');
            Galleria.run('#galleria',{
                    dataSource: data,
                    extend: function() {
                        function next(){
                            jQuery("#mod_tvtma_slider_offset").val(  parseInt(jQuery("#mod_tvtma_slider_offset").val()) + 1);
                            jQuery("#mod_tvtma_slider_limit").val(1);
                            jQuery.ajax({
                                    type: "POST",
                                    url: "index.php",
                                    data: jQuery('#mod_tvtma_slider').serialize(),
                                    dataType: "json",
                                    success: function(request){
                                        if(request.json.length > 0) {
                                            gallery.push({
                                                image: request.json[0].image,
                                                title: request.json[0].title,
                                                author: request.json[0].author,
                                                about: request.json[0].about
                                            });

                                        } else {


                                        }
                                    }
                            });
                        };
                        var gallery = this; // "this" is the gallery instance
                        jQuery('#mod_tvtma_slide_prev').click(function() {
                            gallery.prev(); // call the play method
                        });
                        jQuery('#mod_tvtma_slide_next').click(function() {
                            gallery.next(); // call the play method
                            next();
                        });
                        // Select categories
                        jQuery('.tvtma_slider_button').click(function(){
                            
                            //var text = jQuery(this).text();
                            //jQuery("#menu_id").val(jQuery(this).attr('id'));
                            var condition = "";
                            jQuery('.tvtmafieldlist:checked').each(function(index){
                                if(jQuery(this).attr('checked')) {
                                    //alert(jQuery(this).attr('value'));
                                    condition += jQuery(this).attr('value');
                                    condition += ".";
                                    condition += jQuery(this).attr('name');
                                    condition += "|";
                                }
                            });
                            //alert(condition);
                            //return false;
                            jQuery("#menu_id").val(condition);
                            jQuery("#mod_tvtma_slider_offset").val(0);
                            jQuery("#mod_tvtma_slider_limit").val(5);
                            jQuery.ajax({
                                    type: "POST",
                                    url: "index.php",
                                    data: jQuery('#mod_tvtma_slider').serialize(),
                                    dataType: "json",
                                    success: function(request){
                                        jQuery("#mod_tvtma_slider_offset").val(5);
                                        if(request.json.length > 0) {
                                            jQuery("#mod_tvtma_slider_total").html(request.total);
                                            if(gallery._fullscreen.active == true) {
                                                gallery.setOptions('width', '100%');
                                                gallery.setOptions('height', '100%');
                                                gallery.load(request.json);

                                            } else {
                                                gallery.load(request.json);
                                            }
                                            

                                        } else {
                                            if(gallery._fullscreen.active != true) {
                                                jQuery('#mod_tvtma_slider_notice').html("<?php echo JText::_("MOD_TVTMA_SLIDER_EMPTY");?>");
                                                jQuery('#mod_tvtma_slider_notice').bPopup({
                                                    modalClose: true,
                                                    opacity: 0.6,
                                                    positionStyle: 'fixed' //'fixed' or 'absolute'
                                                });
                                            }

                                        }
                                        //alert(JSON.stringify(gallery, null, 4));
                                        //jQuery("#menu_id").val(null);
                                        jQuery('.megamenu').css({'display' : 'none'});
                                    }
                                });
                        });
                        
                        this.bind(Galleria.IMAGE, function(e) {
                            
                            var currentIMG = gallery.getData(gallery.getIndex());
                            if(gallery.getIndex() == 0) {
                                this.attachKeyboard({
                                    left: function() {
                                        return false;

                                    }

                                }); 
                                jQuery("#mod_tvtma_slide_prev").css({'z-Index' : '-20'});
                            } else {
                                jQuery("#mod_tvtma_slide_prev").css({'z-Index' : '100'});
                                this.attachKeyboard({
                                    left: function() {
                                        gallery.prev();

                                    }

                                }); 
                            }
                            var author = currentIMG.author;
                            var about = currentIMG.about;
                            var totalImg = gallery.getDataLength();
                            jQuery('#mod_tvtma_slide_pictureAuthor').html(author);
                            jQuery('.galleria-fullscreen').html('Fullscreen');
                            jQuery('#mod_tvtma_slider_more_info').html(about);
                            jQuery('.tvtmaslider-link').html('<?php echo JText::_("MOD_TVTMA_SLIDER_GET_MORE_INFORMATION");?>');
                            //alert(totalImg);
                            jQuery("#mod_tvtma_slide_pictureAuthor").appendTo('.galleria-bar');
                            jQuery("#mod_tvtma_slider_cat_name").appendTo('.galleria-container');
                            jQuery("#mod_tvtma_slider_total").appendTo('.galleria-container');
                            jQuery("#mod_tvtma_slider_more_info").appendTo('.galleria-bar');
                            jQuery("#mod_tvtma_slide_catList").appendTo('.galleria-bar');
                            jQuery("#mod_tvtma_slide_prev").appendTo('.galleria-bar');
                            jQuery("#mod_tvtma_slide_next").appendTo('.galleria-bar');
                            //jQuery('.galleria-info-text').appendTo('#mod_tvtma_slider_cat_name');
                        });
                        
                        jQuery('#megaanchor').click(function(){
                           var left = jQuery("#megaanchor").offset().left - <?php echo $width/1.7;?>;
                           var top = jQuery("#megaanchor").offset().top - 15 -<?php echo $height/1.8;?>;
                           jQuery("#megamenu").offset({left:left,top:top});
                           
                        });
                        
                        jQuery("a#down").live('click',function(){
                            jQuery('#mod_tvtma_slider_more_info').slideUp();
                            setTimeout(function(){
                                jQuery("#mod_tvtma_slide_pictureAuthor").show();
                            }, 300);
                            
                            
                        });
                        
                        jQuery("#mod_tvtma_slide_pictureAuthor").click(function(){
                            jQuery('#mod_tvtma_slider_more_info').slideDown();
                            if(gallery._fullscreen.active == true) {
                                gallery.setOptions('width', '100%');
                                gallery.setOptions('height', '100%');

                            }
                            jQuery("#mod_tvtma_slide_pictureAuthor").hide();
                            
                        });
                        
                        
                        this.bind("fullscreen_exit", function(e) {
                            var left = jQuery("#tvtslider").offset().left;
                            var top = jQuery("#tvtslider").offset().top;
                            jQuery("#megamenu").offset({left:left,top:top});
                            jQuery("#megamenu").css({"display" : 'none'});
                            jQuery("#mainContainer").css({"z-index" : '1000'});
                            jQuery("#mod_tvtma_slider_cat_name").css({"display" : 'block'});
                            jQuery("#mod_tvtma_slider_total").css({"display" : 'block'});
                        });
                        
                        this.bind("fullscreen_enter", function(e) {
                            jQuery("#mainContainer").css({"z-index" : '1001'});
                            jQuery("#mod_tvtma_slider_cat_name").css({"display" : 'none'});
                            jQuery("#mod_tvtma_slider_total").css({"display" : 'none'});
                            this.attachKeyboard({
                            right: function() {
                                if(gallery._fullscreen.active == true) {
                                    gallery.next();
                                    next();
                                }
                                
                            }

                        }); 
                        });

                    }
            });
            Galleria.configure({
                height: <?php echo $height;?>,
                width : <?php echo $width;?>,
                lightbox: <?php echo $lightbox;?>,
                lightboxFadeSpeed : <?php echo $lightboxFadeSpeed;?>,
                lightboxTransitionSpeed : <?php echo $lightboxTransitionSpeed;?>,
                showCounter : <?php echo $showCounter; ?>,
                trueFullscreen : <?php echo $trueFullscreen;?>,
                transition : '<?php echo $transition;?>',
                thumbQuality : <?php echo $thumbQuality;?>,
                thumbnails : <?php echo $thumbnails;?>,
                responsive : <?php echo $responsive;?>,
                queue : <?php echo $queue;?>,
                preload : <?php echo $preload;?>,
                autoplay : <?php echo $auto;?>,
                transitionSpeed: <?php echo $transitionSpeed;?>,
                showImagenav : <?php echo $showImagenav;?>,
                wait : <?php echo $wait;?>,
                imageCrop : <?php echo $imageCrop;?>,
                showInfo : <?php echo $showInfo;?>,
                fullscreenTransition : <?php echo $fullscreenTransition;?>,
                fullscreenCrop : <?php echo $fullscreenCrop;?>
            });

        }
    });
    

});


</script>
<form name="mod_tvtma_slider" id="mod_tvtma_slider" method="post" action="#">
  <input type="hidden" name="option" value ="com_tvtma1080" />
  <input type="hidden" name="view" value="tvtmaslider" />
  <input type="hidden" name="task" value="getTVTMASlider" />
  <input type="hidden" name="format" value="json" />
  <input type="hidden" name="menu_id" value="" id="menu_id" />
  <input type="hidden" name="offset" value="0" id="mod_tvtma_slider_offset" />
  <input type="hidden" name="limit" value="5" id="mod_tvtma_slider_limit" />
  <input type="hidden" name="section_id" value="<?php echo $sectionId;?>"/>
</form>

