<?php 
$pageTitle = 'Reservaciones - TicoTrips';
require_once __DIR__ . '/../layouts/header.php'; 
?>

<main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Barra de Búsqueda Principal -->
    <div class="bg-white border-b rounded-lg shadow-sm mb-8">
        <div class="px-6 py-4">
            <div class="flex flex-col md:flex-row gap-4">
                <div class="flex-1">
                    <input type="text" placeholder="¿Qué quieres hacer? Tour, hospedaje, restaurante..."
                        class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:border-teal-500 focus:ring-1 focus:ring-teal-500">
                </div>
                <div class="flex gap-4">
                    <div class="relative">
                        <input type="date" class="px-4 py-2 rounded-lg border border-gray-300 focus:border-teal-500 focus:ring-1 focus:ring-teal-500">
                    </div>
                    <div class="relative">
                        <select class="px-4 py-2 rounded-lg border border-gray-300 focus:border-teal-500 focus:ring-1 focus:ring-teal-500">
                            <option value="">Personas</option>
                            <option value="1">1 persona</option>
                            <option value="2">2 personas</option>
                            <option value="3">3 personas</option>
                            <option value="4">4+ personas</option>
                        </select>
                    </div>
                    <button class="bg-teal-600 text-white px-6 py-2 rounded-lg hover:bg-teal-700 transition">
                        Buscar
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="flex flex-col lg:flex-row gap-8">
        <!-- Columna de Filtros -->
        <div class="lg:w-64 space-y-6">
            <div class="bg-white rounded-lg shadow-sm p-4">
                <h3 class="font-semibold text-gray-900 mb-4">Tipo de Servicio</h3>
                <div class="space-y-2">
                    <label class="flex items-center space-x-2">
                        <input type="checkbox" class="rounded text-teal-600 focus:ring-teal-500">
                        <span>Tours y Experiencias</span>
                    </label>
                    <label class="flex items-center space-x-2">
                        <input type="checkbox" class="rounded text-teal-600 focus:ring-teal-500">
                        <span>Hospedaje</span>
                    </label>
                    <label class="flex items-center space-x-2">
                        <input type="checkbox" class="rounded text-teal-600 focus:ring-teal-500">
                        <span>Restaurantes</span>
                    </label>
                    <label class="flex items-center space-x-2">
                        <input type="checkbox" class="rounded text-teal-600 focus:ring-teal-500">
                        <span>Artesanías</span>
                    </label>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-sm p-4">
                <h3 class="font-semibold text-gray-900 mb-4">Ubicación</h3>
                <select class="w-full px-3 py-2 rounded-md border border-gray-300 focus:border-teal-500 focus:ring-1 focus:ring-teal-500">
                    <option value="">Todas las provincias</option>
                    <option value="San José">San José</option>
                    <option value="Alajuela">Alajuela</option>
                    <option value="Cartago">Cartago</option>
                    <option value="Heredia">Heredia</option>
                    <option value="Guanacaste">Guanacaste</option>
                    <option value="Puntarenas">Puntarenas</option>
                    <option value="Limón">Limón</option>
                </select>
            </div>

            <div class="bg-white rounded-lg shadow-sm p-4">
                <h3 class="font-semibold text-gray-900 mb-4">Rango de Precio</h3>
                <div class="flex gap-4">
                    <input type="number" placeholder="Mín" class="w-full px-3 py-2 rounded-md border border-gray-300 focus:border-teal-500 focus:ring-1 focus:ring-teal-500">
                    <input type="number" placeholder="Máx" class="w-full px-3 py-2 rounded-md border border-gray-300 focus:border-teal-500 focus:ring-1 focus:ring-teal-500">
                </div>
            </div>
        </div>

        <!-- Resultados -->
        <div class="flex-1">
            <h2 class="text-2xl font-bold text-gray-900 mb-6">Servicios Disponibles</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Ejemplo de tarjeta de servicio -->
                <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition">
                    <div class="h-48 bg-gray-200">
                        <img src="app/public/images/monteverde.jpg" alt="Servicio" class="w-full h-full object-cover">
                    </div>
                    <div class="p-4">
                        <h3 class="font-bold text-lg text-gray-900">Tour Monteverde</h3>
                        <p class="text-gray-600 text-sm mt-1">Monteverde, Puntarenas</p>
                        <div class="flex items-center mt-2">
                            <span class="text-yellow-400">
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star-half-alt"></i>
                            </span>
                            <span class="text-gray-500 text-sm ml-2">(128 reseñas)</span>
                        </div>
                        <div class="flex items-center justify-between mt-4">
                            <p class="text-teal-600 font-bold text-lg">₡35,000</p>
                            <a href="index.php?controller=reservation&action=create" 
                               class="bg-teal-600 text-white px-4 py-2 rounded-lg hover:bg-teal-700 transition text-sm">
                                Reservar
                            </a>
                        </div>
                    </div>
                </div>

                <div class="col-span-full text-center py-8 text-gray-500">
                    <i class="fas fa-search fa-3x mb-4"></i>
                    <p>Usa los filtros para encontrar servicios disponibles</p>
                </div>
            </div>
        </div>
    </div>
</main>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
