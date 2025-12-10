<?php 
$pageTitle = 'Gestionar Cupones - TicoTrips';
require_once __DIR__ . '/../layouts/header.php'; 
?>

<main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="mb-6 flex justify-between items-center">
        <h1 class="text-3xl font-bold text-gray-900">Mis Cupones</h1>
        <button onclick="showCreateCouponModal()" 
                class="bg-teal-600 text-white px-6 py-2 rounded-lg hover:bg-teal-700 transition">
            <i class="fas fa-plus mr-2"></i> Crear Cupón
        </button>
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

    <!-- Lista de cupones -->
    <div class="bg-white shadow-sm rounded-lg overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Código</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tipo</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Descuento</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Vigencia</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Usos</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Estado</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Acciones</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                <?php if (!empty($cupones)): ?>
                    <?php foreach ($cupones as $cupon): ?>
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-mono font-semibold text-gray-900">
                                <?= htmlspecialchars($cupon['codigo_cupon']) ?>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                <?= htmlspecialchars($cupon['tipo_descuento']) ?>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 font-semibold">
                                <?php if ($cupon['tipo_descuento'] === 'Porcentaje'): ?>
                                    <?= $cupon['valor_descuento'] ?>%
                                <?php else: ?>
                                    ₡<?= number_format($cupon['valor_descuento'], 0) ?>
                                <?php endif; ?>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                <?= date('d/m/Y', strtotime($cupon['fecha_inicio'])) ?><br>
                                <?= date('d/m/Y', strtotime($cupon['fecha_fin'])) ?>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                <?= $cupon['usos_restantes'] ?? 'Ilimitado' ?>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <?php if ($cupon['id_estatus'] == 1): ?>
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                        Activo
                                    </span>
                                <?php else: ?>
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800">
                                        Inactivo
                                    </span>
                                <?php endif; ?>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <button class="text-teal-600 hover:text-teal-900 mr-3">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button class="text-red-600 hover:text-red-900">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="7" class="px-6 py-8 text-center text-gray-500">
                            <i class="fas fa-ticket-alt fa-3x mb-3"></i>
                            <p>No tienes cupones creados</p>
                            <button onclick="showCreateCouponModal()" 
                                    class="mt-4 text-teal-600 hover:text-teal-700 font-semibold">
                                Crear tu primer cupón
                            </button>
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</main>

<!-- Modal Crear Cupón -->
<div id="createCouponModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 border w-full max-w-2xl shadow-lg rounded-md bg-white">
        <h3 class="text-2xl font-bold mb-6">Crear Nuevo Cupón</h3>
        <form method="POST" action="index.php?controller=business&action=createCoupon" class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Código del Cupón</label>
                    <input type="text" name="codigo_cupon" required
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-teal-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Tipo de Descuento</label>
                    <select name="tipo_descuento" required
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-teal-500">
                        <option value="Porcentaje">Porcentaje</option>
                        <option value="MontoFijo">Monto Fijo</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Valor del Descuento</label>
                    <input type="number" name="valor_descuento" required min="1"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-teal-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Usos Permitidos (opcional)</label>
                    <input type="number" name="usos_restantes" min="1"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-teal-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Fecha Inicio</label>
                    <input type="date" name="fecha_inicio" required
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-teal-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Fecha Fin</label>
                    <input type="date" name="fecha_fin" required
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-teal-500">
                </div>
            </div>
            <div class="flex justify-end gap-2 mt-6">
                <button type="button" onclick="closeCreateCouponModal()"
                    class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400">
                    Cancelar
                </button>
                <button type="submit"
                    class="px-4 py-2 bg-teal-600 text-white rounded-md hover:bg-teal-700">
                    Crear Cupón
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function showCreateCouponModal() {
    document.getElementById('createCouponModal').classList.remove('hidden');
}

function closeCreateCouponModal() {
    document.getElementById('createCouponModal').classList.add('hidden');
}
</script>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
