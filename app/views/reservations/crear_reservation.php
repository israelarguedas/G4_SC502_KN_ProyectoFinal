<?php 
$pageTitle = 'Hacer Reservación - TicoTrips';
require_once __DIR__ . '/../layouts/header.php'; 
?>

    <main class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="bg-white rounded-xl shadow-lg overflow-hidden">
            <!-- Encabezado del formulario -->
            <div class="p-6 bg-teal-600">
                <h1 class="text-2xl font-bold text-white">Reservación</h1>
                <p class="text-teal-100 mt-1">Complete los detalles de su reserva</p>
            </div>

            <!-- Resumen del servicio seleccionado -->
            <?php if ($service): ?>
            <div class="p-6 bg-gray-50 border-b">
                <div class="flex items-start space-x-4">
                    <div>
                        <h2 class="font-semibold text-gray-900"><?php echo htmlspecialchars($service['titulo']); ?></h2>
                        <p class="text-gray-500 text-sm"><?php echo htmlspecialchars($service['nombre_negocio']); ?></p>
                        <?php if (!empty($service['nombre_categoria'])): ?>
                            <p class="text-gray-500 text-xs"><?php echo htmlspecialchars($service['nombre_categoria']); ?></p>
                        <?php endif; ?>
                        <p class="text-lg font-semibold text-teal-600 mt-2">₡<?php echo number_format($service['precio_base'], 0); ?> por persona</p>
                    </div>
                </div>
            </div>
            <?php endif; ?>

            <!-- Formulario de reserva -->
            <form id="reservation-form" action="index.php?controller=reservation&action=store" method="POST" class="p-6 space-y-6">
                <input type="hidden" name="id_servicio" value="<?php echo $service ? $service['id_servicio'] : ''; ?>">
                <input type="hidden" name="id_usuario" value="<?php echo $_SESSION['user_id'] ?? ''; ?>">

                <!-- Detalles de la reserva -->
                <div class="space-y-4">
                    <h3 class="text-lg font-medium text-gray-900">Detalles de la Reserva</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Fecha</label>
                            <input type="date" name="fecha" required id="reservation-date"
                                min="<?php echo date('Y-m-d'); ?>"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-teal-500 focus:ring-teal-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Número de Personas</label>
                            <select name="personas" required id="people-count"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-teal-500 focus:ring-teal-500">
                                <option value="1">1 persona</option>
                                <option value="2" selected>2 personas</option>
                                <option value="3">3 personas</option>
                                <option value="4">4 personas</option>
                                <option value="5">5 personas</option>
                                <option value="6">6 personas</option>
                                <option value="7">7 personas</option>
                                <option value="8">8 personas</option>
                                <option value="9">9 personas</option>
                                <option value="10">10+ personas</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Resumen de precios -->
                <div class="bg-gray-50 p-6 -mx-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Resumen de Precios</h3>
                    <div class="space-y-2">
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">Precio por persona</span>
                            <span class="font-medium" id="unit-price">₡<?php echo number_format($service['precio_base'], 0); ?></span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">Número de Personas</span>
                            <span class="font-medium" id="people-display">2</span>
                        </div>
                        <div class="border-t border-gray-200 pt-2 mt-2"></div>
                        <div class="flex justify-between">
                            <span class="text-lg font-semibold text-gray-900">Total</span>
                            <span class="text-lg font-bold text-teal-600" id="total-price">₡<?php echo number_format($service['precio_base'] * 2, 0); ?></span>
                        </div>
                    </div>
                </div>

                <!-- Términos y condiciones -->
                <div class="space-y-4">
                    <div class="flex items-start">
                        <div class="flex items-center h-5">
                            <input type="checkbox" required
                                class="h-4 w-4 rounded border-gray-300 text-teal-600 focus:ring-teal-500">
                        </div>
                        <div class="ml-3 text-sm">
                            <label class="font-medium text-gray-700">Acepto los términos y condiciones</label>
                            <p class="text-gray-500">Al marcar esta casilla, acepto las políticas de cancelación y los términos del servicio.</p>
                        </div>
                    </div>
                </div>

                <!-- Botón de envío -->
                <div class="flex justify-end">
                    <button type="submit"
                        class="bg-teal-600 text-white px-8 py-3 rounded-lg hover:bg-teal-700 focus:outline-none focus:ring-2 focus:ring-teal-500 focus:ring-offset-2 transition-colors">
                        Confirmar Reservación
                    </button>
                </div>
            </form>
        </div>
    </main>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const peopleCount = document.getElementById('people-count');
            const peopleDisplay = document.getElementById('people-display');
            const totalPrice = document.getElementById('total-price');
            const unitPrice = <?php echo $service['precio_base']; ?>;
            
            // Update total when number of people changes
            peopleCount.addEventListener('change', function() {
                const people = parseInt(this.value);
                const total = people * unitPrice;
                
                peopleDisplay.textContent = people;
                totalPrice.textContent = '₡' + Math.round(total).toLocaleString('es-CR');
            });
        });
    </script>
    
<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
