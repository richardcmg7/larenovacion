(function(){
    angular
        .module('proyecto')
        .controller('RegistroalertaController', RegistroalertaController);

    function RegistroalertaController($http, $rootScope) {
        var vm = this;

        $rootScope.tituloMenu = 'Registro';
      	$rootScope.subtituloMenu = 'Alertas';
      	vm.mensaje = {};
      	vm.objeto = {};
      	vm.lista = [];
      	vm.seleccionado = {};

      	vm.consultar = consultar;
        vm.seleccionar = seleccionar;

      	consultar();

      	function consultar(){
      		vm.lista = [];
            vm.objeto = {
                accion: 3
            };
            $http({
                url: "logica/registro.php",
                method: "POST",
                data: vm.objeto
            }).then(function(response) {
            	if(response.data == null || response.data == 'null'){
            		vm.lista = [];
            	}else{
            		vm.lista = response.data;
            	}
            });
      	}

        function seleccionar(item){
            $rootScope.documentoPersona = item.documento;
            window.open("#/registronuevo","_self")
        }

    }
})();