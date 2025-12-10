<?php

class Auth {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function getUserByEmail($email) {
        try {
            $stmt = $this->pdo->prepare("
                SELECT id_usuario, nombre_completo, email, telefono, fecha_Nacimiento, 
                       password_hash, id_rol, id_estatus 
                FROM usuarios 
                WHERE email = ? 
                LIMIT 1
            ");
            $stmt->execute([$email]);
            return $stmt->fetch();
        } catch (PDOException $e) {
            Logger::error("Error obteniendo usuario por email: " . $e->getMessage());
            throw $e;
        }
    }

    public function createUser($data) {
        try {
            // Verificar si el correo ya existe
            $checkStmt = $this->pdo->prepare("SELECT id_usuario FROM usuarios WHERE email = ?");
            $checkStmt->execute([$data['email']]);
            
            if ($checkStmt->rowCount() > 0) {
                return ['success' => false, 'message' => 'El correo electrónico ya está registrado.'];
            }

            // Hash de la contraseña
            $password_hash = password_hash($data['password'], PASSWORD_DEFAULT);

            // Insertar usuario
            $sql = "INSERT INTO usuarios (
                nombre_completo, email, telefono, fecha_nacimiento, 
                password_hash, id_rol, id_estatus
            ) VALUES (
                :nombre, :email, :telefono, :fecha_nacimiento, 
                :password_hash, :id_rol, 1
            )";
            
            $stmt = $this->pdo->prepare($sql);
            $result = $stmt->execute([
                'nombre' => $data['nombre'],
                'email' => $data['email'],
                'telefono' => $data['telefono'],
                'fecha_nacimiento' => $data['fecha_nacimiento'],
                'password_hash' => $password_hash,
                'id_rol' => $data['id_rol']
            ]);

            if ($result) {
                Logger::info("Nuevo usuario registrado: " . $data['email']);
                return ['success' => true, 'message' => 'Registro exitoso'];
            }

            return ['success' => false, 'message' => 'Error al crear usuario'];
        } catch (PDOException $e) {
            Logger::error("Error creando usuario: " . $e->getMessage());
            
            if ($e->getCode() == '23000') {
                return ['success' => false, 'message' => 'El correo electrónico ya está registrado.'];
            }
            
            return ['success' => false, 'message' => 'Error en el registro. Intente más tarde.'];
        }
    }

    public function getUserById($id) {
        try {
            $stmt = $this->pdo->prepare("
                SELECT id_usuario, nombre_completo, email, telefono, fecha_Nacimiento, 
                       id_rol, id_estatus 
                FROM usuarios 
                WHERE id_usuario = ?
            ");
            $stmt->execute([$id]);
            return $stmt->fetch();
        } catch (PDOException $e) {
            Logger::error("Error obteniendo usuario por ID: " . $e->getMessage());
            throw $e;
        }
    }
}
