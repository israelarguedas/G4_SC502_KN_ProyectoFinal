<?php 
$pageTitle = 'Gestionar Servicios - TicoTrips';
require_once __DIR__ . '/../layouts/header.php'; 
?>

<main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="mb-6 flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Gestionar Servicios</h1>
            <p class="text-gray-600 mt-1">Administra todos los servicios de tu negocio</p>
        </div>
        <button onclick="showCreateServiceModal()" 
                class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition">
            <i class="fas fa-plus mr-2"></i> Crear Servicio
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

    <!-- Lista de servicios -->
    <div class="bg-white shadow-sm rounded-lg overflow-hidden">
        <?php if (!empty($servicios)): ?>
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nombre del Servicio</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Categoría</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Precio Base</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Descripción</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Estado</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Acciones</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <?php foreach ($servicios as $servicio): ?>
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-6 py-4 text-sm font-semibold text-gray-900">
                                <?= htmlspecialchars($servicio['nombre_servicio']) ?>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600">
                                <?= htmlspecialchars($servicio['nombre_categoria'] ?? 'Sin categoría') ?>
                            </td>
                            <td class="px-6 py-4 text-sm font-medium text-gray-900">
                                ₡<?= number_format($servicio['precio_base'], 2) ?>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600">
                                <span class="line-clamp-2">
                                    <?= htmlspecialchars(substr($servicio['descripcion'] ?? 'Sin descripción', 0, 60)) ?>...
                                </span>
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
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                                <button onclick="showEditServiceModal(<?= htmlspecialchars(json_encode($servicio)) ?>)" 
                                        class="text-blue-600 hover:text-blue-900 transition">
                                    <i class="fas fa-edit"></i> Editar
                                </button>
                                <button onclick="if(confirm('¿Estás seguro?')) deleteService(<?= $servicio['id_servicio'] ?>)" 
                                        class="text-red-600 hover:text-red-900 transition">
                                    <i class="fas fa-trash"></i> Eliminar
                                </button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <div class="px-6 py-12 text-center">
                <i class="fas fa-inbox fa-4x text-gray-400 mb-4"></i>
                <p class="text-gray-600 text-lg mb-2">No tienes servicios creados</p>
                <p class="text-gray-500 mb-6">Comienza creando tu primer servicio para que los clientes puedan reservar</p>
                <button onclick="showCreateServiceModal()" 
                        class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition">
                    <i class="fas fa-plus mr-2"></i> Crear Primer Servicio
                </button>
            </div>
        <?php endif; ?>
    </div>
</main>

<!-- Modal Crear Servicio -->
<div id="createServiceModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 border w-full max-w-2xl shadow-lg rounded-md bg-white">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-2xl font-bold">Crear Nuevo Servicio</h3>
            <button onclick="closeCreateServiceModal()" class="text-gray-400 hover:text-gray-600">
                <i class="fas fa-times text-2xl"></i>
            </button>
        </div>
        <form method="POST" action="index.php?controller=business&action=createService" class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Nombre del Servicio *</label>
                    <input type="text" name="nombre_servicio" required
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                        placeholder="Ej: Tour de Aventura">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Categoría</label>
                    <select name="id_categoria_fk"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">Selecciona una categoría</option>
                        <?php foreach ($categorias as $cat): ?>
                            <option value="<?= $cat['id_categoria'] ?>">
                                <?= htmlspecialchars($cat['nombre_categoria']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Precio Base (₡) *</label>
                    <input type="number" name="precio_base" step="0.01" min="0" required
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                        placeholder="0.00">
                </div>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Descripción *</label>
                <textarea name="descripcion" rows="4" required
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                    placeholder="Describe detalladamente el servicio que ofreces..."></textarea>
            </div>
            <div class="flex justify-end gap-2 mt-6 pt-4 border-t">
                <button type="button" onclick="closeCreateServiceModal()"
                    class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400 transition">
                    Cancelar
                </button>
                <button type="submit"
                    class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition">
                    <i class="fas fa-save mr-2"></i> Crear Servicio
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Editar Servicio -->
<div id="editServiceModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 border w-full max-w-2xl shadow-lg rounded-md bg-white">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-2xl font-bold">Editar Servicio</h3>
            <button onclick="closeEditServiceModal()" class="text-gray-400 hover:text-gray-600">
                <i class="fas fa-times text-2xl"></i>
            </button>
        </div>
        <form method="POST" action="index.php?controller=business&action=updateService" class="space-y-4">
            <input type="hidden" name="id_servicio" id="edit_id_servicio">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Nombre del Servicio *</label>
                    <input type="text" name="nombre_servicio" id="edit_nombre_servicio" required
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Categoría</label>
                    <select name="id_categoria_fk" id="edit_id_categoria_fk"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">Selecciona una categoría</option>
                        <?php foreach ($categorias as $cat): ?>
                            <option value="<?= $cat['id_categoria'] ?>">
                                <?= htmlspecialchars($cat['nombre_categoria']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Precio Base (₡) *</label>
                    <input type="number" name="precio_base" id="edit_precio_base" step="0.01" min="0" required
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Descripción *</label>
                <textarea name="descripcion" id="edit_descripcion" rows="4" required
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"></textarea>
            </div>
            <div class="flex justify-end gap-2 mt-6 pt-4 border-t">
                <button type="button" onclick="closeEditServiceModal()"
                    class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400 transition">
                    Cancelar
                </button>
                <button type="submit"
                    class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition">
                    <i class="fas fa-save mr-2"></i> Guardar Cambios
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function showCreateServiceModal() {
    document.getElementById('createServiceModal').classList.remove('hidden');
}

function closeCreateServiceModal() {
    document.getElementById('createServiceModal').classList.add('hidden');
}

function showEditServiceModal(servicio) {
    document.getElementById('edit_id_servicio').value = servicio.id_servicio;
    document.getElementById('edit_nombre_servicio').value = servicio.nombre_servicio;
    document.getElementById('edit_precio_base').value = servicio.precio_base;
    document.getElementById('edit_descripcion').value = servicio.descripcion;
    document.getElementById('edit_id_categoria_fk').value = servicio.id_categoria_fk || '';
    document.getElementById('editServiceModal').classList.remove('hidden');
}

function closeEditServiceModal() {
    document.getElementById('editServiceModal').classList.add('hidden');
}

function deleteService(servicioId) {
    const form = document.createElement('form');
    form.method = 'POST';
    form.action = 'index.php?controller=business&action=deleteService';
    form.innerHTML = '<input type="hidden" name="id_servicio" value="' + servicioId + '">';
    document.body.appendChild(form);
    form.submit();
}

// Cerrar modal al presionar ESC
document.addEventListener('keydown', function(event) {
    if (event.key === 'Escape') {
        document.getElementById('createServiceModal').classList.add('hidden');
        document.getElementById('editServiceModal').classList.add('hidden');
    }
});

// Cerrar modales al hacer click fuera
document.getElementById('createServiceModal')?.addEventListener('click', function(e) {
    if (e.target === this) closeCreateServiceModal();
});

document.getElementById('editServiceModal')?.addEventListener('click', function(e) {
    if (e.target === this) closeEditServiceModal();
});
</script>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
