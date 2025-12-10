<?php 
$pageTitle = 'Iniciar Sesión - TicoTrips';
require_once __DIR__ . '/../layouts/header.php'; 
?>

<body class="bg-gray-100 min-h-screen">
    <div class="w-full max-w-lg bg-white p-8 rounded-xl shadow-2xl mx-auto sm:mt-20 my-10">
        
        <h2 class="text-2xl font-bold mb-6 text-center text-teal-600">
            Iniciar Sesión
        </h2>
        
        <?php if(isset($_GET['error'])): ?>
            <div class="p-4 mb-4 text-sm text-red-800 rounded-lg bg-red-50">
                <?= htmlspecialchars($_GET['error']) ?>
            </div>
        <?php endif; ?> 
        
        <?php if(isset($_SESSION['success'])): ?>
            <div class="p-4 mb-4 text-sm text-green-800 rounded-lg bg-green-50">
                <?= htmlspecialchars($_SESSION['success']) ?>
            </div>
            <?php unset($_SESSION['success']); ?>
        <?php endif; ?>
        
        <form id="login-form" action="index.php?action=login" method="POST"> 	
            <div class="space-y-4">
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700">Correo Electrónico</label>
                    <input type="email" id="email" name="email" required
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-teal-500 focus:ring-teal-500 sm:text-sm p-2 border">
                </div>
                <div>
                    <label for="password_hash" class="block text-sm font-medium text-gray-700">Contraseña</label>
                    <input type="password" id="password_hash" name="password_hash" required
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-teal-500 focus:ring-teal-500 sm:text-sm p-2 border">
                </div>

                <div class="flex justify-end text-sm">
                    <a href="index.php?action=register" 
                    class="font-medium text-teal-600 hover:text-teal-500">
                        ¿No tienes cuenta? Regístrate
                    </a>
                </div>

                <button type="submit"
                    class="w-full py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-teal-600 hover:bg-teal-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-teal-500 transition duration-150">
                    Iniciar Sesión
                </button>
            </div>
        </form>
    </div>
</body>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
