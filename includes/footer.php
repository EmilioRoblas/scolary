
<footer class="scolaryFooter py-4">
    <div class="container text-center">
        <p class="mb-1">
            <strong class="footer-brand">Scolary</strong> &copy; <?php echo date('Y'); ?>. Todos los derechos reservados.
        </p>
        <a href="politicaPrivacidad.php" class="footer-link text-decoration-none">
            Política de privacidad
        </a>
        <span> • </span>
        <?php 
        if($_SESSION['usuarioRol'] == 'tutor'){
        ?>
        <a href="service/bajaTutor.php" class="footer-link text-decoration-none">
            Dar de baja tu cuenta
        </a>
        <?php }?>
    </div>
</footer>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
