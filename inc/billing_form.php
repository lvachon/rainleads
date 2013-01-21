<script>
function changeCountry() {
		if ($('#country').val()=='US') {
			$('#state').show();
			$('.can').hide();
			$('.us').show();
			$('#state').val('AK');
		} else if ($('#country').val()=='CA') {
			$('#state').show();
			$('.us').hide();
			$('.can').show();
			$('#state').val('AB');
		} else {
			$('#state').hide();
			$('.us').hide();
			$('.can').hide();
		}
	}
	
function checkPromo(){
	$.post("/account/checkpromo.php",{"promo":$("#promo").val()},function(data){$("#promostatus").html(data);});
}
var i=0;
</script>


<style>
#cards img {
	opacity: .7;
}
#cards img.sel {
	opacity: 1;
}
#creditcards {
	margin-left: 20px;
	width: 500px;
}
.paySub {
	background: url('paySub.png') bottom left repeat-x #fff;
	border: none;
	border: 1px solid #ccc;
	-webkit-appearance: none;
	border-radius: 3px;
	font-size: 13px;
	padding: 8px 12px;
	font-weight: bold;
	color: #777;
	text-shadow: #fff 0 1px;
}
#billing {
	width: 200px;
}
.us{
	display:none;
}
input[type=text] {
	border: 1px solid #ccc;
	padding: 2px;
	color: #333;
}
.left {float: left;}
.right {float: right;}
.clear {clear: both;}
</style>
<div id="billing_form_2" style="margin-left:40px; " >
<div  id="billing" class="left">
<strong>Billing Info</strong>
<br/><br/>
<table cellpadding="5" class="payment-form" style="font-size:12px;" id="settings_table">
	<tr>
		<td>First Name:<span class="red"><strong>*</strong></span><div class="textBox"><input type='text' class="bform" name='bfname' style="width:180px;" value="<?=htmlentities($viewer->data['fname']);?>"/></div></td>
	</tr>
	<tr>
		<td>Last Name:<span class="red"><strong>*</strong></span><div class="textBox"><input type='text' class="bform" name='blname' style="width:180px;" value="<?=htmlentities($viewer->data['lname']);?>"/></div></td>
	</tr>
	<tr>
		<td>Email:<div class="textBox"><input type='text' class="bform" name='email' style="width:180px;" value="<?=htmlentities($viewer->email);?>"/></div></td>
	</tr>
	<tr>
		<td>Phone Number:<div class="textBox"><input type='text' name='phone' class="bform" style="width:180px;" value="<?=htmlentities($viewer->data['phone']);?>"/></div></td>
	</tr>
	<tr>
		<td>Address:<div class="textBox"><input type='text' class="bform" name='baddress1' style="width:180px;" value="<?=htmlentities($viewer->data['address1']);?>" /></div></td>
	</tr>
	<tr>
		<td>City:<div class="textBox"><input type='text' name='bcity' class="bform"  style="width:180px;"  value="<?=htmlentities($viewer->data['city']);?>"/></div></td>
    </tr>
    <tr>
		<td>State:<div class="textBox"><select name='bstate' class="bform"  style="width:190px;">
  					<?php $selected = $_GET['bstate']; include 'state_select.php';?>
                    </select></div>
        </td>
    </tr>
    <tr>
		<td>Country:<div class="textBox"><select name='bcountry' class="bform"  style="width:190px;">
  					<?php $selected = $_GET['bcountry']; include 'country_select.php';?>
                    </select></div>
        </td>
    </tr>
    <tr>
		<td>Postal Code:<span class="red"><strong>*</strong></span><div class="textBox"><input type='text' name='bzip' class="bform" style='width:78px;'  value="<?=htmlentities($viewer->data['zip']);?>"/></div></td>
	</tr>
</table>
</div>
<div  id="creditcards" class="left">
<strong>Shopping Cart Details</strong>
<br/><br/>
<div class="" style=" display:none;" id="total">
	<table width="100%" class="striped-table" cellpadding="4">
		<tr>
			<td>30 Days <span id="planname"></span> Access</td>
			<td width="120" align="right"><span id="price"></span></td>
    	</tr>
    </table>
</div>
<br/><br/>
<strong>Credit Card Details</strong>
<br/><br/>
<table cellpadding="5" id="settings_table" class="payment-form" style="font-size:12px;">
	<tr>
		<td>
			Card Number:<span class="red"><strong>*</strong></span><br/>
			<div class="textBox "><input class="bform" style="width:220px;" type="text" name="ccnum" /></div>
			<small>Use only numbers, no dashes or spaces</small><br />
            <div id="cards">
            	<img src="../img/visa.png" />
            	<img src="../img/mastercard.png" />
                <img src="../img/discover.png" />
                <img src="../img/amex.png" />
                <img src="../img/card.png" />
            </div>
			
		</td>
	</tr>
	
	<tr>
		<td>
			Expiration Date:<span class="red"><strong>*</strong></span><br/>
			<select name="expmo" style="width:100px;">
                <option value="01">Jan.01</option>
				<option value="02">Feb.02</option>
				<option value="03">Mar.03</option>
				<option value="04">Apr.04</option>
				<option value="05">May.05</option>
				<option value="06">Jun.06</option>
				<option value="07">Jul.07</option>
				<option value="08">Aug.08</option>
				<option value="09">Sep.09</option>
				<option value="10">Oct.10</option>
				<option value="11">Nov.11</option>
				<option value="12">Dec.12</option>
			</select> &nbsp;
			<select name="expyr" style="width:100px;">
            	<?php foreach(range(date('Y'),date('Y')+9) as $y){?>
                	<option value="<?=substr($y,2)?>"><?=$y?></option>
                <?php } ?>
			</select>
		</td>
	</tr>
	<tr valign="bottom">
		<td>
			Card Security Code:<span class="red"><strong>*</strong></span><br/>
			<div class="textBox" style="float:left;"><input  class="bform" style="width:90px;" type="text" name="ccv" /></div><img src="../img/back.png" class="left" style="margin-left:3px;"/>
			&nbsp;
			<div class="clear"></div>
		</td>
	</tr>
	<?php if(($account->membership=="free" && $account->expiration>0)||!$account->id){?>
	<tr><td>Promo Code:</td></tr><tr><td><input type='text' name='promo' id='promo' onchange="clearTimeout(i);i=setTimeout('checkPromo()',1000);"/></td></tr>
	<tr><td colspan='2' id='promostatus'></td></tr>
	<?php } ?>
	<tr>
		<td><!-- (c) 2005, 2013. Authorize.Net is a registered trademark of CyberSource Corporation --> <div class="AuthorizeNetSeal"> <script type="text/javascript" language="javascript">var ANS_customer_id="f86f50cf-83f8-48c5-9cbb-839d0b4a008b";</script> <script type="text/javascript" language="javascript" src="//verify.authorize.net/anetseal/seal.js" ></script> <a href="http://www.authorize.net/" id="AuthorizeNetText" target="_blank">Merchant Services</a> </div></td>
	</tr>
</table>
</div>
<div class="clear"></div>
</div>
