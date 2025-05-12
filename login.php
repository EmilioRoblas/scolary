<?php
session_start();
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $usuario = $_POST['usuario'];
    $clave = $_POST['clave'];
    //Esto es una prueba básica aún no he implementado el loggin real 
    //porque quiero trastear primero con los componentes boostrap,
    //el login lo cogeré del proyecto del videoclub.
    if ($usuario === 'admin' && $clave === 'admin123') {
        $_SESSION['usuario'] = $usuario;
        header("Location: dashboardAdmin.php");
        exit();
    } else {
        $error = "Usuario o clave incorrectos";
    }
}
?>

<?php include 'includes/header.php'; ?>
<div class="container mt-5">
    <h2>Iniciar sesión en Scolary</h2>
    <?php if (isset($error)): ?>
        <div class="alert alert-danger"><?= $error ?></div>
    <?php endif; ?>
    <form method="post">
        <div class="mb-3">
            <label class="form-label">Usuario</label>
            <input type="text" name="usuario" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Contraseña</label>
            <input type="password" name="clave" class="form-control" required>
        </div>
        <button class="btn btn-primary">Entrar</button>
    </form>
</div>
<?php include 'includes/footer.php'; ?>
