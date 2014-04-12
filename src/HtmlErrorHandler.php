<?php
namespace AeFramework;

class HtmlErrorHandler extends ErrorHandler
{
	public function error($errno, $errstr, $errfile, $errline)
	{
		$this->output($this->errorString($errno), $errstr, sprintf("File: %s\n\nLine: %s", $errfile, $errline));
	}
	
	public function exception(\Exception $exception)
	{
		$this->output(sprintf('Exception: %s', $exception->getMessage()), sprintf("File: %s<br/><br/>Line: %s", $exception->getFile(), $exception->getLine()), print_r($exception->getTrace(), true));
	}
	
	private function output($title, $summary, $info)
	{
		echo $this->html($title, $summary, $info);
		exit;
	}
	
	private function html($title, $summary, $info)
	{
		http_response_code(500);
		# Create a barebones document
		return implode(array(
			"<!doctype html>",
			"<html>",
				"<head>",
					"<title>", $title, "</title>",
					"<style>",
						"* { padding:0px; margin:0px; }",
						"html { background:#ffc; }",
						"body { color:#222; font-family:'Tahoma', sans-serif; font-size: 1.2em; }",
						"h1, p, pre { padding:20px; }",
						"h1 { display:block; background:#333; color:white; padding:20px; font-weight:normal; }",
						"p { padding:20px; background:#eea; }",
					"</style>",
				"</head>",
				"<body>",
					"<h1>", $title, "</h1>",
					"<p>", $summary, "</p>",
					"<pre>", $info, "</pre>",
				"</body>",
			"</html>"
		));
	}
}