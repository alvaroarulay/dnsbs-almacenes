<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
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
        .header {
            width: 100%;
            display: flex;
            justify-content: space-between;
        }
    </style>
</head>
<body>
    <div class="header">
        <div style="height: 3.8cm;" class="clearfix">
            <table>
                <tr>
                    <td>
                        <div style="width: 6cm; height: 3.8cm; text-align: center;">
                            <img src="{{ asset('img/sistema.png') }}" alt="" style="width: auto; height: 2cm; ">
                            <p style="margin: 0.1cm; text-align: center; font-size: small; padding: 0;" ><b>POLICIA BOLIVIANA</b></p>
                            <p style="font-size: 8pt; margin: 0; text-align: center; padding: 0;">COMANDO GENERAL</p>
                            <p style="font-size: 8pt; margin: 0; text-align: center; padding: 0;"><b>DIRECCIÓN NACIONAL DE SALUD</b></p>
                            <p style="font-size: 8pt; margin: 0; text-align: center; padding: 0;"><b>Y BIENESTAR SOCIAL</b></p>
                            <p style="font-size: 8pt; margin: 0; text-align: center; padding: 0;">La Paz - Bolivia</p>
                        </div>
                    </td>
                    <td>
                        <div style="width: 10.59cm; height: 1.5cm;">
                            <p style="margin: 0.1cm; text-align: center; padding: 0; font-size: 15pt;" ><b>{{$titulo}}</b></p>
                            <p style="font-size: 8pt; margin: 0; text-align: center; padding: 0;">{{$fechaTitulo}}</p>
                        </div>
                    </td>
                    <td>
                        <div style="width: 4cm; height: 1cm; ">
                            <p style="font-size: 8pt; margin: 0.1cm; text-align: right;"><b>Impreso:</b></p>
                            
                        </div>
                    </td>
                    <td>
                        <div style="width: 1cm; height: 1cm;">
                            <p style="font-size: 8pt; margin: 0.1cm;">{{$fechaDerecha}}</p>
                            
                        </div>
                    </td>
                </tr>
            </table>
            <hr>
    </div>
    </div>
</body>
</html>