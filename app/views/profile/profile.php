<?php 
$pageTitle = 'Mi Perfil - TicoTrips';
require_once __DIR__ . '/../layouts/header.php'; 
?>

<main class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="bg-white shadow-lg rounded-lg overflow-hidden">
        <!-- Header del perfil -->
        <div class="bg-gradient-to-r from-teal-500 to-teal-600 px-6 py-8">
            <div class="flex items-center space-x-4">
                <div class="w-20 h-20 rounded-full bg-white flex items-center justify-center">
                    <?php if (!empty($user['foto_perfil'])): ?>
                        <img src="<?= htmlspecialchars($user['foto_perfil']) ?>" 
                             alt="Foto de perfil" 
                             class="w-full h-full rounded-full object-cover">
                    <?php else: ?>
                        <i class="fas fa-user fa-3x text-teal-600"></i>
                    <?php endif; ?>
                </div>
                <div class="text-white">
                    <h1 class="text-2xl font-bold"><?= htmlspecialchars($user['nombre_completo']) ?></h1>
                    <p class="text-teal-100"><?= htmlspecialchars($user['email']) ?></p>
                </div>
            </div>
        </div>

        <!-- Mensajes de éxito/error -->
        <?php if(isset($_SESSION['success'])): ?>
            <div class="mx-6 mt-6 p-4 text-sm text-green-800 rounded-lg bg-green-50">
                <?= htmlspecialchars($_SESSION['success']) ?>
            </div>
            <?php unset($_SESSION['success']); ?>
        <?php endif; ?>

        <?php if(isset($_SESSION['error'])): ?>
            <div class="mx-6 mt-6 p-4 text-sm text-red-800 rounded-lg bg-red-50">
                <?= htmlspecialchars($_SESSION['error']) ?>
            </div>
            <?php unset($_SESSION['error']); ?>
        <?php endif; ?>

        <!-- Contenido del perfil -->
        <div class="p-6">
            <!-- Tabs -->
            <div class="border-b border-gray-200 mb-6">
                <nav class="flex space-x-8" aria-label="Tabs">
                    <button data-tab="info" class="profile-tab border-b-2 border-teal-500 text-teal-600 py-4 px-1 font-medium">
                        <i class="fas fa-user mr-2"></i> Información Personal
                    </button>
                    <button data-tab="security" class="profile-tab border-b-2 border-transparent text-gray-500 hover:text-gray-700 py-4 px-1 font-medium">
                        <i class="fas fa-lock mr-2"></i> Seguridad
                    </button>
                    <?php if(isset($_SESSION['id_rol']) && in_array($_SESSION['id_rol'], [3, 4, 5, 6])): ?>
                    <button data-tab="business" class="profile-tab border-b-2 border-transparent text-gray-500 hover:text-gray-700 py-4 px-1 font-medium">
                        <i class="fas fa-briefcase mr-2"></i> Mi Negocio
                    </button>
                    <?php endif; ?>
                </nav>
            </div>

            <!-- Tab: Información Personal -->
            <div id="tab-info" class="tab-content">
                <form method="POST" action="index.php?controller=profile&action=update" class="space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="nombre" class="block text-sm font-medium text-gray-700 mb-2">
                                Nombre Completo
                            </label>
                            <input type="text" id="nombre" name="nombre" 
                                   value="<?= htmlspecialchars($user['nombre_completo']) ?>"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent">
                        </div>

                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                                Correo Electrónico
                            </label>
                            <input type="email" id="email" name="email" 
                                   value="<?= htmlspecialchars($user['email']) ?>"
                                   disabled
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg bg-gray-100 cursor-not-allowed">
                            <p class="text-xs text-gray-500 mt-1">El correo no puede ser modificado</p>
                        </div>

                        <div>
                            <label for="telefono" class="block text-sm font-medium text-gray-700 mb-2">
                                Teléfono
                            </label>
                            <input type="tel" id="telefono" name="telefono" 
                                   value="<?= htmlspecialchars($user['telefono'] ?? '') ?>"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent">
                        </div>

                        <div>
                            <label for="fecha_nacimiento" class="block text-sm font-medium text-gray-700 mb-2">
                                Fecha de Nacimiento
                            </label>
                            <input type="date" id="fecha_nacimiento" name="fecha_nacimiento" 
                                   value="<?= htmlspecialchars($user['fecha_Nacimiento'] ?? '') ?>"
                                   disabled
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg bg-gray-100 cursor-not-allowed">
                            <p class="text-xs text-gray-500 mt-1">La fecha de nacimiento no puede ser modificada</p>
                        </div>
                    </div>

                    <div class="flex justify-end space-x-4">
                        <button type="button" onclick="window.history.back()" 
                                class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition">
                            Cancelar
                        </button>
                        <button type="submit" 
                                class="px-6 py-2 bg-teal-600 text-white rounded-lg hover:bg-teal-700 transition">
                            <i class="fas fa-save mr-2"></i> Guardar Cambios
                        </button>
                    </div>
                </form>
            </div>

            <!-- Tab: Seguridad -->
            <div id="tab-security" class="tab-content hidden">
                <form method="POST" action="index.php?controller=profile&action=changePassword" class="space-y-6">
                    <div class="space-y-4">
                        <div>
                            <label for="current_password" class="block text-sm font-medium text-gray-700 mb-2">
                                Contraseña Actual
                            </label>
                            <input type="password" id="current_password" name="current_password" required
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent">
                        </div>

                        <div>
                            <label for="new_password" class="block text-sm font-medium text-gray-700 mb-2">
                                Nueva Contraseña
                            </label>
                            <input type="password" id="new_password" name="new_password" required
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent">
                        </div>

                        <div>
                            <label for="confirm_password" class="block text-sm font-medium text-gray-700 mb-2">
                                Confirmar Nueva Contraseña
                            </label>
                            <input type="password" id="confirm_password" name="confirm_password" required
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent">
                        </div>
                    </div>

                    <div class="flex justify-end">
                        <button type="submit" 
                                class="px-6 py-2 bg-teal-600 text-white rounded-lg hover:bg-teal-700 transition">
                            <i class="fas fa-key mr-2"></i> Cambiar Contraseña
                        </button>
                    </div>
                </form>
            </div>

            <!-- Tab: Mi Negocio (solo para comercios) -->
            <?php if(isset($_SESSION['id_rol']) && in_array($_SESSION['id_rol'], [3, 4, 5, 6])): ?>
            <div id="tab-business" class="tab-content hidden">
                <div class="text-center py-8">
                    <i class="fas fa-store fa-4x text-gray-300 mb-4"></i>
                    <h3 class="text-xl font-semibold text-gray-700 mb-2">Información de Negocio</h3>
                    <p class="text-gray-500 mb-6">Gestiona la información de tu negocio</p>
                    <a href="index.php?controller=business&action=manageCoupons" 
                       class="inline-block px-6 py-3 bg-teal-600 text-white rounded-lg hover:bg-teal-700 transition">
                        <i class="fas fa-briefcase mr-2"></i> Ir a Panel de Negocio
                    </a>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Información adicional -->
    <div class="mt-6 grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="bg-white shadow rounded-lg p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">
                <i class="fas fa-calendar-check text-teal-600 mr-2"></i> Mis Reservaciones
            </h3>
            <p class="text-gray-600 mb-4">Ver y gestionar tus reservaciones</p>
            <a href="index.php?controller=reservation&action=myReservations" 
               class="text-teal-600 hover:text-teal-700 font-medium">
                Ver todas <i class="fas fa-arrow-right ml-1"></i>
            </a>
        </div>

       <!-- <div class="bg-white shadow rounded-lg p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">
                <i class="fas fa-star text-yellow-500 mr-2"></i> Mis Reseñas
            </h3>
            <p class="text-gray-600 mb-4">Revisa tus opiniones sobre servicios</p>
            <a href="#" class="text-teal-600 hover:text-teal-700 font-medium">
                Ver todas <i class="fas fa-arrow-right ml-1"></i>
            </a>
        </div>  -->
    </div>
</main>

<script>
// Tab switching functionality
document.querySelectorAll('.profile-tab').forEach(button => {
    button.addEventListener('click', function() {
        const tabName = this.dataset.tab;
        
        // Update button styles
        document.querySelectorAll('.profile-tab').forEach(btn => {
            btn.classList.remove('border-teal-500', 'text-teal-600');
            btn.classList.add('border-transparent', 'text-gray-500');
        });
        this.classList.remove('border-transparent', 'text-gray-500');
        this.classList.add('border-teal-500', 'text-teal-600');
        
        // Show/hide content
        document.querySelectorAll('.tab-content').forEach(content => {
            content.classList.add('hidden');
        });
        document.getElementById('tab-' + tabName).classList.remove('hidden');
    });
});
</script>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
