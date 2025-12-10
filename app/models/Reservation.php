<?php

class Reservation {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function create($data) {
        try {
            $sql = "INSERT INTO reservaciones (
                id_usuario_fk, id_servicio_fk, fecha_reservacion, 
                hora_reservacion, cantidad_personas, nombre_contacto,
                email_contacto, telefono_contacto, tipo_identificacion,
                numero_identificacion, pais_residencia, id_estatus
            ) VALUES (
                :user_id, :service_id, :fecha, :hora, :personas,
                :nombre, :email, :telefono, :tipo_id, :numero_id,
                :pais, 1
            )";

            $stmt = $this->pdo->prepare($sql);
            $result = $stmt->execute([
                'user_id' => $data['user_id'],
                'service_id' => $data['service_id'],
                'fecha' => $data['fecha'],
                'hora' => $data['hora'],
                'personas' => $data['personas'],
                'nombre' => $data['nombre'],
                'email' => $data['email'],
                'telefono' => $data['telefono'],
                'tipo_id' => $data['tipo_identificacion'],
                'numero_id' => $data['numero_identificacion'],
                'pais' => $data['pais']
            ]);

            if ($result) {
                Logger::info("Reservación creada para usuario: " . $data['user_id']);
                return ['success' => true, 'message' => 'Reservación creada exitosamente'];
            }

            return ['success' => false, 'message' => 'Error al crear reservación'];
        } catch (PDOException $e) {
            Logger::error("Error creando reservación: " . $e->getMessage());
            return ['success' => false, 'message' => 'Error en la base de datos'];
        }
    }

    public function getByUserId($userId) {
        try {
            $stmt = $this->pdo->prepare("
                SELECT r.*, s.nombre_servicio, n.nombre_publico as nombre_negocio,
                       e.nombre_estatus
                FROM reservaciones r
                JOIN servicios s ON r.id_servicio_fk = s.id_servicio
                JOIN negocios n ON s.id_negocio_fk = n.id_negocio
                JOIN estatus e ON r.id_estatus = e.id_estatus
                WHERE r.id_usuario_fk = ?
                ORDER BY r.fecha_reservacion DESC
            ");
            $stmt->execute([$userId]);
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            Logger::error("Error obteniendo reservaciones: " . $e->getMessage());
            return [];
        }
    }

    public function getById($id) {
        try {
            $stmt = $this->pdo->prepare("
                SELECT r.*, s.nombre_servicio, n.nombre_publico as nombre_negocio
                FROM reservaciones r
                JOIN servicios s ON r.id_servicio_fk = s.id_servicio
                JOIN negocios n ON s.id_negocio_fk = n.id_negocio
                WHERE r.id_reservacion = ?
            ");
            $stmt->execute([$id]);
            return $stmt->fetch();
        } catch (PDOException $e) {
            Logger::error("Error obteniendo reservación: " . $e->getMessage());
            return null;
        }
    }

    public function updateStatus($id, $status) {
        try {
            $stmt = $this->pdo->prepare("UPDATE reservaciones SET id_estatus = ? WHERE id_reservacion = ?");
            $result = $stmt->execute([$status, $id]);
            
            if ($result) {
                Logger::info("Estado de reservación actualizado: " . $id);
                return ['success' => true, 'message' => 'Estado actualizado'];
            }

            return ['success' => false, 'message' => 'Error al actualizar estado'];
        } catch (PDOException $e) {
            Logger::error("Error actualizando estado: " . $e->getMessage());
            return ['success' => false, 'message' => 'Error en la base de datos'];
        }
    }
}
