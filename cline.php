<?php
if (is_cli()) {
	echo "'quit'\tto exit\n'cls'\tto clear screen\n'clr'\tto clear buffer\n'\\\\\\'\tto enter/exit buffer\n";
	$console=	fopen('php://stdin', 'r');
	$input	=	'';
	$buffer	=	'';
	$long	=	FALSE;
	$line	=	0;
	while (TRUE) {
		if ($long) {
			echo str_pad($line, 3, '.', STR_PAD_LEFT), '> ';
		} else {
			if ($line) {
				echo str_pad($line, 3, '.', STR_PAD_LEFT), ' Syn> ';
			} else {
				echo 'Syn> ';
			}
		}
		$input = trim(fgets($console));
		switch (TRUE) {
			case ($input == 'cls') :
				echo chr(27) . chr(91) . 'H' . chr(27) . chr(91) . 'J';
				break;
			case (($input == 'exit') OR ($input == 'quit') OR ($input == 'quit;')) :
				exit('bye');
				break;
			case ($input == strtolower('clr')) :
				$buffer = '';
				$line = 0;
				echo ">buffer cleared\n";
				break;
			case ($input == '\\\\\\') :
				if ($long) {
					try {
						eval($buffer);
					} catch (Throwable $e) {
						echo $e;
					}
					echo "\n";
					$buffer = '';
					$long = FALSE;
					$line = 0;
				} else {
					if (strlen($buffer) > 1) {
						try {
							eval($buffer);
						} catch (Throwable $e) {
							echo $e;
						}
						echo "\n";
						$buffer = '';
						$line = 0;
					} else {
						$long = TRUE;
					}
				}
				break;
			case ($input == '\\\\') :
				if ($line) {
					try {
						eval($buffer);
					} catch (Throwable $e) {
						echo $e;
					}
					echo "\n";
				}
				break;
			case ($long) :
				$buffer .= trim($input);
				$line++;
				break;
			case (substr($input, -1) == '\\') :
				$buffer .= rtrim(trim($input), '\\');
				$line++;
				break;
			case (strlen($input) == 0) :
				break;
			default :
				try {
					if (substr(trim($input), -1) == ';') {
						eval($input);
					} else {
						eval("$input;");
					}
				} catch (Throwable $e) {
					echo $e;
				}
				echo "\n";
		}
	}
} else {
	echo 'cli only';
}

function is_cli() {
	if (defined('STDIN')) {
		return TRUE;
	}
	if (empty($_SERVER['REMOTE_ADDR']) AND ! isset($_SERVER['HTTP_USER_AGENT']) AND count($_SERVER['argv']) > 0) {
		return TRUE;
	} 
	return FALSE;
}