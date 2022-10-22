(function(){
    'use strict'

    var app = angular.module('index', []);

    app.controller('AppController', AppController);

    function AppController($http) {
      var vm = this;

      vm.usuLogin = '';
      vm.usuContra = '';
      vm.mensaje = '';

      vm.ingresar = function(){
        if(vm.usuLogin == ''){
          vm.mensaje = 'Debe ingresar el Usuario';
          $('#modalValidate').modal('show');
          return false;
        }
        if(vm.usuContra == ''){
          vm.mensaje = 'Debe ingresar la Contraseña';
          $('#modalValidate').modal('show');
          return false;
        }
        vm.objeto = {
          accion: 1,
          usuLogin: vm.usuLogin,
          usuContra: vm.usuContra
        };
        
        $http({
            url: "logica/panel.php",
            method: "POST",
            data: vm.objeto
        }).then(function(response) {
          console.log(response.data);
          if (response.data == false || response.data == 'null'){
            vm.mensaje = 'Usuario y/o Consetraseña incorrectos';
            $('#modalValidate').modal('show');
            //window.open("index.php","_top");
          }else{
              window.open("panel.php","_top");
          }
        });
      }

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

})();