/* self collapse nav */
jQuery(document).ready(function() { 
	jQuery('.tab_toggle').click(function(){
		var theid = jQuery(this).attr('href');
		jQuery('.tab_toggle').removeClass('active');
		jQuery('.tab_items').removeClass('active');
		jQuery(this).addClass('active');
		jQuery(theid).addClass('active');
		return false;
	});

	jQuery(function () {
	    jQuery("div.blogitems").slice(0, 6).show();
	    jQuery("#loadMore").on('click', function (e) {
	        e.preventDefault();
	        jQuery("div.blogitems:hidden").slice(0, 3).slideDown();
	        if (jQuery("div.blogitems:hidden").length == 0) {
	            jQuery("#load").fadeOut('slow');
	        }
	        jQuery('html,body').animate({
	            scrollTop: jQuery(this).offset().top
	        }, 1500);
	    });
	});

	jQuery(function () {
	    jQuery("div.hblogitems").slice(0, 3).show();
	    jQuery("#hloadMore").on('click', function (e) {
	        e.preventDefault();
	        jQuery("div.hblogitems:hidden").slice(0, 3).slideDown();
	        if (jQuery("div.hblogitems:hidden").length == 0) {
	            jQuery("#load").fadeOut('slow');
	        }
	        jQuery('html,body').animate({
	            scrollTop: jQuery(this).offset().top
	        }, 1500);
	    });
	});

	jQuery(function () {
	    jQuery("a.paginationitem").removeClass('active');
	    jQuery("a.paginationitem:first").addClass('active');
	    jQuery(".category-overview .overview").removeClass('active');
	    jQuery(".category-overview .overview.page1").addClass('active');

	    jQuery(".pagination a.paginationitem").on('click', function (e) {
	        e.preventDefault();
	        jQuery(".pagination a").removeClass('active');
	        jQuery(this).addClass('active');

	        var theclass = jQuery(this).attr('href');
	        jQuery(".category-overview .overview").removeClass('active');
	    	jQuery(".category-overview .overview."+theclass).addClass('active');

	    	jQuery('html,body').animate({
	            scrollTop: jQuery('.category-overview').offset().top
	        }, 1000);
	    });

	    jQuery(".pagination a.paginationitemfirst").on('click', function (e) {
	        e.preventDefault();
	        jQuery(".pagination a.paginationitem:first").click();
	    });

	    jQuery(".pagination a.paginationitemlast").on('click', function (e) {
	        e.preventDefault();
	        jQuery(".pagination a.paginationitem:last").click();
	    });

	    jQuery(".pagination a.paginationitemprev").on('click', function (e) {
	        e.preventDefault();
	        jQuery(".pagination a.paginationitem.active").prev().click();
	    });

	    jQuery(".pagination a.paginationitemnext").on('click', function (e) {
	        e.preventDefault();
	        jQuery(".pagination a.paginationitem.active").next().click();
	    });
	});

	jQuery('a[href=#top]').click(function () {
	    jQuery('body,html').animate({
	        scrollTop: 0
	    }, 600);
	    return false;
	});

	jQuery('a.showmore-review').on('click', function (e) {
	    e.preventDefault();
	    var theID = jQuery(this).attr('href');
	    var thesymbol = jQuery(this).children('small').html();
	    if(thesymbol == '+') {
	    	jQuery(this).children('small').html('-');
	    	jQuery(theID).slideDown();
	    } else {
	    	jQuery(this).children('small').html('+');
	    	jQuery(theID).slideUp();
	    }
	    
	});

	
});

jQuery(window).scroll(function () {
    if (jQuery(this).scrollTop() > 50) {
        jQuery('.totop a').fadeIn();
    } else {
        jQuery('.totop a').fadeOut();
    }
});