<?php

namespace Discord;

use Discord\Guzzle;
use Discord\Exceptions\MessageFailException;

class DiscordChannel extends DiscordEntity
{
	protected $guild;
	protected $channel_id;
	protected $channel_name;
	protected $channel_private;
	protected $client;

	public function __construct($guild, $channel_id, $channel_name, $channel_private, $client)
	{
		$this->guild = $guild;
		$this->channel_id = $channel_id;
		$this->channel_name = $channel_name;
		$this->channel_private = $channel_private;
		$this->client = $client;
	}

	/**
	 * Sends a message to the channel
	 * @param string
	 * @param User ID
	 */
	public function sendMessage($message, $mention = null)
	{
		try {
			$response = Guzzle::post('channels/' . $this->channel_id . '/messages', [
				'headers' => [
					'authorization' => $this->client->token
				],
				'json' => [
					'content' => (!is_null($mention)) ? '<@' . $mention . '> ' . $message : $message
				]
			]);
		} catch (Exception $e) {
			throw new MessageFailException($e);
		}

		return $response;
	}
}