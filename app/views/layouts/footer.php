    <?php if(isset($_SESSION['success'])): ?>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            Swal.fire({
                icon: 'success',
                title: '¡Éxito!',
                text: '<?= addslashes($_SESSION['success']) ?>',
                confirmButtonColor: '#0d9488'
            });
        });
    </script>
    <?php unset($_SESSION['success']); ?>
    <?php endif; ?>

    <?php if(isset($_SESSION['error'])): ?>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: '<?= addslashes($_SESSION['error']) ?>',
                confirmButtonColor: '#0d9488'
            });
        });
    </script>
    <?php unset($_SESSION['error']); ?>
    <?php endif; ?>

    <script src="app/public/js/main.js" type="module"></script>
  </body>
</html>
