<!DOCTYPE html>
<html lang="en">
<head>

    <title>Afiliados Al Día</title>
    <style>
        body {
            margin: 0;
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
            font-size: 0.675rem;
            font-weight: normal;
            line-height: 1.5;
            color: #151b1e;           
        }
        table {
            border-collapse: collapse;
            width: 60%;
        }

        th, td {
            text-align: left;
            padding: 8px;
        }

        tr:nth-child(even){background-color: #f2f2f2}

        th {
            background-color: #4CAF50;
            color: white;
        }
    </style>
    
</head>
<body>
    <div>
        <h3>Reporte de Afiliados que estan al día con sus aportes en fecha {{ $fecha }}</h3>
    </div>
    <strong>Total de Afiliados : {{count($afiliados)}} </strong><br>
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>C.I.</th>
                <th>Paterno</th>
                <th>Materno</th>
                <th>Nombres</th>
                <!--
                <th>Tipo de Ingreso</th>
                <th>Fecha de Ingreso</th>
                -->
                <th>Codigo Colegio</th>
                <th>Vigente hasta</th>
            </tr>
        </thead>
        <tbody>
        <?php $c = 0  ?>
        @foreach ($afiliados as $a)
        
            <tr>
                <td nowrap> {{$c = $c + 1 }} </td>
                <td nowrap> {{$a->ci}} </td>
                <td nowrap> {{$a->apellido_paterno}} </td>
                <td nowrap> {{$a->apellido_materno }}</td>
                <td nowrap> {{$a->nombres}} </td>
                <!--
                <td nowrap> {{$a->modalidad}} </td>
                <td nowrap> {{$a->fecha_modalidad}} </td>
                -->
                <td nowrap> {{$a->codigounico}} </td>
                <td nowrap> {{$a->fv}} </td>
            </tr>
        @endforeach
        </tbody>
    </table>
    </table>
</body>
</html>



