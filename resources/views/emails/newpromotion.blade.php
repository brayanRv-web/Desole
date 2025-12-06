<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Nueva promoción en DÉSOLÉ</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
</head>

<body style="margin:0; padding:0; background:#0d0d0d; font-family:'Poppins', Arial, sans-serif;">

    <div style="max-width:600px; margin:0 auto; padding:40px 20px;">

        <div style="width:100%; height:8px; background:#65cf72; border-radius:6px; margin-bottom:30px;"></div>

        <div style="background:#161616; padding:35px 25px; border-radius:16px; color:#f5f5f5;">

            <h1 style="color:#65cf72; font-size:26px; font-weight:600; margin-bottom:15px;">
                ¡Nueva promoción disponible!
            </h1>

            <p style="font-size:15px; line-height:1.7;">
                Hola {{ $cliente->nombre }},  
                acabamos de lanzar una nueva promoción:
            </p>

            <h2 style="color:#65cf72; margin:15px 0 10px;">
                {{ $promo->titulo }}
            </h2>

            <p style="font-size:15px;">
                {{ $promo->descripcion }}
            </p>

            <div style="background:#0d0d0d; padding:18px; border-radius:12px; border:1px solid #222; margin:20px 0;">
                <p style="color:#bbbbbb; font-size:14px;">
                    Productos incluidos:
                    <br><br>
                    @foreach($promo->productos as $prod)
                        • {{ $prod->nombre }} <br>
                    @endforeach
                </p>
            </div>

            <div style="text-align:center;">
                <a href="{{ url('/promociones/'.$promo->id) }}"
                   style="
                        background:#65cf72;
                        color:#0d0d0d;
                        padding:12px 28px;
                        border-radius:10px;
                        text-decoration:none;
                        font-weight:600;
                   ">Ver promoción</a>
            </div>

            <p style="margin-top:25px; color:#65cf72; font-weight:600;">
                — Equipo DÉSOLÉ Café
            </p>
        </div>

        <div style="width:100%; height:8px; background:#65cf72; border-radius:6px; margin-top:30px;"></div>
    </div>

</body>
</html>
