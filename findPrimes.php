<?php

	function find_prime($input)
	{
		$primes = [];
		$is_prime;
		for ($count = 2; $count <= $input; $count++) 
		{
			$is_prime = TRUE;
			for ($i = 2; $i <= $count; $i++)
			{
				if ($i == $count) {
					break;
				}	
				if ($count % $i == 0) {
					$is_prime = FALSE;
					break;
				}
			}
			if ($is_prime) {
				array_push($primes, $count);
			}

		}
		return $primes;
	}
	
	function tester()
	{
		echo "Test 1: find_prime(10) <br>";
		$result = find_prime(10);
		$expected = [2, 3, 5, 7];
		echo "Expected: " . implode(', ', $expected) . "<br> Results: " . implode(', ', $result) . "<br>";
		if ($result == $expected) {echo "Test Passed <br> <br>";}
		else {echo "Test Failed <br> <br>";}
		
		echo "Test 1: find_prime(23) <br>";
		$result = find_prime(23);
		$expected = [2, 3, 5, 7, 11, 13, 17, 19, 23];
		echo "Expected: " . implode(', ', $expected) . "<br> Results: " . implode(', ', $result) . "<br>";
		if ($result == $expected) {echo "Test Passed <br> <br>";}
		else {echo "Test Failed <br> <br>";}

		echo "Test 1: find_prime(0) <br>";
		$result = find_prime(0);
		$expected = [];
		echo "Expected: " . implode(', ', $expected) . "<br> Results: " . implode(', ', $result) . "<br>";
		if ($result == $expected) {echo "Test Passed <br> <br>";}
		else {echo "Test Failed <br> <br>";}
	}
	
	tester();
?>