<?php


if (!function_exists('delete_all_between_and_return_original')) {
	function delete_all_between_and_return_original($beginning, $end, $string, $getOffset) {
		//echo "given string: <br>" . $string . "<br>";
		$beginningPos = strpos($string, $beginning, $getOffset);
		$endPos = strpos($string, $end, $getOffset);
		if ($beginningPos === false || $endPos === false) {
			return $string;
		}
		
		$textToDelete = substr ($string, $beginningPos, ($endPos + strlen($end))-$beginningPos );

		
		return str_replace($textToDelete, '', $string);
		//return delete_all_between ($beginning, $end, str_replace($textToDelete, '', $string)); //recursion to ensure  all occurences are replaced
		
	}
}

if (!function_exists('delete_given_div')) {
	function delete_given_div($divId) {
		echo "<script>document.getElementById($divId).remove();</script>";
	}
}


//$string = 'and [script]invalid1[/script] more [script]invalid2[/script] text';
//$out = delete_all_between_and_return_original('[script]', '[/script]', $string, 30);
//echo ($out);


?>