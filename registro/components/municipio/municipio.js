(function(){
    angular
        .module('proyecto')
        .controller('MunicipioController', MunicipioController);

    function MunicipioController($http, $rootScope) {
        var vm = this;

        $rootScope.tituloMenu = 'Administración';
      	$rootScope.subtituloMenu = 'Municipio';
      	vm.mensaje = {};
      	vm.objeto = {};
      	vm.id = '';
      	vm.nombre = '';
      	vm.departamento = {};
      	vm.nombreEditar = '';
      	vm.departamentoEditar = '';
        vm.estadoEditar = '';
      	vm.lista = [];
        vm.departamentoLista = [];
      	vm.seleccionado = {};

      	vm.consultar = consultar;
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
                url: "logica/municipio.php",
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

      	function guardar(){
      		vm.objeto = {
                accion: 1,
                nombre: vm.nombre,
                departamento: vm.departamento
            };
            $http({
                url: "logica/municipio.php",
                method: "POST",
                data: vm.objeto
            }).then(function(response) {
            	vm.mensaje = response.data;
                if(vm.mensaje.resultado){
                	vm.nombre = '';
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
                departamento: vm.departamentoEditar,
                estado: vm.estadoEditar,
                id: vm.seleccionado.id
            };
            $http({
                url: "logica/municipio.php",
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
      	}

      	function eliminar(seleccionado){
      		vm.objeto = {
                accion: 4,
                id: seleccionado.id
            };
            $http({
                url: "logica/municipio.php",
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