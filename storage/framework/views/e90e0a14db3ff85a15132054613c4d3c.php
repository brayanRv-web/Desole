<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Nuevo producto en DÉSOLÉ</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
</head>

<body style="margin:0; padding:0; background:#0d0d0d; font-family:'Poppins', Arial, sans-serif;">
    <div style="max-width:600px; margin:0 auto; padding:40px 20px;">

        <div style="width:100%; height:8px; background:#65cf72; border-radius:6px; margin-bottom:30px;"></div>

        <div style="background:#161616; padding:35px 25px; border-radius:16px; color:#f5f5f5;">
            
            <h1 style="color:#65cf72; margin-bottom:15px; font-size:26px; font-weight:600;">
                ¡Nuevo producto disponible!
            </h1>

            <p style="font-size:15px; line-height:1.7;">
                Hola <?php echo e($cliente->nombre); ?>,  
                tenemos un nuevo producto en el menú:  
                <strong style="color:#65cf72;"><?php echo e($producto->nombre); ?></strong>.
            </p>

            <p style="font-size:15px;">
                <?php echo e($producto->descripcion); ?>

            </p>

            <div style="margin: 20px 0; text-align:center;">
                <a href="<?php echo e(url('/producto/'.$producto->id)); ?>"
                   style="
                        background:#65cf72;
                        color:#0d0d0d;
                        padding:12px 28px;
                        border-radius:10px;
                        text-decoration:none;
                        font-weight:600;
                   ">Ver producto</a>
            </div>

            <p style="font-size:14px; color:#bbbbbb;">
                ¡Te esperamos para probarlo!
            </p>

            <p style="font-size:15px; color:#65cf72; font-weight:600; margin-top:25px;">
                — Equipo DÉSOLÉ Café
            </p>
        </div>

        <div style="width:100%; height:8px; background:#65cf72; border-radius:6px; margin-top:30px;"></div>
    </div>
</body>
</html>
<?php /**PATH C:\Users\josxp\Documents\desole\Desole\resources\views/emails/newproduct.blade.php ENDPATH**/ ?>