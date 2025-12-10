<?php 
$pageTitle = 'Cupones y Promociones - TicoTrips';
require_once __DIR__ . '/../layouts/header.php'; 
?>

    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Encabezado -->
        <div class="text-center mb-12">
            <h1 class="text-3xl font-bold text-gray-900">Cupones y Promociones B2B</h1>
            <p class="mt-4 text-lg text-gray-600">Descuentos exclusivos en servicios turísticos de Costa Rica</p>
            
            <?php if ($es_negocio && $negocio_id): ?>
                <a href="index.php?controller=business&action=manageCoupons" 
                   class="mt-4 inline-block px-6 py-2 bg-teal-600 text-white rounded-lg hover:bg-teal-700 transition-colors">
                    📊 Gestionar Mis Cupones
                </a>
            <?php endif; ?>
        </div>

        <!-- Lista de Cupones Activos -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <?php if (!empty($cupones)): ?>
                <?php foreach ($cupones as $cupon): ?>
                    <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow">
                        <div class="p-6">
                            <!-- Encabezado del Cupón -->
                            <div class="flex justify-between items-start mb-4">
                                <div class="flex-1">
                                    <h3 class="text-lg font-bold text-gray-900"><?php echo htmlspecialchars($cupon['nombre_negocio']); ?></h3>
                                    <p class="text-sm text-gray-600"><?php echo htmlspecialchars($cupon['canton'] . ', ' . $cupon['provincia']); ?></p>
                                </div>
                                
                                <!-- Badge de Descuento -->
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-bold bg-red-100 text-red-800 ml-2">
                                    <?php 
                                        if ($cupon['tipo_descuento'] === 'Porcentaje') {
                                            echo '-' . $cupon['valor_descuento'] . '%';
                                        } else {
                                            echo '-₡' . number_format($cupon['valor_descuento'], 0);
                                        }
                                    ?>
                                </span>
                            </div>

                            <!-- Código del Cupón -->
                            <div class="mb-4 p-3 bg-gray-50 rounded border-2 border-dashed border-teal-300">
                                <p class="text-xs text-gray-600">Código de Cupón</p>
                                <p class="text-lg font-mono font-bold text-teal-600"><?php echo htmlspecialchars($cupon['codigo_cupon']); ?></p>
                            </div>

                            <!-- Información del Cupón -->
                            <div class="space-y-3 mb-4">
                                <div class="flex items-center text-sm text-gray-600">
                                    <i class="fas fa-calendar-alt w-5 text-teal-600"></i>
                                    <span>Válido: <?php echo date('d/m/Y', strtotime($cupon['fecha_inicio'])); ?> - <?php echo date('d/m/Y', strtotime($cupon['fecha_fin'])); ?></span>
                                </div>
                                
                                <?php if ($cupon['usos_restantes'] !== null): ?>
                                    <div class="flex items-center text-sm text-gray-600">
                                        <i class="fas fa-ticket-alt w-5 text-teal-600"></i>
                                        <span>Disponibles: 
                                            <span class="font-semibold text-gray-900"><?php echo $cupon['usos_restantes']; ?></span>
                                        </span>
                                    </div>
                                <?php else: ?>
                                    <div class="flex items-center text-sm text-gray-600">
                                        <i class="fas fa-infinity w-5 text-teal-600"></i>
                                        <span>Disponibilidad: <span class="font-semibold text-gray-900">Ilimitada</span></span>
                                    </div>
                                <?php endif; ?>
                            </div>

                            <!-- Botón de Acción -->
                            <button onclick="copiarCodigo('<?php echo htmlspecialchars($cupon['codigo_cupon']); ?>')"
                                class="w-full bg-teal-600 text-white px-4 py-2 rounded-lg hover:bg-teal-700 transition-colors font-medium">
                                Copiar Código
                            </button>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="col-span-full p-8 bg-gray-50 rounded-lg text-center">
                    <i class="fas fa-tag text-4xl text-gray-400 mb-4"></i>
                    <p class="text-gray-600">No hay cupones disponibles en este momento.</p>
                    <p class="text-sm text-gray-500">Vuelve pronto para nuevas promociones</p>
                </div>
            <?php endif; ?>
        </div>

        <!-- Información Adicional -->
        <div class="mt-12 bg-gradient-to-r from-teal-50 to-green-50 rounded-lg shadow-sm p-6">
            <h2 class="text-xl font-bold text-gray-900 mb-4">💡 Cómo usar los cupones</h2>
            <ul class="space-y-3 text-gray-700">
                <li class="flex items-start">
                    <i class="fas fa-check-circle w-6 text-teal-600 mt-0.5 mr-3 flex-shrink-0"></i>
                    <span><strong>1. Copia el código:</strong> Haz clic en "Copiar Código" para guardar el cupón</span>
                </li>
                <li class="flex items-start">
                    <i class="fas fa-check-circle w-6 text-teal-600 mt-0.5 mr-3 flex-shrink-0"></i>
                    <span><strong>2. Realiza tu reserva:</strong> Selecciona el servicio y el período deseado</span>
                </li>
                <li class="flex items-start">
                    <i class="fas fa-check-circle w-6 text-teal-600 mt-0.5 mr-3 flex-shrink-0"></i>
                    <span><strong>3. Ingresa el código:</strong> Copia el código del cupón en el campo de descuento</span>
                </li>
                <li class="flex items-start">
                    <i class="fas fa-check-circle w-6 text-teal-600 mt-0.5 mr-3 flex-shrink-0"></i>
                    <span><strong>4. Confirma tu reserva:</strong> El descuento se aplicará automáticamente</span>
                </li>
            </ul>

            <div class="mt-6 pt-6 border-t border-gray-300">
                <h3 class="font-semibold text-gray-900 mb-3">⚠️ Términos y Condiciones</h3>
                <ul class="space-y-2 text-sm text-gray-600">
                    <li>• Los cupones son válidos solo durante las fechas especificadas</li>
                    <li>• Un cupón puede usarse una sola vez por usuario (excepto cupones ilimitados)</li>
                    <li>• Los descuentos no son acumulables con otras promociones</li>
                    <li>• Reserva con anticipación para garantizar disponibilidad</li>
                </ul>
            </div>
        </div>
    </main>

    <script>
        function copiarCodigo(codigo) {
            // Copiar al portapapeles
            navigator.clipboard.writeText(codigo).then(() => {
                alert('✓ Código copiado: ' + codigo);
            }).catch(() => {
                // Fallback para navegadores antiguos
                const input = document.createElement('input');
                input.value = codigo;
                document.body.appendChild(input);
                input.select();
                document.execCommand('copy');
                document.body.removeChild(input);
                alert('✓ Código copiado: ' + codigo);
            });
        }
    </script>
    
<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
