<?php 
$pageTitle = 'Registro - TicoTrips';
require_once __DIR__ . '/../layouts/header.php';

// Definición de ID de Roles
define('ROL_CLIENTE', 2);
define('ROL_COMERCIO', 3); 
?>

<body class="bg-gray-100 min-h-screen">
    <div class="w-full max-w-lg bg-white p-8 rounded-xl shadow-2xl mx-auto sm:mt-20 my-10">
        <h2 class="text-2xl font-bold text-gray-800 mb-6 text-center">Registro de Usuario</h2>
        
        <?php if(isset($_SESSION['error'])): ?>
            <div class="p-4 mb-4 text-sm text-red-800 rounded-lg bg-red-50">
                <?= htmlspecialchars($_SESSION['error']) ?>
            </div>
            <?php unset($_SESSION['error']); ?>
        <?php endif; ?>

        <?php if(isset($_SESSION['success'])): ?>
            <div class="p-4 mb-4 text-sm text-green-800 rounded-lg bg-green-50">
                <?= htmlspecialchars($_SESSION['success']) ?>
            </div>
            <?php unset($_SESSION['success']); ?>
        <?php endif; ?>

        <form id="user-register-form" action="index.php?action=register" method="POST">
            <div class="space-y-4">
                
                <div>
                    <label for="reg-user-nombre" class="block text-sm font-medium text-gray-700">Nombre Completo</label>
                    <input type="text" id="reg-user-nombre" name="nombre" required
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-teal-500 focus:ring-teal-500 sm:text-sm p-2 border">
                </div>
                
                <div>
                    <label for="reg-user-email" class="block text-sm font-medium text-gray-700">Correo Electrónico</label>
                    <input type="email" id="reg-user-email" name="correo" required
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-teal-500 focus:ring-teal-500 sm:text-sm p-2 border">
                </div>
                
                <div>
                    <label for="reg-user-password" class="block text-sm font-medium text-gray-700">Contraseña</label>
                    <input type="password" id="reg-user-password" name="password" required
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-teal-500 focus:ring-teal-500 sm:text-sm p-2 border">
                </div>

                <div>
                    <label for="reg-user-telefono" class="block text-sm font-medium text-gray-700">Teléfono</label>
                    <input type="tel" id="reg-user-telefono" name="telefono"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-teal-500 focus:ring-teal-500 sm:text-sm p-2 border">
                </div>

                <div>
                    <label for="reg-user-fecha-nacimiento" class="block text-sm font-medium text-gray-700">Fecha de Nacimiento</label>
                    <input type="date" id="reg-user-fecha-nacimiento" name="fecha_nacimiento"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-teal-500 focus:ring-teal-500 sm:text-sm p-2 border">
                </div>

                <div>
                    <label for="reg-user-rol" class="block text-sm font-medium text-gray-700">Tipo de Usuario</label>
                    <select id="reg-user-rol" name="rol" required
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-teal-500 focus:ring-teal-500 sm:text-sm p-2 border">
                        <option value="">Seleccione...</option>
                        <option value="<?= ROL_CLIENTE ?>">Cliente</option>
                        <option value="<?= ROL_COMERCIO ?>">Comercio / Negocio</option>
                    </select>
                </div>

                <button type="submit"
                    class="w-full py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-teal-600 hover:bg-teal-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-teal-500 transition duration-150">
                    Registrarse
                </button>

                <div class="text-center text-sm">
                    <a href="index.php?action=login" class="font-medium text-teal-600 hover:text-teal-500">
                        ¿Ya tienes cuenta? Inicia sesión
                    </a>
                </div>
            </div>
        </form>
    </div>
</body>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
