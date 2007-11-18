<?php defined('SYSPATH') or die('No direct script access.');

class Form_Model extends Model {

	// Action attribute
	protected $action = '';

	// Title attribute
	protected $title  = '';

	// Input data
	protected $inputs = array();

	// Validation library
	protected $validation;

	// Validation status
	protected $status;

	public function __construct()
	{
		// Uncomment the following line if you want the database loaded:
		// parent::__construct

		// Load validation
		$this->validation = new Validation();
	}

	/*
	 * Set the form action.
	 */
	public function action($uri)
	{
		$this->action = $uri;

		return $this;
	}

	/*
	 * Set the form title.
	 */
	public function title($title)
	{
		$this->title = $title;

		return $this;
	}

	/*
	 * Set input data.
	 */
	public function inputs($inputs)
	{
		$rules = array();
		foreach($inputs as $name => $data)
		{
			if (isset($data['rules']))
			{
				// Remove the rules from the input data
				$rules[$name] = arr::remove('rules', $data);

				// Reset current item
				$inputs[$name] = $data;
			}
		}

		// Set validation rules
		$this->validation->set_rules($rules);

		// Merge input data
		$this->inputs = array_merge($this->inputs, $inputs);

		return $this;
	}

	/*
	 * Run validation.
	 */
	public function validate()
	{
		if ($this->status === NULL AND ! empty($_POST))
		{
			// Run validation now
			$this->status = $this->validation->run();
		}

		return $this->status;
	}

	/*
	 * Returns the validated data.
	 */
	public function data($key = NULL)
	{
		if ($key === NULL)
		{
			return $this->validation->data_array;
		}
		else
		{
			return $this->validation->$key;
		}
	}

	/*
	 * Build the form and return it.
	 */
	public function build($template = 'kohana_form')
	{
		if ($this->status === NULL AND ! empty($_POST))
		{
			// Run validation now
			$this->status = $this->validation->run();
		}

		// Required data for the template
		$form = array
		(
			'action' => $this->action,
			'title'  => $this->title,
			'inputs' => array()
		);

		foreach($this->inputs as $name => $data)
		{
			// Error name
			$error = $name.'_error';

			// Append the value and error the the input, if it does not
			// already exist
			$data += array
			(
				'value' => $this->validation->$name,
				'error' => $this->validation->$error
			);

			$form['inputs'][$name] = $data;
		}

		return Kohana::instance()->load->view($template, $form);
	}

} // End Form_Model