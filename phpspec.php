<?php

class PhpSpec {
  public $passed = 0;
  public $failed = 0;
  
  function pass() {
  	$this->passed++;
  	echo '.';
  }

  function fail($message) {
  	$this->failed++;
  	echo PHP_EOL . $message . PHP_EOL;
  }
}

$phpSpec = new PhpSpec();
$level = -1;

function describe($description, $block) {
	global $level;
	$l = ++$level;
	
	$block();
	
	if ($l == 0) {
		global $phpSpec;
		$count = $phpSpec->passed + $phpSpec->failed;
		echo PHP_EOL . PHP_EOL . $count . " Test" . ($count == 1 ? "" : "s") . ": ";
		echo $phpSpec->passed . " passed, " . $phpSpec->failed . " failed";
		echo PHP_EOL;
	}
}

function it() {
	if (func_num_args() != 2)
		throw new Exception("it() takes 2 arguments");
	
	$description = func_get_arg(0);
	$block = func_get_arg(1);

	global $phpSpec;
	try {
		$block();
		$phpSpec->pass();
	}
	catch (Exception $ex) {
		$phpSpec->fail($ex->getMessage());
	}
}

function assertThat($actual, $matcher) {
	if (!$matcher->matches($actual))
		throw new Exception($actual . ' expected but was ' . $matcher->expected);
}

function is($expected) {
	return new IsMatcher($expected);
}

class IsMatcher {

	public $expected; 

	function __construct($expected) {
		$this->expected = $expected;
	}

	function matches($actual) {
		return $actual === $this->expected;
	}
}

function expect($expression) {
	return new Expectation($expression);
}

class Expectation {

	private $expression;

	function __construct($expression) {
		$this->expression = $expression;
	}

	function to_throw($message) {
		try {
			$exp = $this->expression;
			$exp();
			throw new Exception("did not");
		}
		catch (Exception $ex) {
			if ($ex->getMessage() != $message)
				throw new Exception("expected to throw '" . $message . "', but " . $ex->getMessage());
		}
	}
}

?>
