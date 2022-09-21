<?php
function mos_email_form_func($atts = array(), $content = '') {
	$atts = shortcode_atts( array(
        'class' => '',
		'placeholder' => 'Enter your email address',
		'button' => 'Apply for access',
	), $atts, 'mos-email-form' );
    ob_start(); ?>
        <div class="mos-email-form-wrapper <?php echo $atts['class'] ?>">
			<form class="mos-email-form needs-validation" novalidate method="post">
				<div class="input-group">
					<input name="email" id="email" type="text" class="form-control" placeholder="<?php echo $atts['placeholder'] ?>"  pattern="[a-zA-Z0-9._%+-]+@((?!gmail|yahoo|hotmail|outlook|ymail).)+\.[a-z]{2,}$" required>
					<button class="btn btn-mos-email elementor-button" type="submit"><?php echo $atts['button'] ?></button>
					<div class="invalid-feedback">Please choose a valid business email.</div>
				</div>
				<?php wp_nonce_field( 'mos_email_form_action', 'mos_email_form_field' ); ?>
			</form>
			<?php echo do_shortcode($content) ?>
		</div>
    <?php $html = ob_get_clean();
    return $html;
}
add_shortcode( 'mos-email-form', 'mos_email_form_func' );