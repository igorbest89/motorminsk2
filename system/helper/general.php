<?php
/**
 * @param $path
 * @return string
 */
function renderSVG($path)
{
    $fullPath   = DIR_IMAGE . $path;
    $handle     = fopen($fullPath, "r");
    $contents   = fread($handle, filesize($fullPath));
    fclose($handle);


    return $contents;
}


/**
 * Merge data about logo
 * @param $base
 * @param $config_logo
 * @param string $name
 * @return array
 */
function renderLogoData($base, $config_logo, $name = "")
{
    $array              = array();
    $array['logo']      = '';
    $array['logo_svg']  = false;
    $array['name']      = $name;
    $image              = $config_logo;

    if (is_file(DIR_IMAGE . $image))
    {
        if (strpos($image, '.svg') !== false)
        {
            $array['logo']      = renderSVG($image);
            $array['logo_svg']  = true;

        }
        else
        {
            $array['logo'] = $base . 'image/' . $image;
        }

    }


    return $array;
}

/**
 * @param $logo
 * @param $logo_svg
 * @param string $name
 * @return string
 */
function renderLogo($logo, $logo_svg, $name = '')
{
    return (true == $logo_svg ? $logo : "<img src='{$logo}' title='{$name}' alt={$name}' class='img-responsive' />");
}


function token($length = 32) {
	// Create random token
	$string = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
	
	$max = strlen($string) - 1;
	
	$token = '';
	
	for ($i = 0; $i < $length; $i++) {
		$token .= $string[mt_rand(0, $max)];
	}	
	
	return $token;
}

/**
 * Backwards support for timing safe hash string comparisons
 * 
 * http://php.net/manual/en/function.hash-equals.php
 */

if(!function_exists('hash_equals')) {
	function hash_equals($known_string, $user_string) {
		$known_string = (string)$known_string;
		$user_string = (string)$user_string;

		if(strlen($known_string) != strlen($user_string)) {
			return false;
		} else {
			$res = $known_string ^ $user_string;
			$ret = 0;

			for($i = strlen($res) - 1; $i >= 0; $i--) $ret |= ord($res[$i]);

			return !$ret;
		}
	}
}