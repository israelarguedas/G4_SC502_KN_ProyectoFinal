<?php 
$pageTitle = htmlspecialchars($business['nombre_publico']) . ' - TicoTrips';
require_once __DIR__ . '/../layouts/header.php'; 
?>

<main class="bg-gray-50 min-h-screen py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Back Button -->
        <div class="mb-6">
            <a href="index.php?controller=home&action=search" 
               class="inline-flex items-center text-teal-600 hover:text-teal-700 font-medium">
                <i class="fas fa-arrow-left mr-2"></i>
                Volver a la búsqueda
            </a>
        </div>

        <!-- Business Header -->
        <div class="bg-white rounded-lg shadow-md overflow-hidden mb-8">
            <!-- Cover Image -->
            <div class="h-64 bg-gradient-to-r from-teal-500 to-blue-600 relative">
                <?php if (!empty($business['foto_portada'])): ?>
                    <img src="app/public/images/business/<?php echo htmlspecialchars($business['foto_portada']); ?>" 
                         alt="<?php echo htmlspecialchars($business['nombre_publico']); ?>"
                         class="w-full h-full object-cover">
                <?php endif; ?>
            </div>
            
            <!-- Business Info -->
            <div class="p-6">
                <div class="flex flex-col md:flex-row md:items-start md:justify-between">
                    <div class="flex-1">
                        <h1 class="text-3xl font-bold text-gray-900 mb-2">
                            <?php echo htmlspecialchars($business['nombre_publico']); ?>
                        </h1>
                        
                        <div class="flex flex-wrap gap-4 text-gray-600 mb-4">
                            <span class="flex items-center">
                                <i class="fas fa-map-marker-alt mr-2 text-teal-600"></i>
                                <?php echo htmlspecialchars($business['distrito'] . ', ' . $business['canton'] . ', ' . $business['provincia']); ?>
                            </span>
                            <span class="flex items-center">
                                <i class="fas fa-tag mr-2 text-teal-600"></i>
                                <?php echo htmlspecialchars($business['nombre_categoria']); ?>
                            </span>
                            <?php if (!empty($business['telefono_contacto'])): ?>
                                <span class="flex items-center">
                                    <i class="fas fa-phone mr-2 text-teal-600"></i>
                                    <?php echo htmlspecialchars($business['telefono_contacto']); ?>
                                </span>
                            <?php endif; ?>
                        </div>
                        
                        <?php if (!empty($business['descripcion_corta'])): ?>
                            <p class="text-gray-700 leading-relaxed">
                                <?php echo nl2br(htmlspecialchars($business['descripcion_corta'])); ?>
                            </p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- Services Section -->
        <div class="mb-8">
            <h2 class="text-2xl font-bold text-gray-900 mb-6">Servicios Disponibles</h2>
            
            <?php if (!empty($services)): ?>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <?php foreach ($services as $service): ?>
                        <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition flex flex-col h-full">
                            <div class="p-6 flex flex-col flex-1">
                                <div class="flex items-start justify-between mb-3">
                                    <h3 class="text-lg font-semibold text-gray-900 flex-1">
                                        <?php echo htmlspecialchars($service['titulo']); ?>
                                    </h3>
                                    <?php if (!empty($service['nombre_categoria'])): ?>
                                        <span class="ml-2 px-2 py-1 bg-teal-100 text-teal-800 text-xs rounded flex-shrink-0">
                                            <?php echo htmlspecialchars($service['nombre_categoria']); ?>
                                        </span>
                                    <?php endif; ?>
                                </div>
                                
                                <div class="flex-1 mb-4">
                                    <?php if (!empty($service['descripcion_corta'])): ?>
                                        <p class="text-gray-600 text-sm line-clamp-3">
                                            <?php echo htmlspecialchars($service['descripcion_corta']); ?>
                                        </p>
                                    <?php endif; ?>
                                </div>
                                
                                <div class="flex items-center justify-between mt-auto">
                                    <div>
                                        <?php if (!empty($service['precio_base'])): ?>
                                            <p class="text-2xl font-bold text-teal-600">
                                                ₡<?php echo number_format($service['precio_base'], 0); ?>
                                            </p>
                                            <?php if (!empty($service['duracion_dias'])): ?>
                                                <p class="text-xs text-gray-500">
                                                    <?php echo $service['duracion_dias']; ?> <?php echo $service['duracion_dias'] == 1 ? 'noche' : 'días'; ?>
                                                </p>
                                            <?php endif; ?>
                                        <?php endif; ?>
                                    </div>
                                    
                                    <a href="index.php?controller=reservation&action=create&service_id=<?php echo $service['id_servicio']; ?>"
                                       class="px-4 py-2 bg-teal-600 text-white rounded hover:bg-teal-700 transition text-sm flex-shrink-0">
                                        Reservar
                                    </a>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <div class="bg-white rounded-lg shadow-md p-12 text-center">
                    <i class="fas fa-info-circle fa-3x text-gray-300 mb-4"></i>
                    <h3 class="text-xl font-semibold text-gray-700 mb-2">No hay servicios disponibles</h3>
                    <p class="text-gray-500">Este negocio aún no ha publicado servicios.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</main>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
