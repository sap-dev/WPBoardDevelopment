<?php
	/**
		* @author WPBoard
		* @copyright 2013 WPBoard
		* @package com.wpboard.plugin
		* @category Core
		* @file mysql.php
	*/

	namespace Itschi\lib;

	class mysql {
		var $debug = false;
		private $connect = NULL;

		function __construct($host, $username, $pw, $database) {
			$this->connect = @mysql_connect($host, $username, $pw) or die($this->error('Couldn´t connect to database'));
			@mysql_select_db($database, $this->connect) or die($this->error("Database does not exist"));

			mysql_unbuffered_query("SET NAMES 'utf8'", $this->connect);
		}

		function error($sql)
		{
			exit('
				<title>SQL Error</title>
				<link rel="stylesheet" href="./styles/error.css" />
				<div id="fatalError">
					<div class="title"><h2>SQL-Error</h2></div>

					<div class="error MySQL">
						' . mysql_error() . '
<br><br>
'.$sql.'
					</div>
				</div>
			');
		}

		function query($sql) {

			$this->unbuffered_query('SET NAMES UTF8');

			if (($result = @mysql_query($sql, $this->connect)) === false) {
				$this->error($sql);
			}

			$this->sql = $sql;

			return $result;
		}

		function unbuffered_query($sql) {
			if (($result = @mysql_unbuffered_query($sql, $this->connect)) === false) {
				$this->error($sql);
			}

			$this->sql = $sql;

			return true;
		}

		function debug($sql) {
			echo '<hr /><pre>' . preg_replace('/	/', '', trim($sql)) . '</pre>';

			$res = @mysql_query('EXPLAIN ' . $sql);
			while ($row = @mysql_fetch_assoc($res)) {
				foreach ($row as $k => $v) {
					echo strtoupper($k) . ': <span>' . $v . '</span> ';
				}

				echo '<br />';
			}

			$this->free_result($res);
		}

		function fetch_array($result) {
			return mysql_fetch_assoc($result);
		}

		function fetch_object($result) {
			return mysql_fetch_object($result);
		}

		function num_rows($result) {
			return mysql_num_rows($result);
		}

		function result($result, $int) {
			return mysql_result($result, $int);
		}

		function insert_id() {
			return mysql_insert_id($this->connect);
		}

		function free_result($res) {
			@mysql_free_result($res);
		}

		function chars($var) {
			return mysql_real_escape_string($var, $this->connect);
		}

		function affected_rows() {
			return mysql_affected_rows($this->connect);
		}
	}
?>
