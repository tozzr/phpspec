<?php

error_reporting(E_ALL);

describe("phpSpec", function() {
	
	describe("describe", function() {
		it("takes 2 arguments", function() {
			expect(function() { describe(); })->to_throw("describe() takes 2 arguments");
		});
		describe("foo", function() {
			it("is silly", function() {
				expect(1)->to_equal(1);
			});
		});
	});

	describe("it", function() {
		it("takes 2 arguments", function() {
			expect(function() { it(); })->to_throw("it() takes 2 arguments");
		});
	});

	describe("beforeEach", function() {
		$log = "";

		beforeEach(function() use(&$log) {
			$log .= "+";
		});

		it("should be executed before the first it block", function() use(&$log) {
			expect($log)->to_equal("+");
		});

		it("should be executed before the second it block as well", function() use(&$log) {
			expect($log)->to_equal("+");
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