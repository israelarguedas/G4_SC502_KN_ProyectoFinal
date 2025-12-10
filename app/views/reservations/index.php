<?php 
require_once __DIR__ . '/../layouts/header.php'; 
?>

    <div class="container mx-auto px-4 py-8">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-800 mb-2">Mis Reservaciones</h1>
            <p class="text-gray-600">Gestiona y revisa el estado de tus reservaciones</p>
        </div>

        <?php if (empty($reservations)): ?>
            <!-- Estado vacío -->
            <div class="bg-white rounded-lg shadow-md p-12 text-center">
                <div class="mb-4">
                    <i class="fas fa-calendar-times text-6xl text-gray-300"></i>
                </div>
                <h3 class="text-xl font-semibold text-gray-700 mb-2">No tienes reservaciones</h3>
                <p class="text-gray-500 mb-6">Explora nuestros servicios y crea tu primera reservación</p>
                <a href="index.php?controller=home&action=search" 
                   class="inline-block bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 transition">
                    <i class="fas fa-search mr-2"></i>Explorar Servicios
                </a>
            </div>
        <?php else: ?>
            <!-- Resumen de estadísticas -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <?php
                $totalReservations = count($reservations);
                $pendingCount = count(array_filter($reservations, fn($r) => $r['id_estatus'] == 1));
                $confirmedCount = count(array_filter($reservations, fn($r) => $r['id_estatus'] == 2));
                ?>
                <div class="bg-white rounded-lg shadow-md p-6">
                    <div class="flex items-center">
                        <div class="p-3 bg-blue-100 rounded-full">
                            <i class="fas fa-calendar-check text-2xl text-blue-600"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-gray-500 text-sm">Total Reservaciones</p>
                            <p class="text-2xl font-bold text-gray-800"><?php echo $totalReservations; ?></p>
                        </div>
                    </div>
                </div>
                <div class="bg-white rounded-lg shadow-md p-6">
                    <div class="flex items-center">
                        <div class="p-3 bg-yellow-100 rounded-full">
                            <i class="fas fa-clock text-2xl text-yellow-600"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-gray-500 text-sm">Pendientes</p>
                            <p class="text-2xl font-bold text-gray-800"><?php echo $pendingCount; ?></p>
                        </div>
                    </div>
                </div>
                <div class="bg-white rounded-lg shadow-md p-6">
                    <div class="flex items-center">
                        <div class="p-3 bg-green-100 rounded-full">
                            <i class="fas fa-check-circle text-2xl text-green-600"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-gray-500 text-sm">Confirmadas</p>
                            <p class="text-2xl font-bold text-gray-800"><?php echo $confirmedCount; ?></p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Lista de reservaciones -->
            <div class="space-y-4">
                <?php foreach ($reservations as $reservation): ?>
                    <?php
                    // Determinar color del estado
                    $statusColors = [
                        1 => ['bg' => 'bg-yellow-100', 'text' => 'text-yellow-800', 'icon' => 'fa-clock'],
                        2 => ['bg' => 'bg-green-100', 'text' => 'text-green-800', 'icon' => 'fa-check-circle'],
                        3 => ['bg' => 'bg-red-100', 'text' => 'text-red-800', 'icon' => 'fa-times-circle'],
                        4 => ['bg' => 'bg-blue-100', 'text' => 'text-blue-800', 'icon' => 'fa-check-double']
                    ];
                    $statusColor = $statusColors[$reservation['id_estatus']] ?? ['bg' => 'bg-gray-100', 'text' => 'text-gray-800', 'icon' => 'fa-info-circle'];
                    
                    // Formatear fecha
                    $fechaReserva = new DateTime($reservation['fecha_reserva']);
                    $fechaFormateada = $fechaReserva->format('d/m/Y');
                    ?>
                    <div class="bg-white rounded-lg shadow-md hover:shadow-lg transition-shadow overflow-hidden">
                        <div class="p-6">
                            <div class="flex flex-col md:flex-row md:items-center md:justify-between">
                                <!-- Información principal -->
                                <div class="flex-1">
                                    <div class="flex items-start mb-3">
                                        <div class="flex-1">
                                            <h3 class="text-xl font-semibold text-gray-800 mb-1">
                                                <?php echo htmlspecialchars($reservation['titulo']); ?>
                                            </h3>
                                            <p class="text-gray-600 flex items-center mb-2">
                                                <i class="fas fa-store mr-2 text-blue-600"></i>
                                                <?php echo htmlspecialchars($reservation['nombre_negocio']); ?>
                                            </p>
                                        </div>
                                        <span class="<?php echo $statusColor['bg']; ?> <?php echo $statusColor['text']; ?> px-3 py-1 rounded-full text-sm font-medium ml-4">
                                            <i class="fas <?php echo $statusColor['icon']; ?> mr-1"></i>
                                            <?php echo htmlspecialchars($reservation['nombre_estatus']); ?>
                                        </span>
                                    </div>
                                    
                                    <!-- Detalles de la reservación -->
                                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-sm">
                                        <div class="flex items-center text-gray-600">
                                            <i class="fas fa-calendar mr-2 text-blue-500"></i>
                                            <span><strong>Fecha:</strong> <?php echo $fechaFormateada; ?></span>
                                        </div>
                                        <div class="flex items-center text-gray-600">
                                            <i class="fas fa-users mr-2 text-green-500"></i>
                                            <span><strong>Personas:</strong> <?php echo $reservation['cantidad_personas']; ?></span>
                                        </div>
                                        <div class="flex items-center text-gray-600">
                                            <i class="fas fa-dollar-sign mr-2 text-yellow-500"></i>
                                            <span><strong>Total:</strong> ₡<?php echo number_format($reservation['total_pagar'], 0); ?></span>
                                        </div>
                                        <div class="flex items-center text-gray-600">
                                            <i class="fas fa-map-marker-alt mr-2 text-red-500"></i>
                                            <span><?php echo htmlspecialchars($reservation['canton']); ?>, <?php echo htmlspecialchars($reservation['provincia']); ?></span>
                                        </div>
                                    </div>
                                    
                                    <!-- Categoría -->
                                    <div class="mt-3">
                                        <span class="inline-block bg-gray-100 text-gray-700 px-3 py-1 rounded text-xs font-medium">
                                            <i class="fas fa-tag mr-1"></i>
                                            <?php echo htmlspecialchars($reservation['nombre_categoria']); ?>
                                        </span>
                                    </div>
                                </div>
                                
                                <!-- Acciones -->
                                <div class="mt-4 md:mt-0 md:ml-6 flex flex-col space-y-2">
                                    <a href="index.php?controller=reservation&action=details&id=<?php echo $reservation['id_reserva']; ?>" 
                                       class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition text-center text-sm">
                                        <i class="fas fa-eye mr-1"></i> Ver Detalles
                                    </a>
                                    <?php if ($reservation['id_estatus'] == 1 || $reservation['id_estatus'] == 2): ?>
                                        <button onclick="confirmCancel(<?php echo $reservation['id_reserva']; ?>)" 
                                                class="bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700 transition text-center text-sm">
                                            <i class="fas fa-times mr-1"></i> Cancelar
                                        </button>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>

    <script>
        function confirmCancel(reservationId) {
            if (confirm('¿Estás seguro de que deseas cancelar esta reservación?')) {
                window.location.href = `index.php?controller=reservation&action=cancel&id=${reservationId}`;
            }
        }
    </script>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
