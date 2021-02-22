<?php
if (is_cli()) {
	echo "'quit'\tto exit\n'cls'\tto clear screen\n'clr'\tto clear buffer\n'\\\\\\'\tto enter/exit buffer\n";
	$__cnsle=	fopen('php://stdin', 'r');
	$__input=	'';
	$__vfr	=	'';
	$__long	=	FALSE;
	$__line	=	0;
	while (TRUE) {
		if ($__long) {
			echo str_pad($__line, 3, '.', STR_PAD_LEFT), '> ';
		} else {
			if ($__line) {
				echo str_pad($__line, 3, '.', STR_PAD_LEFT), ' Syn> ';
			} else {
				echo 'Syn> ';
			}
		}
		$__input = trim(fgets($__cnsle));
		switch (TRUE) {
			case ($__input == 'cls') :
				echo chr(27) . chr(91) . 'H' . chr(27) . chr(91) . 'J';
				break;
			case (($__input == 'exit') OR ($__input == 'quit') OR ($__input == 'quit;')) :
				exit('bye');
				break;
			case ($__input == strtolower('clr')) :
				$__vfr = '';
				$__line = 0;
				echo ">buffer cleared\n";
				break;
			case ($__input == '\\\\\\') :
				if ($__long) {
					try {
						eval($__vfr);
					} catch (Throwable $__ee) {
						echo $__ee;
					}
					echo "\n";
					$__vfr = '';
					$__long = FALSE;
					$__line = 0;
				} else {
					if (strlen($__vfr) > 1) {
						try {
							eval($__vfr);
						} catch (Throwable $__ee) {
							echo $__ee;
						}
						echo "\n";
						$__vfr = '';
						$__line = 0;
					} else {
						$__long = TRUE;
					}
				}
				break;
			case ($__input == '\\\\') :
				if ($__line) {
					try {
						eval($__vfr);
					} catch (Throwable $__ee) {
						echo $__ee;
					}
					echo "\n";
				}
				break;
			case ($__long) :
				$__vfr .= "$__input\n";
				$__line++;
				break;
			case (substr($__input, -1) == '\\') :
				$__vfr .= rtrim(trim($__input), '\\');
				$__line++;
				break;
			case (strlen($__input) == 0) :
				break;
			default :
				try {
					if (substr(trim($__input), -1) == ';') {
						eval($__input);
					} else {
						eval("$__input;");
					}
				} catch (Throwable $__ee) {
					echo $__ee;
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