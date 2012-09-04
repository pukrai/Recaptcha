#Code Igniter 2 Recaptcha Library

A simple to use and and easy to install [Recaptcha](http://www.google.com/recaptcha) library for [Code Igniter 2](http://codeigniter.com/).

##Installation

1. Get your public and private keys by registering at the recaptcha site (see link above).

2. Open application/config/recaptcha.php and adapt the settings according to your needs
    1. Public Key
	
	 ```php
	 // your public key
	 $config['public_key']   = "INSERT_YOUR_PUBLIC_KEY_HERE";
	 ```
	
    2. Private Key
	
	 ```php
	 // your private key
	 $config['private_key']   = "INSERT_YOUR_PUBLIC_KEY_HERE";
	 ```
    
	3. Using SSL for the Recaptcha or not
	
     If your site is SSL encrypted, you will want to use ssl for the recaptcha, too, otherwise the end-user's browser will moan and cry about you mixing secure and un-secure content.
	 ```php
	 // use ssl or not?
	 $config['use_ssl'] 	= TRUE;
	 ```
	    
3. Copy the modified application/config/recaptcha.php to your application/config/ directory in your CI installation.

4. Check if you have already extended the Form Helper. 

 If you did, you will find a MY_form_helper.php in /application/helpers. Open it and add the following lines in there:
	
 ```php
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
 ``` 
	
 Otherwise simply copy `application/helpers/MY_form_helper.php` to your `/application/helpers` directory.
	
5. Check if you have already extended the Form Validation Library.
	
 If you did, you will find a `MY_Form_validation.php` in `/application/libraries`. Open it and add the following lines *inside the block* 
	
 ```php
 class MY_Form validation extends CI_Form_validation { ... }
 ```
		
 Insert this:
		
 ```php
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
 ```
		
 Otherwise simply copy the `application/libraries/MY_Form_validation.php` into your `/application/libraries` directory of your CI installation.
	
6. Copy `application/libraries/recaptcha.php` into your `/application/libraries` directory of your CI installation.
	
7. Done.

## Usage

1. Displaying the Recaptcha in your view

 Load the form helper in your controller:

 File `application/controllers/welcome.php`:

 ```php
 public function index()
 {
 	// this loads the form helper and by extending the form_helper.php, 
	// we have a new form-tag we can use to display the Recaptcha 
 	$this->load->helper('form');
 	$this->load->view('welcome_message');
 }
 ```
	
2. Insert the recaptcha whereever you want inside your vie

 File `application/views/welcome_message.php`:
	
 ```php
 <?php
 	// display validation errors
 	echo validation_errors();
			 
 	// call function 'validate' in controller 'welcome' upon submit
 	echo form_open('welcome/validate');
		
   	// the new form-tag to display the recaptcha
 	echo form_recaptcha();

 	echo form_submit('submit');
 	echo form_close();
 ?>
 ```

3. Validating the recaptcha

 File `application/controllers/welcome.php`:

 ```php	
 public function validate() {
 	// load the form_validation library. Since we extended the library, we have a new 
	// validation rule called 'valid_recaptcha' 
	$this->load->library('form_validation');
	
	// new rule: valid_captcha, must be run on the field named 'recaptcha_challenge_field' 
	$rules = array(
		array(
			'field' => 'recaptcha_challenge_field',
			'label' => 'Recaptcha',
			'rules' => 'required|valid_recaptcha',
		),
	);
		
	$this->form_validation->set_rules($rules);
		
	if (!$this->form_validation->run()) {
		$this->load->view('welcome_message');
	} else {
		$this->load->view('recaptcha_result');
 	}
 }
 ```