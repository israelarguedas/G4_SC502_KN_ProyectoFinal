<?php include 'header.php' ?>

    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Encabezado -->
        <div class="text-center mb-12">
            <h1 class="text-3xl font-bold text-gray-900">Cupones y Promociones Exclusivas</h1>
            <p class="mt-4 text-lg text-gray-600">Descuentos especiales en hoteles y tours</p>
        </div>

        <!-- Filtros -->
        <div class="mb-8 flex flex-wrap gap-4">
            <button class="px-4 py-2 bg-teal-600 text-white rounded-full hover:bg-teal-700">Todos</button>
            <button class="px-4 py-2 bg-white text-gray-700 rounded-full hover:bg-gray-100">Hoteles</button>
            <button class="px-4 py-2 bg-white text-gray-700 rounded-full hover:bg-gray-100">Tours</button>
        </div>

        <!-- Grid de Cupones -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <!-- Cupón 1 -->
            <div class="bg-white rounded-lg shadow-sm overflow-hidden">
                <img src="https://via.placeholder.com/400x200" alt="Hotel Vista al Mar" class="w-full h-48 object-cover">
                <div class="p-6">
                    <div class="flex justify-between items-start mb-4">
                        <div>
                            <h3 class="text-xl font-bold text-gray-900">Hotel Vista al Mar</h3>
                            <p class="text-gray-600">Manuel Antonio, Puntarenas</p>
                        </div>
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-red-100 text-red-800">
                            -25%
                        </span>
                    </div>
                    
                    <div class="space-y-4">
                        <div class="flex items-center text-gray-500">
                            <i class="fas fa-calendar-alt w-5"></i>
                            <span>Válido hasta: 15 Dic 2025</span>
                        </div>
                        <div class="flex items-center text-gray-500">
                            <i class="fas fa-ticket-alt w-5"></i>
                            <span>Quedan 8 cupones</span>
                        </div>
                        
                        <div class="border-t pt-4">
                            <div class="flex justify-between items-center mb-2">
                                <span class="text-gray-600">Precio normal</span>
                                <span class="text-gray-500 line-through">₡120,000</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-gray-900 font-medium">Precio con cupón</span>
                                <span class="text-2xl font-bold text-teal-600">₡90,000</span>
                            </div>
                        </div>

                        <button onclick="window.location.href='make-reservation.php'"
                            class="w-full bg-teal-600 text-white px-4 py-2 rounded-lg hover:bg-teal-700 transition-colors">
                            Comprar Cupón
                        </button>
                    </div>
                </div>
            </div>

            <!-- Cupón 2 -->
            <div class="bg-white rounded-lg shadow-sm overflow-hidden">
                <img src="https://via.placeholder.com/400x200" alt="Tour Volcán Arenal" class="w-full h-48 object-cover">
                <div class="p-6">
                    <div class="flex justify-between items-start mb-4">
                        <div>
                            <h3 class="text-xl font-bold text-gray-900">Tour Volcán Arenal</h3>
                            <p class="text-gray-600">La Fortuna, Alajuela</p>
                        </div>
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-red-100 text-red-800">
                            -30%
                        </span>
                    </div>
                    
                    <div class="space-y-4">
                        <div class="flex items-center text-gray-500">
                            <i class="fas fa-calendar-alt w-5"></i>
                            <span>Válido hasta: 31 Dic 2025</span>
                        </div>
                        <div class="flex items-center text-gray-500">
                            <i class="fas fa-ticket-alt w-5"></i>
                            <span>Quedan 15 cupones</span>
                        </div>
                        
                        <div class="border-t pt-4">
                            <div class="flex justify-between items-center mb-2">
                                <span class="text-gray-600">Precio normal</span>
                                <span class="text-gray-500 line-through">₡45,000</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-gray-900 font-medium">Precio con cupón</span>
                                <span class="text-2xl font-bold text-teal-600">₡31,500</span>
                            </div>
                        </div>

                        <button onclick="window.location.href='make-reservation.php'"
                            class="w-full bg-teal-600 text-white px-4 py-2 rounded-lg hover:bg-teal-700 transition-colors">
                            Comprar Cupón
                        </button>
                    </div>
                </div>
            </div>

            <!-- Cupón 3 (Agotado) -->
            <div class="bg-white rounded-lg shadow-sm overflow-hidden opacity-75">
                <img src="https://via.placeholder.com/400x200" alt="Hotel Mountain Lodge" class="w-full h-48 object-cover filter grayscale">
                <div class="p-6">
                    <div class="flex justify-between items-start mb-4">
                        <div>
                            <h3 class="text-xl font-bold text-gray-900">Hotel Mountain Lodge</h3>
                            <p class="text-gray-600">Monteverde, Puntarenas</p>
                        </div>
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-red-100 text-red-800">
                            -40%
                        </span>
                    </div>
                    
                    <div class="space-y-4">
                        <div class="flex items-center text-gray-500">
                            <i class="fas fa-calendar-alt w-5"></i>
                            <span>Válido hasta: 20 Dic 2025</span>
                        </div>
                        <div class="flex items-center text-red-500">
                            <i class="fas fa-ticket-alt w-5"></i>
                            <span>CUPONES AGOTADOS</span>
                        </div>
                        
                        <div class="border-t pt-4">
                            <div class="flex justify-between items-center mb-2">
                                <span class="text-gray-600">Precio normal</span>
                                <span class="text-gray-500 line-through">₡150,000</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-gray-900 font-medium">Precio con cupón</span>
                                <span class="text-2xl font-bold text-teal-600">₡90,000</span>
                            </div>
                        </div>

                        <button disabled
                            class="w-full bg-gray-300 text-gray-500 px-4 py-2 rounded-lg cursor-not-allowed">
                            Agotado
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Información Adicional -->
        <div class="mt-12 bg-white rounded-lg shadow-sm p-6">
            <h2 class="text-xl font-bold text-gray-900 mb-4">Información Importante</h2>
            <ul class="space-y-2 text-gray-600">
                <li class="flex items-center">
                    <i class="fas fa-info-circle w-5 text-teal-600"></i>
                    <span>Los cupones son válidos solo en las fechas especificadas</span>
                </li>
                <li class="flex items-center">
                    <i class="fas fa-info-circle w-5 text-teal-600"></i>
                    <span>Debes mostrar el cupón digital en el establecimiento</span>
                </li>
                <li class="flex items-center">
                    <i class="fas fa-info-circle w-5 text-teal-600"></i>
                    <span>La disponibilidad está sujeta a cambios</span>
                </li>
            </ul>
        </div>
    </main>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Aquí iría la lógica para filtrar cupones y actualizar disponibilidad
            const filterButtons = document.querySelectorAll('button[class*="px-4 py-2"]');
            
            filterButtons.forEach(button => {
                button.addEventListener('click', function() {
                    // Remover clase activa de todos los botones
                    filterButtons.forEach(btn => {
                        btn.classList.remove('bg-teal-600', 'text-white');
                        btn.classList.add('bg-white', 'text-gray-700');
                    });
                    
                    // Agregar clase activa al botón clickeado
                    this.classList.remove('bg-white', 'text-gray-700');
                    this.classList.add('bg-teal-600', 'text-white');
                    
                    // Aquí iría la lógica de filtrado
                });
            });
        });
    </script>
    
<?php include 'footer.php' ?>