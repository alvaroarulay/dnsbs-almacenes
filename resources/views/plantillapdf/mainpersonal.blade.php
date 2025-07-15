<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reporte de Personal</title>
    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
			letter-spacing: 1px;
        }
        #cabecera{
            height: 0.8cm;
            margin-top: 0.1cm;
            margin-bottom: 0cm;
            padding: 0;
        }
        #cabecera tr td{
            margin:0;
            padding:0;
        }
		#customers {
            border-collapse: collapse;
            margin-top: 0.2cm;
            font-size: 6pt;
		}
		#customers td {
			padding: 5px;
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
                    <th style="width: 2cm;">CÓDIGO</th>
                    <th style="width: 8cm;">NOMBRE</th>
					<th style="width: 2cm;">CARNET</th>
					<th style="width: 3cm;">TELEFONO</th>
					<th style="width: 5cm;">DIRECCIÓN</th>
				  	<th style="width: 4cm;">ALMACEN</th>
                </tr>
            </thead>
            <tbody>
            @foreach ($datos as $dato)
                    <tr>
                        <td>{{$loop->iteration}}</td>
                        <td>{{$dato->codper}}</td>
                        <td>{{$dato->nomper}}</td>
						<td>{{$dato->ciper}}</td>
						<td>{{$dato->telper}}</td>	
						<td>{{$dato->dirper}}</td>
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
