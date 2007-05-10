<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * BlueFlame
 *
 * An open source application development framework for PHP 4.3.2 or newer
 *
 * @package		BlueFlame
 * @author		Rick Ellis
 * @copyright	Copyright (c) 2006, EllisLab, Inc.
 * @license		http://www.codeignitor.com/user_guide/license.html
 * @link		http://blueflame.ciforge.com
 * @since		Version 1.0
 * @filesource
 */

// ------------------------------------------------------------------------

/**
 * Language Class
 *
 * @package		BlueFlame
 * @subpackage	Libraries
 * @category	Language
 * @author		Rick Ellis
 * @link		http://blueflame.ciforge.com/user_guide/libraries/language.html
 */
class CI_Language {

	var $language	= array();
	var $is_loaded	= array();

	/**
	 * Constructor
	 *
	 * @access	public
	 */	
	function CI_Language()
	{
		log_message('debug', "Language Class Initialized");
	}
	
	// --------------------------------------------------------------------
	
	/**
	 * Load a language file
	 *
	 * @access	public
	 * @param	mixed	the name of the language file to be loaded. Can be an array
	 * @param	string	the language (english, etc.)
	 * @return	void
	 */
	function load($langfile = '', $idiom = '', $return = FALSE)
	{	
		$langfile = str_replace(EXT, '', str_replace('_lang.', '', $langfile)).'_lang'.EXT;
		
		if (in_array($langfile, $this->is_loaded, TRUE))
		{
			return;
		}
		
		if ($idiom == '')
		{
			$CI =& get_instance();
			$deft_lang = $CI->config->item('language');
			$idiom = ($deft_lang == '') ? 'english' : $deft_lang;
		}
	
		// Determine where the language file is and load it
		if (file_exists(APPPATH.'language/'.$idiom.'/'.$langfile))
		{
			include(APPPATH.'language/'.$idiom.'/'.$langfile);
		}
		else
		{		
			if (file_exists(BASEPATH.'language/'.$idiom.'/'.$langfile))
			{
				include(BASEPATH.'language/'.$idiom.'/'.$langfile);
			}
			else
			{
				show_error('Unable to load the requested language file: language/'.$langfile);
			}
		}

		
		if ( ! isset($lang))
		{
			log_message('error', 'Language file contains no data: language/'.$idiom.'/'.$langfile);
			return;
		}
		
		if ($return == TRUE)
		{
			return $lang;
		}
		
		$this->is_loaded[] = $langfile;
		$this->language = array_merge($this->language, $lang);
		unset($lang);
		
		log_message('debug', 'Language file loaded: language/'.$idiom.'/'.$langfile);
		return TRUE;
	}
	
	// --------------------------------------------------------------------
	
	/**
	 * Fetch a single line of text from the language array
	 *
	 * @access	public
	 * @param	string	the language line
	 * @return	string
	 */
	function line($line = '')
	{
		return ($line == '' OR ! isset($this->language[$line])) ? FALSE : $this->language[$line];
	}

}
// END Language Class
?>