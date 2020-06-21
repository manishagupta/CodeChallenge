<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin extends CI_Controller {

	public function __construct(){
        parent::__construct();
        $this->load->model('usermodel');
        $this->load->library('session');
        $this->load->library("pagination");
    }
    public function index(){
        $this->load->view('admin/login');
    }
	public function login(){
        $email=$this->input->post('email');
        $password=$this->input->post('password');
        $result=$this->usermodel->select_single_row('admin','ID,AdminEmail,AdminPSW',array('AdminEmail'=>$email,'AdminPSW'=>$password));
		
        if(sizeof($result)>0){
            $this->session->set_userdata('username_admin',$result['AdminEmail']);
            $this->session->set_userdata('user_id',$result['ID']);
            //redirect('user/books');
            echo 1;
        } else {
            echo 0;
        }

    }
	 public function catalog_categories(){
        if($this->session->userdata('user_id')!=''){
            $result['data']=$this->usermodel->select_all('categories');
            $this->load->view('admin/catalog_categories',$result);
        } else {
            redirect('admin/user');
        }
    }
	public function add_category(){
        if($this->session->userdata('user_id')!=''){
                $data=array(
                    'Name'=>$this->input->post('Name_add'),
                    'Type'=>$this->input->post('Type_add'),
                    'Model'=>$this->input->post('Model_add'),
                );
                $result=$this->usermodel->insert('categories',$data);
                redirect('admin/catalog_categories');
           
        }

        else {
            redirect('admin');
        }
    }
    public function update_category(){
        if($this->session->userdata('user_id')!=''){
            $cat_id=$this->input->post('ID');
            $data=array();
            $select_value=$this->input->post('Action');
            if($select_value=='Update'){
                $data=array(
                    'Name'=>$this->input->post('Name'),
                    'Type'=>$this->input->post('Type'),
                    'Model'=>$this->input->post('Model'),
				);
                   
                $result=$this->usermodel->update('categories',$cat_id,$data);
            } else {
                $this->db->delete('categories',array('ID'=>$cat_id));
            }
            redirect('admin/catalog_categories');
        } else {
            redirect('admin');
        }
    }
	 public function catalog_products(){
        if($this->session->userdata('user_id')!=''){
            $result['cat_array']=$this->usermodel->select_all('categories');
            $result['data']=$this->usermodel->select_all('product');
           
            /* echo "<pre>";
            print_r($result);
            die; */
            $this->load->view('admin/catalog_products',$result);
        } else {
            redirect('admin/');
        }
    }
	public function add_product(){
        if($this->session->userdata('user_id')!=''){
           
                $data=array(
                    'Name'=>$this->input->post('Name_add'),
                    'Category'=>$this->input->post('Category_add'),
                    'Description'=>$this->input->post('Description_add'),
                    'Price'=>$this->input->post('Price_add'),
                    'Make'=>$this->input->post('Make_add'),
                );
                $result=$this->usermodel->insert('product',$data);
                redirect('admin/catalog_products');
            
        }

        else {
            redirect('admin/');
        }
    }
     public function update_product(){
        if($this->session->userdata('user_id')!=''){
            $cat_id=$this->input->post('ID');
			
            $data=array();
            $select_value=$this->input->post('Action');
            if($select_value=='Update'){
               $data=array(
                    'Name'=>$this->input->post('Name'),
                    'Category'=>$this->input->post('Category'),
                    'Description'=>$this->input->post('Description'),
                    'Price'=>$this->input->post('Price'),
                    'Make'=>$this->input->post('Make'),
                );
                $result=$this->usermodel->update('product',$cat_id,$data);
				
            } else {
                $this->db->delete('product',array('ID'=>$cat_id));
            }
            redirect('admin/catalog_products');
        } else {
            redirect('admin');
        }
    }
	 public function logout(){
        $this->session->unset_userdata('username_admin');
        $this->session->unset_userdata('user_id');
        redirect('admin');
    }
}
