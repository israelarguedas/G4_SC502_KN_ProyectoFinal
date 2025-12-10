<?php 
$pageTitle = 'Panel de Administración - TicoTrips';
require_once __DIR__ . '/../layouts/header.php'; 
?>

<main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <h1 class="text-3xl font-extrabold text-gray-900 mb-6">Gestión Centralizada</h1>

    <?php if(isset($_SESSION['success'])): ?>
        <div class="mb-4 p-4 text-sm text-green-800 rounded-lg bg-green-50">
            <?= htmlspecialchars($_SESSION['success']) ?>
        </div>
        <?php unset($_SESSION['success']); ?>
    <?php endif; ?>

    <?php if(isset($_SESSION['error'])): ?>
        <div class="mb-4 p-4 text-sm text-red-800 rounded-lg bg-red-50">
            <?= htmlspecialchars($_SESSION['error']) ?>
        </div>
        <?php unset($_SESSION['error']); ?>
    <?php endif; ?>

    <div class="flex border-b border-gray-300 mb-8" id="admin-tab-container">
        <button data-tab="comercios"
            class="admin-tab flex-1 py-3 text-sm font-medium border-b-2 border-teal-600 text-teal-600 hover:text-teal-700 transition duration-150">
            <i class="fas fa-store mr-2"></i> Validación de Comercios
        </button>
        <button data-tab="reservaciones"
            class="admin-tab flex-1 py-3 text-sm font-medium border-b-2 border-transparent text-gray-500 hover:text-gray-700 transition duration-150">
            <i class="fas fa-book-open mr-2"></i> Reservaciones
        </button>
        <button data-tab="estadisticas"
            class="admin-tab flex-1 py-3 text-sm font-medium border-b-2 border-transparent text-gray-500 hover:text-gray-700 transition duration-150">
            <i class="fas fa-chart-line mr-2"></i> Estadísticas
        </button>
    </div>

    <!-- Tab Comercios -->
    <div id="tab-comercios" class="admin-content">
        <h2 class="text-2xl font-semibold text-gray-800 mb-4">Cola de Solicitudes Pendientes</h2>
        
        <div class="bg-white shadow-sm rounded-lg overflow-hidden">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nombre Negocio</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Categoría</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ubicación</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Fecha Solicitud</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Acciones</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <?php if (!empty($pendingBusinesses)): ?>
                        <?php foreach ($pendingBusinesses as $business): ?>
                            <tr class="hover:bg-yellow-50">
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                    #<?= htmlspecialchars($business['id_negocio']) ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    <?= htmlspecialchars($business['nombre_publico']) ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    <?= htmlspecialchars($business['nombre_categoria']) ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    <?= htmlspecialchars($business['canton'] . ', ' . $business['provincia']) ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    <?= date('d/m/Y', strtotime($business['fecha_solicitud'])) ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <form method="POST" action="index.php?controller=admin&action=approveBusiness" class="inline">
                                        <input type="hidden" name="business_id" value="<?= $business['id_negocio'] ?>">
                                        <button type="submit" class="text-green-600 hover:text-green-900 mr-3">
                                            <i class="fas fa-check"></i> Aprobar
                                        </button>
                                    </form>
                                    <button onclick="showRejectModal(<?= $business['id_negocio'] ?>)" 
                                            class="text-red-600 hover:text-red-900">
                                        <i class="fas fa-times"></i> Rechazar
                                    </button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6" class="px-6 py-4 text-center text-gray-500">
                                No hay solicitudes pendientes
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Tab Reservaciones -->
    <div id="tab-reservaciones" class="admin-content hidden">
        <h2 class="text-2xl font-semibold text-gray-800 mb-4">Resumen Global de Reservaciones</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
            <div class="bg-white shadow-sm rounded-lg p-5">
                <h3 class="text-lg font-semibold text-gray-700">Total Reservaciones (Anual)</h3>
                <p class="text-3xl font-bold text-teal-600 mt-2">
                    <?= number_format($reservationStats['anuales'] ?? 0) ?>
                </p>
            </div>
            <div class="bg-white shadow-sm rounded-lg p-5">
                <h3 class="text-lg font-semibold text-gray-700">Reservaciones Pendientes</h3>
                <p class="text-3xl font-bold text-yellow-600 mt-2">
                    <?= number_format($reservationStats['pendientes'] ?? 0) ?>
                </p>
            </div>
            <div class="bg-white shadow-sm rounded-lg p-5">
                <h3 class="text-lg font-semibold text-gray-700">Reservaciones Confirmadas</h3>
                <p class="text-3xl font-bold text-green-600 mt-2">
                    <?= number_format($reservationStats['confirmadas'] ?? 0) ?>
                </p>
            </div>
        </div>
    </div>

    <!-- Tab Estadísticas -->
    <div id="tab-estadisticas" class="admin-content hidden">
        <h2 class="text-2xl font-semibold text-gray-800 mb-4">Estadísticas Clave</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
            <div class="bg-white shadow-sm rounded-lg p-5">
                <h3 class="text-lg font-semibold text-gray-700">Usuarios Activos</h3>
                <p class="text-3xl font-bold text-teal-600 mt-2">
                    <?= number_format($statistics['total_usuarios'] ?? 0) ?>
                </p>
            </div>
            <div class="bg-white shadow-sm rounded-lg p-5">
                <h3 class="text-lg font-semibold text-gray-700">Negocios Activos</h3>
                <p class="text-3xl font-bold text-green-600 mt-2">
                    <?= number_format($statistics['negocios_activos'] ?? 0) ?>
                </p>
            </div>
        </div>
    </div>
</main>

<!-- Modal para rechazar -->
<div id="rejectModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <h3 class="text-lg font-bold mb-4">Rechazar Solicitud</h3>
        <form method="POST" action="index.php?controller=admin&action=rejectBusiness">
            <input type="hidden" name="business_id" id="reject_business_id">
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Motivo del rechazo:</label>
                <textarea name="reason" rows="4" required
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-teal-500"></textarea>
            </div>
            <div class="flex justify-end gap-2">
                <button type="button" onclick="closeRejectModal()"
                    class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400">
                    Cancelar
                </button>
                <button type="submit"
                    class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700">
                    Rechazar
                </button>
            </div>
        </form>
    </div>
</div>

<script>
// Tab switching
document.querySelectorAll('.admin-tab').forEach(button => {
    button.addEventListener('click', function() {
        const tabName = this.dataset.tab;
        
        // Update button styles
        document.querySelectorAll('.admin-tab').forEach(btn => {
            btn.classList.remove('border-teal-600', 'text-teal-600');
            btn.classList.add('border-transparent', 'text-gray-500');
        });
        this.classList.remove('border-transparent', 'text-gray-500');
        this.classList.add('border-teal-600', 'text-teal-600');
        
        // Show/hide content
        document.querySelectorAll('.admin-content').forEach(content => {
            content.classList.add('hidden');
        });
        document.getElementById('tab-' + tabName).classList.remove('hidden');
    });
});

function showRejectModal(businessId) {
    document.getElementById('reject_business_id').value = businessId;
    document.getElementById('rejectModal').classList.remove('hidden');
}

function closeRejectModal() {
    document.getElementById('rejectModal').classList.add('hidden');
}
</script>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
