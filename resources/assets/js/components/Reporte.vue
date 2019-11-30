<template>
        <main class="main">
            <!-- Breadcrumb -->
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/">Escritorio</a></li>
                <li class="breadcrumb-item"><a href="#">Reportes</a></li>
            </ol>
            <div class="container-fluid">
                <!-- Ejemplo de tabla Listado -->
                <div class="card">
                    <div class="card-header">
                        <i class="fa fa-align-justify"></i> Generar Reporte
                    </div>
                    <div class="card-body">
                            <div class="row">

                                <p class="col-md-2">Tipo de reporte</p>
                                <select class="col-md-3 form-control" v-model="tipo_reporte" v-on:click="gestion=0 ;mostrarReporte=false">

                                    <option value="tipo_1">Afiliados sin deudas</option>
                                    <option value="tipo_2">Afiliados con deudas</option>
                                    <option value="tipo_3">Afiliados con 25 años de antiguedad</option>
                                    <option value="tipo_4">Afiliados con 50 años de antiguedad</option>

                                </select>
                                <p class="col-md-2" v-if="tipo_reporte=='tipo_1' || tipo_reporte=='tipo_2'">Fecha</p>
                                <input type="date" class="form-control col-md-3" v-if="tipo_reporte=='tipo_1' || tipo_reporte=='tipo_2'" v-model="gestion">

                                <p class="col-md-1" v-if="tipo_reporte=='tipo_3' || tipo_reporte=='tipo_4'">Gestion Actual</p>

                                <select class="col-md-3 form-control" v-model="gestion" v-if="tipo_reporte=='tipo_3' || tipo_reporte=='tipo_4'">
                                    <option value="2017">2017</option>
                                    <option value="2018">2018</option>
                                    <option value="2019">2019</option>
                                    <option value="2020">2020</option>
                                    <option value="2021">2021</option>
                                    <option value="2022">2022</option>
                                    <option value="2023">2023</option>
                                    <option value="2024">2024</option>
                                    <option value="2025">2025</option>
                                    <option value="2026">2026</option>
                                    <option value="2027">2027</option>
                                    <option value="2028">2028</option>
                                    <option value="2029">2029</option>
                                    <option value="2030">2030</option>
                                </select> 
                               
                            </div>
                            <br>
                            <div class="alert alert-info" role="alert" v-if="tipo_reporte && gestion">
                               <p v-if="tipo_reporte=='tipo_1'">Se generara un reporte con afiliados que <strong>SI pagaron</strong> sus aportes hasta la fecha <strong> {{desde(gestion)}}</strong></p>
                               <p v-if="tipo_reporte=='tipo_2'">Se generara un reporte con afiliados que <strong>NO pagaron</strong> sus aportes hasta la fecha <strong> {{desde(gestion)}}</strong></p>
                               <p v-if="tipo_reporte=='tipo_3'">Se generara un reporte con afiliados que tengan <strong> Antiguedad de 25 años </strong> pertenecientes ala gestión  <strong> {{gestion*1 - 25 }}</strong></p>
                               <p v-if="tipo_reporte=='tipo_4'">Se generara un reporte con afiliados que tengan <strong> Antiguedad de 50 años </strong> pertenecientes ala gestión  <strong> {{gestion*1 - 50 }}</strong></p>
                            
                            <button type="button"  class="col-md-2 btn btn-primary" v-if="!tipo_reporte==''" @click="listarNoDeudores()"> Generar Reporte </button>
                            </div>
                    </div>


                            <div class="table-responsive"  v-if="mostrarReporte">
<div class="text-center">
                                 <button type="button"  class="btn btn-danger" @click="descargarjspdf()"> <i class="fa fa-file-pdf-o" aria-hidden="true"></i> <br> Descargar Reporte </button> <br>
                                 <br>
                                 </div>
                            <table id="my-table" class="table table-bordered table-striped table-sm">
                                <thead>
                                    <tr>
                               <th  colspan="5" v-if="tipo_reporte=='tipo_1'">Afiliados que <strong>SI pagaron</strong> sus aportes hasta la fecha <strong> {{desde(gestion)}}</strong></th>
                               <th  colspan="5" v-if="tipo_reporte=='tipo_2'">Afiliados que <strong>NO pagaron</strong> sus aportes hasta la fecha <strong> {{desde(gestion)}}</strong></th>
                               <th  colspan="5" v-if="tipo_reporte=='tipo_3'">Afiliados que tienen <strong> Antiguedad de 25 años </strong> pertenecientes ala gestión  <strong> {{gestion*1 - 25 }}</strong></th>
                               <th  colspan="5" v-if="tipo_reporte=='tipo_4'">Afiliados que tienen <strong> Antiguedad de 50 años </strong> pertenecientes ala gestión  <strong> {{gestion*1 - 50 }}</strong></th>

                                    </tr>
<tr>
                                        <th colspan="5"> Total de afiliados : {{ arrayAfiliado.length }} </th>
                                    </tr>
                                    <tr>
                                        <th colspan="5"> Fecha de reporte : {{ desde(fechaActual)}} </th>
                                    </tr>
                                    <tr>
                                        <th>Apellido paterno</th>
                                        <th>Apellido materno</th>
                                        <th>Nombres</th>
                                        <th>Ci</th>
                                        <th v-if="tipo_reporte=='tipo_1' || tipo_reporte=='tipo_2'">Ultimo Aporte</th>
                                        <th v-if="tipo_reporte=='tipo_3' || tipo_reporte=='tipo_4'">Fecha de Ingreso</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    
                                    <tr v-for="afiliado in arrayAfiliado" :key="afiliado.id">
                                        <!--mostrar datos-->
                                        <td v-text="afiliado.apellido_paterno"></td>
                                        <td v-text="afiliado.apellido_materno"></td>
                                        <td v-text="afiliado.nombres"></td>
                                        <td v-text="afiliado.ci"></td>
                                        <td v-if="tipo_reporte=='tipo_1' || tipo_reporte=='tipo_2'" v-text="desde(afiliado.fecha_vencimiento)"></td>
                                        <td v-if="tipo_reporte=='tipo_3' || tipo_reporte=='tipo_4'" v-text="desde(afiliado.fecha_modalidad)"></td>
                                        <!--mostrar si esta activo o desactivado-->
                                    </tr>                                
                                </tbody>
                            </table>
                            </div><!--fin table responsive-->


                </div>
            </div>

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
                            <div v-if="tipoAccion==1" class="form-group row">
                                <label class="form-control-label col-md-6 " for="text-input"> Introdicir Gestión </label>
                                <input type="number" class="form-control col-md-6" v-model="gestion">
                            </div>

                            <div v-if="tipoAccion==2 " class="form-group row">
                                <label class="form-control-label col-md-6 " for="text-input"> Introdicir Fecha </label>
                                <input type="date" class="form-control col-md-6" v-model="fecha">
                            </div>

                            <div v-if="tipoAccion==3" class="form-group row">
                                <label class="form-control-label col-md-6 " for="text-input"> Introdicir Fecha </label>
                                <input type="date" class="form-control col-md-6" v-model="fecha">
                            </div>

                            <div v-if="tipoAccion==4" class="form-group row">
                                <label class="form-control-label col-md-6 " for="text-input"> Introdicir Gestión </label>
                                <input type="number" class="form-control col-md-6" v-model="gestion">
                            </div>

                            <div v-if="tipoAccion==5" class="form-group row">
                                <label class="form-control-label col-md-6 " for="text-input"> Introdicir Gestión </label>
                                <input type="number" class="form-control col-md-6" v-model="gestion">
                            </div>
                        </div>

                        <div class="modal-footer">
                            <button type="button" @click="cerrarModal()" class="btn btn-secondary">Cerrar</button>
                            <button type="button" v-if="tipoAccion==1" class="btn btn-success" @click="informeGestionPdf()"> <i class="fa fa-file-pdf-o" aria-hidden="true"></i> Generar reporte </button>
                            <button type="button" v-if="tipoAccion==2" class="btn btn-success" @click="informeDeudoresPdf()"> <i class="fa fa-file-pdf-o" aria-hidden="true"></i> Generar reporte </button>
                            <button type="button" v-if="tipoAccion==3" class="btn btn-success" @click="informeNoDeudoresPdf()"> <i class="fa fa-file-pdf-o" aria-hidden="true"></i> Generar reporte </button>
                            <button type="button" v-if="tipoAccion==4" class="btn btn-success" @click="informe25Pdf()"> <i class="fa fa-file-pdf-o" aria-hidden="true"></i> Generar reporte </button>
                            <button type="button" v-if="tipoAccion==5" class="btn btn-success" @click="informe50Pdf()"> <i class="fa fa-file-pdf-o" aria-hidden="true"></i> Generar reporte </button>
                            
                        </div>
                    </div>
                    <!-- /.modal-content -->
                </div>
                <!-- /.modal-dialog -->
            </div>

        </main>
</template>

<script>
import moment from 'moment';

import jsPDF from 'jspdf';
import 'jspdf-autotable';
moment.locale('es');
    export default {
        data(){
            return {
                afiliado_id:0,
                arrayAfiliado:[],
                arrayTitulos:[],
                listado : 1,
                tipo_reporte:'',
                gestion : 0,
                fecha : '',
                mostrarReporte : false , 

                gestionActual : '',
                fechaActual : '',

                modal : 0,
                tituloModal : '',
                errorAfiliado : '',
                errorMostrarMsjAfiliado : [],
                tipoAccion : '',
            }
        },
        methods:{ 
            descargarjspdf(){
                var pdf = new jsPDF();
                pdf.autoTable({html: '#my-table'});
                pdf.save('Reporte Afiliados'+this.gestion+'.pdf');
            },

            listarTitulos (id){
                    let me=this;
                    var url= 'afiliado/perfil?id='+id;
                    axios.get(url).then(function (response) {
                        me.arrayAfiliado=response.data.afiliado;
                        me.arrayTitulos=response.data.titulos;
                    })
                    .catch(function (error) {
                        console.log(error);
                    });
            },

            listarNoDeudores(){
                
                /*let me=this;
                var url= 'afiliado/nodeudorespdf?gestion='+this.gestion;
                axios.get(url).then(function (response) {
                    //console.log(response.data);
                    me.arrayAfiliado=response.data;
                    me.mostrarReporte = true;
                })
                .catch(function (error) {
                    console.log(error);
                }); */

                let me=this;
                var url= 'afiliado/nodeudorespdf?gestion='+this.gestion+'&tipo_reporte='+ this.tipo_reporte;
                axios.get(url).then(function (response) {
                    //console.log(response.data);
                    me.arrayAfiliado=response.data;
                    me.mostrarReporte = true;
                })
                .catch(function (error) {
                    console.log(error);
                }); 
            },

            abrirModal(accion){
                switch(accion){
                    case 'gestion':
                    {
                        this.modal = 1;
                        this.tituloModal = 'Generar Informe, Afiliados Inscritos por Gestión';
                        this.gestion = this.gestionActual;
                        this.tipoAccion = 1;
                        break;
                    }

                    case 'deudores':
                    {
                        this.modal = 1;
                        this.tituloModal = 'Generar Informe de Afiliados Deudores';
                        this.fecha = this.fechaActual;
                        this.tipoAccion = 2;
                        break;
                    }

                    case 'pagaron':
                    {
                        this.modal = 1;
                        this.tituloModal = 'Generar Informe de Afiliados que Pagaron';
                        this.fecha = this.fechaActual;
                        this.tipoAccion = 3;
                        break;
                    }

                    case 'antiguedad25':
                    {
                        this.modal = 1;
                        this.tituloModal = 'Generar Informe de Afiliados con Antigüedad 25 años ';
                        this.gestion = this.gestionActual;
                        this.tipoAccion = 4;
                        break;
                    }

                    
                    case 'antiguedad50':
                    {
                        this.modal = 1;
                        this.tituloModal = 'Generar Informe de Afiliados con Antigüedad 50 años ';
                        this.gestion = this.gestionActual;
                        this.tipoAccion = 5;
                        break;
                    }


                }
            },
            cerrarModal(){
                this.afiliado_id=0;
                this.arrayAfiliado=[];
                this.arrayTitulos=[];
                this.listado = 1;
                this.gestion = 0;
                this.fecha = '';
                this.modal = 0;
                this.tituloModal = '';
                this.errorAfiliado = '';
                this.errorMostrarMsjAfiliado = [];
                this.tipoAccion = 0;
            },

            informeGestionPdf(){
                window.open('/reporte/gestion?gestion='+this.gestion,'_blank');
            },
            informeDeudoresPdf(){
                window.open('/reporte/deudores?fecha='+this.fecha,'_blank');
            },
            informeNoDeudoresPdf(){
                window.open('/reporte/nodeudores?fecha='+this.fecha,'_blank');
            },
            informe25Pdf(){
                window.open('/reporte/antiguedad25?gestion='+this.gestion,'_blank');
            },
            informe50Pdf(){
                window.open('/reporte/antiguedad50?gestion='+this.gestion,'_blank');
            },
            desde(date){
                return moment(date).format("D MMMM YYYY");
            }
        },
        mounted() {
            this.gestionActual = moment(moment.now()).format("YYYY"); //selecciona el año actual
            this.fechaActual =  moment(moment.now()).format("Y-MM-DD"); //selecciona el año actual
        },
        
    }
</script>

<style>    
    .modal-content{
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
        color: red !important;
        font-weight: bold;
    }
    @media (min-width: 600px) {
        .btnagregar{
            margin-top:2rem;
        }
    }
</style>
