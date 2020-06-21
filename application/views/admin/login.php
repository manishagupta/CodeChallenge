


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>BackOffice | Administration</title>
<script type="text/javascript" src="<?php echo base_url('assets/js/jquery.js');?>"></script>

</head>

<body>

<script language="javascript">
	
	$(document).ready(function(){
	$('#lgn_btn').click(function(){
		var regExpObj = /(\d\d\d)-\d\d\d\d\d\d\d\d/;
		var reg = /^([A-Za-z0-9_\-\.])+\@([A-Za-z0-9_\-\.])+\.([A-Za-z]{2,4})$/;
		var email=$('#USERID').val();
		var password=$('#PASSWORD').val();
			if(email=='' ){
				$('#error_message').attr('color','red');
				$('#error_message').html('Please Enter Your Username');
				return false;
			} else if(password==''|| password=='Enter Password'){
				$('#error_message').attr('color','red');
				$('#error_message').html('Please Enter Your Password');
				return false;
			} else {
				//alert('<?php echo site_url(); ?>/admin/user/login');
				$.ajax({
					url:'<?php echo site_url(); ?>/user/login',
					type:'post',
					async:false,
					data:{'email':email,'password':password},
					success:function(result){
						//
						//alert('<?php echo site_url(); ?>admin/user/login');
						if(result==1){
							window.location.href='<?php echo site_url('user/dashboard');?>';
						} else {
							$('#error_message').attr('color','red');
							$('#error_message').html('Invalid Username Or Password.');								
						}
					}			
				});
			}
	});
});
		
						
</script>


  


<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td bgcolor="#333333"><table width="100%" border="0" cellspacing="0" cellpadding="10">
        <tr>
          <td><span class="style3">BackOffice | </span><span class="style4">ADMINISTRATION</span></td>

          <td width="300" class="style4"><div align="right"><img src="<?php echo base_url();?>/assets/images/lock.png" width="16" height="16" align="absbottom" /> You are not logged</div></td>
          <td width="150" class="style4"><div align="center"> <a href="<?php echo site_url('user/');?>" target="_blank" class="styleAnchor">Store Front</a> </div></td>
        </tr>
      </table></td>
  </tr>
  
  <tr>

    <td>&nbsp;</td>
  </tr>
  <tr>
    <td><table width="95%" border="0" align="center" cellpadding="10" cellspacing="0">
        <tr>
          <td>&nbsp;</td>
        </tr>
      </table></td>
  </tr>

  <tr>
    <td><table width="95%" border="1"  align="center" cellpadding="10" cellspacing="0" bordercolor="#FFFFFF" class="dashboard" style="border-radius:10px" >
      <tr>
        <td><table width="100%" border="0" cellpadding="10" cellspacing="0" bgcolor="#CCCCCC">
          <tr>
            <td width="10"><img src="<?php echo base_url();?>/assets/images/home.png" alt="Home" width="22" height="22" /></td>
            <td bgcolor="#CCCCCC"><span class="style5">Login</span></td>
          </tr>

        </table></td>
      </tr>
      <tr>
        <td><table width="100%" border="0" cellspacing="0" cellpadding="10">
          <tr>
            <td bgcolor="#547C96"><span class="style3">Welcome to the login panel</span></td>
          </tr>
          <tr>

            <td>
			
			
			<form name="login" method="post" action="">
			<table width="100%" border="0" cellpadding="10" cellspacing="0" bordercolor="#F0F0F0">
              
			  
			  

              <tr>
                <td>&nbsp;</td>
                <td colspan="2" style="color:red;" id="error_message">Please Authenticate Yourself </td>
                <td>&nbsp;</td>
              </tr>

              <tr>
                <td>&nbsp;</td>
                <td width="100">Username</td>
                <td><div align="left">
                  <input type="text" name="USERID" id="USERID"/>
                </div></td>
                <td>&nbsp;</td>
              </tr>

              <tr>
                <td>&nbsp;</td>
                <td>Password</td>
                <td><div align="left">
                  <input type="password" name="PASSWORD" id="PASSWORD" />
                </div></td>
                <td>&nbsp;</td>
              </tr>

              <tr>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td><input type="button" name="button" value="Login" id="lgn_btn" /></td>
                <td>&nbsp;</td>
              </tr>
            </table>
			</form>
			
			
			
           
			<br /><br />
			
			
			<div align="center">
</div>
			
			
			</td>
          </tr>
        </table></td>
      </tr>

    </table></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
  </tr>
</table>
</body>
</html>
