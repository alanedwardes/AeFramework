<?php
namespace Carbo\Extensions\Admin\Views;

use Carbo\Extensions\Statistics\SchemaCurator;

class StatsView extends AdminView implements \Carbo\Views\IAuthenticated
{
	private $db = null;
	private $curator = null;

	public function __construct($template, $template_dir, $connection)
	{
		$this->db = \Doctrine\DBAL\DriverManager::getConnection($connection, new \Doctrine\DBAL\Configuration);
		$this->curator = new SchemaCurator($this->db->getSchemaManager(), @$connection['prefix']);
		
		parent::__construct($template, $template_dir);
	}

	public function response(array $template_params = [])
	{
		$visit_table = $this->curator->tableName(SchemaCurator::VISIT_TABLE);
		$address_table = $this->curator->tableName(SchemaCurator::ADDRESS_TABLE);
		$host_table = $this->curator->tableName(SchemaCurator::HOST_TABLE);
		$path_table = $this->curator->tableName(SchemaCurator::PATH_TABLE);
		$referer_table = $this->curator->tableName(SchemaCurator::REFERER_TABLE);
		$browser_table = $this->curator->tableName(SchemaCurator::BROWSER_TABLE);
		$browser_version_table = $this->curator->tableName(SchemaCurator::BROWSER_VERSION_TABLE);
		$platform_table = $this->curator->tableName(SchemaCurator::PLATFORM_TABLE);
		$session_table = $this->curator->tableName(SchemaCurator::SESSION_TABLE);
	
		$period = '4 HOUR';
		
		$sql = "SELECT total_visits.count t, unique_visits.count u, total_visits.time_interval i FROM (
			SELECT COUNT(*) count, DATE_SUB(DATE_SUB(datetime, INTERVAL MOD(MINUTE(datetime), 5) MINUTE), INTERVAL SECOND(datetime) SECOND) time_interval
			FROM {$visit_table}
			WHERE datetime > NOW() - INTERVAL {$period}
			GROUP BY time_interval
		) total_visits LEFT OUTER JOIN (
			SELECT COUNT(*) count, DATE_SUB(DATE_SUB(datetime, INTERVAL MOD(MINUTE(datetime), 5) MINUTE), INTERVAL SECOND(datetime) SECOND) time_interval
			FROM {$visit_table}
			WHERE datetime > NOW() - INTERVAL {$period}
			AND is_unique
			GROUP BY time_interval
		) unique_visits
		ON unique_visits.time_interval = total_visits.time_interval";
		
		$data = [];
		$dt = new \DateTime();
		$dt->setTimestamp(ceil(time() / 300) * 300);
		for ($i = 0; $i <= (60 * 4) / 5; $i++)
		{
			$time = $dt->sub(new \DateInterval("PT5M"))->format('H:i');
			$data[$time] = [$time, 0, 0];
		}

		$stmt = $this->db->query($sql);
		
		while ($row = $stmt->fetch())
		{
			$time = new \DateTime($row['i']);
			$data[$time->format('H:i')] = [$time->format('H:i'), (int)$row['t'], (int)$row['u']];
		}
		
		$sql = "SELECT visit.id, address, host, path, referer, browser, browser_version,
			platform, session_id, verb, port, memory, generate, is_unique, datetime, status
		FROM {$visit_table} visit
		INNER JOIN {$address_table} address ON visit.address_id = address.id
		INNER JOIN {$host_table} host ON visit.host_id = host.id
		INNER JOIN {$path_table} path ON visit.path_id = path.id
		INNER JOIN {$referer_table} referer ON visit.referer_id = referer.id
		INNER JOIN {$browser_table} browser ON visit.browser_id = browser.id
		INNER JOIN {$browser_version_table} browser_version ON visit.browser_version_id = browser_version.id
		INNER JOIN {$platform_table} platform ON visit.platform_id = platform.id
		ORDER BY visit.id DESC
		LIMIT 20";
		
		$visits = $this->db->query($sql)->fetchAll();
	
		return parent::response($template_params += [
			'archive' => array_values(array_reverse($data)),
			'visits' => $visits,
			'referers' => $this->db->query("SELECT * FROM {$referer_table} WHERE referer != '' ORDER BY id DESC LIMIT 10")->fetchAll()
		]);
	}
}