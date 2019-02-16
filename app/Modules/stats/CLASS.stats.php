<?php

	class stats
	{
		public function __construct()
		{
			$this->conn = $GLOBALS['conn'];
		}

		public function get_count($type, $table, $where = '1=1')
		{
			$sql = 'SELECT count('.$type.') FROM '.$table.' WHERE '.$where;
			return $this->conn->GetOne($sql);
		}

		public function get_sum($type, $table, $where = '1=1', $debug = false)
		{
			$sql = 'SELECT sum('.$type.') FROM '.$table.' WHERE '.$where;
			if($debug == true) echo $sql;
			return $this->conn->GetOne($sql);
		}
	}
