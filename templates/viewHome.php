 <!DOCTYPE html>
<html>
<head><meta http-equiv="Content-Type" content="text/html; charset=gb18030">


<title>Api <?php echo $system['nameSite'];?> - <?php echo $system['titleSite'];?></title>
<link rel="icon" href="/img/favicon.png">

<!-- Bootstrap core CSS -->
<link href="/css/bootstrap.min.css" rel="stylesheet">
<!-- Custom styles for this template -->
<link href="/css/starter-template.css" rel="stylesheet">


</head>
<body>
<div class="container">
    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="box">
                <div class="box-icon">
                <img src="/img/logo.png" alt="<?php echo $system['nameSite'];?>" class="imgLogo">
                </div>
                <div class="info">
                    <h4 class="text-center">API-RestFull</h4>
                    <img src="/img/restCatatumbo.png" alt="RestFull <?php echo $system['nameSite'];?>" class="imgFondo">
                    <p><b><?php echo $system['domainSit']?></b> <?php echo $system['descriptS']?></p>
					</p>
                    <a href="<?php echo $system['loginUrl'];?>" class="btn btn-primary">Entrar</a>
                    <a href="<?php echo $system['documtUrl'];?>" class="btn btn-primary">Documentación del Api</a>
                </div>
            </div>
        </div>
</div>
<?php include('viewCreativeCommons.php');?>
</body>

</html> 
