<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class MY_Form_validation extends CI_Form_validation {

	/**
	 * Extending form helper to add ReCaptcha
	 */

	public function valid_recaptcha($str) {
		
		$CI =& get_instance();
		$CI->load->library(array('recaptcha'));
		
		$result = $CI->recaptcha->recaptcha_check_answer();
		
		if ($result->is_valid) {
			return TRUE;
		} else {
			$CI->form_validation->recaptcha_error = $result->error;
			$CI->form_validation->set_message('valid_recaptcha', 'The %s is incorrect. Please try again.');
			return FALSE;
		}
	}
}

?>