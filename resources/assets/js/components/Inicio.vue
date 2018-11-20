<template>
        <main class="main">
            <!-- Breadcrumb -->
            <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/">Inicio</a></li>
            </ol>
            <div class="container-fluid">
                <!-- Ejemplo de tabla Listado -->
                <div class="card">
                    <div class="card-header">
                        <i class="fa fa-align-justify"></i> Inicio
                    </div>
                    <div class="card-body">
                        <div id="carouselExampleSlidesOnly" class="carousel slide col-md-8 offset-md-2" data-ride="carousel">
                            <div class="carousel-inner" >
                                <div class="carousel-item active">

                                <img class="d-block w-100" :src="logo_src2" alt="First slide">
                                </div>
                                <div class="carousel-item">
                                <img class="d-block w-60" :src="logo_src" alt="Second slide">
                                </div>
                                <div class="carousel-item">
                                <img class="d-block w-100" :src="logo_src3" alt="Third slide">
                                </div>
                            </div>
                        </div>
                    
                        <h3 class="text-center">Colegio Departamental de Bioquímica y Farmacia</h3>
                        <h3 class="text-center">"La Paz"</h3>
                        <h5 class="text-center"> <strong> Telefono de contacto: </strong> 2-2423367 </h5>
                    </div>
                    <small>Sistema desarollado por: Rodrigo.B. Salazar.</small>
                    <small>Contacto: 78929196</small>
                    <small>Soluciones Informáticas</small>
                </div>
                <!-- Fin ejemplo de tabla Listado -->
            </div>
        </main>
</template>
<script>
    export default {
        data(){
            return {
            logo_src: 'img/logo_ps.png',
            logo_src2: 'img/slide1.jpg',
            logo_src3: 'img/slide2.jpg'
            }
        },       
    }
</script>