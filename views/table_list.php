<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?><!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>Example</title>

	<style type="text/css">

	::selection { background-color: #E13300; color: white; }
	::-moz-selection { background-color: #E13300; color: white; }

	body {
		background-color: #fff;
		margin: 40px;
		font: 13px/20px normal Helvetica, Arial, sans-serif;
		color: #4F5155;
	}

	a {
		color: #003399;
		background-color: transparent;
		font-weight: normal;
	}


	#body {
		margin: 0 15px 0 15px;
	}
.table{
	

}
.table th, td{
	padding:4px;
	border:1px solid #666666;
}
	</style>
</head>
<body>

<div id="container">
	<h1>Example</h1>
 
	<?php 
	echo $table_list;
	  
	?>
</div>

</body>
</html>