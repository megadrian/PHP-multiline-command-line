<?php
printf ("'exit();' or 'die();' to quit\n'cls' to clear screen\n\n");
$console= fopen('php://stdin', 'r');
$input	= '';
while (TRUE) {
	echo 'Syn> ';
	$input = trim(fgets($console));
	if ($input == strtolower('cls')) {
		echo chr(27).chr(91).'H'.chr(27).chr(91).'J';
	} else {
		try {
			$x = eval($input);
		} catch (Throwable $e) {
			echo $e;
		}
		echo "\n";
	}
}