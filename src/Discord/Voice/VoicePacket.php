<?php

namespace Discord\Voice;

use Discord\Voice\Buffer;

class VoicePacket
{
	/**
	 * Huge thanks to Austin and Michael from JDA for these constants
	 * and audio packets.
	 *
	 * Check out their repo:
	 * https://github.com/DV8FromTheWorld/JDA
	 */
	const RTP_HEADER_BYTE_LENGTH = 12;

	const RTP_VERSION_PAD_EXTEND_INDEX = 0;
	const RTP_VERSION_PAD_EXTEND = 0x80;

	const RTP_PAYLOAD_INDEX = 1;
	const RTP_PAYLOAD_TYPE = 0x78;

	const SEQ_INDEX = 2;
	const TIMESTAMP_INDEX =  4;
	const SSRC_INDEX = 8;

	/**
	 * The voice packet buffer.
	 *
	 * @var \Discord\Voice\Buffer 
	 */
	protected $buffer;

	/**
	 * Constructs the voice packet.
	 *
	 * @param opus $opusdata 
	 * @param int $ssrc 
	 * @param int $seq 
	 * @param int $timestamp 
	 * @return void 
	 */
	public function __construct($opusdata, $ssrc, $seq, $timestamp)
	{
		$this->initBuffer($opusdata, $ssrc, $seq, $timestamp);
	}

	/**
	 * Initilizes the buffer.
	 *
	 * @param opus $opusdata 
	 * @param int $ssrc
	 * @param int $seq 
	 * @param int $timestamp 
	 * @return void 
	 */
	public function initBuffer($opusdata, $ssrc, $seq, $timestamp)
	{
		$data = (binary) $opusdata;

		$buffer = new Buffer(strlen($opusdata) + self::RTP_HEADER_BYTE_LENGTH);
		$buffer->write(self::RTP_VERSION_PAD_EXTEND, self::RTP_VERSION_PAD_EXTEND_INDEX);
		$buffer->write(self::RTP_PAYLOAD_TYPE, self::RTP_PAYLOAD_INDEX);
		$buffer->writeShort($seq, self::SEQ_INDEX);
		$buffer->writeInt($timestamp, self::TIMESTAMP_INDEX);
		$buffer->writeInt($ssrc, self::SSRC_INDEX);
		$buffer->write($opusdata, self::RTP_HEADER_BYTE_LENGTH);

		// for ($i = 0; $i < strlen($data); $i++) {
		// 	$buffer->write($data[$i], $i + 12);
		// }

		$this->buffer = $buffer;
	}

	/**
	 * Handles to string casting of object.
	 *
	 * @return string 
	 */
	public function __toString()
	{
		return (string) $this->buffer;
	}
}