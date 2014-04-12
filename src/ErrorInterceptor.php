<?php
namespace AeFramework;

class ErrorInterceptor
{
	private $handlers = array();
	
	public function __construct()
	{
		set_error_handler(function($errno, $errstr, $errfile, $errlin){
			$this->handleError($errno, $errstr, $errfile, $errlin);
		});
		
		set_exception_handler(function(\Exception $eception){
			$this->handleException($eception);
		});
	}
	
	public function addHandler(ErrorHandler $handler)
	{
		$this->handlers[] = $handler;
	}
	
	private function handleError($errno, $errstr, $errfile, $errline)
	{
		foreach ($this->handlers as $handler)
			$handler->error($errno, $errstr, $errfile, $errline);
	}
	
	private function handleException(\Exception $exception)
	{
		foreach ($this->handlers as $handler)
			$handler->exception($exception);
	}
}