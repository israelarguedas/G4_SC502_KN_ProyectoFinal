<?php 
$pageTitle = 'Mis Reservaciones - TicoTrips';
require_once __DIR__ . '/../layouts/header.php'; 
?>

<main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-gray-900">Mis Reservaciones</h1>
        <p class="mt-2 text-gray-600">Gestiona tus reservas activas y pasadas</p>
    </div>

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

    <!-- Filtros de Estado -->
    <div class="bg-white rounded-lg shadow-sm p-4 mb-6">
        <div class="flex gap-2">
            <button class="px-4 py-2 rounded-lg bg-teal-600 text-white font-medium">
                Todas
            </button>
            <button class="px-4 py-2 rounded-lg bg-gray-100 text-gray-700 hover:bg-gray-200">
                Pendientes
            </button>
            <button class="px-4 py-2 rounded-lg bg-gray-100 text-gray-700 hover:bg-gray-200">
                Confirmadas
            </button>
            <button class="px-4 py-2 rounded-lg bg-gray-100 text-gray-700 hover:bg-gray-200">
                Completadas
            </button>
            <button class="px-4 py-2 rounded-lg bg-gray-100 text-gray-700 hover:bg-gray-200">
                Canceladas
            </button>
        </div>
    </div>

    <!-- Lista de Reservaciones -->
    <div class="space-y-4">
        <?php if (!empty($reservations)): ?>
            <?php foreach ($reservations as $reservation): ?>
                <div class="bg-white rounded-lg shadow-sm overflow-hidden hover:shadow-md transition">
                    <div class="p-6">
                        <div class="flex flex-col md:flex-row md:items-center md:justify-between">
                            <div class="flex-1">
                                <div class="flex items-start gap-4">
                                    <img src="https://via.placeholder.com/100" alt="Servicio" 
                                         class="w-20 h-20 rounded-lg object-cover">
                                    <div class="flex-1">
                                        <h3 class="text-lg font-semibold text-gray-900">
                                            <?= htmlspecialchars($reservation['servicio_nombre'] ?? 'Servicio') ?>
                                        </h3>
                                        <p class="text-sm text-gray-600">
                                            Reserva #<?= htmlspecialchars($reservation['id_reserva']) ?>
                                        </p>
                                        <div class="mt-2 flex flex-wrap gap-3 text-sm text-gray-600">
                                            <span class="flex items-center">
                                                <i class="fas fa-calendar mr-1"></i>
                                                <?= date('d/m/Y', strtotime($reservation['fecha_reserva'])) ?>
                                            </span>
                                            <span class="flex items-center">
                                                <i class="fas fa-users mr-1"></i>
                                                <?= htmlspecialchars($reservation['cantidad_personas']) ?> personas
                                            </span>
                                            <span class="flex items-center">
                                                <i class="fas fa-money-bill mr-1"></i>
                                                ₡<?= number_format($reservation['monto_total'], 0) ?>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="mt-4 md:mt-0 md:ml-6 flex flex-col items-end gap-3">
                                <?php 
                                    $statusColors = [
                                        1 => 'bg-yellow-100 text-yellow-800',
                                        2 => 'bg-green-100 text-green-800',
                                        3 => 'bg-blue-100 text-blue-800',
                                        4 => 'bg-red-100 text-red-800'
                                    ];
                                    $statusNames = [
                                        1 => 'Pendiente',
                                        2 => 'Confirmada',
                                        3 => 'Completada',
                                        4 => 'Cancelada'
                                    ];
                                    $statusId = $reservation['id_estado'] ?? 1;
                                ?>
                                <span class="px-3 py-1 text-xs font-semibold rounded-full <?= $statusColors[$statusId] ?>">
                                    <?= $statusNames[$statusId] ?>
                                </span>
                                
                                <div class="flex gap-2">
                                    <a href="index.php?controller=reservation&action=details&id=<?= $reservation['id_reserva'] ?>"
                                       class="px-4 py-2 text-sm text-teal-600 hover:text-teal-700 font-medium">
                                        Ver Detalles
                                    </a>
                                    <?php if ($statusId == 1 || $statusId == 2): ?>
                                        <button onclick="cancelReservation(<?= $reservation['id_reserva'] ?>)"
                                                class="px-4 py-2 text-sm text-red-600 hover:text-red-700 font-medium">
                                            Cancelar
                                        </button>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="bg-white rounded-lg shadow-sm p-12 text-center">
                <i class="fas fa-calendar-times fa-4x text-gray-300 mb-4"></i>
                <h3 class="text-xl font-semibold text-gray-900 mb-2">No tienes reservaciones</h3>
                <p class="text-gray-600 mb-6">Comienza a explorar servicios y haz tu primera reserva</p>
                <a href="index.php?controller=reservation&action=index" 
                   class="inline-block px-6 py-3 bg-teal-600 text-white rounded-lg hover:bg-teal-700 transition">
                    Explorar Servicios
                </a>
            </div>
        <?php endif; ?>
    </div>
</main>

<script>
function cancelReservation(reservationId) {
    if (confirm('¿Estás seguro de que deseas cancelar esta reservación?')) {
        window.location.href = `index.php?controller=reservation&action=cancel&id=${reservationId}`;
    }
}
</script>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
