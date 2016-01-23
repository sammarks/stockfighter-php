<?php

namespace Marks\Stockfighter\WebSocket;

use Devristo\Phpws\Messaging\WebSocketMessageInterface;
use Evenement\EventEmitterTrait;
use Marks\Stockfighter\Contracts\WebSocketContract;
use Marks\Stockfighter\Stockfighter;
use React\ChildProcess\Process;
use React\EventLoop\Timer\Timer;
use React\Promise\Deferred;
use React\Promise\Promise;
use Zend\Log\Logger;
use Zend\Log\LoggerInterface;
use Zend\Log\Writer\Stream;

class WebSocket implements WebSocketContract
{
	use EventEmitterTrait;

	const TIMEOUT = 10;

	/**
	 * The URL to the websocket endpoint.
	 * @var string
	 */
	protected $url = '';

	/**
	 * The child process instance.
	 * @var Process
	 */
	protected $process = null;

	/**
	 * The Stockfighter instance.
	 * @var Stockfighter
	 */
	protected $stockfighter = null;

	public function __construct($url, Stockfighter $stockfighter)
	{
		$this->url = $url;
		$this->stockfighter = $stockfighter;

		// Initialize the child process.
		$this->process = new Process(__DIR__ . '/../../websocket ' . $this->url);
		$this->process->on('exit', function ($exitCode, $termSignal) {
			// TODO: Handle exit.
		});
	}

	public function connect()
	{
		$this->stockfighter->loop->addTimer(0.001, function (Timer $timer) {
			$this->process->start($timer->getLoop());
			$this->process->stdout->on('data', function ($output) {

				// Get the event name and emit it.
				$space_index = strpos($output, ' ');
				$event = substr($output, 0, $space_index);

				// Add processors.
				$message = substr($output, $space_index + 1);
				$processor = 'process' . ucfirst($event);
				if (method_exists($this, $processor)) {
					if (!call_user_func_array([$this, $processor], [&$message])) {
						return;
					}
				}

				// Emit the message.
				$this->emit($event, [$this, $message]);

			});
		});
	}

	protected function processMessage(&$message)
	{
		// Get the contents.
		$contents = json_decode($message, true);
		if (!$contents) {
			return false;
		}

		// Handle the contents.
		$message = $this->handleContents($contents);
	}

	public function close()
	{
		$this->process->close();
	}

	/**
	 * Given contents returned from the websocket API, does any conversions
	 * and returns the final result.
	 *
	 * @param array $contents
	 *
	 * @return array
	 */
	protected function handleContents(array $contents)
	{
		return $contents;
	}
}
