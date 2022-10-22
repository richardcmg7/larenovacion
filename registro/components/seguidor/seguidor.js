(function(){
    angular
        .module('proyecto')
        .controller('SeguidorController', SeguidorController);

    function SeguidorController($http, $rootScope) {
        var vm = this;

        $rootScope.tituloMenu = 'Marketing';
      	$rootScope.subtituloMenu = 'Call Center';
      	vm.mensaje = {};
      	vm.objeto = {};
        vm.filtro = {
            "documento": '',
            "nombre": '',
            "lider": {},
            "lugar": 'T',
            "departamento": {},
            "municipio": {}
        };
        vm.departamento = {};
        vm.municipio = {};

        vm.departamentoLista = [];
        vm.municipioLista = [];
      	vm.lista = [];
      	vm.seleccionado = {};
        vm.liderLista = [];

        vm.cargarLideres = cargarLideres;
        vm.seleccionarLider = seleccionarLider;
        vm.seleccionarLugar = seleccionarLugar;
        vm.cargarMunicipios = cargarMunicipios;
        vm.limpiar = limpiar;

      	vm.consultar = consultar;
        vm.seleccionar = seleccionar;

      	limpiar();

        function limpiar(){
            vm.filtro = {
                "documento": '',
                "nombre": '',
                "lider": {},
                "lugar": 'T',
                "departamento": {},
                "municipio": {}
            };
            vm.lista = [];
            vm.liderLista = [];
            vm.objeto = {
                accion: 9
            };
            $http({
                url: "logica/usuario.php",
                method: "POST",
                data: vm.objeto
            }).then(function(response) {
                if(response.data == null || response.data == 'null'){
                    vm.liderLista = [];
                }else{
                    vm.liderLista = response.data;
                }
            });

            vm.departamentoLista = [];
            vm.objeto = {
                accion: 2
            };
            $http({
                url: "logica/departamento.php",
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

        function cargarLideres(){
            $('#modalEditar').modal('show');
        }

        function seleccionarLider(lider){
            vm.filtro.lider = lider;
            $('#modalEditar').modal('hide');
        }

        function seleccionarLugar(){
            vm.filtro.departamento = {};
            vm.filtro.municipio = {};
        }

        function cargarMunicipios(){
            vm.municipioLista = [];
            vm.objeto = {
                accion: 5,
                departamento: vm.filtro.departamento
            };
            $http({
                url: "logica/municipio.php",
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

      	function consultar(){
      		vm.lista = [];
            vm.objeto = {
                accion: 6,
                documento: vm.filtro.documento,
                nombre: vm.filtro.nombre,
                lider: vm.filtro.lider.documento,
                lugar: vm.filtro.lugar,
                departamento: vm.filtro.departamento.id,
                municipio: vm.filtro.municipio.id
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