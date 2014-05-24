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

  public function addSpecGroup($description, $block) {
  	$group = new SpecGroup($description);
  	$parent = $this->last;
  	$group->setParent($parent);

  	if ($this->groupIdent == 0)
  		$this->groups[$this->groupIndex++] = $group;
  	else
  		$this->last->addSpecGroup($group);
  
  	$this->last = $group;
  	
  	$this->groupIdent++;
  	$block();
 		$this->groupIdent--;
 		$this->last = $parent;
 	}

  public function addSpec($description, $block) {
  	$this->last->addSpec(new Spec($description, $block));
  }

  public function addBeforeEach($block) {
  	$this->last->addBeforeEach($block);
  }

  public function addAfterEach($block) {
  	$this->last->addAfterEach($block);
  }

  public function evaluate() {
  	$start = microtime();
  	foreach ($this->groups as $g) {
  		$g->evaluate($this);
  	}
  	$this->log(microtime()-$start);
  }

  private function log($time) {
  	echo $this->log . PHP_EOL;
  	echo "finished in " . round($time, 6) . " seconds" . PHP_EOL;
  	echo $this->passed . " passed, " . $this->failed . " failed" . PHP_EOL;
		echo PHP_EOL;
  }
}

class SpecGroup {
	private $description;
	private $children = array();
	private $befores = array();
	private $afters = array();
	private $specs = array();
	private $parent = NULL;

	function __construct($description) {
		$this->description = $description;
	}

	public function getDescription() { 
		return $this->description;
	}

	public function addSpecGroup(SpecGroup $group) {
		array_push($this->children, $group);
	}

	public function getChildren() {
		return $this->children;
	}

	public function addBeforeEach($block) {
		array_push($this->befores, $block);
	}

	public function addAfterEach($block) {
		array_push($this->afters, $block);
	}

	public function addSpec(Spec $spec) {
		$spec->setGroup($this);
		array_push($this->specs, $spec);
	}

	public function getSpecs() {
		return $this->specs;
	}

	public function setParent($parent) {
		$this->parent = $parent;
	}

	public function evaluate($logger) {
		foreach ($this->specs as $spec) {
			$this->executeBeforeEachs();
			$spec->evaluate($logger);
			$this->executeAfterEachs();
		}

		foreach ($this->children as $child)
			$child->evaluate($logger);
	}

	public function executeBeforeEachs() {
		if ($this->parent)
			$this->parent->executeBeforeEachs();
		foreach ($this->befores as $before) {
			$before();
		}
	}

	public function executeAfterEachs() {
		foreach ($this->afters as $after) {
			$after();
		}
		if ($this->parent)
			$this->parent->executeAfterEachs();
	}
}

class Spec {
	private $description;
	private $block;
	private $group;

	function __construct($description, $block) {
		$this->description = $description;
		$this->block = $block;
	}

	public function setGroup($group) {
		$this->group = $group;
	}

	public function evaluate($logger) {
		try {
			$block = $this->block;
			$block();
			$logger->pass();
		}
		catch (Exception $ex) {
			$logger->fail($this->group->getDescription() . "\n  " . $this->description . ":\n    " . $ex->getMessage());
		}
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

function afterEach($block) {
	global $phpSpec;
	$phpSpec->addAfterEach($block);
};

function pass() {
	global $phpSpec;
	$phpSpec->pass();
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
