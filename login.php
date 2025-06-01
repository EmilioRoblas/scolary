<?php 
include 'includes/header.php';
if (isset($_SESSION)) {
    // Borra todas las variables de sesión
    $_SESSION = [];
    // Destruye la sesión
    session_destroy();
} ?>
<main class="main-content">
<div class="container mt-5">
    <div class="mx-auto text-center" style="max-width: 400px;">
    
    <img src="view/images/iconoScolary2.png" alt="Logotipo de la empresa" width="90" height="90" class="mb-3">    
    <h2 style="color: rgb(32, 68, 76)">Iniciar sesión en Scolary</h2>
    <?php if (isset($_GET['error'])){ ?>
        <div class="alert alert-danger"><?= $_GET['error'] ?></div>
    <?php }?>
    <form action="service/compruebaLogin.php" method="post">
        <div class="mb-3">
            <label style="color: rgb(32, 68, 76)" class="form-label">Usuario</label>
            <input type="text" name="usuario" class="form-control" required>
        </div>
        <div class="mb-3">
            <label style="color: rgb(32, 68, 76)" class="form-label">Contraseña</label>
            <input type="password" name="clave" class="form-control" required>
        </div>
        <div class="d-flex justify-content-center">
        <input type="submit" class="btn botonCrear" value="Entrar">
        </div>
    </form>
    </div>
</div>
</main>
<?php include 'includes/footer.php'; ?>
