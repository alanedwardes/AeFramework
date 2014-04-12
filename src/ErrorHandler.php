<?php
namespace AeFramework;

abstract class ErrorHandler
{
	public function errorString($errno)
	{
		switch ($errno)
		{
			case E_WARNING: return 'Warning'; break;
			case E_NOTICE: return'Notice'; break;
			case E_USER_ERROR: return 'User Generated Error'; break;
			case E_USER_WARNING: return 'User Generated Warning'; break;
			case E_USER_NOTICE: return 'User Generated Notice'; break;
			case E_RECOVERABLE_ERROR: return 'Recoverable Fatal Error'; break;
			case E_ALL: return 'Strict'; break;
		}
	}

	abstract function error($errno, $errstr, $errfile, $errline);
	abstract function exception(\Exception $exception);
}