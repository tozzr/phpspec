<?php

class PhpSpec {
  public $passed = 0;
  public $failed = 0;
  private $log = "";
  private $groupIndex = 0;
  private $groupIdent = 0;
  private $groups = array();
  private $last = NULL;

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

  public function addSpecGroup($description, $block) {
  	$group = new SpecGroup($description);
  	$parent = $this->last;

  	if ($this->groupIdent == 0) {
  		$this->groups[$this->groupIndex++] = $group;
  		$this->last = $group;
  	}
  	else {
  		$this->last->addSpecGroup($group);
  		$this->last = $group;
  	}
  	
  	$this->groupIdent++;
  	$block();
 		$this->groupIdent--;
 		$this->last = $parent;
 	}

  public function addSpec($description, $block) {
  	$this->last->addSpec($description);
  	try {
			$block();
			$this->pass();
		}
		catch (Exception $ex) {
			$this->fail($ex->getMessage());
		}
  }

  public function addBeforeEach($block) {
  	$block();
  }

  public function execute() {
  	$this->log();
  	
  	foreach ($this->groups as $g) {
  		$this->showGroup($g, "");
  	}
  }

  private function showGroup($g, $ident) {
  	echo $ident . $g->getDescription() . PHP_EOL;
  	foreach ($g->getSpecs() as $spec)
  		echo "  " . $spec . PHP_EOL;
  	foreach ($g->getChildren() as $child)
  		$this->showGroup($child, $ident . "- ");
  }
}

class SpecGroup {
	private $description;
	private $children = array();
	private $specs = array();

	function __construct($description) {
		$this->description = $description;
	}

	public function getDescription() { 
		return $this->description;
	}

	public function addSpecGroup($group) {
		array_push($this->children, $group);
	}

	public function getChildren() {
		return $this->children;
	}

	public function addSpec($spec) {
		array_push($this->specs, $spec);
	}

	public function getSpecs() {
		return $this->specs;
	}
}

$phpSpec = new PhpSpec();

function describe() {
	if (func_num_args() != 2)
		throw new Exception("describe() takes 2 arguments");
	
	global $phpSpec;
	$phpSpec->addSpecGroup(func_get_arg(0), func_get_arg(1));
}

function it() {
	if (func_num_args() != 2)
		throw new Exception("it() takes 2 arguments");

	global $phpSpec;
	$phpSpec->addSpec(func_get_arg(0), func_get_arg(1));
}

function beforeEach($block) {
	global $phpSpec;
	$phpSpec->addBeforeEach($block);
};

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

	private $actual;

	function __construct($actual) {
		$this->actual = $actual;
	}

	function to_equal($expected) {
		if ($this->actual != $expected)
			throw new Exception("expected \"" . $expected . "\", but was \"" . $this->actual . "\"");
	}

	function to_throw($message) {
		try {
			$expression = $this->actual;
			$expression();
			throw new Exception("did not");
		}
		catch (Exception $ex) {
			if ($ex->getMessage() != $message)
				throw new Exception("expected to throw '" . $message . "', but " . $ex->getMessage());
		}
	}
}

?>
