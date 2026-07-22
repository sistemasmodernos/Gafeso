
<html>
<head>
<link rel="stylesheet" href="css/general.css" type="text/css" />
  		<link type="text/css" href="css/redmond/jquery-ui-1.8.13.custom.css" rel="stylesheet" />
        <link type="text/css" href="css/jquery-ui-numeric.css" rel="stylesheet" />
        <link type="text/css" href="css/buses.css" rel="stylesheet" />        
        <script type="text/javascript" src="js/sfunciones.js"></script>
		<script type="text/javascript" src="js/jquery-1.5.1.min.js"></script>
		<script type="text/javascript" src="js/jquery-ui-1.8.13.custom.min.js"></script>
        <script type="text/javascript" src="js/jquery.ui.datepicker-es.js"></script>
        <script type="text/javascript" src="js/jquery-ui-numeric.js"></script>
        <script type="text/javascript" src="js/jquery-ui-numeric-min.js"></script>
</head>
<body onload='marcar();'>
<div>
<div id='formabus'>
<table width="180" border="0" align="center" cellpadding="0" cellspacing="0" class="tablabus48">
<tr>
		<td width="10" height="23">&nbsp;</td>
        <td width="40">&nbsp;</td>
        <td width="40">&nbsp;</td>
        <td width="31">&nbsp;</td>
        <td width="40">&nbsp;</td>
        <td width="40">&nbsp;</td>
        <td >&nbsp;</td>
    </tr>
    <tr>
		<td height="35">&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
    </tr>
    <tr>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
    </tr>
    <tr>
        <td height="30">&nbsp;</td>
		<td class='libre' id="asiento1">1</td>
		<td class='libre' id="asiento2">2</td>
		<td id="asiento_no">&nbsp;</td>
		<td id="asiento_no">&nbsp;</td>		
		<td class='libre' id="asiento3">3</td>		
		<td id="asiento_no" >&nbsp;</td>
	</tr>
		<tr>
		<td height="31">&nbsp;</td>
        <td id="asiento7" class='libre'>7</td>        
        <td id="asiento8" class='libre'>8</td>
        <td id="asiento_no">&nbsp;</td>
        <td id="asiento6" height="31" class='libre'>6</td>
        <td id="asiento5" height="31" class='libre'>5</td>
        <td id="asiento_no" >&nbsp;</td>
	</tr>
	<tr>
		<td id="asiento_no" height="31">&nbsp;</td>
		<td id="asiento11" class='libree'>11e</td>
		<td id="asiento12" class='libree'>12e</td>
        	<td id="asiento_no" >&nbsp;</td>
		<td id="asiento10" class='libre' >10</td>
		<td id="asiento9" class='libre'>9</td>
		<td id="asiento_no" >&nbsp;</td>
	</tr>
    <tr>
		<td id="asiento_no" height="31">&nbsp;</td>    
		<td id="asiento15"  class='libre'>15</td>
		<td id="asiento16"  class='libre'>16</td>
		<td id="asiento_no" >&nbsp;</td>
		<td id="asiento14"  class='libre'>14</td>
	    	<td id="asiento13"  class='libre'>13</td>
		<td id="asiento_no" >&nbsp;</td>
	</tr>  
    <tr>
		<td id="asiento_no" height="33">&nbsp;</td>
		<td id="asiento19"  class='libre'>19</td>
		<td id="asiento20"  class='libre'>20</td>
		<td id="asiento_no" >&nbsp;</td>
		<td id="asiento18"  class='libre'>18</td>
		<td id="asiento17"  class='libre'>17</td>
		<td id="asiento_no" >&nbsp;</td>
	</tr>          
    <tr>
		<td id="asiento_no" height="31">&nbsp;</td>
		<td id="asiento23" class='libre'>23</td>
		<td id="asiento24" class='libre'>24</td>
		<td id="asiento_no" >&nbsp;</td>
		<td id="asiento22"  class='libre'>22</td>
		<td id="asiento21"  class='libre'>21</td>
		<td id="asiento_no">&nbsp;</td>
	</tr>
	<tr>
		<td id="asiento_no" height="32">&nbsp;</td>
		<td id="asiento27" class='libre'>27</td>
		<td id="asiento28" class='libre'>28</td>
		<td id="asiento_no">&nbsp;</td>
		<td id="asiento26" class='libre'>26</td>
		<td id="asiento25" class='libre'>25</td>
		<td id="asiento_no">&nbsp;</td>		
	</tr>
	<tr>
		<td id="asiento_no" height="32">&nbsp;</td>
		<td id="asiento31" class='libre'>31</td>
		<td id="asiento32" class='libre'>32</td>

		<td id="asiento_no" >&nbsp;</td>
		<td id="asiento30" class='libre'>30</td>
		<td id="asiento29" class='libre'>29</td>
		<td id="asiento_no">&nbsp;</td>
	</tr>

	<tr>
		<td id="asiento_no" height="32">&nbsp;</td>
		<td id="asiento35" class='libre'>35</td>
		<td id="asiento36" class='libre'>36</td>	
		<td id="asiento_no">&nbsp;</td>
		<td id="asiento34" class='libre'>34</td>		
		<td id="asiento33" class='libre'>33</td>
		<td id="asiento_no">&nbsp;</td>		
	</tr>
	<tr>
		<td id="asiento_no" height="32">&nbsp;</td>
		<td id="asiento39" class='libre'>39</td>	
		<td id="asiento40" class='libre'>40</td>		
		<td id="asiento_no">&nbsp;</td>
		<td id="asiento38" class='libre'>38</td>
		<td id="asiento37" class='libre'>37</td>	
		<td id="asiento_no">&nbsp;</td>
	</tr>
	<tr>
		<td id="asiento_no" height="32">&nbsp;</td>
		<td id="asiento41" class='libre'>41</td>		
		<td id="asiento42" class='libre'>42</td>	
		<td id="asiento_no" >&nbsp;</td>
		<td id="asiento_no" >&nbsp;</td>
		<td id="asiento_no" >&nbsp;</td>
		<td id="asiento_no">&nbsp;</td>
	</tr>
	<tr>
		<td id="asiento_no" height="32">&nbsp;</td>
		<td id="asiento43" class='libre'>43</td>
		<td id="asiento44" class='libre'>44</td>
		<td id="asiento_no">&nbsp;</td>
		<td id="asiento_no">&nbsp;</td>
		<td id="asiento_no">&nbsp;</td>
		<td id="asiento_no">&nbsp;</td>
	</tr>

	<tr>
		<td id="asiento_no" height="32">&nbsp;</td>
		<td id="asiento45" class='libre'>45</td>
		<td id="asiento46" class='libre'>46</td>
		<td id="asiento_no">&nbsp;</td>
		<td id="asiento_no">&nbsp;</td>
		<td id="asiento_no">&nbsp;</td>
		<td id="asiento_no">&nbsp;</td>
	</tr>

	<tr>
		<td id="asiento_no" height="32">&nbsp;</td>
		<td id="asiento47" class='libre'>47</td>
		<td id="asiento48" class='libre'>48</td>
		<td id="asiento_no">&nbsp;</td>
		<td id="asiento_no">&nbsp;</td>
		<td id="asiento_no">&nbsp;</td>
		<td id="asiento_no">&nbsp;</td>
	</tr>


	<tr>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
	</tr>
	<tr>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
	</tr>
</table>        
</div>
</div>
</body>        
</html>
