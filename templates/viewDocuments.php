<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
	<meta name="description" content="">
	<meta name="author" content="">
	<link rel="icon" href="/img/favicon.png">

	<title>Api <?php echo $system['nameSite'];?> - <?php echo $system['titleSite'];?></title>

	<!-- Bootstrap core CSS -->
	<link href="/css/bootstrap.min.css" rel="stylesheet">

	<!-- Custom styles for this template -->
	<link href="/css/starter-template.css" rel="stylesheet">
  <link href="/css/jquery-confirm.min.css" rel="stylesheet">

	<!-- Just for debugging purposes. Don't actually copy these 2 lines! -->
	<!--[if lt IE 9]><script src="../../assets/js/ie8-responsive-file-warning.js"></script><![endif]-->
  <script src="/js/ie-emulation-modes-warning.js"></script>

  <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
      <![endif]-->
      <style type="text/css" media="screen">
      	@import url(//maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css);
      </style>
    </head>

    <body>
     <nav class="navbar navbar-inverse navbar-fixed-top">
      <div class="container">
       <div class="navbar-header">
        <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
         <span class="sr-only"><?php echo $system['nameSite']?></span>
         <span class="icon-bar"></span>
         <span class="icon-bar"></span>
         <span class="icon-bar"></span>
       </button>
       <a class="navbar-brand" href="<?php echo $system['apiRestSi']?>">Api RestFull <?php echo $system['nameSite'];?></a>
     </div>
     <div id="navbar" class="collapse navbar-collapse">
      <ul class="nav navbar-nav">

      </ul>
    </div><!--/.nav-collapse -->
  </div>
</nav>
<div class="container">
  <div class="row">
    <div class="col-xs-2 col-sm-2 col-md-2 col-lg-2">
      <div class="box">
        <div class="box-icon">
          <i class="fa fa-5x fa-html5"></i>
        </div>
      </div>
    </div>
    <div class="col-xs-2 col-sm-2 col-md-2 col-lg-2">
      <div class="box">
        <div class="box-icon">
          <i class="fa fa-5x fa-git"></i>
        </div>
      </div>
    </div>
    <div class="col-xs-2 col-sm-2 col-md-2 col-lg-2">
      <div class="box">
        <div class="box-icon">
          <i class="fa fa-5x fa-css3"></i>
        </div>
      </div>
    </div>
    <div class="col-xs-2 col-sm-2 col-md-2 col-lg-2">
      <div class="box">
        <div class="box-icon">
          <i class="fa fa-5x fa-code"></i>
        </div>
      </div>
    </div>
    <div class="col-xs-2 col-sm-2 col-md-2 col-lg-2">
      <div class="box">
        <div class="box-icon">
          <i class="fa fa-5x fa-database"></i>
        </div>
      </div>
    </div>
    <div class="col-xs-2 col-sm-2 col-md-2 col-lg-2">
      <div class="box">
        <div class="box-icon">
          <i class="fa fa-5x fa-puzzle-piece"></i>
        </div>
      </div>
    </div>
  </div>

  <div class="container-fluid">
    <div class="row">
      <div class="col-lg-12">
       <h2 class="page-header">
         <?php echo $system['nameSite'];?>, <small><?php echo $system['detallApi'];?></small>
       </h2>
       <ul class="nav nav-tabs faq-cat-tabs">
        <?php
        $a=1;
        foreach ($apiDoc as $key => $value) {
          if(!is_null($value)){
            if($a==1){
              echo '<li class="active"><a href="#faq-cat-'.$a.'" data-toggle="tab"><b>'.ucwords(str_replace('_',' ',$key)).'</b></a></li>';
            }else{
              echo '<li><a href="#faq-cat-'.$a.'" data-toggle="tab"><b>'.ucwords(str_replace('_',' ',$key)).'</b></a></li>';
            }
            $a++;
          }
        }
        ?>
      </ul>
      <div class="tab-content faq-cat-content">
       <!--inicia la primera-->
       <?php
       $a=1;
       foreach ($apiDoc as $key => $value) {
        //$common->printAll($key);
        if(!is_null($value)){
          if($a==1){?>
          <div id="faq-cat-<?php echo $a;?>" class="tab-pane active in fade" >
            <div id="accordion-cat-<?php echo $a;?>" class="panel-group" >
              <?php }else{ ?>
              <div id="faq-cat-<?php echo $a;?>" class="tab-pane fade" >
                <div id="accordion-cat-<?php echo $a;?>" class="panel-group" >
                  <?php }
                  $b=1;
                  foreach ($value as $key2 => $value2) {
                    $item=$common->getTabsAnnotations($value2);
                    $deta=$common->getTabsService($item);
                    ?>

                    <div class="panel panel-default panel-faq">
                      <div class="panel-heading">
                        <a data-toggle="collapse" data-parent="#accordion-cat-<?php echo $a;?>" href="#faq-cat-<?php echo $a;?>-sub-<?php echo $b;?>">
                          <h4 class="panel-title">
                            <b><?php echo ucwords(str_replace('_',' ',$key)).' - '.$deta->descri; ?></b>
                            <span class="pull-right"><i class="glyphicon glyphicon-plus"></i></span>
                          </h4>
                        </a>
                      </div>
                      <div id="faq-cat-<?php echo $a;?>-sub-<?php echo $b;?>" class="panel-collapse collapse">
                        <div class="panel-body">
                          <?php include('bodyServices.php');?>
                        </div>
                      </div>
                    </div>
                    <?php
                    $b++;
                  }
                  ?>
                  <!--button onclick="testInsertUsuario()">probar</button-->
                </div>
              </div>
              <?php
              $a++;
            }
          }
          ?>
        </div>

      </div>
      <a class="btn" href="/" role="button">
        <button type="button" class="btn btn-primary">Regregar</button>
      </a>

    </div>
  </div>
</div>
</div>
</div>
<!-- /.row -->

</div>

<!-- /.container-fluid -->


    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="/js/jquery-3.1.1.min.js"></script>
    <script src="/js/bootstrap.min.js"></script>
    <script src="/js/tabs.js"></script>
  <script src="/js/jquery-confirm.min.js"></script>

    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
    <script src="/js/ie10-viewport-bug-workaround.js"></script>
  </body>
<script type="text/javascript">
  /*function testInsertUsuario(){
$.confirm({
    title: 'Registro de usuario',
    content: '' +
    '<form action="" class="formName">' +
    '<div class="form-group">' +
    '<label>Nombre</label>' +
    '<input type="text" placeholder="nombre de usuario" class="nombre form-control" required />' +
    '</div>' +    
    '<div class="form-group">' +
    '<label>Correo</label>' +
    '<input type="text" placeholder="correo" class="correo form-control" required />' +
    '</div>' +
    '<div class="form-group">' +
    '<label>Contraseña</label>' +
    '<input type="text" placeholder="contraseña" class="contraseña form-control" required />' +
    '</div>' +
    '<div class="form-group">' +
    '<label>Confirmacion Contraseña</label>' +
    '<input type="text" placeholder="confirmacion contraseña" class="confirmacion_contraseña form-control" required />' +
    '</div>' +
    '<div class="form-group">' +
    '<label>Id perfil</label>' +
    '<select name="select" class="id_perfil form-control">'+
      '<option value="1">Cliente</option>'+ 
      '<option value="2">Freelance</option>'+
    '</select>' +
    '</div>' +    
    '</form>',
    buttons: {
        formSubmit: {
            text: 'Enviar',
            btnClass: 'btn-blue',
            action: function () {
              var nombre = this.$content.find('.nombre').val();
              var correo = this.$content.find('.correo').val();
              var contraseña = this.$content.find('.contraseña').val();
              var confirmacion_contraseña = this.$content.find('.confirmacion_contraseña').val();
              var id_perfil = this.$content.find('.id_perfil').val();
              $.ajax({
                  type: 'POST',
                  url : 'usuarios/insertUsuario',
                  data: { 
                      'nombre': nombre, 
                      'correo': correo, 
                      'contrasena': contraseña, 
                      'confirmacion_contrasena': confirmacion_contraseña, 
                      'id_perfil': id_perfil
                  },
                  success: function(msg){
                      alert(JSON.stringify(msg));
                  }
              });
            }
        },
        cancel: function () {
            //close
        },
    },
    onContentReady: function () {
        // bind to events
        var jc = this;
        this.$content.find('form').on('submit', function (e) {
            // if the user submits the form by pressing enter in the field.
            e.preventDefault();
            jc.$$formSubmit.trigger('click'); // reference the button and click it
        });
    }
})
                                     
  }*/
</script>
  </html>
