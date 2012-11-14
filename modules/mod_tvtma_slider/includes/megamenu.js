jQuery(document).ready(function() {
  jQuery('#megaanchor').click(function(){
      jQuery('.megamenu').show();
  });
  jQuery('a.close').click(function(){
        jQuery('.megamenu').hide();
  });
  // Hide menu when move mouse out
  jQuery('.megamenu').mouseleave(function(){
      //jQuery(this).css({'display' : 'none'});
  });
  // checkbox when click hyperlink
  jQuery('#megamenu .column a').click(function(){
     var parent = jQuery(this).parent();
     var checkbox = parent.find('input');
     checkbox.attr('checked', true);
  });
  
  jQuery('.tvtma_slider_button').click(function(){
      
  });

})