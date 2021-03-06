<?php defined( 'ABSPATH' ) or die( 'No script kiddies please!' );
/**
 * Options Manager Class
 *
 * @author		Geraint Palmer
 * @version 	1.0.3 	   
 */
class GcpOptions
{
	const VERSION = '1.0.3';
	protected $options_key = 'ttd_plugin_options';
	protected $_options = array(
		'key'	=> 'value',
	);

	private $_optioncache = null;
	
	function __construct($optionsKey, $defaultArray)
	{
		$this->options_key = $optionsKey;
		$this->_options = $defaultArray;
	}
	
	protected function _populate_option_cache()
	{
		//checks to see if options are cached
		if (!$this->_optioncache)
		{
			// tries to create a cache from wordpress DB options table.// uses plugin preset options not in db
			$this->_optioncache =  get_option( $this->options_key ) !== false ? unserialize( get_option( $this->options_key )) : false;
			if (!$this->_optioncache) 
				$this->_optioncache = $this->_options;
		}
	}
	
	/**
	 * Clears the option cache
	 */
	public function flush_options()
	{
		unset($this->_optioncache);
	}
	
	/**
	 * Fetches the value of an option. Returns `null` if the option is not set.
	 */
	public function get_option($option, $default=null)
	{
		$this->_populate_option_cache();
		$value = $this->_optioncache[$option];

		if (!$value)
			return $default;
		return $value;
	}
	
	/**
	 * Removes an option.
	 */
	public function delete_option($option)
	{
		$this->_populate_option_cache();
		unset($this->_optioncache[$option]);
		update_option( $this->options_key, serialize($this->_optioncache) );
	}
	
	/**
	 * Updates the value of an option.
	 */
	public function update_option($option, $value)
	{
		$this->_populate_option_cache();
		$this->_optioncache[$option] = $value;
		update_option( $this->options_key, serialize($this->_optioncache) );
	}
	
	/**
	 * Sets an option if it doesn't exist.
	 */
	public function add_option($option, $value)
	{
		$this->_populate_option_cache();
		if (!array_key_exists($option, $this->_optioncache))
		{
			$this->_optioncache[$option] = $value;
			add_option( $this->options_key, serialize($this->_optioncache) );
		}
	}
}

?>