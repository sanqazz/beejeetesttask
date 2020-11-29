<?php
	namespace Core;

	class Model
	{
		private static $link;
		private static $dbh;

		public function __construct()
		{
			if (!self::$link) {

				$dsn = 'mysql:host='.DB_HOST.';dbname='.DB_NAME;
				self::$dbh = new \PDO($dsn, DB_USER, DB_PASS);
			}
		}

		protected function findOne($query, $params)
		{
			$sth = self::$dbh->prepare($query);

			try {
				$sth->execute($params);
				} catch (\PDOException $e) {
				print "Error!: " . $e->getMessage() . "<br/>";
				die();
			}

			$resultObj = $sth->fetch(\PDO::FETCH_OBJ);
			$result = json_decode(json_encode($resultObj), true);

			$sth = null;
			self::$dbh = null;

			return $result;

		}

		protected function findMany($query)
		{
			$sth = self::$dbh->prepare($query);

			try {
				$sth->execute();
			} catch (\PDOException $e) {
				print "Error!: " . $e->getMessage() . "<br/>";
				die();
			}

			for ($data = []; $row = $sth->fetch(\PDO::FETCH_OBJ); $data[] = $row);
			$result = json_decode(json_encode($data), true);

			$sth = null;
			self::$dbh = null;

			return $result;
		}

		protected function countAll($query)
		{
			$sth = self::$dbh->prepare($query);

			try {
				$sth->execute();
				} catch (\PDOException $e) {
				print "Error!: " . $e->getMessage() . "<br/>";
				die();
			}

			$resultObj = $sth->fetch(\PDO::FETCH_OBJ);
			$result = json_decode(json_encode($resultObj), true);
			$result = $result['count'];

			$sth = null;
			self::$dbh = null;

			return $result;
		}


		protected function changeTask($query, $params)
		{

			$sth = self::$dbh->prepare($query);

			try {
				$sth->execute($params);
				} catch (\PDOException $e) {
				print "Error!: " . $e->getMessage() . "<br/>";
				die();
			}

			$sth = null;
			self::$dbh = null;

		}
	}
