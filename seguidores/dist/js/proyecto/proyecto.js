(function(){
    'use strict'

    var app = angular.module('proyecto', ['ngNewRouter','ngMessages','ngResource','ngAnimate','angularUtils.directives.dirPagination','ui.bootstrap']);

    app.controller('AppController', AppController);

    function AppController($http, $rootScope, $router) {
      var vm = this;

      $rootScope.tituloMenu = 'Home';
      $rootScope.subtituloMenu = '';

      $rootScope.usuarioGeneral = {};
      $rootScope.bodegaGeneral = [];
      $rootScope.currentPage = 1;
      $rootScope.pageSize = 50;
      $rootScope.pageSizeV = 10;
      $rootScope.comprado = 0;
      $rootScope.paraVender = 0;
      $rootScope.documentoPersona = 0;

      vm.objeto = {};
      vm.mensaje = {};
      vm.documento = '';
      vm.documentoBus = ''; 
      vm.primerNombre = '';
      vm.segundoNombre = '';
      vm.primerApellido = '';
      vm.segundoApellido = '';
      vm.fechaNacimiento = '';
      vm.grupoSanguineo = {};
      vm.sexo = {};
      vm.orientacionSexual = {};
      vm.estadoCivil = {};
      vm.correoElectronico = '';
      vm.numeroCelular = '';
      vm.numeroWhatsapp = '';
      vm.departamento = {};
      vm.municipio = {};
      vm.lider = {};
      vm.autorizacion = false;
      vm.seleccionado = {};
      vm.habilitarFormulario = false;
      vm.habilitarAutorizacion = false;

      vm.grupoSanguineoLista = [];
      vm.sexoLista = [];
      vm.orientacionSexualLista = [];
      vm.estadoCivilLista = [];
      vm.departamentoLista = [];
      vm.municipioLista = [];
      vm.liderLista = [];

      vm.buscarPersona = buscarPersona;
      vm.guardar = guardar;
      vm.limpiar = limpiar;
      vm.cargarMunicipio = cargarMunicipio;
      vm.cargarLider = cargarLider;

      inicio();

      function inicio(){
          vm.grupoSanguineoLista = [];
          vm.objeto = {
              accion: 1
          };
          $http({
              url: "logica/registro.php",
              method: "POST",
              data: vm.objeto
          }).then(function(response) {
              if(response.data == null || response.data == 'null'){
                  vm.grupoSanguineoLista = [];
              }else{
                  vm.grupoSanguineoLista = response.data;
              }
          });

          vm.sexoLista = [];
          vm.objeto = {
              accion: 2
          };
          $http({
              url: "logica/registro.php",
              method: "POST",
              data: vm.objeto
          }).then(function(response) {
              if(response.data == null || response.data == 'null'){
                  vm.sexoLista = [];
              }else{
                  vm.sexoLista = response.data;
              }
          });

          vm.orientacionSexualLista = [];
          vm.objeto = {
              accion: 3
          };
          $http({
              url: "logica/registro.php",
              method: "POST",
              data: vm.objeto
          }).then(function(response) {
              if(response.data == null || response.data == 'null'){
                  vm.orientacionSexualLista = [];
              }else{
                  vm.orientacionSexualLista = response.data;
              }
          });

          vm.estadoCivilLista = [];
          vm.objeto = {
              accion: 4
          };
          $http({
              url: "logica/registro.php",
              method: "POST",
              data: vm.objeto
          }).then(function(response) {
              if(response.data == null || response.data == 'null'){
                  vm.estadoCivilLista = [];
              }else{
                  vm.estadoCivilLista = response.data;
              }
          });

          vm.departamentoLista = [];
          vm.objeto = {
              accion: 7
          };
          $http({
              url: "logica/registro.php",
              method: "POST",
              data: vm.objeto
          }).then(function(response) {
              if(response.data == null || response.data == 'null'){
                  vm.departamentoLista = [];
              }else{
                  vm.departamentoLista = response.data;
              }
          });
      }

      function buscarPersona(){
        if(vm.documentoBus == ''){
            return false;
        }
        vm.objeto = {
            accion: 5,
            documento: vm.documentoBus
        };
        $http({
            url: "logica/registro.php",
            method: "POST",
            data: vm.objeto
        }).then(function(response) {
            if(response.data.length > 0){
                vm.habilitarFormulario = false;
                vm.seleccionado = response.data[0];
                vm.documento = response.data[0].documento;
                vm.primerNombre = response.data[0].primerNombre;
                vm.segundoNombre = response.data[0].segundoNombre;
                vm.primerApellido = response.data[0].primerApellido;
                vm.segundoApellido = response.data[0].segundoApellido;
                vm.fechaNacimiento = response.data[0].fechaNacimiento;
                vm.grupoSanguineo = response.data[0].grupoSanguineo;
                vm.orientacionSexual = response.data[0].orientacionSexual;
                vm.sexo = response.data[0].sexo;
                vm.estadoCivil = response.data[0].estadoCivil;
                vm.correoElectronico = response.data[0].correoElectronico;
                vm.numeroCelular = response.data[0].numeroCelular;
                vm.numeroWhatsapp = response.data[0].numeroWhatsapp;
                vm.autorizacion = false;
                if(response.data[0].autorizacion == 'SI'){
                  vm.autorizacion = true;
                }
                if(vm.autorizacion){
                  vm.habilitarAutorizacion = false;
                  $("#validacionTexto").html('* El Usuario '+ vm.documento+' - '+vm.primerNombre+' '+vm.segundoNombre+' '+vm.primerApellido+' '+vm.segundoApellido+' ya se encuentra registrado.');
                  $('#modalValidate').modal('show');
                }else{
                  vm.habilitarAutorizacion = true;
                  $("#validacionTexto").html('* El Usuario '+ vm.documento+' - '+vm.primerNombre+' '+vm.segundoNombre+' '+vm.primerApellido+' '+vm.segundoApellido+' ya se encuentra registrado. <br/>* El campo AUTORIZACIÓN PARA LA RECOLECCIÓN Y TRATAMIENTO DE DATOS PERSONALES es obligatorio.');
                  $('#modalValidate').modal('show');
                }
            }else{
                vm.documento = vm.documentoBus;
                vm.habilitarFormulario = true;
                vm.habilitarAutorizacion = true;
                limpiar(false);
            }
        });
      }

      function limpiar(opcion){
        if(opcion){
          vm.documento = '';
          vm.documentoBus = '';
          vm.habilitarFormulario = false;
          vm.habilitarAutorizacion = false;
        }
        vm.primerNombre = '';
        vm.segundoNombre = '';
        vm.primerApellido = '';
        vm.segundoApellido = '';
        vm.fechaNacimiento = '';
        vm.grupoSanguineo = {};
        vm.sexo = {};
        vm.orientacionSexual = {};
        vm.estadoCivil = {};
        vm.correoElectronico = '';
        vm.numeroCelular = '';
        vm.numeroWhatsapp = '';
        vm.autorizacion = false;
        vm.seleccionado = {};
        vm.departamento = {};
        vm.municipio = {};
        vm.lider = {};
      }

      function cargarMunicipio(){
        vm.municipioLista = [];
        vm.objeto = {
            accion: 8,
            departamento: vm.departamento
        };
        $http({
            url: "logica/registro.php",
            method: "POST",
            data: vm.objeto
        }).then(function(response) {
            if(response.data == null || response.data == 'null'){
                vm.municipioLista = [];
            }else{
                vm.municipioLista = response.data;
            }
        });
      }

      function cargarLider(){
        vm.liderLista = [];
        vm.objeto = {
            accion: 9,
            municipio: vm.municipio
        };
        $http({
            url: "logica/registro.php",
            method: "POST",
            data: vm.objeto
        }).then(function(response) {
            if(response.data == null || response.data == 'null'){
                vm.liderLista = [];
            }else{
                vm.liderLista = response.data;
            }
        });
      }

      function guardar(){
        vm.fechaNacimiento = $('#datepicker').val();
        var mensaje = '';
        if(vm.seleccionado.id != null && vm.seleccionado.autorizacion=='NO'){
          if(vm.autorizacion == false){
              mensaje += "* El campo AUTORIZACIÓN PARA LA RECOLECCIÓN Y TRATAMIENTO DE DATOS PERSONALES es obligatorio." + '<br/>';
          }
        }else{
          if(vm.documento == ''){
              mensaje += "* El campo Nùmero de Cedula es obligatorio." + '<br/>';
          }
          if(vm.primerNombre == ''){
              mensaje += "* El campo Primer Nombre es obligatorio." + '<br/>';
          }
          if(vm.primerApellido == ''){
              mensaje += "* El campo Primer Apellido es obligatorio." + '<br/>';
          }
          if(vm.fechaNacimiento == ''){
              mensaje += "* El campo Fecha de Nacimiento es obligatorio." + '<br/>';
          }
          if(vm.grupoSanguineo.id == null){
              mensaje += "* El campo Grupo Sanguineo y Factor RH es obligatorio." + '<br/>';
          }
          if(vm.sexo.id == null){
              mensaje += "* El campo Sexo es obligatorio." + '<br/>';
          }
          if(vm.orientacionSexual.id == null){
              mensaje += "* El campo Orientacion Sexual es obligatorio." + '<br/>';
          }
          if(vm.estadoCivil.id == null){
              mensaje += " * El campo Estado Civil es obligatorio." + '<br/>';
          }
          if(vm.numeroCelular == '' && vm.seleccionado.id == null){
              mensaje += "* El campo Nùmero de Celular es obligatorio." + '<br/>';
          }
          if(vm.autorizacion == false){
              mensaje += "* El campo AUTORIZACIÓN PARA LA RECOLECCIÓN Y TRATAMIENTO DE DATOS PERSONALES es obligatorio." + '<br/>';
          }
          if(vm.departamento.id == null){
              mensaje += "* El campo Departamento es obligatorio." + '<br/>';
          }
          if(vm.municipio.id == null){
              mensaje += "* El campo Municipio es obligatorio." + '<br/>';
          }
          if(vm.lider.id == null){
              mensaje += "* El campo Lider es obligatorio." + '<br/>';
          }
        }
  
        if(mensaje != ''){
          $("#validacionTexto").html(mensaje);
          $('#modalValidate').modal('show');
        }else{
          $('#modalCargando').modal('show');
          vm.objeto = {
            accion: 6,
            id: vm.seleccionado.id,
            documento: vm.documento,
            primerNombre: vm.primerNombre,
            segundoNombre: vm.segundoNombre,
            primerApellido: vm.primerApellido,
            segundoApellido: vm.segundoApellido,
            fechaNacimiento: vm.fechaNacimiento,
            grupoSanguineo: vm.grupoSanguineo,
            sexo: vm.sexo,
            orientacionSexual: vm.orientacionSexual,
            estadoCivil: vm.estadoCivil,
            correoElectronico: vm.correoElectronico,
            numeroCelular: vm.numeroCelular,
            numeroWhatsapp: vm.numeroWhatsapp,
            municipio: vm.municipio,
            lider: vm.lider
          }
          $http({
              url: "logica/registro.php",
              method: "POST",
              data: vm.objeto
          }).then(function(response) {
              vm.mensaje = response.data;
              $('#modalCargando').modal('hide');
              if(vm.mensaje.resultado){
                  $('#modalBien').modal('show');
                  limpiar(true);
              }else{
                  $('#modalError').modal('show');
              }
          });
        }
      }

      $router.config([
        //{ path: '/', redirectTo: '/home' },
        //{ path: '/home', component: 'home' },
      ]);

    }

    app.directive('ngEnter', function () {
      return function (scope, element, attrs) {
        element.bind("keydown keypress", function (event) {
          if(event.which === 13) {
            scope.$apply(function (){
                scope.$eval(attrs.ngEnter);
            });

            event.preventDefault();
          }
        });
      };
    });

    app.directive('numbersOnly', function () {
      return {
          require: 'ngModel',
          link: function (scope, element, attr, ngModelCtrl) {
              function fromUser(text) {
                  if (text) {
                      var transformedInput = text.replace(/[^0-9]/g, '');

                      if (transformedInput !== text) {
                          ngModelCtrl.$setViewValue(transformedInput);
                          ngModelCtrl.$render();
                      }
                      return transformedInput;
                  }
                  return '';
              }            
              ngModelCtrl.$parsers.push(fromUser);
          }
      };
  });

  app.filter('cortarTexto', function(){
    return function(input, limit){
      return (input.length > limit) ? input.substr(0, limit)+'...' : input;
    };
  })

  app.filter('pasarMayusculas', function(){
    return function(input){
      return input.toUpperCase();
    };
  })

  app.filter('noFractionCurrency',
    [ '$filter', '$locale', function(filter, locale) {
      var currencyFilter = filter('currency');
      var formats = locale.NUMBER_FORMATS;
      return function(amount, currencySymbol) {
        var value = currencyFilter(amount, currencySymbol);
        var sep = value.indexOf(formats.DECIMAL_SEP);
        //console.log(amount, value);
        if(amount >= 0) { 
          return value.substring(0, sep);
        }
        return value.substring(0, sep) + ')';
      };
    } ]);


})();