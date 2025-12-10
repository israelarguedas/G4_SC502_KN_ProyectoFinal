<?php 
$pageTitle = 'Panel de Dueño - TicoTrips';
require_once __DIR__ . '/../layouts/header.php'; 
?>

<main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Encabezado -->
    <div class="mb-8 flex justify-between items-center">
        <div>
            <h1 class="text-4xl font-bold text-gray-900"><?= htmlspecialchars($business['nombre_publico']) ?></h1>
            <p class="text-gray-600 mt-2">Panel de Control - Negocio</p>
        </div>
        <div class="space-x-3">
            <a href="index.php?controller=business&action=manageServices" 
               class="inline-block bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition">
                <i class="fas fa-cogs mr-2"></i> Servicios
            </a>
            <a href="index.php?controller=business&action=manageCoupons" 
               class="inline-block bg-teal-600 text-white px-6 py-2 rounded-lg hover:bg-teal-700 transition">
                <i class="fas fa-ticket-alt mr-2"></i> Cupones
            </a>
        </div>
    </div>

    <!-- Alertas -->
    <?php if(isset($_SESSION['success'])): ?>
        <div class="mb-4 p-4 text-sm text-green-800 rounded-lg bg-green-50 border border-green-200">
            <i class="fas fa-check-circle mr-2"></i> <?= htmlspecialchars($_SESSION['success']) ?>
        </div>
        <?php unset($_SESSION['success']); ?>
    <?php endif; ?>

    <?php if(isset($_SESSION['error'])): ?>
        <div class="mb-4 p-4 text-sm text-red-800 rounded-lg bg-red-50 border border-red-200">
            <i class="fas fa-exclamation-circle mr-2"></i> <?= htmlspecialchars($_SESSION['error']) ?>
        </div>
        <?php unset($_SESSION['error']); ?>
    <?php endif; ?>

    <!-- Tarjetas de Estadísticas -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4 mb-8">
        <!-- Servicios Activos -->
        <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-blue-500">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-gray-500 text-sm uppercase tracking-wider">Servicios Activos</p>
                    <p class="text-3xl font-bold text-gray-900 mt-2"><?= $stats['total_servicios'] ?? 0 ?></p>
                </div>
                <div class="bg-blue-100 rounded-full p-3">
                    <i class="fas fa-cube text-blue-600 text-xl"></i>
                </div>
            </div>
        </div>

        <!-- Cupones Activos -->
        <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-teal-500">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-gray-500 text-sm uppercase tracking-wider">Cupones Activos</p>
                    <p class="text-3xl font-bold text-gray-900 mt-2"><?= $stats['cupones_activos'] ?? 0 ?></p>
                </div>
                <div class="bg-teal-100 rounded-full p-3">
                    <i class="fas fa-ticket-alt text-teal-600 text-xl"></i>
                </div>
            </div>
        </div>

        <!-- Total Reservas -->
        <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-purple-500">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-gray-500 text-sm uppercase tracking-wider">Total Reservas</p>
                    <p class="text-3xl font-bold text-gray-900 mt-2"><?= $stats['total_reservas'] ?? 0 ?></p>
                </div>
                <div class="bg-purple-100 rounded-full p-3">
                    <i class="fas fa-calendar-check text-purple-600 text-xl"></i>
                </div>
            </div>
        </div>

        <!-- Próximas Reservas -->
        <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-orange-500">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-gray-500 text-sm uppercase tracking-wider">Próx. 7 días</p>
                    <p class="text-3xl font-bold text-gray-900 mt-2"><?= $stats['proximas_reservas'] ?? 0 ?></p>
                </div>
                <div class="bg-orange-100 rounded-full p-3">
                    <i class="fas fa-clock text-orange-600 text-xl"></i>
                </div>
            </div>
        </div>

        <!-- Ingresos Totales -->
        <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-green-500">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-gray-500 text-sm uppercase tracking-wider">Ingresos Totales</p>
                    <p class="text-3xl font-bold text-gray-900 mt-2">₡<?= number_format($stats['ingresos_totales'] ?? 0, 0) ?></p>
                </div>
                <div class="bg-green-100 rounded-full p-3">
                    <i class="fas fa-money-bill-wave text-green-600 text-xl"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Servicios Recientes (col 1-2) -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-lg shadow-md">
                <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
                    <h2 class="text-xl font-bold text-gray-900">Servicios Activos</h2>
                    <a href="index.php?controller=business&action=manageServices" 
                       class="text-blue-600 hover:text-blue-700 text-sm font-semibold">
                        Ver todos →
                    </a>
                </div>
                <div class="overflow-hidden">
                    <?php if (!empty($servicios)): ?>
                        <table class="min-w-full">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Servicio</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Precio</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Estado</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                <?php foreach (array_slice($servicios, 0, 5) as $servicio): ?>
                                    <tr class="hover:bg-gray-50 transition">
                                        <td class="px-6 py-4 text-sm font-medium text-gray-900">
                                            <?= htmlspecialchars($servicio['nombre_servicio']) ?>
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-600">
                                            ₡<?= number_format($servicio['precio_base'], 2) ?>
                                        </td>
                                        <td class="px-6 py-4 text-sm">
                                            <?php if ($servicio['id_estatus'] == 1): ?>
                                                <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                                    Activo
                                                </span>
                                            <?php else: ?>
                                                <span class="px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800">
                                                    Inactivo
                                                </span>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    <?php else: ?>
                        <div class="px-6 py-12 text-center text-gray-500">
                            <i class="fas fa-inbox fa-3x mb-3"></i>
                            <p class="mt-2">No tienes servicios creados aún</p>
                            <a href="index.php?controller=business&action=manageServices" 
                               class="text-blue-600 hover:text-blue-700 font-semibold mt-2 inline-block">
                                Crear servicios →
                            </a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Cupones Activos (col 3) -->
        <div>
            <div class="bg-white rounded-lg shadow-md">
                <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
                    <h2 class="text-xl font-bold text-gray-900">Cupones Activos</h2>
                    <a href="index.php?controller=business&action=manageCoupons" 
                       class="text-teal-600 hover:text-teal-700 text-sm font-semibold">
                        Ver todos →
                    </a>
                </div>
                <div class="overflow-hidden">
                    <?php if (!empty($cupones)): ?>
                        <div class="divide-y divide-gray-200">
                            <?php foreach (array_slice($cupones, 0, 4) as $cupon): ?>
                                <?php if ($cupon['id_estatus'] == 1 && strtotime($cupon['fecha_fin']) >= time()): ?>
                                    <div class="px-6 py-4 hover:bg-gray-50 transition">
                                        <div class="flex justify-between items-start">
                                            <div class="flex-1">
                                                <p class="font-mono font-bold text-teal-600 text-sm">
                                                    <?= htmlspecialchars($cupon['codigo_cupon']) ?>
                                                </p>
                                                <p class="text-xs text-gray-500 mt-1">
                                                    <?php if ($cupon['tipo_descuento'] === 'Porcentaje'): ?>
                                                        <?= $cupon['valor_descuento'] ?>% de descuento
                                                    <?php else: ?>
                                                        ₡<?= number_format($cupon['valor_descuento'], 0) ?> de descuento
                                                    <?php endif; ?>
                                                </p>
                                                <p class="text-xs text-gray-400 mt-1">
                                                    Vence: <?= date('d/m/Y', strtotime($cupon['fecha_fin'])) ?>
                                                </p>
                                            </div>
                                            <?php if ($cupon['usos_restantes'] !== null): ?>
                                                <span class="bg-orange-100 text-orange-800 text-xs font-semibold px-2 py-1 rounded">
                                                    <?= $cupon['usos_restantes'] ?> usos
                                                </span>
                                            <?php else: ?>
                                                <span class="bg-green-100 text-green-800 text-xs font-semibold px-2 py-1 rounded">
                                                    Ilimitado
                                                </span>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </div>
                    <?php else: ?>
                        <div class="px-6 py-8 text-center text-gray-500">
                            <i class="fas fa-ticket-alt fa-2x mb-2"></i>
                            <p class="text-sm mt-2">Sin cupones activos</p>
                            <a href="index.php?controller=business&action=manageCoupons" 
                               class="text-teal-600 hover:text-teal-700 font-semibold mt-2 inline-block text-sm">
                                Crear cupón →
                            </a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Reservas Recientes -->
    <div class="mt-8">
        <div class="bg-white rounded-lg shadow-md">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-xl font-bold text-gray-900">Reservas Recientes</h2>
            </div>
            <div class="overflow-hidden">
                <?php if (!empty($reservas_recientes)): ?>
                    <table class="min-w-full">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Cliente</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Servicio</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Fecha</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Monto</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Estado</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            <?php foreach ($reservas_recientes as $reserva): ?>
                                <tr class="hover:bg-gray-50 transition">
                                    <td class="px-6 py-4 text-sm font-medium text-gray-900">
                                        <?= htmlspecialchars($reserva['nombre']) ?>
                                        <br>
                                        <span class="text-xs text-gray-500"><?= htmlspecialchars($reserva['correo']) ?></span>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-600">
                                        <?= htmlspecialchars($reserva['nombre_servicio']) ?>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-600">
                                        <?= date('d/m/Y', strtotime($reserva['fecha_reserva'])) ?>
                                    </td>
                                    <td class="px-6 py-4 text-sm font-semibold text-gray-900">
                                        ₡<?= number_format($reserva['monto_total'], 2) ?>
                                    </td>
                                    <td class="px-6 py-4 text-sm">
                                        <?php 
                                        $estados = [
                                            1 => ['text' => 'Confirmada', 'color' => 'green'],
                                            2 => ['text' => 'Pendiente', 'color' => 'yellow'],
                                            3 => ['text' => 'Cancelada', 'color' => 'red'],
                                            4 => ['text' => 'Completada', 'color' => 'blue']
                                        ];
                                        $estado = $estados[$reserva['id_estatus']] ?? ['text' => 'Desconocido', 'color' => 'gray'];
                                        ?>
                                        <span class="px-2 py-1 text-xs font-semibold rounded-full bg-<?= $estado['color'] ?>-100 text-<?= $estado['color'] ?>-800">
                                            <?= $estado['text'] ?>
                                        </span>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <div class="px-6 py-12 text-center text-gray-500">
                        <i class="fas fa-calendar-times fa-3x mb-3"></i>
                        <p>No hay reservas aún</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Información del Negocio -->
    <div class="mt-8 bg-white rounded-lg shadow-md p-6">
        <h2 class="text-xl font-bold text-gray-900 mb-4">Información del Negocio</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <p class="text-xs uppercase text-gray-500 font-semibold">Nombre Legal</p>
                <p class="text-gray-900 font-semibold mt-1"><?= htmlspecialchars($business['nombre_legal']) ?></p>
            </div>
            <div>
                <p class="text-xs uppercase text-gray-500 font-semibold">Categoría</p>
                <p class="text-gray-900 font-semibold mt-1"><?= htmlspecialchars($business['nombre_categoria'] ?? 'N/A') ?></p>
            </div>
            <div>
                <p class="text-xs uppercase text-gray-500 font-semibold">Teléfono de Contacto</p>
                <p class="text-gray-900 font-semibold mt-1"><?= htmlspecialchars($business['telefono_contacto']) ?></p>
            </div>
            <div>
                <p class="text-xs uppercase text-gray-500 font-semibold">Email de Contacto</p>
                <p class="text-gray-900 font-semibold mt-1"><?= htmlspecialchars($business['correo_contacto']) ?></p>
            </div>
            <div class="md:col-span-2">
                <p class="text-xs uppercase text-gray-500 font-semibold">Descripción</p>
                <p class="text-gray-900 mt-1"><?= htmlspecialchars($business['descripcion_corta']) ?></p>
            </div>
            <div class="md:col-span-2">
                <p class="text-xs uppercase text-gray-500 font-semibold">Dirección</p>
                <p class="text-gray-900 mt-1">
                    <?= htmlspecialchars($business['direccion_exacta']) ?><br>
                    <span class="text-sm"><?= htmlspecialchars($business['distrito']) ?>, <?= htmlspecialchars($business['canton']) ?>, <?= htmlspecialchars($business['provincia']) ?></span>
                </p>
            </div>
        </div>
    </div>
</main>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
