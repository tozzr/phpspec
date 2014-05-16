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

function describe($description, $block) {
	$block();

	global $phpSpec;
	echo PHP_EOL . $phpSpec->passed . " passed, " . $phpSpec->failed . " failed";
	echo PHP_EOL;
}

function it($description, $block) {
	$block();
}

function expect($lambda) {
	return new ExpressionMatcher($lambda);
}

function assertThat($actual, $matcher) {
	global $phpSpec;
	if ($matcher->matches($actual))
		$phpSpec->pass();
	else {
		$phpSpec->fail($actual . ' expected but was ' . $matcher->expected);
		return false;
	}
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

class ExpressionMatcher {

	private $expression;

	function __construct($expression) {
		$this->expression = $expression;
	}

	function to_fail() {
		return $this->expression == false;
	}
}

?>
