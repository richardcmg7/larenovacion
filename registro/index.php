<?php
session_start();
session_destroy();
unset($_SESSION);
?>
<!DOCTYPE html>
<html ng-app="index">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>LA RENOVACIÓN</title>
  <link rel="shortcut icon" href="dist/img/mano.ico">
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <!-- Bootstrap 3.3.7 -->
  <link rel="stylesheet" href="bower_components/bootstrap/dist/css/bootstrap.min.css">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="bower_components/font-awesome/css/font-awesome.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="bower_components/Ionicons/css/ionicons.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="dist/css/AdminLTE.min.css">
  <!-- iCheck -->
  <link rel="stylesheet" href="plugins/iCheck/square/blue.css">
  <!-- styeles -->
  <link rel="stylesheet" href="dist/css/estilos.css">

  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->

</head>
<body class="hold-transition login-page bodyLogin" data-ng-controller="AppController as appCtrl">

  <div class="loginFroms">
    <form action="panel.php" method="post">
      <div class="col-md-12 columna4">
        <div class="form-group has-feedback">
          <input type="text" class="form-control" placeholder="Usuario" data-ng-model="appCtrl.usuLogin" ng-enter="appCtrl.ingresar()">
          <span class="glyphicon glyphicon-user form-control-feedback"></span>
        </div>
      </div>
      <div class="col-md-12 columna4">
        <div class="form-group has-feedback">
          <input type="password" class="form-control" placeholder="Constraseña" data-ng-model="appCtrl.usuContra" ng-enter="appCtrl.ingresar()">
          <span class="glyphicon glyphicon-lock form-control-feedback"></span>
        </div>
      </div>
      <div class="col-md-12 columna4">
        <button type="button" class="btn btn-success" data-ng-click="appCtrl.ingresar()">Ingresar</button>
      </div>
    </form>
    <div class="imgBodyLogin"></div>
  </div>

  <footer class="main-footer" style="margin-left: 0px; bottom: 0; z-index: -5000; position: fixed; width: 100%; text-align: center;">
    <strong>Copyright &copy; 2020. </strong> Todo los derechos reservados. <img style="width: 30px; height: 30px; margin-left: 10px; margin-right: 5px;" src="dist/img/mano.jpg"/><b style="font-style: italic; color: #019850;">LA RENOVACIÓN</b>
  </footer>

  <div class="modal modal-danger fade" id="modalValidate" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span></button>
          <h4 class="modal-title">Error</h4>
        </div>
        <div class="modal-body">
          <p>{{appCtrl.mensaje}}</p>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-outline" data-dismiss="modal">Aceptar</button>
        </div>
      </div>
      </div>
  </div>
 

<!-- jQuery 3 -->
<script src="bower_components/jquery/dist/jquery.min.js"></script>
<!-- Bootstrap 3.3.7 -->
<script src="bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
<!-- iCheck -->
<script src="plugins/iCheck/icheck.min.js"></script>
<script>
  $(function () {
    $('input').iCheck({
      checkboxClass: 'icheckbox_square-blue',
      radioClass: 'iradio_square-blue',
      increaseArea: '20%' /* optional */
    });
  });
</script>

<script src="dist/js/proyecto/angular/angular.min.js"></script>
<script src="dist/js/proyecto/index.js"></script>
</body>
</html>
