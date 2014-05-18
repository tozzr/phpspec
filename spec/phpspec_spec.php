<?php

error_reporting(E_ALL);

describe("phpSpec", function() {
	
	describe("describe", function() {
		it("takes 2 arguments", function() {
			expect(function() { describe(); })->to_throw("describe() takes 2 arguments");
		});
	});

	describe("it", function() {
		it("takes 2 arguments", function() {
			expect(function() { it(); })->to_throw("it() takes 2 arguments");
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