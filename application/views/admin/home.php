




<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>BackOffice | Administration</title>
<style type="text/css">
<!--
body {
	margin-top: 0px;
	margin-bottom: 0px;
	margin-left: 0px;
	margin-right: 0px;
}
.style3 {color: #FFFFFF; font-weight: bold; }
body,td,th {
	font-family: Verdana, Arial, Helvetica, sans-serif;
	font-size: 12px;
}
.style4 {color: #FFFFFF}
.styleAnchor {color: #FFFFFF;}
.style5 {
	font-size: 14px;
	font-weight: bold;
}


.dashboard {
border-width:1px;
border-radius:10px;
border-color:#666666;

}
.style6 {color: #FFFF00}
-->
</style></head>

<body>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td bgcolor="#333333"><?php include('header_logout.php');?></td>
  </tr>
  
  <tr>
    <td><table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
        <tr bgcolor="#25000A">
          <td  align="left" valign="top" bgcolor="#999999"><!-- dd menu -->
              <script type="text/javascript">
<!--
var timeout         = 500;
var closetimer		= 0;
var ddmenuitem      = 0;

// open hidden layer
function mopen(id)
{	
	// cancel close timer
	mcancelclosetime();

	// close old layer
	if(ddmenuitem) ddmenuitem.style.visibility = 'hidden';

	// get new layer and show it
	ddmenuitem = document.getElementById(id);
	ddmenuitem.style.visibility = 'visible';
    document.getElementById('stflash1').style.display  = 'none';
	document.getElementById('stflash0').style.display  = 'block';
}
// close showed layer
function mclose()
{
	if(ddmenuitem) ddmenuitem.style.visibility = 'hidden';
	document.getElementById('stflash1').style.display  = 'block';
	document.getElementById('stflash0').style.display  = 'none';
}

// go close timer
function mclosetime()
{
	closetimer = window.setTimeout(mclose, timeout);
}

// cancel close timer
function mcancelclosetime()
{
	if(closetimer)
	{
		window.clearTimeout(closetimer);
		closetimer = null;
	}
}

// close layer when click-out
document.onclick = mclose; 
// -->

          </script>
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
			<?php include('header.php');?>
		<!-- div class="sample" style="margin-bottom: 15px;height:42px;"><span -->
              <!-- /dd -->          </td>
        </tr>
    </table></td>
  </tr>
  <tr>
    <td><table width="95%" border="1"  align="center" cellpadding="10" cellspacing="0" bordercolor="#FFFFFF" class="dashboard" style="border-radius:10px" >
      <tr>
        <td><table width="100%" border="0" cellpadding="10" cellspacing="0" bgcolor="#CCCCCC">
          <tr>
            <td bgcolor="#CCCCCC"><span class="style5">Name </span>: <?php echo $data['first_name'].' '.$data['last_name'];?></td>
            <td bgcolor="#CCCCCC"><span class="style5">Organization </span>: <?php echo $data['organization'];?></td>
            <td bgcolor="#CCCCCC"><span class="style5">Desgnation </span>: <?php echo $data['desgnation'];?></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td>
		
				
		
		<table width="100%" border="0" cellspacing="0" cellpadding="10">
			<tr bgcolor="#EFEFEF">
				<td width="10"><img src="<?php echo base_url();?>assets/images/<?php echo $data['photo'];?>" alt="Image"  /></td>
            
			</tr>
        
        </table>
		
		
				</td>
      </tr>
    </table></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
  </tr>
</table>
</body>
</html>
