<?php

class User {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function getUserById($id) {
        try {
            $stmt = $this->pdo->prepare("
                SELECT id_usuario, nombre_completo, email, telefono, fecha_nacimiento, 
                       id_rol, id_estatus, foto_perfil
                FROM usuarios 
                WHERE id_usuario = ?
            ");
            $stmt->execute([$id]);
            return $stmt->fetch();
        } catch (PDOException $e) {
            Logger::error("Error obteniendo usuario: " . $e->getMessage());
            throw $e;
        }
    }

    public function updateProfile($id, $data) {
        try {
            $sql = "UPDATE usuarios SET 
                    nombre_completo = :nombre,
                    telefono = :telefono,
                    fecha_nacimiento = :fecha_nacimiento
                    WHERE id_usuario = :id";
            
            $stmt = $this->pdo->prepare($sql);
            $result = $stmt->execute([
                'nombre' => $data['nombre_completo'],
                'telefono' => $data['telefono'],
                'fecha_nacimiento' => $data['fecha_nacimiento'],
                'id' => $id
            ]);

            if ($result) {
                Logger::info("Perfil actualizado para usuario ID: " . $id);
                return ['success' => true, 'message' => 'Perfil actualizado exitosamente'];
            }

            return ['success' => false, 'message' => 'Error al actualizar perfil'];
        } catch (PDOException $e) {
            Logger::error("Error actualizando perfil: " . $e->getMessage());
            return ['success' => false, 'message' => 'Error al actualizar perfil'];
        }
    }

    public function uploadProfilePhoto($id, $file) {
        try {
            // Validar archivo
            $allowed_types = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif'];
            $max_size = 5 * 1024 * 1024; // 5MB

            if (!in_array($file['type'], $allowed_types)) {
                return ['success' => false, 'message' => 'Formato de imagen no válido'];
            }

            if ($file['size'] > $max_size) {
                return ['success' => false, 'message' => 'La imagen es muy grande (máximo 5MB)'];
            }

            // Crear directorio si no existe
            $upload_dir = __DIR__ . '/../../app/public/images/profiles/';
            if (!is_dir($upload_dir)) {
                mkdir($upload_dir, 0755, true);
            }

            // Generar nombre único
            $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
            $filename = 'profile_' . $id . '_' . time() . '.' . $extension;
            $filepath = $upload_dir . $filename;

            // Mover archivo
            if (move_uploaded_file($file['tmp_name'], $filepath)) {
                // Actualizar base de datos
                $sql = "UPDATE usuarios SET foto_perfil = :ruta WHERE id_usuario = :id";
                $stmt = $this->pdo->prepare($sql);
                $stmt->execute([
                    'ruta' => 'app/public/images/profiles/' . $filename,
                    'id' => $id
                ]);

                return ['success' => true, 'message' => 'Foto de perfil actualizada'];
            }

            return ['success' => false, 'message' => 'Error al subir la imagen'];
        } catch (Exception $e) {
            Logger::error("Error subiendo foto de perfil: " . $e->getMessage());
            return ['success' => false, 'message' => 'Error al subir la imagen'];
        }
    }
}
