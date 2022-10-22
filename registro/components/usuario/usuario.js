(function(){
    angular
        .module('proyecto')
        .controller('UsuarioController', UsuarioController);

    function UsuarioController($http, $rootScope) {
        var vm = this;

        $rootScope.tituloMenu = 'Administración';
      	$rootScope.subtituloMenu = 'Usuario';
      	vm.mensaje = {};
      	vm.objeto = {};
      	vm.id = '';
        vm.idPersona = 0;
      	vm.nombre = '';
      	vm.usuario = '';
      	vm.clave = '';
      	vm.tipo = '';
        vm.municipio = {};
        vm.departamento = {};
        vm.nombreEditar = '';
        vm.tipoEditar = '';
        vm.municipioEditar = {};
        vm.departamentoEditar = {};
        vm.usuarioEditar = '';
        vm.claveEditar = '';
        vm.estadoEditar;
      	vm.lista = [];
      	vm.seleccionado = {};
        vm.municipioLista = [];
        vm.municipioListaEditar = [];
        vm.departamentoLista = [];

      	vm.consultar = consultar;
      	vm.guardar = guardar;
      	vm.editarInfo = editarInfo;
        vm.editarCrede = editarCrede;
      	vm.seleccionar = seleccionar;
      	vm.eliminar = eliminar;
        vm.buscarPersona = buscarPersona;
        vm.selectTipo = selectTipo;
        vm.cargarMunicipios = cargarMunicipios;
        vm.cargarMunicipiosEditar = cargarMunicipiosEditar;

      	consultar();
        cargarDepartamentos();

      	function consultar(){
      		vm.lista = [];
            vm.objeto = {
                accion: 2
            };
            $http({
                url: "logica/usuario.php",
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
        }

        function cargarMunicipiosEditar(opcion){
            vm.municipioListaEditar = [];
            vm.objeto = {
                accion: 5,
                departamento: vm.departamentoEditar
            };
            $http({
                url: "logica/municipio.php",
                method: "POST",
                data: vm.objeto
            }).then(function(response) {
                if(response.data == null || response.data == 'null'){
                    vm.municipioListaEditar = [];
                }else{
                    vm.municipioListaEditar = response.data;
                    if(opcion == 2){
                        vm.municipioEditar = vm.seleccionado.municipio;
                    }
                }
            });
        }

        function buscarPersona(){
            vm.idPersona = 0;
            vm.nombre = '';
            if(vm.usuario == ''){
                return false;
            }
            vm.objeto = {
                accion: 6,
                documento: vm.usuario
            };
            $http({
                url: "logica/usuario.php",
                method: "POST",
                data: vm.objeto
            }).then(function(response) {
                if(response.data.length > 0){
                    vm.idPersona = response.data[0].id;
                    vm.nombre = response.data[0].nombre;
                    vm.usuario = response.data[0].documento;
                }
            });
        }

        function selectTipo(opcion){
            vm.municipioLista = [];
            if(opcion == 1){
                vm.departamento = {};
                vm.municipio = {};
            }else{
                vm.departamentoEditar = {};
                vm.municipioEditar = {};
            }
        }

      	function guardar(){
      		vm.objeto = {
                accion: 1,
                persona: vm.idPersona,
                clave: vm.clave,
                tipo: vm.tipo,
                municipio: vm.municipio
            };
            $http({
                url: "logica/usuario.php",
                method: "POST",
                data: vm.objeto
            }).then(function(response) {
            	vm.mensaje = response.data;
                if(vm.mensaje.resultado){
                    vm.idPersona = 0;
                	vm.nombre = '';
                	vm.usuario = '';
                    vm.clave = '';
                    vm.tipo = '';
                    vm.departamento = {};
                    vm.municipio = {};
                	consultar();
                	$('#modalBien').modal('show');
                }else{
                	$('#modalError').modal('show');
                }
            });
      	}

      	function editarInfo(){
      		vm.objeto = {
                accion: 3,
                nombre: vm.nombreEditar,
                tipo: vm.tipoEditar,
                estado: vm.estadoEditar,
                id: vm.seleccionado.id,
                municipio: vm.municipioEditar
            };
            $http({
                url: "logica/usuario.php",
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
            vm.departamentoEditar = {};
            vm.municipioEditar = {};
      		vm.seleccionado = seleccionado;
      		vm.nombreEditar = vm.seleccionado.nombre;
      		vm.usuarioEditar = vm.seleccionado.usuario;
            vm.tipoEditar = vm.seleccionado.tipo;
            vm.estadoEditar = vm.seleccionado.estado;
            vm.claveEditar = '';
            if(vm.seleccionado.tipo == 2 || vm.seleccionado.tipo == '2' || vm.seleccionado.tipo == 3 || vm.seleccionado.tipo == '3' || vm.seleccionado.tipo == 4 || vm.seleccionado.tipo == '4'){
                vm.departamentoEditar = vm.seleccionado.departamento;
                cargarMunicipiosEditar(2);
            }
      	}

      	function eliminar(seleccionado){
      		vm.objeto = {
                accion: 4,
                id: seleccionado.id
            };
            $http({
                url: "logica/usuario.php",
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

        function editarCrede(){
            vm.objeto = {
                accion: 5,
                usuario: vm.usuarioEditar,
                clave: vm.claveEditar,
                id: vm.seleccionado.id,
                cambio: 1
            };
            $http({
                url: "logica/usuario.php",
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

    }
})();