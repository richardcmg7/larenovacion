(function(){
    angular
        .module('proyecto')
        .controller('EventoController', EventoController);

    function EventoController($http, $rootScope) {
        var vm = this;

        $rootScope.tituloMenu = 'Apoyo';
      	$rootScope.subtituloMenu = 'Nuevo Evento';
      	vm.mensaje = {};
      	vm.objeto = {};
      	vm.nombre = '';
      	vm.fechaInicio = '';
        vm.fechaFinalizacion = '';
        vm.descripcion = '';
        vm.usuario = {};
        vm.documento = '';
        vm.idPersona = 0;
        vm.nombrePersona = '';
        vm.filtro = '';
        vm.tipoPersonalApoyo = {};
      	vm.tipoPersonalApoyoLista = [];
        vm.personasLista = [];
        vm.personalApoyoLista = [];

      	vm.buscarPersona = buscarPersona;
        vm.abrirModalPersona = abrirModalPersona;
        vm.selectPersona = selectPersona;
        vm.limpiarPersona = limpiarPersona;
        vm.agregarPersona = agregarPersona;
        vm.eliminarPersona = eliminarPersona;
      	vm.guardar = guardar;

        inicio();

        function inicio(){
            vm.objeto = {
                accion: 2
            };
            $http({
                url: "logica/panel.php", 
                method: "POST",
                data: vm.objeto
            }).then(function(response) {
                vm.usuario = response.data;
            });

            vm.tipoPersonalApoyoLista = [];
            vm.objeto = {
                accion: 2
            };
            $http({
                url: "logica/tipoPersonal.php",
                method: "POST",
                data: vm.objeto
            }).then(function(response) {
                if(response.data == null || response.data == 'null'){
                    vm.tipoPersonalApoyoLista = [];
                }else{
                    vm.tipoPersonalApoyoLista = response.data;
                }
            });

            vm.personasLista = [];
            vm.objeto = {
                accion: 7,
                busqueda: 2
            };
            $http({
                url: "logica/usuario.php",
                method: "POST",
                data: vm.objeto
            }).then(function(response) {
                if(response.data == null || response.data == 'null'){
                    vm.personasLista = [];
                }else{
                    vm.personasLista = response.data;
                }
            });
        }

      	function buscarPersona(){
      		vm.idPersona = 0;
            vm.nombrePersona = '';
            if(vm.documento == ''){
                return false;
            }
            vm.objeto = {
                accion: 7,
                documento: vm.documento,
                busqueda: 1
            };
            $http({
                url: "logica/usuario.php",
                method: "POST",
                data: vm.objeto
            }).then(function(response) {
                if(response.data.length > 0){
                    vm.idPersona = response.data[0].id;
                    vm.nombrePersona = response.data[0].nombre;
                }
            });
      	}

        function abrirModalPersona(){
            vm.filtro = '';
            $('#modalPersonas').modal('show');
        }

        function selectPersona(item){
            vm.idPersona = item.id;
            vm.nombrePersona = item.nombre;
            vm.documento = item.documento;
            $('#modalPersonas').modal('hide');
        }

        function limpiarPersona(){
            vm.idPersona = 0;
            vm.nombrePersona = '';
            vm.documento = '';
            vm.tipoPersonalApoyo = {};
        }

        function agregarPersona(){
            if(vm.idPersona == 0){
                vm.mensaje.mensaje = 'Debe buscar/seleccionar una persona.';
                $('#modalError').modal('show');
                return false;
            }
            if(vm.tipoPersonalApoyo.id == null){
                vm.mensaje.mensaje = 'Debe seleccionar el Tipo Personal Apoyo.';
                $('#modalError').modal('show');
                return false;
            }
            var existe = false;
            for(var i = 0; i < vm.personalApoyoLista.length; i++){
                if(vm.personalApoyoLista[i].idPersona == vm.idPersona && vm.personalApoyoLista[i].tipoId == vm.tipoPersonalApoyo.id){
                    existe = true;
                }
            }
            if(existe){
                vm.mensaje.mensaje = 'Una persona no puede tener asignado el mismo Tipo Personal Apoyo más de una vez.';
                $('#modalError').modal('show');
                return false;
            }
            vm.personalApoyoLista.push({"idPersona":vm.idPersona,"persona":vm.documento+' - '+vm.nombrePersona,
                "tipoNombre":vm.tipoPersonalApoyo.nombre,"tipoId":vm.tipoPersonalApoyo.id});
            limpiarPersona();
        }

        function eliminarPersona(posicion){
            vm.personalApoyoLista.splice(posicion, 1);
        }

      	function guardar(){
            var mensaje = '';
            if(vm.nombre == ''){
                mensaje += "* El campo Nombre es obligatorio" + '<br/>';
            }
            if(vm.fechaInicio == ''){
                mensaje += "* El campo Fecha Inicio es obligatorio" + '<br/>';
            }
            if(vm.fechaFinalizacion == ''){
                mensaje += "* El campo Fecha Finalización es obligatorio" + '<br/>';
            }
            if(vm.usuario.nombrePersona == null){
                mensaje += "* El campo Colaborador es obligatorio" + '<br/>';
            }
            if(vm.personalApoyoLista.length == 0){
                mensaje += "* Debe seleccionar el Personal de Apoyo" + '<br/>';
            }

            if(mensaje != ''){
                $("#validacionTexto").html(mensaje);
                $('#modalValidate').modal('show');
            }else{
          		vm.objeto = {
                    accion: 1,
                    nombre: vm.nombre,
                    fechaInicio: vm.fechaInicio,
                    fechaFinalizacion: vm.fechaFinalizacion,
                    descripcion: vm.descripcion,
                    personalApoyoLista: vm.personalApoyoLista
                };
                $http({
                    url: "logica/evento.php",
                    method: "POST",
                    data: vm.objeto
                }).then(function(response) {
                	vm.mensaje = response.data;
                    if(vm.mensaje.resultado){
                        $('#modalBien').modal('show');
                    	vm.nombre = '';
                        vm.fechaInicio = '';
                        vm.fechaFinalizacion = '';
                        vm.descripcion = '';
                        vm.personalApoyoLista = [];
                        limpiarPersona();
                    }else{
                    	$('#modalError').modal('show');
                    }
                });
            }
      	}

    }
})();