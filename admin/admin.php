<?php
session_start();
include("../config/db.php"); // conexión a la base de datos

// ---------- CRUD CATEGORÍAS ----------
if (isset($_POST['accion']) && $_POST['accion'] === 'crear_categoria') {
    $nombre = $conn->real_escape_string($_POST['nombre']);
    $descripcion = $conn->real_escape_string($_POST['descripcion']);

    $sql = "INSERT INTO categorias (nombre, descripcion, fecha_creacion, fecha_actualizacion)
            VALUES ('$nombre', '$descripcion', NOW(), NOW())";

    if ($conn->query($sql) === TRUE) {
        header("Location: admin.php");
        exit;
    } else {
        die("Error al insertar categoría: " . $conn->error);
    }
}

if (isset($_POST['accion']) && $_POST['accion'] === 'actualizar_categoria') {
    $id = (int)$_POST['id_categoria'];
    $nombre = $conn->real_escape_string($_POST['nombre']);
    $descripcion = $conn->real_escape_string($_POST['descripcion']);

    $sql = "UPDATE categorias 
            SET nombre='$nombre', descripcion='$descripcion', fecha_actualizacion=NOW()
            WHERE id_categoria=$id";

    if ($conn->query($sql) === TRUE) {
        header("Location: admin.php");
        exit;
    } else {
        die("Error al actualizar categoría: " . $conn->error);
    }
}

if (isset($_GET['eliminar_categoria'])) {
    $id = (int)$_GET['eliminar_categoria'];
    $sql = "DELETE FROM categorias WHERE id_categoria=$id";

    if ($conn->query($sql) === TRUE) {
        header("Location: admin.php");
        exit;
    } else {
        die("Error al eliminar categoría: " . $conn->error);
    }
}

// ---------- CRUD PRODUCTOS ----------
if (isset($_POST['accion']) && $_POST['accion'] === 'crear_producto') {
    $nombre = $conn->real_escape_string($_POST['nombre']);
    $descripcion = $conn->real_escape_string($_POST['descripcion']);
    $precio = (float)$_POST['precio'];
    $stock = (int)$_POST['stock'];
    $categoria = (int)$_POST['categoria'];
    $destacado = isset($_POST['destacado']) ? 1 : 0;

    // Procesar imagen subida
    $imagen_url = "";
    if (isset($_FILES['imagen_url']) && $_FILES['imagen_url']['error'] === UPLOAD_ERR_OK) {
        $nombreArchivo = basename($_FILES['imagen_url']['name']);
        $rutaDestino = "../uploads/" . $nombreArchivo;
        if (move_uploaded_file($_FILES['imagen_url']['tmp_name'], $rutaDestino)) {
            $imagen_url = $rutaDestino;
        }
    }

    $sql = "INSERT INTO productos (nombre, descripcion, precio, stock, categoria, imagen_url, destacado, fecha_creacion, fecha_actualizacion)
            VALUES ('$nombre','$descripcion',$precio,$stock,$categoria,'$imagen_url',$destacado,NOW(),NOW())";

    if ($conn->query($sql) === TRUE) {
        header("Location: admin.php");
        exit;
    } else {
        die("Error al insertar producto: " . $conn->error);
    }
}

if (isset($_POST['accion']) && $_POST['accion'] === 'actualizar_producto') {
    $id = (int)$_POST['id_producto'];
    $nombre = $conn->real_escape_string($_POST['nombre']);
    $descripcion = $conn->real_escape_string($_POST['descripcion']);
    $precio = (float)$_POST['precio'];
    $stock = (int)$_POST['stock'];
    $categoria = (int)$_POST['categoria'];
    $destacado = isset($_POST['destacado']) ? 1 : 0;

    // Procesar imagen subida
    $imagen_url = $_POST['imagen_actual']; // ruta actual
    if (isset($_FILES['imagen_url']) && $_FILES['imagen_url']['error'] === UPLOAD_ERR_OK) {
        $nombreArchivo = basename($_FILES['imagen_url']['name']);
        $rutaDestino = "../uploads/" . $nombreArchivo;
        if (move_uploaded_file($_FILES['imagen_url']['tmp_name'], $rutaDestino)) {
            $imagen_url = $rutaDestino;
        }
    }

    $sql = "UPDATE productos 
            SET nombre='$nombre', descripcion='$descripcion', precio=$precio, stock=$stock,
                categoria=$categoria, imagen_url='$imagen_url', destacado=$destacado, fecha_actualizacion=NOW()
            WHERE id_producto=$id";

    if ($conn->query($sql) === TRUE) {
        header("Location: admin.php");
        exit;
    } else {
        die("Error al actualizar producto: " . $conn->error);
    }
}

if (isset($_GET['eliminar_producto'])) {
    $id = (int)$_GET['eliminar_producto'];
    $sql = "DELETE FROM productos WHERE id_producto=$id";

    if ($conn->query($sql) === TRUE) {
        header("Location: admin.php");
        exit;
    } else {
        die("Error al eliminar producto: " . $conn->error);
    }
}

// ---------- LECTURA ----------
// Categorías
$categorias = $conn->query("SELECT * FROM categorias ORDER BY id_categoria ASC");

// Orden dinámico productos
$orden = "p.id_producto";
$dir   = "DESC";

if (isset($_GET['orden'])) {
    $ordenPermitido = ["p.id_producto","p.nombre","p.descripcion","p.precio","p.stock","c.nombre"];
    if (in_array($_GET['orden'], $ordenPermitido)) {
        $orden = $_GET['orden'];
    }
}
if (isset($_GET['dir']) && in_array(strtoupper($_GET['dir']), ["ASC","DESC"])) {
    $dir = strtoupper($_GET['dir']);
}

$ordenActual = $orden;
$dirActual   = $dir;

function linkOrden($columna, $texto, $ordenActual, $dirActual) {
    $nextDir = "ASC";
    if ($ordenActual === $columna) {
        $nextDir = ($dirActual === "ASC") ? "DESC" : "ASC";
    }
    return "<a href=\"?orden=$columna&dir=$nextDir\">$texto</a>";
}

$productos = $conn->query("SELECT p.*, c.nombre AS categoria_nombre
                           FROM productos p
                           LEFT JOIN categorias c ON p.categoria = c.id_categoria
                           ORDER BY $orden $dir");
?>




<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Panel de Administración | Pendinails</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <!-- Bootstrap 5 -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
  <div class="container my-4">

    <!-- Mensajes -->
    <?php if (!empty($mensaje)): ?>
      <div class="alert alert-info alert-dismissible fade show" role="alert">
        <?= htmlspecialchars($mensaje) ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
      </div>
    <?php endif; ?>

    <!-- Gestión de Categorías -->
    <div class="card mb-4">
      <div class="card-header bg-primary text-white fw-bold">Gestión de Categorías</div>
      <div class="card-body">
        <!-- Formulario para crear nueva categoría -->
        <form method="POST">
          <input type="hidden" name="accion" value="crear_categoria">
          <table class="table table-bordered table-sm table-hover align-middle">
            <thead class="table-light">
              <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Descripción</th>
                <th>Acción</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td>Nuevo</td>
                <td><input type="text" name="nombre" class="form-control form-control-sm" required></td>
                <td><input type="text" name="descripcion" class="form-control form-control-sm"></td>
                <td><button type="submit" class="btn btn-success btn-sm">➕ Crear</button></td>
              </tr>
            </tbody>
          </table>
        </form>

        <!-- Tabla de categorías existentes -->
        <table class="table table-bordered table-sm table-hover align-middle">
          <tbody>
            <?php while ($c = $categorias->fetch_assoc()): ?>
            <tr>
              <form method="POST">
                <input type="hidden" name="accion" value="actualizar_categoria">
                <input type="hidden" name="id_categoria" value="<?= $c['id_categoria'] ?>">
                <td><?= $c['id_categoria'] ?></td>
                <td><input type="text" name="nombre" value="<?= htmlspecialchars($c['nombre']) ?>" class="form-control form-control-sm"></td>
                <td><input type="text" name="descripcion" value="<?= htmlspecialchars($c['descripcion']) ?>" class="form-control form-control-sm"></td>
                <td>
                  <button type="submit" class="btn btn-warning btn-sm">💾 Guardar</button>
                  <a href="?eliminar_categoria=<?= $c['id_categoria'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('¿Eliminar?')">🗑️</a>
                </td>
              </form>
            </tr>
            <?php endwhile; ?>
          </tbody>
        </table>
      </div>
    </div>

    <!-- Gestión de Productos -->
    <div class="card">
      <div class="card-header bg-success text-white fw-bold">Gestión de Productos</div>
      <div class="card-body">
        <!-- Formulario para crear nuevo producto -->
        <form method="POST" enctype="multipart/form-data">
          <input type="hidden" name="accion" value="crear_producto">
          <table class="table table-bordered table-sm table-hover align-middle">
            <thead class="table-light">
              <tr>
                <th><?= linkOrden("p.id_producto","ID",$ordenActual,$dirActual) ?></th>
                <th><?= linkOrden("p.nombre","Nombre",$ordenActual,$dirActual) ?></th>
                <th><?= linkOrden("p.descripcion","Descripción",$ordenActual,$dirActual) ?></th>
                <th><?= linkOrden("p.precio","Precio",$ordenActual,$dirActual) ?></th>
                <th><?= linkOrden("p.stock","Stock",$ordenActual,$dirActual) ?></th>
                <th><?= linkOrden("c.nombre","Categoría",$ordenActual,$dirActual) ?></th>
                <th>Imagen</th>
                <th>Destacado</th>
                <th>Acción</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td>Nuevo</td>
                <td><input type="text" name="nombre" class="form-control form-control-sm" required></td>
                <td><input type="text" name="descripcion" class="form-control form-control-sm"></td>
                <td><input type="number" step="0.01" name="precio" class="form-control form-control-sm w-auto" required></td>
                <td><input type="number" name="stock" class="form-control form-control-sm w-auto" required></td>
                <td>
                  <select name="categoria" class="form-select form-select-sm">
                    <?php
                    $cats = $conn->query("SELECT * FROM categorias ORDER BY nombre ASC");
                    while ($cat = $cats->fetch_assoc()):
                    ?>
                      <option value="<?= $cat['id_categoria'] ?>"><?= htmlspecialchars($cat['nombre']) ?></option>
                    <?php endwhile; ?>
                  </select>
                </td>
                <td><input type="file" name="imagen_url" class="form-control form-control-sm"></td>
                <td class="text-center"><input type="checkbox" name="destacado"></td>
                <td><button type="submit" class="btn btn-success btn-sm">➕ Crear</button></td>
              </tr>
            </tbody>
          </table>
        </form>

        <!-- Tabla de productos existentes -->
        <table class="table table-bordered table-sm table-hover align-middle">
          <tbody>
            <?php while ($p = $productos->fetch_assoc()): ?>
            <tr>
              <form method="POST" enctype="multipart/form-data">
                <input type="hidden" name="accion" value="actualizar_producto">
                <input type="hidden" name="id_producto" value="<?= $p['id_producto'] ?>">
                <input type="hidden" name="imagen_actual" value="<?= htmlspecialchars($p['imagen_url']) ?>">
                <td><?= $p['id_producto'] ?></td>
                <td><input type="text" name="nombre" value="<?= htmlspecialchars($p['nombre']) ?>" class="form-control form-control-sm"></td>
                <td><input type="text" name="descripcion" value="<?= htmlspecialchars($p['descripcion']) ?>" class="form-control form-control-sm"></td>
                <td><input type="number" step="0.01" name="precio" value="<?= $p['precio'] ?>" class="form-control form-control-sm w-auto"></td>
                <td><input type="number" name="stock" value="<?= $p['stock'] ?>" class="form-control form-control-sm w-auto"></td>
                <td>
                  <select name="categoria" class="form-select form-select-sm">
                    <?php
                    $cats2 = $conn->query("SELECT * FROM categorias ORDER BY nombre ASC");
                    while ($cat2 = $cats2->fetch_assoc()):
                    ?>
                      <option value="<?= $cat2['id_categoria'] ?>" <?= ($p['categoria'] == $cat2['id_categoria']) ? 'selected' : '' ?>>
                        <?= htmlspecialchars($cat2['nombre']) ?>
                      </option>
                    <?php endwhile; ?>
                  </select>
                </td>
                <td><input type="file" name="imagen_url" class="form-control form-control-sm"></td>
                <td class="text-center"><input type="checkbox" name="destacado" <?= ($p['destacado'] == 1) ? 'checked' : '' ?>></td>
                <td>
                  <button type="submit" class="btn btn-warning btn-sm">💾 Guardar</button>
                  <a href="?eliminar_producto=<?= $p['id_producto'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('¿Eliminar?')">🗑️</a>
                </td>
              </form>
            </tr>
            <?php endwhile; ?>
          </tbody>
        </table>
      </div>
    </div>

    <footer class="py-4 text-center text-muted">
      <small>© <?= date('Y') ?> Pendinails — Panel Admin</small>
    </footer>
  </div>

  <!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
