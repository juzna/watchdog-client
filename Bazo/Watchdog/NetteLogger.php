<?php
namespace Bazo\Watchdog;
/**
 * Description of Logger
 *
 * @author Martin
 */
class NetteLogger extends \Nette\Diagnostics\Logger
{
	private
		/** @var \Bazo\Watchdog\Client */	
		$watchdogClient
	;		
			
	public function __construct(\Bazo\Watchdog\Client $watchdogClient)
	{
		$this->watchdogClient = $watchdogClient;
		$this->directory = \Nette\Diagnostics\Debugger::$logDirectory;
	}
	
	public function log($message, $priority = self::INFO)
	{
		$res = parent::log($message, $priority);
		$levelMap = array(
			self::DEBUG => Alert::NOTICE,
			self::CRITICAL => Alert::ERROR,
			self::ERROR => Alert::ERROR,
			self::INFO => Alert::INFO,
			self::WARNING => Alert::ERROR
		);
		$level = $levelMap[$priority];
		$this->watchdogClient->logNette($message, $level);
		return $res;
	}



	public static function register(Client $watchdogClient)
	{
		\Nette\Diagnostics\Debugger::$logger = new static($watchdogClient);
	}
}