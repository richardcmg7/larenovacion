(function(){
    angular
        .module('proyecto')
        .controller('E24Controller', E24Controller);

    function E24Controller($http, $rootScope) {
        var vm = this;

        $rootScope.tituloMenu = 'Inicio';
      	$rootScope.subtituloMenu = '';
      	vm.mensaje = {};
      	vm.objeto = {};
        vm.municipio = {
            id: 1
        };
        vm.departamento = {
            "id": 1
        };
        vm.puesto = {};
        vm.mesa = {};
        vm.categoria = {};
        vm.candidato = {};
        vm.votos = '';

        vm.puestoFiltro = {};
        vm.mesaFiltro = {};
        vm.categoriaFiltro = {};
        vm.candidatoFiltro = {};
        vm.totalVotos = 0;
        vm.totalMesas = 0;

        vm.puestoLista = [];
        vm.mesaLista = [];
        vm.categoriaLista = [];
        vm.candidatoLista = [];
        vm.candidatoListaFiltro = [];

        vm.cargarCandidato = cargarCandidato;
        vm.cargarCandidatoFiltro = cargarCandidatoFiltro;
        vm.consultar = consultar;
        vm.guardar = guardar;
        vm.eliminar = eliminar;
        vm.limpiar = limpiar;

        consultar();
        cargarPuesto();
        cargarMesa();
        cargarCategoria();

        function limpiar(){
            vm.puestoFiltro = {};
            vm.mesaFiltro = {};
            vm.categoriaFiltro = {};
            vm.candidatoFiltro = {};
            vm.totalVotos = 0;
            vm.totalMesas = 0;
            vm.lista = [];
            consultar();
        }

        function consultar(){
            vm.lista = [];
            vm.totalVotos = 0;
            vm.totalMesas = 0;
            vm.objeto = {
                accion: 2,
                puesto: vm.puestoFiltro.id,
                mesa: vm.mesaFiltro.id,
                categoria: vm.categoriaFiltro.id,
                candidato: vm.candidatoFiltro.id
            };
            $http({
                url: "logica/escrutinio.php",
                method: "POST",
                data: vm.objeto
            }).then(function(response) {
                if(response.data == null || response.data == 'null'){
                    vm.lista = [];
                }else{
                    vm.lista = response.data;
                    for(var i = 0; i < vm.lista.length; i++){
                        vm.totalVotos = (parseInt(vm.totalVotos) + parseInt(vm.lista[i].votos));
                        vm.totalMesas = vm.lista[i].totalMesas;
                    }
                }
            });
        }

        function cargarPuesto(){
            vm.municipio.id = 1;
            vm.puestoLista = [];
            vm.objeto = {
                accion: 5,
                municipio: vm.municipio
            };
            $http({
                url: "logica/puesto.php",
                method: "POST",
                data: vm.objeto
            }).then(function(response) {
                if(response.data == null || response.data == 'null'){
                    vm.puestoLista = [];
                }else{
                    vm.puestoLista = response.data;
                }
            });
        }

        function cargarMesa(){
            vm.mesaLista = [];
            vm.objeto = {
                accion: 5
            };
            $http({
                url: "logica/mesa.php",
                method: "POST",
                data: vm.objeto
            }).then(function(response) {
                if(response.data == null || response.data == 'null'){
                    vm.mesaLista = [];
                }else{
                    vm.mesaLista = response.data;
                }
            });
        }

        function cargarCategoria(){
            vm.categoriaLista = [];
            vm.objeto = {
                accion: 5
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

        function cargarCandidato(){
            vm.candidato = {};
            vm.candidatoLista = [];
            vm.objeto = {
                accion: 5,
                categoria: vm.categoria,
                departamento: vm.departamento,
                municipio: vm.municipio
            };
            $http({
                url: "logica/candidato.php",
                method: "POST",
                data: vm.objeto
            }).then(function(response) {
                if(response.data == null || response.data == 'null'){
                    vm.candidatoLista = [];
                }else{
                    vm.candidatoLista = response.data;
                }
            });
        }

        function cargarCandidatoFiltro(){
            vm.candidatoFiltro = {};
            vm.candidatoListaFiltro = [];
            vm.objeto = {
                accion: 5,
                categoria: vm.categoriaFiltro,
                departamento: vm.departamento,
                municipio: vm.municipio
            };
            $http({
                url: "logica/candidato.php",
                method: "POST",
                data: vm.objeto
            }).then(function(response) {
                if(response.data == null || response.data == 'null'){
                    vm.candidatoListaFiltro = [];
                }else{
                    vm.candidatoListaFiltro = response.data;
                }
            });
        }

        function guardar(){
            vm.objeto = {
                accion: 1,
                puesto: vm.puesto.id,
                mesa: vm.mesa.id,
                categoria: vm.categoria.id,
                candidato: vm.candidato.id,
                votos: vm.votos
            };
            $http({
                url: "logica/escrutinio.php",
                method: "POST",
                data: vm.objeto
            }).then(function(response) {
                vm.mensaje = response.data;
                if(vm.mensaje.resultado){
                    consultar();
                    vm.puesto = {};
                    vm.mesa = {};
                    vm.categoria = {};
                    vm.candidato = {};
                    vm.votos = '';
                    $('#modalBien').modal('show');
                }else{
                    $('#modalError').modal('show');
                }
            });
        }

        function eliminar(seleccionado){
            vm.objeto = {
                accion: 3,
                id: seleccionado.id
            };
            $http({
                url: "logica/escrutinio.php",
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