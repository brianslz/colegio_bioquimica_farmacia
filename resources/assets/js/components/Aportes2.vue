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
                            <div v-if="arrayAfiliado[0]" class="form-group border row">
                                <div class="col-md-offset-2 col-md-9">
                                    <p><strong>Afiliado: </strong> {{ arrayAfiliado[0].apellido_paterno}} {{arrayAfiliado[0].apellido_materno}} {{arrayAfiliado[0].nombres }} </p> 
                                    <p> <strong>C.I: </strong>{{arrayAfiliado[0].ci}} <strong> &nbsp; Carnet Colegio </strong> {{arrayAfiliado[0].codigounico}}  </p>
                                </div>
                                <div class="col-md-offset-2 col-md-9">
                                <p><strong>Modalidad de ingreso: </strong> {{ arrayAfiliado[0].modalidad }}  &nbsp; <strong>Fecha de ingreso: </strong>{{ desde(arrayAfiliado[0].fecha_modalidad) }}</p>
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
                arrayAfiliado:[],
                arrayAportes:[],
            }
        },

        methods:{

        listarAportes(){
            let me=this;
            var url= 'aporte/getAportesUsuario?id='+this.afiliado_id;
            axios.get(url).then(function (response) {
                me.arrayAfiliado=response.data.afiliado;
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
                var respuesta= response.data;
                me.fecha_ultimo_pago = respuesta.pago.fecha_vencimiento;
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


