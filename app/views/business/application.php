<?php 
$pageTitle = 'Solicitud de Certificación - TicoTrips';
require_once __DIR__ . '/../layouts/header.php'; 
?>

<main class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="bg-white rounded-lg shadow-sm p-8">
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900">Solicitud de Certificación de Negocio</h1>
            <p class="mt-2 text-gray-600">Complete el formulario para registrar su negocio turístico en TicoTrips</p>
        </div>

        <?php if(isset($_SESSION['error'])): ?>
            <div class="mb-6 p-4 text-sm text-red-800 rounded-lg bg-red-50">
                <?= htmlspecialchars($_SESSION['error']) ?>
            </div>
            <?php unset($_SESSION['error']); ?>
        <?php endif; ?>

        <form action="index.php?controller=business&action=submitApplication" method="POST" enctype="multipart/form-data" class="space-y-6">
            <!-- Información del Negocio -->
            <div class="space-y-4">
                <h2 class="text-xl font-semibold text-gray-900">Información del Negocio</h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Nombre Público *</label>
                        <input type="text" name="nombre_publico" required
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-teal-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Razón Social</label>
                        <input type="text" name="razon_social"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-teal-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Cédula Jurídica *</label>
                        <input type="text" name="cedula_juridica" required
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-teal-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Teléfono *</label>
                        <input type="tel" name="telefono" required
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-teal-500">
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Descripción del Negocio *</label>
                    <textarea name="descripcion" rows="4" required
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-teal-500"
                        placeholder="Describe tu negocio, servicios y experiencias que ofreces..."></textarea>
                </div>
            </div>

            <!-- Ubicación -->
            <div class="space-y-4">
                <h2 class="text-xl font-semibold text-gray-900">Ubicación</h2>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Provincia *</label>
                        <select name="provincia" id="provincia" required
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-teal-500">
                            <option value="">Seleccione...</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Cantón *</label>
                        <select name="canton" id="canton" required
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-teal-500">
                            <option value="">Seleccione...</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Distrito *</label>
                        <select name="distrito" id="distrito" required
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-teal-500">
                            <option value="">Seleccione...</option>
                        </select>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Dirección Exacta *</label>
                    <input type="text" name="direccion_exacta" required
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-teal-500"
                        placeholder="Ej: 200m sur de la iglesia católica">
                </div>
            </div>

            <!-- Documentación -->
            <div class="space-y-4">
                <h2 class="text-xl font-semibold text-gray-900">Documentación</h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Certificado de Personería Jurídica</label>
                        <input type="file" name="personeria_juridica" accept=".pdf,.jpg,.jpeg,.png"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-teal-500">
                        <p class="mt-1 text-xs text-gray-500">PDF, JPG o PNG (máx. 5MB)</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Licencia Comercial</label>
                        <input type="file" name="licencia_comercial" accept=".pdf,.jpg,.jpeg,.png"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-teal-500">
                        <p class="mt-1 text-xs text-gray-500">PDF, JPG o PNG (máx. 5MB)</p>
                    </div>
                </div>
            </div>

            <!-- Horarios -->
            <div class="space-y-4">
                <h2 class="text-xl font-semibold text-gray-900">Horario de Atención</h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Hora de Apertura</label>
                        <input type="time" name="hora_apertura"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-teal-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Hora de Cierre</label>
                        <input type="time" name="hora_cierre"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-teal-500">
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Días de Operación</label>
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-2">
                        <label class="flex items-center space-x-2">
                            <input type="checkbox" name="dias[]" value="Lunes" class="rounded text-teal-600">
                            <span class="text-sm">Lunes</span>
                        </label>
                        <label class="flex items-center space-x-2">
                            <input type="checkbox" name="dias[]" value="Martes" class="rounded text-teal-600">
                            <span class="text-sm">Martes</span>
                        </label>
                        <label class="flex items-center space-x-2">
                            <input type="checkbox" name="dias[]" value="Miércoles" class="rounded text-teal-600">
                            <span class="text-sm">Miércoles</span>
                        </label>
                        <label class="flex items-center space-x-2">
                            <input type="checkbox" name="dias[]" value="Jueves" class="rounded text-teal-600">
                            <span class="text-sm">Jueves</span>
                        </label>
                        <label class="flex items-center space-x-2">
                            <input type="checkbox" name="dias[]" value="Viernes" class="rounded text-teal-600">
                            <span class="text-sm">Viernes</span>
                        </label>
                        <label class="flex items-center space-x-2">
                            <input type="checkbox" name="dias[]" value="Sábado" class="rounded text-teal-600">
                            <span class="text-sm">Sábado</span>
                        </label>
                        <label class="flex items-center space-x-2">
                            <input type="checkbox" name="dias[]" value="Domingo" class="rounded text-teal-600">
                            <span class="text-sm">Domingo</span>
                        </label>
                    </div>
                </div>
            </div>

            <!-- Términos y Condiciones -->
            <div class="flex items-start">
                <input type="checkbox" required class="mt-1 rounded text-teal-600 focus:ring-teal-500">
                <label class="ml-3 text-sm text-gray-700">
                    Acepto los <a href="#" class="text-teal-600 hover:text-teal-700">términos y condiciones</a> 
                    y confirmo que la información proporcionada es veraz *
                </label>
            </div>

            <!-- Botones -->
            <div class="flex justify-end gap-4 pt-6 border-t">
                <a href="index.php" 
                   class="px-6 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition">
                    Cancelar
                </a>
                <button type="submit"
                    class="px-6 py-2 bg-teal-600 text-white rounded-lg hover:bg-teal-700 transition">
                    Enviar Solicitud
                </button>
            </div>
        </form>
    </div>
</main>

<script>
// Cargar ubicaciones desde el JSON
fetch('app/public/js/apiMockup/infoGeografica.json')
    .then(response => response.json())
    .then(data => {
        const provinciaSelect = document.getElementById('provincia');
        const cantonSelect = document.getElementById('canton');
        const distritoSelect = document.getElementById('distrito');

        // Llenar provincias
        data.forEach(provincia => {
            const option = document.createElement('option');
            option.value = provincia.provincia;
            option.textContent = provincia.provincia;
            provinciaSelect.appendChild(option);
        });

        // Actualizar cantones cuando cambie la provincia
        provinciaSelect.addEventListener('change', function() {
            cantonSelect.innerHTML = '<option value="">Seleccione...</option>';
            distritoSelect.innerHTML = '<option value="">Seleccione...</option>';
            
            const provinciaData = data.find(p => p.provincia === this.value);
            if (provinciaData) {
                provinciaData.cantones.forEach(canton => {
                    const option = document.createElement('option');
                    option.value = canton.canton;
                    option.textContent = canton.canton;
                    cantonSelect.appendChild(option);
                });
            }
        });

        // Actualizar distritos cuando cambie el cantón
        cantonSelect.addEventListener('change', function() {
            distritoSelect.innerHTML = '<option value="">Seleccione...</option>';
            
            const provinciaData = data.find(p => p.provincia === provinciaSelect.value);
            if (provinciaData) {
                const cantonData = provinciaData.cantones.find(c => c.canton === this.value);
                if (cantonData) {
                    cantonData.distritos.forEach(distrito => {
                        const option = document.createElement('option');
                        option.value = distrito;
                        option.textContent = distrito;
                        distritoSelect.appendChild(option);
                    });
                }
            }
        });
    });
</script>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
