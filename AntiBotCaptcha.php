<?php
/*
Plugin Name: Q2A AntiBot Captcha
Plugin URI: 
Description: AntiBot Captcha - simple good-looking, but well-protected plugin against spam robots for your contents.
Author: Krzysztof Kielce
Version: 1.0
Author URI: 

Based on:
Wordpress Captcha plugin: http://isaev.asia/en/my-wordpress-plugins/antibot-captchca/

*/

$url = "";
$form_div;

if (!class_exists('AntiBotCaptcha')) {

@session_start();

class AntiBotCaptcha
{
	public static $count = 4; /* symbol count (by default = 4) */
	
	function AntiBotCaptcha($url_)
	{
		global $url, $form_div;
		$url = $url_;
		
		$form_div = strtolower(substr(md5( $_SERVER['PHP_SELF']  ), 3, 12));
	}
	
	function qa_captcha_html($captcha_imput_code)
	{
		global $url, $form_div;
		$img_path = $url."AntiBotCaptcha.php?image=".time();
		
		return '<div style="vertical-align:middle;" id="'.$form_div.'div">' .
		'<p><input type="text" class="qa-form-tall-number" name="'.$form_div.'" id="'.$form_div.'" size="6"  tabindex="4" autocomplete="off" />' .
		'<label for="'.$form_div.'"> <img src="'.$img_path.'"  alt="'.$captcha_imput_code.'" align="absbottom" /></label>'.
		'</p></div>';
	}
	
	function setCount($count_) 
	{ $_SESSION['option_count'] = $count_; }
	function setCharset($chars_) 
	{ $_SESSION['option_chars'] = $chars_; }
	
}	//end class

/**
 * A CaptchaResponse is returned from captcha_check_answer()
 */
class CaptchaResponse {
        var $is_valid;
        var $error;
}

}	//end if

function captcha_check_answer()
{
	global $_POST, $_SESSION, $form_div;
	
	$captcha_response = new CaptchaResponse();
	$captcha_response->is_valid = false;
	
	$securitycode = $_POST[$form_div];
	if ($securitycode == "")
		$captcha_response->error = 'ERROR: Input code from image';
	else if ( $_SESSION['IMAGE_CODE'] != $securitycode )
		$captcha_response->error = 'Invalid code. Return back and try input code again.';
	else {
		unset($_SESSION['IMAGE_CODE']);
		$captcha_response->is_valid = true;
	}
	return $captcha_response;
}
	
	

$secimg = new AntiBotCaptcha($url);

if (isset($_GET['image']) && preg_match('/^[0-9]+$/', $_GET['image'])) {  
//session_start();

// default values:
if (!isset($_SESSION['option_count']))
	$_SESSION['option_count'] = 4;
if (!isset($_SESSION['option_chars']))
	$_SESSION['option_chars'] = 23456789;
$count = $_SESSION["option_count"];
$width=200; /* picture width */
$height=48; /* picture height */
$font_size_min=20; /* minimum symobl height */
$font_size_max=32; /* maximum symobl height */
$font_file=(dirname(__FILE__))."/gothic.otf"; /* font name, otf or ttfs */
$char_angle_min=-10; /* maximum skew of the symbol to the left*/
$char_angle_max=10;	/*  maximum skew of the symbol to the right */
$char_angle_shadow=5;	/*shadow size */
$char_align=40;	/* align symbol verticaly */
$start=5;	/* first symbol position */
$interval=16;	/* interval between the start position of characters */
$chars=$_SESSION['option_chars']; /* charset */
$noise=0; /* noise level */

$image=imagecreatetruecolor($width, $height);

$background_color=imagecolorallocate($image, 255, 255, 255); /* rbg background color*/
$font_color=imagecolorallocate($image, 32, 64, 96); /* rbg shadow color*/

imagefill($image, 0, 0, $background_color);
imagecolortransparent($image, $background_color);

$str="";

$num_chars=strlen($chars);
for ($i=0; $i<$count; $i++)
{
	$char=$chars[rand(0, $num_chars-1)];
	$font_size=rand($font_size_min, $font_size_max);
	$char_angle=rand($char_angle_min, $char_angle_max);
	imagettftext($image, $font_size, $char_angle, $start, $char_align, $font_color, $font_file, $char);
	imagettftext($image, $font_size, $char_angle+$char_angle_shadow*(rand(0, 1)*2-1), $start, $char_align, $background_color, $font_file, $char);
	$start+=$interval;
	$str.=$char;
}

if ($noise)
{
	for ($i=0; $i<$width; $i++)
	{
		for ($j=0; $j<$height; $j++)
		{
			$rgb=imagecolorat($image, $i, $j);
			$r=($rgb>>16) & 0xFF;
			$g=($rgb>>8) & 0xFF;
			$b=$rgb & 0xFF;
			$k=rand(-$noise, $noise);
			$rn=$r+255*$k/100;
			$gn=$g+255*$k/100;		
			$bn=$b+255*$k/100;
			if ($rn<0) $rn=0;
			if ($gn<0) $gn=0;
			if ($bn<0) $bn=0;
			if ($rn>255) $rn=255;
			if ($gn>255) $gn=255;
			if ($bn>255) $bn=255;
			$color=imagecolorallocate($image, $rn, $gn, $bn);
			imagesetpixel($image, $i, $j , $color);					
		}
	}
}

$_SESSION["IMAGE_CODE"]=$str;

if (function_exists("imagepng"))
{
	header("Content-type: image/png");
	imagepng($image);
}
elseif (function_exists("imagegif"))
{
	header("Content-type: image/gif");
	imagegif($image);
}
elseif (function_exists("imagejpeg"))
{
	header("Content-type: image/jpeg");
	imagejpeg($image);
}

imagedestroy($image);
exit;
}


?>