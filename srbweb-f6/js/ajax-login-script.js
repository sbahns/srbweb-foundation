jQuery(document).ready(function($) {

    // Perform AJAX login on form submit
    $('form#loginform-ajax').on('submit', function(e){
        
        $('form#loginform-ajax input').focus(function() {
			$('form#loginform-ajax div.alert').slideUp().text('');
		});
        
        $('form#loginform-ajax span.status').fadeIn().text(ajax_login_object.loadingmessage);
		
        $.ajax({
            type: 'POST',
            dataType: 'jsonp',
            crossDomain: true,
            url: ajax_login_object.ajaxurl,
            data: { 
                'action': 'ajaxlogin', //calls wp_ajax_nopriv_ajaxlogin
                'username': $('form#loginform-ajax #user_login').val(), 
                'password': $('form#loginform-ajax #user_pass').val()
			}, //'security': $('form#loginform #security').val() // nonce
            
            success: function(data){
                //$('form#loginform-ajax p.status').text(data.message);
				$('form#loginform-ajax span.status').fadeOut(function(){
					$('form#loginform-ajax span.status').text(data.message);
				});
                if (data.loggedin == true){
					$('form#loginform-ajax span.status').fadeIn();
					location.reload();
                } else {
					$('form#loginform-ajax div.alert').text(data.message);
					$('form#loginform-ajax div.alert').slideDown();
				}
            }

        });
        e.preventDefault();
    });

});