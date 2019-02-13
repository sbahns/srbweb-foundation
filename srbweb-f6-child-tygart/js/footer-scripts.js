// FOOTER SCRIPTS
// Various custom and additional scripts for this theme. These will all load in the footer.

/*---------------------------------------
 * Google Search box toggle
 * Google search magnifying glass/search box toggle
 * .menu-search
 * -------------------------------------*/
jQuery('.fi-magnifying-glass').click(function() {
	//jQuery('.google-search').slideToggle(300);
    jQuery('.google-search').toggleClass('mui-enter-active');
	jQuery('.googleq').focus();
});

jQuery.fn.searchPlaceholder = function(txt) {
    //removes placeholder text in google search form when user clicks in search field
   this.focus(function(){
     if(jQuery(this).attr('value') === (txt)){
       jQuery(this).attr('value', '');
     }
   });
   //adds placeholder text in google search form when user moves out of search field
   this.blur(function(){
     if(jQuery(this).attr('value') === ''){
       jQuery(this).attr('value', txt);
     }
   });
};
jQuery("input.googleq").searchPlaceholder("Search this site...");


/*-------------------------------------------------------
 * Category Description Show/Hide
 * hide subsequent paragraphs in category descriptions
 -------------------------------------------------------*/
jQuery('div.category-description p:not(:first-child)').slideUp();
jQuery('div.category-description ul').slideUp();
jQuery('a.toggle-description').on('click', function(event) {
	var root = this.parentNode.parentNode.parentNode;
	jQuery('div.category-description p:not(:first-child)', root).slideToggle();
	jQuery('div.category-description ul', root).slideToggle();
 // jQuery('a.toggle-description').toggleHTML('Hide full description', 'See full description');
	jQuery('a.toggle-description', root).toggleHTML('Read Less <i class="fi-play"></i>', 'Read More <i class="fi-play"></i>');
});


/*-------------------------------------------------------
 * ?
 -------------------------------------------------------*/
jQuery.fn.toggleHTML = function(a, b) {
	return this.html(function(_, html) {
		return jQuery.trim(html) === a ? b : a;
	});
}


jQuery('#library-view').bind('change', function () { // bind change event to select
    var url = jQuery(this).val(); // get selected value
    if (url != '') { // require a URL
        window.location = url; // redirect
    }
    return false;
});

/*----------------------------------------------------------------------
* Foundation Lightbox (uses Foundation 6 Reveal)
* http://foundation.zurb.com/sites/docs/reveal.html
* corresponding HTML and CSS are in footer.php.
* --------------------------------------------------------------------*/

  jQuery('.foundation_lightbox').click( function(){
      imgurl = jQuery(this).parent().attr('href');

      jQuery('#revealimg').attr("src",imgurl);

      jQuery('#lightbox-image').foundation('open');
      return false;
});

/*----------------------------------------------------------------------
 * Banner Spacer
 * add class to reduce header/nameplate/leaderboad, etc when scrolling
 * --------------------------------------------------------------------*/

jQuery(window).bind('load', function() {
	jQuery('.my-fixed-header').addClass("banner");
	jQuery('.banner-spacer').addClass("open");
});

/*----------------------------------------------------------------------
 * Scrolling effects
 * add class to reduce header/nameplate/leaderboad, etc when scrolling
 * --------------------------------------------------------------------*/
jQuery(window).on('scroll', function() {
  if (jQuery(this).scrollTop() > 0){
    jQuery('.leaderboard-container').addClass("hide");
    jQuery('header#my-fixed-header').addClass("shrink");
    jQuery('.my-fixed-header').removeClass("banner");
    jQuery('.banner-spacer').removeClass("open");
    jQuery('#my-fixed-header img').addClass("shrink");
  //  jQuery('#page').css("margin-top", "-76px");
    jQuery('#page').addClass("padding-on-print");
  //jQuery('.google-search').addClass("scrolled");
  }
  else{
    jQuery('.leaderboard-container').removeClass("hide");
    jQuery('header#my-fixed-header').removeClass("shrink");
    jQuery('.my-fixed-header').addClass("banner");
    jQuery('.banner-spacer').addClass("open");
    jQuery('#my-fixed-header img').removeClass("shrink");
  //  jQuery('#page').css("margin-top", "12px");
    //jQuery('.google-search').removeClass("scrolled").addClass("scrolled-back");
  }
});

/*----------------------------------------------------------------------
 * Accordion Widgets
 * code to dictate the behavior of our custom accordion widgets
 * --------------------------------------------------------------------*/
jQuery(document).ready(function() {
	jQuery('#browse-topics-list').attr("aria-multiselectable","false");
	jQuery('#free-guides-list .is-accordion-submenu-parent').attr("aria-expanded","true");
	jQuery('#free-guides-list .is-accordion-submenu').attr("aria-hidden","false").css("display","block");
});

/*----------------------------------------------------------------------
 * Network Home image mouseover assist
 * applies the image brightness reduction filter css when the mouse is
 * over the text layer above the image
 * --------------------------------------------------------------------*/
jQuery( '.image-hover-text' ).hover(function() {
  jQuery('img.network-thumb').addClass("network-home-image");
});
