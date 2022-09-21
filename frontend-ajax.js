jQuery(document).ready(function($){
	$('.mos-email-form').submit(function(e){
		e.preventDefault();		
		var form = $(this);
		const email = form.find('.form-control').val();
		const regex = /[a-zA-Z0-9._%+-]+@((?!gmail|yahoo|hotmail|outlook|ymail).)+\.[a-z]{2,}$/g;
		const found = email.match(regex);
		if (found){
			var form_data = $(this).serialize();
			$(this).find('.btn-mos-email').html('Sending').prop( "disabled", true );
			//console.log(form_data);
			$.ajax({
				url: frontend_ajax_object.ajaxurl, // or example_ajax_obj.ajaxurl if using on frontend
				type:"POST",
				dataType:"json",
				data: {
					'action': 'email_tracking',
					'form_data' : form_data,
				},
				success: function(result){
					if(result.validation){
						form.removeClass('was-validated');
						form.find('.form-control').val("We'll be in touch soon!").prop( "disabled", true );
						form.find('.btn-mos-email').html('<i class="fa fa-check"></i> Sent').prop( "disabled", true );
						//$('.track-output').html(result);                
					} else {
						form.find('.btn-mos-email').html('Try Again').prop( "disabled", false );
					}
				},
				error: function(errorThrown){
					console.log(errorThrown);
					form.find('.btn-mos-email').html('Try Again').prop( "disabled", false );
				}
			});
		}
	});
});