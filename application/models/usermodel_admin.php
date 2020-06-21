<?php
	class Usermodel_admin extends CI_model{
		
		public function login($email,$password){
			
			$this->db->select('id,username');
			$this->db->where('username',$email);
			$this->db->where('password',$password);
			$query=$this->db->get('users');
			return $query->row_array();
		}
		
		public function insert($table_name,$data){
			if($this->db->insert($table_name,$data)){
				echo 1;
			} else {
				echo 0;
			}
		} public function insert_a($table_name,$data){
			$this->db->insert($table_name,$data);
		}
		public function select_single_row($table_name,$select_fields,$data_array){
			$this->db->select($select_fields);
			$this->db->where($data_array);
			$query=$this->db->get($table_name);
			return $query->row_array();
		}
		public function select_all($table_name){
			$this->db->order_by('ID','desc');
			$query=$this->db->get($table_name);
			return $query->result_array();

		}
		public function select_all_order_by_select($table_name,$order_by){
			$this->db->order_by($order_by,'desc');
			$query=$this->db->get($table_name);
			return $query->result_array();

		}
		public function select_all_single($table_name){
			$query=$this->db->get($table_name);
			return $query->row_array();

		}
		public function record_count() {
			return $this->db->count_all("amartex_shippingaddress");
		}
		public function select_all_order_by($table_name){
			$this->db->order_by('date','desc');
			$query=$this->db->get($table_name);
			return $query->row_array();

		}
		public function select_multiple_row($table_name,$data_array){
			//$this->db->select($select_fields);
			$this->db->where($data_array);
			$query=$this->db->get($table_name);
			return $query->result_array();

		}
		public function select_multiple_row_limit($table_name,$select_fields,$data_array){
			$this->db->select($select_fields);
			$this->db->where($data_array);
			$this->db->limit($limit,$offset);
			$query=$this->db->get($table_name);
			return $query->result_array();

		}
		public function select_all_shippinaddress($limit,$start){
			$query=$this->db->query("Select * from amartex_shippingaddress  where deleted=0 order by ID desc limit ".$start.",".$limit." ");
				if($query->num_rows()>0){
					return $query->result_array();
				} else {
					return false;
				}
		}
		public function select_all_shippinaddress_undelete(){
			$query=$this->db->query("Select * from amartex_shippingaddress  where deleted>0 order by ID desc limit 0,50");
			return $query->result_array();			
		}
		public function update($table_name,$rid,$save)
			{
				$this->db->update($table_name,$save,array('ID'=>$rid));
			}public function update_pro($table_name,$rid,$save)
			{
				$this->db->update($table_name,$save,array('IDKEY'=>$rid));
			}
		public function delete($table_name,$id){
			$this->db->delete('amartex_categories',array('ID'=>$cat_id));
		}		
				
		
	}
?>