<?php

describe("phpSpec", function() {
	
	describe("describe", function() {
		it("takes 2 arguments", function() {
			expect(function() { describe(); })->to_throw("describe() takes 2 arguments");
		});
		describe("describes can be nested", function() {
			it("should be also evaluated", function() {
				pass();
			});
		});
	});

	describe("it", function() {
		it("takes 2 arguments", function() {
			expect(function() { it(); })->to_throw("it() takes 2 arguments");
		});
	});

	describe("beforeEach", function() {
		$x = "";

		beforeEach(function() use(&$x) {
			$x .= "x";
		});

		it("should be executed before the first it block", function() use(&$x) {
			expect($x)->to_equal("x");
		});

		it("should be executed before the second it block as well", function() use(&$x) {
			expect($x)->to_equal("xx");
		});

		describe("beforeEach in nested contexts", function() use (&$x) {
			$y = "";

			beforeEach(function() use(&$x, &$y) {
				$x .= "x";
				$y .= "y";
			});

			it("should execute all preceding beforeEachs", function() use(&$x, &$y) {
				expect($x)->to_equal("xxx");
				expect($y)->to_equal("y");
			});
		});
	});

	describe("assertThat", function() {
		it("should pass simple matches", function() {
			assertThat(1, is(1));
		});
		
		it("should throw an exception when matcher does not match", function(){
			expect(function() {
				assertThat(1, is(2)); 
			})->to_throw(1 . " expected but was " . 2);
		});
	});

});

?>