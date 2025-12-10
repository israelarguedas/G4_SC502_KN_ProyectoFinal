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
            <div class="p-6 bg-gray-50 border-b">
                <div class="flex items-start space-x-4">
                    <img src="https://via.placeholder.com/150" alt="Imagen del lugar" class="w-24 h-24 rounded-lg object-cover">
                    <div>
                        <h2 class="font-semibold text-gray-900" id="service-name">Aventura en Monteverde</h2>
                        <p class="text-gray-500 text-sm" id="service-type">Tour / Experiencia</p>
                        <div class="flex items-center mt-1">
                            <div class="flex text-yellow-400">
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star-half-alt"></i>
                            </div>
                            <span class="ml-2 text-sm text-gray-600">4.5 (128 reseñas)</span>
                        </div>
                        <p class="text-lg font-semibold text-gray-900 mt-2">₡35,000 por persona</p>
                    </div>
                </div>
            </div>

            <!-- Formulario de reserva -->
            <form id="reservation-form" action="index.php?controller=reservation&action=store" method="POST" class="p-6 space-y-6">
                <input type="hidden" name="id_servicio" value="1">
                <input type="hidden" name="id_usuario" value="<?php echo $_SESSION['user_id'] ?? ''; ?>">
                <!-- Información de contacto -->
                <div class="space-y-4">
                    <h3 class="text-lg font-medium text-gray-900">Información de Contacto</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Nombre Completo</label>
                            <input type="text" name="nombre_cliente" required
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-teal-500 focus:ring-teal-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Correo Electrónico</label>
                            <input type="email" name="email_cliente" required
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-teal-500 focus:ring-teal-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Tipo de Identificación</label>
                            <select required id="id-type"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-teal-500 focus:ring-teal-500">
                                <option value="cedula">Cédula Nacional</option>
                                <option value="dimex">DIMEX</option>
                                <option value="pasaporte">Pasaporte</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Número de Identificación</label>
                            <input type="text" name="identificacion" required id="id-number"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-teal-500 focus:ring-teal-500"
                                placeholder="Ingrese su número de identificación">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Teléfono</label>
                            <input type="tel" name="telefono_cliente" required
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-teal-500 focus:ring-teal-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">País de Residencia</label>
                            <select required
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-teal-500 focus:ring-teal-500">
                                <option value="CR">Costa Rica</option>
                                <option value="US">Estados Unidos</option>
                                <option value="ES">España</option>
                                <option value="other">Otro</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Detalles de la reserva -->
                <div class="space-y-4">
                    <h3 class="text-lg font-medium text-gray-900">Detalles de la Reserva</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Fecha</label>
                            <input type="date" name="fecha_reserva" required id="reservation-date"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-teal-500 focus:ring-teal-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Hora</label>
                            <select required id="reservation-time"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-teal-500 focus:ring-teal-500">
                                <option value="09:00">9:00 AM</option>
                                <option value="11:00">11:00 AM</option>
                                <option value="13:00">1:00 PM</option>
                                <option value="15:00">3:00 PM</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Número de Personas</label>
                            <select name="cantidad_personas" required id="people-count"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-teal-500 focus:ring-teal-500">
                                <option value="2">2 personas</option>
                                <option value="3">3 personas</option>
                                <option value="4">4 personas</option>
                                <option value="5">5 personas</option>
                                <option value="6">6+ personas</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Idioma Preferido</label>
                            <select required
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-teal-500 focus:ring-teal-500">
                                <option value="es">Español</option>
                                <option value="en">Inglés</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Requerimientos especiales -->
                <div class="space-y-4">
                    <h3 class="text-lg font-medium text-gray-900">Requerimientos Especiales</h3>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Notas Adicionales</label>
                        <textarea name="comentarios" rows="3" placeholder="Alergias, necesidades especiales, preferencias..."
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-teal-500 focus:ring-teal-500"></textarea>
                    </div>
                </div>

                <!-- Resumen de precios -->
                <div class="bg-gray-50 p-6 -mx-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Resumen de Precios</h3>
                    <div class="space-y-2">
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">Precio por persona</span>
                            <span class="font-medium" data-price="unit">₡35,000</span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">Personas</span>
                            <span class="font-medium" data-price="people">2</span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">Subtotal</span>
                            <span class="font-medium" data-price="subtotal">₡70,000</span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">Impuestos (13% IVA)</span>
                            <span class="font-medium" data-price="tax">₡9,100</span>
                        </div>
                        <div class="border-t pt-2 mt-2">
                            <div class="flex justify-between">
                                <span class="text-lg font-semibold">Total</span>
                                <span class="text-lg font-bold text-teal-600" data-price="total">₡79,100</span>
                            </div>
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
            
            // Actualizar precios cuando cambie el número de personas
            peopleCount.addEventListener('change', updatePrices);
            
            function updatePrices() {
                const people = parseInt(peopleCount.value);
                const pricePerPerson = 35000;
                const subtotal = people * pricePerPerson;
                const tax = subtotal * 0.13;
                const total = subtotal + tax;
                
                // Actualizar el resumen de precios en el DOM
                document.querySelector('[data-price="people"]').textContent = people;
                document.querySelector('[data-price="subtotal"]').textContent = `₡${subtotal.toLocaleString()}`;
                document.querySelector('[data-price="tax"]').textContent = `₡${Math.round(tax).toLocaleString()}`;
                document.querySelector('[data-price="total"]').textContent = `₡${Math.round(total).toLocaleString()}`;
            }
        });
    </script>
    
<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
