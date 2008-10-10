<?php defined('SYSPATH') or die('No direct script access.');

class Auth_User_Model extends ORM {

	// Relationships
	protected $has_many = array('user_tokens');
	protected $has_and_belongs_to_many = array('roles');

	// Columns to ignore
	protected $ignored_columns = array('password_confirm');

	public function __set($key, $value)
	{
		if ($key === 'password')
		{
			// Use Auth to hash the password
			$value = Auth::instance()->hash_password($value);
		}

		parent::__set($key, $value);
	}

	/**
	 * Validates and optionally saves a new user record from an array.
	 *
	 * @param  array    values to check
	 * @param  boolean  save the record when validation succeeds
	 * @return boolean
	 */
	public function validate(array & $array, $save = FALSE)
	{
		$array = Validation::factory($array)
			->pre_filter('trim')
			->add_rules('email', 'required', 'length[4,127]', 'valid::email')
			->add_rules('username', 'required', 'length[4,32]', 'chars[a-zA-Z0-9_.]', array($this, 'username_available'))
			->add_rules('password', 'required', 'length[5,42]')
			->add_rules('password_confirm', 'matches[password]');

		return parent::validate($array, $save);
	}

	/**
	 * Validates login information from an array, and optionally redirects
	 * after a successful login.
	 *
	 * @param  array    values to check
	 * @param  string   URI or URL to redirect to
	 * @return boolean
	 */
	public function login(array & $array, $redirect = FALSE)
	{
		$array = Validation::factory($array)
			->pre_filter('trim')
			->add_rules('username', 'required', 'length[4,32]', 'chars[a-zA-Z0-9_.]')
			->add_rules('password', 'required', 'length[5,42]');

		// Login starts out invalid
		$status = FALSE;

		if ($array->validate())
		{
			if ($this->find($array['username']))
			{
				if (Auth::instance()->login($this, $array['password']))
				{
					if (is_string($redirect)
					{
						// Redirect after a successful login
						url::redirect($redirect);
					}

					// Login is successful
					$status = TRUE;
				}
				else
				{
					$array->add_error('password', 'invalid');
				}
			}
			else
			{
				$array->add_error('username', 'invalid');
			}
		}

		return $status;
	}

	/**
	 * Tests if a username exists in the database. This can be used as a
	 * Valdidation rule.
	 *
	 * @param   mixed    id to check
	 * @return  boolean
	 */
	public function username_available($id)
	{
		return ! $this->db
			->where($this->unique_key($id), $id)
			->count_records($this->table_name);
	}

	/**
	 * Allows a model to be loaded by username or email address.
	 */
	public function unique_key($id)
	{
		if ( ! empty($id) AND is_string($id) AND ! ctype_digit($id))
		{
			return valid::email($id) ? 'email' : 'username';
		}

		return parent::unique_key($id);
	}

} // End Auth User Model