<template>
        <div>
            <div class="card-body">
                <div v-if="arrayAfiliado[0]" class="table-responsive">
                    <h4> <a href="#"><i class="icon-user" disabled></i></a> Datos Personales</h4>
                    <table class="table table-bordered table-striped">
                        <tr>
                            <th>Apellido Paterno</th>
                            <td v-text="arrayAfiliado[0].apellido_paterno"></td>
                            <th>Apellido Materno</th>
                            <td v-text="arrayAfiliado[0].apellido_materno ">&nbsp;</td>
                        </tr>
                        <tr>
                            <th>Nombres</th>
                            <td v-text="arrayAfiliado[0].nombres ">&nbsp;</td>
                            <th>Fecha de Nacimiento</th>
                            <td v-text="desde(arrayAfiliado[0].fecha_nac)">&nbsp;</td>
                        </tr>
                        <tr>
                            <th>Lugar de Nacimiento</th>
                            <td v-text="arrayAfiliado[0].lugar_nac ">&nbsp;</td>
                            <th>Estado Civil</th>
                            <td v-text="arrayAfiliado[0].estado_civil ">&nbsp;</td>
                        </tr>
                        <tr>
                            <th>C.I</th>
                            <td v-text="arrayAfiliado[0].ci ">&nbsp;</td>
                            <th>Expedido</th>
                            <td v-text="arrayAfiliado[0].departamento ">&nbsp;</td>
                        </tr>
                        <tr>
                            <th>Nacionalidad</th>
                            <td v-text="arrayAfiliado[0].nacionalidad ">&nbsp;</td>
                            <th>Telefono</th>
                            <td v-text="arrayAfiliado[0].telefono ">&nbsp;</td>
                        </tr>
                        <tr>
                            <th>Celular</th>
                            <td v-text="arrayAfiliado[0].celular ">&nbsp;</td>
                            <th>E-mail</th>
                            <td v-text="arrayAfiliado[0].email ">&nbsp;</td>
                        </tr>
                        <tr>
                            <th>Fecha, Registro Min. Salud y Deportes</th>
                            <td v-text="desde(arrayAfiliado[0].fecha_min_salud) ">&nbsp;</td>
                            <th>Numero de Registro Matricula</th>
                            <td v-text="arrayAfiliado[0].matricula ">&nbsp;</td>
                        </tr>
                        <tr>
                            <th>Lugar de Trabajo</th>
                            <td v-text="arrayAfiliado[0].lugar_trabajo ">&nbsp;</td>
                            <th>Dirección</th>
                            <td v-text="arrayAfiliado[0].direccion_trabajo ">&nbsp;</td>
                        </tr>
                        <tr>
                            <th>Modalidad de Ingreso</th>
                            <td v-text="arrayAfiliado[0].modalidad ">&nbsp;</td>
                            <th>Fecha de Inicio de Pago</th>
                            <td v-text="desde(arrayAfiliado[0].fecha_modalidad) ">&nbsp;</td>
                        </tr>
                        <tr>
                            <th>Fecha de Ingreso </th>
                            <td>{{desde(arrayAfiliado[0].created_at)}}</td>
                            <th>Carnet Colegio</th>
                            <td v-text="arrayAfiliado[0].codigounico ">&nbsp;</td>
                        </tr>
                        <tr>
                            <th>Observaciones</th>
                            <td colspan="3" v-text="arrayAfiliado[0].observaciones">
                            </td>
                        </tr>
                    </table>
                </div>
                <br>
                <h4><a href="#"><i class="icon-graduation" disabled></i></a> Datos Academicos</h4>
                <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>Universidad</th>
                                <th>Titulo / especialidad</th>
                                <th>Grado Academico</th>
                                <th>Fecha</th>
                                <th>Ciudad</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="titulo in arrayTitulos" :key="titulo.id">
                                <td v-text="titulo.universidad"></td>
                                <td v-text="titulo.nombre_titulo"></td>
                                <td v-text="titulo.grado"></td>
                                <td v-text="desde(titulo.fecha_titulo)"></td>
                                <td v-text="titulo.ciudad"></td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <br>
                <h4><a href="#"><i class="icon-clock" disabled></i></a> Historial de Afiliado</h4>
                <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>Tipo de Estado</th>
                                <th>Fecha Inicio</th>
                                <th>Fecha Finalización</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="seguimiento in arraySeguimiento" :key="seguimiento.id_seg">
                                <!--<td v-text="desde(titulo.fecha_titulo)"></td>-->
                                <td v-text="seguimiento.tipo_estado"></td>
                                <td v-text="desde(seguimiento.fecha_inicio)"></td>
                                <td>
                                    <template v-if="seguimiento.fecha_fin">
                                        {{ desde(seguimiento.fecha_fin) }}
                                    </template>
                                    <template v-else>
                                        Actualiad
                                    </template>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <button type="button" @click="cargarPdf()" class="btn btn-success btn-sm">
                    <i class="icon-printer"></i> Imprimir Perfil
                </button> &nbsp;
            </div>
        </div>
</template>
<script>
import moment from 'moment';
moment.locale('es');
    export default {
        data(){
            return {
                afiliado_id:0,
                arrayAfiliado:[],
                arrayTitulos:[],
                arraySeguimiento:[]
            }
        },
        methods:{
            listarTitulos (id){
                    let me=this;
                    var url= 'afiliado/perfil?id='+id;
                    axios.get(url).then(function (response) {
                        me.arrayAfiliado=response.data.afiliado;
                        me.arrayTitulos=response.data.titulos;
                        me.arraySeguimiento = response.data.seguimiento;
                    })
                    .catch(function (error) {
                        console.log(error);
                    });
            },
            cargarPdf(){
                window.open('/afiliado/perfilPdf?id='+this.afiliado_id,'_blank');
            },
            desde(date){
                return moment(date).format("D MMMM YYYY");
            }
        },
        mounted() {
        this.afiliado_id = this.mensaje;//asignamos el iddonante
        this.listarTitulos(this.afiliado_id);
    },
        props: ['mensaje'] //como mensaje nos viene el id
    }
</script>



