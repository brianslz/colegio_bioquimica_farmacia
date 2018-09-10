<template>
        <main class="main">
            <!-- Breadcrumb -->
            <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/">Escritorio</a></li>
            <li class="breadcrumb-item"><a href="#">Usuarios</a></li>
            </ol>
            <div class="container-fluid">
                <!-- Ejemplo de tabla Listado -->
                <div class="card">
                    <div class="card-header">
                        <i class="fa fa-align-justify"></i> Usuarios
                    </div>
                    <div class="card-body">
                        <div class="alert alert-warning" role="alert">
                            En esta sección solo se visualizaran los usuarios que son administradores del sistema
                        </div>
                        <table class="table table-bordered table-striped table-sm">
                            <thead>
                                <tr>
                                    <th>Usuario</th>
                                    <th>Persona</th>
                                    <th>Rol</th>
                                    <th>Estado</th>
                                    <th>Opciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="usuario in arrayUsuario" :key="usuario.id">
                                    <td v-text="usuario.usuario"></td>
                                    <td> {{ usuario.apellido_paterno+' '+usuario.apellido_materno+' '+usuario.nombres }} </td>

                                    <td v-text="'Administrador'"></td>
                                    <td>
                                        <div v-if="usuario.condicion">
                                            <span class="badge badge-success">Activo</span>
                                        </div>
                                        <div v-else>
                                            <span class="badge badge-danger">Desactivado</span>
                                        </div>
                                    </td>
                                    <td>
                                        <button type="button" @click="abrirModal('usuario','actualizar',usuario)" class="btn btn-warning btn-sm">
                                            <!-- abrirModal('MODELO','ACCION','OBJETO a act')-->
                                          <i class="icon-pencil"></i>
                                        </button> &nbsp;
                                        <!--boton para arbrir alerta y desactivar-->
                                        <button class="btn btn-danger btn-sm" @click="abrirModal('usuario','actualizarPrivilegios',usuario)" >
                                            <i class="icon-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <!-- Fin ejemplo de tabla Listado -->
            </div>
            <!--Inicio del modal agregar/actualizar-->
            <div class="modal fade" tabindex="-1" :class="{'mostrar' : modal}" role="dialog" aria-labelledby="myModalLabel" style="display: none;" aria-hidden="true">
                <!--si modal = 1 se mostrara -->
                <div class="modal-dialog modal-primary modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title" v-text="tituloModal"></h4>
                            <button type="button" class="close" @click="cerrarModal" aria-label="Close">
                              <span aria-hidden="true">×</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form action="" method="post" enctype="multipart/form-data" class="form-horizontal">
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Usuario </label>
                                    <div class="col-md-9">
                                        <input type="text" v-model="nombreUsuario" class="form-control" disabled>
                                        
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="email-input">Contraseña Nueva</label>
                                    <div class="col-md-9">
                                        <input type="text" v-model="contrasena" class="form-control" placeholder="Ingrese Contraseña Nueva">
                                    </div>
                                </div>  
                                <div v-show="errorUsuario" class="form-group row div-error">
                                    <div class="text-center text-error">
                                        <div v-for="error in errorMostrarMsjUsuario" :key="error" v-text="error">
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" @click="cerrarModal">Cerrar</button>
                            <button type="button" class="btn btn-primary" v-if="tipoAccion==2" @click="actualizarUsuario()">Actualizar</button>
                        </div>
                    </div>
                    <!-- /.modal-content -->
                </div>
                <!-- /.modal-dialog -->
            </div>
            <!--Fin del modal-->
        </main>
</template>

<script>
    export default {
        data(){
            return {
                usuario_id:0,
                nombreUsuario:'',
                contrasena:'',
                arrayUsuario:[],
                modal : 0,
                //modal para ocultar o mostrar el mismo
                tituloModal : '',
                tipoAccion:0,
                errorUsuario:0,
                errorMostrarMsjUsuario:[],

                offset:3,
                criterio:'nombreUsuario',//cual es el campo de busqueda
                buscar:''//texto a buscar
            }
        },

        methods:{
            listarUsuario (page,buscar,criterio){
                let me=this;
                var url= '/userAdmin';
                axios.get(url).then(function (response) {
                    var respuesta= response.data;
                    me.arrayUsuario = respuesta.users;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },

            actualizarUsuario(){
                if(this.validarUsuario()){
                    return;
                }
                let me = this;
                //enviamos 2 parametros (ruta,array)
                axios.put('/userAdmin/actualizar',{'id':this.usuario_id,'contrasena':this.contrasena})
                .then(function(response){
                    me.cerrarModal();
                    me.listarUsuario(1,'','nombreUsuario');
                }).catch(function(error){
                    console.log(error);
                });//fin axios
            },
            abrirModal(modelo, accion, data=[]){
                //data sera = a [array] en caso de llegar vacio
                switch(modelo)
                {
                    case 'usuario':
                    {
                        //MODELO = CATEGORIA
                        switch(accion){
                            case 'actualizar':
                            {
                                //ACCION = ACTUALIZAR
                                //console.log(data);//para mostrar el objeto
                                this.modal=1;
                                this.tituloModal='Actualizar Contraseña';
                                this.tipoAccion=2;//para mostrar boton actualizar
                                this.nombreUsuario=data['usuario'];
                                this.usuario_id=data['id'];
                                break;
                            }
                            case 'actualizarPrivilegios':
                            {
                                //ACCION = ACTUALIZAR
                                //console.log(data);//para mostrar el objeto
                                this.modal=1;
                                this.tituloModal='Actualizar Contraseña';
                                this.tipoAccion=2;//para mostrar boton actualizar
                                this.nombreUsuario=data['usuario'];
                                this.usuario_id=data['id'];
                                break;
                            }
                        }
                    }
                }
            },

            validarUsuario(){
                this.errorUsuario=0;
                this.errorMostrarMsjUsuario=[];
                if(!this.nombreUsuario) this.errorMostrarMsjUsuario.push("El nombre de usuario no puede estar vacío");
                if(!this.contrasena) this.errorMostrarMsjUsuario.push("Ingrese una contraseña valida");
                if(this.errorMostrarMsjUsuario.length) this.errorUsuario=1;
                return this.errorUsuario;
            },
            cerrarModal(){
                this.modal=0;
                this.tituloModal=0;
                this.errorUsuario=0;
                this.errorMostrarMsjUsuario=[];
                this.nombreUsuario='';
                this.contrasena='';
            },
        },
        mounted() {
            this.listarUsuario(1,this.buscar,this.criterio);
        }
    }
</script>
<style>
    .modal-content{
        width: 100% !important;
        position: absolute !important;
    }
    .mostrar{
        display: list-item !important;
        opacity: 1 !important;
        position: fixed !important;
        background-color: #3c29297a !important;
    }
    .div-error{
        display: flex;
        justify-content: center;
    }
    .text-error{
        color:red !important;
        font-weight: bold;
    }
</style>

