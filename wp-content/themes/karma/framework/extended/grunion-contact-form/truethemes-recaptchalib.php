<?php
/*
 * updated on @since Karma version 2.6.7 dev 1
 *
 * This is a PHP library that handles calling reCAPTCHA.
 *    - Documentation and latest version
 *          http://recaptcha.net/plugins/php/
 *    - Get a reCAPTCHA API Key
 *          https://www.google.com/recaptcha/admin/create
 *    - Discussion group
 *          http://groups.google.com/group/recaptcha
 *
 * Copyright (c) 2007 reCAPTCHA -- http://recaptcha.net
 * AUTHORS:
 *   Mike Crawford
 *   Ben Maurer
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 */

/**
 * The reCAPTCHA server URL's
 */
define('RECAPTCHA2_JS_URL', 'https://www.google.com/recaptcha/api.js');
define('RECAPTCHA2_VERIFY_URL', 'https://www.google.com/recaptcha/api/siteverify');

/**
 * Gets the challenge HTML (javascript and non-javascript version).
 * This is called from the browser, and the resulting reCAPTCHA HTML widget
 * is embedded within the HTML form it was called from.
 *
 * @param string $pubkey A public key for reCAPTCHA
 *
 * @return string - The HTML to be embedded in the user's form.
 */
function tt_recaptcha_get_html( $pubkey ) {
	if ( $pubkey == null || $pubkey == '' ) {
		die ( "To use reCAPTCHA you must get an API key from <a href='https://www.google.com/recaptcha/admin/create'>https://www.google.com/recaptcha/admin/create</a>" );
	}

	return '
		<script type="text/javascript" src="' . RECAPTCHA2_JS_URL . '"></script>
		<div class="g-recaptcha" data-sitekey="' . $pubkey . '"></div>
	';
}


/**
 * A tt_ReCaptchaResponse is returned from recaptcha_check_answer()
 */
class tt_ReCaptchaResponse {
	var $is_valid;
	var $error;
}


/**
  * Calls an HTTP POST function to verify if the user's guess was correct
  * @param string $privkey
  * @param string $remoteip
  * @param string $response
  * @return tt_ReCaptchaResponse
  */
function tt_recaptcha_check_answer( $privkey, $remoteip, $response ) {
	if ( $privkey == null || $privkey == '' ) {
		die ( "To use reCAPTCHA you must get an API key from <a href='https://www.google.com/recaptcha/admin/create'>https://www.google.com/recaptcha/admin/create</a>" );
	}

	if ( $remoteip == null || $remoteip == '' ) {
		die ( "For security reasons, you must pass the remote ip to reCAPTCHA" );
	}

	//discard spam submissions
	if ( $response == null || strlen( $response ) == 0 ) {
		$recaptcha_response = new tt_ReCaptchaResponse();
		$recaptcha_response->error = 'incorrect-captcha-sol';
		$recaptcha_response->is_valid = false;

		return $recaptcha_response;
	}

	$request = wp_remote_post( RECAPTCHA2_VERIFY_URL, array(
		'body' => array(
			'secret' => $privkey,
			'remoteip' => $remoteip,
			'response' => $response,
		),
	) );

	$recaptcha_response = new tt_ReCaptchaResponse();
	$recaptcha2_response = json_decode( $request['body'], true );

	if ( true === $recaptcha2_response['success'] ) {
		$recaptcha_response->is_valid = true;
	} else {
		$recaptcha_response->is_valid = false;
		$recaptcha_response->error = array_shift( $recaptcha2_response['error-codes'] );
	}

	return $recaptcha_response;

}
//deleted mail hide related code and signup url code, we only need reCAPTCHA
?>