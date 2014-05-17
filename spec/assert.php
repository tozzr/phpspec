<?php

error_reporting(E_ALL);

chdir(dirname(__FILE__));
include "../phpspec.php";

describe("phpSpec", function() {
	describe("it", function() {
		it("takes two arguments", function() {
			expect(function() { it(); })->to_throw("it() takes 2 arguments");
		});
	});
	
	it("should pass simple matches", function() {
		assertThat(1, is(1));
	});
	
	it("should throw an exception when matcher does not match", function(){
		expect(function() {
			assertThat(1, is(2)); 
		})->to_throw(1 . " expected but was " . 2);
	});
});

?>