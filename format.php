<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * PyroForm Plugin
 *
 * Text and content manipulation plugin for PyroCMS
 *
 * @author		Parse19
 * @copyright	Copyright (c) 2011, Parse19
 *
 */
class Plugin_format extends Plugin
{
	/**
	 * Uppercase
	 *
	 * Convert a string to uppercase
	 *
	 * @access	public
	 * @return	string
	 */
	public function uppercase()
	{
		return strtoupper($this->flex_content());
	}

	// --------------------------------------------------------------------------
	
	/**
	 * Lowercase
	 *
	 * Convert a string to lowercase
	 *
	 * @access	public
	 * @return	string
	 */
	public function lowercase()
	{
		return strtolower($this->flex_content());
	}

	// --------------------------------------------------------------------------

	/**
	 * Word Limiter
	 *
	 * Limit words in a string
	 *
	 * @access	public
	 * @return	string
	 */
	public function word_limit()
	{
		$this->load->helper('text');
		
		return word_limiter($this->flex_content(), $this->attribute('limit', 50), $this->attribute('suffix', '&hellip;'));
	}

	// --------------------------------------------------------------------------

	/**
	 * Character Limiter
	 *
	 * Limit characters in a string
	 *
	 * @access	public
	 * @return	string
	 */
	public function character_limit()
	{
		$this->load->helper('text');
		
		return character_limiter($this->flex_content(), $this->attribute('limit', 50), $this->attribute('suffix', '&hellip;'));
	}

	// --------------------------------------------------------------------------

	/**
	 * Word Censor
	 *
	 * Censor words in a string. Accepts disallowed
	 * Characters as a string separated by pipes
	 *
	 * @access	public
	 * @return	string
	 */
	public function word_censor()
	{
		$this->load->helper('text');
		
		$disallowed_raw = $this->attribute('disallowed');
		$disallowed = explode('|', $disallowed_raw);
		
		return word_censor($this->flex_content(), $disallowed, $this->attribute('censor_string', '***'));
	}

	// --------------------------------------------------------------------------

	/**
	 * Number Format
	 *
	 * @access	public
	 * @return	string
	 */
	public function number()
	{
		return number_format(
					$this->flex_content('number'),
					$this->attribute('decimals', '0'),
					$this->attribute('dec_point', '.'),
					$this->attribute('thousands_sep', ',')
				);
	}

	// --------------------------------------------------------------------------

	/**
	 * Money Format
	 *
	 * @access	public
	 * @return	string
	 */
	public function money()
	{
		return money_format(
					$this->attribute('format', '%i'),
					$this->flex_content('money')
				);
	}

	// --------------------------------------------------------------------------

	/**
	 * Date Format. Originally from PyroStreams.
	 *
	 * @access	public
	 * @return	string
	 */
	public function date()
	{
	 	$date_formats = array('DATE_ATOM', 'DATE_COOKIE', 'DATE_ISO8601', 'DATE_RFC822', 'DATE_RFC850', 'DATE_RFC1036', 'DATE_RFC1123', 'DATE_RFC2822', 'DATE_RSS', 'DATE_W3C');
	 	
		$date = $this->flex_content('date');
		$format = $this->attribute('format', 'Y-m-d H:i:s');
		
		// No sense in trying to get down
		// with somedata that isn't there
		if(!$date or !$format) return null;
		
		$this->load->helper('date');
	
		// Make sure we have a UNIX date
		if(!is_numeric($date)) $date = mysql_to_unix($date);
		
		// Is this a preset?
		if(in_array($format, $date_formats)) return standard_date($format, $date);

		// Default is PHP date		
		return date($format, $date);
	}

	// --------------------------------------------------------------------------

	/**
	 * Timespan
	 *
	 * @access	public
	 * @return	string
	 */
	public function timespan()
	{
		$this->load->helper('date');
		
		$start = $this->attribute('start');
		$end = $this->attribute('end', time());
		
		if(!is_numeric($start)) $start = mysql_to_unix($start);
		if(!is_numeric($end)) $end = mysql_to_unix($end);

		return timespan($start, $end);	
	}

	// --------------------------------------------------------------------------
	
	/**
	 * Get content from tag
	 *
	 * This allows some flexibility, hence the name.
	 * You can either pass content through a content=""
	 * tag, or put it between two tags.
	 *
	 * The content between the two tags over
	 * the content="" parameter.
	 *
	 * @access	private
	 * @param	[string - name override. Allows overriding of "content" input name]	
	 * @return	string
	 */
	private function flex_content($name_override = false)
	{
		$name = ($name_override) ? $name_override : 'content';
		
		return ($this->content()) ? $this->content() : $this->attribute($name);
	}

}

/* End of file example.php */