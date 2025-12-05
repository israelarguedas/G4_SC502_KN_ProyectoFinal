<?php include 'header.php' ?>


    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <h1 class="text-3xl font-extrabold text-gray-900 mb-6">Gestión Centralizada</h1>

        <div class="flex border-b border-gray-300 mb-8" id="admin-tab-container">
            <button data-tab="comercios"
                class="admin-tab flex-1 py-3 text-sm font-medium border-b-2 border-teal-600 text-teal-600 hover:text-teal-700 transition duration-150">
                <i class="fas fa-store mr-2"></i> Validacion de comercios
            </button>
            <button data-tab="reservaciones"
                class="admin-tab flex-1 py-3 text-sm font-medium border-b-2 border-transparent text-gray-500 hover:text-gray-700 transition duration-150">
                <i class="fas fa-book-open mr-2"></i> Reservaciones
            </button>
            <button data-tab="estadisticas"
                class="admin-tab flex-1 py-3 text-sm font-medium border-b-2 border-transparent text-gray-500 hover:text-gray-700 transition duration-150">
                <i class="fas fa-chart-line mr-2"></i> Cupones/Estadísticas
            </button>
        </div>

        <div id="tab-comercios" class="admin-content">
            <h2 class="text-2xl font-semibold text-gray-800 mb-4">Cola de Solicitudes Pendientes</h2>
            
            <div class="bg-white shadow-sm rounded-lg overflow-hidden">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nombre Negocio</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Fecha Solicitud</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200" id="comercios-solicitudes-table">
                        <tr data-status="pending" class="hover:bg-yellow-50">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">APP-001</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">Hotel Montaña Verde</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">Nov 1, 2025</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <button class="text-teal-600 hover:text-teal-900 mr-3">Ver Documentos</button>
                                <button class="text-green-600 hover:text-green-900 mr-3">Aprobar</button>
                                <button class="text-red-600 hover:text-red-900">Rechazar</button>
                            </td>
                        </tr>
                        </tbody>
                </table>
            </div>
            
            <h3 class="text-xl font-semibold text-gray-800 mt-8 mb-4">Gestión de Comercios Aprobados</h3>
            <button class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 transition duration-150">
                <i class="fas fa-edit mr-2"></i> Editar Información de Comercio
            </button>
        </div>

        <div id="tab-reservaciones" class="admin-content hidden">
            <h2 class="text-2xl font-semibold text-gray-800 mb-4">Resumen Global de Reservaciones</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                <div class="bg-white shadow-sm rounded-lg p-5">
                    <h3 class="text-lg font-semibold text-gray-700">Total Reservaciones (Anual)</h3>
                    <p class="text-3xl font-bold text-teal-600 mt-2">1,250</p>
                </div>
                <div class="bg-white shadow-sm rounded-lg p-5">
                    <h3 class="text-lg font-semibold text-gray-700">Reservaciones Pendientes</h3>
                    <p class="text-3xl font-bold text-yellow-600 mt-2">15</p>
                </div>
                <div class="bg-white shadow-sm rounded-lg p-5">
                    <h3 class="text-lg font-semibold text-gray-700">Ingresos Totales (Simulados)</h3>
                    <p class="text-3xl font-bold text-green-600 mt-2">₡125,000,000</p>
                </div>
            </div>
            <p class="text-gray-600">Para una gestión detallada, filtre o acceda a la vista de negocio individual.</p>
        </div>

        <div id="tab-estadisticas" class="admin-content hidden">
            <h2 class="text-2xl font-semibold text-gray-800 mb-4">Estadísticas Clave</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                <div class="bg-white shadow-sm rounded-lg p-5">
                    <h3 class="text-lg font-semibold text-gray-700">Búsquedas por Zona (Top)</h3>
                    <p class="mt-2 text-gray-600">1. Guanacaste (35%)</p>
                    <p class="text-gray-600">2. Caribe Sur (20%)</p>
                </div>
                <div class="bg-white shadow-sm rounded-lg p-5">
                    <h3 class="text-lg font-semibold text-gray-700">Comercios más populares</h3>
                    <p class="mt-2 text-gray-600">1. Tour Monteverde (150 reservas)</p>
                    <p class="text-gray-600">2. Hotel Vista al Mar (120 reservas)</p>
                </div>
            </div>

            <h2 class="text-2xl font-semibold text-gray-800 mt-8 mb-4">Gestión de Cupones/Ofertas</h2>
            <button class="bg-teal-600 text-white px-4 py-2 rounded-md hover:bg-teal-700 transition duration-150">
                <i class="fas fa-plus mr-2"></i> Agregar Nueva Oferta Destacada
            </button>
            <p class="mt-4 text-gray-600">Aquí se puede agregar, editar y eliminar ofertas destacadas.</p>
        </div>
    </main>

<?php include 'footer.php' ?>