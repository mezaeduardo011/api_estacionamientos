 <!DOCTYPE html>
 <html>
 <head>
    <meta charset="UTF-8">
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
                        <h4 class="text-center">Entrar</h4>
                        <img src="/img/restCatatumbo.png" alt="RestFull <?php echo $system['nameSite'];?>" class="imgFondo">
                        <p><b><?php echo $system['domainSit']?></b> <?php echo $system['descriptS']?></p>
                    </p>
                    <?php if(!empty($mensaje)){?>
                    <div class="alert alert-warning alert-dismissible fade in" role="alert">
                      <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <strong>Alerta! </strong> <?php echo $mensaje;?>.
                </div>
                <?php } ?>

                <form name="form" id="form" class="form-horizontal" enctype="multipart/form-data" method="POST" action="/proceforms">

                    <div class="input-group">
                        <span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
                        <input id="user" type="text" class="form-control" name="user" value="" placeholder="User" required>                                        
                    </div>
                    <br/>
                    <div class="input-group">
                        <span class="input-group-addon"><i class="glyphicon glyphicon-lock"></i></span>
                        <input id="password" type="password" class="form-control" name="password" placeholder="Password" required>
                    </div>                                                                  
                    <br/>
                    <div class="form-group">
                        <!-- Button -->
                        <div class="col-sm-12 controls">
                            <button type="submit" href="#" class="btn btn-primary pull-right"><i class="glyphicon glyphicon-log-in"></i> Log in</button>                          
                        </div>
                    </div>

                </form>     



            </div>
        </div>
    </div>
</div>
</body>
<!-- /.container-fluid -->


    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="/js/jquery-1.10.2.js"></script>
    <script src="/js/bootstrap.min.js"></script>
    <script src="/js/tabs.js"></script>

    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
    <script src="/js/ie10-viewport-bug-workaround.js"></script>
    </html> 
