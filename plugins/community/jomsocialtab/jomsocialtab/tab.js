jQuery(document).ready(function() {
        
// setting the tabs in the sidebar hide and show, setting the current tab
	jQuery('div.tabbed div').hide();
	jQuery('div.t0').show();
	jQuery('div.tabbed ul.tabs li.t0 a').addClass('tab-current');

// SIDEBAR TABS
jQuery('div.tabbed ul li a').click(function(){
        var current = jQuery(this);
        var ulParent = current.parents('ul');
        var divParent = current.parents('div.tabbed');
        ulParent.find('li').each(function(){
            if(jQuery(this).hasClass('current-tab')) {
                jQuery(this).removeClass('current-tab');
            }
        });
	var thisClass = jQuery(this).attr('id');
        var parent = jQuery(this).parent();
        parent.addClass('current-tab');
	divParent.find('div.jomsocialtab').hide();
	jQuery('div.' + thisClass).show();
        //jQuery('div.' + thisClass).show();
	jQuery('div.tabbed ul.tabs li a').removeClass('tab-current');
	jQuery(this).addClass('tab-current');
	});
});