<?php 
namespace Simple\Ftp;

/**
 * This class can connect to ftp server
 *
 * @package Ftp Class
 * @author 	Mark Anthony Naluz <anthony.naluz15@gmail.com>
 * @copyright Jul 2018 <Mark Anthony Naluz>
 */

class Ftp {

	/**
	 * FTP passive
	 */
	private static $passive = true;
	/**
	 * FTP Connection Instance
	 */
	private static $_instance = null;
	/**
	 * FTP host
	 */
	private static $_ftp_host;
	/**
	 * FTP username
	 */
	private static $_ftp_uname;
	/**
	 * FTP password
	 */
	private static $_ftp_pass;
	/**
	 * FTP connection
	 */
	private static $_conn;
	/**
	 * FTP connection string
	 */
	private static $_connString;
	/**
	 * FTP secured
	 */
	private static $_secured;

	private function __construct($host,$username,$pass,$secured) {
		self::$_ftp_host = $host;
		self::$_ftp_uname = $username;
		self::$_ftp_pass = $pass;
		self::$_secured = $secured;
	}

	/**
	 * Establish connection
	 * @return void
	 */
	private static function connect() {

		if (self::$_secured == false) {

			$ftpConn = ftp_connect(self::$_ftp_host);
			$login = ftp_login($ftpConn, self::$_ftp_uname, self::$_ftp_pass);
			self::getPasv($ftpConn);
			self::$_conn = $login;
			self::$_connString = 'ftp://'.self::$_ftp_uname.':'.self::$_ftp_pass.'@'.self::$_ftp_host;

		} elseif(self::$_secured == true) {

			$ssl = ftp_ssl_connect(self::$_ftp_host);
			$login = ftp_login($ssl, self::$_ftp_uname, self::$_ftp_pass);
			self::getPasv($ssl);
			self::$_conn = $login;
			self::$_connString = 'sftp://'.self::$_ftp_uname.':'.self::$_ftp_pass.'@'.self::$_ftp_host;

		} else {

			self::$_conn = false;
			self::$_connString = false;

		}
	}

	/**
	 * Set instance connection
	 * @return bool
	 */
	public static function getInstance() {

		if (!isset(self::$_instance)) {

			self::$_instance = self::connect();

		}

		return self::$_instance;
	}

	/**
	 * Get current Connection
	 * @return resource
	 */
	private static function _getConnection() {

		return self::$_conn;

	}

	public function getConnectionId()
	{
		return self::_getConnection();
	}

	/**
	 * check whether the ftp is connected.
	 * @return bool
	 */
	private static function _isConnected() {

		return (is_resource($this->_getConnection())) ? true : false;

	}

	public function isConnected()
	{
		return self::_isConnected();
	}

	/**
	 * get the list of files.
	 * @param $dir directory
	 * @return bool | array
	 */
	public function ftpFiles($dir) {

		return (self::_isConnected() === true) ? ftp_nlist(self::_getConnection(), $dir) : false;

	}

	/**
	 * get the current working directory.
	 * @return bool | array
	 */
	public function pwd() {

		return (self::_isConnected() === true) ? ftp_pwd(self::_getConnection()) : false;

	}

	/**
	 * Change current directories.
	 * @param $dir directory
	 * @return bool
	 */
	public function chdir($dir) {

		return (self::_isConnected() === true) ? ftp_chdir(self::_getConnection(), $dir) : false;

	}

	/**
	 * Make directory.
	 * @param $dir directory name
	 * @return bool
	 */
	public function mkdir($dir) {

		return (self::_isConnected() === true) ? ftp_mkdir(self::_getConnection(), $dir) : false;

	}

	/**
	 * Make nested sub-directories.
	 * @param string $dirs
	 * @return Ftp
	 */
	public function mkdirs($dirs) {

		if (substr($dirs, 0, 1) == '/') {
			$dirs = substr($dirs, 1);
		}

		if (substr($dirs, -1) == '/') {
			$dirs = substr($dirs, 0, -1);
		}

		$dirs = explode('/', $dirs);
		$curDir = self::$_connString;

		foreach ($dirs as $dir) {
			$curDir .= '/'.$dir;
			if (!is_dir($curDir)) {
				$this->mkdir($dir);
			}

			$this->chdir($dir);
		}

		return $this;
	}

	/**
	 * Remove directory.
	 * @param $dir directory
	 * @return bool
	 */
	public function rmdir($dir) {

		return (self::_isConnected() === true) ? ftp_rmdir(self::_getConnection(), $dir) : false;

	}

	/**
	 * Check if file exists.
	 * @param $dir directory
	 * @return bool
	 */
	public function fileExists($file) {

		return (self::_isConnected() === true) ? ftp_rmdir(self::$_connString.$file) : false;

	}

	/**
	 * Check is the dir is exists.
	 * @param $dir directory
	 * @return bool
	 */
	public function dirExists($dir) {

		return (self::_isConnected() === true) ? ftp_rmdir(self::$_connString.$dir) : false;

	}

	/**
	 * Get the file.
	 * @link http://php.net/manual/en/function.ftp-get.php
	 * @param $local local
	 *        $remote remote
	 *        $mode mode
	 * @return bool
	 */
	public function get($local, $remote, $mode = FTP_BINARY) {

		return (self::_isConnected() === true) ? ftp_get(self::_getConnection(), $local, $remote, $mode) : false;

	}

	/**
	 * Rename file.
	 * @param $old old
	 *        $new naw name
	 * @return bool
	 */
	public function rename($old, $new) {

		return (self::_isConnected() === true) ? ftp_rename(self::_getConnection(), $old, $new) : false;

	}

	/**
	 * Change premission.
	 * @param $file file
	 *        $mode mode
	 * @return bool
	 */
	public function chmod($file, $mode) {

		return (self::_isConnected() === true) ? ftp_chmod(self::_getConnection(), $mode, $file) : false;

	}

	/**
	 * Switch the passive mod.
	 * @param bool
	 * @return void
	 */
	public function pasv($bool) {

		self::$passive = $bool;

    }

    /**
	 * Get passive mod.
	 * @return void
	 */
	private static function getPasv($ftpConn) {
		
        return (self::_isConnected() === true) ? ftp_pasv($ftpConn, self::$passive) : false;

    }

	/**
	 * Delete the files.
	 * @param $file file you want to delete
	 * @return bool
	 */
	public function delete($file) {

		return (self::_isConnected() === true) ? ftp_delete(self::_getConnection(), $file) : false;

	}

	/**
	 * Close the FTP connection.
	 * @return void
	 */
	public function disconnect() {
		if(self::_isConnected()) {
			ftp_close(self::$_conn);
		}
	}

	/**
	 * Upload the files.
	 * @param $files number of files you want to uplaod
	 * 		  $root Server root directory or sub
	 * @return mix-data
	 */
	public function put($files, $root = 'public_html') {

		if (self::_isConnected() === true) {
			foreach ($files as $key => $value) {
				ftp_put(self::_getConnection(), $server_root.'/'.$value, $value, FTP_ASCII);
			}
		} else {
			return false;
		}
	}

}