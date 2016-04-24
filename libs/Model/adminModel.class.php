<?php
	class adminModel{

		public $_table = 'AdminUser';

		function findOne_by_username($username){
			$sql = "select * from ".$this->_table." where username='$username' and isdeleted = 0";
			return DB::findOne($sql);
		}

		function count(){
			$sql = 'select count(*) from '.$this->_table;
			return DB::findResult($sql, 0, 0);
		}
	}
?>