(function(){
    angular
        .module('proyecto')
        .controller('CambioclaveController', CambioclaveController);

    function CambioclaveController($http, $rootScope) {
        var vm = this;

        $rootScope.tituloMenu = 'Actualizar Contraseña';
      	$rootScope.subtituloMenu = '';
      	vm.mensaje = {};
      	vm.objeto = {};
      	vm.usuario = {};
      	vm.actual = '';
        vm.nueva = '';

      	vm.consultar = consultar;
      	vm.guardar = guardar;

      	consultar();

      	function consultar(){
      		vm.objeto = {
                accion: 2
            };
            $http({
                url: "logica/panel.php", 
                method: "POST",
                data: vm.objeto
            }).then(function(response) {
                vm.usuario = response.data;
            });
      	}

      	function guardar(){

            if(vm.nueva.length < 8){
                vm.mensaje.mensaje = 'El campo Nueva Contraseña debe tener por lo menos 8 caracteres';
                $('#modalError').modal('show');
                return false;
            }

            vm.objeto = {
                accion: 5,
                id: vm.usuario.codigoUsuario,
                usuario: vm.usuario.nombre,
                actual: vm.actual,
                clave: vm.nueva,
                cambio: 2
            };
            $http({
                url: "logica/usuario.php",
                method: "POST",
                data: vm.objeto
            }).then(function(response) {
                vm.mensaje = response.data;
                if(vm.mensaje.resultado){
                    vm.actual = '';
                    vm.nueva = '';
                    $('#modalBien').modal('show');
                }else{
                    $('#modalError').modal('show');
                }
            });
      		
      	}


    }
})();