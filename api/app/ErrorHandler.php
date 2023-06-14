<?php

class ErrorHandler
{
	private static function report(Exception $e, $json = false)
	{
		if(!$json) {
			echo "<h1 style='color: red;'>ERROR</h1>";
			echo "<hr style='border-color: red; border-width: 5px;'>";
			echo "<table style='border-collapse: collapse;' border='0'>";
			echo "<tr><td>Code:&nbsp;&nbsp;&nbsp; </td><td>{$e->getCode()}</td></tr>";
			echo "<tr><td>File:&nbsp;&nbsp;&nbsp; </td><td>{$e->getFile()}</td></tr>";
			echo "<tr><td>Line:&nbsp;&nbsp;&nbsp; </td><td>{$e->getLine()}</td></tr>";
			echo "<tr><td colspan='2'><u><strong>Message:</strong></u></td></tr>";
			echo "<tr><td colspan='2'>{$e->getMessage()}</td></tr>";
			echo "</table>";
		} else {
			echo json_encode([
				'code' => $e->getCode(),
				'file' => $e->getFile(),
				'line' => $e->getLine(),
				'msg'  => $e->getMessage()
			]);
		}
	}

	public static function error(Exception $e = null, $die_app = false)
	{

		if(!is_null($e)) {
			self::report($e);
			if($die_app)
				die('<p>Program halted</p>');
		}
	}

	public static function error_json(Exception $e = null, $die_app = false)
	{
		if(!is_null($e)) {
			self::report($e, true);
			if($die_app)
				die(json_encode([
               'error' => $e->getCode(),
                  'file'  => $e->getFile(),
                  'line'  => $e->getLine(),
                  'msg'   => $e->getMessage()
               ]));
		}
	}

}