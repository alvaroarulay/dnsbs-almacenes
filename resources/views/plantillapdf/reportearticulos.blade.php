<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Ejemplo Header Fijo</title>
    <style>
        body {
            margin: 0;
            padding-top: 4cm; /* Altura del header para evitar que el main lo tape */
            font-family: Arial, sans-serif;
			letter-spacing: 1px;
        }

        header {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
			height: 3.2cm; /* Altura del header */
			z-index: 1000; /* Asegura que el header esté por encima del contenido */
			
        }

        .logo {
            float: left;
            width: 4cm;
            text-align: center;
        }

        .titulo {
            float: left;
            width: 9.59cm;
            text-align: center;
        }

        .info-derecha {
            float: left;
            width: 4cm;
            text-align: right;
            font-size: 12px;
        }

        .clearfix::after {
            content: "";
            clear: both;
            display: table;
        }

        main {
            padding: 20px;
        }
		#customers {
		border-collapse: collapse;
        margin-top: 0.2cm;
		font-size: 6pt;
		}
		#customers td {
			padding: 5px;
			border-top: 1px solid #ddd;
		}
		#customers th{
			text-align: left;
			background-color: #ddd;
			color: black;
		}
		#customers tfoot tr{
			border: 1px solid black;
		}
    </style>
</head>
<body>


   <main>
         <table id="cabecera">
			<tr style=" height: .5cm;">
				<td >
					<p style="font-size: xx-small; margin: 0.1cm; text-align: right;"><b>ALMACEN:</b></p>
				</td>
				<td style=" height: .5cm; width:7cm;">
					<p style="font-size: xx-small; margin: 0.1cm;">{{$almacen}}</p>
				</td>
			</tr>
			<tr style=" height: .5cm;">
				<td >
				<p style="font-size: xx-small; margin: 0.1cm; text-align: right;"><b>ESTABLECIMIENTO:</b></p>
				</td>
				<td >
				<p style="font-size: xx-small; margin: 0.1cm;">{{$establecimiento}}</p>
				</td>
			</tr>
        </table>
        <table id="customers">
            <thead>
                <tr style="border: 1px solid ;">
                    <th style="width: 1cm;">NRO.</th>
                    <th style="width: 2cm;">CÓDIGO ANTERIOR</th>
                    <th style="width: 2cm;">CÓDIGO</th>
					<th style="width: 8cm;">DESCRIPCIÓN</th>
					<th style="width: 3cm;">UNIDAD</th>
					<th style="width: 5cm;">PARTIDA</th>
				  	<th style="width: 4cm;">ALMACEN</th>
                </tr>
            </thead>
            <tbody>
            @foreach ($datos as $dato)
                    <tr>
                        <td>{{$loop->iteration}}</td>
                        <td>{{$dato->cod_anterior}}</td>
                        <td>{{$dato->codigo}}</td>
						<td>{{$dato->descripcion}}</td>
						<td>{{$dato->unidad}}</td>	
						<td>{{$dato->partida}}</td>
						<td>{{$dato->almacen}}</td>
                    </tr>
            @endforeach
            </tbody>
            <tfoot>
				<tr style="border: 1px solid;" >
					<th style="font-size: xx-small; text-align: right;" colspan="7" ><b>Cantidad: </b>{{$datos->count()}}</th>
				</tr>
			</tfoot>
        </table>
    </main>

</body>
</html>
