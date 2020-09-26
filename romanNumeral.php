<?php

	function String_Evaluation($lettr) {
		//7 letters in roman numeral alphabet
		//check input letter and return its roman value, or if not in alphabet, 
		//return -1 to signify invalidity.
		if ($lettr == 'I') {
			return 1;
		}
		if ($lettr == 'V') {
			return 5;
		}
		if ($lettr == 'X') {
			return 10;
		}
		if ($lettr == 'L') {
			return 50;
		}
		if ($lettr == 'C') {
			return 100;
		}
		if ($lettr == 'D') {
			return 500;
		}
		if ($lettr == 'M') {
			return 1000;
		}
		return -1; //invalid input: not in Roman Numeral form
	}
	
	function Roman_Conversion($input) {
		$result = 0; //defaults to 0
		
		//loop through every character in input string
		for ($i=0; $i<strlen($input); $i++) {
			$temp1 = String_Evaluation($input[$i]);
			if ($temp1 == -1) {
				return "Invalid Input";
				break;
			}
			if ($i+1 < strlen($input)) { //check for i+1 
				$temp2 = String_Evaluation($input[$i+1]);
				if ($temp2 == -1) {
					return "Invalid Input";
					break;
				}
				//compare temp1 and 2 
				/*
				LOGIC: numbers ordered highest to lowest; only one lesser digit
				ever comes before a higher one to signify one place less than 
				its succeeding.
				*/
				if($temp1 >= $temp2) {
					$result = $result+$temp1;
				}
				else {
					$result = $result +$temp2 -$temp1;
					$i++; //move cursor up 1 to avoid repeating operations on this digit
				}
				
			}
			else {$result += $temp1; }//need i++ here?
			
		}
		return $result;
	}
	
	function Tester() {
		echo "<b>Test 1:</b> VI --> ", Roman_Conversion("VI"), "<br>";
		echo "<b>Test 2:</b> IV --> ", Roman_Conversion("IV"), "<br>";
		echo "<b>Test 3:</b> MCMXC --> ", Roman_Conversion("MCMXC"), "<br>";
		echo "<b>Test 4:</b> IX --> ", Roman_Conversion("IX"), "<br>";
		echo "<b>Test 5:</b> rfdgd --> ", Roman_Conversion("rfdgd"), "<br>";

	}
	
	Tester();

?>