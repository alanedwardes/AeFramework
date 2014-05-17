<?php
namespace Carbo\Extensions\Admin\Views;

class AdminView extends \Carbo\Views\TemplateView
{
	private function findGitRevision($path)
	{
		$head = @file_get_contents(sprintf('%s/HEAD', $path));
		if ($head)
		{
			preg_match('/^ref: (?P<ref>.*)$/', $head, $matches);
			if (isset($matches['ref']) and $matches['ref'])
			{
				$ref = @file_get_contents(sprintf('%s/%s', $path, $matches['ref']));
				if ($ref)
				{
					return trim($ref);
				}
			}
		}
		
		return false;
	}
	
	public function findRevision()
	{
		# Save the current working directory
		$last_working_directory = getcwd();
		
		# Change to the current directory
		chdir(__DIR__);
		
		# Move up 4 directories (to carbo)
		chdir('../../../..');
		
		# Store the carbo directory
		$carbo_directory = getcwd();
		
		# Restore current working directory
		chdir($last_working_directory);
		
		# Guess the git dir
		$git_directory = sprintf('%s/.git', $carbo_directory);
		
		# Check if we found a git folder
		if (is_dir($git_directory))
			return $this->findGitRevision($git_directory);
		
		return false;
	}
	
	public function response($template_params = [])
	{
		return parent::response($template_params += [
			'repository' => 'https://github.com/alanedwardes/carbo',
			'author' => 'http://alanedwardes.com/'
		]);
	}
}