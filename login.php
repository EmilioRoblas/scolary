
<?php include 'includes/header.php'; ?>
<div class="container mt-5">
    <h2>Iniciar sesión en Scolary</h2>
    <?php if (isset($_GET['error'])){ ?>
        <div class="alert alert-danger"><?= $_GET['error'] ?></div>
    <?php }?>
    <form action="service/compruebaLogin.php" method="post">
        <div class="mb-3">
            <label class="form-label">Usuario</label>
            <input type="text" name="usuario" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Contraseña</label>
            <input type="password" name="clave" class="form-control" required>
        </div>
        <input type="submit" class="btn btn-primary" value="Entrar">
    </form>
</div>
<?php include 'includes/footer.php'; ?>
