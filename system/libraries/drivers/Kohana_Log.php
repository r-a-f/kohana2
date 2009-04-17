<?php defined('SYSPATH') OR die('No direct access allowed.');
/**
 * Log API driver.
 *
 * @package    Kohana_Log
 * @author     Kohana Team
 * @copyright  (c) 2007-2009 Kohana Team
 * @license    http://kohanaphp.com/license.html
 */
abstract class Kohana_Log_Driver {

	protected $config = array();

	public function __construct(array $config)
	{
		$this->config = $config;
	}

	abstract public function save(array $messages);
}