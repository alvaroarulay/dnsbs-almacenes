<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reporte de Compras</title>
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
                    <th style="width: 8cm;">DESCRIPCIÓN</th>
					<th style="width: 1.5cm;">CANTIDAD</th>
					<th style="width: 1.5cm;">PRECIO UNITARIO</th>
					<th style="width: 1.5cm;">CANTIDAD RESTANTE</th>
                    <th style="width: 4.5cm;">PROVEEDOR</th>
                    <th style="width: 4.5cm;">PERSONAL</th>
				  	<th style="width: 3cm;">FECHA</th>
                </tr>
            </thead>
            <tbody>
            @foreach ($datos as $dato)
                    <tr>
                        <td>{{$loop->iteration}}</td>
                        <td>{{$dato->codigo}}</td>
                        <td>{{$dato->descripcion}}</td>
						<td>{{$dato->cantidad}}</td>
						<td>{{$dato->precio_unitario}}</td>	
						<td>{{$dato->restante}}</td>
                        <td>{{$dato->proveedor}}</td>
                        <td>{{$dato->personal}}</td>
						<td>{{$dato->fecha}}</td>
                    </tr>
            @endforeach
            </tbody>
            <tfoot>
				<tr style="border: 1px solid;" >
					<th style="font-size: xx-small; text-align: right;" colspan="9" ><b>Cantidad: </b>{{$datos->count()}}</th>
				</tr>
			</tfoot>
        </table>
    </main>

</body>
</html>
