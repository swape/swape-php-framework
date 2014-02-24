<!doctype html>
<html lang="en">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title><?php echo @$strTitle; ?></title>
    <meta name="author" content="Alireza Balouch"/>
    <link rel="stylesheet" href="<?php echo $strTemplateWebPath; ?>style.min.css" type="text/css" />
    <link href="//netdna.bootstrapcdn.com/font-awesome/4.0.1/css/font-awesome.min.css" rel="stylesheet">
    <?php echo @$strHead; ?>
</head>
<body>
    <header id="topmenu"><?php 
if(@$_SESSION['ufullname'] != ''){
   echo 'Hello ' . $_SESSION['ufullname'];
}
echo @$strTopMenu ?></header>
    <section id="wraper" >
        <div id="menu"><div id="innermenu"><?php 
echo @$strMenu;
if(@$_SESSION['uid'] != ''){
    echo '<a href="?logout=1" class="btn"><i class="fa fa-power-off"></i> Logout</a>';
}
?></div></div>
        <div id="content" ><div id="innercontent"><?php echo @$strContent; ?></div></div>
    </section>

    <script src="//cdnjs.cloudflare.com/ajax/libs/jquery/2.0.3/jquery.min.js" type="text/javascript" ></script>
    <script src="<?php echo $strTemplateWebPath; ?>js.min.js" type="text/javascript"></script>
    <?php echo @$strJs; ?>
</body>
</html>