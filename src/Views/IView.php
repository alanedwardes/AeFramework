<?php
namespace AeFramework\Views;

interface IView
{
	public function map($params = []);
	public function code();
	public function headers();
	public function body();
}