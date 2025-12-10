<?php 
$pageTitle = 'Gestionar Cupones - TicoTrips';
require_once __DIR__ . '/../layouts/header.php'; 
?>

<main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="mb-6 flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Gestionar Cupones</h1>
            <p class="text-gray-600 mt-1">Crea y administra cupones de descuento para tus servicios</p>
        </div>
        <button onclick="showCreateCouponModal()" 
                class="bg-teal-600 text-white px-6 py-2 rounded-lg hover:bg-teal-700 transition">
            <i class="fas fa-plus mr-2"></i> Crear Cupón
        </button>
    </div>

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
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-mono font-semibold text-teal-600">
                                <?= htmlspecialchars($cupon['codigo_cupon']) ?>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                <span class="px-2 py-1 rounded text-xs font-semibold" 
                                      style="background-color: <?= $cupon['tipo_descuento'] === 'Porcentaje' ? '#dcfce7' : '#fef3c7' ?>; color: <?= $cupon['tipo_descuento'] === 'Porcentaje' ? '#166534' : '#b45309' ?>;">
                                    <?= htmlspecialchars($cupon['tipo_descuento']) ?>
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 font-bold">
                                <?php if ($cupon['tipo_descuento'] === 'Porcentaje'): ?>
                                    <span class="text-lg">-<?= $cupon['valor_descuento'] ?>%</span>
                                <?php else: ?>
                                    <span class="text-lg">-₡<?= number_format($cupon['valor_descuento'], 0) ?></span>
                                <?php endif; ?>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                <div class="text-xs">
                                    <strong>Inicio:</strong> <?= date('d/m', strtotime($cupon['fecha_inicio'])) ?><br>
                                    <strong>Fin:</strong> <?= date('d/m', strtotime($cupon['fecha_fin'])) ?>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 font-medium">
                                <?php if ($cupon['usos_restantes'] !== null): ?>
                                    <span class="px-2 py-1 rounded text-xs font-semibold bg-orange-100 text-orange-800">
                                        <?= $cupon['usos_restantes'] ?> restantes
                                    </span>
                                <?php else: ?>
                                    <span class="px-2 py-1 rounded text-xs font-semibold bg-green-100 text-green-800">
                                        Ilimitado
                                    </span>
                                <?php endif; ?>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <?php 
                                $es_activo = $cupon['id_estatus'] == 1 && strtotime($cupon['fecha_fin']) >= time();
                                ?>
                                <?php if ($es_activo): ?>
                                    <span class="px-3 py-1 text-xs font-bold rounded-full bg-green-100 text-green-800">
                                        <i class="fas fa-check-circle mr-1"></i> Activo
                                    </span>
                                <?php else: ?>
                                    <span class="px-3 py-1 text-xs font-bold rounded-full bg-gray-100 text-gray-800">
                                        <i class="fas fa-clock mr-1"></i> Vencido
                                    </span>
                                <?php endif; ?>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                                <button onclick="showEditCouponModal(<?= htmlspecialchars(json_encode($cupon)) ?>)" 
                                        class="text-blue-600 hover:text-blue-900 transition">
                                    <i class="fas fa-edit"></i> Editar
                                </button>
                                <button onclick="if(confirm('¿Eliminar este cupón?')) deleteCoupon(<?= $cupon['id_cupon'] ?>)" 
                                        class="text-red-600 hover:text-red-900 transition">
                                    <i class="fas fa-trash"></i> Eliminar
                                </button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="7" class="px-6 py-12 text-center">
                            <i class="fas fa-ticket-alt fa-4x text-gray-400 mb-4"></i>
                            <p class="text-gray-600 text-lg mb-2">No tienes cupones creados</p>
                            <p class="text-gray-500 mb-6">Comienza a ofrecer descuentos a tus clientes</p>
                            <button onclick="showCreateCouponModal()" 
                                    class="bg-teal-600 text-white px-6 py-2 rounded-lg hover:bg-teal-700 transition">
                                <i class="fas fa-plus mr-2"></i> Crear tu primer cupón
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
        <div class="flex justify-between items-center mb-6">
            <h3 class="text-2xl font-bold text-gray-900">Crear Nuevo Cupón</h3>
            <button onclick="closeCreateCouponModal()" class="text-gray-400 hover:text-gray-600">
                <i class="fas fa-times text-2xl"></i>
            </button>
        </div>
        <form method="POST" action="index.php?controller=business&action=createCoupon" class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Código del Cupón *</label>
                    <input type="text" name="codigo_cupon" required
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-teal-500"
                        placeholder="Ej: VERANO2024"
                        style="text-transform: uppercase;">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Tipo de Descuento *</label>
                    <select name="tipo_descuento" required onchange="updateDiscountLabel()"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-teal-500">
                        <option value="Porcentaje">Porcentaje (%)</option>
                        <option value="MontoFijo">Monto Fijo (₡)</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2" id="discountLabel">Valor del Descuento (%) *</label>
                    <input type="number" name="valor_descuento" required min="1" step="0.01"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-teal-500"
                        placeholder="Ej: 15">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Usos Permitidos (opcional)</label>
                    <input type="number" name="usos_restantes" min="1"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-teal-500"
                        placeholder="Dejar vacío = ilimitado">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Fecha de Inicio *</label>
                    <input type="date" name="fecha_inicio" required
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-teal-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Fecha de Finalización *</label>
                    <input type="date" name="fecha_fin" required
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-teal-500">
                </div>
            </div>
            <div class="flex justify-end gap-2 mt-6 pt-4 border-t">
                <button type="button" onclick="closeCreateCouponModal()"
                    class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400 transition">
                    Cancelar
                </button>
                <button type="submit"
                    class="px-4 py-2 bg-teal-600 text-white rounded-md hover:bg-teal-700 transition">
                    <i class="fas fa-save mr-2"></i> Crear Cupón
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Editar Cupón -->
<div id="editCouponModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 border w-full max-w-2xl shadow-lg rounded-md bg-white">
        <div class="flex justify-between items-center mb-6">
            <h3 class="text-2xl font-bold text-gray-900">Editar Cupón</h3>
            <button onclick="closeEditCouponModal()" class="text-gray-400 hover:text-gray-600">
                <i class="fas fa-times text-2xl"></i>
            </button>
        </div>
        <form method="POST" action="index.php?controller=business&action=updateCoupon" class="space-y-4">
            <input type="hidden" name="id_cupon" id="edit_id_cupon">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Código del Cupón *</label>
                    <input type="text" name="codigo_cupon" id="edit_codigo_cupon" required
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-teal-500"
                        style="text-transform: uppercase;">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Tipo de Descuento *</label>
                    <select name="tipo_descuento" id="edit_tipo_descuento" required onchange="updateEditDiscountLabel()"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-teal-500">
                        <option value="Porcentaje">Porcentaje (%)</option>
                        <option value="MontoFijo">Monto Fijo (₡)</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2" id="editDiscountLabel">Valor del Descuento (%) *</label>
                    <input type="number" name="valor_descuento" id="edit_valor_descuento" required min="1" step="0.01"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-teal-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Usos Permitidos (opcional)</label>
                    <input type="number" name="usos_restantes" id="edit_usos_restantes" min="1"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-teal-500"
                        placeholder="Dejar vacío = ilimitado">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Fecha de Inicio *</label>
                    <input type="date" name="fecha_inicio" id="edit_fecha_inicio" required
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-teal-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Fecha de Finalización *</label>
                    <input type="date" name="fecha_fin" id="edit_fecha_fin" required
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-teal-500">
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Estado *</label>
                    <select name="id_estatus" id="edit_id_estatus" required
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-teal-500">
                        <option value="1">Activo</option>
                        <option value="2">Pausado</option>
                        <option value="4">Inactivo</option>
                    </select>
                </div>
            </div>
            <div class="flex justify-end gap-2 mt-6 pt-4 border-t">
                <button type="button" onclick="closeEditCouponModal()"
                    class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400 transition">
                    Cancelar
                </button>
                <button type="submit"
                    class="px-4 py-2 bg-teal-600 text-white rounded-md hover:bg-teal-700 transition">
                    <i class="fas fa-save mr-2"></i> Guardar Cambios
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

function showEditCouponModal(cupon) {
    document.getElementById('edit_id_cupon').value = cupon.id_cupon;
    document.getElementById('edit_codigo_cupon').value = cupon.codigo_cupon;
    document.getElementById('edit_tipo_descuento').value = cupon.tipo_descuento;
    document.getElementById('edit_valor_descuento').value = cupon.valor_descuento;
    document.getElementById('edit_usos_restantes').value = cupon.usos_restantes || '';
    
    // Convertir fecha al formato YYYY-MM-DD
    document.getElementById('edit_fecha_inicio').value = cupon.fecha_inicio.split(' ')[0];
    document.getElementById('edit_fecha_fin').value = cupon.fecha_fin.split(' ')[0];
    
    document.getElementById('edit_id_estatus').value = cupon.id_estatus;
    updateEditDiscountLabel();
    document.getElementById('editCouponModal').classList.remove('hidden');
}

function closeEditCouponModal() {
    document.getElementById('editCouponModal').classList.add('hidden');
}

function deleteCoupon(couponId) {
    const form = document.createElement('form');
    form.method = 'POST';
    form.action = 'index.php?controller=business&action=deleteCoupon';
    form.innerHTML = '<input type="hidden" name="id_cupon" value="' + couponId + '">';
    document.body.appendChild(form);
    form.submit();
}

function updateDiscountLabel() {
    const type = document.querySelector('select[name="tipo_descuento"]').value;
    const label = document.getElementById('discountLabel');
    label.textContent = type === 'Porcentaje' ? 'Valor del Descuento (%) *' : 'Valor del Descuento (₡) *';
}

function updateEditDiscountLabel() {
    const type = document.getElementById('edit_tipo_descuento').value;
    const label = document.getElementById('editDiscountLabel');
    label.textContent = type === 'Porcentaje' ? 'Valor del Descuento (%) *' : 'Valor del Descuento (₡) *';
}

// Cerrar modal al presionar ESC
document.addEventListener('keydown', function(event) {
    if (event.key === 'Escape') {
        document.getElementById('createCouponModal').classList.add('hidden');
        document.getElementById('editCouponModal').classList.add('hidden');
    }
});

// Cerrar modales al hacer click fuera
document.getElementById('createCouponModal')?.addEventListener('click', function(e) {
    if (e.target === this) closeCreateCouponModal();
});

document.getElementById('editCouponModal')?.addEventListener('click', function(e) {
    if (e.target === this) closeEditCouponModal();
});

// Convertir código a mayúsculas automáticamente
document.addEventListener('input', function(e) {
    if (e.target.name === 'codigo_cupon' || e.target.id === 'edit_codigo_cupon') {
        e.target.value = e.target.value.toUpperCase();
    }
});
</script>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
