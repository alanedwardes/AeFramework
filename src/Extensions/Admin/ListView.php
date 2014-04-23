<?php
namespace AeFramework\Extensions\Admin;

class ListView extends AdminView
{
	public function __construct()
	{
		parent::__construct(\AeFramework\Util::joinPath(__DIR__, 'templates/list.html'));
	}
	
	public function body()
	{
		$user_order = @$_GET['order'];
		$user_direction = @$_GET['direction'];
		$user_limit = @$_GET['limit'];
		$user_start = @$_GET['start'];
		
		$direction = ($user_direction == 'desc') ? 'desc' : 'asc';
		$order = reset($this->table->columns)->name;
		
		foreach ($this->table->columns as $column)
		{
			if ($column->name == $user_order)
			{
				$order = $column->name;
			}
		}
		
		$limit = min(max(intval($user_limit), 20), 500);
		$start = max(intval($user_start), 0);
		
		return parent::body([
			'rows' => $this->da->select($this->table, "1 ORDER BY {$order} {$direction} LIMIT {$start}, {$limit}"),
			'order' => $order,
			'direction' => $direction,
			'limit' => $limit,
			'start' => $start,
		]);
	}
}