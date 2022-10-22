(function(){
    angular
        .module('proyecto')
        .controller('LocalidadController', LocalidadController);

    function LocalidadController($http, $rootScope) {
        var vm = this;

        $rootScope.tituloMenu = 'Administración';
      	$rootScope.subtituloMenu = 'Localidad';
      	vm.mensaje = {};
      	vm.objeto = {};
      	vm.id = '';
      	vm.nombre = '';
      	vm.municipio = {};
        vm.departamento = {};
      	vm.nombreEditar = '';
      	vm.municipioEditar = {};
        vm.departamentoEditar = {};
        vm.estadoEditar = '';
      	vm.lista = [];
        vm.municipioLista = [];
        vm.departamentoLista = [];
        vm.municipioListaEditar = [];
      	vm.seleccionado = {};

      	vm.consultar = consultar;
        vm.cargarMunicipios = cargarMunicipios;
      	vm.guardar = guardar;
      	vm.editar = editar;
      	vm.seleccionar = seleccionar;
      	vm.eliminar = eliminar;

      	consultar();
        consultarDepartamentos();

      	function consultar(){
      		vm.lista = [];
            vm.objeto = {
                accion: 2
            };
            $http({
                url: "logica/localidad.php",
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

        function consultarDepartamentos(){
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

        function cargarMunicipios(option){
            var departamento;
            if(option == 1){
                vm.municipioLista = [];
                departamento = vm.departamento;
            }else{
                vm.municipioListaEditar = [];
                departamento = vm.departamentoEditar;
            }
            vm.objeto = {
                accion: 5,
                departamento: departamento
            };
            $http({
                url: "logica/municipio.php",
                method: "POST",
                data: vm.objeto
            }).then(function(response) {
                if(response.data == null || response.data == 'null'){
                    if(option == 1){
                        vm.municipioLista = [];
                    }else{
                        vm.municipioListaEditar = [];
                    }
                }else{
                    if(option == 1){
                        vm.municipioLista = response.data;
                    }else{
                        vm.municipioListaEditar = response.data;
                        vm.municipioEditar = vm.seleccionado.municipio;
                    }
                }
            });
        }

      	function guardar(){
      		vm.objeto = {
                accion: 1,
                nombre: vm.nombre,
                municipio: vm.municipio
            };
            $http({
                url: "logica/localidad.php",
                method: "POST",
                data: vm.objeto
            }).then(function(response) {
            	vm.mensaje = response.data;
                if(vm.mensaje.resultado){
                	vm.nombre = '';
                	vm.municipio = {};
                    vm.departamento = {};
                	consultar();
                	$('#modalBien').modal('show');
                }else{
                	$('#modalError').modal('show');
                }
            });
      	}

      	function editar(){
      		vm.objeto = {
                accion: 3,
                nombre: vm.nombreEditar,
                municipio: vm.municipioEditar,
                estado: vm.estadoEditar,
                id: vm.seleccionado.id
            };
            $http({
                url: "logica/localidad.php",
                method: "POST",
                data: vm.objeto
            }).then(function(response) {
            	vm.mensaje = response.data;
            	$('#modalEditar').modal('hide');
                if(vm.mensaje.resultado){
                	$('#modalBien').modal('show');
                	consultar();
                }else{
                	$('#modalError').modal('show');
                }
            });
      	}

      	function seleccionar(seleccionado){
      		vm.seleccionado = seleccionado;
      		vm.nombreEditar = vm.seleccionado.nombre;
      		vm.departamentoEditar = vm.seleccionado.departamento;
            vm.estadoEditar = vm.seleccionado.estado;
            cargarMunicipios(2);
      	}

      	function eliminar(seleccionado){
      		vm.objeto = {
                accion: 4,
                id: seleccionado.id
            };
            $http({
                url: "logica/localidad.php",
                method: "POST",
                data: vm.objeto
            }).then(function(response) {
            	vm.mensaje = response.data;
                if(vm.mensaje.resultado){
                	consultar();
                	$('#modalBien').modal('show');
                }else{
                	$('#modalError').modal('show');
                }
            });
      	}

    }
})();