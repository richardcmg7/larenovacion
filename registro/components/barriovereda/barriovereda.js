(function(){
    angular
        .module('proyecto')
        .controller('BarrioveredaController', BarrioveredaController);

    function BarrioveredaController($http, $rootScope) {
        var vm = this;

        $rootScope.tituloMenu = 'Administración';
      	$rootScope.subtituloMenu = 'Barrio/Vereda';
      	vm.mensaje = {};
      	vm.objeto = {};
      	vm.id = '';
      	vm.nombre = '';
      	vm.municipio = {};
        vm.departamento = {};
        vm.localidad = {};
        vm.comuna = {};
        vm.zonaResidencia = {};
      	vm.nombreEditar = '';
      	vm.municipioEditar = {};
        vm.departamentoEditar = {};
        vm.comunaEditar = {};
        vm.localidadEditar = {};
        vm.zonaResidenciaEditar = {};
        vm.estadoEditar = '';
      	vm.lista = [];
        vm.municipioLista = [];
        vm.departamentoLista = [];
        vm.municipioListaEditar = [];
        vm.comunaLista = [];
        vm.comunaListaEditar = [];
        vm.localidadLista = [];
        vm.localidadListaEditar = [];
        vm.zonaResidenciaLista = [];
      	vm.seleccionado = {};

      	vm.consultar = consultar;
        vm.cargarMunicipios = cargarMunicipios;
        vm.cargarLocalidades = cargarLocalidades;
        vm.cargarComunas = cargarComunas;
      	vm.guardar = guardar;
      	vm.editar = editar;
      	vm.seleccionar = seleccionar;
      	vm.eliminar = eliminar;

      	consultar();
        consultarDepartamentos();
        consultarZonaResidencia();

      	function consultar(){
      		vm.lista = [];
            vm.objeto = {
                accion: 2
            };
            $http({
                url: "logica/barrioVereda.php",
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
                        cargarLocalidades(2);
                    }
                }
            });
        }

        function cargarLocalidades(option){
            var municipio;
            if(option == 1){
                vm.localidadLista = [];
                municipio = vm.municipio;
            }else{
                vm.localidadListaEditar = [];
                municipio = vm.municipioEditar;
            }
            vm.objeto = {
                accion: 5,
                municipio: municipio
            };
            $http({
                url: "logica/localidad.php",
                method: "POST",
                data: vm.objeto
            }).then(function(response) {
                if(response.data == null || response.data == 'null'){
                    if(option == 1){
                        vm.localidadLista = [];
                    }else{
                        vm.localidadListaEditar = [];
                    }
                }else{
                    if(option == 1){
                        vm.localidadLista = response.data;
                    }else{
                        vm.localidadListaEditar = response.data;
                        vm.localidadEditar = vm.seleccionado.localidad;
                        cargarComunas(2);
                    }
                }
            });
        }

        function cargarComunas(option){
            var localidad;
            if(option == 1){
                vm.comunaLista = [];
                localidad = vm.localidad;
            }else{
                vm.comunaListaEditar = [];
                localidad = vm.localidadEditar;
            }
            vm.objeto = {
                accion: 5,
                localidad: localidad
            };
            $http({
                url: "logica/comuna.php",
                method: "POST",
                data: vm.objeto
            }).then(function(response) {
                if(response.data == null || response.data == 'null'){
                    if(option == 1){
                        vm.comunaLista = [];
                    }else{
                        vm.comunaListaEditar = [];
                    }
                }else{
                    if(option == 1){
                        vm.comunaLista = response.data;
                    }else{
                        vm.comunaListaEditar = response.data;
                        vm.comunaEditar = vm.seleccionado.comuna;
                    }
                }
            });
        }

        function consultarZonaResidencia(){
            vm.zonaResidenciaLista = [];
            vm.objeto = {
                accion: 5
            };
            $http({
                url: "logica/zonaResidencia.php",
                method: "POST",
                data: vm.objeto
            }).then(function(response) {
                if(response.data == null || response.data == 'null'){
                    vm.zonaResidenciaLista = [];
                }else{
                    vm.zonaResidenciaLista = response.data;
                }
            });
        }

      	function guardar(){
      		vm.objeto = {
                accion: 1,
                nombre: vm.nombre,
                comuna: vm.comuna,
                zonaResidencia: vm.zonaResidencia
            };
            $http({
                url: "logica/barrioVereda.php",
                method: "POST",
                data: vm.objeto
            }).then(function(response) {
            	vm.mensaje = response.data;
                if(vm.mensaje.resultado){
                	vm.nombre = '';
                	vm.municipio = {};
                    vm.departamento = {};
                    vm.comuna = {};
                    vm.localidad = {};
                    vm.zonaResidencia = {};
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
                comuna: vm.comunaEditar,
                estado: vm.estadoEditar,
                id: vm.seleccionado.id,
                zonaResidencia: vm.zonaResidenciaEditar
            };
            $http({
                url: "logica/barrioVereda.php",
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
            vm.zonaResidenciaEditar = vm.seleccionado.zonaResidencia;
            cargarMunicipios(2);
      	}

      	function eliminar(seleccionado){
      		vm.objeto = {
                accion: 4,
                id: seleccionado.id
            };
            $http({
                url: "logica/barrioVereda.php",
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