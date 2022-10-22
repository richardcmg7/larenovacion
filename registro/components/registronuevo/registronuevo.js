(function(){
    angular
        .module('proyecto')
        .controller('RegistronuevoController', RegistronuevoController);

    function RegistronuevoController($http, $rootScope) {
        var vm = this;

        $rootScope.tituloMenu = 'Registro';
      	$rootScope.subtituloMenu = 'Editar';
      	vm.mensaje = {};
      	vm.objeto = {};
        vm.seleccionado = {};
        vm.lider = '';
        vm.nombreLider = '';
        vm.usuario = {};
        vm.imagen = 'dist/img/user.jpg';
        vm.aceptar = true;

        vm.id = 0;
        vm.registro = {
            "personal": {
                "documento": '',
                "primerNombre": '',
                "segundoNombre": '',
                "primerApellido": '',
                "segundoApellido": '',
                "fechaNacimiento": '',
                "grupoSanguineo": {},
                "sexo": {},
                "orientacionSexual": {},
                "estadoCivil": {},
                "correoElectronico": '',
                "numeroCelular": '',
                "numeroWhatsapp": '',
                "foto": ''
            },
            "ubicacion": {
                "departamento": {},
                "municipio": {},
                "direccionResidencia": '',
                "barrioVereda": {},
                "tipoVivienda": {},
                "localidad": {},
                "comuna": {}
            },
            "demografica": {
                "estrato": {},
                "grupoSisben": {},
                "servicio": [],
                "discapacidad": {},
                "grupoEtnico": {},
                "nivelEducativo": {},
                "profesionArte": {},
                "ocupacion": {}
            },
            "votacion": {
                "departamento": {},
                "municipio" : {},
                "puesto": {},
                "mesa": {},
                "intencionVoto": []
            }
        };

        vm.categoria = {};
        vm.categoriaLista = [];
        vm.candidato = {};
        vm.candidatoLista = [];

        vm.grupoSanguineoLista = [];
        vm.sexoLista = [];
        vm.orientacionSexualLista = [];
        vm.estadoCivilLista = [];

        vm.departamentoLista = [];
        vm.municipioLista = [];
        vm.barrioVeredaLista = [];
        vm.tipoViviendaLista = [];
        vm.localidadLista = [];
        vm.comunaLista = [];

        vm.estratoLista = [];
        vm.grupoSisbenLista = [];
        vm.servicioLista = [];
        vm.discapacidadLista = [];
        vm.grupoEtnicoLista = [];
        vm.nivelEducativoLista = [];
        vm.profesionArteLista = [];
        vm.ocupacionLista = [];

        vm.departamentoVotacionLista = [];
        vm.municipioVotacionLista = [];
        vm.puestoLista = [];
        vm.mesaLista = [];

        vm.deshabilitarGuardarCambios = true;

        vm.selectTab = selectTab;
        vm.buscarPersona = buscarPersona;
        vm.limpiar = limpiar;
        vm.guardarCambios = guardarCambios;
        vm.cargarMunicipio = cargarMunicipio;
        vm.cargarLocalidad = cargarLocalidad;
        vm.cargarComuna = cargarComuna;
        vm.cargarBarrioVereda = cargarBarrioVereda;
        vm.cargarMunicipioVotacion = cargarMunicipioVotacion;
        vm.cargarPuesto = cargarPuesto;
        vm.buscarLider = buscarLider;
        vm.cargarCandidato = cargarCandidato;
        vm.agregarCandidato = agregarCandidato;
        vm.eliminarCandidato = eliminarCandidato;
        vm.abrirExplorador = abrirExplorador;

        cargarData();

        function selectTab(opcion){
            $("#tab_1").removeClass("active");
            $("#tab_2").removeClass("active");
            $("#tab_3").removeClass("active");
            $("#tab_4").removeClass("active");
            switch(opcion){
                case 1:
                    $("#tab_1").addClass("active");
                break;
                case 2:
                    $("#tab_2").addClass("active");
                break;
                case 3:
                    $("#tab_3").addClass("active");
                break;
                case 4:
                    $("#tab_4").addClass("active");
                break;
            }
        }

        function cargarData(){

            vm.objeto = {
                accion: 2
            };
            $http({
                url: "logica/panel.php", 
                method: "POST",
                data: vm.objeto
            }).then(function(response) {
                vm.usuario = response.data;
                vm.lider = vm.usuario.nombre;
                vm.nombreLider = vm.usuario.nombrePersona;
            });

            cargarGrupoSanguineo();
            cargarSexo();
            cargarOrientacionSexual();
            cargarEstadoCivil();

            cargarDepartamento();
            cargarTipoVivienda();

            cargarEstrato();
            cargarGrupoSisben();
            cargarServicio();
            cargarDiscapacidad();
            cargarGrupoEtnico();
            cargarNivelEducativo();
            cargarProfesionArte();
            cargarOcupacion();

            cargarMesa();
            cargarCategoria();

            if($rootScope.documentoPersona != 0){
                vm.registro.personal.documento = $rootScope.documentoPersona;
                buscarPersona();
            }
        }

        function abrirExplorador(){
            $('#archivos').val('');
            $('#inputImagen').val('');
            var el = document.getElementById("archivos");
            if (el) {
                el.click();
            } 
        }

        function cargarGrupoSanguineo(){
            vm.grupoSanguineoLista = [];
            vm.objeto = {
                accion: 5
            };
            $http({
                url: "logica/grupoSanguineo.php",
                method: "POST",
                data: vm.objeto
            }).then(function(response) {
                if(response.data == null || response.data == 'null'){
                    vm.grupoSanguineoLista = [];
                }else{
                    vm.grupoSanguineoLista = response.data;
                }
            });
        }

        function cargarSexo(){
            vm.sexoLista = [];
            vm.objeto = {
                accion: 5
            };
            $http({
                url: "logica/sexo.php",
                method: "POST",
                data: vm.objeto
            }).then(function(response) {
                if(response.data == null || response.data == 'null'){
                    vm.sexoLista = [];
                }else{
                    vm.sexoLista = response.data;
                }
            });
        }

        function cargarOrientacionSexual(){
            vm.orientacionSexualLista = [];
            vm.objeto = {
                accion: 5
            };
            $http({
                url: "logica/genero.php",
                method: "POST",
                data: vm.objeto
            }).then(function(response) {
                if(response.data == null || response.data == 'null'){
                    vm.orientacionSexualLista = [];
                }else{
                    vm.orientacionSexualLista = response.data;
                }
            });
        }

        function cargarEstadoCivil(){
            vm.estadoCivilLista = [];
            vm.objeto = {
                accion: 5
            };
            $http({
                url: "logica/estadoCivil.php",
                method: "POST",
                data: vm.objeto
            }).then(function(response) {
                if(response.data == null || response.data == 'null'){
                    vm.estadoCivilLista = [];
                }else{
                    vm.estadoCivilLista = response.data;
                }
            });
        }

        function cargarDepartamento(){
            vm.departamentoLista = [];
            vm.departamentoVotacionLista = [];
            vm.objeto = {
                accion: 5
            };
            $http({
                url: "logica/departamento.php",
                method: "POST",
                data: vm.objeto
            }).then(function(response) {
                if(response.data == null || response.data == 'null'){
                    vm.departamentoLista = [];
                    vm.departamentoVotacionLista = [];
                }else{
                    vm.departamentoLista = response.data;
                    vm.departamentoVotacionLista = response.data;
                }
            });
        }

        function cargarTipoVivienda(){
            vm.tipoViviendaLista = [];
            vm.objeto = {
                accion: 5
            };
            $http({
                url: "logica/tipoVivienda.php",
                method: "POST",
                data: vm.objeto
            }).then(function(response) {
                if(response.data == null || response.data == 'null'){
                    vm.tipoViviendaLista = [];
                }else{
                    vm.tipoViviendaLista = response.data;
                }
            });
        }

        function cargarEstrato(){
            vm.estratoLista = [];
            vm.objeto = {
                accion: 5
            };
            $http({
                url: "logica/estrato.php",
                method: "POST",
                data: vm.objeto
            }).then(function(response) {
                if(response.data == null || response.data == 'null'){
                    vm.estratoLista = [];
                }else{
                    vm.estratoLista = response.data;
                }
            });
        }

        function cargarGrupoSisben(){
            vm.grupoSisbenLista = [];
            vm.objeto = {
                accion: 5
            };
            $http({
                url: "logica/grupoSisben.php",
                method: "POST",
                data: vm.objeto
            }).then(function(response) {
                if(response.data == null || response.data == 'null'){
                    vm.grupoSisbenLista = [];
                }else{
                    vm.grupoSisbenLista = response.data;
                }
            });
        }

        function cargarServicio(){
            vm.servicioLista = [];
            vm.objeto = {
                accion: 5
            };
            $http({
                url: "logica/servicioPublico.php",
                method: "POST",
                data: vm.objeto
            }).then(function(response) {
                if(response.data == null || response.data == 'null'){
                    vm.servicioLista = [];
                }else{
                    vm.servicioLista = response.data;
                }
            });
        }

        function cargarDiscapacidad(){
            vm.discapacidadLista = [];
            vm.objeto = {
                accion: 5
            };
            $http({
                url: "logica/discapacidad.php",
                method: "POST",
                data: vm.objeto
            }).then(function(response) {
                if(response.data == null || response.data == 'null'){
                    vm.discapacidadLista = [];
                }else{
                    vm.discapacidadLista = response.data;
                }
            });
        }

        function cargarGrupoEtnico(){
            vm.grupoEtnicoLista = [];
            vm.objeto = {
                accion: 5
            };
            $http({
                url: "logica/grupoEtnico.php",
                method: "POST",
                data: vm.objeto
            }).then(function(response) {
                if(response.data == null || response.data == 'null'){
                    vm.grupoEtnicoLista = [];
                }else{
                    vm.grupoEtnicoLista = response.data;
                }
            });
        }

        function cargarNivelEducativo(){
            vm.nivelEducativoLista = [];
            vm.objeto = {
                accion: 5
            };
            $http({
                url: "logica/nivelEducativo.php",
                method: "POST",
                data: vm.objeto
            }).then(function(response) {
                if(response.data == null || response.data == 'null'){
                    vm.nivelEducativoLista = [];
                }else{
                    vm.nivelEducativoLista = response.data;
                }
            });
        }

        function cargarProfesionArte(){
            vm.profesionArteLista = [];
            vm.objeto = {
                accion: 5
            };
            $http({
                url: "logica/profesion.php",
                method: "POST",
                data: vm.objeto
            }).then(function(response) {
                if(response.data == null || response.data == 'null'){
                    vm.profesionArteLista = [];
                }else{
                    vm.profesionArteLista = response.data;
                }
            });
        }

        function cargarOcupacion(){
            vm.ocupacionLista = [];
            vm.objeto = {
                accion: 5
            };
            $http({
                url: "logica/ocupacion.php",
                method: "POST",
                data: vm.objeto
            }).then(function(response) {
                if(response.data == null || response.data == 'null'){
                    vm.ocupacionLista = [];
                }else{
                    vm.ocupacionLista = response.data;
                }
            });
        }

        function cargarMesa(){
            vm.registro.votacion.mesa = {};
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
            vm.categoria = {};
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

        function buscarPersona(){
            if(vm.registro.personal.documento == ''){
                return false;
            }
            vm.objeto = {
                accion: 2,
                documento: vm.registro.personal.documento
            };
            $http({
                url: "logica/registro.php",
                method: "POST",
                data: vm.objeto
            }).then(function(response) {
                if(response.data.length > 0){
                    vm.seleccionado = response.data[0];
                    vm.deshabilitarGuardarCambios = false;
                    vm.id = response.data[0].id;
                    vm.registro.personal.primerNombre = response.data[0].primerNombre;
                    vm.registro.personal.segundoNombre = response.data[0].segundoNombre;
                    vm.registro.personal.primerApellido = response.data[0].primerApellido;
                    vm.registro.personal.segundoApellido = response.data[0].segundoApellido;
                    vm.registro.personal.fechaNacimiento = response.data[0].fechaNacimiento;
                    vm.registro.personal.grupoSanguineo = response.data[0].grupoSanguineo;
                    vm.registro.personal.orientacionSexual = response.data[0].orientacionSexual;
                    vm.registro.personal.sexo = response.data[0].sexo;
                    vm.registro.personal.estadoCivil = response.data[0].estadoCivil;
                    vm.registro.personal.correoElectronico = response.data[0].correoElectronico;
                    vm.registro.personal.numeroCelular = response.data[0].numeroCelular;
                    vm.registro.personal.numeroWhatsapp = response.data[0].numeroWhatsapp;
                    vm.registro.personal.foto = response.data[0].nombreFoto;
                    vm.imagen = response.data[0].foto;
                    vm.lider = response.data[0].lider;
                    vm.nombreLider = response.data[0].nombreLider;

                    vm.registro.ubicacion.departamento = response.data[0].departamento;
                    vm.registro.ubicacion.tipoVivienda = response.data[0].tipoVivienda;
                    vm.registro.ubicacion.direccionResidencia = response.data[0].direccionResidencia;
                    cargarMunicipio(2);

                    vm.registro.demografica.estrato = response.data[0].estrato;
                    vm.registro.demografica.grupoSisben = response.data[0].grupoSisben;
                    vm.registro.demografica.servicio = [];
                    vm.registro.demografica.servicio = response.data[0].servicio;
                    $('.select2-selection__rendered').empty();
                    for(var i = 0; i < vm.servicioLista.length; i++){
                        for(var m = 0; m < response.data[0].servicio.length; m++){
                            if(vm.servicioLista[i].id == response.data[0].servicio[m]){
                                $('.select2-selection__rendered').append('<li class="select2-selection__choice" title="'+vm.servicioLista[i].nombre+'" data-select2-id="'+vm.servicioLista[i].id+'">'+vm.servicioLista[i].nombre+'</li>');
                            }
                        }
                    }
                    
                    //vm.servicioLista[0].selected = true;
                    vm.registro.demografica.discapacidad = response.data[0].discapacidad;
                    vm.registro.demografica.grupoEtnico = response.data[0].grupoEtnico;
                    vm.registro.demografica.nivelEducativo = response.data[0].nivelEducativo;
                    vm.registro.demografica.profesionArte = response.data[0].profesionArte;
                    vm.registro.demografica.ocupacion = response.data[0].ocupacion;

                    vm.registro.votacion.departamento = response.data[0].departamentoVotacion;
                    vm.registro.votacion.mesa = response.data[0].mesa;
                    cargarMunicipioVotacion(2);

                    
                }else{
                    limpiar(false);
                }
            });
        }

        

        function cargarMunicipio(opcion){
            vm.registro.ubicacion.municipio = {};
            vm.registro.ubicacion.localidad = {};
            vm.registro.ubicacion.comuna = {};
            vm.registro.ubicacion.barrioVereda = {};
            vm.municipioLista = [];
            vm.objeto = {
                accion: 5,
                departamento: vm.registro.ubicacion.departamento
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
                    if(opcion == 2){
                        vm.registro.ubicacion.municipio = vm.seleccionado.municipio; 
                    }
                    cargarLocalidad(opcion);
                }
            });
        }

        function cargarLocalidad(opcion){
            vm.registro.ubicacion.localidad = {};
            vm.registro.ubicacion.comuna = {};
            vm.registro.ubicacion.barrioVereda = {};
            vm.localidadLista = [];
            vm.objeto = {
                accion: 5,
                municipio: vm.registro.ubicacion.municipio
            };
            $http({
                url: "logica/localidad.php",
                method: "POST",
                data: vm.objeto
            }).then(function(response) {
                if(response.data == null || response.data == 'null'){
                    vm.localidadLista = [];
                }else{
                    vm.localidadLista = response.data;
                    if(opcion == 2){
                        vm.registro.ubicacion.localidad = vm.seleccionado.localidad;
                    }
                    cargarComuna(opcion);
                }
            });
        }

        function cargarComuna(opcion){
            vm.registro.ubicacion.comuna = {};
            vm.registro.ubicacion.barrioVereda = {};
            vm.comunaLista = [];
            vm.objeto = {
                accion: 5,
                localidad: vm.registro.ubicacion.localidad
            };
            $http({
                url: "logica/comuna.php",
                method: "POST",
                data: vm.objeto
            }).then(function(response) {
                if(response.data == null || response.data == 'null'){
                    vm.comunaLista = [];
                }else{
                    vm.comunaLista = response.data;
                    if(opcion == 2){
                        vm.registro.ubicacion.comuna = vm.seleccionado.comuna;
                    }
                    cargarBarrioVereda(opcion);
                }
            });
        }

        function cargarBarrioVereda(opcion){
            vm.registro.ubicacion.barrioVereda = {};
            vm.barrioVeredaLista = [];
            vm.objeto = {
                accion: 5,
                comuna: vm.registro.ubicacion.comuna
            };
            $http({
                url: "logica/barrioVereda.php",
                method: "POST",
                data: vm.objeto
            }).then(function(response) {
                if(response.data == null || response.data == 'null'){
                    vm.barrioVeredaLista = [];
                }else{
                    vm.barrioVeredaLista = response.data;
                    if(opcion == 2){
                        vm.registro.ubicacion.barrioVereda = vm.seleccionado.barrioVereda;
                    }
                }
            });
        }

        function cargarMunicipioVotacion(opcion){
            vm.registro.votacion.municipio = {};
            vm.registro.votacion.puesto = {};
            vm.municipioVotacionLista = [];
            vm.categoria = {};
            vm.candidato = {};
            vm.registro.votacion.intencionVoto = [];
            vm.objeto = {
                accion: 5,
                departamento: vm.registro.votacion.departamento
            };
            $http({
                url: "logica/municipio.php",
                method: "POST",
                data: vm.objeto
            }).then(function(response) {
                if(response.data == null || response.data == 'null'){
                    vm.municipioVotacionLista = [];
                }else{
                    vm.municipioVotacionLista = response.data;
                    if(opcion == 2){
                        vm.registro.votacion.municipio = vm.seleccionado.municipioVotacion;
                    }
                    cargarPuesto(opcion);
                }
            });
        }

        function cargarPuesto(opcion){
            vm.registro.votacion.puesto = {};
            vm.puestoLista = [];
            vm.categoria = {};
            vm.candidato = {};
            vm.registro.votacion.intencionVoto = [];
            vm.objeto = {
                accion: 5,
                municipio: vm.registro.votacion.municipio
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
                    if(opcion == 2){
                        vm.registro.votacion.puesto = vm.seleccionado.puesto; 
                        cargarIntencionVoto(); 
                    }
                }
            });
        }

        function cargarCandidato(){
            vm.candidato = {};
            vm.candidatoLista = [];
            vm.objeto = {
                accion: 5,
                categoria: vm.categoria,
                departamento: vm.registro.votacion.departamento,
                municipio: vm.registro.votacion.municipio
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

        function cargarIntencionVoto(){
            vm.registro.votacion.intencionVoto = [];
            vm.objeto = {
                accion: 4,
                idPersona: vm.id
            };
            $http({
                url: "logica/registro.php",
                method: "POST",
                data: vm.objeto
            }).then(function(response) {
                if(response.data == null || response.data == 'null'){
                    vm.registro.votacion.intencionVoto = [];
                }else{
                    vm.registro.votacion.intencionVoto = response.data;
                }
            });
        }

        function agregarCandidato(){
            var existe = false;
            for(var i = 0; i < vm.registro.votacion.intencionVoto.length; i++){
                if(vm.categoria.id == vm.registro.votacion.intencionVoto[i].categoriaId 
                    && vm.registro.votacion.intencionVoto[i].eliminado == 1){
                    existe = true;
                }
            }
            if(existe){
                vm.mensaje.mensaje = 'La categoria política ya fue seleccionada';
                $('#modalError').modal('show');
                return false;
            }
            console.log(vm.categoria);
            console.log(vm.candidato);
            vm.registro.votacion.intencionVoto.push({
                "id":0,
                "categoriaId":vm.categoria.id,
                "categoriaNombre":vm.categoria.nombre,
                "candidatoId":vm.candidato.id,
                "candidatoNombre":vm.candidato.nombre,
                "eliminado": 1
            });
            vm.categoria = {};
            vm.candidato = {};
        }

        function eliminarCandidato(item){
            for(var i = 0; i < vm.registro.votacion.intencionVoto.length; i++){
                if(item.categoriaId == vm.registro.votacion.intencionVoto[i].categoriaId 
                    && item.candidatoId == vm.registro.votacion.intencionVoto[i].candidatoId 
                    && item.id == vm.registro.votacion.intencionVoto[i].id){
                    vm.registro.votacion.intencionVoto[i].eliminado = 0;
                }
            }
        }

        function guardarCambios(tab){

            var mensaje = '';
            if((vm.id != '0' || vm.id != 0) && (vm.lider != vm.usuario.nombre) && (vm.registro.personal.documento != vm.usuario.nombre) && (vm.usuario.codigoPerfilUsuario == 2 
                || vm.usuario.codigoPerfilUsuario == '2')){
                vm.mensaje.mensaje = 'El lider logueado no tiene permisos para realizar cambios al registro.';
                $('#modalError').modal('show');
                return false;
            }

            if(tab == 1 || tab == '1'){
                if(vm.registro.personal.documento == ''){
                    mensaje += "* El campo Nùmero de Cedula es obligatorio" + '<br/>';
                }
                if(vm.registro.personal.primerNombre == ''){
                    mensaje += "* El campo Primer Nombre es obligatorio" + '<br/>';
                }
                if(vm.registro.personal.primerApellido == ''){
                    mensaje += "* El campo Primer Apellido es obligatorio" + '<br/>';
                }
                if(vm.registro.personal.fechaNacimiento == ''){
                    mensaje += "* El campo Fecha de Nacimiento es obligatorio" + '<br/>';
                }
                if(vm.registro.personal.grupoSanguineo.id == null){
                    mensaje += "* El campo Grupo Sanguineo y Factor RH es obligatorio" + '<br/>';
                }
                if(vm.registro.personal.sexo.id == null){
                    mensaje += "* El campo Sexo es obligatorio" + '<br/>';
                }
                if(vm.registro.personal.orientacionSexual.id == null){
                    mensaje += "* El campo Orientacion Sexual es obligatorio" + '<br/>';
                }
                if(vm.registro.personal.estadoCivil.id == null){
                    mensaje += " * El campo Estado Civil es obligatorio" + '<br/>';
                }
                if(vm.nombreLider == ''){
                    mensaje += "* El campo Lider es obligatorio" + '<br/>';
                }
            }else if(tab == 2 || tab == '2'){
                if(vm.registro.ubicacion.departamento.id == null){
                    mensaje += "* El campo Departamento es obligatorio" + '<br/>';
                }
                if(vm.registro.ubicacion.municipio.id == null){
                    mensaje += "* El campo Ciudad/Municipio es obligatorio" + '<br/>';
                }
                if(vm.registro.ubicacion.localidad.id == null){
                    mensaje += "* El campo Localidad es obligatorio" + '<br/>';
                }
                if(vm.registro.ubicacion.comuna.id == null){
                    mensaje += "* El campo Comuna es obligatorio" + '<br/>';
                }
                if(vm.registro.ubicacion.barrioVereda.id == null){
                    mensaje += "* El campo Barrio/Vereda es obligatorio" + '<br/>';
                }
                if(vm.registro.ubicacion.tipoVivienda.id == null){
                    mensaje += "* El campo Tipo de Vivienda es obligatorio" + '<br/>';
                }
                if(vm.registro.ubicacion.direccionResidencia == '' || vm.registro.ubicacion.direccionResidencia == null){
                    mensaje += "* El campo Direccion Residencia es obligatorio" + '<br/>';
                }
            }else if(tab == 3 || tab == '3'){
                if(vm.registro.demografica.estrato.id == null){
                    mensaje += "* El campo Estrato es obligatorio" + '<br/>';
                }
                if(vm.registro.demografica.grupoSisben.id == null){
                    mensaje += "* El campo Grupo SISBEN es obligatorio" + '<br/>';
                }
                if(vm.registro.demografica.servicio.length == 0){
                    mensaje += "* El campo Servicios es obligatorio" + '<br/>';
                }
            }else if(tab == 4 || tab == '4'){
                if(vm.registro.votacion.departamento.id == null){
                    mensaje += "* El campo Departamento es obligatorio" + '<br/>';
                }
                if(vm.registro.votacion.municipio.id == null){
                    mensaje += "* El campo Ciudad/Municipio es obligatorio" + '<br/>';
                }
                if(vm.registro.votacion.puesto.id == null){
                    mensaje += "* El campo Puesto es obligatorio" + '<br/>';
                }
                if(vm.registro.votacion.mesa.id == null){
                    mensaje += "* El campo Mesa es obligatorio" + '<br/>';
                }
            }



            if(mensaje != ''){
                $("#validacionTexto").html(mensaje);
                $('#modalValidate').modal('show');
            }else{
                $('#modalCargando').modal('show');
                if(tab == 1 || tab == '1'){
                    var archivos = document.getElementById("archivos");
                    var archivo = archivos.files;
                    var archivos = new FormData();
                    var cant = archivo.length;
                    if(archivo.length > 0){
                        for(i=0; i<archivo.length; i++){
                            archivos.append('archivo'+i,archivo[i]);
                        }
                        $.ajax({
                            type: "POST",
                            url: 'logica/upload.php',
                            data: archivos,
                            dataType: "json",
                            async: true,
                            processData: false,
                            contentType: false,
                            success: function (response) {
                                guardarBd(response.imagenServidor, response.imagenOriginal, tab, cant);
                            },
                            error: function () {
                                alert("No se ha procesado su solicitud en estos momentos. error")
                            }
                        })
                    }else{
                        guardarBd("", "", tab, cant);
                    }
                }else{
                    guardarBd("", "", tab, 0);
                }

            }

        }

        function guardarBd(imagenServidor, imagenOriginal, tab, cant){
            if(cant > 0){
                vm.imagen = 'imagenes/'+imagenServidor;
                vm.registro.personal.foto = imagenServidor;
            }
            vm.objeto = {
                accion: 1,
                tab: tab,
                id: vm.id,
                informacion: vm.registro,
                lider: vm.lider
            }
            $http({
                url: "logica/registro.php",
                method: "POST",
                data: vm.objeto
            }).then(function(response) {
                vm.mensaje = response.data;
                $('#modalCargando').modal('hide');
                if(vm.mensaje.resultado){
                    $('#modalBien').modal('show');
                    if(tab == 1){
                        vm.id = vm.mensaje.id;
                        vm.deshabilitarGuardarCambios = false;
                        $('#archivos').val('');
                        $('#inputImagen').val('');
                    }
                    if(tab == 4){
                        cargarIntencionVoto();
                    }
                }else{
                    $('#modalError').modal('show');
                }
            });
        }

        function limpiar(todo){
            vm.id = 0;
            vm.deshabilitarGuardarCambios = true;
            if(todo){
                vm.registro.personal.documento = '';
            }
            vm.registro.personal.primerNombre = '';
            vm.registro.personal.segundoNombre = '';
            vm.registro.personal.primerApellido = '';
            vm.registro.personal.segundoApellido = '';
            vm.registro.personal.fechaNacimiento = '';
            vm.registro.personal.grupoSanguineo = {};
            vm.registro.personal.orientacionSexual = {};
            vm.registro.personal.sexo = {};
            vm.registro.personal.estadoCivil = {};
            vm.registro.personal.correoElectronico = '';
            vm.registro.personal.numeroCelular = '';
            vm.registro.personal.numeroWhatsapp = '';
            vm.registro.personal.foto = '';
            vm.imagen = 'dist/img/user.jpg';
            $('#archivos').val('');
            $('#inputImagen').val('');
            vm.lider = vm.usuario.nombre;
            vm.nombreLider = vm.usuario.nombrePersona;

            vm.registro.ubicacion.departamento = {};
            vm.registro.ubicacion.municipio = {};
            vm.registro.ubicacion.localidad = {};
            vm.registro.ubicacion.comuna = {};
            vm.registro.ubicacion.barrioVereda = {};
            vm.registro.ubicacion.tipoVivienda = {};
            vm.registro.ubicacion.direccionResidencia = '';

            vm.registro.demografica.estrato = {};
            vm.registro.demografica.grupoSisben = {};
            vm.registro.demografica.servicio = [];
            $('.select2-selection__rendered').empty();
            vm.registro.demografica.discapacidad = {};
            vm.registro.demografica.grupoEtnico = {};
            vm.registro.demografica.nivelEducativo = {};
            vm.registro.demografica.profesionArte = {};
            vm.registro.demografica.ocupacion = {};

            vm.registro.votacion.departamento = {};
            vm.registro.votacion.municipio = {};
            vm.registro.votacion.puesto = {};
            vm.registro.votacion.mesa = {};
            vm.registro.votacion.intencionVoto = [];

            vm.categoria = {};
            vm.candidato = {};
            
        }

        function buscarLider(){
            vm.nombreLider = '';
            if(vm.usuario == ''){
                return false;
            }
            vm.objeto = {
                accion: 6,
                documento: vm.lider
            };
            $http({
                url: "logica/usuario.php",
                method: "POST",
                data: vm.objeto
            }).then(function(response) {
                if(response.data.length > 0){
                    vm.nombreLider = response.data[0].nombre;
                }
            });
        }

    }
})();