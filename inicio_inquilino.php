<?php
session_start();
error_reporting(E_ALL);
ini_set("display_errors", 1);
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Inicio Inquilino</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      background-color: #f9f9f9;
      margin: 0;
      padding: 0;
    }
    .container {
      max-width: 1200px;
      margin: 50px auto;
      padding: 20px;
      text-align: center;
      display: flex;
      justify-content: center;
      flex-wrap: nowrap;
    }
    h1 {
      color: white;
      background-image: url("../images/bannerbien.mp4");
      background-size: cover;
      background-position: center;
      padding: 60px 20px;
      font-size: 3rem;
      font-weight: bold;
      text-align: center;
      margin: 0;
      width: 100%;
      box-sizing: border-box;
      border-radius: 12px;
    }
    .cards {
      display: flex;
      flex-wrap: wrap;
      justify-content: center;
      gap: 20px;
      margin: 30px auto;
      padding: 0 20px;
      max-width: 1200px;
    }
    .card {
      background-color: #fff;
      border-radius: 12px;
      box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
      padding: 30px;
      width: 280px;
      transition: transform 0.2s;
      cursor: pointer;
      margin: 10px;
    }
    .card:hover {
      transform: translateY(-5px);
    }
    .card-icon {
      display: flex;
      flex-wrap: wrap;
      justify-content: center;
      font-size: 40px;
      color: #FFFFFF;
      margin-bottom: 10px;
    }
    .card-title {
      font-size: 20px;
      font-weight: bold;
      margin-bottom: 10px;
    }
    .card-buttons {
      display: flex;
      justify-content: center;
      gap: 10px;
      margin-top: 15px;
    }
    .card-button {
      background-color: rgb(115, 196, 156);
      color: #fff;
      border: none;
      border-radius: 25px;
      padding: 10px 20px;
      cursor: pointer;
      font-size: 14px;
      font-weight: bold;
      box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
      transition: all 0.3s ease;
      text-align: center;
    }
    .card-button:hover {
      background-color: rgb(77, 134, 85);
      box-shadow: 0 6px 10px rgba(0, 0, 0, 0.15);
      transform: translateY(-2px);
    }
    .card-button:active {
      background-color: #c2185b;
      transform: translateY(0);
      box-shadow: 0 3px 5px rgba(0, 0, 0, 0.2);
    }
    .bottom-button-container {
      text-align: center;
      margin: 40px 0;
    }
    aside {
      flex-shrink: 0;
      max-width: 230px;
    }
    aside img {
      width: 100%;
      height: auto;
      position: sticky;
      top: 0;
    }
    .slider-wrapper {
      max-width: 800px;
      margin: 0 auto;
      position: relative;
    }
    .slider-container {
      overflow: hidden;
      border-radius: 16px;
      box-shadow: 0 6px 15px rgba(0, 0, 0, 0.15);
    }
    .slides {
      display: flex;
      width: 100%;
      transition: transform 0.5s ease-in-out;
    }
    .slide {
      min-width: 100%;
      display: flex;
      justify-content: center;
      align-items: center;
    }
    .slide p {
      font-size: 16px;
      margin-top: 10px;
      color: #333;
    }
    .slide img {
      display: block;
      max-height: 400px;
      border-radius: 10px;
    }
    input[type="radio"] {
      display: none;
    }
    .navigation {
      text-align: center;
      margin-top: 16px;
    }
    .navigation label {
      width: 14px;
      height: 14px;
      margin: 0 6px;
      border-radius: 50%;
      background-color: #bbb;
      display: inline-block;
      cursor: pointer;
    }
    input#slide1:checked~.slider-container .slides {
      transform: translateX(0);
    }
    input#slide2:checked~.slider-container .slides {
      transform: translateX(-210px);
    }
    input#slide3:checked~.slider-container .slides {
      transform: translateX(-420px);
    }
    input#slide4:checked~.slider-container .slides {
      transform: translateX(-630px);
    }
    input#slide5:checked~.slider-container .slides {
      transform: translateX(-840px);
    }
    input#slide1:checked~.navigation label[for="slide1"],
    input#slide2:checked~.navigation label[for="slide2"],
    input#slide3:checked~.navigation label[for="slide3"],
    input#slide4:checked~.navigation label[for="slide4"],
    input#slide5:checked~.navigation label[for="slide5"] {
      background-color: #444;
    }
  </style>
  <link rel="icon" type="image/jpg" href="/images/logo_compiso.ico" />
  <link rel="stylesheet" href="./css/general.css">
  <link rel="stylesheet" href="./css/inicio.css">

  <script> window.chtlConfig = { chatbotId: "2783453492" } </script>
  <script async data-id="2783453492" id="chatling-embed-script" type="text/javascript"
    src="https://chatling.ai/js/embed.js"></script>
</head>
<body>
  <header>
    <div>
      <div>
        <img src="./images/logo_compiso.png" alt="Logo" id="logo">
        <h2 id="titulo">Compiso</h2>
      </div>
      <div>
        <a href="./usuario/cerrar_session.php" id="login">Cerrar Sesión</a>
      </div>
    </div>
  </header>
  <div class="container">
    <?php
    if (isset($_SESSION["usuario"])) {
      echo "<h1>Bienvenid@ " . htmlspecialchars($_SESSION["usuario"]["nombre"]) . "</h1>";
    } else {
      header("Location: usuario/iniciar_sesion.php");
      exit;
    }
    ?>
  </div>
  <div class="flex justify-between">
    <aside class="sticky top-0 w-2/10 bg-white p-4">
      <a href="https://www.adobe.com/es/products/special-offers.html?sdid=B8NR3JZH&mv=display&mv2=display"
        target="_blank">
        <img src="./images/publi-adobe.PNG" alt="Publicidad Izquierda" class="w-full h-auto sticky top-0">
      </a>
    </aside>
    <main class="w-6/10 bg-white p-4">
      <div class="cards">
        <div class="card">
          <div class="card-icon">👤</div>
          <div class="card-title">Mi perfil</div>
          <div class="card-buttons">
            <button onclick="location.href='./panel_control/mi_perfil.php'" class="card-button">Mi perfil</button>
            <button onclick="location.href='./panel_control/completa_perfil.php'" class="card-button">Completar
              perfil</button>
          </div>
        </div>
        <div class="card">
          <div class="card-icon">👥</div>
          <div class="card-title">Compañeros</div>
          <div class="card-buttons">
            <button onclick="location.href='./panel_control/buscar_usuarios.php'" class="card-button">Buscar
              más</button>
            <button onclick="location.href='./matches/matches_usuarios.php'" class="card-button">Mis match</button>
          </div>
        </div>
        <div class="card">
          <div class="card-icon">🏠</div>
          <div class="card-title">Viviendas</div>
          <div class="card-buttons">
            <button onclick="location.href='./panel_control/buscar_vivienda.php'" class="card-button">Buscar
              más</button>
            <button onclick="location.href='./matches/matches_viviendas.php'" class="card-button">Matches</button>
          </div>
        </div>
      </div>
      <div class="bottom-button-container">
        <button onclick="location.href='./paypal/crear_plan_premium.php'" class="card-button">💰 Pásate a Premium y disfruta de las ventajas</button>
      </div>
      <div class="bottom-button-container">
        <button onclick="location.href='./panel_control/comentarios.php'" class="card-button">⭐ Puntúanos y deja tu
          opinión</button>
      </div>
      <?php require("./utiles/conexion.php"); ?>
     <div class="slider-wrapper">
        <?php
        require("./utiles/conexion.php");
        $slideData = [];
        $result = $_conexion->query("SELECT id_vivienda, direccion, imagenes FROM Vivienda LIMIT 5");
        if ($result && $result->num_rows > 0) {
          while ($row = $result->fetch_assoc()) {
            $slideData[] = [
              'img' => './panel_control/uploads/' . htmlspecialchars($row["imagenes"]),
              'direccion' => htmlspecialchars($row["direccion"])
            ];
          }
        }
        foreach ($slideData as $index => $slide) {
          echo '<input type="radio" name="slider" id="slide' . ($index + 1) . '" ' . ($index === 0 ? 'checked' : '') . '>';
        }
        ?>
        <div class="slider-container">
          <div class="slides" id="slides">
            <?php foreach ($slideData as $slide): ?>
              <div class="slide">
                <div>
                  <img src="<?= $slide['img'] ?>" alt="Vivienda">
                  <p style="margin-top: 10px; font-weight: bold; color: #333; text-align: center;">
                    <?= $slide['direccion'] ?>
                  </p>
                </div>
              </div>
            <?php endforeach; ?>
          </div>
        </div>
        <div class="navigation" id="navigation">
          <?php for ($i = 1; $i <= count($slideData); $i++): ?>
            <label for="slide<?= $i ?>"></label>
          <?php endfor; ?>
        </div>
    </main>
    <aside class="sticky top-0 w-2/10 bg-white p-4">
      <a href="https://www.amazon.es/dp/B0DSG33F3L?aref=sqlChtsoQV&aaxitk=d08d355ec17803e5f8f987030a9c6819&language=es_ES&tag=ss-us-20&smid=A1AT7YVPFBWXBL&ref=dacx_dp_581322398197797346_577533411937755342&th=1"
        target="_blank">
        <img src="./images/amazon-xiaomi.PNG" alt="Publicidad Izquierda" class="w-full h-auto sticky top-0">
      </a>
    </aside>
  </div>
  <footer class="bg-black p-4 text-white text-center text-sm mt-auto">
    &copy; <?php echo date("Y"); ?> Compiso. Todos los derechos reservados.
  </footer>
</body>
<script>
  const slidesContainer = document.getElementById('slides');
  const slideElements = slidesContainer.querySelectorAll('.slide');
  const navigation = document.getElementById('navigation');
  let currentIndex = 0;
  const goToSlide = (index) => {
    currentIndex = index;
    slidesContainer.style.transform = `translateX(-${index * 100}%)`;
    updateNavigation();
  };
  const updateNavigation = () => {
    const navButtons = navigation.querySelectorAll('button');
    navButtons.forEach((btn, i) => {
      btn.classList.toggle('active', i === currentIndex);
    });
  };
  navigation.innerHTML = '';
  slideElements.forEach((_, index) => {
    const btn = document.createElement('button');
    btn.classList.add('carousel-dot');
    btn.addEventListener('click', () => goToSlide(index));
    navigation.appendChild(btn);
  });
  goToSlide(0);
  document.addEventListener('keydown', (e) => {
    if (e.key === 'ArrowRight') {
      goToSlide((currentIndex + 1) % slideElements.length);
    } else if (e.key === 'ArrowLeft') {
      goToSlide((currentIndex - 1 + slideElements.length) % slideElements.length);
    }
  });
  let autoSlideInterval;
  const startAutoSlide = () => {
    autoSlideInterval = setInterval(() => {
      goToSlide((currentIndex + 1) % slideElements.length);
    }, 
  };
  const stopAutoSlide = () => {
    clearInterval(autoSlideInterval);
  };
  startAutoSlide();
  navigation.addEventListener('mouseover', stopAutoSlide);
  navigation.addEventListener('mouseout', startAutoSlide);
</script>

</html>