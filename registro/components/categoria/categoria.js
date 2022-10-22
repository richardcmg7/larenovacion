(function(){
    angular
        .module('proyecto')
        .controller('CategoriaController', CategoriaController);

    function CategoriaController($http, $rootScope) {
        var vm = this;

        $rootScope.tituloMenu = 'Administración';
      	$rootScope.subtituloMenu = 'Categoria Política';
      	vm.mensaje = {};
      	vm.objeto = {};
      	vm.id = '';
      	vm.nombre = '';
      	vm.ubicacion = '';
      	vm.nombreEditar = '';
      	vm.ubicacionEditar = '';
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
                url: "logica/categoria.php",
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
                nombre: vm.nombre,
                ubicacion: vm.ubicacion
            };
            $http({
                url: "logica/categoria.php",
                method: "POST",
                data: vm.objeto
            }).then(function(response) {
            	vm.mensaje = response.data;
                if(vm.mensaje.resultado){
                	vm.nombre = '';
                	vm.ubicacion = '';
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
                ubicacion: vm.ubicacionEditar,
                estado: vm.estadoEditar,
                id: vm.seleccionado.id
            };
            $http({
                url: "logica/categoria.php",
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
      		vm.ubicacionEditar = vm.seleccionado.ubicacion;
            vm.estadoEditar = vm.seleccionado.estado;
      	}

      	function eliminar(seleccionado){
      		vm.objeto = {
                accion: 4,
                id: seleccionado.id
            };
            $http({
                url: "logica/categoria.php",
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