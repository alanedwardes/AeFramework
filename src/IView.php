<?php
namespace AeFramework;

interface IView
{
	public function map($params = []);
	public function code();
	public function headers();
	public function body();
}