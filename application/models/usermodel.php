<?php
	class Usermodel extends CI_model{
		
		public function select_single_row($table_name,$select_fields,$data_array){
			$this->db->select($select_fields);
			$this->db->where($data_array);
			$query=$this->db->get($table_name);
			//echo $this->db->last_query();
			return $query->row_array();
		}
		public function select_multiple_row($table_name,$select_fields,$data_array){
			$this->db->select($select_fields);
			$this->db->where($data_array);
			$query=$this->db->get($table_name);
			//echo $this->db->last_query();
			return $query->result_array();
		}
		public function select_all($table_name){
			$this->db->order_by('ID','desc');
			$query=$this->db->get($table_name);
			return $query->result_array();

		}
		 public function insert($table_name,$data){
			$this->db->insert($table_name,$data);
		}
		public function update($table_name,$rid,$save)
			{
				$this->db->update($table_name,$save,array('ID'=>$rid));
			}
		public function my_orders($SESSIONKEY){
			$this->db->select('shoppingcart.*');
			$this->db->from('shoppingcart');
			$this->db->where('SESSIONKEY',$SESSIONKEY);
			$query=$this->db->get();return $query->result_array();

		}
	}
?>