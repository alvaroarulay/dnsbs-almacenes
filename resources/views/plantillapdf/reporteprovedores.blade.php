
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>
<head>
<style>
	@page {
		margin: 0cm 0cm;
	}
	body{
		font-family: 'arial', sans-serif;
		margin-top: 4.5cm;
		margin-left: 1cm;
		margin-right: 2cm;
		margin-bottom: 2cm;
		width: 18.5cm;
	}
	hr{
		margin: 0;
		border-top: 1px solid rgb(0, 0, 0);
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
	.eslogan{
		font-size: xx-small;
		font-style: italic;
	}
    #cabecera{
        height: 1cm;
        margin-top: 0.1cm;
        margin-bottom: 0.1;
        padding: 0;
    }
	#cabecera tr td{
		margin:0;
		padding:0;
	}
	header {
		position: fixed;
		top: 1cm;
		left: 1cm;
		right: 1cm;
		height: 5cm;
		}

		/** Definir las reglas del pie de página **/
	footer {
		position: fixed;
		bottom: 0cm;
		left: 1.5cm;
		right: 0cm;
		height: 2cm;
	}
	.page-break {
	    page-break-after: always;
	}
</style>
</head>
<body>
    <header>
        <div style="width: 17.59cm; height: 5cm;">
            <table>
                <tr>
                    <td>
                        <div style="width: 4cm; height: 3cm; text-align: center;">
                            <img src="{{ asset('img/sistema.png') }}" alt="" style="width: auto; height: 2cm; ">
                            <p style="margin: 0.1cm; text-align: center; font-size: x-small; padding: 0;" ><b>POLICIA BOLIVIANA</b></p>
                            <p style="font-size: 5pt; margin: 0; text-align: center; padding: 0;">COMANDO GENERAL</p>
                            <p style="font-size: 5pt; margin: 0; text-align: center; padding: 0;"><b>DIRECCIÓN NACIONAL DE SALUD</b></p>
                            <p style="font-size: 5pt; margin: 0; text-align: center; padding: 0;"><b>Y BIENESTAR SOCIAL</b></p>
                            <p style="font-size: 5pt; margin: 0; text-align: center; padding: 0;">{{$ciudad}} - BOLIVIA</p>
                        </div>
                    </td>
                    <td>
                        <div style="width: 9.59cm; height: 1.5cm;">
                            <p style="margin: 0.1cm; text-align: center; padding: 0" ><b>{{$titulo}}</b></p>
                            <p style="font-size: xx-small; margin: 0; text-align: center; padding: 0;">{{$fechaTitulo}}</p>
                        </div>
                    </td>
                    <td>
                        <div style="width: 3cm; height: 1cm; ">
                            <p style="font-size: xx-small; margin: 0.1cm; text-align: right;"><b>Página:</b></p>
                            <p style="font-size: xx-small; margin: 0.1cm; text-align: right;"><b>Fecha de Impresión:</b></p>
                            
                        </div>
                    </td>
                    <td>
                        <div style="width: 1cm; height: 1cm;">
                            <p style="font-size: xx-small; margin: 0.1cm;"><br></p>
                            <p style="font-size: xx-small; margin: 0.1cm;">{{$fechaDerecha}}</p>
                            
                        </div>
                    </td>
                </tr>
            </table>
	    </div>
        
    </header>
    <main>
         <hr>
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
                    <th style="width: 3cm;">NOMBRE</th>
                    <th style="width: 4cm;">DIRECCIÓN</th>
					<th style="width: 2cm;">TELÉFONO</th>
					<th style="width: 2cm;">NIT</th>
					<th style="width: 2cm;">E-MAIL</th>
				  	<th style="width: 2cm;">CONTACTO</th>
					<th style="width: 2cm;">OBSERVACIONES</th>
                </tr>
            </thead>
            <tbody>
            @foreach ($datos as $dato)
                    <tr>
                        <td>{{$loop->iteration}}</td>
                        <td>{{$dato->nompro}}</td>
                        <td>{{$dato->direccion}}</td>
						<td>{{$dato->telefono}}</td>
						<td>{{$dato->nit}}</td>	
						<td>{{$dato->email}}</td>
						<td>{{$dato->contacto}}</td>
						<td>{{$dato->observaciones}}</td>
                    </tr>
            @endforeach
            </tbody>
            <tfoot>
				<tr style="border: 1px solid;" >
					<th style="font-size: xx-small; text-align: right;" colspan="8" ><b>Cantidad: </b>{{$datos->count()}}</th>
				</tr>
			</tfoot>
        </table>
    </main>
<footer>
    <div style="width: 17.59cm; height: 2cm; text-align: center;">
        <p style="font-size: xx-small; margin: 0.1cm;">Sistema de Almacenes - Policía Boliviana</p>
        <p class="eslogan">"La seguridad es nuestra misión"</p>
    </div>
</footer>
<script type="text/php">
		if ( isset($pdf) ) {
			$pdf->page_script('
				$font = $fontMetrics->get_font("Arial, Helvetica, sans-serif", "NORMAL");
				$pdf->text(505, 58, "$PAGE_NUM de $PAGE_COUNT", $font, 7);
			');
		}
</script>
</body>
</html>