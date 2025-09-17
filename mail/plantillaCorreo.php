<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>

    <style>
        p{
            text-align: justify;
        }

        h1{
            text-align: center;
        }
        .content-att p{
            margin: 0;
            padding: 0;
        }
    </style>

</head>
<body>

    <h1>Solicitud de informacion</h1>
    <br>
    <p><?php echo $datosPlantilla['mensaje']; ?></p>
    <br>
    <div class="content-att">
        <p><strong>Datos</strong></p>
        <p><strong>Nombre: </strong><?php echo $datosPlantilla['nombre']; ?></p>
        <p><strong>Telefono: </strong><?php echo $datosPlantilla['telefono']; ?></p>
        <p><strong>Email: </strong><?php echo $datosPlantilla['email']; ?></p>
    </div>
    

</body>
</html>