<?php

class PhpSpec {
  public $passed = 0;
  public $failed = 0;
  private $log = "";

  function pass() {
  	$this->passed++;
  	$this->log .= ".";
  }

  function fail($message) {
  	$this->failed++;
  	$this->log .= PHP_EOL . $message . PHP_EOL;
  }

  private function log() {
  	echo $this->log . PHP_EOL . PHP_EOL;

  	$count = $this->passed + $this->failed;
		echo $count . " Test" . ($count == 1 ? "" : "s") . ": ";
		echo $this->passed . " passed, " . $this->failed . " failed" . PHP_EOL;
		echo PHP_EOL;
  }

  public function addSpec($description, $block) {
  	try {
			$block();
			$this->pass();
		}
		catch (Exception $ex) {
			$this->fail($ex->getMessage());
		}
  }

  public function execute() {
  	$this->log();
  }
}

$phpSpec = new PhpSpec();
$level = 0;

function describe() {
	if (func_num_args() != 2)
		throw new Exception("describe() takes 2 arguments");
	
	$description = func_get_arg(0);
	$block = func_get_arg(1);

	global $level;
	$level++;
	
	$block();
	
	$level--;

	if ($level == 0) {
		//global $phpSpec;
		//$phpSpec->log();
	}
}

function it() {
	if (func_num_args() != 2)
		throw new Exception("it() takes 2 arguments");
	
	$description = func_get_arg(0);
	$block = func_get_arg(1);

	global $phpSpec;
	$phpSpec->addSpec($description, $block);
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
