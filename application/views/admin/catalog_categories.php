
	


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>BackOffice | Administration</title>
</head>

<body>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>

    <td bgcolor="#333333"><?php include('header_logout.php');?></td>
  </tr>
  
  <tr>
    <td><table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
        <tr bgcolor="#25000A">

          <td  align="left" valign="top" bgcolor="#999999"><!-- dd menu -->
                           <style>
#sddm
{	margin: 0;
	padding: 0;
	z-index: 30}

#sddm li
{	margin: 0;
	padding: 0;
	list-style: none;
	float: left;
	font: bold 12px }

#sddm li a
{	display: block;
	margin: 0 1px 0 0;
	padding: 10px 20px;
	width: 90px;
	background: #CCCCCC;
	color: #666666;
	color: #000000;
	text-align: center;
	text-decoration: none}

#sddm li a:hover
{	background: #49A3FF}

#sddm div
{	position: absolute;
	visibility: hidden;
	margin: 0;
	padding: 0;
	background: #EAEBD8;
	border: 1px solid #5970B2}

	#sddm div a
	{	position: relative;
		display: block;
		margin: 0;
		padding: 5px 10px;
		width: 118px;
		white-space: nowrap;
		text-align: left;
		text-decoration: none;
		background: #EAEBD8;
		color: #2875DE;
		color: #000000;
		font: 12px }

	#sddm div a:hover
	{	background: #49A3FF;
		color: #FFF}
          </style>
              <!-- div class="sample" style="margin-bottom: 15px;height:42px;"><span -->
<?php include('header.php');?><!-- /dd -->          </td>
        </tr>
    </table></td>

  </tr>
 
  <tr>
    <td><table width="95%" border="1"  align="center" cellpadding="10" cellspacing="0" bordercolor="#FFFFFF" class="dashboard" style="border-radius:10px" >
      <tr>
        <td><table width="100%" border="0" cellpadding="10" cellspacing="0" bgcolor="#CCCCCC">
          <tr>
            <td width="10"><img src="<?php echo base_url();?>/assets/images/category.png" alt="Home" width="22" height="22" /></td>
            <td bgcolor="#CCCCCC"><span class="style5">Categories</span></td>

          </tr>
        </table></td>
      </tr>
      <tr>
        <td>
		
		
		<table width="100%" border="0" cellspacing="0" cellpadding="10">
          
          <tr>
            <td><table width="100%" border="1" cellpadding="5" cellspacing="0" bordercolor="#F0F0F0">
			<tr bgcolor="#CCCCCC">

                <td width="10"><div align="center"><strong>ID</strong></div></td>
                <td><div align="center"><strong>Name</strong></div></td>
                <td><div align="center"><strong>Type</strong></div></td>
                
                <td><div align="center"><strong>Model</strong></div></td>
                <td><div align="center"><strong>Action</strong></div></td>
                <td><div align="center"></div></td>

                </tr>
			  <form action="<?php echo site_url('admin/add_category');?>" method="post" enctype="multipart/form-data">
			  <tr>
               
				<td><div align="center">
                      <input name="ID" type="hidden" value="">
                </div></td>
				
                <td><div align="center">
                  <input name="Name_add" type="text" value=""  onClick="javascript:this.select()"  size="12" required="required">
                </div></td>

                <td><div align="center">
                  <input name="Type_add" type="text" value=""  onClick="javascript:this.select()" size="10" required="required">
                </div></td>
                <td><div align="center">
                  <input name="Model_add" type="number" value="" size="12" required="required">
                </div></td>
                
                <td><div align="center">
                  <select name="Action" id="Action">

                   
                    <option value="AddNew">Add New</option>
                  </select>
                </div></td>
                <td><div align="center">
                  <input type="submit" name="Submit" value="Submit" />
                </div></td>
                </tr>
			  </form>
			  
			  <?php if(sizeof($data)!=0){
			  foreach($data as $d){
			  ?>
			  
			  			  <form action="<?php echo site_url('admin/update_category');?>" method="post" enctype="multipart/form-data">
			  <tr>
                <td><div align="center">    <?php echo $d['ID'];?>                 <input name="ID" type="hidden" value=" <?php echo $d['ID'];?>  ">
                </div></td> 
				

                <td><div align="center">
                  <input name="Name" type="text" value="<?php echo $d['Name'];?>" size="" required="required">
                </div></td>
                <td><div align="center">
                  <input name="Type" type="text" value="<?php echo $d['Type'];?>" size=" " required="required">
                </div></td>
                <td><div align="center">

                  <input name="Model" type="number" value="<?php echo $d['Model'];?>" size=" " required="required">
                </div></td>
                
               
                <td><div align="center">
                  <select name="Action" id="Action">
                    <option value="Update">Update</option>

                    <option value="Delete">Delete</option>
                  </select>
                  <br>
                 </div></td>
                <td><div align="center">
                  <input type="submit" name="Submit" value="Submit" />
                </div></td>
                </tr>

			  </form>
			  
			  <?php }?>
			  <?php }?>