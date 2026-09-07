<?php
session_start();
$mensaje = isset($_GET['mensaje']) ? $_GET['mensaje'] : '';
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Plataforma experimental de apoyo al tamizaje auditivo temprano</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<style>
:root{
  --azul:#1565c0;
  --verde:#4db6ac;
  --gris:#f4f6f8;
  --oscuro:#263238;
}

*{
  box-sizing:border-box;
  font-family: "Segoe UI", Arial, sans-serif;
}

body{
  margin:0;
  background:var(--gris);
  color:#333;
}

/* ===== HEADER ===== */
header{
  background:linear-gradient(90deg,var(--azul),var(--verde));
  color:white;
  padding:18px 30px;
  display:flex;
  justify-content:space-between;
  align-items:center;
  flex-wrap:wrap;
}

header h1{
  font-size:20px;
  margin:0;
}

header span{
  font-size:13px;
  opacity:.9;
}

/* LOGIN */
.login-form input{
  padding:8px;
  border:none;
  border-radius:5px;
  margin-right:6px;
}

.login-form button{
  padding:8px 14px;
  border:none;
  border-radius:5px;
  background:#0d47a1;
  color:white;
  cursor:pointer;
}

.login-form button:hover{
  background:#08306b;
}

/* ===== NAV ===== */
nav{
  background:white;
  display:flex;
  justify-content:center;
  box-shadow:0 2px 6px rgba(0,0,0,.1);
}

nav a{
  padding:14px 22px;
  text-decoration:none;
  color:#333;
  font-weight:600;
}

nav a:hover{
  color:var(--azul);
}

/* ===== SECTIONS ===== */
section{
  padding:50px 8%;
  background:white;
  margin-bottom:25px;
}

section h2{
  color:var(--azul);
  margin-bottom:12px;
}

section p{
  line-height:1.6;
  font-size:15px;
}

.cards{
  display:grid;
  grid-template-columns:repeat(auto-fit,minmax(240px,1fr));
  gap:20px;
  margin-top:25px;
}

.card{
  background:#fafafa;
  padding:22px;
  border-radius:12px;
  box-shadow:0 4px 10px rgba(0,0,0,.08);
}

.card h3{
  margin-top:0;
  color:var(--oscuro);
}

/* ===== FOOTER ===== */
footer{
  background:#263238;
  color:white;
  text-align:center;
  padding:25px;
  font-size:13px;
}

/* ===== MENSAJE ERROR ===== */
.error{
  background:#fdecea;
  color:#b71c1c;
  padding:12px;
  text-align:center;
}
</style>
</head>

<body>

<header>
  <div>
    <h1>Plataforma experimental de apoyo al tamizaje auditivo temprano</h1>
    <span>Plataforma experimental de apoyo al tamizaje auditivo temprano</span>
  </div>

  <form method="POST" action="validar.php" class="login-form">
    <input type="text" name="usuario" placeholder="Usuario" required>
    <input type="password" name="password" placeholder="Contraseña" required>
    <button type="submit">Ingresar</button>
  </form>
</header>

<?php if($mensaje): ?>
<div class="error">⚠️ Error de autenticación. Verifique sus credenciales.</div>
<?php endif; ?>

<nav>
  <a href="#info">Información</a>
  <a href="#prototipo">Prototipo</a>
  <a href="#equipo">Quiénes Somos</a>
</nav>

<!-- ===== INFORMACIÓN ===== -->
<section id="info">
  <h2>¿Por qué detectar la hipoacusia tempranamente?</h2>
  <p>
    La hipoacusia infantil es una condición que afecta el desarrollo del lenguaje,
    la comunicación y el aprendizaje. La detección temprana permite una intervención
    oportuna, mejorando significativamente la calidad de vida del niño.
  </p>

  <div class="cards">
    <div class="card">
      <h3>🎯 Objetivo</h3>
      <p>
        Desarrollar una plataforma que apoye la detección temprana de
        posibles alteraciones auditivas en bebés de 0 a 6 meses.
      </p>
    </div>

    <div class="card">
      <h3>👨‍⚕️ Especialistas</h3>
      <p>
        Acceso a datos cuantitativos, análisis de respuestas motoras
        y apoyo a la evaluación clínica.
      </p>
    </div>

    <div class="card">
      <h3>👨‍👩‍👧 Padres</h3>
      <p>
        Visualización clara de resultados preliminares y orientación
        temprana sin reemplazar el diagnóstico médico.
      </p>
    </div>
  </div>
</section>

<!-- ===== PROTOTIPO ===== -->
<section id="prototipo">
  <h2>Descripción del Prototipo</h2>
  <p>
    El sistema integra hardware y software para la emisión de estímulos sonoros
    controlados y el análisis de respuestas motoras mediante visión por computador.
  </p>
  <div style="text-align:center; margin:30px 0;">
  <img src="img/prototipo.png" alt="Prototipo del sistema"
       style="width:100%; max-width:600px; border-radius:15px;
              box-shadow:0 6px 15px rgba(0,0,0,.2);">
  <p style="font-size:13px; color:#555; margin-top:8px;">
    Prototipo experimental para detección temprana de hipoacusia infantil
  </p>
</div>



  <div class="cards">
    <div class="card">
      <h3>🧠 Hardware</h3>
      <p>
        Jetson Nano, cámara fija, sistema de sonido lateral
        y entorno controlado para pruebas auditivas.
      </p>
    </div>

    <div class="card">
      <h3>📊 Software</h3>
      <p>
        Plataforma web para registro de datos, procesamiento
        de resultados y visualización diferenciada por rol.
      </p>
    </div>

    <div class="card">
      <h3>📈 Análisis</h3>
      <p>
        Medición de ángulos de cabeza, movimientos de extremidades
        y tiempo de respuesta ante estímulos auditivos.
      </p>
    </div>
  </div>
</section>

<!-- ===== EQUIPO ===== -->
<section id="equipo">
  <h2>Equipo de Desarrollo</h2>
  <p>
    Proyecto desarrollado como Trabajo de Grado en Ingeniería Electrónica
    y Telecomunicaciones.
  </p>

  <div class="cards">
    <div class="card" style="text-align:center;">
      <img src="img/kevin.jpg" alt="Kevin Santiago Oliveros"
           style="width:120px; height:120px; object-fit:cover;
                  border-radius:50%; margin-bottom:15px;
                  box-shadow:0 4px 10px rgba(0,0,0,.15);">
    
      <h3>Kevin Santiago Oliveros</h3>
      <p>
        Estudiante de Ingeniería Electrónica y Telecomunicaciones<br>
        Universidad del Cauca
      </p>
    </div>


    <div class="card" style="text-align:center;">
      <img src="img/manuela.png" alt="Manuela Gaviria"
           style="width:120px; height:120px; object-fit:cover;
                  border-radius:50%; margin-bottom:15px;
                  box-shadow:0 4px 10px rgba(0,0,0,.15);">
    
      <h3>Manuela Gaviria</h3>
      <p>
        Estudiante de Ingeniería Electrónica y Telecomunicaciones<br>
        Universidad del Cauca
      </p>
    </div>

  </div>
</section>

<footer>
  © 2026 – Plataforma para seguimiento de registro audivisual<br>
  Trabajo de Grado – Universidad del Cauca
</footer>

</body>
</html>
