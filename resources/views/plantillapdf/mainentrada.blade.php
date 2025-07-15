<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Nota de Entrada</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
    <style>
        body {
            margin: 0;
            font-family: "Roboto", sans-serif;
            font-optical-sizing: auto;
            font-weight: 400;
            font-style: normal;
            font-variation-settings:"wdth" 100;
            letter-spacing: 0.5px;
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
            font-size: 8pt;
            
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
					<p style="font-size: x-small; margin: 0.1cm; text-align: right;"><b>ALMACEN:</b></p>
				</td>
				<td style=" height: .5cm; width:7cm;">
					<p style="font-size: x-small; margin: 0.1cm;">{{$almacen}}</p>
				</td>
			</tr>
			<tr style=" height: .5cm;">
				<td >
				<p style="font-size: x-small; margin: 0.1cm; text-align: right;"><b>ESTABLECIMIENTO:</b></p>
				</td>
				<td >
				<p style="font-size: x-small; margin: 0.1cm;">{{$establecimiento}}</p>
				</td>
			</tr>
        </table>
        <table id="customers">
            <thead>
                <tr style="border: 1px solid ;">
                    <th style="width: 1cm; text-align:center">NRO.</th>
                    <th style="width: 4cm; text-align:center">CÓDIGO</th>
                    <th style="width: 15cm; text-align:center">DESCRIPCIÓN</th>
					<th style="width: 2.5cm; text-align:center">CANTIDAD</th>
					<th style="width: 2.5cm; text-align:center">PRECIO UNITARIO</th>
					<th style="width: 2.5cm; text-align:center">TOTAL</th>
                </tr>
            </thead>
            <tbody>
            @foreach ($datos as $dato)
                    <tr>
                        <td style="text-align:center">{{$loop->iteration}}</td>
                        <td style="text-align:center">{{$dato->codigo}}</td>
                        <td>{{$dato->descripcion}}</td>
						<td style="text-align:right">{{$dato->cantidad}}</td>
						<td style="text-align:right">{{number_format($dato->precio_unitario, 2, ',', '.')}}</td>	
						<td style="text-align:right"><b>{{ number_format($dato->cantidad * $dato->precio_unitario, 2, ',', '.')}}</b></td>
                    </tr>
            @endforeach
            </tbody>
            <tfoot>
				<tr style="border: 1px solid;" >
					<th style="font-size: x-small; text-align: right;" colspan="6" >Cantidad: {{$datos->count()}}</th>
				</tr>
			</tfoot>
        </table>
    </main>

</body>
</html>
