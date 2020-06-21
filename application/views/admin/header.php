		<script type="text/javascript" src="<?php echo base_url('assets/js/jquery.js');?>"></script>
		<script type="text/javascript" src="<?php echo base_url('assets/js/activatables.js');?>"></script>
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


	<ul id="sddm" >
                <li><a href="<?php echo site_url('user/dashboard');?>">Dashboard</a>                </li>
                <li><a href="<?php echo site_url('user/dashboard');?>">State</a>                </li>
                <li><a href="<?php echo site_url('user/dashboard');?>">District</a>                </li>
                <li><a href="<?php echo site_url('user/dashboard');?>">Child</a>                </li>
				
				
               
              </ul>
         