(function(){
    angular
        .module('proyecto')
        .controller('OcupacionController', OcupacionController);

    function OcupacionController($http, $rootScope) {
        var vm = this;

        $rootScope.tituloMenu = 'Administración';
      	$rootScope.subtituloMenu = 'Ocupación';
      	vm.mensaje = {};
      	vm.objeto = {};
      	vm.id = '';
      	vm.nombre = '';
      	vm.nombreEditar = '';
        vm.estadoEditar = '';
      	vm.lista = [];
      	vm.seleccionado = {};

      	vm.consultar = consultar;
      	vm.guardar = guardar;
      	vm.editar = editar;
      	vm.seleccionar = seleccionar;
      	vm.eliminar = eliminar;

      	consultar();

      	function consultar(){
      		vm.lista = [];
            vm.objeto = {
                accion: 2
            };
            $http({
                url: "logica/ocupacion.php",
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

      	function guardar(){
      		vm.objeto = {
                accion: 1,
                nombre: vm.nombre
            };
            $http({
                url: "logica/ocupacion.php",
                method: "POST",
                data: vm.objeto
            }).then(function(response) {
            	vm.mensaje = response.data;
                if(vm.mensaje.resultado){
                	vm.nombre = '';
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
                url: "logica/ocupacion.php",
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
            vm.estadoEditar = vm.seleccionado.estado;
      	}

      	function eliminar(seleccionado){
      		vm.objeto = {
                accion: 4,
                id: seleccionado.id
            };
            $http({
                url: "logica/ocupacion.php",
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