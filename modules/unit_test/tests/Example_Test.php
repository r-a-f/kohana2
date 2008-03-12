<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Example Test.
 *
 * $Id$
 *
 * @package    Unit_Test
 * @author     Kohana Team
 * @copyright  (c) 2007-2008 Kohana Team
 * @license    http://kohanaphp.com/license.html
 */
class Example_Test extends Unit_Test_Case {

	public function true_false_test()
	{
		$var = TRUE;
		$this
			->assert_true($var)
			->assert_true_strict($var)
			->assert_false( ! $var)
			->assert_false_strict( ! $var);
	}

	public function equal_identical_test()
	{
		$var = '5';
		$this
			->assert_equal($var, 5)
			->assert_not_equal($var, 6)
			->assert_identical($var, '5')
			->assert_not_identical($var, 5);
	}

	public function type_test()
	{
		$this
			->assert_boolean(TRUE)
			->assert_not_boolean('TRUE')
			->assert_integer(123)
			->assert_not_integer('123')
			->assert_float(1.23)
			->assert_not_float(123)
			->assert_array(array(1, 2, 3))
			->assert_not_array('array()')
			->assert_object(new stdClass)
			->assert_not_object('X')
			->assert_null(NULL)
			->assert_not_null(0)
			->assert_empty('0')
			->assert_not_empty('1');
	}

	public function pattern_test()
	{
		$var = "Kohana\n";
		$this
			->assert_pattern($var, '/^Kohana$/')
			->assert_not_pattern($var, '/^Kohana$/D');
	}

	public function debug_example_test()
	{
		foreach (array(1, 5, 6, 12, 65, 128, 9562) as $var)
		{
			// By supplying $var in the debug parameter,
			// we can see on which number this test fails.
			$this->assert_true($var < 100, $var);
		}
	}

	public function error_test()
	{
		throw new Exception;
	}

}