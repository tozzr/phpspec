<?php

error_reporting(E_ALL);

chdir(dirname(__FILE__));
include "../phpspec.php";

describe ("assertThat", function() {
	it ("should pass simple matches", function() {
		assertThat(1, is(1));
	});
	it ("fails when it should", function(){
		expect( function () {
			assertThat(1, is(2)); 
		})->to_fail();
	});
});

?>