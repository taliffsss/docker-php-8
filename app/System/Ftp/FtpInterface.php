<?php 
namespace Simple\Ftp;

/**
 * @package Ftp Interface Class
 * @author 	Mark Anthony Naluz <anthony.naluz15@gmail.com>
 * @copyright Jul 2018 <Mark Anthony Naluz>
 */

interface FtpInterface
{

	/**
	 * Set instance connection
	 * @return bool
	 */
	public static function getInstance();

	/**
	 * Get current Connection
	 * @return resource
	 */
	public static function getConnectionId();

	/**
	 * check whether the ftp is connected.
	 * @return bool
	 */
	public static function isConnected(): bool

	/**
	 * get the list of files.
	 * @param $dir directory
	 * @return bool | array
	 */
	public function ftpFiles(string $dir): bool;

	/**
	 * get the current working directory.
	 * @return bool | array
	 */
	public function pwd(): bool;

	/**
	 * Change current directories.
	 * @param $dir directory
	 * @return bool
	 */
	public function chdir($dir);

	/**
	 * Make directory.
	 * @param $dir directory name
	 * @return bool
	 */
	public function mkdir($dir);

	/**
	 * Make nested sub-directories.
	 * @param string $dirs
	 * @return Ftp
	 */
	public function mkdirs(string $dirs);

	/**
	 * Remove directory.
	 * @param $dir directory
	 * @return bool
	 */
	public function rmdir(string $dir);

	/**
	 * Check if file exists.
	 * @param $dir directory
	 * @return bool
	 */
	public function fileExists(string $file);

	/**
	 * Check is the dir is exists.
	 * @param $dir directory
	 * @return bool
	 */
	public function dirExists(string $dir);

	/**
	 * Get the file.
	 * @link http://php.net/manual/en/function.ftp-get.php
	 * @param $local local
	 *        $remote remote
	 *        $mode mode
	 * @return bool
	 */
	public function get($local, $remote, $mode);

	/**
	 * Rename file.
	 * @param $old old
	 *        $new naw name
	 * @return bool
	 */
	public function rename(string $old, string $new);

	/**
	 * Change premission.
	 * @param $file file
	 *        $mode mode
	 * @return bool
	 */
	public function chmod(string $file, int $mode): bool;

	/**
	 * Switch the passive mod.
	 * @param bool
	 * @return void
	 */
	public function pasv(bool $bool): void;

    /**
	 * Get passive mod.
	 * @return void
	 */
	private static function getPasv($ftpConn);

	/**
	 * Delete the files.
	 * @param $file file you want to delete
	 * @return bool
	 */
	public function delete($file);

	/**
	 * Close the FTP connection.
	 * @return void
	 */
	public function disconnect();

	/**
	 * Upload the files.
	 * @param $files number of files you want to uplaod
	 * 		  $root Server root directory or sub
	 * @return mix-data
	 */
	public function put(string $files, string $root);

}