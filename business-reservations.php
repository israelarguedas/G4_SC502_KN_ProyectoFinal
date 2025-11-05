<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tico Trips - Gestión de Reservaciones</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 min-h-screen">
    <div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
        <!-- Encabezado -->
        <div class="bg-white shadow-sm rounded-lg p-6 mb-6">
            <h1 class="text-3xl font-bold text-teal-600">Panel de Reservaciones</h1>
            <p class="text-gray-500 mt-2">Gestione las reservaciones de su negocio</p>
        </div>

        <!-- Estadísticas Rápidas -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
            <div class="bg-white shadow-sm rounded-lg p-6">
                <h3 class="text-lg font-semibold text-gray-700">Reservaciones Hoy</h3>
                <p class="text-3xl font-bold text-teal-600 mt-2">0</p>
            </div>
            <div class="bg-white shadow-sm rounded-lg p-6">
                <h3 class="text-lg font-semibold text-gray-700">Pendientes</h3>
                <p class="text-3xl font-bold text-yellow-600 mt-2">0</p>
            </div>
            <div class="bg-white shadow-sm rounded-lg p-6">
                <h3 class="text-lg font-semibold text-gray-700">Confirmadas</h3>
                <p class="text-3xl font-bold text-green-600 mt-2">0</p>
            </div>
            <div class="bg-white shadow-sm rounded-lg p-6">
                <h3 class="text-lg font-semibold text-gray-700">Canceladas</h3>
                <p class="text-3xl font-bold text-red-600 mt-2">0</p>
            </div>
        </div>

        <!-- Filtros y Búsqueda -->
        <div class="bg-white shadow-sm rounded-lg p-6 mb-6">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <label for="status-filter" class="block text-sm font-medium text-gray-700">Estado</label>
                    <select id="status-filter" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-teal-500 focus:ring-teal-500 p-2 border">
                        <option value="all">Todos</option>
                        <option value="pending">Pendientes</option>
                        <option value="confirmed">Confirmadas</option>
                        <option value="cancelled">Canceladas</option>
                    </select>
                </div>
                <div>
                    <label for="date-filter" class="block text-sm font-medium text-gray-700">Fecha</label>
                    <input type="date" id="date-filter" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-teal-500 focus:ring-teal-500 p-2 border">
                </div>
                <div>
                    <label for="search" class="block text-sm font-medium text-gray-700">Buscar</label>
                    <input type="text" id="search" placeholder="Nombre, email, ID..." class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-teal-500 focus:ring-teal-500 p-2 border">
                </div>
                <div class="flex items-end">
                    <button class="w-full bg-teal-600 text-white px-4 py-2 rounded-md hover:bg-teal-700 transition duration-150">
                        Aplicar Filtros
                    </button>
                </div>
            </div>
        </div>

        <!-- Lista de Reservaciones -->
        <div class="bg-white shadow-sm rounded-lg overflow-hidden">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID Reserva</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Cliente</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Fecha</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Estado</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Detalles</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Acciones</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200" id="reservations-table">
                    <!-- Las reservaciones se cargarán dinámicamente aquí -->
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">RES-001</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            <div>Juan Pérez</div>
                            <div class="text-xs text-gray-400">juan@ejemplo.com</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            <div>Nov 5, 2025</div>
                            <div class="text-xs text-gray-400">2:00 PM</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                Pendiente
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            <div>2 personas</div>
                            <div class="text-xs text-gray-400">Tour Aventura</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <button class="text-green-600 hover:text-green-900 mr-3">Confirmar</button>
                            <button class="text-red-600 hover:text-red-900">Cancelar</button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Modal de Detalles de Reservación -->
        <div id="reservation-details-modal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full">
            <div class="relative top-20 mx-auto p-5 border w-full max-w-2xl shadow-lg rounded-md bg-white">
                <div class="flex justify-between items-center pb-3 border-b">
                    <h3 class="text-xl font-bold text-gray-900">Detalles de la Reservación</h3>
                    <button class="modal-close text-gray-400 hover:text-gray-500">
                        <span class="text-2xl">&times;</span>
                    </button>
                </div>
                <div class="mt-4">
                    <div class="grid grid-cols-2 gap-4">
                        <!-- Los detalles se cargarán dinámicamente aquí -->
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="assets/js/main.js"></script>
    <!-- Vamos a crear un nuevo archivo JS específico para la gestión de reservaciones -->
    <script src="assets/js/reservations.js"></script>
</body>

</html>