<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Extending form helper to add ReCaptcha
 */

 if ( ! function_exists('form_recaptcha'))
{
	function form_recaptcha($error = null) {
		
		$CI =& get_instance();
		$CI->load->library(array('recaptcha'));
		
		echo $CI->recaptcha->recaptcha_get_html($error);
	}
}