<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Webservice extends CI_Controller {

	public function __construct(){
        parent::__construct();
        $this->load->model('usermodel');
    }
	/*	
	Function : login
	Purpose : logging the user in
	Params : email,password
	URL: http://localhost/CodeChallenge/Webservice/login
	*/
	public function login(){
		$email=$this->input->post('email');
		$password=$this->input->post('password');
		$res['result']        = FALSE;
	
		if (empty($email) ||  empty($password)) {
			$res['error'] = 'Email, password should not be empty.';
			echo json_encode($res);
			die();
		}
   
		$data=$this->usermodel->select_single_row('users',['username','email','id'],array('email'=>$email,'password'=>md5($password)));
		if(sizeof($data)>0){	
			$this->session->set_userdata('id',$data['id']);
		
			$res['data']=$data;
			$res['result']        = True;
			$res['message']='Login successfully.';
			echo json_encode($res);
			die();
		} else{
			 $res['error'] = 'Invalid login details.';
			 echo json_encode($res);
			 die();
		}
	}
	public function logout(){
		$this->session->unset_userdata('id');
		redirect('login');
	}	
	
	/*	
	Function : getAllStates
	Purpose : To get all the states
	Params : email,password
	URL: http://localhost/CodeChallenge/Webservice/getAllStates
	*/
	
	public function getAllStates(){
		$states=$this->usermodel->select_multiple_row('states',['id','name','alias','state_code'],['active'=>1]);
		if(empty($states)){
			$res['result']        = FALSE;
			$res['message'] = 'No state exist.';
			echo json_encode($res);	
			
		}else {
			$res['result']        = True;
			$res['data']        = $states;
			$res['message'] = 'All States.';
			echo json_encode($res);	
			
		}
		
	}
	
	/*	
	Function : postState
	Params: country_id,name,alias,state_code
	Purpose : Adding States
	URL: http://localhost/CodeChallenge/Webservice/postState
	*/
	public function postState(){
		$country_id=$this->input->post('country_id');
		$name=$this->input->post('name');	
		$alias=$this->input->post('alias');	
		$state_code=$this->input->post('state_code');	
		
		$res['result']=FALSE;
		if (empty($country_id) || empty($name) || empty($alias) || empty($state_code)   ) {
			$res['message'] = 'Please fill the required fields.';
			echo json_encode($res);
			die();
		}
		if ($country_id <>  sanitizeInt($country_id) ) {
			$res['message'] = 'Please enter valid values.';
			echo json_encode($res);
			die();
		}
		$data=$this->usermodel->select_single_row('states','*',array('name'=>$name,'country_id'=>$country_id));
		if (!empty($data)   ) {
			$res['message'] = 'State already exists.';
			echo json_encode($res);
			die();
		}
		$data_pro=array(
			'country_id'=>$country_id,
			'name'=>$name,
			'alias'=>$alias,
			'state_code'=>$state_code,
		);
		$this->db->insert('states',$data_pro);
		$res['result']        = TRUE;
		$res['message'] = "Added successfully.";
		echo json_encode($res);
		die();
	}
	
	/*	
	Function : getAllDistricts
	Purpose : list all districts of a state
	Params: state_id
	URL: http://localhost/CodeChallenge/Webservice/getAllCategoryProducts
	*/
	function getAllDistricts(){
		$state_id=$this->input->post('state_id');
		$res['result']        = FALSE;
	
		if (empty($state_id)) {
			$res['error'] = 'state_id shouldn\'t be empty';
			echo json_encode($res);
			die();
		}
   
		$data=$this->usermodel->select_multiple_row('districts',['id','name','alias'],array('state_id'=>$state_id));
		
		if(sizeof($data)>0){
			
			$res['districts']=$data;
			$res['result']        = True;
			$res['message']='All districts';
			echo json_encode($res);
			die();
		} else{

			 $res['error'] = 'No district found.';
			 echo json_encode($res);
			 die();

		}
	}
	
	/*	
	Function : postDistrict
	Params: country_id,name,alias,state_code
	Purpose : Adding States
	URL: http://localhost/CodeChallenge/Webservice/postState
	*/
	public function postDistrict(){
		$country_id=$this->input->post('country_id');
		$state_id=$this->input->post('state_id');
		$name=$this->input->post('name');	
		$alias=$this->input->post('alias');	
		$state_code=$this->input->post('state_code');	
		
		$res['result']=FALSE;
		if (empty($country_id) ||empty($state_id) || empty($name) || empty($alias) || empty($state_code)   ) {
			$res['message'] = 'Please fill the required fields.';
			echo json_encode($res);
			die();
		}
		if ($country_id <>  sanitizeInt($country_id) ) {
			$res['message'] = 'Please enter valid country id.';
			echo json_encode($res);
			die();
		}
		if ($state_id <>  sanitizeInt($state_id) ) {
			$res['message'] = 'Please enter valid state id.';
			echo json_encode($res);
			die();
		}
		$data=$this->usermodel->select_single_row('states',['id'],array('id'=>$state_id));
		if (empty($data)   ) {
			$res['message'] = "State doesn't exists.";
			echo json_encode($res);
			die();
		}
		$data=$this->usermodel->select_single_row('districts',['id'],array('name'=>$name,'state_id'=>$state_id));
		if (!empty($data)   ) {
			$res['message'] = 'Districts already exists.';
			echo json_encode($res);
			die();
		}
		$data_pro=array(
			'country_id'=>$country_id,
			'state_id'=>$state_id,
			'name'=>sanitizeString($name),
			'alias'=>$alias,
		);
		$this->db->insert('districts',$data_pro);
		$res['result']        = TRUE;
		$res['message'] = "Added successfully.";
		echo json_encode($res);
		die();
	}
	
	/*	
	Function : getChild
	Params: id
	Purpose : getting information of a child
	URL: http://localhost/CodeChallenge/Webservice/getChild
	*/
	function getChild(){
		$id=$this->input->post('id');
		$res['result']        = FALSE;
	
		if (empty($id)) {
			$res['error'] = 'Child id shouldn\'t be empty';
			echo json_encode($res);
			die();
		}
   
		$data=$this->usermodel->select_single_row('users',['id','username','bday','father_name','mother_name','email'],array('id'=>$id));
		if(!empty($data)){
				$res['cat_products']=$data;
				$res['result']        = True;
				$res['message']='Child info.';
				echo json_encode($res);
				die();
		} else {
			 $res['error'] = 'No child found with this id.';
			 echo json_encode($res);
			 die();

		}
	}
	/*	
	Function : postChild
	Params: username,email,first_name,gender,password
	Purpose : Adding States
	URL: http://localhost/CodeChallenge/Webservice/postState
	*/
	public function postChild(){
		$username=$this->input->post('username');
		$email=$this->input->post('email');
		$password=$this->input->post('password');	
		$first_name=$this->input->post('first_name');	
		$gender=$this->input->post('gender');	
		
		$res['result']=FALSE;
		if (empty($username) ||empty($email) || empty($password) || empty($first_name) || empty($gender)   ) {
			$res['message'] = 'Please fill the required fields.';
			echo json_encode($res);
			die();
		}
		
		$data=$this->usermodel->select_single_row('users',['id'],array('email'=>$email));
		if (empty($data)   ) {
			$res['message'] = "Email already exists.";
			echo json_encode($res);
			die();
		}
		
		$data_pro=array(
			'username'=>$username,
			'email'=>$email,
			'password'=>md5($password),
			'first_name'=>$first_name,
			'gender'=>$gender,
		);
		$this->db->insert('users',$data_pro);
		$res['result']        = TRUE;
		$res['message'] = "Added successfully.";
		echo json_encode($res);
		die();
	}
	
}
