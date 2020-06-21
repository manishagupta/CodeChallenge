<table width="100%" border="0" cellspacing="0" cellpadding="10">
        <tr>
          <td><span class="style3">BackOffice | </span><span class="style4">ADMINISTRATION</span></td>
          <td width="300" class="style4"><div align="center"><img src="<?php echo base_url();?>/assets/images/lock.png" width="16" height="16" align="absbottom" /> You are logged in as<span class="style6"> <?php echo $this->session->userdata('username_admin');?> </span>| <a href="<?php echo site_url('admin/logout');?>" class="style4">Logout</a> </div></td>

          <td width="150" class="style4"><div align="center"> <a href="<?php echo site_url('admin/');?>" target="_blank" class="styleAnchor">Store Front</a> </div></td>
        </tr>
      </table>