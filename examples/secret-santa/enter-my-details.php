<!doctype html>
<!--[if lt IE 7]> <html class="no-js ie6 oldie" lang="en-GB"> <![endif]-->
<!--[if IE 7]>    <html class="no-js ie7 oldie" lang="en-GB"> <![endif]-->
<!--[if IE 8]>    <html class="no-js ie8 oldie" lang="en-GB"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js" lang="en-GB"> <!--<![endif]-->
<head>
<meta charset="UTF-8" /> 
<meta name="viewport" content="width=device-width,initial-scale=1.0">
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
<title>Enter my Details - Secret Santa</title>
<script type="text/javascript" src="js/jquery.js"></script>
<script type="text/javascript" src="js/library.js"></script>

<style type="text/css">
input[aria-invalid="true"] {
   outline:1px solid red;
}
.srdr {
   position:absolute; left:-9999px;
}
span.errors, input {
   display:block;
}

</style>
</head>
<body id="body">
<h1>Secret Santa - My Details</h1>
<p>Enter your details here to add yourself to the list.</p>
<div id="content" role="main">
<p><span id="error-notification" role="alert" aria-atomic="true"></span></p>

<form action="error-reporting02.php" method="post" name="error-reporting" id="error-reporting">
<p><label for="myname">Name</label>
<input type="text" name="myname" id="myname" aria-required="true" data-v-reqd="Please specify your name">
<span class="errors" id="myname-errors"></span>
</p>
<p><label for="workteam">Work team</label>
<select name="workteam" id="workteam" aria-required="true" data-v-reqd="Please specify your team">
	<option value="0">Please choose...</option>
	<option value="Marketing">Marketing</option>
	<option value="Sales">Sales</option>
	<option value="Finance">Finance</option>
	<option value="Operations">Operations</option>
</select>
<span class="errors" id="workteam-errors"></span>
</p>

<fieldset id="category" class="toggle">
<legend>Gift category selection</legend>
<input type="radio" name="category" id="category-sport" value="Sport" />
<label for="category-sport">Sport</label>
<input type="radio" name="category" id="category-games" value="Games" />
<label for="category-games">Games</label>
<input type="radio" name="category" id="category-beauty" value="Beauty Products" />
<label for="category-beauty">Beauty Products</label>
<input type="radio" name="category" id="category-gift" value="Gift Cards" />
<label for="category-gift">Gift Cards</label>
<input type="radio" name="category" id="category-food" value="Food and Drink" />
<label for="category-food">Food and Drink</label>
<input type="radio" name="category" id="category-random" value="Random" />
<label for="category-random">Random</label>
<span class="errors" id="category-errors"></span>
</fieldset>



<p><input type="submit" name="submit" id="submit" value="Submit"></p>

</form>

</div><!-- End of #content -->
</body>
</html>




