<?php
/* PROCESS */

require_once('plugboard.php');
require_once('rotors.php');

function wrap_number($number, $length) {
	if ($number >= 0) {
		return $number % $length;
	} else {
		return wrap_number($length + $number, $length);
	}
}

$output = '';
$input_array = str_split($input);
foreach ($input_array as $input_letter) {
	if ($input_letter != ' ') {
		$letter = array_search($input_letter, $letters, true);

		if ($letter === false) {
			throw new Exception('Unsupported input');
		}

		$letter = $plugboard[$letter];

		$letter = $rotor1[wrap_number($letter + $rotor1_pos, count($rotor1))];
		$letter = $rotor2[wrap_number($letter + $rotor2_pos, count($rotor2))];
		$letter = $rotor3[wrap_number($letter + $rotor3_pos, count($rotor3))];

		$letter = $reflector[$letter];

		$letter = wrap_number(array_search($letter, $rotor3, true) - $rotor3_pos, count($rotor3));
		$letter = wrap_number(array_search($letter, $rotor2, true) - $rotor2_pos, count($rotor2));
		$letter = wrap_number(array_search($letter, $rotor1, true) - $rotor1_pos, count($rotor1));

		$letter = $plugboard[$letter];

		$output .= $letters[$letter];
		
		$rotor1_pos++;
		// Calculate rotor position
		if ($rotor1_pos >= count($rotor1)) {
			$rotor2_pos++;
		}
		if ($rotor2_pos >= count($rotor2)) {
			$rotor3_pos++;
		}
	} else {
		$output .= ' ';
	}
}