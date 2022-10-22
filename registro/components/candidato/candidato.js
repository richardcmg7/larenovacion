(function(){
    angular
        .module('proyecto')
        .controller('CandidatoController', CandidatoController);

    function CandidatoController($http, $rootScope) {
        var vm = this;

        $rootScope.tituloMenu = 'Administración';
      	$rootScope.subtituloMenu = 'Candidato';
      	vm.mensaje = {};
      	vm.objeto = {};
      	vm.id = '';
      	vm.nombre = '';
        vm.categoria = {};
        vm.valor = {};
        vm.pais = '1';
      	vm.municipio = {};
        vm.departamento = {};
      	vm.nombreEditar = '';
        vm.categoriaEditar = {};
        vm.valorEditar = {};
        vm.estadoEditar = '';
        vm.categoriaLista = [];
      	vm.lista = [];
        vm.municipioLista = [];
        vm.departamentoLista = [];
      	vm.seleccionado = {};

      	vm.consultar = consultar;
        vm.cargarValores = cargarValores;
        vm.cargarDepartamentos = cargarDepartamentos;
        vm.cargarMunicipios = cargarMunicipios;
        vm.selectItem = selectItem;
      	vm.guardar = guardar;
      	vm.editar = editar;
      	vm.seleccionar = seleccionar;
      	vm.eliminar = eliminar;

      	consultar();
        cagarCategorias();
        cargarDepartamentos();

      	function consultar(){
      		vm.lista = [];
            vm.objeto = {
                accion: 2
            };
            $http({
                url: "logica/candidato.php",
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

        function cagarCategorias(){
            vm.categoriaLista = [];
            vm.objeto = {
                accion: 2
            };
            $http({
                url: "logica/categoria.php",
                method: "POST",
                data: vm.objeto
            }).then(function(response) {
                if(response.data == null || response.data == 'null'){
                    vm.categoriaLista = [];
                }else{
                    vm.categoriaLista = response.data;
                }
            });
        }

        function cargarValores(){
            vm.valor = {};
            vm.departamento = {};
            vm.municipio = {};
            if(vm.categoria.ubicacion == 'NACIONAL'){
                vm.valor.id = '1';
            }
        }

        function cargarDepartamentos(){
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

        function cargarMunicipios(){
            vm.valor = {};
            if(vm.categoria.ubicacion == 'MUNICIPAL'){
                vm.municipioLista = [];
                vm.objeto = {
                    accion: 5,
                    departamento: vm.departamento
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
            }else{
                vm.valor = vm.departamento;
            }
        }

        function selectItem(){
            vm.valor = {};
            vm.valor = vm.municipio;
        }

      	function guardar(){
      		vm.objeto = {
                accion: 1,
                nombre: vm.nombre,
                categoria: vm.categoria,
                valor: vm.valor
            };
            $http({
                url: "logica/candidato.php",
                method: "POST",
                data: vm.objeto
            }).then(function(response) {
            	vm.mensaje = response.data;
                if(vm.mensaje.resultado){
                	vm.nombre = '';
                	vm.categoria = {};
                    vm.valor = {};
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
                estado: vm.estadoEditar,
                id: vm.seleccionado.id
            };
            $http({
                url: "logica/candidato.php",
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
      		vm.categoriaEditar = vm.seleccionado.categoria;
            vm.valorEditar = vm.seleccionado.valor;
            vm.estadoEditar = vm.seleccionado.estado;
      	}

      	function eliminar(seleccionado){
      		vm.objeto = {
                accion: 4,
                id: seleccionado.id
            };
            $http({
                url: "logica/candidato.php",
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