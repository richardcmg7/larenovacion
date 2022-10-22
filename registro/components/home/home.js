(function(){
    angular
        .module('proyecto')
        .controller('HomeController', HomeController);

    function HomeController($http, $rootScope) {
        var vm = this;

        $rootScope.tituloMenu = 'Inicio';
      	$rootScope.subtituloMenu = '';
      	vm.mensaje = {};
      	vm.objeto = {};
        vm.registrosCompletos = 0;
        vm.registrosSeguidores = 0;
        vm.registrosSinUbicacion = 0;
        vm.registrosSinDemografica = 0;
        vm.registrosSinVotacion = 0;

        consultar();

        function consultar(){
            vm.objeto = {
                accion: 1
            };
            $http({
                url: "logica/home.php",
                method: "POST",
                data: vm.objeto
            }).then(function(response) {
                vm.registrosCompletos = response.data.registrosCompletos;
                vm.registrosSeguidores = response.data.registrosSeguidores;
                vm.registrosSinUbicacion = response.data.registrosSinUbicacion;
                vm.registrosSinDemografica = response.data.registrosSinDemografica;
                vm.registrosSinVotacion = response.data.registrosSinVotacion;
            });
        }

    }
})();