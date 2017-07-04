<?php
	session_start();

	$ranStr = md5(microtime());
	$ranStr = hexdec( crc32($ranStr));

	$ranStr = substr($ranStr, 0, 6);

	$_SESSION['cap_code'] = $ranStr;
