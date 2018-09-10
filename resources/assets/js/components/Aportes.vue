<template>
        <main class="main">
            <!-- Breadcrumb -->
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/">Escritorio</a></li>
                <li class="breadcrumb-item"><a href="#">Aportes</a></li>
            </ol>
            <div class="container-fluid">
                <!-- Ejemplo de tabla Listado -->
                <div class="card">
                    <div class="card-header">
                        <i class="fa fa-align-justify"></i> Control de Aportes
                    </div>
                    <!--Listado, Tabla de afiliados-->
                    <template v-if="listado">
                        <div class="card-body">
                        <div class="form-group row">
                            <div class="col-md-6">
                                <div class="input-group">
                                    <select class="form-control col-md-3" v-model="criterio">
                                      <option value="ci">C.I.</option>
                                      <option value="codigounico">Carnet Colegio </option>
                                      <option value="apellido_paterno">Apellido Paterno</option>
                                    </select>
                                    <input type="text" v-model="buscar" @keyup.enter="listarAfiliado(1,buscar,criterio)" class="form-control" placeholder="Texto a buscar">
                                    <button type="submit" @click="listarAfiliado(1,buscar,criterio)" class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
                                </div>
                            </div>
                        </div>
                        <table class="table table-bordered table-striped table-sm">
                            <thead>
                                <tr>
                                    <th>Paterno</th>
                                    <th>Materno</th>
                                    <th>Nombres</th>
                                    <th>CI</th>
                                    <th>Carnet Colegio</th>
                                    <th>Estado</th>
                                    <th>Opciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="afiliado in arrayAfiliado" :key="afiliado.id">
                                        <td v-text="afiliado.apellido_paterno"></td>
                                        <td v-text="afiliado.apellido_materno"></td>
                                        <td v-text="afiliado.nombres"></td>
                                        <td v-text="afiliado.ci"></td>
                                        <td v-text="afiliado.codigounico"></td>
                                    <td>
                                        <div v-if="afiliado.condicion">
                                            <span class="badge badge-success">Activo</span>
                                        </div>
                                        <div v-else>
                                            <span class="badge badge-danger">Desactivado</span>
                                        </div>
                                    </td>
                                    <td>
                                        <button type="button" @click="mostrarListado(afiliado)" v-tooltip.bottom="'Ver Aportes'"  class="btn btn-primary btn-sm">
                                          <i class="icon-eye"></i>
                                        </button>
                                        <button v-if="afiliado.condicion" type="button" @click="abrirModal('afiliado','registrar',afiliado)" v-tooltip.bottom="'Registrar Aporte'" class="btn btn-secondary btn-sm">
                                          <i class="icon-note"></i>
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <nav>
                            <ul class="pagination">
                                <li class="page-item" v-if="pagination.current_page > 1">
                                    <a class="page-link" href="#" @click.prevent="cambiarPagina(pagination.current_page - 1,buscar,criterio)">Ant</a>
                                </li>
                                <li class="page-item" v-for="page in pagesNumber" :key="page" :class="[page == isActived ? 'active' : '']">
                                    <a class="page-link" href="#" @click.prevent="cambiarPagina(page,buscar,criterio)" v-text="page"></a>
                                </li>
                                <li class="page-item" v-if="pagination.current_page < pagination.last_page">
                                    <a class="page-link" href="#" @click.prevent="cambiarPagina(pagination.current_page + 1,buscar,criterio)">Sig</a>
                                </li>
                            </ul>
                        </nav>
                    </div>
                </template>
                <template v-else>
                    <div class="card-body">
                        <div class="form-group border row">
                            <div class="col-md-offset-2 col-md-9">
                                <p><strong>Afiliado:</strong> {{ apellido_paterno }}&nbsp;{{ apellido_materno }} &nbsp; {{nombres}} </p> 
                                <p> <strong>C.I:</strong>{{ci}} <strong> &nbsp; Carnet Colegio </strong> {{codigounico}}  </p>
                            </div>
                            <div class="col-md-offset-2 col-md-9">
                            <p><strong>Modalidad de Pago: </strong> {{ modalidad }}  &nbsp; <strong>Fecha de ingreso: </strong>{{ desde(fecha_modalidad) }}</p>
                            <p> <strong>Pago hasta: </strong>{{ desde(fecha_ultimo_pago) }} 
                                <button v-if="actualDeuda(fecha_ultimo_pago)" type="button" class="btn btn-success btn-sm">
                                    <i class="icon-user"></i> Sin Deudas
                                </button>

                                <button v-else type="button" class="btn btn-danger btn-sm">
                                    <i class="icon-user"></i> Deudor
                                </button>
                            </p>
                            </div>
                        </div>
                        <div v-for="aporte in arrayAportes" :key="aporte.id" >
                            <table class="table table-bordered table-sm">
                                <tr>
                                    <th>Modaliad: </th><td>{{ aporte.modalidad }}</td> <th>Comprobante: </th><td>{{aporte.codigo_verficacion}} </td> <th>Fecha de Pago: </th><td>{{ desde(aporte.fecha_pago) }}</td> 
                                </tr>
                                <tr>
                                    <th>Numero de Meses: </th><td>{{aporte.num_meses}} </td> <th>Monto: </th><td>{{aporte.monto}}</td>  <th>Vale hasta: </th><td>{{ desde(aporte.fecha_vencimiento) }} </td> 
                                </tr>
                            </table>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-6">
                                <button type="button" @click="ocultarFormulario()" class="btn btn-secondary">Cerrar</button>
                                <button type="button" @click="reporteAportes()" class="btn btn-success"><i class="icon-printer"></i> Imprimir </button>
                            </div>
                        </div>
                    </div><!-- FIN Formulario-->
                </template>
                </div>
                <!-- Fin ejemplo de tabla Listado -->
            </div>

            <!--Inicio del modal agregar/actualizar-->
            <div class="modal fade" tabindex="-1" :class="{'mostrar' : modal}" role="dialog" aria-labelledby="myModalLabel" style="display: none;" aria-hidden="true">
                <div class="modal-dialog modal-primary modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title" v-text="tituloModal"></h4>
                            <button type="button" class="close" @click="cerrarModal()" aria-label="Close">
                              <span aria-hidden="true">×</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form action="" method="post" enctype="multipart/form-data" class="form-horizontal">
                                <div class="form-group border row">
                                    <div class="col-md-offset-2 col-md-9">
                                        <p><strong>Afiliado:</strong> {{ apellido_paterno }}&nbsp;{{ apellido_materno }} &nbsp; {{nombres}} </p> 
                                        <p> <strong>C.I:</strong>{{ci}} <strong> &nbsp; Carnet Colegio: </strong> {{codigounico}}  </p>
                                    </div>
                                    <div class="col-md-offset-2 col-md-9">
                                    <p><strong>Modalidad de Pago: </strong> {{ modalidad }}  &nbsp; <strong>Fecha de ingreso: </strong>{{ desde(fecha_modalidad) }}</p>
                                    <p> <strong>Pago hasta: </strong>{{ desde(fecha_ultimo_pago) }}

                                        <button v-if="actualDeuda(fecha_ultimo_pago)" type="button" class="btn btn-success btn-sm">
                                            <i class="icon-user"></i> Sin Deudas
                                        </button>

                                        <button v-if="actualDeuda(fecha_ultimo_pago) && actualPromo(fecha_ultimo_pago)" @click="pagarGestion()" v-tooltip.bottom="'Valido Hasta el 31 de Marzo'" type="button" class="btn btn-primary btn-sm">
                                            <i class="icon-user"></i> Pagar por una Gestion
                                        </button>

                                        <button v-if="!actualDeuda(fecha_ultimo_pago)" type="button" class="btn btn-danger btn-sm">
                                            <i class="icon-user"></i> Deudor
                                        </button>
                                    </p>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Fecha </label>
                                    <div class="col-md-3">
                                        <input type="date" v-model="fecha_pago" class="form-control" placeholder="00">
                                    </div>
                                    
                                    <label class="col-md-3 form-control-label" for="text-input">N° de Comprobante </label>
                                    <div class="col-md-3">
                                        <input type="text" v-model="codigo_verficacion" class="form-control" placeholder="00">
                                    </div>
                                </div>
                                

                                <div class="form-group row" v-if="promo"><!--formulario promocion de un año-->
                                    <label class="col-md-3 form-control-label" for="text-input">Numero de Meses (*)</label>
                                    <div class="col-md-3">
                                        <input type="number" v-model="num_meses" class="form-control" disabled>
                                    </div>
                                    <label class="col-md-3 form-control-label" for="text-input">Monto a Pagar</label>
                                    <div class="col-md-3">
                                        <strong> {{ monto =  200 }} Bolivianos </strong>
                                    </div>
                                </div>

                                <div class="form-group row" v-else>
                                    <label class="col-md-3 form-control-label" for="text-input">Numero de Meses (*)</label>
                                    <div class="col-md-3">
                                        <input type="number" v-model="num_meses" class="form-control" placeholder="00">
                                    </div>
                                    <label class="col-md-3 form-control-label" for="text-input">Monto a Pagar</label>
                                    <div class="col-md-3">
                                        <strong> {{ monto =  num_meses * 20 }} Bolivianos </strong>
                                    </div>
                                </div>



                                <div v-if="num_meses > 0" class="form-group row">
                                    <label class="col-md-4 form-control-label" for="text-input"> Duracion del pago sera hasta :  </label>
                                    <div class="col-md-3">
                                        <strong> {{ getFechaVencimiento(fecha_ultimo_pago,num_meses) }} </strong>
                                    </div>
                                </div>
                                
                                <div v-show="errorAfiliado" class="form-group row div-error">
                                    <div class="text-center text-error">
                                        <div v-for="error in errorMostrarMsjAfiliado" :key="error" v-text="error">

                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" @click="cerrarModal()">Cerrar</button>
                            <button type="button" v-if="tipoAccion==1" class="btn btn-primary" @click="registrarAporte()">Registrar Pago</button>
                            <button type="button" v-if="tipoAccion==2" class="btn btn-primary" @click="actualizarAporte()">Actualizar Pago</button>
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
import moment,{ months } from 'moment';
moment.locale('es');
import VTooltip from 'v-tooltip';


    export default {
        data(){
            return {

                //datos del afiliado 
                idafiliado:0, //usaremos tambien en pago
                apellido_paterno:'',
                apellido_materno:'',
                nombres:'',
                ci:'',
                codigounico:'',
                //datos entre el afiliado y sus aportes
                modalidad:'',
                fecha_modalidad:'',
                fecha_ultimo_pago:'',

                //datos del aporte
                //id:0,
                codigo_verficacion:'',
                fecha_pago:'',
                num_meses:'',
                fecha_vencimiento:'',
                monto:'',
                
                arrayAfiliado:[],
                arrayAportes:[],
                modal : 0,
                listado:1,
                //modal para ocultar o mostrar el mismo
                tituloModal : '',
                tipoAccion:0,
                errorAfiliado:0,

                promo:false,

                errorMostrarMsjAfiliado:[],
                pagination:{
                    'total':0,
                    'current_page':0,
                    'per_page':0,
                    'last_page':0,
                    'from':0,
                    'to':0,
                },
                offset:3,
                criterio:'ci',//cual es el campo de busqueda
                buscar:''//texto a buscar
            }
        },
        computed:{
            isActived: function(){
                return this.pagination.current_page;
            },
            //Calcula los elementos de la paginación
            pagesNumber: function() {
                if(!this.pagination.to) {
                    return [];
                }
                
                var from = this.pagination.current_page - this.offset; 
                if(from < 1) {
                    from = 1;
                }

                var to = from + (this.offset * 2); 
                if(to >= this.pagination.last_page){
                    to = this.pagination.last_page;
                }

                var pagesArray = [];
                while(from <= to) {
                    pagesArray.push(from);
                    from++;
                }
                return pagesArray;
            }
        },
        methods:{
            listarAfiliado (page,buscar,criterio){
                let me=this;
                var url= '/afiliadoAporte?page=' + page + '&buscar='+ buscar + '&criterio='+ criterio;
                //listamos afiliados segun esta ruta
                axios.get(url).then(function (response) {
                    var respuesta= response.data;
                    me.arrayAfiliado = respuesta.afiliados.data;
                    me.pagination= respuesta.pagination;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },

            cambiarPagina(page,buscar,criterio){
                let me = this;
                //actaliza la pagina actual
                me.pagination.current_page=page;
                //envia la peticion para visualizar la DATA de esta pagina
                me.listarAfiliado(page,buscar,criterio);
            },

            registrarAporte(){
                if(this.validarAfiliado()){
                    return;
                }

                let me = this;
                //enviamos 2 parametros (ruta,array)
                axios.post('/aporte/registrar',{
                    'idafiliado':this.idafiliado,
                    'modalidad':this.modalidad,
                    'fecha_modalidad':this.fecha_modalidad,
                    'codigo_verficacion':this.codigo_verficacion,
                    'fecha_pago':this.fecha_pago,
                    'num_meses':this.num_meses,
                    'fecha_ultimo_pago':this.fecha_ultimo_pago, //le vamos  a sumar num meses
                    'monto':this.monto})
                .then(function(response){
                    me.cargarPdf();
                    me.cerrarModal();
                    me.listarAfiliado(1,'','');
                }).catch(function(error){
                    console.log(error);
                });//fin axios
            },

            actualizarAporte(){
                if(this.validarAfiliado()){
                    return;
                }
                let me = this;
                //enviamos 2 parametros (ruta,array)
                axios.put('/afiliado/actualizar',{'id':this.idafiliado,'nombre':this.nombre,'descripcion':this.descripcion})
                .then(function(response){
                    me.cerrarModal();
                    me.listarAfiliado(1,'','ci');
                }).catch(function(error){
                    console.log(error);
                });//fin axios
            },
            
            abrirModal(modelo, accion, data=[]){
            //abrirModal('afiliado','registrar',afiliado)" 
                switch(modelo)
                {
                    case 'afiliado':
                    {
                        switch(accion){
                            case 'registrar':
                            {
                               
                                this.tituloModal='Registrar Aporte';

                                //cargamos el afiliado
                                this.idafiliado=data["id"]
                                this.apellido_paterno=data["apellido_paterno"],
                                this.apellido_materno=data["apellido_materno"],
                                this.nombres=data["nombres"],
                                this.ci=data["ci"],
                                this.codigounico=data["codigounico"],
                                this.modalidad=data["modalidad"],
                                this.fecha_modalidad=data["fecha_modalidad"],
                                //fecha_ultimo_pago  se carga con funcion
                                this.cargarUltimoPago(this.idafiliado)
                                //alistamos los campos del pago
                                this.codigo_verficacion='',
                                this.fecha_pago='',
                                this.num_meses='',
                                this.fecha_vencimiento='',
                                this.monto='',

                                //NOS QUEDAMOS AQUI
                                /*
                                IDEAS 
                                CARGAR EL ULTIMO PAGO
                                MOSTRAR ESTADO DEL AFILIADO 
                                (SI DEBE)
                                (CUANTO DEBE) MESES O BS                                
                                */

                                this.modal=1;
                                this.nombre = '';
                                this.descripcion = '';


                                this.tipoAccion=1;//para mostrar boton agregar
                                break;
                            }
                            case 'actualizar':
                            {

                                this.modal=1;
                                this.tituloModal='Actualizar categoría';
                                this.tipoAccion=2;//para mostrar boton actualizar
                                
                                this.nombre=data['nombre'];
                                this.descripcion=data['descripcion'];
                                this.idafiliado=data['id'];
                                break;
                            }
                        }
                    }
                }
            },

            cargarUltimoPago(id){
                let me = this;
                var url= '/Aporte/ultimoPago?id=' + id ;
                //listamos afiliados segun esta ruta
                axios.get(url).then(function (response) {
                    var respuesta= response.data;
                    me.fecha_ultimo_pago = respuesta.pago.fecha_vencimiento;
                })
                .catch(function (error) {
                    //console.log(error);
                    //si el afiliado es nuevo ... 
                    me.fecha_ultimo_pago = me.fecha_modalidad;
                });
            },

            mostrarListado(data=[]){
                //nuevo registro
                this.listado=0;
                //cargamos el afiliado
                this.idafiliado=data["id"]
                this.apellido_paterno=data["apellido_paterno"],
                this.apellido_materno=data["apellido_materno"],
                this.nombres=data["nombres"],
                this.ci=data["ci"],
                this.codigounico=data["codigounico"],
                this.modalidad=data["modalidad"],
                this.fecha_modalidad=data["fecha_modalidad"],
                //fecha_ultimo_pago  se carga con funcion
                this.cargarUltimoPago(this.idafiliado),
                this.cargarAportes(this.idafiliado)
            },
            ocultarFormulario(){
                this.listado=1;

                //this.afiliado_id= 0;
                //this.apellido_paterno='';
                //this.apellido_materno='';
            },
            cargarAportes(id){
                let me=this;
                var url= '/aporte/getAportes?id='+id;
                //listamos afiliados segun esta ruta
                axios.get(url).then(function (response) {
                    var respuesta= response.data;
                    me.arrayAportes = respuesta.pagos;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },

            validarAfiliado(){
                this.errorAfiliado=0;
                this.errorMostrarMsjAfiliado=[];
                if(!this.fecha_pago) this.errorMostrarMsjAfiliado.push("Introduce la Fecha");
                if(!this.codigo_verficacion) this.errorMostrarMsjAfiliado.push("Introduce el numero de comprobante");
                if(!this.num_meses) this.errorMostrarMsjAfiliado.push("Introduce el numero de meses a pagar");
                if(this.errorMostrarMsjAfiliado.length) this.errorAfiliado=1;
                return this.errorAfiliado;
            },
            cerrarModal(){
                this.modal=0;
                this.tituloModal=0;
                this.nombre='';
                this.descripcion='';

                this.errorAfiliado=0;
                this.promo=false;
                this.errorMostrarMsjAfiliado=[];

            },
            cargarPdf(){
                window.open('http://127.0.0.1:8000/pago/reciboPdf?id='+this.idafiliado,'_blank');
            },
            reporteAportes(){
                window.open('http://127.0.0.1:8000/pago/aportesPdf?id='+this.idafiliado,'_blank');
            },

            desde(date){
                return moment(date).format("D MMMM YYYY");
            },

            actualDeuda(date){
                //console.log(moment().isBefore(date)); falso si debe, true No debe 
                return moment().isBefore(date);
            },

            actualPromo(date){
                //manejamos rango de fechas, por alguna razon los meses los cuenta desde 0 y no 1 
                var r1 = moment([ moment().year(),0,1]).format("YYYY-MM-DD");
                var r2 = moment([ moment().year(),2,31]).format("YYYY-MM-DD");
                return moment().isBetween(r1,r2);
            },

            pagarGestion(){
                this.promo = true;
                this.num_meses = 12;
            },
            getFechaVencimiento(date,n){
                return moment(date).add(n,'months').format("D MMMM YYYY");
            }
        },
        mounted() {
            this.listarAfiliado(1,this.buscar,this.criterio);
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
    
    .tooltip {
    display: block !important;
    z-index: 10000;
    }

    .tooltip .tooltip-inner {
    background: black;
    color: white;
    border-radius: 16px;
    padding: 5px 10px 4px;
    }

    .tooltip .tooltip-arrow {
    width: 0;
    height: 0;
    border-style: solid;
    position: absolute;
    margin: 5px;
    border-color: black;
    }

    .tooltip[x-placement^="top"] {
    margin-bottom: 5px;
    }

    .tooltip[x-placement^="top"] .tooltip-arrow {
    border-width: 5px 5px 0 5px;
    border-left-color: transparent !important;
    border-right-color: transparent !important;
    border-bottom-color: transparent !important;
    bottom: -5px;
    left: calc(50% - 5px);
    margin-top: 0;
    margin-bottom: 0;
    }

    .tooltip[x-placement^="bottom"] {
    margin-top: 5px;
    }

    .tooltip[x-placement^="bottom"] .tooltip-arrow {
    border-width: 0 5px 5px 5px;
    border-left-color: transparent !important;
    border-right-color: transparent !important;
    border-top-color: transparent !important;
    top: -5px;
    left: calc(50% - 5px);
    margin-top: 0;
    margin-bottom: 0;
    }

    .tooltip[x-placement^="right"] {
    margin-left: 5px;
    }

    .tooltip[x-placement^="right"] .tooltip-arrow {
    border-width: 5px 5px 5px 0;
    border-left-color: transparent !important;
    border-top-color: transparent !important;
    border-bottom-color: transparent !important;
    left: -5px;
    top: calc(50% - 5px);
    margin-left: 0;
    margin-right: 0;
    }

    .tooltip[x-placement^="left"] {
    margin-right: 5px;
    }

    .tooltip[x-placement^="left"] .tooltip-arrow {
    border-width: 5px 0 5px 5px;
    border-top-color: transparent !important;
    border-right-color: transparent !important;
    border-bottom-color: transparent !important;
    right: -5px;
    top: calc(50% - 5px);
    margin-left: 0;
    margin-right: 0;
    }

    .tooltip[aria-hidden='true'] {
    visibility: hidden;
    opacity: 0;
    transition: opacity .15s, visibility .15s;
    }

    .tooltip[aria-hidden='false'] {
    visibility: visible;
    opacity: 1;
    transition: opacity .15s;
    }
</style>

