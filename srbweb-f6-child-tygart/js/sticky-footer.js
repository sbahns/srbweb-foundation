 $(window).bind("load", function () {

    var footer = $("#footer");
//    if (footer.length) {
//      var offset = footer.offset().top;
//not sure if this var offset ^ is needed - getting an error on pos.top


    var pos = footer.position();
    var height = $(window).height();
    height = height - pos.top;
    height = height - footer.height();
    if (height > 0) {
        footer.css({
            'margin-top': height + 'px'
        });
    }
//  }

    // jump to listings fix
    if ( $("a[name='jump-listings']").length > 0 ){

        //console.log( "found a[name='jump-listings']" );

    } else {

        //console.log( "NOT found a[name='jump-listings']" );
        $(".hideifnolistings").hide();

    }

});

$(window).on('resize', function(){
    if ( $("#footer").length > 0 ){
    	var footer = $("#footer");
        var pos = footer.position();
        var height = $(window).height();
        height = height - pos.top;
        height = height - footer.height();
        if (height > 0) {
            footer.css({
                'margin-top': height + 'px'
            });
        }
    }
});(jQuery);
