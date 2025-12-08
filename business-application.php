<?php
// business-application.php

include 'header.php';
require_once 'init.php';
require_once 'functions.php';

// Aseguramos que la conexión a la DB ($pdo) esté disponible desde init.php
global $pdo; 
$mensaje = '';

// --- FUNCIONES DE LECTURA DE DATOS ---

// Función para obtener la lista de provincias únicas
function get_provincias($pdo) {
    $stmt = $pdo->query("SELECT DISTINCT provincia FROM ubicaciones ORDER BY provincia");
    return $stmt->fetchAll(PDO::FETCH_COLUMN);
}

// --- VALIDACIÓN DE ACCESO (is_comercio) ---

if (!is_comercio()) {
    // Inclusión de SweetAlert para el error de acceso
    echo '<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>';
    echo "
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            Swal.fire({
                icon: 'error',
                title: 'Acceso Denegado',
                text: 'Es necesario registrar un Usuario como Comercio/Negocio para acceder al formulario de aplicación.',
                confirmButtonText: 'Ir a Registro'
            }).then((result) => {
                window.location.href = 'register.php?error=" . urlencode('Acceso denegado. Es necesario registrar un Usuario como Comercio/Negocio para acceder al formulario de aplicación.') . "';
            });
        });
    </script>
    ";
    exit; 
}

// --- PROCESAMIENTO DEL FORMULARIO (POST) ---

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    $user = current_user(); // Obtenemos la información del usuario de la sesión
    $id_usuario_fk = $user['id_usuario'] ?? null;
    
    if (!$id_usuario_fk) {
        $mensaje = '<div class="p-4 mb-4 text-sm text-red-800 rounded-lg bg-red-50">Error de sesión: Usuario no identificado.</div>';
    } else {
        
        // 1. Sanitizar y obtener datos de Negocio
        $nombre_legal = filter_input(INPUT_POST, 'nombre_legal', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $nombre_publico = filter_input(INPUT_POST, 'nombre_publico', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $tipo_negocio = filter_input(INPUT_POST, 'tipo_negocio', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $descripcion_corta = filter_input(INPUT_POST, 'descripcion_negocio', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $telefono_contacto = filter_input(INPUT_POST, 'telefono_negocio', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $correo_contacto = filter_input(INPUT_POST, 'correo_negocio', FILTER_SANITIZE_EMAIL);
        
        // 2. Sanitizar y obtener datos de Hacienda
        $tipo_cedula = filter_input(INPUT_POST, 'tipo_cedula', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $cedula_hacienda = filter_input(INPUT_POST, 'cedula_hacienda', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $nombre_representante = filter_input(INPUT_POST, 'nombre_representante_hacienda', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $no_licencia_municipal = filter_input(INPUT_POST, 'registro_municipal', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        
        // 3. Sanitizar y obtener datos de Ubicación
        $provincia = filter_input(INPUT_POST, 'provincia', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $canton = filter_input(INPUT_POST, 'canton', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $distrito = filter_input(INPUT_POST, 'distrito', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $direccion_exacta = filter_input(INPUT_POST, 'direccion_exacta', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $link_google_maps = filter_input(INPUT_POST, 'google_maps_link', FILTER_SANITIZE_URL);
        $link_waze = filter_input(INPUT_POST, 'waze_link', FILTER_SANITIZE_URL);
        
        // --- Manejo básico de archivos (solo placeholder) ---
        $ruta_cedula_frente = "uploads/cedulas/" . uniqid() . "_frente.pdf"; 
        $ruta_cedula_reverso = "uploads/cedulas/" . uniqid() . "_reverso.pdf"; 
        
        try {
            // A. Asignación de ID_ROL basado en la selección del negocio
            $id_rol_a_asignar = 6; // Por defecto: 6 (Comercio Registrado / Genérico)
            
            if ($tipo_negocio == 'Hospedaje') {
                $id_rol_a_asignar = 4; // ID_ROL = 4 (Hospedaje)
            } elseif ($tipo_negocio == 'Tour / Experiencia') {
                $id_rol_a_asignar = 5; // ID_ROL = 5 (Tour)
            } 
            // Restaurante / Gastronomía, Tienda de Artesanías / Souvenirs, y Otros Comercios usan el ID 6

            // B. Obtener id_categoria
            $stmt_cat = $pdo->prepare("SELECT id_categoria FROM categorias WHERE nombre_categoria = ?");
            $stmt_cat->execute([$tipo_negocio]);
            $id_categoria_fk = $stmt_cat->fetchColumn();

            if (!$id_categoria_fk) {
                $mensaje = '<div class="p-4 mb-4 text-sm text-red-800 rounded-lg bg-red-50">Error: Categoría de negocio no válida.</div>';
                throw new Exception("Categoría no encontrada.");
            }
            
            // C. Obtener id_ubicacion
            $stmt_ubicacion = $pdo->prepare("SELECT id_ubicacion FROM ubicaciones WHERE provincia = ? AND canton = ? AND distrito = ? LIMIT 1");
            $stmt_ubicacion->execute([$provincia, $canton, $distrito]);
            $id_ubicacion_fk = $stmt_ubicacion->fetchColumn();

            if (!$id_ubicacion_fk) {
                $mensaje = '<div class="p-4 mb-4 text-sm text-red-800 rounded-lg bg-red-50">Error: La ubicación seleccionada (Provincia/Cantón/Distrito) no es válida o no existe.</div>';
                throw new Exception("Ubicación no encontrada.");
            }
            
            // D. Insertar en la tabla negocios (id_estatus 3 = Pendiente)
            $sql = "INSERT INTO negocios (
                id_usuario_fk, nombre_legal, nombre_publico, id_categoria_fk, 
                descripcion_corta, telefono_contacto, correo_contacto, 
                tipo_cedula, cedula_hacienda, nombre_representante, no_licencia_municipal,
                ruta_cedula_frente, ruta_cedula_reverso,
                id_ubicacion_fk, direccion_exacta, link_google_maps, link_waze, id_estatus
            ) VALUES (
                :id_usuario_fk, :nombre_legal, :nombre_publico, :id_categoria_fk, 
                :descripcion_corta, :telefono_contacto, :correo_contacto, 
                :tipo_cedula, :cedula_hacienda, :nombre_representante, :no_licencia_municipal,
                :ruta_cedula_frente, :ruta_cedula_reverso,
                :id_ubicacion_fk, :direccion_exacta, :link_google_maps, :link_waze, 3
            )";
            
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                'id_usuario_fk' => $id_usuario_fk,
                'nombre_legal' => $nombre_legal,
                'nombre_publico' => $nombre_publico,
                'id_categoria_fk' => $id_categoria_fk,
                'descripcion_corta' => $descripcion_corta,
                'telefono_contacto' => $telefono_contacto,
                'correo_contacto' => $correo_contacto,
                'tipo_cedula' => $tipo_cedula,
                'cedula_hacienda' => $cedula_hacienda,
                'nombre_representante' => $nombre_representante,
                'no_licencia_municipal' => $no_licencia_municipal,
                'ruta_cedula_frente' => $ruta_cedula_frente,
                'ruta_cedula_reverso' => $ruta_cedula_reverso,
                'id_ubicacion_fk' => $id_ubicacion_fk,
                'direccion_exacta' => $direccion_exacta,
                'link_google_maps' => $link_google_maps,
                'link_waze' => $link_waze
            ]);

            // E. Actualizar el rol y el estatus del usuario AHORA
            $sql_update_user = "UPDATE usuarios SET id_rol = ?, id_estatus = 3 WHERE id_usuario = ?";
            $stmt_update = $pdo->prepare($sql_update_user);
            $stmt_update->execute([$id_rol_a_asignar, $id_usuario_fk]);

            // F. Es recomendable forzar el cierre de sesión para que el nuevo rol se cargue en el próximo login
            session_destroy(); 
            // Opcionalmente, puedes redirigir a una página que le diga que su cuenta está pendiente
            
            echo '<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>';
            echo "
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    Swal.fire({
                        icon: 'success',
                        title: '¡Solicitud Enviada!',
                        text: 'Su solicitud ha sido enviada con éxito. Su cuenta está ahora en estado PENDIENTE y será revisada por un administrador.',
                        confirmButtonText: 'Ir a Login'
                    }).then((result) => {
                        window.location.href = 'login.php';
                    });
                });
            </script>
            ";
            exit;
            
        } catch (PDOException $e) {
            if ($e->getCode() == '23000') { 
                 $mensaje = '<div class="p-4 mb-4 text-sm text-red-800 rounded-lg bg-red-50"> Error: La cédula de hacienda ya está registrada.</div>';
            } else {
                 $mensaje = '<div class="p-4 mb-4 text-sm text-red-800 rounded-lg bg-red-50"> Error en el registro: Intente más tarde.</div>';
            }
        } catch (Exception $e) {
             // El mensaje ya se estableció en el bloque try/catch
        }
    }
}
?>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const provinciaSelect = document.getElementById('provincia');
    const cantonSelect = document.getElementById('canton');
    const distritoSelect = document.getElementById('distrito');

    // Inicializar estado de los dropdowns
    cantonSelect.disabled = true;
    distritoSelect.disabled = true;

    /**
     * Función genérica para hacer la petición AJAX al servidor PHP.
     */
    function fetchAndFill(url, targetSelect, defaultText) {
        // Limpiar el select objetivo y restablecer la opción por defecto
        targetSelect.innerHTML = `<option value="" disabled selected>${defaultText}</option>`;
        targetSelect.disabled = true; // Deshabilitar mientras se carga

        fetch(url)
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                if (data && data.length > 0) {
                    data.forEach(item => {
                        const option = document.createElement('option');
                        option.value = item;
                        option.textContent = item;
                        targetSelect.appendChild(option);
                    });
                    targetSelect.disabled = false; // Habilitar al cargar
                }
            })
            .catch(error => {
                console.error('Fetch error:', error);
            });
    }

    // --- Listeners de Eventos ---

    // 1. Cuando cambia la PROVINCIA
    provinciaSelect.addEventListener('change', function() {
        const provincia = this.value;
        // 1. Limpiar y deshabilitar Cantón y Distrito
        cantonSelect.innerHTML = '<option value="" disabled selected>Seleccione</option>';
        distritoSelect.innerHTML = '<option value="" disabled selected>Seleccione</option>';
        distritoSelect.disabled = true;

        if (provincia) {
            // Llama a get_ubicaciones.php para obtener Cantones
            const url = `get_ubicaciones.php?provincia=${encodeURIComponent(provincia)}`;
            fetchAndFill(url, cantonSelect, 'Cargando Cantones...');
        }
    });

    // 2. Cuando cambia el CANTÓN
    cantonSelect.addEventListener('change', function() {
        const provincia = provinciaSelect.value;
        const canton = this.value;
        
        // 1. Limpiar y deshabilitar Distrito
        distritoSelect.innerHTML = '<option value="" disabled selected>Seleccione</option>';

        if (canton) {
            // Llama a get_ubicaciones.php para obtener Distritos
            const url = `get_ubicaciones.php?provincia=${encodeURIComponent(provincia)}&canton=${encodeURIComponent(canton)}`;
            fetchAndFill(url, distritoSelect, 'Cargando Distritos...');
        }
    });
    
    // Deshabilitar/Habilitar al cambiar el dropdown
    provinciaSelect.addEventListener('change', function() {
        document.getElementById('canton').disabled = !this.value;
        document.getElementById('distrito').disabled = true;
    });
    
    document.getElementById('canton').addEventListener('change', function() {
        document.getElementById('distrito').disabled = !this.value;
    });
});
</script>

<body class="bg-gray-50 min-h-screen">
    <div class="max-w-4xl mx-auto py-12 px-4 sm:px-6 lg:px-8">
        <div class="bg-white p-8 rounded-xl shadow-2xl">
            <h1 class="text-3xl font-bold text-center text-teal-600 mb-6">Solicitud de Certificación de Negocio Local</h1>
            <p class="text-center text-gray-500 mb-8">
                Complete este formulario para iniciar el proceso de validación.
            </p>
            
            <?php echo $mensaje; // Mostrar mensaje de error/éxito si hay un fallo sin SweetAlert ?>

            <form id="business-application-form" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST" class="space-y-6">
                
                <div class="border-b border-gray-200 pb-5">
                    <h2 class="text-xl font-semibold text-gray-900">1. Datos del Negocio</h2>
                    <p class="mt-1 text-sm text-gray-500">Información pública y de contacto del comercio.</p>

                    <div class="mt-4 grid grid-cols-1 gap-y-4 sm:grid-cols-2 sm:gap-x-6">
                        <div>
                            <label for="nombre_legal" class="block text-sm font-medium text-gray-700">Nombre Legal del Negocio</label>
                            <input type="text" id="nombre_legal" name="nombre_legal" required
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-teal-500 focus:ring-teal-500 p-2 border">
                        </div>
                        <div>
                            <label for="nombre_publico" class="block text-sm font-medium text-gray-700">Nombre Público/Comercial</label>
                            <input type="text" id="nombre_publico" name="nombre_publico" required
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-teal-500 focus:ring-teal-500 p-2 border">
                        </div>
                        
                        <div class="sm:col-span-2">
                            <label for="tipo_negocio" class="block text-sm font-medium text-gray-700">Categoría del Negocio</label>
                            <select id="tipo_negocio" name="tipo_negocio" required
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-teal-500 focus:ring-teal-500 p-2 border">
                                <option value="" disabled selected>Seleccione un tipo</option>
                                <option value="Hospedaje">Hospedaje</option>
                                <option value="Tour / Experiencia">Tour / Experiencia</option>
                                <option value="Restaurante / Gastronomía">Restaurante / Gastronomía</option>
                                <option value="Tienda de Artesanías / Souvenirs">Tienda de Artesanías / Souvenirs</option>
                                <option value="Otros Comercios">Otros Comercios Locales</option>
                            </select>
                        </div>

                        <div class="sm:col-span-2">
                            <label for="descripcion_negocio" class="block text-sm font-medium text-gray-700">Descripción Corta</label>
                            <textarea id="descripcion_negocio" name="descripcion_negocio" rows="3" required
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-teal-500 focus:ring-teal-500 p-2 border"></textarea>
                        </div>
                        <div>
                            <label for="telefono_negocio" class="block text-sm font-medium text-gray-700">Teléfono de Contacto</label>
                            <input type="tel" id="telefono_negocio" name="telefono_negocio" required
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-teal-500 focus:ring-teal-500 p-2 border">
                        </div>
                        <div>
                            <label for="correo_negocio" class="block text-sm font-medium text-gray-700">Correo Público</label>
                            <input type="email" id="correo_negocio" name="correo_negocio" required
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-teal-500 focus:ring-teal-500 p-2 border">
                        </div>
                    </div>
                </div>

                <div class="border-b border-gray-200 pb-5">
                    <h2 class="text-xl font-semibold text-gray-900">2. Documentación de Validación y Hacienda</h2>
                    <p class="mt-1 text-sm text-gray-500">
                        Información requerida para la verificación de su negocio en Hacienda.
                    </p>

                    <div class="mt-4 grid grid-cols-1 gap-y-4 sm:grid-cols-3 sm:gap-x-6">
                        <div>
                            <label for="tipo_cedula" class="block text-sm font-medium text-gray-700">Tipo de Cédula (Hacienda)</label>
                            <select id="tipo_cedula" name="tipo_cedula" required
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-teal-500 focus:ring-teal-500 p-2 border">
                                <option value="" disabled selected>Seleccione Tipo</option>
                                <option value="Fisica">Cédula Física (Nacional)</option>
                                <option value="Juridica">Cédula Jurídica</option>
                                <option value="Dimex">DIMEX</option>
                                <option value="Nite">NITE</option>
                            </select>
                        </div>

                        <div class="sm:col-span-2">
                            <label for="cedula_hacienda" class="block text-sm font-medium text-gray-700">Cédula o Identificación (Hacienda)</label>
                            <input type="text" id="cedula_hacienda" name="cedula_hacienda" required
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-teal-500 focus:ring-teal-500 p-2 border" 
                                placeholder="Ej: 1-0000-0000 para Físicas">
                        </div>

                        <div class="sm:col-span-3">
                            <label for="nombre_representante_hacienda" class="block text-sm font-medium text-gray-700">Nombre del Representante Legal (en Hacienda)</label>
                            <input type="text" id="nombre_representante_hacienda" name="nombre_representante_hacienda" required
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-teal-500 focus:ring-teal-500 p-2 border">
                        </div>

                        <div>
                            <label for="foto_cedula_frente" class="block text-sm font-medium text-gray-700">Subir Cédula (Frente)</label>
                            <input type="file" id="foto_cedula_frente" name="foto_cedula_frente" accept=".jpg,.png,.pdf" required
                                class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-teal-50 file:text-teal-700 hover:file:bg-teal-100">
                        </div>

                        <div>
                            <label for="foto_cedula_reverso" class="block text-sm font-medium text-gray-700">Subir Cédula (Reverso)</label>
                            <input type="file" id="foto_cedula_reverso" name="foto_cedula_reverso" accept=".jpg,.png,.pdf" required
                                class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-teal-50 file:text-teal-700 hover:file:bg-teal-100">
                        </div>

                        <div>
                            <label for="registro_municipal" class="block text-sm font-medium text-gray-700">No. de Licencia Municipal/Permiso Sanitario</label>
                            <input type="text" id="registro_municipal" name="registro_municipal" required
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-teal-500 focus:ring-teal-500 p-2 border">
                        </div>
                    </div>
                </div>

                <div class="border-b border-gray-200 pb-5">
                    <h2 class="text-xl font-semibold text-gray-900">3. Ubicación y Dirección</h2>
                    <p class="mt-1 text-sm text-gray-500">Datos necesarios para la búsqueda geolocalizada por provincia, cantón y distrito.</p>
                    
                    <div class="mt-4 grid grid-cols-1 gap-y-4 sm:grid-cols-3 sm:gap-x-6">
                        
                        <div>
                            <label for="provincia" class="block text-sm font-medium text-gray-700">Provincia</label>
                            <select id="provincia" name="provincia" required
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-teal-500 focus:ring-teal-500 p-2 border">
                                <option value="" disabled selected>Seleccione</option>
                                <?php 
                                $provincias = get_provincias($pdo);
                                foreach ($provincias as $p) {
                                    echo "<option value=\"" . htmlspecialchars($p) . "\">$p</option>";
                                }
                                ?>
                            </select>
                        </div>
                        
                        <div>
                            <label for="canton" class="block text-sm font-medium text-gray-700">Cantón</label>
                            <select id="canton" name="canton" required
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-teal-500 focus:ring-teal-500 p-2 border" disabled>
                                <option value="" disabled selected>Seleccione</option>
                            </select>
                        </div>
                        
                        <div>
                            <label for="distrito" class="block text-sm font-medium text-gray-700">Distrito</label>
                            <select id="distrito" name="distrito" required
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-teal-500 focus:ring-teal-500 p-2 border" disabled>
                                <option value="" disabled selected>Seleccione</option>
                            </select>
                        </div>
                        
                        <div class="sm:col-span-3">
                            <label for="direccion_exacta" class="block text-sm font-medium text-gray-700">Dirección Exacta (Punto de Referencia)</label>
                            <textarea id="direccion_exacta" name="direccion_exacta" rows="3" required
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-teal-500 focus:ring-teal-500 p-2 border" placeholder="50 metros al norte del Banco Nacional..."></textarea>
                        </div>

                        <div class="sm:col-span-3 grid grid-cols-1 gap-y-4 sm:grid-cols-2 sm:gap-x-6">
                            <div>
                                <label for="google_maps_link" class="block text-sm font-medium text-gray-700">Link de Google Maps (Opcional)</label>
                                <input type="url" id="google_maps_link" name="google_maps_link"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-teal-500 focus:ring-teal-500 p-2 border">
                            </div>
                            <div>
                                <label for="waze_link" class="block text-sm font-medium text-gray-700">Link de Waze (Opcional)</label>
                                <input type="url" id="waze_link" name="waze_link"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-teal-500 focus:ring-teal-500 p-2 border">
                            </div>
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
    </div>
  </body>
</html>