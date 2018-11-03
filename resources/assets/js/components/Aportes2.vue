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
                    <template>
                        <div class="card-body">

<!-- primera parte -->

                        <div class="row">
                                    <div class="col-md-8">
                                        <h5>Datos de Afiliación</h5>
                                        <div class="border">
                                            

                                            <strong>Afiliado:</strong> {{ afiliado.apellido_paterno }}&nbsp;{{ afiliado.apellido_materno }} &nbsp; {{afiliado.nombres}} <br><br>
                                            <strong>C.I:</strong>{{afiliado.ci}} <strong> &nbsp; Carnet Colegio: </strong> {{afiliado.codigounico}}  <br><br>
                                            <strong>Modalidad de Ingreso: </strong> {{ afiliado.modalidad }}  <br><br>
                                            <strong>Fecha de ingreso: </strong>{{ desde(afiliado.fecha_modalidad) }}
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <h5>Ultimo Aporte</h5>
                                    <div class="border">
                                        <strong>Ultimo Pago : </strong>{{ desde(fecha_ultimo_pago) }} <br><br>

                                        <button v-if="actualDeuda(fecha_ultimo_pago)" type="button" class="btn btn-success">
                                            <i class="icon-user"></i> Sin Deudas
                                        </button>

                                        <button v-if="actualDeuda(fecha_ultimo_pago) && actualPromo(fecha_ultimo_pago)" @click="pagarGestion()" v-tooltip.bottom="'Valido Hasta el 31 de Marzo'" type="button" class="btn btn-primary">
                                            <i class="icon-user"></i> Pagar por una Gestion
                                        </button>

                                        <button v-if="!actualDeuda(fecha_ultimo_pago)" type="button" class="btn btn-danger">
                                            <i class="icon-user"></i> Deudor
                                        </button>
                                    <br><br>
                                        <template v-if="calcularDeuda(fecha_ultimo_pago)>2">
                                           <strong>Meses a Deber: </strong> {{calcularDeuda(fecha_ultimo_pago)}} Meses <br>
                                           <strong>Costo a Pagar: </strong> {{calcularDeuda(fecha_ultimo_pago)*20}} Bs
                                        </template>
                                    </div>
                                    </div>
                        </div> <br>

<!-- segunda parte -->
                            <h5>Registro de Aportes</h5>
                            <div v-for="aporte in arrayAportes" :key="aporte.id" >
                                <table class="table table-bordered table-sm">
                                    <tr>
                                        <th>ingreso como : </th><td>{{ aporte.modalidad }}</td> <th>Comprobante: </th><td>{{aporte.codigo_verficacion}} </td> <th>Fecha de Pago: </th><td>{{ desde(aporte.fecha_pago) }}</td> 
                                    </tr>
                                    <tr>
                                        <th>Numero de Meses: </th><td>{{aporte.num_meses}} </td> <th>Monto: </th><td>{{aporte.monto}}</td>  <th>Vale hasta: </th><td>{{ desde(aporte.fecha_vencimiento) }} </td> 
                                    </tr>
                                </table>
                            </div>
                            <div class="form-group row">
                                <div class="col-md-6">
                                    <button type="button" @click="reporteAportes()" class="btn btn-success"><i class="icon-printer"></i> Imprimir </button>
                                </div>
                            </div>
                        </div><!-- FIN Formulario-->
                    </template>


                </div>
                <!-- Fin ejemplo de tabla Listado -->
            </div>
        </main>
</template>

<script>
import moment,{ months } from 'moment';
moment.locale('es');
    export default {
        data(){
            return {
                afiliado_id:0,
                fecha_ultimo_pago:0,
                afiliado: [],
                arrayAportes:[],
            }
        },

        methods:{
            listarAportes(){
                let me=this;
                var url= 'aporte/getAportesUsuario?id='+this.afiliado_id;
                axios.get(url).then(function (response) {
                    me.afiliado=response.data.afiliado;
                    me.arrayAportes=response.data.pagos;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            cargarUltimoPago(){
                let me = this;
                var url= '/Aporte/ultimoPago?id=' + this.afiliado_id ;
                //listamos afiliados segun esta ruta
                axios.get(url).then(function (response) {
                    console.log (response.data.fecha_vencimiento);
                    me.fecha_ultimo_pago = response.data.fecha_vencimiento;
                })
                .catch(function (error) {
                    //console.log(error);
                    //si el afiliado es nuevo ... 
                    me.fecha_ultimo_pago = me.fecha_modalidad;
                });
            },
            reporteAportes(){
                window.open('http://127.0.0.1:8000/pago/aportesPdf?id='+this.afiliado_id,'_blank');
            },

            desde(date){
                return moment(date).format("D MMMM YYYY");
            },

            actualDeuda(date){
                //console.log(moment().isBefore(date)); falso si debe, true No debe 
                return moment().isBefore(date);
            },

            calcularDeuda(date){
                // siempre a >b
                var a = moment();
                var b = moment(date);
                
                return ( a.diff(b, 'months')+1 );

            },


            getFechaVencimiento(date,n){
                return moment(date).add(n,'months').format("D MMMM YYYY");
            }
        },
        mounted() {
            this.afiliado_id = this.mensaje;//asignamos el iddonante
            this.listarAportes(this.afiliado_id);
            this.cargarUltimoPago();
        },
        props: ['mensaje'] //como mensaje nos viene el id
    }
</script>


