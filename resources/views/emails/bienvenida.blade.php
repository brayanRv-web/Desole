<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Bienvenido a DÉSOLÉ</title>

    <!-- Poppins (Gmail la respeta) -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">

</head>

<body style="margin:0; padding:0; background:#0d0d0d; font-family:'Poppins', Arial, sans-serif;">

    <!-- Contenedor general -->
    <div style="max-width:600px; margin:0 auto; padding:40px 20px;">

        <!-- Caja superior -->
        <div style="width:100%; height:8px; background:#65cf72; border-radius:6px; margin-bottom:30px;"></div>

        <!-- Card principal -->
        <div style="background:#161616; padding:35px 25px; border-radius:16px; color:#f5f5f5;">

            <h1 style="margin:0 0 15px; color:#65cf72; font-size:28px; font-weight:600;">
                👋 ¡Hola {{ $cliente->nombre }}!
            </h1>

            <p style="font-size:15px; line-height:1.7;">
                Bienvenido a <strong>DÉSOLÉ</strong>.  
                Gracias por unirte a nuestra comunidad — ahora eres parte de algo delicioso.
            </p>

            <!-- Panel interior -->
            <div style="background:#0d0d0d; padding:18px; border-radius:12px; border:1px solid #222; margin-bottom:25px;">
                <p style="color:#bbbbbb; margin:0; font-size:14px; line-height:1.6;">
                    En DÉSOLÉ comenzarás a recibir:
                    <br><br>
                    • ☕ Promociones exclusivas<br>
                    • 🎁 Recompensas únicas<br>
                    • 📰 Noticias y nuevos productos<br>
                </p>
            </div>

            <!-- Botón -->
            <div style="text-align:center; margin-bottom:30px;">
                <a href="{{ url('/') }}"
                   style="
                        background:#65cf72;
                        color:#0d0d0d;
                        padding:12px 26px;
                        font-size:16px;
                        font-weight:600;
                        border-radius:10px;
                        text-decoration:none;
                        display:inline-block;
                   ">
                   Visitar Desolé
                </a>
            </div>

            <p style="color:#bbbbbb; font-size:14px;">
                Si necesitas ayuda, contáctanos en  
                <br><strong style="color:#65cf72;">desole.cafeteria@gmail.com</strong>
            </p>

            <p style="margin-top:25px; font-size:15px;">
                Gracias por confiar en nosotros.  
                ¡Te esperamos en tu próxima visita!
            </p>

            <p style="color:#65cf72; font-weight:600; margin-top:20px;">
                — El equipo de DÉSOLÉ Café
            </p>

        </div>

        <!-- Caja inferior -->
        <div style="width:100%; height:8px; background:#65cf72; border-radius:6px; margin-top:30px;"></div>
    </div>

</body>
</html>
