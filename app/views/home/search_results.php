<?php 
$pageTitle = 'Resultados de Búsqueda - TicoTrips';
require_once __DIR__ . '/../layouts/header.php'; 
?>

<main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Barra de búsqueda -->
    <div class="bg-white rounded-lg shadow-sm p-6 mb-8">
        <h2 class="text-2xl font-bold text-gray-900 mb-4">Buscar Negocios</h2>
        <form action="index.php" method="GET" class="space-y-4">
            <input type="hidden" name="controller" value="home">
            <input type="hidden" name="action" value="search">
            
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Provincia</label>
                    <select name="provincia" id="provinciaSearch" 
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-teal-500">
                        <option value="">Todas</option>
                        <?php
                        $provincias = ['San José', 'Alajuela', 'Cartago', 'Heredia', 'Guanacaste', 'Puntarenas', 'Limón'];
                        foreach ($provincias as $prov): ?>
                            <option value="<?= $prov ?>" <?= ($_GET['provincia'] ?? '') == $prov ? 'selected' : '' ?>>
                                <?= $prov ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Cantón</label>
                    <input type="text" name="canton" value="<?= htmlspecialchars($_GET['canton'] ?? '') ?>"
                           placeholder="Cualquiera"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-teal-500">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Distrito</label>
                    <input type="text" name="distrito" value="<?= htmlspecialchars($_GET['distrito'] ?? '') ?>"
                           placeholder="Cualquiera"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-teal-500">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Categoría</label>
                    <select name="categoria" 
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-teal-500">
                        <option value="">Todas</option>
                        <option value="Hospedaje" <?= ($_GET['categoria'] ?? '') == 'Hospedaje' ? 'selected' : '' ?>>Hospedaje</option>
                        <option value="Tour / Experiencia" <?= ($_GET['categoria'] ?? '') == 'Tour / Experiencia' ? 'selected' : '' ?>>Tours</option>
                        <option value="Gastronomía" <?= ($_GET['categoria'] ?? '') == 'Gastronomía' ? 'selected' : '' ?>>Gastronomía</option>
                        <option value="Artesanías" <?= ($_GET['categoria'] ?? '') == 'Artesanías' ? 'selected' : '' ?>>Artesanías</option>
                    </select>
                </div>
            </div>
            
            <div class="flex justify-end">
                <button type="submit" 
                        class="px-6 py-2 bg-teal-600 text-white rounded-lg hover:bg-teal-700 transition">
                    <i class="fas fa-search mr-2"></i>
                    Buscar
                </button>
            </div>
        </form>
    </div>

    <!-- Resultados -->
    <div class="mb-6">
        <h2 class="text-2xl font-bold text-gray-900">
            Resultados de Búsqueda
            <?php if (!empty($results)): ?>
                <span class="text-teal-600">(<?= count($results) ?> encontrados)</span>
            <?php endif; ?>
        </h2>
    </div>

    <?php if (!empty($results)): ?>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <?php foreach ($results as $business): ?>
                <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-xl transition">
                    <div class="h-48 bg-gray-200">
                        <?php if (!empty($business['foto_portada'])): ?>
                            <img src="app/public/images/business/<?= htmlspecialchars($business['foto_portada']) ?>" 
                                 alt="<?= htmlspecialchars($business['nombre_publico']) ?>"
                                 class="w-full h-full object-cover">
                        <?php else: ?>
                            <div class="w-full h-full flex items-center justify-center text-gray-400">
                                <i class="fas fa-building fa-4x"></i>
                            </div>
                        <?php endif; ?>
                    </div>
                    
                    <div class="p-4">
                        <div class="flex items-start justify-between mb-2">
                            <h3 class="font-bold text-lg text-gray-900">
                                <?= htmlspecialchars($business['nombre_publico']) ?>
                            </h3>
                            <?php if (!empty($business['nombre_categoria'])): ?>
                                <span class="text-xs px-2 py-1 bg-teal-100 text-teal-800 rounded-full">
                                    <?= htmlspecialchars($business['nombre_categoria']) ?>
                                </span>
                            <?php endif; ?>
                        </div>
                        
                        <p class="text-gray-600 text-sm mb-2 flex items-center">
                            <i class="fas fa-map-marker-alt text-teal-500 mr-1"></i>
                            <?= htmlspecialchars($business['canton'] . ', ' . $business['provincia']) ?>
                        </p>
                        
                        <?php if (!empty($business['descripcion_corta'])): ?>
                            <p class="text-gray-600 text-sm mb-4 line-clamp-2">
                                <?= htmlspecialchars($business['descripcion_corta']) ?>
                            </p>
                        <?php endif; ?>
                        
                        <div class="flex items-center justify-between">
                            <a href="index.php?controller=business&action=view&id=<?= $business['id_negocio'] ?>" 
                               class="text-teal-600 hover:text-teal-700 font-semibold text-sm">
                                Ver más <i class="fas fa-arrow-right ml-1"></i>
                            </a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <div class="bg-white rounded-lg shadow-sm p-12 text-center">
            <i class="fas fa-search fa-4x text-gray-300 mb-4"></i>
            <h3 class="text-xl font-semibold text-gray-900 mb-2">No se encontraron resultados</h3>
            <p class="text-gray-600 mb-6">Intenta modificar los criterios de búsqueda</p>
            <a href="index.php" 
               class="inline-block px-6 py-3 bg-teal-600 text-white rounded-lg hover:bg-teal-700 transition">
                Volver al inicio
            </a>
        </div>
    <?php endif; ?>
</main>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
