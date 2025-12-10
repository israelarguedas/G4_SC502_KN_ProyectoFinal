<?php 
$pageTitle = 'Solicitud de Certificación - TicoTrips';
require_once __DIR__ . '/../layouts/header.php'; 
// Asumiendo que $provincias está disponible desde el controlador: $provincias = $this->businessModel->getProvincias();
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
        
        <?php if(isset($_SESSION['success'])): ?>
            <div class="mb-6 p-4 text-sm text-green-800 rounded-lg bg-green-50">
                <?= htmlspecialchars($_SESSION['success']) ?>
            </div>
            <?php unset($_SESSION['success']); ?>
        <?php endif; ?>


        <form action="index.php?controller=business&action=submitApplication" method="POST" enctype="multipart/form-data" class="space-y-6">
            <div class="space-y-4">
                <h2 class="text-xl font-semibold text-gray-900">Información del Negocio</h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="nombre_legal" class="block text-sm font-medium text-gray-700">Nombre Legal / Razón Social <span class="text-red-500">*</span></label>
                        <input type="text" id="nombre_legal" name="nombre_legal" required
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-teal-500 focus:ring-teal-500 p-2 border">
                    </div>
                    <div>
                        <label for="nombre_publico" class="block text-sm font-medium text-gray-700">Nombre Comercial Público <span class="text-red-500">*</span></label>
                        <input type="text" id="nombre_publico" name="nombre_publico" required
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-teal-500 focus:ring-teal-500 p-2 border">
                    </div>
                </div>

                <div>
                    <label for="descripcion_corta" class="block text-sm font-medium text-gray-700">Descripción Corta (Máx. 255 caracteres) <span class="text-red-500">*</span></label>
                    <textarea id="descripcion_corta" name="descripcion_corta" rows="3" maxlength="255" required
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-teal-500 focus:ring-teal-500 p-2 border"></textarea>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="tipo_negocio" class="block text-sm font-medium text-gray-700">Tipo de Negocio <span class="text-red-500">*</span></label>
                        <select id="tipo_negocio" name="tipo_negocio" required
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-teal-500 focus:ring-teal-500 p-2 border">
                            <option value="">Seleccione el tipo</option>
                            <option value="Hospedaje">Hospedaje</option>
                            <option value="Tour / Experiencia">Tour / Experiencia</option>
                            <option value="Restaurante / Gastronomia">Restaurante / Gastronomía</option> 
                            <option value="Tienda de Artesanias / Souvenirs">Tienda de Artesanías / Souvenirs</option> 
                            <option value="Transporte">Transporte</option>
                            <option value="Otros Comercios">Otros Comercios</option>
                        </select>
                    </div>
                    <div>
                        <label for="telefono_contacto" class="block text-sm font-medium text-gray-700">Teléfono de Contacto <span class="text-red-500">*</span></label>
                        <input type="tel" id="telefono_contacto" name="telefono_contacto" required
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-teal-500 focus:ring-teal-500 p-2 border">
                    </div>
                </div>
                <div>
                    <label for="correo_contacto" class="block text-sm font-medium text-gray-700">Correo Electrónico de Contacto <span class="text-red-500">*</span></label>
                    <input type="email" id="correo_contacto" name="correo_contacto" required
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-teal-500 focus:ring-teal-500 p-2 border">
                </div>
            </div>

            <div class="space-y-4 pt-6 border-t border-gray-200">
                <h2 class="text-xl font-semibold text-gray-900">Información Fiscal y Legal</h2>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label for="tipo_cedula" class="block text-sm font-medium text-gray-700">Tipo de Cédula (Física o Jurídica) <span class="text-red-500">*</span></label>
                        <select id="tipo_cedula" name="tipo_cedula" required
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-teal-500 focus:ring-teal-500 p-2 border">
                            <option value="">Seleccione</option>
                            <option value="Fisica">Física</option>
                            <option value="Juridica">Jurídica</option>
                        </select>
                    </div>
                    <div class="col-span-2">
                        <label for="cedula_hacienda" class="block text-sm font-medium text-gray-700">Cédula Física / Número de Cédula Jurídica (Hacienda) <span class="text-red-500">*</span></label>
                        <input type="text" id="cedula_hacienda" name="cedula_hacienda" required
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-teal-500 focus:ring-teal-500 p-2 border" placeholder="Ej: 3-101-123456 o 1-050-001234">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="nombre_representante" class="block text-sm font-medium text-gray-700">Nombre del Representante Legal <span class="text-red-500">*</span></label>
                        <input type="text" id="nombre_representante" name="nombre_representante" required
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-teal-500 focus:ring-teal-500 p-2 border">
                    </div>
                    <div>
                        <label for="no_licencia_municipal" class="block text-sm font-medium text-gray-700">Número de Licencia Municipal (Opcional)</label>
                        <input type="text" id="no_licencia_municipal" name="no_licencia_municipal"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-teal-500 focus:ring-teal-500 p-2 border">
                    </div>
                </div>

                <p class="text-sm text-gray-500 italic">Los archivos de cédula son obligatorios para la certificación final, pero opcionales para pruebas.</p>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="foto_cedula_frente" class="block text-sm font-medium text-gray-700">Foto Cédula (Frente)</label>
                        <input type="file" id="foto_cedula_frente" name="foto_cedula_frente" 
                            class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-teal-50 file:text-teal-700 hover:file:bg-teal-100">
                    </div>
                    <div>
                        <label for="foto_cedula_reverso" class="block text-sm font-medium text-gray-700">Foto Cédula (Reverso)</label>
                        <input type="file" id="foto_cedula_reverso" name="foto_cedula_reverso" 
                            class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-teal-50 file:text-teal-700 hover:file:bg-teal-100">
                    </div>
                </div>
            </div>

            <div class="space-y-4 pt-6 border-t border-gray-200">
                <h2 class="text-xl font-semibold text-gray-900">Ubicación</h2>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label for="provincia" class="block text-sm font-medium text-gray-700">Provincia <span class="text-red-500">*</span></label>
                        <select id="provincia" name="provincia" required
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-teal-500 focus:ring-teal-500 p-2 border">
                            <option value="">Seleccione...</option>
                            <?php 
                            // $provincias debe estar disponible desde el controlador showApplication()
                            if (isset($provincias) && is_array($provincias)) {
                                foreach ($provincias as $prov) {
                                    echo '<option value="' . htmlspecialchars($prov) . '">' . htmlspecialchars($prov) . '</option>';
                                }
                            }
                            ?>
                        </select>
                    </div>
                    <div>
                        <label for="canton" class="block text-sm font-medium text-gray-700">Cantón <span class="text-red-500">*</span></label>
                        <select id="canton" name="canton" required disabled
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-teal-500 focus:ring-teal-500 p-2 border">
                            <option value="">Seleccione...</option>
                        </select>
                    </div>
                    <div>
                        <label for="distrito" class="block text-sm font-medium text-gray-700">Distrito <span class="text-red-500">*</span></label>
                        <select id="distrito" name="distrito" required disabled
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-teal-500 focus:ring-teal-500 p-2 border">
                            <option value="">Seleccione...</option>
                        </select>
                    </div>
                </div>
                
                <div>
                    <label for="direccion_exacta" class="block text-sm font-medium text-gray-700">Dirección Exacta <span class="text-red-500">*</span></label>
                    <textarea id="direccion_exacta" name="direccion_exacta" rows="3" required
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-teal-500 focus:ring-teal-500 p-2 border"></textarea>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="link_google_maps" class="block text-sm font-medium text-gray-700">Link de Google Maps (Opcional)</label>
                        <input type="url" id="link_google_maps" name="link_google_maps"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-teal-500 focus:ring-teal-500 p-2 border">
                    </div>
                    <div>
                        <label for="link_waze" class="block text-sm font-medium text-gray-700">Link de Waze (Opcional)</label>
                        <input type="url" id="link_waze" name="link_waze"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-teal-500 focus:ring-teal-500 p-2 border">
                    </div>
                </div>

            </div>


            <div class="pt-5">
                <button type="submit"
                    class="w-full py-3 px-4 border border-transparent rounded-md shadow-sm text-base font-medium text-white bg-teal-600 hover:bg-teal-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-teal-500 transition duration-150">
                    Enviar Solicitud para Aprobación
                </button>
            </div>
        </form>
    </div>
</main>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const provinciaSelect = document.getElementById('provincia');
        const cantonSelect = document.getElementById('canton');
        const distritoSelect = document.getElementById('distrito');

        // Función para cargar datos desde el nuevo endpoint MVC
        async function fetchUbicaciones(params, targetSelect, defaultText) {
            
            const url = `index.php?controller=business&action=getUbicacionesAjax&${new URLSearchParams(params)}`;
            
            targetSelect.innerHTML = `<option value="" disabled selected>${defaultText}</option>`;
            targetSelect.disabled = true;

            if (targetSelect === cantonSelect) {
                distritoSelect.innerHTML = '<option value="" disabled selected>Seleccione...</option>';
                distritoSelect.disabled = true;
            }

            try {
                const response = await fetch(url);
                const data = await response.json(); 

                targetSelect.innerHTML = '<option value="" disabled selected>Seleccione...</option>';

                if (data && data.length > 0) {
                    data.forEach(item => {
                        const option = document.createElement('option');
                        option.value = item;
                        option.textContent = item;
                        targetSelect.appendChild(option);
                    });
                    targetSelect.disabled = false;
                } else if (data.error) {
                    console.error("Error del servidor (JSON):", data.error);
                    targetSelect.innerHTML = `<option value="" disabled selected>Error: ${data.error}</option>`;
                } else {
                    targetSelect.innerHTML = '<option value="" disabled selected>No se encontraron resultados</option>';
                }
            } catch (error) {
                console.error("Error de Fetch/JSON:", error.message, "La URL solicitada fue:", url);
                targetSelect.innerHTML = '<option value="" disabled selected>Error de carga (Revisar consola)</option>';
            }
        }

        // --- 1. Cuando cambia la Provincia ---
        provinciaSelect.addEventListener('change', async function() {
            const provincia = this.value;
            if (provincia) {
                fetchUbicaciones({ provincia: provincia }, cantonSelect, 'Cargando Cantones...');
            }
        });

        // --- 2. Cuando cambia el Cantón ---
        cantonSelect.addEventListener('change', async function() {
            const provincia = provinciaSelect.value;
            const canton = this.value;
            if (provincia && canton) {
                fetchUbicaciones({ provincia: provincia, canton: canton }, distritoSelect, 'Cargando Distritos...');
            }
        });
    });
</script>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>