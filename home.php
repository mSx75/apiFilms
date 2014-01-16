<!DOCTYPE html>
<html>
<head>
	<title>apiFilm</title>
	<style type="text/css">
	*{padding:0; margin:0; outline: none;}
	body{font-family: arial; font-size: 1em;}
	.wrap{margin:0 auto; width:100%; padding:70px 0;}
	.container{margin:0 auto; width:1024px;}
	.backdark{background: #222; border-bottom:10px solid #eee;}
	.fb{width:270px; margin:70px auto 0 auto;}
	h1{text-align: center; font-size:3em; color:#eee;}
	a{padding:20px 50px;  background-color:#3B5998; text-decoration: none; color:#fff;}
	</style>
</head>
<body>
<?php 
	$httpURL 	= $_SERVER['REQUEST_URI'];
 ?>
 <div class="wrap backdark">
 	<div class="container">
 		<h1>ApiFilms</h1>
 	</div>
 </div>
 <div class="wrap">
 	<div class="container fb">
 	<?php  //if(isset($_REQUEST['token_access'])) :?>
 	<?php //echo $_REQUEST['token_access']; ?>
 	<?php //else : ?>	
 		<a href="<?php echo $httpURL ?>/login">Connect with Facebook</a>
 	<?php //endif ; ?>
 	</div>
 </div>
</body>
</html>