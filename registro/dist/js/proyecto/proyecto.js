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
      
      //cargar valores de usuario
      vm.objeto = {
        accion: 2
      };
      $http({
          url: "logica/panel.php", 
          method: "POST",
          data: vm.objeto
      }).then(function(response) {
        $rootScope.usuarioGeneral = response.data;
        console.log($rootScope.usuarioGeneral);
        if($rootScope.usuarioGeneral.codigoUsuario == null || $rootScope.usuarioGeneral.nombreUsuario){
          window.open('index.php','_self');
        }else{
          window.open("#/home","_self");
          setInterval(consultarUsuario,10000);
        }
      });

      function cerrarModal(){
        $('#modalCargando').modal('hide');
      }

       function consultarUsuario(){
        vm.objeto = {
            accion: 3
        };
        $http({
            url: "logica/panel.php",
            method: "POST",
            data: vm.objeto
        }).then(function(response) {
          var ejecutar = true;
      
          if(response.data == null || response.data == 'null'){
            ejecutar = false;
          }else{
            if(response.data.length <= 0){
              ejecutar = false;
            }
          }
          if(ejecutar){
            $('#modalLogin').modal('show');
            //vm.salir();
          }
        });
      }

      vm.salir = function(){
        window.open('index.php','_self');
      }

      $router.config([
        //{ path: '/', redirectTo: '/home' },
        { path: '/home', component: 'home' },
        { path: '/departamento', component: 'departamento' },
        { path: '/estadocivil', component: 'estadocivil' },
        { path: '/orientacionsexual', component: 'genero' },
        { path: '/gruposanguineo', component: 'gruposanguineo' },
        { path: '/sexo', component: 'sexo' },
        { path: '/tipovivienda', component: 'tipovivienda' },
        { path: '/zonaresidencia', component: 'zonaresidencia' },
        { path: '/municipio', component: 'municipio' },
        { path: '/barriovereda', component: 'barriovereda' },
        { path: '/localidad', component: 'localidad' },
        { path: '/comuna', component: 'comuna' },
        { path: '/usuario', component: 'usuario' },
        { path: '/estrato', component: 'estrato' },
        { path: '/gruposisben', component: 'gruposisben' },
        { path: '/serviciopublico', component: 'serviciopublico' },
        { path: '/mesavotacion', component: 'mesa' },
        { path: '/puestovotacion', component: 'puesto' },
        { path: '/discapacidad', component: 'discapacidad' },
        { path: '/niveleducativo', component: 'niveleducativo' },
        { path: '/grupoetnico', component: 'grupoetnico' },
        { path: '/ocupacion', component: 'ocupacion' },
        { path: '/profesion', component: 'profesion' },
        { path: '/candidato', component: 'candidato' },
        { path: '/categoria', component: 'categoria' },
        { path: '/registronuevo', component: 'registronuevo' },
        { path: '/registroalerta', component: 'registroalerta' },
        { path: '/registroreporte', component: 'registroreporte' },
        { path: '/cambioclave', component: 'cambioclave' },
        { path: '/tipopersonal', component: 'tipopersonal' },
        { path: '/evento', component: 'evento' },
        { path: '/consultarevento', component: 'consultarevento' },
        { path: '/callcenter', component: 'seguidor' },
        { path: '/e24', component: 'e24' }
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