<?php
// Conexión a la base de datos
include("../config/db.php");
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>PendiNails - Joyas únicas</title>
  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- Tu CSS personalizado -->
  <link rel="stylesheet" href="stylos.css">
</head>

<body>
<!-- 🔝 Menú de navegación responsive -->
<nav class="navbar navbar-dark">
  <div class="container">
    <a class="navbar-brand" href="#">PendiNails</a>
    <div class="navbar-nav ms-auto">
      <a class="nav-link" href="#inicio">Inicio</a>
      <a class="nav-link" href="#destacados">Destacados</a>
      <a class="nav-link" href="#productos">Productos</a>
      <a class="nav-link" href="#contacto">Contacto</a>
      <a class="nav-link" href="#legal">Términos legales</a>
    </div>
  </div>
</nav>

<!-- 🔼 Flecha hacia inicio -->
<a href="#inicio" class="scroll-top" id="scrollTopBtn" aria-label="Volver arriba">
  <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="#ffb6c1" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
    <polyline points="18 15 12 9 6 15" />
  </svg>
</a>

<!-- 🏠 Sección Hero -->
<section id="inicio" class="hero">
  <div class="d-flex flex-column align-items-center text-center">
    <h1>PendiNails</h1>
    <p>La originalidad hecha a mano: el diseño que transforma uñas sintéticas en joyas exclusivas.</p>
    <img src="img/hero_pendinails.png" alt="Pendientes PendiNails" class="hero-img my-3">
    <a href="#productos" class="btn-elegante">Ver productos</a>
  </div>
</section>

<!-- 🎠 Carrusel de productos destacados -->
<section id="destacados" class="container my-5">
  <h2 class="text-center mb-4">Productos destacados</h2>
  <div id="pendinailsCarousel" class="carousel slide" data-bs-ride="carousel">
    <div class="carousel-inner">
      <?php
      // Incluimos también el campo 'descripcion'
      $result = $conn->query("SELECT id_producto, nombre, descripcion, precio, imagen_url FROM productos WHERE destacado = 1");
      $active = true;
      while($row = $result->fetch_assoc()) {
          echo '<div class="carousel-item text-center '.($active ? 'active' : '').'">';
          echo '<img src="'.$row['imagen_url'].'" class="d-block mx-auto carousel-img" alt="'.$row['nombre'].'">';
          echo '<h5>'.$row['nombre'].'</h5>';
          // Mostramos la descripción debajo del nombre
          echo '<p class="descripcion">'.$row['descripcion'].'</p>';
          echo '<p>'.$row['precio'].' €</p>';
          echo '<a href="#comprar-'.$row['id_producto'].'" class="btn-elegante"><span>Comprar ahora</span></a>';
          echo '</div>';
          $active = false;
      }
      ?>
    </div>
    <button class="carousel-control-prev" type="button" data-bs-target="#pendinailsCarousel" data-bs-slide="prev" aria-label="Anterior">
      <span class="carousel-control-prev-icon"></span>
    </button>
    <button class="carousel-control-next" type="button" data-bs-target="#pendinailsCarousel" data-bs-slide="next" aria-label="Siguiente">
      <span class="carousel-control-next-icon"></span>
    </button>
  </div>
</section>

  <!-- 🔍 Buscador de PendiNails -->
  <section id="productos" class="container my-5">
    <div class="card shadow-sm border-0 mx-auto" style="max-width: 700px;">
      <div class="card-body p-4">
        <h2 class="text-center mb-4">Buscar PendiNails</h2>
        <form class="row g-3 justify-content-center">
          <!-- Barra de búsqueda por nombre -->
          <div class="col-12 col-md-6">
            <div class="form-floating">
              <input type="text" class="form-control" id="buscarNombre" placeholder="Buscar por nombre">
              <label for="buscarNombre">Buscar por nombre</label>
            </div>
          </div>
          <!-- Lista desplegable por sección -->
          <div class="col-12 col-md-6">
            <div class="form-floating">
              <select class="form-select" id="buscarSeccion">
                <option selected disabled>Selecciona sección</option>
                <option value="rosas">Colección Rosas</option>
                <option value="glitter">Colección Glitter</option>
                <option value="estaciones">Colección Estaciones</option>
                <option value="exclusivos">Exclusivos</option>
              </select>
              <label for="buscarSeccion">Buscar por sección</label>
            </div>
          </div>
          <!-- Botón de búsqueda -->
          <div class="col-12">
            <button type="submit" class="btn btn-dark w-100">Buscar</button>
          </div>
        </form>
      </div>
    </div>
  </section>


<!-- 📩 Contacto Elegante -->
<section id="contacto" class="container my-5">
  <div class="card shadow-sm border-0 mx-auto" style="max-width: 600px;">
    <div class="card-body p-4">
      <h2 class="text-center mb-4">Contacto</h2>
      <form class="d-flex flex-column gap-3">
        <div class="form-floating">
          <input type="text" class="form-control" id="nombre" placeholder="Nombre" required>
          <label for="nombre">Nombre</label>
        </div>
        <div class="form-floating">
          <input type="email" class="form-control" id="email" placeholder="Correo electrónico" required>
          <label for="email">Correo electrónico</label>
        </div>
        <div class="form-floating">
          <textarea class="form-control" id="mensaje" placeholder="Mensaje" style="height: 120px;" required></textarea>
          <label for="mensaje">Mensaje</label>
        </div>
        <button type="submit" class="btn btn-dark w-100">Enviar</button>
      </form>
    </div>
  </div>
</section>

<!-- ⚖️ Términos legales -->
<section id="legal" class="container my-5">
  <h2 class="text-center mb-4">Términos legales</h2>
  <p class="text-center">Este sitio web cumple con la normativa vigente en materia de comercio electrónico y protección de datos. Al realizar una compra, aceptas nuestras condiciones de uso y política de privacidad.</p>
</section>

<!-- 🔻 Footer -->
<footer>
  <p>&copy; 2025 PendiNails. Todos los derechos reservados.</p>
  <p>Email: elisagimenez6@gmail.com | Instagram: @pendinails</p>
</footer>

  <!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    const navLinks = document.querySelectorAll('.offcanvas-collapse .nav-link');
    const navbarCollapse = document.getElementById('menu');

    navLinks.forEach(link => {
      link.addEventListener('click', () => {
        const bsCollapse = new bootstrap.Collapse(navbarCollapse, {
          toggle: false
        });
        bsCollapse.hide();
      });
    });
  </script>

<script>
  // Scroll suave con compensación de la altura de la navbar
  document.getElementById("scrollTopBtn").addEventListener("click", function(e) {
    e.preventDefault(); // evita el salto brusco
    const navbarHeight = document.querySelector(".navbar").offsetHeight;
    const target = document.querySelector("#inicio").offsetTop;

    window.scrollTo({
      top: target - navbarHeight, // resta la altura de la navbar
      behavior: "smooth"
    });
  });

  // Mostrar/ocultar la flecha al hacer scroll
  window.addEventListener("scroll", function() {
    const btn = document.getElementById("scrollTopBtn");
    if (window.scrollY > 200) {
      btn.style.display = "block";
    } else {
      btn.style.display = "none";
    }
  });
</script>

</body>
</html>