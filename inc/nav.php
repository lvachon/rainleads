<?php if(in_array($_SERVER['SCRIPT_NAME'],$LOGGED_OUT_PAGES)){?>
<div id="nav">
    <div class="inner">
        <a class="link first" href="index.php">Home</a>
        <a class="link" href="tour.php">Feature Tour</a>
        <!--<a class="link" href="features.php">Features</a>-->
        <a class="link" href="plans.php">Plans & Pricing</a>
        <!--<a class="link" href="customForms.php">Custom Forms</a>  -->
        <a class="link" href="enterprise.php">Enterprise Solutions</a>
        <a class="link" href="contact.php">Contact Us</a>

       <!-- <a class="link" href="mobile.php">Mobile</a>-->
        <!--<a class="link" style="border-right:none;"></a>-->
        <div class="clear"></div>
    </div>
</div>
<?php } ?>