<?php 
$pageTitle = 'Resultados de Búsqueda - TicoTrips';
require_once __DIR__ . '/../layouts/header.php'; 

$provincia = htmlspecialchars($_GET['provincia'] ?? '');
$canton = htmlspecialchars($_GET['canton'] ?? '');
$distrito = htmlspecialchars($_GET['distrito'] ?? '');
$categoria = htmlspecialchars($_GET['categoria'] ?? '');
$query = htmlspecialchars($_GET['q'] ?? '');
?>

<main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Search Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900 mb-4">Resultados de Búsqueda</h1>
        
        <?php if ($provincia || $canton || $distrito || $query): ?>
            <div class="flex flex-wrap gap-2 items-center">
                <span class="text-gray-600">Filtros activos:</span>
                <?php if ($provincia): ?>
                    <span class="px-3 py-1 bg-teal-100 text-teal-800 rounded-full text-sm">
                        <?= $provincia ?>
                    </span>
                <?php endif; ?>
                <?php if ($canton): ?>
                    <span class="px-3 py-1 bg-teal-100 text-teal-800 rounded-full text-sm">
                        <?= $canton ?>
                    </span>
                <?php endif; ?>
                <?php if ($distrito): ?>
                    <span class="px-3 py-1 bg-teal-100 text-teal-800 rounded-full text-sm">
                        <?= $distrito ?>
                    </span>
                <?php endif; ?>
                <?php if ($query): ?>
                    <span class="px-3 py-1 bg-teal-100 text-teal-800 rounded-full text-sm">
                        "<?= $query ?>"
                    </span>
                <?php endif; ?>
                <a href="index.php?controller=search&action=index" class="text-teal-600 hover:text-teal-700 text-sm ml-2">
                    Limpiar filtros
                </a>
            </div>
        <?php endif; ?>
    </div>

    <!-- Search Form -->
    <div class="bg-white rounded-lg shadow-sm p-6 mb-8">
        <form action="index.php" method="GET" class="space-y-4">
            <input type="hidden" name="controller" value="search">
            <input type="hidden" name="action" value="index">
            
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Búsqueda</label>
                    <input type="text" name="q" value="<?= $query ?>" 
                           placeholder="Tour, restaurante, hotel..."
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Provincia</label>
                    <select name="provincia" id="provinciaSelect"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500">
                        <option value="">Todas</option>
                    </select>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Cantón</label>
                    <select name="canton" id="cantonSelect" disabled
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500">
                        <option value="">Todos</option>
                    </select>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Distrito</label>
                    <select name="distrito" id="distritoSelect" disabled
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500">
                        <option value="">Todos</option>
                    </select>
                </div>
            </div>
            
            <div class="flex justify-end">
                <button type="submit" 
                        class="px-6 py-2 bg-teal-600 text-white rounded-lg hover:bg-teal-700 transition font-semibold">
                    <i class="fas fa-search mr-2"></i>Buscar
                </button>
            </div>
        </form>
    </div>

    <!-- Results -->
    <div class="mb-4">
        <p class="text-gray-600">
            Se encontraron <strong><?= count($results) ?></strong> resultados
        </p>
    </div>

    <?php if (!empty($results)): ?>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <?php foreach ($results as $business): ?>
                <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-xl transition">
                    <div class="h-48 bg-gray-200 overflow-hidden">
                        <?php if (!empty($business['ruta_logo'])): ?>
                            <img src="<?= htmlspecialchars($business['ruta_logo']) ?>" 
                                 alt="<?= htmlspecialchars($business['nombre_publico']) ?>"
                                 class="w-full h-full object-cover">
                        <?php else: ?>
                            <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-teal-400 to-teal-600">
                                <i class="fas fa-building fa-3x text-white"></i>
                            </div>
                        <?php endif; ?>
                    </div>
                    
                    <div class="p-4">
                        <div class="flex justify-between items-start mb-2">
                            <h3 class="font-bold text-lg text-gray-900">
                                <?= htmlspecialchars($business['nombre_publico']) ?>
                            </h3>
                            <span class="px-2 py-1 text-xs bg-teal-100 text-teal-800 rounded-full">
                                <?= htmlspecialchars($business['nombre_categoria']) ?>
                            </span>
                        </div>
                        
                        <p class="text-sm text-gray-600 mb-2">
                            <i class="fas fa-map-marker-alt text-teal-500"></i>
                            <?= htmlspecialchars($business['distrito']) ?>, <?= htmlspecialchars($business['canton']) ?>
                        </p>
                        
                        <?php if (!empty($business['descripcion_corta'])): ?>
                            <p class="text-sm text-gray-600 mb-3 line-clamp-2">
                                <?= htmlspecialchars($business['descripcion_corta']) ?>
                            </p>
                        <?php endif; ?>
                        
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <?php 
                                $rating = round($business['rating'], 1);
                                $fullStars = floor($rating);
                                $hasHalfStar = ($rating - $fullStars) >= 0.5;
                                ?>
                                <div class="text-yellow-400 mr-1">
                                    <?php for ($i = 0; $i < $fullStars; $i++): ?>
                                        <i class="fas fa-star"></i>
                                    <?php endfor; ?>
                                    <?php if ($hasHalfStar): ?>
                                        <i class="fas fa-star-half-alt"></i>
                                    <?php endif; ?>
                                    <?php for ($i = $fullStars + ($hasHalfStar ? 1 : 0); $i < 5; $i++): ?>
                                        <i class="far fa-star"></i>
                                    <?php endfor; ?>
                                </div>
                                <span class="text-sm text-gray-600">
                                    (<?= $business['num_reviews'] ?>)
                                </span>
                            </div>
                            
                            <button class="text-teal-600 hover:text-teal-700 font-semibold text-sm">
                                Ver más <i class="fas fa-arrow-right ml-1"></i>
                            </button>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <div class="bg-white rounded-lg shadow-sm p-12 text-center">
            <i class="fas fa-search fa-4x text-gray-300 mb-4"></i>
            <h3 class="text-xl font-semibold text-gray-900 mb-2">No se encontraron resultados</h3>
            <p class="text-gray-600 mb-6">Intenta modificar tus criterios de búsqueda</p>
            <a href="index.php" class="inline-block px-6 py-3 bg-teal-600 text-white rounded-lg hover:bg-teal-700 transition">
                Volver al inicio
            </a>
        </div>
    <?php endif; ?>
</main>

<script>
// Load geographic data via AJAX from MVC backend
document.addEventListener('DOMContentLoaded', function() {
    const provinciaSelect = document.getElementById('provinciaSelect');
    const cantonSelect = document.getElementById('cantonSelect');
    const distritoSelect = document.getElementById('distritoSelect');
    
    const currentProvincia = '<?= $provincia ?>';
    const currentCanton = '<?= $canton ?>';
    const currentDistrito = '<?= $distrito ?>';
    
    // Load provinces
    fetch('index.php?controller=search&action=getProvincias')
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                data.data.forEach(provincia => {
                    const option = document.createElement('option');
                    option.value = provincia;
                    option.textContent = provincia;
                    if (provincia === currentProvincia) option.selected = true;
                    provinciaSelect.appendChild(option);
                });
                
                if (currentProvincia) {
                    loadCantones(currentProvincia);
                }
            }
        });
    
    // Province change handler
    provinciaSelect.addEventListener('change', function() {
        cantonSelect.innerHTML = '<option value="">Todos</option>';
        distritoSelect.innerHTML = '<option value="">Todos</option>';
        cantonSelect.disabled = !this.value;
        distritoSelect.disabled = true;
        
        if (this.value) {
            loadCantones(this.value);
        }
    });
    
    // Canton change handler
    cantonSelect.addEventListener('change', function() {
        distritoSelect.innerHTML = '<option value="">Todos</option>';
        distritoSelect.disabled = !this.value;
        
        if (this.value && provinciaSelect.value) {
            loadDistritos(provinciaSelect.value, this.value);
        }
    });
    
    function loadCantones(provincia) {
        fetch(`index.php?controller=search&action=getCantones&provincia=${encodeURIComponent(provincia)}`)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    data.data.forEach(canton => {
                        const option = document.createElement('option');
                        option.value = canton;
                        option.textContent = canton;
                        if (canton === currentCanton) option.selected = true;
                        cantonSelect.appendChild(option);
                    });
                    cantonSelect.disabled = false;
                    
                    if (currentCanton) {
                        loadDistritos(provincia, currentCanton);
                    }
                }
            });
    }
    
    function loadDistritos(provincia, canton) {
        fetch(`index.php?controller=search&action=getDistritos&provincia=${encodeURIComponent(provincia)}&canton=${encodeURIComponent(canton)}`)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    data.data.forEach(distrito => {
                        const option = document.createElement('option');
                        option.value = distrito;
                        option.textContent = distrito;
                        if (distrito === currentDistrito) option.selected = true;
                        distritoSelect.appendChild(option);
                    });
                    distritoSelect.disabled = false;
                }
            });
    }
});
</script>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
