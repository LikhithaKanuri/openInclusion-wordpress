<!doctype html>
<!--[if lt IE 7]> <html class="no-js ie6 oldie" lang="en-GB"> <![endif]-->
<!--[if IE 7]>    <html class="no-js ie7 oldie" lang="en-GB"> <![endif]-->
<!--[if IE 8]>    <html class="no-js ie8 oldie" lang="en-GB"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js" lang="en-GB"> <!--<![endif]-->
<head>
<meta charset="UTF-8" /> 
<meta name="viewport" content="width=device-width,initial-scale=1.0">
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
<title>Error Reporting</title>
<script src="http://research-dev/accessibility-training/jquery/jquery.js"></script>
<script type="text/javascript" src="library.js"></script>

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
<title>Error Reporting in Forms</title>
<div id="content" role="main">
<form action="error-reporting01.php" method="post" name="error-reporting" id="error-reporting">

<p>First name
<input type="text" name="input1" id="input1" aria-required="true" data-v-reqd="Please specify first name">
<span class="errors" id="errors1"></span>
</p>
<p>Last name
<input type="text" name="input2" id="input2" aria-required="true" data-v-reqd="Please specify last name">
<span class="errors" id="errors2"></span>
</p>
<p>Town or City
<input type="text" name="input3" id="input3" aria-required="true" data-v-reqd="Please specify town or city">
<span class="errors" id="errors3"></span>
</p>
<p>Postcode
<input type="text" name="input4" id="input4" aria-required="true" data-v-reqd="Please specify postcode">
<span class="errors" id="errors4"></span>
</p>
<p><input type="submit" name="submit" id="submit" value="Submit"></p>

</form>

</div><!-- End of #content -->
</body>
</html>




