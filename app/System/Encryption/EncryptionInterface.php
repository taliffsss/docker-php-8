<?php

namespace Simple\Encryption;

interface EncryptionInterface
{

	/**
	* Encrypt Data
	* key generated in a cryptographically safe way.
	* @var String $data
	* @return String
	*/
	public static function encrypt(string $data): string;

	/**
	* Decrypt Data
	* key generated in a cryptographically safe way.
	* @var string $data
	* @var String
	* @return String
	*/
	public static function decrypt(string $data, int $sha2len): string;
	
}