<?php

/*
 * *****************************************************
 * Copyright (C) 2015    WALTHER SMITH FRANCO OTERO <WFRANCOOTERO@GMAIL.COM> |  http://about.me/walthersmith
 * 
 * This file is part of tomador de pedidos API and mobil app.
 * 
 * tomador de pedidos API and mobil app can not be copied and/or distributed without the express
 * permission of WALTHER SMITH FRANCO OTERO
 * *****************************************************
 */

/**
 * Description of Proveedores
 *
 * @author WALTHER SMITH FRANCO OTERO
 */
class Proveedores {

	private $db = NULL;

	public function proveedoresList() {
		$Con = new Connection();
		$this->db = $Con->Connect();
		$sql = $this->db->query("select  *
			from nivelesprod where idnivel=1
			");
		if ($sql->num_rows > 0) {
			$result = array();
			while ($rlt = $sql->fetch_assoc()) {
				$result[] = $rlt;
			}

			echo $this->json($result);
		}else{
			$result = [];
			echo $this->json($result);
		}
	}
	public function lineasList() {
		$Con = new Connection();
		$this->db = $Con->Connect();
		$sql = $this->db->query("select  *
			from nivelesprod where idnivel=2
			");
		if ($sql->num_rows > 0) {
			$result = array();
			while ($rlt = $sql->fetch_assoc()) {
				$result[] = $rlt;
			}

			echo $this->json($result);
		}else{
			$result = [];
			echo $this->json($result);
		}
	}
	private function json($data) {

		if (is_array($data)) {
			if (isset($_GET['callback'])) {
				echo $_GET['callback'] . '(' . json_encode($data) . ')';
			} else {
				return json_encode($data);
			}
		}
	}

}
