phpspec
=======

php clone of the beautiful rspec. with that in mind - you want your specs to like this:

```php
describe("some awesome feature", function() {
  it("should be the hottest thing around", function() {
    expect(new CoolFeature()->run())->to_equal("best ever");
  });
});
```
and then your rocket ramps up leaving all the rest behind.

yeah, ok there's more than that.

```php
describe("check if your code throws correct", function() {
  it("should throw when something strange happens", function() {
    expect(function() { throw new Exception("BEWARE! STRANGE BEHAVIOUR!"); })->to_throw("BEWARE! STRANGE BEHAVIOUR!");
  });
});
```
les doubles:

```php
describe("test doubles", function() {
	it("should imitate needed behaviour", function() {
    $d = double("foo");
    
    $d->stub("bar")->returns("bazz");
	  
	  expect($d->bar())->to_equal("bazz");
	});
		
	it("should imitate needed behaviour depending on arguments", function() {
		$d = double("foo");
		
		$d->stub("bar", "r")->returns("read");
		$d->stub("bar", "w")->returns("write");
		
		expect($d->bar("r"))->to_equal("read");
		expect($d->bar("w"))->to_equal("write");
	});
});
```

happy coding!

  

