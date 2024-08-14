<?php
add_action( 'wp_enqueue_scripts', 'hello_elementor_child_enqueue_scripts', 20 );
function theme_enqueue_scripts() {
	/**
	 * frontend ajax requests.
	 */
	wp_enqueue_script( 'frontend-ajax', get_stylesheet_directory_uri() . '/frontend-ajax.js', array('jquery'), null, true );
	wp_localize_script( 'frontend-ajax', 'frontend_ajax_object',
		array( 
			'ajaxurl' => admin_url( 'admin-ajax.php' ),
		)
	);
}
add_action( 'wp_enqueue_scripts', 'theme_enqueue_scripts' );
/* AJAX action callback */
add_action( 'wp_ajax_email_tracking', 'email_tracking_ajax_callback' );
add_action( 'wp_ajax_nopriv_email_tracking', 'email_tracking_ajax_callback' );
/* Ajax Callback */
function email_tracking_ajax_callback () {
    parse_str($_POST['form_data'], $searcharray);
    $output = array($searcharray['email']);
	$output['validation'] = false;
    //$output = array($searcharray['mos_email_form_field']);
	if (  filter_var($searcharray['email'], FILTER_VALIDATE_EMAIL) && isset( $searcharray['mos_email_form_field'] ) && wp_verify_nonce(sanitize_text_field(wp_unslash($searcharray['mos_email_form_field'])), 'mos_email_form_action' ) ) {
		$output['validation'] = true;
		$admin_email = get_option( 'admin_email' );
		//$admin_email = 'mostak.shahid@gmail.com';
		$blogname = get_option( 'blogname' );
		$siteurl = get_option( 'siteurl' );

		$to = $admin_email;
		$subject = 'Email from '.$blogname;
		$body = 'Someone like to contact you from <a href="'.$siteurl.'">'.$blogname.'</a>, and the email address is '.strtolower($searcharray['email']);
		$headers = array('Content-Type: text/html; charset=UTF-8');
		wp_mail( $to, $subject, $body, $headers );
	}
	echo json_encode($output);
    exit; // required. to end AJAX request.
}

function mos_email_form_submit(){
	if ( filter_var($_POST['email'], FILTER_VALIDATE_EMAIL) && isset( $_POST['mos_email_form_field'] ) && wp_verify_nonce( $_POST['mos_email_form_field'], 'mos_email_form_action' ) ) {
		//var_dump($_POST);
		$admin_email = get_option( 'admin_email' );
		//$admin_email = 'mostak.shahid@gmail.com';
		$blogname = get_option( 'blogname' );
		$siteurl = get_option( 'siteurl' );

		$to = $admin_email;
		$subject = 'Email from '.$blogname;
		$body = 'Someone like to contact you from <a href="'.$siteurl.'">'.$blogname.'</a>, and the email address is '.strtolower($_POST['email']);
		$headers = array('Content-Type: text/html; charset=UTF-8');
		wp_mail( $to, $subject, $body, $headers );
	}
}
add_action('wp_head', 'mos_email_form_submit');
function form_validation_func() {
	?>
    <script>
		(() => {
		  'use strict'

		  // Fetch all the forms we want to apply custom Bootstrap validation styles to
		  const forms = document.querySelectorAll('.needs-validation')

		  // Loop over them and prevent submission
		  Array.from(forms).forEach(form => {
			form.addEventListener('submit', event => {
			  if (!form.checkValidity()) {
				event.preventDefault()
				event.stopPropagation()
			  }

			  form.classList.add('was-validated')
			}, false)
		  })
		})()
	</script>
	<?php
}
add_action( 'wp_footer', 'form_validation_func' );
