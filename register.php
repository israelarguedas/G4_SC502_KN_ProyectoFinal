<?php
include 'header.php';

// Definición de ID de Roles basada en tus INSERTS
define('ROL_CLIENTE', 2);
define('ROL_COMERCIO', 3); 

$mensaje = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // 1. Obtener y sanitizar los datos del formulario
    $nombre = filter_input(INPUT_POST, 'nombre', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $correo = filter_input(INPUT_POST, 'correo', FILTER_SANITIZE_EMAIL);
    $telefono = filter_input(INPUT_POST, 'telefono', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $fecha_nacimiento = filter_input(INPUT_POST, 'fecha_nacimiento', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $password_plana = $_POST['password'] ?? '';
    $id_rol_seleccionado = filter_input(INPUT_POST, 'rol', FILTER_VALIDATE_INT);
    
    // 2. Validaciones básicas
    if (empty($nombre) || empty($correo) || empty($password_plana) || empty($id_rol_seleccionado)) {
        $mensaje = '<div class="p-4 mb-4 text-sm text-red-800 rounded-lg bg-red-50">Por favor, complete todos los campos requeridos.</div>';
    } else if (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
        $mensaje = '<div class="p-4 mb-4 text-sm text-red-800 rounded-lg bg-red-50">El formato del correo electrónico no es válido.</div>';
    } else {
        // 3. Procesamiento seguro de la contraseña
        $password_hash = password_hash($password_plana, PASSWORD_DEFAULT);
        
        // 4. Asignar el rol correcto
        if ($id_rol_seleccionado == ROL_CLIENTE) {
            $id_rol = ROL_CLIENTE;
            $rol_texto = 'Cliente';
        } else if ($id_rol_seleccionado == ROL_COMERCIO) {
            $id_rol = ROL_COMERCIO;
            $rol_texto = 'Comercio';
        } else {
            // Asignar rol Cliente por defecto si hay manipulación
            $id_rol = ROL_CLIENTE; 
            $rol_texto = 'Cliente (Por defecto)';
        }

        // 5. Inserción en la base de datos (Requiere conexión a DB)
        // Asegúrate de tener tu conexión a DB ($pdo) aquí.
        try {
            $sql = "INSERT INTO usuarios (nombre_completo, email, telefono, fecha_nacimiento, password_hash, id_rol, id_estatus) 
                    VALUES (:nombre, :email, :telefono, :fecha_nacimiento, :password_hash, :id_rol, 1)"; 
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                'nombre' => $nombre,
                'email' => $correo,
                'telefono' => $telefono,
                'fecha_nacimiento' => ($fecha_nacimiento ?: NULL), // Insertar NULL si no se proporciona fecha
                'password_hash' => $password_hash,
                'id_rol' => $id_rol
            ]);

            $mensaje = '<div class="p-4 mb-4 text-sm text-green-800 rounded-lg bg-green-50">¡Registro exitoso como ' . $rol_texto . '!</div>';
            
        } catch (PDOException $e) {
            if ($e->getCode() == '23000') { // Error de duplicado (ej. correo)
                 $mensaje = '<div class="p-4 mb-4 text-sm text-red-800 rounded-lg bg-red-50"> Error: El correo electrónico ya está registrado.</div>';
            } else {
                 $mensaje = '<div class="p-4 mb-4 text-sm text-red-800 rounded-lg bg-red-50"> Error en el registro: Intente más tarde.</div>';
            }
        }
    }
}
?>

<body class="bg-gray-100 min-h-screen">
    <div class="w-full max-w-lg bg-white p-8 rounded-xl shadow-2xl mx-auto sm:mt-20 my-10">
        <h2 class="text-2xl font-bold text-gray-800 mb-6 text-center">Registro de Usuario</h2>
        
        <?php echo $mensaje; ?>

        <form id="user-register-form" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
            <div class="space-y-4">
                
                <div>
                    <label for="reg-user-nombre" class="block text-sm font-medium text-gray-700">Nombre Completo</label>
                    <input type="text" id="reg-user-nombre" name="nombre" required
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-teal-500 focus:ring-teal-500 sm:text-sm p-2 border"
                        value="<?php echo isset($nombre) ? htmlspecialchars($nombre) : ''; ?>">
                </div>
                
                <div>
                    <label for="reg-user-email" class="block text-sm font-medium text-gray-700">Correo Electrónico</label>
                    <input type="email" id="reg-user-email" name="correo" required
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-teal-500 focus:ring-teal-500 sm:text-sm p-2 border"
                        value="<?php echo isset($correo) ? htmlspecialchars($correo) : ''; ?>">
                </div>
                
                <div>
                    <label for="reg-user-password" class="block text-sm font-medium text-gray-700">Contraseña</label>
                    <input type="password" id="reg-user-password" name="password" required
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-teal-500 focus:ring-teal-500 sm:text-sm p-2 border">
                </div>

                <div>
                    <label for="reg-user-telefono" class="block text-sm font-medium text-gray-700">Teléfono</label>
                    <input type="tel" id="reg-user-telefono" name="telefono"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-teal-500 focus:ring-teal-500 sm:text-sm p-2 border"
                        value="<?php echo isset($telefono) ? htmlspecialchars($telefono) : ''; ?>">
                </div>
                
                <div>
                    <label for="reg-user-fecha-nacimiento" class="block text-sm font-medium text-gray-700">Fecha de Nacimiento (Opcional)</label>
                    <input type="date" id="reg-user-fecha-nacimiento" name="fecha_nacimiento"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-teal-500 focus:ring-teal-500 sm:text-sm p-2 border"
                        value="<?php echo isset($fecha_nacimiento) ? htmlspecialchars($fecha_nacimiento) : ''; ?>">
                </div>

                <div>
                    <label for="reg-user-rol" class="block text-sm font-medium text-gray-700">Tipo de Registro</label>
                    <select id="reg-user-rol" name="rol" required
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-teal-500 focus:ring-teal-500 sm:text-sm p-2 border">
                        <option value="">Seleccione...</option>
                        <option value="<?php echo ROL_CLIENTE; ?>" 
                            <?php echo (isset($id_rol_seleccionado) && $id_rol_seleccionado == ROL_CLIENTE) ? 'selected' : ''; ?>>
                            Usuario Regular (Cliente)
                        </option>
                        <option value="<?php echo ROL_COMERCIO; ?>"
                            <?php echo (isset($id_rol_seleccionado) && $id_rol_seleccionado == ROL_COMERCIO) ? 'selected' : ''; ?>>
                            Comercio (Negocio/Empresa)
                        </option>
                    </select>
                </div>

                <button type="submit"
                    class="w-full py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-teal-600 hover:bg-teal-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-teal-500 transition duration-150">
                    Registrarme
                </button>
            </div>
        </form>
    </div>
  </body>
</html>