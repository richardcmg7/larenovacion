<?php
session_start();
if($_SESSION['usuarioLogin']!=null) {
    $usrStr = $_SESSION["usuarioLogin"];
?>
<!DOCTYPE html>
<html ng-app="proyecto">
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
  <!-- AdminLTE Skins. Choose a skin from the css/skins
       folder instead of downloading all of them to reduce the load. -->
  <link rel="stylesheet" href="dist/css/skins/_all-skins.min.css">
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
  <style>
    .example-modal .modal {
      position: relative;
      top: auto;
      bottom: auto;
      right: auto;
      left: auto;
      display: block;
      z-index: 1;
    }

    .example-modal .modal {
      background: transparent !important;
    }
  </style>
  <!-- styeles -->
  <link rel="stylesheet" href="dist/css/estilos.css">
</head>
<body class="hold-transition skin-blue sidebar-mini fixed" data-ng-controller="AppController as appCtrl" data-ng-init="abrirModal()" style="background-color: #fff !important;">
<div class="wrapper">

  <header class="main-header">

    <a href="#/home" class="logo">
      <!-- mini logo for sidebar mini 50x50 pixels -->
      <span class="logo-mini"><img src="dist/img/mano.jpg"/></span>
      <span class="logo-lg" style="font-style: italic; font-size:18px"><img style="width:35px" src="dist/img/mano.jpg"/><b>LA RENOVACIÓN</b></span>
    </a>

    <nav class="navbar navbar-static-top">
       <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button" style="display: none;">
        <span class="sr-only">Toggle navigation</span>
      </a>
      <div class="tituloHome">{{tituloMenu}} <i class="fa fa-chevron-right"></i> {{subtituloMenu}}</div>
      <div class="navbar-custom-menu">
        <ul class="nav navbar-nav">


          <li class="dropdown user user-menu">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
              <img src="{{usuarioGeneral.foto}}" class="user-image" alt="User Image">
              <span class="hidden-xs">{{usuarioGeneral.nombrePersona}}</span>
            </a>
            <ul class="dropdown-menu">

              <li class="user-header">
                <img src="{{usuarioGeneral.foto}}" class="img-circle" alt="User Image" style="width:60px;height:60px;">

                <p>
                  {{usuarioGeneral.nombrePersona}}
                  <small>{{usuarioGeneral.nombrePerfilUsuario}}</small>
                  <small data-ng-show="usuarioGeneral.codigoPerfilUsuario=='2' || usuarioGeneral.codigoPerfilUsuario=='3' || usuarioGeneral.codigoPerfilUsuario=='4'">{{usuarioGeneral.municipioNombre}}</small>
                </p>
              </li>

              <li class="user-footer">
                <div class="pull-left">
                  <a href="#/cambioclave" class="btn btn-default btn-flat">Actualizar Contraseña</a>
                </div>
                <div class="pull-right">
                  <a class="btn btn-default btn-flat" data-ng-click="appCtrl.salir()">Salir</a>
                </div>
              </li>
            </ul>
          </li>

        </ul>
      </div>
    </nav>
  </header>

  <aside class="main-sidebar">

    <section class="sidebar">
      <ul class="sidebar-menu" data-widget="tree">
        <li><a href="#/home"><i class="fa fa-home"></i> Inicio</a></li>
        <?php if($usrStr[0]["tipo"] == 1){ ?>
        <li class="treeview">
          <a href="#">
            <i class="fa fa-gears"></i>
            <span>Administración</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li><a href="#/barriovereda"><i class="fa fa-bars"></i> Barrio/Vereda</a></li>
            <li><a href="#/candidato"><i class="fa fa-bars"></i> Candidato</a></li>
            <li><a href="#/categoria"><i class="fa fa-bars"></i> Categoria Política</a></li>
            <li><a href="#/comuna"><i class="fa fa-bars"></i> Comuna</a></li>
            <li><a href="#/departamento"><i class="fa fa-bars"></i> Departamento</a></li>
            <li><a href="#/discapacidad"><i class="fa fa-bars"></i> Discapacidad</a></li>
            <li><a href="#/estadocivil"><i class="fa fa-bars"></i> Estado Civil</a></li>
            <li><a href="#/estrato"><i class="fa fa-bars"></i> Estrato</a></li>
            <li><a href="#/grupoetnico"><i class="fa fa-bars"></i> Grupo Étnico</a></li>
            <li><a href="#/gruposanguineo"><i class="fa fa-bars"></i> Grupo sanguineo y Factor RH</a></li>
            <li><a href="#/gruposisben"><i class="fa fa-bars"></i> Grupo SISBEN</a></li>
            <li><a href="#/localidad"><i class="fa fa-bars"></i> Localidad</a></li>
            <li><a href="#/mesavotacion"><i class="fa fa-bars"></i> Mesa Votación</a></li>
            <li><a href="#/municipio"><i class="fa fa-bars"></i> Municipio</a></li>
            <li><a href="#/niveleducativo"><i class="fa fa-bars"></i> Nivel Educativo</a></li>
            <li><a href="#/ocupacion"><i class="fa fa-bars"></i> Ocupación</a></li>
            <li><a href="#/orientacionsexual"><i class="fa fa-bars"></i> Orientación Sexual</a></li>
            <li><a href="#/profesion"><i class="fa fa-bars"></i> Profesión</a></li>
            <li><a href="#/puestovotacion"><i class="fa fa-bars"></i> Puesto Votación</a></li>
            <li><a href="#/serviciopublico"><i class="fa fa-bars"></i> Servicio Publico</a></li>
            <li><a href="#/sexo"><i class="fa fa-bars"></i> Sexo</a></li>
            <li><a href="#/tipopersonal"><i class="fa fa-bars"></i> Tipo Personal Apoyo</a></li>
            <li><a href="#/tipovivienda"><i class="fa fa-bars"></i> Tipo Vivienda</a></li>
            <li><a href="#/usuario"><i class="fa fa-bars"></i> Usuario</a></li>
            <li><a href="#/zonaresidencia"><i class="fa fa-bars"></i> Zona Residencia</a></li>
          </ul>
        </li>
        <?php }
        if($usrStr[0]["tipo"] == 1 || $usrStr[0]["tipo"] == 2 || $usrStr[0]["tipo"] == 4){ ?>
        <li class="treeview">
          <a href="#">
            <i class="fa fa-file-photo-o"></i>
            <span>Registro</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li><a href="#/registronuevo"><i class="fa fa-bars"></i> Editar</a></li>
            <li><a href="#/registroalerta"><i class="fa fa-bars"></i> Alertas</a></li>
            <li><a href="#/registroreporte"><i class="fa fa-bars"></i> Reporte</a></li>
          </ul>
        </li>
        <?php }
        if($usrStr[0]["tipo"] == 1 || $usrStr[0]["tipo"] == 3){ ?>
        <li class="treeview">
          <a href="#">
            <i class="fa fa-users"></i>
            <span>Apoyo</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li><a href="#/evento"><i class="fa fa-bars"></i> Nuevo Evento</a></li>
            <li><a href="#/consultarevento"><i class="fa fa-bars"></i> Consultar Eventos </a></li>
          </ul>
        </li>
        <?php }
        if($usrStr[0]["tipo"] == 1 || $usrStr[0]["tipo"] == 4){ ?>
        <li class="treeview">
          <a href="#">
            <i class="fa fa-phone-square"></i>
            <span>Marketing</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li><a href="#/callcenter"><i class="fa fa-bars"></i> Call Center</a></li>
          </ul>
        </li>
        <?php 
        }
        if($usrStr[0]["tipo"] == 1 || $usrStr[0]["tipo"] == 5){ ?>
        <li class="treeview">
          <a href="#">
            <i class="fa fa-gears"></i>
            <span>Escrutinio</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li><a href="#/e24"><i class="fa fa-bars"></i> E24</a></li>
          </ul>
        </li>
        <?php } ?>
      </ul>
    </section>

  </aside>

  <div class="content-wrapper">
      <section class="content">
        <div class="row">
          <ng-viewport></ng-viewport>
        </div>
    </section>
  </div>

  <footer class="main-footer" style="text-align: center;">
    <strong>Copyright &copy; 2020. </strong> Todo los derechos reservados. <img style="width: 30px; height: 30px; margin-left: 10px; margin-right: 5px;" src="dist/img/mano.jpg"/><b style="font-style: italic; color: #019850;">LA RENOVACIÓN</b>
  </footer>
</div>

<div class="modal fade" id="modalCargando" style="background: rgba(245 241 241 / 57%);" data-controls-modal="your_div_id" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog" style="width: 200px !important; margin: 10% auto !important;">
    <img src="dist/img/cargando.gif" />
    </div>
</div>

<div class="modal fade" id="modalLogin" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close" data-ng-click="appCtrl.salir()">
          <span aria-hidden="true">×</span></button>
        <h4 class="modal-title">Sesion expiró</h4>
      </div>
      <div class="modal-body">
        <p>Otra persona ha ingresado con su Usuario y su Contraseña.</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-success" data-ng-click="appCtrl.salir()">Aceptar</button>
      </div>
    </div>
  </div>
</div>

<!-- jQuery 3 -->
<script src="bower_components/jquery/dist/jquery.min.js"></script>
<!-- jQuery UI 1.11.4 -->
<script src="bower_components/jquery-ui/jquery-ui.min.js"></script>
<!-- Bootstrap 3.3.7 -->
<script src="bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
<!-- Sparkline -->
<script src="bower_components/jquery-sparkline/dist/jquery.sparkline.min.js"></script>
<!-- jvectormap -->
<script src="plugins/jvectormap/jquery-jvectormap-1.2.2.min.js"></script>
<script src="plugins/jvectormap/jquery-jvectormap-world-mill-en.js"></script>
<!-- jQuery Knob Chart -->
<script src="bower_components/jquery-knob/dist/jquery.knob.min.js"></script>
<!-- daterangepicker -->
<script src="bower_components/moment/min/moment.min.js"></script>
<script src="bower_components/bootstrap-daterangepicker/daterangepicker.js"></script>
<!-- datepicker -->
<script src="bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js"></script>
<!-- Bootstrap WYSIHTML5 -->
<script src="plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js"></script>
<!-- Slimscroll -->
<script src="bower_components/jquery-slimscroll/jquery.slimscroll.min.js"></script>
<!-- FastClick -->
<script src="bower_components/fastclick/lib/fastclick.js"></script>
<!-- AdminLTE App -->
<script src="dist/js/adminlte.min.js"></script>


<!-- proyecto -->

<script src="dist/js/proyecto/angular/angular.min.js"></script>
<script src="dist/js/proyecto/angular/angular-animate.min.js"></script>
<script src="dist/js/proyecto/angular/angular-messages.min.js"></script>
<script src="dist/js/proyecto/angular/angular-resource.min.js"></script>
<script src="dist/js/proyecto/angular/router.es5.min.js"></script>
<script src="dist/js/proyecto/proyecto.js"></script>
<script src="dist/js/proyecto/paginacion/ui-bootstrap.js"></script>
<script src="dist/js/proyecto/paginacion/dirPagination.js"></script>

<script src="components/departamento/departamento.js"></script>
<script src="components/home/home.js"></script>
<script src="components/estadocivil/estadocivil.js"></script>
<script src="components/genero/genero.js"></script>
<script src="components/gruposanguineo/gruposanguineo.js"></script>
<script src="components/sexo/sexo.js"></script>
<script src="components/tipovivienda/tipovivienda.js"></script>
<script src="components/zonaresidencia/zonaresidencia.js"></script>
<script src="components/municipio/municipio.js"></script>
<script src="components/barriovereda/barriovereda.js"></script>
<script src="components/localidad/localidad.js"></script>
<script src="components/comuna/comuna.js"></script>
<script src="components/usuario/usuario.js"></script>
<script src="components/estrato/estrato.js"></script>
<script src="components/gruposisben/gruposisben.js"></script>
<script src="components/serviciopublico/serviciopublico.js"></script>
<script src="components/mesa/mesa.js"></script>
<script src="components/puesto/puesto.js"></script>
<script src="components/discapacidad/discapacidad.js"></script>
<script src="components/niveleducativo/niveleducativo.js"></script>
<script src="components/grupoetnico/grupoetnico.js"></script>
<script src="components/ocupacion/ocupacion.js"></script>
<script src="components/profesion/profesion.js"></script>
<script src="components/candidato/candidato.js"></script>
<script src="components/categoria/categoria.js"></script>
<script src="components/tipopersonal/tipopersonal.js"></script>

<script src="components/registronuevo/registronuevo.js"></script>
<script src="components/registroalerta/registroalerta.js"></script>
<script src="components/registroreporte/registroreporte.js"></script>
<script src="components/cambioclave/cambioclave.js"></script>
<script src="components/evento/evento.js"></script>
<script src="components/consultarevento/consultarevento.js"></script>
<script src="components/seguidor/seguidor.js"></script>
<script src="components/e24/e24.js"></script>

</body>
</html>
<?php
} else {
    header("Location: index.php");
}
?>
