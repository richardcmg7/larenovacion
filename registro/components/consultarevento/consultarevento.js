(function(){
    angular
        .module('proyecto')
        .controller('ConsultareventoController', ConsultareventoController);

    function ConsultareventoController($http, $rootScope) {
        var vm = this;

        $rootScope.tituloMenu = 'Apoyo';
      	$rootScope.subtituloMenu = 'Consultar Eventos';
      	vm.mensaje = {};
      	vm.objeto = {};
        vm.tab = 1;
        vm.nombre = '';
        vm.fechaDe = '';
        vm.fechaHasta = '';
        vm.estado = '';
        vm.documento = '';
        vm.nombrePersona = '';
      	vm.lista = [];
      	vm.seleccionado = {};
        vm.personalApoyoLista = [];

      	vm.consultar = consultar;
        vm.limpiar = limpiar;
        vm.seleccionar = seleccionar;
        vm.atras = atras;
        vm.editar = editar;
        vm.realizar = realizar;
        vm.eliminar = eliminar;

      	function consultar(){

            if(vm.fechaDe == '' && vm.fechaHasta != ''){
                vm.mensaje.mensaje = 'Debe seleccionar la fecha De.';
                $('#modalError').modal('show');
                return false;
            }

      		vm.lista = [];
            vm.objeto = {
                accion: 2,
                nombre: vm.nombre,
                fechaDe: vm.fechaDe,
                fechaHasta: vm.fechaHasta,
                estado: vm.estado,
                documento: vm.documento,
                nombrePersona: vm.nombrePersona
            };
            $http({
                url: "logica/evento.php",
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

        function limpiar(){
            vm.nombre = '';
            vm.fechaDe = '';
            vm.fechaHasta = '';
            vm.estado = '';
            vm.documento = '';
            vm.nombrePersona = '';
            vm.lista = [];
        }

        function seleccionar(seleccionado){
            vm.seleccionado = {};
            vm.seleccionado = seleccionado;
            vm.tab = 2;
            consultarPersonalApoyo();
        }

        function consultarPersonalApoyo(){
            vm.personalApoyoLista = [];
            vm.objeto = {
                accion: 3,
                evento: vm.seleccionado.id
            };
            $http({
                url: "logica/evento.php",
                method: "POST",
                data: vm.objeto
            }).then(function(response) {
                if(response.data == null || response.data == 'null'){
                    vm.personalApoyoLista = [];
                }else{
                    vm.personalApoyoLista = response.data;
                }
            });
        }

        function atras(){
            vm.tab = 1;
        }

        function editar(){
            var mensaje = '';
            if(vm.seleccionado.nombre == ''){
                mensaje += "* El campo Nombre es obligatorio" + '<br/>';
            }
            if(vm.seleccionado.fechaInicio == ''){
                mensaje += "* El campo Fecha Inicio es obligatorio" + '<br/>';
            }
            if(vm.seleccionado.fechaFinalizacion == ''){
                mensaje += "* El campo Fecha Finalización es obligatorio" + '<br/>';
            }

            if(mensaje != ''){
                $("#validacionTexto").html(mensaje);
                $('#modalValidate').modal('show');
            }else{
                vm.objeto = {
                    accion: 4,
                    nombre: vm.seleccionado.nombre,
                    fechaInicio: vm.seleccionado.fechaInicio,
                    fechaFinalizacion: vm.seleccionado.fechaFinalizacion,
                    descripcion: vm.seleccionado.descripcion,
                    id: vm.seleccionado.id
                };
                $http({
                    url: "logica/evento.php",
                    method: "POST",
                    data: vm.objeto
                }).then(function(response) {
                    vm.mensaje = response.data;
                    if(vm.mensaje.resultado){
                        $('#modalBien').modal('show');
                        consultar();
                        atras();
                    }else{
                        $('#modalError').modal('show');
                    }
                });
            }
        }

        function realizar(item){
            vm.objeto = {
                accion: 5,
                id: item.id
            };
            $http({
                url: "logica/evento.php",
                method: "POST",
                data: vm.objeto
            }).then(function(response) {
                vm.mensaje = response.data;
                if(vm.mensaje.resultado){
                    $('#modalBien').modal('show');
                    consultar();
                    atras();
                }else{
                    $('#modalError').modal('show');
                }
            });
        }

        function eliminar(){
            vm.objeto = {
                accion: 6,
                id: vm.seleccionado.id
            };
            $http({
                url: "logica/evento.php",
                method: "POST",
                data: vm.objeto
            }).then(function(response) {
                vm.mensaje = response.data;
                if(vm.mensaje.resultado){
                    $('#modalBien').modal('show');
                    consultar();
                    atras();
                }else{
                    $('#modalError').modal('show');
                }
            });
        }

    }
})();