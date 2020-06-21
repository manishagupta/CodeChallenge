<?php
class User extends CI_controller{
    public function __construct(){
        parent::__construct();
        $this->load->model('usermodel_admin');
        $this->load->model('usermodel');
        $this->load->library('session');
        $this->load->library("pagination");
    }
    public function index(){
        $this->load->view('admin/login');
    }
	
	
    public function login(){
        $email=$this->input->post('email');
        $password=md5($this->input->post('password'));
        $result=$this->usermodel_admin->login($email,$password);
        if(sizeof($result)>0){
            $this->session->set_userdata('username_admin',$result['username']);
            $this->session->set_userdata('user_id',$result['id']);
            echo 1;
        } else {
            echo 0;
        }

    }
    
    public function dashboard(){
        //echo $this->session->userdata('user_id');
        if($this->session->userdata('user_id')!=''){
			$user_id=$this->session->userdata('user_id');
            $data['data']=$this->usermodel->select_single_row('users',['first_name','last_name','email','id','organization','desgnation','photo'],array('id'=>$user_id));
			$this->load->view('admin/home',$data);
        } else {
            redirect('user/login');
        }
    }
    public function success($cart_ID){
        $result['data']=$this->usermodel->select_single_row_with_where('amartex_shippingaddress','*',array('cart_ID'=>$cart_ID));
        $result['data1']=$this->usermodel->select_multiple_row_with_wherepr('amartex_shoppingcart','*',array('SESSIONKEY'=>$cart_ID,'PaymentStatus'=>'due'),'ID');

        $this->load->view('admin/success',$result);
    }
    public function order_edit($cart_ID){
        $result['data']=$this->usermodel->select_multiple_row_with_wherepr('amartex_shippingaddress','*',array('cart_ID'=>$cart_ID),'ID');
        /* echo "<pre>";
        print_r($result['data']);
        die; */
        $this->load->view('admin/order_edit',$result);
    }
    public function logout(){
        $this->session->unset_userdata('username_admin');
        $this->session->unset_userdata('user_id');
        $this->session->unset_userdata('rights');

        redirect('admin/user');
    }
    public function catalog_categories(){
        if($this->session->userdata('user_id')!=''){
            $result['data']=$this->usermodel_admin->select_all_order_by_select('amartex_categories','id');
            $this->load->view('admin/catalog_categories',$result);
        } else {
            redirect('admin/user');
        }
    }
    public function catalog_brands(){
        if($this->session->userdata('user_id')!=''){
            $result['data']=$this->usermodel_admin->select_all('amartex_brands');
            $this->load->view('admin/catalog_brands',$result);
        } else {
            redirect('admin/user');
        }
    }public function catalog_products_edit($IDKEY,$ref){
    if($this->session->userdata('user_id')!=''){
        $data_cat=$this->usermodel->select_multiple_row_without_where('amartex_categories','*');
        $data_options=$this->usermodel->select_multiple_row_with_wherepr('amartex_options','*',array('ReferenceN'=>$ref),'ID');
        $result['cat_array']=$data_cat;
        $result['data_options']=$data_options;

        $result['data']=$this->usermodel_admin->select_single_row('amartex_product','*',array('IDKEY'=>$IDKEY));

        $this->load->view('admin/catalog_products_edit',$result);
    } else {
        redirect('admin/user');
    }
}
    public function add_brands(){
        if($this->session->userdata('user_id')!=''){
            $config['upload_path'] = 'media/brands/';
            $config['allowed_types'] = 'gif|jpg|png|jpeg';
            /* $config['max_size']	= '100';
            $config['max_width']  = '1024';
            $config['max_height']  = '768';
             */
            $this->load->library('upload', $config);

            $this->load->library('upload', $config);
            if ( ! $this->upload->do_upload('file')){
                $error = array('error' => $this->upload->display_errors());
                //$error = array('error' => $this->upload->display_errors());
                $this->session->set_flashdata('profile',$error['error']);

            } else {
                $data_file = array('upload_data' => $this->upload->data());
                $data=array(
                    'ImageURL1'=> $data_file['upload_data']['file_name'],
                    'brand'=> $this->input->post('brand'),
                    'Priority'=> $this->input->post('Priority'),
                    //'salary'=> $this->input->post('salary')
                );
                $this->usermodel_admin->insert('amartex_brands',$data);

            }


            redirect('admin/user/catalog_brands');


        } else {
            redirect('admin/user');
        }

    }
    public function update_brands(){
        if($this->session->userdata('user_id')!=''){
            $config['upload_path'] = 'media/brands/';
            $config['allowed_types'] = 'gif|jpg|png|jpeg';
            /* $config['max_size']	= '100';
            $config['max_width']  = '1024';
            $config['max_height']  = '768';
             */
            $this->load->library('upload', $config);
            $data=array();
            $cat_id=$this->input->post('ID');
            //echo 'cart_ID:::::'.$cat_id;die;
            $select_value=$this->input->post('Action');
            if($this->input->post('DelImage1')==1){
                $ImageURL1=$this->input->post('ImageURL1');//'ImageURL1'=> $data_file['upload_data']['file_name'],

                if(file_exists(FCPATH.'/media/brands/'.$ImageURL1)){

                    unlink(FCPATH.'/'.$ImageURL1);
                    $this->usermodel_admin->update('amartex_brands',$cat_id,array('ImageURL1'=>''));
                }
                redirect('admin/user/catalog_brands');
            } else	if($select_value=='Update'){
                $this->load->library('upload', $config);
                if($_FILES['file']['name']!=''){
                    if ( ! $this->upload->do_upload('file')){
                        $error = array('error' => $this->upload->display_errors());
                        print_r($error);
                    } else {
                        $data_file = array('upload_data' => $this->upload->data());

                        $data=array(
                            'ImageURL1'=> $data_file['upload_data']['file_name'],
                            'brand'=> $this->input->post('brand'),
                            'Priority'=> $this->input->post('Priority'),
                            'Active'=> $this->input->post('Active'),
                            //'salary'=> $this->input->post('salary')
                        );

                    }
                } else {
                    $data=array(
                        //'ImageURL1'=> $data_file['upload_data']['file_name'],
                        'brand'=> $this->input->post('brand'),
                        'Priority'=> $this->input->post('Priority'),
                        'Active'=> $this->input->post('Active')
                    );
                }
                $this->usermodel_admin->update('amartex_brands',$cat_id,$data);
                redirect('admin/user/catalog_brands');
            }
            else if($select_value=='Delete'){
                $this->db->query("delete  from amartex_brands where ID='".$cat_id."' ");
                redirect('admin/user/catalog_brands');
            }

        } else {
            redirect('admin/user');
        }
        redirect('admin/user/catalog_brands');
    }
    public function delete_order($order_id){
        if($this->session->userdata('user_id')!=''){
            $time=time();
            $this->db->query("Update amartex_shippingaddress set deleted=\"$time\" where cart_ID=".$order_id." ");
            redirect('admin/user/dashboard');
        } else {
            redirect('admin/user');
        }
    }
    public function undelete_order($order_id){
        //$time=time();
        if($this->session->userdata('user_id')!=''){
            $this->db->query("Update amartex_shippingaddress set deleted=0 where cart_ID=".$order_id." ");
            redirect('admin/user/dashboard');
        } else {
            redirect('admin/user');
        }
    }
    public function add_category(){
        if($this->session->userdata('user_id')!=''){
            $config['upload_path'] = 'assets/images/';
            $config['allowed_types'] = 'gif|jpg|png|jpeg';
            $this->load->library('upload', $config);

            if ( ! $this->upload->do_upload('file1')){
                $error = array('error' => $this->upload->display_errors());
                $this->session->set_flashdata('profile',$error['error']);
                redirect('admin/user/catalog_categories');
                //print_r($error);
            } else {
                $data_file = array('upload_data' => $this->upload->data());
                //echo '$data_file:::::::'.$data_file['upload_data']['file_name'];
                //die;
                $data=array(
                    'Categories'=>$this->input->post('Categories_add'),
                    'Image'=> $data_file['upload_data']['file_name'],
                    'Subcategories'=>$this->input->post('Subcategories_add'),
                    'Subcategories2'=>$this->input->post('Subcategories2_add'),
                    'Description'=>$this->input->post('Description_add'),
                    'MetaTitle'=>$this->input->post('MetaTitle_add'),
                    'MetaKeyword'=>$this->input->post('MetaKeyword_add'),
                    'MetaDesc'=>$this->input->post('MetaDesc_add'),
                    'Active'=>$this->input->post('Active_add'),
                );
                $result=$this->usermodel_admin->insert('amartex_categories',$data);
                redirect('admin/user/catalog_categories');
            }
        }

        else {
            redirect('admin/user');
        }
    }
    public function update_category(){
        if($this->session->userdata('user_id')!=''){
            $cat_id=$this->input->post('ID');
            $data=array();
            $select_value=$this->input->post('Action');
            if($select_value=='Update'){
                $config['upload_path'] = 'assets/images/';
                $config['allowed_types'] = 'gif|jpg|png|jpeg';
                $this->load->library('upload', $config);/*
echo 	$_FILES['file1']['name'].'sdrfadqwe';
die;			 */
                if($_FILES['file1']['name']!=''){
                    if ( ! $this->upload->do_upload('file1')){
                        $error = array('error' => $this->upload->display_errors());
                        print_r($error);
                    } else {
                        $data_file = array('upload_data' => $this->upload->data());
                        //echo '$data_file:::::::'.$data_file['upload_data']['file_name'];
                        //die;
                        $data=array(
                            'Image'=> $data_file['upload_data']['file_name'],
                            'Categories'=>$this->input->post('Categories'),
                            'Subcategories'=>$this->input->post('Subcategories'),
                            'Subcategories2'=>$this->input->post('Subcategories2'),
                            'Description'=>$this->input->post('Description'),
                            'MetaTitle'=>$this->input->post('MetaTitle'),
                            'MetaKeyword'=>$this->input->post('MetaKeyword'),
                            'MetaDesc'=>$this->input->post('MetaDesc'),
                            'Priority'=>$this->input->post('Priority'),
                            'Active'=>$this->input->post('Active'),
                        );
                    }
                }else{
                    $data=array(
                        'Categories'=>$this->input->post('Categories'),
                        'Subcategories'=>$this->input->post('Subcategories'),
                        'Subcategories2'=>$this->input->post('Subcategories2'),
                        'Description'=>$this->input->post('Description'),
                        'MetaTitle'=>$this->input->post('MetaTitle'),
                        'MetaKeyword'=>$this->input->post('MetaKeyword'),
                        'MetaDesc'=>$this->input->post('MetaDesc'),
                        'Priority'=>$this->input->post('Priority'),
                        'Active'=>$this->input->post('Active'),
                    );
                }
                $result=$this->usermodel_admin->update('amartex_categories',$cat_id,$data);
            } else {
                $this->db->delete('amartex_categories',array('ID'=>$cat_id));
            }
            redirect('admin/user/catalog_categories');
        } else {
            redirect('admin/user');
        }
    }
    public function update_sales_returns(){
        if($this->session->userdata('user_id')!=''){

            $cat_id=$this->input->post('ID');
            //echo $cat_id;die;
            $select_value=$this->input->post('Action');

            $data=array(
                //'cart_ID'=>$this->input->post('DD').$this->input->post('MM').$this->input->post('YY'),
                'cart_ID'=>$this->input->post('cart_ID'),
                'BillToName'=>$this->input->post('BillToName'),
                'DispatchDetails'=>$this->input->post('DispatchDetails'),
                'PaymentStatus'=>$this->input->post('PaymentStatus'),
                'OrderRemarks'=>$this->input->post('OrderRemarks1'),
                'OrderConfirmed'=>$this->input->post('OrderConfirmed1'),
            );
            redirect('admin/user/sales_returns');
        }
    }
    public function update_order_sales(){


        if($this->session->userdata('user_id')!=''){

            $cat_id=$this->input->post('ID');
            //echo $cat_id;die;
            $select_value=$this->input->post('Action');

            $data=array(
                'DispatchDate'=>$this->input->post('DD').$this->input->post('MM').$this->input->post('YY'),
                'ShippingStatus'=>$this->input->post('ShippingStatus1'),
                'DispatchDetails'=>$this->input->post('DispatchDetails'),
                'PaymentStatus'=>$this->input->post('PaymentStatus1'),
                'OrderRemarks'=>$this->input->post('OrderRemarks1'),
                'OrderConfirmed'=>$this->input->post('OrderConfirmed1'),

            );
            $result=$this->usermodel_admin->update('amartex_shippingaddress',$cat_id,$data);
            $emailto=$this->input->post('BillEmail');
            $emailfrom="info@stylemydaddy.com";
            $dispatchdate_dd=$this->input->post('DD');
            $dispatchdate_mm=$this->input->post('MM');
            $dispatchdate_yy=$this->input->post('YY');
            $headers = "From: $emailfrom\r\nContent-type: text/html\r\n";
            $subject="site_url() Dispatch details  for your order no. ".$cat_id." ";
            $message="Your dispatch details for the order no. <a href='http://www.Stylemydaddy.com/admin/success.php?cart_ID=$_REQUEST[cart_ID]'>$_REQUEST[cart_ID]</a> are as below: <br>
	                <br>ShippingStatus : ". $this->input->post('ShippingStatus1'). " 
					<hr>
                    Dispatch Details : ".$this->input->post('DispatchDetails')."
                    
					<br><br>Dispatch Date (DD - MM - YY) : $dispatchdate_dd - $dispatchdate_mm - $dispatchdate_yy
					
					 <br><br> Customer Care, <br><a href='http://www.Stylemydaddy.com'>www.Stylemydaddy.com</a>";



            $message= urlencode($message);
            $URL = "http://bhashsms.com/api/sendmsg.php?";
            $URL=$URL."user=apnalelo&pass=India123%23&sender=APNALO&phone=8826817096&text=".$message."&priority=ndnd&stype=normal";
            if($_POST['NotifyCustomer']=="1"){
                //print "To:$emailto,Sub:$subject,Message:$message,Header:$headers";
                if(mail($emailto,$subject,$message,$headers)) ;
                else echo "MAIL1 FAILED<br>"; ;
            };

            redirect('admin/user/sales_orders');
        } else {
            redirect('admin/user');
        }
    }
    public function add_user(){
        if($this->session->userdata('user_id')!=''){
            $data=array(
                'AdminEmail'=>$this->input->post('AdminUSER'),
                'AdminPSW'=>$this->input->post('AdminPassword'),
                'Rights'=>$this->input->post('Rights'),
            );

            $result=$this->usermodel_admin->insert('amartex_admin',$data);

            redirect('admin/user/system_users');
        } else {
            redirect('admin/user');
        }
    }
    public function add_coupon(){
        if($this->session->userdata('user_id')!=''){

            $data=array(
                'DiscountCouponN'=>$this->input->post('DiscountCouponN'),
                'DiscountMinOrder'=>$this->input->post('DiscountMinOrder'),
                'Value'=>$this->input->post('Value'),
                //'Value'=>$this->input->post('Value'),
                'Quantity'=>$this->input->post('Quantity'),
                //'Issued'=>$this->input->post('Issued'),
                'ValidUpto'=>str_replace("-","",$this->input->post('ValidUpto')),
            );
            /* echo "<pre>";
            print_r($data);
            die; */
            $result=$this->usermodel_admin->insert('amartex_discount',$data);

            redirect('admin/user/sales_coupons');
        } else {
            redirect('admin/user');
        }
    }
    public function update_user(){
        if($this->session->userdata('user_id')!=''){
            $cat_id=$this->input->post('ID');
            $select_value=$this->input->post('Action');
            if($select_value=='Update'){
                $data=array(
                    'AdminEmail'=>$this->input->post('AdminUSER'),
                    'AdminPSW'=>$this->input->post('AdminPassword'),
                    'Rights'=>$this->input->post('Rights'),
                );

                $result=$this->usermodel_admin->update('amartex_admin',$cat_id,$data);
            } else {
                $this->db->delete('amartex_admin',array('ID'=>$cat_id));
            }
            redirect('admin/user/system_users');
        } else {
            redirect('admin/user');
        }

    }
    public function update_product_general(){
        $cat_id=$this->input->post('IDKEY');

        $pizza  = $this->input->post('Categories_Subcategories1');
        $pieces = explode(";", $pizza);

        $pizza  = $this->input->post('Categories_Subcategories2');
        $pieces2 = explode(";", $pizza);


        $pizza  = $this->input->post('Categories_Subcategories3');
        $pieces3 = explode(";", $pizza);

        $pizza  = $this->input->post('Categories_Subcategories4');
        $pieces4 = explode(";", $pizza);
        $pizza  = $this->input->post('Categories_Subcategories5');
        $pieces5 = explode(";", $pizza);

        $Name = str_replace("\"", "'", $this->input->post('Name'));
        $Description = str_replace("\"", "'", $this->input->post('Description'));
        $ShortDescription= str_replace("\"", "'", $this->input->post('ShortDescription'));
        //echo 'gfhgjhgj'.$cat_id.$this->input->post('ReferenceN_ooo');

//die;
        $data=array(
            //'ReferenceN'=>$this->input->post('ReferenceN_ooo'),
            'Name'=>$this->input->post('Name'),
            'SupplierReferenceN'=>$this->input->post('SupplierReferenceN'),
            'SupplierShipping'=>$this->input->post('SupplierShipping'),
            'SupplierPrice'=>$this->input->post('SupplierPrice'),
            'Supplier'=>$this->input->post('Supplier'),
            'Manufacturer'=>$this->input->post('Manufacturer'),
            'ShortDescription'=>$this->input->post('ShortDescription'),
            'Tags'=>$this->input->post('Tags'),
            'MetaTitle'=>$this->input->post('MetaTitle'),
            'MetaKeywords'=>$this->input->post('MetaKeywords'),
            'MetaDescription'=>$this->input->post('MetaDescription'),
            'TextWhenINstock'=>$this->input->post('TextWhenINstock'),
            'TextIFbackOrderAllowed'=>$this->input->post('TextIFbackOrderAllowed'),
            /* 'Pricetaxincl'=>$this->input->post('Pricetaxincl'),
            'Onsale'=>$this->input->post('Onsale'),
            'WholesalePrice'=>$this->input->post('WholesalePrice'),
            'Weight'=>$this->input->post('Weight'),
            'Priority'=>$this->input->post('Priority'),
            'Quantity'=>$this->input->post('Quantity'),
            'TaxRulesID'=>$this->input->post('TaxRulesID'),
            'Feature'=>$this->input->post('Feature'),
             Active'=>$this->input->post('Active'),*/
            'Categories'=>$pieces[0],
            'Subcategories'=>$pieces[1],
            'Subcategory25'=>$pieces5[2],
            //'ImageURL1'=>$this->input->post('ImageURL1'),
            'Subcategories2'=>$pieces[2],
            'Category2'=>$pieces2[0],
            'Subcategory2'=>$pieces2[1],
            'Subcategory22'=>$pieces2[2],
            'Category3'=>$pieces3[0],
            'Subcategory3'=>$pieces3[1],
            'Subcategory23'=>$pieces3[2],
            'Category4'=>$pieces4[0],
            'Subcategory4'=>$pieces4[1],
            'Subcategory24'=>$pieces4[2],
            'Category5'=>$pieces5[0],
            'Subcategory5'=>$pieces5[1]
        );
        /*  echo "<pre>";
                print_r($data);
                die;
         */

        $result=$this->usermodel_admin->update_pro('amartex_product',$cat_id,$data);
        redirect('admin/user/catalog_products');

        /*	where IDKEY=$_REQUEST['IDKEY'];";
            $sql2=$sql1;
        mysql_query($sql1);

        $filename="//products/thumb-"."$_REQUEST[ImageURL1]"."-resized.jpg";
        unlink($filename);



        if(strcmp($_REQUEST['DelImage1'],"1")==0)
        {
        $filebase="../products/$_REQUEST[ImageURL1]";
        $filename="$filebase".".jpg";
        unlink($filename);
        $filename="../products/thumb-"."$_REQUEST[ImageURL1]"."-resized.jpg";
        unlink($filename);
        };


        if(strcmp($_REQUEST['DelImage1A'],"1")==0)
        {
        $filebase="../products/$_REQUEST[ImageURL1]";
        $filename="$filebase"."-A.jpg";
        unlink($filename);
        };

        if(strcmp($_REQUEST['DelImage1B'],"1")==0)
        {
        $filebase="../products/$_REQUEST[ImageURL1]";
        $filename="$filebase"."-B.jpg";
        unlink($filename);
        };

        if(strcmp($_REQUEST['DelImage1C'],"1")==0)
        {
        $filebase="../products/$_REQUEST[ImageURL1]";
        $filename="$filebase"."-C.jpg";
        unlink($filename);
        };

        if(strcmp($_REQUEST['DelImage1D'],"1")==0)
        {
        $filebase="../products/$_REQUEST[ImageURL1]";
        $filename="$filebase"."-D.jpg";
        unlink($filename);
        };

        if(strcmp($_REQUEST['DelImage1E'],"1")==0)
        {
        $filebase="../products/$_REQUEST[ImageURL1]";
        $filename="$filebase"."-E.jpg";
        unlink($filename);
        };


        $filebase="../products/$_REQUEST[ImageURL1]";
        $filename="$filebase".".jpg";
        move_uploaded_file($_FILES['file']['tmp_name'], $filename);
        $filename="products/thumb-"."$_REQUEST[ImageURL1]"."-resized.jpg";
        unlink($filename);



        $filebase="../products/$_REQUEST[ImageURL1]";
        $filename="$filebase"."-A.jpg";
        move_uploaded_file($_FILES['fileA']['tmp_name'], $filename);

        $filebase="../products/$_REQUEST[ImageURL1]";
        $filename="$filebase"."-B.jpg";
        move_uploaded_file($_FILES['fileB']['tmp_name'], $filename);

        $filebase="../products/$_REQUEST[ImageURL1]";
        $filename="$filebase"."-C.jpg";
        move_uploaded_file($_FILES['fileC']['tmp_name'], $filename);

        $filebase="../products/$_REQUEST[ImageURL1]";
        $filename="$filebase"."-D.jpg";
        move_uploaded_file($_FILES['fileD']['tmp_name'], $filename);

        $filebase="../products/$_REQUEST[ImageURL1]";
        $filename="$filebase"."-E.jpg";
        move_uploaded_file($_FILES['fileE']['tmp_name'], $filename);




        $filebase="../products/zoom/$_REQUEST[ImageURL1]";
        $filename="$filebase".".jpg";
        move_uploaded_file($_FILES['filez']['tmp_name'], $filename);

        $filebase="../products/zoom/$_REQUEST[ImageURL1]";
        $filename="$filebase"."-A.jpg";
        move_uploaded_file($_FILES['fileAz']['tmp_name'], $filename);

        $filebase="../products/zoom/$_REQUEST[ImageURL1]";
        $filename="$filebase"."-B.jpg";
        move_uploaded_file($_FILES['fileBz']['tmp_name'], $filename);

        $filebase="../products/zoom/$_REQUEST[ImageURL1]";
        $filename="$filebase"."-C.jpg";
        move_uploaded_file($_FILES['fileCz']['tmp_name'], $filename);

        $filebase="../products/zoom/$_REQUEST[ImageURL1]";
        $filename="$filebase"."-D.jpg";
        move_uploaded_file($_FILES['fileDz']['tmp_name'], $filename);

        $filebase="../products/zoom/$_REQUEST[ImageURL1]";
        $filename="$filebase"."-E.jpg";
        move_uploaded_file($_FILES['fileEz']['tmp_name'], $filename);
*/

    }


    public function update_product_data(){
        $cat_id=$this->input->post('IDKEY');


        //echo 'gfhgjhgj'.$cat_id.$this->input->post('ReferenceN_ooo');

//die;
        $data=array(
            'Pricetaxincl'=>$this->input->post('Pricetaxincl'),
            'Onsale'=>$this->input->post('Onsale'),
            'WholesalePrice'=>$this->input->post('WholesalePrice'),
            'Weight'=>$this->input->post('Weight'),
            'Priority'=>$this->input->post('Priority'),
            'Quantity'=>$this->input->post('Quantity'),
            'TaxRulesID'=>$this->input->post('TaxRulesID'),
            'Feature'=>$this->input->post('Feature'),
            'Active'=>$this->input->post('Active'),

        );
        /*  echo "<pre>";
                print_r($data);
                die;
         */

        $result=$this->usermodel_admin->update_pro('amartex_product',$cat_id,$data);
        redirect('admin/user/catalog_products');
    }
    public function filter_products(){
        $cat_subcat=$this->input->post('Categories_Subcategories');
        if($cat_subcat==''){
            redirect('admin/user/catalog_products');
        }
        $res=explode(';',$cat_subcat);
        $result['cat']=$cat=$res[0];
        $result['subcat']=$subcat=$res[1];
        $result['subcat2']=$subcat2=$res[2];

        $data_cat=$this->usermodel->select_multiple_row_without_where('amartex_categories','*');


        $result['cat_array']=$data_cat;
        $re=$this->db->select('*')->where(array('Categories'=>$cat,'Subcategories'=>$subcat,'Subcategories2'=>$subcat2))->get('amartex_product')->result_array();
        echo "<pre>";
        //print_r($re);
        foreach($re as &$r){
            $total_quantity=$r['Quantity'];
            $re_option=$this->usermodel->select_multiple_row_with_wherepr('amartex_options','*',array('ReferenceN'=>$r['ReferenceN']),'ReferenceN');
            if(sizeof($re_option)!=0){
                foreach($re_option as $re_o){
                    $total_quantity+=$re_o['Quantity'];
                    $r['total_quantity']=$total_quantity;
                }
            } else {
                $r['total_quantity']=$total_quantity;
            }
        }
        /* print_r($re);
        die;
         */

        $result['data']=$re;

        /* echo "<pre>";
        print_r($result);
        die; */
        $this->load->view('admin/catalog_products',$result);
    }

    public function update_products(){
        if($this->session->userdata('user_id')!=''){
            //print_r($_POST);
            $cat_id=$this->input->post('IDKEY');
            echo $cat_id;

            $select_value=$this->input->post('Action');
            echo 'dfdssadas'.$select_value;
            /* if($select_value=='Update') echo 'if'; else echo 'else';
            die;
             */if($select_value=='Update'){
                $data=array(
                    'Name'=>$this->input->post('Name'),
                    //'Feature'=>$this->input->post('Feature'),
                    'ReferenceN'=>$this->input->post('ReferenceN'),
                    'Pricetaxincl'=>$this->input->post('Pricetaxincl'),
                    'Priority'=>$this->input->post('Priority'),
                    'Quantity'=>$this->input->post('Quantity2'),
                    //'Feature'=>$this->input->post('Feature'),
                    'Active'=>$this->input->post('Active'),
                    /*  'Categories'=>$cat,
                    'Subcategories'=>$subcat,
                    'Subcategories2'=>$subcat2, */
                );
                // print_r($data);
                //die;
                $result=$this->usermodel_admin->update_pro('amartex_product',$cat_id,$data);
                redirect('admin/user/catalog_products');
            } else {
                $this->db->delete('amartex_product',array('IDKEY'=>$cat_id));
                redirect('admin/user/catalog_products');
            }
            redirect('admin/user/catalog_products');
        } else {
            redirect('admin/user');
        }

    }
    public function filter_customers(){
        if($this->session->userdata('user_id')!=''){
            $id=$this->input->post('ID');
            $Cust_Name=$this->input->post('Cust_Name');
            $BillToName=$this->input->post('BillToName');
            if($id!=''){
                if($Cust_Name=='' && $BillToName !=''){
                    $result['data']=$this->db->query("Select * from amartex_custdatabase  where ID like '%".$id."%'  and BillToName like '%".$BillToName."%' order by ID desc limit 50;")->result_array();
                } if($Cust_Name!='' && $BillToName ==''){
                    $result['data']=$this->db->query("Select * from amartex_custdatabase  where ID like '%".$id."%'  and Cust_Name like '%".$Cust_Name."%' order by ID desc limit 50;")->result_array();
                } if($Cust_Name!='' && $BillToName !='') {
                    $result['data']=$this->db->query("Select * from amartex_custdatabase  where ID like '%".$id."%'  and Cust_Name like '%".$Cust_Name."%' and BillToName '%".$BillToName."%' order by ID desc limit 50;")->result_array();
                } if($Cust_Name=='' && $BillToName =='') {
                    $result['data']=$this->db->query("Select * from amartex_custdatabase  where  ID like '%".$id."%' order by ID desc limit 50;")->result_array();
                }
            }else{
                if($Cust_Name=='' && $BillToName !=''){
                    $result['data']=$this->db->query("Select * from amartex_custdatabase  where BillToName like '%".$BillToName."%' order by ID desc limit 50;")->result_array();
                } if($Cust_Name!='' && $BillToName ==''){
                    $result['data']=$this->db->query("Select * from amartex_custdatabase  where Cust_Name like '%".$Cust_Name."%' order by ID desc limit 50;")->result_array();
                } if($Cust_Name!='' && $BillToName !='') {
                    $result['data']=$this->db->query("Select * from amartex_custdatabase  where  Cust_Name like '%".$Cust_Name."%' and BillToName '%".$BillToName."%' order by ID desc limit 50;")->result_array();
                } if($Cust_Name=='' && $BillToName =='') {
                    $result['data']=$this->db->query("Select * from amartex_custdatabase  order by ID desc limit 50;")->result_array();
                }
            }
            $this->load->view('admin/sales_customers',$result);
            /*
            $select_value=$this->input->post('Action');
            if($select_value=='Update'){
            $data=array(
                'AdminEmail'=>$this->input->post('AdminUSER'),
                'AdminPSW'=>$this->input->post('AdminPassword'),
                'Rights'=>$this->input->post('Rights'),
            );

            $result=$this->usermodel_admin->update('amartex_admin',$cat_id,$data);
            } else {
                $this->db->delete('amartex_admin',array('ID'=>$cat_id));
            }
            redirect('admin/user/system_users'); */
        } else {
            redirect('admin/user');
        }
    }
    public function filter_orders(){
        if($this->session->userdata('user_id')!=''){
            $id=$this->input->post('cart_ID');
            $BillToName=$this->input->post('BillToName');
            $PaymentStatus=$this->input->post('PaymentStatus');
            //echo $id.',,,,,,,,,'.$Cust_Name.'......'.$PaymentStatus;die;
            if($id!='' && $BillToName !=''){
                $result['data']=$this->db->query("Select * from amartex_shippingaddress  where cart_ID like '%".$id."%'  and BillToName like '%".$BillToName."%' order by ID desc limit 50;")->result_array();
            } else if($id!=''){
                $result['data']=$this->db->query("Select * from amartex_shippingaddress  where cart_ID like '%".$id."%'  order by ID desc limit 50;")->result_array();
            }else if($BillToName !=''){
                $result['data']=$this->db->query("Select * from amartex_shippingaddress  where BillToName like '%".$BillToName."%' order by ID desc limit 50;")->result_array();
            } else {
                $result['data']=$this->db->query("Select * from amartex_shippingaddress  where  PaymentStatus='".$PaymentStatus."' order by ID desc limit 50;")->result_array();
            }
            //echo $this->db->last_query();
            $this->load->view('admin/sales_orders',$result);

        } else {
            redirect('admin/user');
        }
    }
    public function filter_order_returns(){
        if($this->session->userdata('user_id')!=''){
            $id=$this->input->post('cart_ID');

            $BillToName=$this->input->post('BillToName');
            $PaymentStatus=$this->input->post('PaymentStatus');
            $ShippingStatus=$this->input->post('ShippingStatus');


            if($id!='' && $BillToName !=''){
                $result['data']=$this->db->query("Select * from amartex_shippingaddress  where cart_ID like '%".$id."%'  and BillToName like '%".$BillToName."%' order by ID desc limit 50;")->result_array();
            } else if($id!='' ){
                $result['data']=$this->db->query("Select * from amartex_shippingaddress  where cart_ID like '%".$id."%'  order by ID desc limit 50;")->result_array();
            }else if($BillToName !=''){
                $result['data']=$this->db->query("Select * from amartex_shippingaddress  where  BillToName like '%".$BillToName."%'  order by ID desc limit 50;")->result_array();
            } else if($PaymentStatus !=''){
                $result['data']=$this->db->query("Select * from amartex_shippingaddress  where PaymentStatus='".$PaymentStatus."'  order by ID desc limit 50;")->result_array();
            } else{
                $result['data']=$this->db->query("Select * from amartex_shippingaddress  where ShippingStatus='".$ShippingStatus."'  order by ID desc limit 50;")->result_array();
            }
            echo $this->db->last_query();
            $this->load->view('admin/sales_returns',$result);
            /*
            $select_value=$this->input->post('Action');
            if($select_value=='Update'){
            $data=array(
                'AdminEmail'=>$this->input->post('AdminUSER'),
                'AdminPSW'=>$this->input->post('AdminPassword'),
                'Rights'=>$this->input->post('Rights'),
            );

            $result=$this->usermodel_admin->update('amartex_admin',$cat_id,$data);
            } else {
                $this->db->delete('amartex_admin',array('ID'=>$cat_id));
            }
            redirect('admin/user/system_users'); */
        } else {
            redirect('admin/user');
        }
    }
    public function add_shipping(){
        if($this->session->userdata('user_id')!=''){
            $data=array(
                'Location'=>$this->input->post('Location'),
                'MinimumOrder'=>$this->input->post('MinimumOrder'),
                'Shipping'=>$this->input->post('Shipping'),
            );

            $this->db->insert('amartex_shippings',$data);
            redirect('admin/user/extension_shipping');
        } else {
            redirect('admin/user');
        }
    }public function check_products_nil(){
    if($this->session->userdata('user_id')!=''){
        $data_cat=$this->usermodel->select_multiple_row_without_where('amartex_categories','*');
        $final_array=array();
        $result['cat_array']=$data_cat;
        $re=$this->usermodel->select_multiple_row_without_wherepr('amartex_product','*','IDKEY');
        echo "<pre>";
        //print_r($re);
        if(sizeof($re)!=0){
            foreach($re as &$r){
                $total_quantity=$r['Quantity'];
                $re_option=$this->usermodel->select_multiple_row_with_wherepr('amartex_options','*',array('ReferenceN'=>$r['ReferenceN']),'ReferenceN');
                if(sizeof($re_option)!=0){
                    foreach($re_option as $re_o){
                        $total_quantity+=$re_o['Quantity'];
                        $r['total_quantity']=$total_quantity;
                    }
                } else {
                    $r['total_quantity']=$total_quantity;
                }
            }
            /* print_r($re);
            die;
             */

            foreach($re as $r){
                if($r['total_quantity']==0){
                    $final_array[]=$r;
                }
            }

            $result['data']=$final_array;
        }
        /* echo "<pre>";
        print_r($result);
        die; */
        $this->load->view('admin/catalog_products',$result);


    } else {
        redirect('admin/user');
    }
}
    public function add_product(){
        if($this->session->userdata('user_id')!=''){

            $cat_Name=$this->input->post('Categories_Subcategories');
            $data=array(
                'Name'=>$this->input->post('Name'),
                //'Feature'=>$this->input->post('Feature'),
                'ReferenceN'=>$this->input->post('ReferenceN'),
                'Pricetaxincl'=>$this->input->post('Pricetaxincl'),
                'Priority'=>$this->input->post('Priority'),
                'Quantity'=>$this->input->post('Quantity'),
                'Feature'=>$this->input->post('Feature'),
                'Active'=>$this->input->post('Active'),
            );

            $this->db->insert('amartex_product',$data);
            redirect('admin/user/catalog_products');
        } else {
            redirect('admin/user');
        }
    }
    public function update_shipping(){
        if($this->session->userdata('user_id')!=''){
            $cat_id=$this->input->post('ID');
            $select_value=$this->input->post('Action');
            if($select_value=='Update'){
                $data=array(
                    'Location'=>$this->input->post('Location'),
                    'MinimumOrder'=>$this->input->post('MinimumOrder'),
                    'Shipping'=>$this->input->post('Shipping'),
                );

                $result=$this->usermodel_admin->update('amartex_shippings',$cat_id,$data);
            } else {
                $this->db->delete('amartex_shippings',array('ID'=>$cat_id));
            }
            redirect('admin/user/extension_shipping');
        } else {
            redirect('admin/user');
        }
    }
    public function edit_system_pagess(){
        if($this->session->userdata('user_id')!=''){
            $cat_id=$this->input->post('PageID');
            $page=$this->input->post('page');
            $Title=$this->input->post('title');
            $para1=$this->input->post('para1');

            $data=array(
                'page'=>$this->input->post('page'),
                'Title'=>$this->input->post('Title'),
                'para1'=>$this->input->post('para1'),
            );
            $result=$this->usermodel_admin->update('amartex_page',$cat_id,$data);

            redirect('admin/user/system_info_pages');
        } else {
            redirect('admin/user');
        }
    }
    public function update_currency(){

        if($this->session->userdata('user_id')!=''){
            $cat_id=$this->input->post('ID');
            $data=array(
                'EUR'=>$this->input->post('EUR'),
                'USD'=>$this->input->post('USD'),
                //'Rights'=>$this->input->post('Rights'),
            );

            $result=$this->usermodel_admin->update('amartex_currency',$cat_id,$data);
            redirect('admin/user/system_currencies');
        } else {
            redirect('admin/user');
        }
    }
    public function update_payments(){
        if($this->session->userdata('user_id')!=''){
            $cat_id=$this->input->post('ID');
            $data=array(
                'Gateway'=>$this->input->post('Gateway'),
                'business'=>$this->input->post('business'),
                'currency_code'=>$this->input->post('currency_code'),
                'notifyemail'=>$this->input->post('notifyemail'),
                //'Rights'=>$this->input->post('Rights'),
            );

            $result=$this->usermodel_admin->update('amartex_gateway',$cat_id,$data);
            redirect('admin/user/extension_payment');
        } else {
            redirect('admin/user');
        }
    }
    public function filter_cat(){
        $value=$this->input->post('value');
        $res=explode(';',$value);
        $cat=$res[0];
        $subcat=$res[1];
        $subcat2=$res[2];
        $resul=$this->db->query('select * from amartex_product where categories="'.$cat.'" and subcategories="'.$subcat.'" and subcategories2="'.$subcat2.'" ');
        $rep=$resul->result_array();
        $data_cat=$this->usermodel->select_multiple_row_without_where('amartex_categories','*');
        $result['cat_array']=$data_cat;
        if(sizeof($rep)!=0){
            foreach($rep as &$r){
                $total_quantity=$r['Quantity'];
                $re_option=$this->usermodel->select_multiple_row_with_wherepr('amartex_options','*',array('ReferenceN'=>$r['ReferenceN']),'ReferenceN');
                if(sizeof($re_option)!=0){
                    foreach($re_option as $re_o){
                        $total_quantity+=$re_o['Quantity'];
                        $r['total_quantity']=$total_quantity;
                    }
                } else {
                    $r['total_quantity']=$total_quantity;
                }
            }
        }
        $result['data']=$rep;
        $this->load->view('admin/catalog_products_filter',$result);
    }

    public function update_taxes(){
        if($this->session->userdata('user_id')!=''){
            $cat_id=$this->input->post('ID');
            $data=array(
                'TaxRule'=>$this->input->post('TaxRule'),
                'Tax'=>$this->input->post('Tax'),
            );
            $result=$this->usermodel_admin->update('amartex_taxes',$cat_id,$data);
            redirect('admin/user/extension_tax');
        } else {
            redirect('admin/user');
        }
    }
    public function catalog_products(){
        if($this->session->userdata('user_id')!=''){
            $data_cat=$this->usermodel->select_multiple_row_without_where('amartex_categories','*');
            $result['cat_array']=$data_cat;
            $re=$this->usermodel->select_multiple_row_without_wherepr('amartex_product','*','IDKEY');
            //echo "<pre>";
            //print_r($re);
            foreach($re as &$r){
                $total_quantity=$r['Quantity'];
                $re_option=$this->usermodel->select_multiple_row_with_wherepr('amartex_options','*',array('ReferenceN'=>$r['ReferenceN']),'ReferenceN');
                if(sizeof($re_option)!=0){
                    foreach($re_option as $re_o){
                        $total_quantity+=$re_o['Quantity'];
                        $r['total_quantity']=$total_quantity;
                    }
                } else {
                    $r['total_quantity']=$total_quantity;
                }
            }
            /* print_r($re);
            die;
             */

            $result['data']=$re;

            /* echo "<pre>";
            print_r($result);
            die; */
            $this->load->view('admin/catalog_products',$result);
        } else {
            redirect('admin/user');
        }
    }
    public function catalog_attribute(){
        if($this->session->userdata('user_id')!=''){
            $result['data']=$this->usermodel_admin->select_all('amartex_categories');
            $this->load->view('admin/catalog_attribute');
        } else {
            redirect('admin/user');
        }
    }
    public function extension_shipping(){
        if($this->session->userdata('user_id')!=''){
            $result['data']=$this->usermodel_admin->select_all('amartex_shippings');
            //echo "<pre>";print_r($result);die();
            $this->load->view('admin/extension_shipping',$result);
        } else {
            redirect('admin/user');
        }
    }
    public function extension_payment(){
        if($this->session->userdata('user_id')!=''){
            $result['data']=$this->usermodel_admin->select_all('amartex_gateway');
            $this->load->view('admin/extension_payments',$result);
        } else {
            redirect('admin/user');
        }
    }
    public function extension_tax(){
        if($this->session->userdata('user_id')!=''){
            $result['data']=$this->usermodel_admin->select_all('amartex_taxes');
            $this->load->view('admin/extension_taxes',$result);
        } else {
            redirect('admin/user');
        }
    }
    public function sales_orders(){
        $config = array();
        $config["base_url"] = site_url() . "/admin/user/sales_orders";
        $config["total_rows"] = $this->usermodel_admin->record_count();
        $config["per_page"] = 50;
        $config["uri_segment"] = 4;
        $this->pagination->initialize($config);
        $page = ($this->uri->segment(4)) ? $this->uri->segment(4) : 0;

        $result['data']=$this->usermodel_admin->select_all_shippinaddress($config["per_page"], $page);
        $result["links"] = $this->pagination->create_links();
        $result['data_undelete']=$this->usermodel_admin->select_all_shippinaddress_undelete();
        //echo "<pre>";print_r($result);die();
        if($this->session->userdata('user_id')!=''){
            $result['data']=$this->usermodel_admin->select_all_shippinaddress(50,0);
            $this->load->view('admin/sales_orders',$result);
        } else {
            redirect('admin/user');
        }
    }
    public function sales_returns(){
        $config = array();
        $config["base_url"] = site_url() . "/admin/user/sales_orders";
        $config["total_rows"] = $this->usermodel_admin->record_count();
        $config["per_page"] = 50;
        $config["uri_segment"] = 4;
        $this->pagination->initialize($config);
        $page = ($this->uri->segment(4)) ? $this->uri->segment(4) : 0;

        $result['data']=$this->usermodel_admin->select_all_shippinaddress($config["per_page"], $page);
        $result["links"] = $this->pagination->create_links();
        $result['data_undelete']=$this->usermodel_admin->select_all_shippinaddress_undelete();

        if($this->session->userdata('user_id')!=''){
            $result['data']=$this->usermodel_admin->select_all_shippinaddress(50,0);
            $this->load->view('admin/sales_returns',$result);
        } else {
            redirect('admin/user');
        }
    }
    public function sales_customer(){
        if($this->session->userdata('user_id')!=''){
            $result['data']=$this->usermodel_admin->select_all('amartex_custdatabase');
            $this->load->view('admin/sales_customers',$result);
        } else {
            redirect('admin/user');
        }
    }
    public function sales_coupons(){
        if($this->session->userdata('user_id')!=''){
            $result['data']=$this->usermodel_admin->select_all('amartex_discount');
            $this->load->view('admin/sales_coupons',$result);
        } else {
            redirect('admin/user');
        }
    }
    public function system_banners(){
        if($this->session->userdata('user_id')!=''){
            $result['data']=$this->usermodel_admin->select_all_single('amartex_banners');
            $this->load->view('admin/system_banners',$result);
        } else {
            redirect('admin/user');
        }
    }
    public function add_banner(){
        if($this->session->userdata('user_id')!=''){
            if($this->input->post('DelImage1')==1){
                $file_url=$this->input->post('file_hidden_1');
                $ee=$file_url;
                // echo 'fi::'.$file_url;
                /*die;
                 */$last=explode('/',$file_url);
                $last_ele=end($last);
                $r=FCPATH.'media/banners/'.$last_ele;
                if($last_ele!='no-image.jpg'){
                    if(file_exists($r)){

                        if(unlink($r)){

                            $this->usermodel_admin->update('amartex_banners',1,array('banmer_1'=>''));
                            echo 'delete'.$this->db->last_query();
                        }
                        else
                            echo 'not';
                    } else{
                        echo 'not ex';
                    }

                }
                redirect('admin/user/system_banners');
            }
            //echo $this->db->last_query();die;

            if($this->input->post('DelImage2')==1){
                $file_url=$this->input->post('file_hidden_2');
                $ee=$file_url;
                $last=explode('/',$file_url);
                $last_ele=end($last);
                //echo $last_ele;
                $r=FCPATH.'media/banners/'.$last_ele;
                if($last_ele!='no-image.jpg'){

                    if(file_exists($r)){
                        if(unlink($r))
                            $this->db->update('amartex_banners',array('banmer_2'=>''),array('id'=>1));

                        else
                            echo 'not';

                    }
                }
                redirect('admin/user/system_banners');
            }
            if($this->input->post('DelImage3')==1){
                $file_url=$this->input->post('file_hidden_3');
                $ee=$file_url;
                $last=explode('/',$file_url);
                $last_ele=end($last);
                //echo $last_ele;
                $r=FCPATH.'media/banners/'.$last_ele;
                if($last_ele!='no-image.jpg'){

                    if(file_exists($r)){
                        if(unlink($r))
                            $this->db->update('amartex_banners',array('banmer_3'=>''),array('id'=>1));

                        else
                            echo 'not';

                    }
                }
                redirect('admin/user/system_banners');
            } if($this->input->post('DelImage4')==1){
                $file_url=$this->input->post('file_hidden_4');
                $ee=$file_url;
                $last=explode('/',$file_url);
                $last_ele=end($last);
                //echo $last_ele;
                $r=FCPATH.'media/banners/'.$last_ele;
                if($last_ele!='no-image.jpg'){

                    if(file_exists($r)){
                        if(unlink($r))
                            $this->usermodel_admin->update('amartex_banners',1,array('banmer_4'=>''));
                        else
                            echo 'not';

                    }
                }
                redirect('admin/user/system_banners');
            }if($this->input->post('DelImage5')==1){
                $file_url=$this->input->post('file_hidden_5');
                $ee=$file_url;
                $last=explode('/',$file_url);
                $last_ele=end($last);
                //echo $last_ele;
                $r=FCPATH.'media/banners/'.$last_ele;
                if($last_ele!='no-image.jpg'){

                    if(file_exists($r)){
                        if(unlink($r))
                            $this->db->update('amartex_banners',array('banmer_5'=>''),array('id'=>1));

                        else
                            echo 'not';

                    }
                }
            }
            $config['upload_path'] = 'media/banners/';
            $config['allowed_types'] = 'gif|jpg|png|jpeg';
            $this->load->library('upload', $config);
            if(($_FILES['file1']['name'])!=''){
                if ( ! $this->upload->do_upload('file1')){
                    $error = array('error' => $this->upload->display_errors());
                    print_r($error);
                } else {
                    $data_file = array('upload_data' => $this->upload->data());
                    echo '$data_file:::::::'.$data_file['upload_data']['file_name'];
                    //die;
                    $data=array(
                        'banmer_1'=> $data_file['upload_data']['file_name'],
                        //'salary'=> $this->input->post('salary')
                    );
                }
                $this->db->update('amartex_banners',$data,array('id'=>1));
                echo $this->db->last_query();
                redirect('admin/user/system_banners');
            }
            if($_FILES['file2']['name'] !=''){
                if ( ! $this->upload->do_upload('file2')){
                    $error = array('error' => $this->upload->display_errors());
                    print_r($error);
                } else {
                    $data_file = array('upload_data' => $this->upload->data());
                    $data=array(
                        'banmer_2'=> $data_file['upload_data']['file_name'],
                        //'salary'=> $this->input->post('salary')
                    );

                }
                $this->db->update('amartex_banners',$data,array('id'=>1));
            }
            if(($_FILES['file3']['name'])!=''){
                if ( ! $this->upload->do_upload('file3')){
                    $error = array('error' => $this->upload->display_errors());
                    print_r($error);
                } else {
                    $data_file = array('upload_data' => $this->upload->data());
                    $data=array(
                        'banmer_3'=> $data_file['upload_data']['file_name'],
                        //'salary'=> $this->input->post('salary')
                    );

                }
                $this->db->update('amartex_banners',$data,array('id'=>1));
            }
            if(($_FILES['file4']['name'])!=''){
                if ( ! $this->upload->do_upload('file4')){
                    $error = array('error' => $this->upload->display_errors());
                    print_r($error);
                } else {
                    $data_file = array('upload_data' => $this->upload->data());
                    $data=array(
                        'banmer_4'=> $data_file['upload_data']['file_name'],
                        //'salary'=> $this->input->post('salary')
                    );

                }
                $this->db->update('amartex_banners',$data,array('id'=>1));
            }
            if(($_FILES['file5']['name'])!=''){
                if ( ! $this->upload->do_upload('file5')){
                    $error = array('error' => $this->upload->display_errors());
                    print_r($error);
                } else {
                    $data_file = array('upload_data' => $this->upload->data());
                    $data=array(
                        'banmer_5'=> $data_file['upload_data']['file_name'],
                        //'salary'=> $this->input->post('salary')
                    );

                }
                $this->db->update('amartex_banners',$data,array('id'=>1));
            }

            redirect('admin/user/system_banners');
        }	else {
            redirect('admin/user');
        }
    }
    public function add_product_images(){
        if($this->session->userdata('user_id')!=''){
            $p_Code=$this->input->post('p_Code');
            if($this->input->post('DelImage1')==1){
                $r=FCPATH.'media/product/'.$p_Code.'.jpg';
                if($last_ele!='no-image.jpg'){
                    if(file_exists($r)){

                        if(unlink($r)){


                            echo 'delete';

                        }
                        else
                            echo 'not';
                    } else{
                        echo 'not ex';
                    }

                }
                redirect('admin/user/catalog_products');
            }if($this->input->post('DelImage1A')==1){
                $r=FCPATH.'media/product/'.$p_Code.'_z.jpg';
                if($last_ele!='no-image.jpg'){
                    if(file_exists($r)){

                        if(unlink($r)){


                            echo 'delete';

                        }
                        else
                            echo 'not';
                    } else{
                        echo 'not ex';
                    }

                }
                redirect('admin/user/catalog_products');
            }if($this->input->post('DelImage1B')==1){
                $r=FCPATH.'media/product/'.$p_Code.'_az.jpg';
                if($last_ele!='no-image.jpg'){
                    if(file_exists($r)){

                        if(unlink($r)){


                            echo 'delete';

                        }
                        else
                            echo 'not';
                    } else{
                        echo 'not ex';
                    }

                }
                redirect('admin/user/catalog_products');
            }if($this->input->post('DelImage1C')==1){
                $r=FCPATH.'media/product/'.$p_Code.'_bz.jpg';
                if($last_ele!='no-image.jpg'){
                    if(file_exists($r)){

                        if(unlink($r)){


                            echo 'delete';

                        }
                        else
                            echo 'not';
                    } else{
                        echo 'not ex';
                    }

                }
                redirect('admin/user/catalog_products');
            }if($this->input->post('DelImage1D')==1){
                $r=FCPATH.'media/product/'.$p_Code.'_cz.jpg';
                if($last_ele!='no-image.jpg'){
                    if(file_exists($r)){

                        if(unlink($r)){


                            echo 'delete';

                        }
                        else
                            echo 'not';
                    } else{
                        echo 'not ex';
                    }

                }
                redirect('admin/user/catalog_products');
            }if($this->input->post('DelImage1E')==1){
                $r=FCPATH.'media/product/'.$p_Code.'_dz.jpg';
                if($last_ele!='no-image.jpg'){
                    if(file_exists($r)){

                        if(unlink($r)){


                            echo 'delete';

                        }
                        else
                            echo 'not';
                    } else{
                        echo 'not ex';
                    }

                }
                redirect('admin/user/catalog_products');
            }
            if($_FILES['file_mbvhj']['name']!=''){
                $p_Code=$this->input->post('p_Code');
                $id=$this->input->post('ID_u');
                //echo 'ffwerfwe'.$id;die;
                $r=FCPATH.'media/product/'.$p_Code.'.jpg';
                if(file_exists($r)){
                    if(unlink($r)){
                        $config['upload_path'] = 'media/product/';
                        $config['allowed_types'] = 'gif|jpg|png|jpeg';
                        $config['file_name'] =$p_Code.'.jpg';
                        $this->load->library('upload', $config);
                        if ( ! $this->upload->do_upload('file_mbvhj')){
                            $error = array('error' => $this->upload->display_errors());
                            print_r($error);
                        } else {
                            $data_file = array('upload_data' => $this->upload->data());
                            echo '$data_file:::::::'.$data_file['upload_data']['file_name'];
                            $data=array(
                                'ImageURL1'=> $data_file['upload_data']['file_name'],
                            );
                        }

                        $this->usermodel_admin->update_pro('amartex_product',$id,$data);
                        echo 'delete'.$this->db->last_query();
                    } else 	echo 'not';
                } else{
                    $config['upload_path'] = 'media/product/';
                    $config['allowed_types'] = 'gif|jpg|png|jpeg';
                    $config['file_name'] =$p_Code.'.jpg';
                    $this->load->library('upload', $config);
                    if ( ! $this->upload->do_upload('file_mbvhj')){
                        $error = array('error' => $this->upload->display_errors());
                        print_r($error);
                    } else {
                        $data_file = array('upload_data' => $this->upload->data());
                        echo '$data_file:::::::'.$data_file['upload_data']['file_name'];
                        $data=array(
                            'ImageURL1'=> $data_file['upload_data']['file_name'],
                        );
                    }

                    $this->usermodel_admin->update_pro('amartex_product',$id,$data);

                }redirect('admin/user/catalog_products');
            }
            if($_FILES['filez']['name']!=''){
                $p_Code=$this->input->post('p_Code');
                $id=$this->input->post('ID_u');
                //echo 'ffwerfwe'.$id;die;
                $r=FCPATH.'media/product/'.$p_Code.'_z.jpg';
                if(file_exists($r)){
                    if(unlink($r)){
                        $config['upload_path'] = 'media/product/';
                        $config['allowed_types'] = 'gif|jpg|png|jpeg';
                        $config['file_name'] =$p_Code.'_z.jpg';
                        $this->load->library('upload', $config);
                        if ( ! $this->upload->do_upload('filez')){
                            $error = array('error' => $this->upload->display_errors());
                            print_r($error);
                        } else {
                            $data_file = array('upload_data' => $this->upload->data());
                            echo '$data_file:::::::'.$data_file['upload_data']['file_name'];

                        }


                        echo 'delete'.$this->db->last_query();
                    } else 	echo 'not';
                } else{
                    $config['upload_path'] = 'media/product/';
                    $config['allowed_types'] = 'gif|jpg|png|jpeg';
                    $config['file_name'] =$p_Code.'_z.jpg';
                    $this->load->library('upload', $config);
                    if ( ! $this->upload->do_upload('filez')){
                        $error = array('error' => $this->upload->display_errors());
                        print_r($error);
                    } else {
                        $data_file = array('upload_data' => $this->upload->data());
                        echo '$data_file:::::::'.$data_file['upload_data']['file_name'];

                    }



                }redirect('admin/user/catalog_products');
            }if($_FILES['fileA']['name']!=''){
                $p_Code=$this->input->post('p_Code');
                $id=$this->input->post('ID_u');
                //echo 'ffwerfwe'.$id;die;
                $r=FCPATH.'media/product/'.$p_Code.'_a.jpg';
                if(file_exists($r)){
                    if(unlink($r)){
                        $config['upload_path'] = 'media/product/';
                        $config['allowed_types'] = 'gif|jpg|png|jpeg';
                        $config['file_name'] =$p_Code.'_a.jpg';
                        $this->load->library('upload', $config);
                        if ( ! $this->upload->do_upload('fileA')){
                            $error = array('error' => $this->upload->display_errors());
                            print_r($error);
                        } else {
                            $data_file = array('upload_data' => $this->upload->data());
                            echo '$data_file:::::::'.$data_file['upload_data']['file_name'];

                        }


                        echo 'delete'.$this->db->last_query();
                    } else 	echo 'not';
                } else{
                    $config['upload_path'] = 'media/product/';
                    $config['allowed_types'] = 'gif|jpg|png|jpeg';
                    $config['file_name'] =$p_Code.'_a.jpg';
                    $this->load->library('upload', $config);
                    if ( ! $this->upload->do_upload('fileA')){
                        $error = array('error' => $this->upload->display_errors());
                        print_r($error);
                    } else {
                        $data_file = array('upload_data' => $this->upload->data());
                        echo '$data_file:::::::'.$data_file['upload_data']['file_name'];

                    }



                }redirect('admin/user/catalog_products');
            }if($_FILES['fileAz']['name']!=''){
                $p_Code=$this->input->post('p_Code');
                $id=$this->input->post('ID_u');
                //echo 'ffwerfwe'.$id;die;
                $r=FCPATH.'media/product/'.$p_Code.'_az.jpg';
                if(file_exists($r)){
                    if(unlink($r)){
                        $config['upload_path'] = 'media/product/';
                        $config['allowed_types'] = 'gif|jpg|png|jpeg';
                        $config['file_name'] =$p_Code.'_az.jpg';
                        $this->load->library('upload', $config);
                        if ( ! $this->upload->do_upload('fileAz')){
                            $error = array('error' => $this->upload->display_errors());
                            print_r($error);
                        } else {
                            $data_file = array('upload_data' => $this->upload->data());
                            echo '$data_file:::::::'.$data_file['upload_data']['file_name'];

                        }


                        echo 'delete'.$this->db->last_query();
                    } else 	echo 'not';
                } else{
                    $config['upload_path'] = 'media/product/';
                    $config['allowed_types'] = 'gif|jpg|png|jpeg';
                    $config['file_name'] =$p_Code.'_az.jpg';
                    $this->load->library('upload', $config);
                    if ( ! $this->upload->do_upload('fileAz')){
                        $error = array('error' => $this->upload->display_errors());
                        print_r($error);
                    } else {
                        $data_file = array('upload_data' => $this->upload->data());
                        echo '$data_file:::::::'.$data_file['upload_data']['file_name'];

                    }



                }redirect('admin/user/catalog_products');
            }if($_FILES['fileB']['name']!=''){
                $p_Code=$this->input->post('p_Code');
                $id=$this->input->post('ID_u');
                //echo 'ffwerfwe'.$id;die;
                $r=FCPATH.'media/product/'.$p_Code.'_b.jpg';
                if(file_exists($r)){
                    if(unlink($r)){
                        $config['upload_path'] = 'media/product/';
                        $config['allowed_types'] = 'gif|jpg|png|jpeg';
                        $config['file_name'] =$p_Code.'_b.jpg';
                        $this->load->library('upload', $config);
                        if ( ! $this->upload->do_upload('fileB')){
                            $error = array('error' => $this->upload->display_errors());
                            print_r($error);
                        } else {
                            $data_file = array('upload_data' => $this->upload->data());
                            echo '$data_file:::::::'.$data_file['upload_data']['file_name'];

                        }


                        echo 'delete'.$this->db->last_query();
                    } else 	echo 'not';
                } else{
                    $config['upload_path'] = 'media/product/';
                    $config['allowed_types'] = 'gif|jpg|png|jpeg';
                    $config['file_name'] =$p_Code.'_b.jpg';
                    $this->load->library('upload', $config);
                    if ( ! $this->upload->do_upload('fileB')){
                        $error = array('error' => $this->upload->display_errors());
                        print_r($error);
                    } else {
                        $data_file = array('upload_data' => $this->upload->data());
                        echo '$data_file:::::::'.$data_file['upload_data']['file_name'];

                    }



                }redirect('admin/user/catalog_products');
            }if($_FILES['fileBz']['name']!=''){
                $p_Code=$this->input->post('p_Code');
                $id=$this->input->post('ID_u');
                //echo 'ffwerfwe'.$id;die;
                $r=FCPATH.'media/product/'.$p_Code.'_bz.jpg';
                if(file_exists($r)){
                    if(unlink($r)){
                        $config['upload_path'] = 'media/product/';
                        $config['allowed_types'] = 'gif|jpg|png|jpeg';
                        $config['file_name'] =$p_Code.'_bz.jpg';
                        $this->load->library('upload', $config);
                        if ( ! $this->upload->do_upload('fileBz')){
                            $error = array('error' => $this->upload->display_errors());
                            print_r($error);
                        } else {
                            $data_file = array('upload_data' => $this->upload->data());
                            echo '$data_file:::::::'.$data_file['upload_data']['file_name'];

                        }


                        echo 'delete'.$this->db->last_query();
                    } else 	echo 'not';
                } else{
                    $config['upload_path'] = 'media/product/';
                    $config['allowed_types'] = 'gif|jpg|png|jpeg';
                    $config['file_name'] =$p_Code.'_bz.jpg';
                    $this->load->library('upload', $config);
                    if ( ! $this->upload->do_upload('fileBz')){
                        $error = array('error' => $this->upload->display_errors());
                        print_r($error);
                    } else {
                        $data_file = array('upload_data' => $this->upload->data());
                        echo '$data_file:::::::'.$data_file['upload_data']['file_name'];

                    }



                }redirect('admin/user/catalog_products');
            }if($_FILES['fileC']['name']!=''){
                $p_Code=$this->input->post('p_Code');
                $id=$this->input->post('ID_u');
                //echo 'ffwerfwe'.$id;die;
                $r=FCPATH.'media/product/'.$p_Code.'_c.jpg';
                if(file_exists($r)){
                    if(unlink($r)){
                        $config['upload_path'] = 'media/product/';
                        $config['allowed_types'] = 'gif|jpg|png|jpeg';
                        $config['file_name'] =$p_Code.'_c.jpg';
                        $this->load->library('upload', $config);
                        if ( ! $this->upload->do_upload('fileC')){
                            $error = array('error' => $this->upload->display_errors());
                            print_r($error);
                        } else {
                            $data_file = array('upload_data' => $this->upload->data());
                            echo '$data_file:::::::'.$data_file['upload_data']['file_name'];

                        }


                        echo 'delete'.$this->db->last_query();
                    } else 	echo 'not';
                } else{
                    $config['upload_path'] = 'media/product/';
                    $config['allowed_types'] = 'gif|jpg|png|jpeg';
                    $config['file_name'] =$p_Code.'_c.jpg';
                    $this->load->library('upload', $config);
                    if ( ! $this->upload->do_upload('fileC')){
                        $error = array('error' => $this->upload->display_errors());
                        print_r($error);
                    } else {
                        $data_file = array('upload_data' => $this->upload->data());
                        echo '$data_file:::::::'.$data_file['upload_data']['file_name'];

                    }



                }redirect('admin/user/catalog_products');
            }if($_FILES['fileCz']['name']!=''){
                $p_Code=$this->input->post('p_Code');
                $id=$this->input->post('ID_u');
                //echo 'ffwerfwe'.$id;die;
                $r=FCPATH.'media/product/'.$p_Code.'_cz.jpg';
                if(file_exists($r)){
                    if(unlink($r)){
                        $config['upload_path'] = 'media/product/';
                        $config['allowed_types'] = 'gif|jpg|png|jpeg';
                        $config['file_name'] =$p_Code.'_cz.jpg';
                        $this->load->library('upload', $config);
                        if ( ! $this->upload->do_upload('fileCz')){
                            $error = array('error' => $this->upload->display_errors());
                            print_r($error);
                        } else {
                            $data_file = array('upload_data' => $this->upload->data());
                            echo '$data_file:::::::'.$data_file['upload_data']['file_name'];

                        }


                        echo 'delete'.$this->db->last_query();
                    } else 	echo 'not';
                } else{
                    $config['upload_path'] = 'media/product/';
                    $config['allowed_types'] = 'gif|jpg|png|jpeg';
                    $config['file_name'] =$p_Code.'_cz.jpg';
                    $this->load->library('upload', $config);
                    if ( ! $this->upload->do_upload('fileCz')){
                        $error = array('error' => $this->upload->display_errors());
                        print_r($error);
                    } else {
                        $data_file = array('upload_data' => $this->upload->data());
                        echo '$data_file:::::::'.$data_file['upload_data']['file_name'];

                    }



                }redirect('admin/user/catalog_products');
            }if($_FILES['fileD']['name']!=''){
                $p_Code=$this->input->post('p_Code');
                $id=$this->input->post('ID_u');
                //echo 'ffwerfwe'.$id;die;
                $r=FCPATH.'media/product/'.$p_Code.'_d.jpg';
                if(file_exists($r)){
                    if(unlink($r)){
                        $config['upload_path'] = 'media/product/';
                        $config['allowed_types'] = 'gif|jpg|png|jpeg';
                        $config['file_name'] =$p_Code.'_d.jpg';
                        $this->load->library('upload', $config);
                        if ( ! $this->upload->do_upload('fileD')){
                            $error = array('error' => $this->upload->display_errors());
                            print_r($error);
                        } else {
                            $data_file = array('upload_data' => $this->upload->data());
                            echo '$data_file:::::::'.$data_file['upload_data']['file_name'];

                        }


                        echo 'delete'.$this->db->last_query();
                    } else 	echo 'not';
                } else{
                    $config['upload_path'] = 'media/product/';
                    $config['allowed_types'] = 'gif|jpg|png|jpeg';
                    $config['file_name'] =$p_Code.'_d.jpg';
                    $this->load->library('upload', $config);
                    if ( ! $this->upload->do_upload('fileD')){
                        $error = array('error' => $this->upload->display_errors());
                        print_r($error);
                    } else {
                        $data_file = array('upload_data' => $this->upload->data());
                        echo '$data_file:::::::'.$data_file['upload_data']['file_name'];

                    }



                }redirect('admin/user/catalog_products');
            }if($_FILES['fileDz']['name']!=''){
                $p_Code=$this->input->post('p_Code');
                $id=$this->input->post('ID_u');
                //echo 'ffwerfwe'.$id;die;
                $r=FCPATH.'media/product/'.$p_Code.'_dz.jpg';
                if(file_exists($r)){
                    if(unlink($r)){
                        $config['upload_path'] = 'media/product/';
                        $config['allowed_types'] = 'gif|jpg|png|jpeg';
                        $config['file_name'] =$p_Code.'_dz.jpg';
                        $this->load->library('upload', $config);
                        if ( ! $this->upload->do_upload('fileDz')){
                            $error = array('error' => $this->upload->display_errors());
                            print_r($error);
                        } else {
                            $data_file = array('upload_data' => $this->upload->data());
                            echo '$data_file:::::::'.$data_file['upload_data']['file_name'];

                        }


                        echo 'delete'.$this->db->last_query();
                    } else 	echo 'not';
                } else{
                    $config['upload_path'] = 'media/product/';
                    $config['allowed_types'] = 'gif|jpg|png|jpeg';
                    $config['file_name'] =$p_Code.'_dz.jpg';
                    $this->load->library('upload', $config);
                    if ( ! $this->upload->do_upload('fileDz')){
                        $error = array('error' => $this->upload->display_errors());
                        print_r($error);
                    } else {
                        $data_file = array('upload_data' => $this->upload->data());
                        echo '$data_file:::::::'.$data_file['upload_data']['file_name'];

                    }



                }redirect('admin/user/catalog_products');
            }if($_FILES['fileE']['name']!=''){
                $p_Code=$this->input->post('p_Code');
                $id=$this->input->post('ID_u');
                //echo 'ffwerfwe'.$id;die;
                $r=FCPATH.'media/product/'.$p_Code.'_e.jpg';
                if(file_exists($r)){
                    if(unlink($r)){
                        $config['upload_path'] = 'media/product/';
                        $config['allowed_types'] = 'gif|jpg|png|jpeg';
                        $config['file_name'] =$p_Code.'_e.jpg';
                        $this->load->library('upload', $config);
                        if ( ! $this->upload->do_upload('fileE')){
                            $error = array('error' => $this->upload->display_errors());
                            print_r($error);
                        } else {
                            $data_file = array('upload_data' => $this->upload->data());
                            echo '$data_file:::::::'.$data_file['upload_data']['file_name'];

                        }


                        echo 'delete'.$this->db->last_query();
                    } else 	echo 'not';
                } else{
                    $config['upload_path'] = 'media/product/';
                    $config['allowed_types'] = 'gif|jpg|png|jpeg';
                    $config['file_name'] =$p_Code.'_e.jpg';
                    $this->load->library('upload', $config);
                    if ( ! $this->upload->do_upload('fileE')){
                        $error = array('error' => $this->upload->display_errors());
                        print_r($error);
                    } else {
                        $data_file = array('upload_data' => $this->upload->data());
                        echo '$data_file:::::::'.$data_file['upload_data']['file_name'];

                    }



                }redirect('admin/user/catalog_products');
            }if($_FILES['fileEz']['name']!=''){
                $p_Code=$this->input->post('p_Code');
                $id=$this->input->post('ID_u');
                //echo 'ffwerfwe'.$id;die;
                $r=FCPATH.'media/product/'.$p_Code.'_ez.jpg';
                if(file_exists($r)){
                    if(unlink($r)){
                        $config['upload_path'] = 'media/product/';
                        $config['allowed_types'] = 'gif|jpg|png|jpeg';
                        $config['file_name'] =$p_Code.'_ez.jpg';
                        $this->load->library('upload', $config);
                        if ( ! $this->upload->do_upload('fileEz')){
                            $error = array('error' => $this->upload->display_errors());
                            print_r($error);
                        } else {
                            $data_file = array('upload_data' => $this->upload->data());
                            echo '$data_file:::::::'.$data_file['upload_data']['file_name'];

                        }


                        echo 'delete'.$this->db->last_query();
                    } else 	echo 'not';
                } else{
                    $config['upload_path'] = 'media/product/';
                    $config['allowed_types'] = 'gif|jpg|png|jpeg';
                    $config['file_name'] =$p_Code.'_ez.jpg';
                    $this->load->library('upload', $config);
                    if ( ! $this->upload->do_upload('fileEz')){
                        $error = array('error' => $this->upload->display_errors());
                        print_r($error);
                    } else {
                        $data_file = array('upload_data' => $this->upload->data());
                        echo '$data_file:::::::'.$data_file['upload_data']['file_name'];

                    }



                }redirect('admin/user/catalog_products');
            }redirect('admin/user/catalog_products');
        }
        else {
            redirect('admin/user');
        }
    }
    public function system_info_pages(){
        if($this->session->userdata('user_id')!=''){
            $result['data']=$this->usermodel_admin->select_all('amartex_page');
            $this->load->view('admin/system_info_pages',$result);
        } else {
            redirect('admin/user');
        }
    }
    public function get_page_info(){
        if($this->session->userdata('user_id')!=''){
            $id=$this->input->post('id');
            $result=$this->usermodel_admin->select_single_row('amartex_page','*',array('ID'=>$id));
            //$this->load->view('admin/system_info_pages',$result);
            echo json_encode($result);
        } else {
            redirect('admin/user');
        }
    }
    public function edit_system_pages($id){
        if($this->session->userdata('user_id')!=''){
            echo $id;
        } else {
            redirect('admin/user');
        }
    }
    public function system_users(){
        if($this->session->userdata('user_id')!=''){
            $result['data']=$this->usermodel_admin->select_all('amartex_admin');
            $this->load->view('admin/system_users',$result);
        } else {
            redirect('admin/user');
        }
    }
    public function system_currencies(){
        if($this->session->userdata('user_id')!=''){
            $result['data']=$this->usermodel_admin->select_all_single('amartex_currency');
            $this->load->view('admin/system_currencies',$result);
        } else {
            redirect('admin/user');
        }
    }
	
	public function delete_coupon($cat_id){
        if($this->session->userdata('user_id')!=''){
            $this->db->delete('amartex_discount',array('ID'=>$cat_id));
            redirect('admin/user/sales_coupons');
        } else {
            redirect('admin/user');
        }
    }
    public function add_options(){
        if($this->session->userdata('user_id')!=''){
            $ref=$this->input->post('ReferenceN');
            $data=array(
                'Color'=>$this->input->post('Color'),
                'ReferenceN'=>$this->input->post('ReferenceN'),

                'Size'=>$this->input->post('Size'),
                //'Subcategories2'=>$this->input->post('Subcategories2_add'),
                'Quantity'=>$this->input->post('Quantity'),
                'CAT'=>$this->input->post('CAT'),
                'SUBCAT'=>$this->input->post('SUBCAT'),
                //'MetaDesc'=>$this->input->post('MetaDesc_add'),
                //'Active'=>$this->input->post('Active_add'),
            );
            $result=$this->usermodel_admin->insert('amartex_options',$data);
            redirect('admin/user/catalog_products');
        } else {
            redirect('admin/user');
        }
    }
    public function update_options(){
        if($this->session->userdata('user_id')!=''){
            $select_value=$this->input->post('Action');
            echo 'dasd::'.$select_value;
            $ref=$this->input->post('ID');
            if($select_value=='UpdateXOption'){

                //echo 'if ';die;
                $data=array(
                    'Color'=>$this->input->post('Color'),
                    'ReferenceN'=>$this->input->post('ReferenceN'),

                    'Size'=>$this->input->post('Size'),
                    //'Subcategories2'=>$this->input->post('Subcategories2_add'),
                    'Quantity'=>$this->input->post('Quantity'),
                    'CAT'=>$this->input->post('CAT'),
                    'SUBCAT'=>$this->input->post('SUBCAT'),
                    //'MetaDesc'=>$this->input->post('MetaDesc_add'),
                    //'Active'=>$this->input->post('Active_add'),
                );$result=$this->usermodel_admin->update('amartex_options',$ref,$data);
            }	 else {
                $this->db->delete('amartex_options',array('ID'=>$ref));
            }
            redirect('admin/user/catalog_products');


        } else {
            redirect('admin/user');
        }
    }
    public function  update_order_returns(){
        $cart_ID=$this->input->post('cart_ID');
        $cart_ID=$this->input->post('cart_ID');
        $cart_ID=$this->input->post('cart_ID');
        $cart_ID=$this->input->post('cart_ID');
        $cart_ID=$this->input->post('cart_ID');
        $cart_ID=$this->input->post('cart_ID');

        $config = array();
        $config["base_url"] = site_url() . "/admin/user/sales_orders";
        $config["total_rows"] = $this->usermodel_admin->record_count();
        $config["per_page"] = 50;
        $config["uri_segment"] = 4;
        $this->pagination->initialize($config);
        $page = ($this->uri->segment(4)) ? $this->uri->segment(4) : 0;

        $result['data']=$this->usermodel_admin->select_all_shippinaddress($config["per_page"], $page);
        $result["links"] = $this->pagination->create_links();
        $result['data_undelete']=$this->usermodel_admin->select_all_shippinaddress_undelete();

        if($this->session->userdata('user_id')!=''){
            $result['data']=$this->usermodel_admin->select_all_shippinaddress(50,0);
            $this->load->view('admin/sales_returns',$result);
        } else{
            redirect('admin/user/');
        }

    }

    /* added for buessiness 29/june */
    public function business_type(){
        if($this->session->userdata('user_id')!=''){
            $result['data']=$this->usermodel_admin->select_all_order_by_select('amartex_business_type','id');
            $this->load->view('admin/business_type',$result);
        } else {
            redirect('admin/user');
        }
    }
    public function add_business_type(){
        if($this->session->userdata('user_id')!=''){
            $config['upload_path'] = 'assets/images/business';
            $config['allowed_types'] = 'gif|jpg|png|jpeg';
            $this->load->library('upload', $config);

            if ( ! $this->upload->do_upload('file1')){
                $error = array('error' => $this->upload->display_errors());
                $this->session->set_flashdata('profile',$error['error']);
                redirect('admin/user/business_type');
                //print_r($error);
            } else {
                $data_file = array('upload_data' => $this->upload->data());
                //echo '$data_file:::::::'.$data_file['upload_data']['file_name'];
                //die;
                $data=array(
                    'name'=>trim($this->input->post('bname')),
                    'icon'=> $data_file['upload_data']['file_name'],
                    'status'=>trim($this->input->post('status_add')),
                );
                $result=$this->usermodel_admin->insert('amartex_business_type',$data);
                redirect('admin/user/business_type');
            }
        }

        else {
            redirect('admin/user');
        }
    }
    public function update_business_type(){
        if($this->session->userdata('user_id')!=''){
            $b_id=$this->input->post('ID');
            $data=array();
            $select_value=$this->input->post('Action');
            if($select_value=='Update'){
                $config['upload_path'] = 'assets/images/business/';
                $config['allowed_types'] = 'gif|jpg|png|jpeg';
                $this->load->library('upload', $config);/*
echo 	$_FILES['file1']['name'].'sdrfadqwe';
die;			 */
                if($_FILES['file1']['name']!=''){
                    if ( ! $this->upload->do_upload('file1')){
                        $error = array('error' => $this->upload->display_errors());
                        print_r($error);
                    } else {
                        $data_file = array('upload_data' => $this->upload->data());
                        //echo '$data_file:::::::'.$data_file['upload_data']['file_name'];
                        //die;
                        $data=array(
                            'icon'=> $data_file['upload_data']['file_name'],
                            'name'=>trim($this->input->post('bname')),
                            'status'=>trim($this->input->post('status_add')),
                        );
                    }
                }else{
                    $data=array(
                        'name'=>trim($this->input->post('bname')),
                        'status'=>trim($this->input->post('status_add')),
                    );
                }

                $result=$this->usermodel_admin->update('amartex_business_type',$b_id,$data);
            } else {
                $this->db->delete('amartex_business_type',array('id'=>$b_id));
            }
            redirect('admin/user/business_type');
        } else {
            redirect('admin/user');
        }
    }

    public function business_info(){
        $result = array();
        if($this->session->userdata('user_id')!=''){
            $result['data']=$this->usermodel_admin->select_all_order_by_select('amartex_business_info','id');
            $result['business_type']=$this->usermodel_admin->select_all_order_by_select('amartex_business_type','id');

            $this->load->view('admin/business_info',$result);
        } else {
            redirect('admin/user');
        }
    }
    public function add_business_info(){
        if($this->session->userdata('user_id')!=''){
            if(isset($_FILES['file2']['name']) && !empty($_FILES['file2']['name'])) {
                $config['upload_path'] = 'assets/images/business';
                $config['allowed_types'] = 'gif|jpg|png|jpeg';
                $this->load->library('upload', $config);
                $this->upload->do_upload('file2');
                $data_file = $this->upload->data();
                $image = $data_file['file_name'];
            } else {
                $image = '';
            }
            $config['upload_path'] = 'assets/images/business';
            $config['allowed_types'] = 'gif|jpg|png|jpeg';
            $this->load->library('upload', $config);

            if ( ! $this->upload->do_upload('file1')){
                $error = array('error' => $this->upload->display_errors());
                $this->session->set_flashdata('profile',$error['error']);
                redirect('admin/user/business_info');
                //print_r($error);
            } else {
                $data_file = array('upload_data' => $this->upload->data());
                //echo '$data_file:::::::'.$data_file['upload_data']['file_name'];
                //die;



                // We define our address
                $address = trim($this->input->post('address')).', '.trim($this->input->post('city')).', '.trim($this->input->post('state')).', '.trim($this->input->post('country')).', '.trim($this->input->post('zipcode'));
                // We get the JSON results from this request

                $geo = $this->getLatLong($address);
                
                //$geo = file_get_contents('https://maps.googleapis.com/maps/api/geocode/json?address='.urlencode($address).'&sensor=false&key=AIzaSyCgtyB6pwTyHE3zMUgvMxRPGvfpEEZAe50');
                // We convert the JSON to an array
                //$geo = json_decode($geo, true);
                // If everything is cool
                $latitude = 0.0;
                $longitude =0.0;
                if (isset($geo['lat']) && isset($geo['long'])) {
                    // We set our values
                    $latitude = $geo['lat'];
                    $longitude = $geo['long'];
                }

                $bName=trim($this->input->post('bname'));
                $bPhone=$this->input->post('phone');

                $data=array(
                    'name'=>trim($this->input->post('bname')),
                    'logo'=> $data_file['upload_data']['file_name'],
                    'status'=>trim($this->input->post('status_add')),
                    'business_type_id'=>trim($this->input->post('business_type_id')),
                    'city'=>trim($this->input->post('city')),
                    'state'=>trim($this->input->post('state')),
                    'zipcode'=>trim($this->input->post('zipcode')),
                    'country'=>trim($this->input->post('country')),
                    'opening_time'=>$this->input->post('opening_time'),
                    'closing_time'=>$this->input->post('closing_time'),
                    'valid_upto'=>$this->input->post('valid_upto'),
                    'description'=>$this->input->post('description'),
                    'phone'=>$this->input->post('phone'),
                    'address'=>trim($this->input->post('address')),
                    'image'=>trim($image),
                    'lat'=>$latitude,
                    'lon'=>$longitude,
                );
                $result=$this->usermodel_admin->insert('amartex_business_info',$data);

                if(!empty($bPhone) ){
                    $message= " Hi,".$bName."(".$bPhone.") is successfully added with LeLo. Now customer can see you on LeLo App. For any query Call +919024899169.";
                    $message_text = str_replace( "</p>", "\n\n", $message );
                    $message_text = str_replace( "<br>", "\n", $message_text );
                    $message_text = str_replace( "<br />", "\n", $message_text );
                    $message_text = strip_tags( $message_text );
                    $message= urlencode($message);
                    $URL = "http://bhashsms.com/api/sendmsg.php?";
                    $URL=$URL."user=apnalelo&pass=India123%23&sender=APNALO&phone=".$bPhone."&text=".$message."&priority=ndnd&stype=normal";
                    $data = file_get_contents($URL);
                }
                redirect('admin/user/business_info');
            }
        }

        else {
            redirect('admin/user');
        }
    }

    function getLatLong($address)
    {
        if(!empty($address)){
            //Formatted address
            $formattedAddr = urlencode($address);
			//str_replace(' ','+',$address);

            $url = "https://maps.google.com/maps/api/geocode/json?address=".$formattedAddr."&sensor=false&key=AIzaSyCgtyB6pwTyHE3zMUgvMxRPGvfpEEZAe50&region=India";
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_PROXYPORT, 3128);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
            $response = curl_exec($ch);
            curl_close($ch);
            $response_a = json_decode($response);
            if(isset($response_a))
            {
                $lat = $response_a->results[0]->geometry->location->lat;
                $long = $response_a->results[0]->geometry->location->lng;
                return array('lat'=>$lat,'long'=>$long);
            }
            else
            {
                return false;
            }

        }else{
            return false;
        }
    }


    public function update_business_info(){
        if($this->session->userdata('user_id')!=''){
            $b_id=$this->input->post('ID');
            $data=array();
            $select_value=$this->input->post('Action');
            if($select_value=='Update'){


                if(isset($_FILES['file2']['name']) && !empty($_FILES['file2']['name'])) {
                    $config['upload_path'] = 'assets/images/business';
                    $config['allowed_types'] = 'gif|jpg|png|jpeg';
                    $this->load->library('upload', $config);
                    $this->upload->do_upload('file2');
                    $data_file = $this->upload->data();
                    $image = $data_file['file_name'];
                } else {
                    $image = $this->input->post('old_image');
                }

                if(isset($_FILES['file1']['name']) && !empty($_FILES['file1']['name'])) {
                    $config['upload_path'] = 'assets/images/business';
                    $config['allowed_types'] = 'gif|jpg|png|jpeg';
                    $this->load->library('upload', $config);
                    $this->upload->do_upload('file1');
                    $data_file = $this->upload->data();
                    $logo = $data_file['file_name'];
                } else {
                    $logo = $this->input->post('old_logo');
                }


                // We define our address
                $address = trim($this->input->post('address')).', '.trim($this->input->post('city')).', '.trim($this->input->post('state')).', '.trim($this->input->post('country')).', '.trim($this->input->post('zipcode'));
                // We get the JSON results from this request
             //   $geo = file_get_contents('http://maps.googleapis.com/maps/api/geocode/json?address='.urlencode($address).'&sensor=false');
                // We convert the JSON to an array
               // $geo = json_decode($geo, true);
                // If everything is cool
				
				 $geo = $this->getLatLong($address);
                $latitude = 0.0;
                $longitude =0.0;
                if (isset($geo['lat']) && isset($geo['long'])) {
                    // We set our values
                    $latitude = $geo['lat'];
                    $longitude = $geo['long'];
                }


                $data=array(
                    'name'=>trim($this->input->post('bname')),
                    'logo'=> $logo,
                    'status'=>trim($this->input->post('status_add')),
                    'business_type_id'=>trim($this->input->post('business_type_id')),
                    'city'=>trim($this->input->post('city')),
                    'state'=>trim($this->input->post('state')),
                    'zipcode'=>trim($this->input->post('zipcode')),
                    'country'=>trim($this->input->post('country')),
                    'opening_time'=>$this->input->post('opening_time'),
                    'closing_time'=>$this->input->post('closing_time'),
                    'valid_upto'=>$this->input->post('valid_upto'),
                    'description'=>$this->input->post('description'),
                    'phone'=>$this->input->post('phone'),
                    'address'=>trim($this->input->post('address')),
                    'image'=>$image,
                    'lat'=>$latitude,
                    'lon'=>$longitude,
                );


                $result=$this->usermodel_admin->update('amartex_business_info',$b_id,$data);
            } else {
                $this->db->delete('amartex_business_info',array('id'=>$b_id));
            }
            redirect('admin/user/business_info');
        } else {
            redirect('admin/user');
        }
    }


    /* added for rooms 6 July 2016 - Pulkit Agarwal */

    public function rooms_type(){
        if($this->session->userdata('user_id')!=''){
            $result['data']=$this->usermodel_admin->select_all_order_by_select('amartex_rooms_type','id');
            $this->load->view('admin/rooms_type',$result);
        } else {
            redirect('admin/user');
        }
    }
    public function add_rooms_type(){
        if($this->session->userdata('user_id')!=''){
            $config['upload_path'] = 'assets/images/room_type_images';
            $config['allowed_types'] = 'gif|jpg|png|jpeg';
            $this->load->library('upload', $config);

            if ( ! $this->upload->do_upload('file1')){
                $error = array('error' => $this->upload->display_errors());
                $this->session->set_flashdata('profile',$error['error']);
                redirect('admin/user/rooms_type');
                //print_r($error);
            } else {
                $data_file = array('upload_data' => $this->upload->data());
                //echo '$data_file:::::::'.$data_file['upload_data']['file_name'];
                //die;
                $data=array(
                    'name'=>trim($this->input->post('bname')),
                    'icon'=> $data_file['upload_data']['file_name'],
                    'status'=>trim($this->input->post('status_add')),
                );
                $result=$this->usermodel_admin->insert('amartex_rooms_type',$data);
                redirect('admin/user/rooms_type');
            }
        }

        else {
            redirect('admin/user');
        }
    }
    public function update_rooms_type(){
        if($this->session->userdata('user_id')!=''){
            $b_id=$this->input->post('ID');
            $data=array();
            $select_value=$this->input->post('Action');
            if($select_value=='Update'){
                $config['upload_path'] = 'assets/images/room_type_images';
                $config['allowed_types'] = 'gif|jpg|png|jpeg';
                $this->load->library('upload', $config);/*
echo 	$_FILES['file1']['name'].'sdrfadqwe';
die;			 */
                if($_FILES['file1']['name']!=''){
                    if ( ! $this->upload->do_upload('file1')){
                        $error = array('error' => $this->upload->display_errors());
                        print_r($error);
                    } else {
                        $data_file = array('upload_data' => $this->upload->data());
                        //echo '$data_file:::::::'.$data_file['upload_data']['file_name'];
                        //die;
                        $data=array(
                            'icon'=> $data_file['upload_data']['file_name'],
                            'name'=>trim($this->input->post('bname')),
                            'status'=>trim($this->input->post('status_add')),
                        );
                    }
                }else{
                    $data=array(
                        'name'=>trim($this->input->post('bname')),
                        'status'=>trim($this->input->post('status_add')),
                    );
                }

                $result=$this->usermodel_admin->update('amartex_rooms_type',$b_id,$data);
            } else {
                $this->db->delete('amartex_rooms_type',array('id'=>$b_id));
            }
            redirect('admin/user/rooms_type');
        } else {
            redirect('admin/user');
        }
    }

    public function rooms_info(){
        $result = array();
        if($this->session->userdata('user_id')!=''){
            $result['data']=$this->usermodel_admin->select_all_order_by_select('amartex_rooms_info','id');
            $result['rooms_type']=$this->usermodel_admin->select_all_order_by_select('amartex_rooms_type','id');
            $result['rooms_user']=$this->usermodel_admin->select_all_order_by_select('amartex_room_user','id');
            $result['features']=$this->usermodel_admin->select_all_order_by_select('amartex_room_features','id');
            $result['user']=$this->usermodel_admin->select_all_order_by_select('amartex_custdatabase','ID');


            $this->load->view('admin/rooms_info',$result);
        } else {
            redirect('admin/user');
        }
    }
    public function add_rooms_info(){
        if($this->session->userdata('user_id')!=''){
            if(isset($_FILES['file2']['name']) && !empty($_FILES['file2']['name'])) {
                $config['upload_path'] = 'assets/images/rooms';
                $config['allowed_types'] = 'gif|jpg|png|jpeg';
                $this->load->library('upload', $config);
                $this->upload->do_upload('file2');
                $data_file = $this->upload->data();
                $image2 = $data_file['file_name'];
            } else {
                $image2 = '';
            }

            if(isset($_FILES['file3']['name']) && !empty($_FILES['file3']['name'])) {
                $config['upload_path'] = 'assets/images/rooms';
                $config['allowed_types'] = 'gif|jpg|png|jpeg';
                $this->load->library('upload', $config);
                $this->upload->do_upload('file3');
                $data_file = $this->upload->data();
                $image3 = $data_file['file_name'];
            } else {
                $image3 = '';
            }

            $config['upload_path'] = 'assets/images/rooms';
            $config['allowed_types'] = 'gif|jpg|png|jpeg';
            $this->load->library('upload', $config);

            if ( ! $this->upload->do_upload('file1')){
                $error = array('error' => $this->upload->display_errors());
                $this->session->set_flashdata('profile',$error['error']);
                redirect('admin/user/rooms_info');
                //print_r($error);
            } else {
                $data_file = array('upload_data' => $this->upload->data());
                //echo '$data_file:::::::'.$data_file['upload_data']['file_name'];
                //die;

                $uId=trim($this->input->post('owner'));

                // We define our address
                $address = trim($this->input->post('address')).', '.trim($this->input->post('city')).', '.trim($this->input->post('state')).', '.trim($this->input->post('country')).', '.trim($this->input->post('zipcode'));

                // We get the JSON results from this request
              //  $geo = file_get_contents('http://maps.googleapis.com/maps/api/geocode/json?address='.urlencode($address).'&sensor=false');
                // We convert the JSON to an array
              //  $geo = json_decode($geo, true);
                // If everything is cool
				
				 $geo = $this->getLatLong($address);
                $latitude = 0.0;
                $longitude =0.0;
                if (isset($geo['lat']) && isset($geo['long'])) {
                    // We set our values
                    $latitude = $geo['lat'];
                    $longitude = $geo['long'];
                }


                $data=array(
                    'image1'=> $data_file['upload_data']['file_name'],
                    'status'=>trim($this->input->post('status_add')),
                    'rooms_type_id'=>trim($this->input->post('rooms_type_id')),
                    'city'=>trim($this->input->post('city')),
                    'state'=>trim($this->input->post('state')),
                    'zipcode'=>trim($this->input->post('zipcode')),
                    'country'=>trim($this->input->post('country')),
                    'rent'=>$this->input->post('rent'),
                    'negotiation'=>$this->input->post('negotation'),
                    'available_on'=>$this->input->post('available_on'),
                    'description'=>$this->input->post('description'),
                    'address'=>trim($this->input->post('address')),
                    'image2'=>trim($image2),
                    'image3'=>trim($image3),
                    'lat'=>$latitude,
                    'lon'=>$longitude,
                    'owner_id'=>trim($this->input->post('owner')),
                    'room_for'=>implode(',',$_POST['rooms_user']),
                    'facility'=>implode(',',$_POST['features']),
                );
                $result=$this->usermodel_admin->insert('amartex_rooms_info',$data);
                $data=$this->usermodel->select_single_row('amartex_custdatabase','*',array('ID'=>$uId));

                if(!empty($data['phoneNum']) ){
                    $message= " Hi,".$data['Cust_Name']."(".$data['phoneNum'].") your room is successfully uploaded on LeLo. Now customer can see you on LeLo App. For any query Call +919024899169.";
                    $message_text = str_replace( "</p>", "\n\n", $message );
                    $message_text = str_replace( "<br>", "\n", $message_text );
                    $message_text = str_replace( "<br />", "\n", $message_text );
                    $message_text = strip_tags( $message_text );
                    $message= urlencode($message);
                    $URL = "http://bhashsms.com/api/sendmsg.php?";
                    $URL=$URL."user=apnalelo&pass=India123%23&sender=APNALO&phone=".$data['phoneNum']."&text=".$message."&priority=ndnd&stype=normal";
                    $data = file_get_contents($URL);
                }


                redirect('admin/user/rooms_info');
            }
        }

        else {
            redirect('admin/user');
        }
    }
    public function update_rooms_info(){
        if($this->session->userdata('user_id')!=''){
            $r_id=$this->input->post('ID');
            $data=array();
            $select_value=$this->input->post('Action');
            if($select_value=='Update'){


                if(isset($_FILES['image1']['name']) && !empty($_FILES['image1']['name'])) {
                    $config['upload_path'] = 'assets/images/rooms';
                    $config['allowed_types'] = 'gif|jpg|png|jpeg';
                    $this->load->library('upload', $config);
                    $this->upload->do_upload('image1');
                    $data_file = $this->upload->data();
                    $image1 = $data_file['file_name'];
                } else {
                    $image1 = $this->input->post('old_image1');
                }

                if(isset($_FILES['image2']['name']) && !empty($_FILES['image2']['name'])) {
                    $config['upload_path'] = 'assets/images/rooms';
                    $config['allowed_types'] = 'gif|jpg|png|jpeg';
                    $this->load->library('upload', $config);
                    $this->upload->do_upload('image2');
                    $data_file = $this->upload->data();
                    $image2 = $data_file['file_name'];
                } else {
                    $image2 = $this->input->post('old_image2');
                }

                if(isset($_FILES['image3']['name']) && !empty($_FILES['image3']['name'])) {
                    $config['upload_path'] = 'assets/images/rooms';
                    $config['allowed_types'] = 'gif|jpg|png|jpeg';
                    $this->load->library('upload', $config);
                    $this->upload->do_upload('image3');
                    $data_file = $this->upload->data();
                    $image3 = $data_file['file_name'];
                } else {
                    $image3 = $this->input->post('old_image3');
                }

                // We define our address
                $address = trim($this->input->post('address')).', '.trim($this->input->post('city')).', '.trim($this->input->post('state')).', '.trim($this->input->post('country')).', '.trim($this->input->post('zipcode'));
                // We get the JSON results from this request
                //$geo = file_get_contents('http://maps.googleapis.com/maps/api/geocode/json?address='.urlencode($address).'&sensor=false');
                // We convert the JSON to an array
               // $geo = json_decode($geo, true);
                // If everything is cool
				
				 $geo = $this->getLatLong($address);
                $latitude = 0.0;
                $longitude =0.0;
                if (isset($geo['lat']) && isset($geo['long'])) {
                    // We set our values
                    $latitude = $geo['lat'];
                    $longitude = $geo['long'];
                }


                $data=array(
                    'image1'=> trim($image1),
                    'status'=>trim($this->input->post('status_add')),
                    'rooms_type_id'=>trim($this->input->post('rooms_type_id')),
                    'city'=>trim($this->input->post('city')),
                    'state'=>trim($this->input->post('state')),
                    'zipcode'=>trim($this->input->post('zipcode')),
                    'country'=>trim($this->input->post('country')),
                    'rent'=>$this->input->post('rent'),
                    'negotiation'=>$this->input->post('negotation'),
                    'available_on'=>$this->input->post('available_on'),
                    'description'=>$this->input->post('description'),
                    'address'=>trim($this->input->post('address')),
                    'image2'=>trim($image2),
                    'image3'=>trim($image3),
                    'lat'=>$latitude,
                    'lon'=>$longitude,
                    'owner_id'=>trim($this->input->post('owner')),
                    'room_for'=>implode(',',$_POST['rooms_user']),
                    'facility'=>implode(',',$_POST['facility']),
                );


                $result=$this->usermodel_admin->update('amartex_rooms_info',$r_id,$data);
            } else {
                $this->db->delete('amartex_rooms_info',array('id'=>$r_id));
            }
            redirect('admin/user/rooms_info');
        } else {
            redirect('admin/user');
        }
    }
    /* added for rooms 6 July 2016 - Pulkit Agarwal */

    /*work by bhuppi */

    public function room_users(){
        if($this->session->userdata('user_id')!=''){
            $result['data']=$this->usermodel_admin->select_all_order_by_select('amartex_room_user','id');
            $this->load->view('admin/room_user',$result);
        } else {
            redirect('admin/user');
        }
    }

    public function add_room_user(){
        if($this->session->userdata('user_id')!=''){
            $config['upload_path'] = 'assets/images/room_user_image';
            $config['allowed_types'] = 'gif|jpg|png|jpeg';
            $this->load->library('upload', $config);

            if ( ! $this->upload->do_upload('file1')){
                $error = array('error' => $this->upload->display_errors());
                $this->session->set_flashdata('profile',$error['error']);
                redirect('admin/user/room_users');
                //print_r($error);
            } else {
                $data_file = array('upload_data' => $this->upload->data());
                //echo '$data_file:::::::'.$data_file['upload_data']['file_name'];
                //die;
                $data=array(
                    'user_name'=>trim($this->input->post('user_name')),
                    'user_icon'=> $data_file['upload_data']['file_name'],
                    'user_status'=>trim($this->input->post('user_status')),
                );
                $result=$this->usermodel_admin->insert('amartex_room_user',$data);
                redirect('admin/user/room_users');
            }
        }

        else {
            redirect('admin/user');
        }
    }


    public function update_room_user(){
        if($this->session->userdata('user_id')!=''){
            $u_id=$this->input->post('ID');
            $data=array();
            $select_value=$this->input->post('Action');
            if($select_value=='Update'){
                $config['upload_path'] = 'assets/images/room_user_image';
                $config['allowed_types'] = 'gif|jpg|png|jpeg';
                $this->load->library('upload', $config);/*
echo 	$_FILES['file1']['name'].'sdrfadqwe';
die;			 */
                if($_FILES['file1']['name']!=''){
                    if ( ! $this->upload->do_upload('file1')){
                        $error = array('error' => $this->upload->display_errors());
                        print_r($error);
                    } else {
                        $data_file = array('upload_data' => $this->upload->data());
                        //echo '$data_file:::::::'.$data_file['upload_data']['file_name'];
                        //die;
                        $data=array(
                            'user_icon'=> $data_file['upload_data']['file_name'],
                            'user_name'=>trim($this->input->post('user_name')),
                            'user_status'=>trim($this->input->post('user_status')),
                        );
                    }
                }else{
                    $data=array(
                        'user_name'=>trim($this->input->post('user_name')),
                        'user_status'=>trim($this->input->post('user_status')),
                    );
                }

                $result=$this->usermodel_admin->update('amartex_room_user',$u_id,$data);
            } else {
                $this->db->delete('amartex_room_user',array('id'=>$u_id));
            }
            redirect('admin/user/room_users');
        } else {
            redirect('admin/user');
        }
    }

    public function room_features(){
        if($this->session->userdata('user_id')!=''){
            $result['data']=$this->usermodel_admin->select_all_order_by_select('amartex_room_features','id');
            $this->load->view('admin/room_feature',$result);
        } else {
            redirect('admin/user');
        }
    }

    public function add_room_feature(){
        if($this->session->userdata('user_id')!=''){
            $config['upload_path'] = 'assets/images/room_facility_images';
            $config['allowed_types'] = 'gif|jpg|png|jpeg';
            $this->load->library('upload', $config);

            if ( ! $this->upload->do_upload('file1')){
                $error = array('error' => $this->upload->display_errors());
                $this->session->set_flashdata('profile',$error['error']);
                redirect('admin/user/room_features');
                //print_r($error);
            } else {
                $data_file = array('upload_data' => $this->upload->data());
                //echo '$data_file:::::::'.$data_file['upload_data']['file_name'];
                //die;
                $data=array(
                    'feature_name'=>trim($this->input->post('f_name')),
                    'feature_icon'=> $data_file['upload_data']['file_name'],
                    'feature_status'=>trim($this->input->post('f_status')),
                );
                $result=$this->usermodel_admin->insert('amartex_room_features',$data);
                redirect('admin/user/room_features');
            }
        }

        else {
            redirect('admin/user');
        }
    }


    public function update_room_feature(){
        if($this->session->userdata('user_id')!=''){
            $f_id=$this->input->post('ID');
            $data=array();
            $select_value=$this->input->post('Action');
            if($select_value=='Update'){
                $config['upload_path'] = 'assets/images/room_facility_images';
                $config['allowed_types'] = 'gif|jpg|png|jpeg';
                $this->load->library('upload', $config);/*
echo 	$_FILES['file1']['name'].'sdrfadqwe';
die;			 */
                if($_FILES['file1']['name']!=''){
                    if ( ! $this->upload->do_upload('file1')){
                        $error = array('error' => $this->upload->display_errors());
                        print_r($error);
                    } else {
                        $data_file = array('upload_data' => $this->upload->data());
                        //echo '$data_file:::::::'.$data_file['upload_data']['file_name'];
                        //die;
                        $data=array(
                            'feature_icon'=> $data_file['upload_data']['file_name'],
                            'feature_name'=>trim($this->input->post('f_name')),
                            'feature_status'=>trim($this->input->post('f_status')),
                        );
                    }
                }else{
                    $data=array(
                        'feature_name'=>trim($this->input->post('f_name')),
                        'feature_status'=>trim($this->input->post('f_status')),
                    );
                }

                $result=$this->usermodel_admin->update('amartex_room_features',$f_id,$data);
            } else {
                $this->db->delete('amartex_room_features',array('id'=>$f_id));
            }
            redirect('admin/user/room_features');
        } else {
            redirect('admin/user');
        }
    }
}
?>
