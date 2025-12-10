<?php

class Reservation {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function create($data) {
        try {
            // Calculate total based on service price and number of people
            $serviceStmt = $this->pdo->prepare("SELECT precio_base FROM servicios WHERE id_servicio = ?");
            $serviceStmt->execute([$data['service_id']]);
            $service = $serviceStmt->fetch();
            
            $total = $service['precio_base'] * $data['personas'];
            
            $sql = "INSERT INTO reservas (
                id_usuario_fk, id_servicio_fk, fecha_reserva, 
                cantidad_personas, total_pagar, id_estatus
            ) VALUES (
                :user_id, :service_id, :fecha, 
                :personas, :total, 1
            )";

            $stmt = $this->pdo->prepare($sql);
            $result = $stmt->execute([
                'user_id' => $data['user_id'],
                'service_id' => $data['service_id'],
                'fecha' => $data['fecha'],
                'personas' => $data['personas'],
                'total' => $total
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
                SELECT r.*, s.titulo as nombre_servicio, n.nombre_publico as nombre_negocio,
                       e.nombre as nombre_estatus, c.nombre_categoria,
                       u.provincia, u.canton, u.distrito
                FROM reservas r
                JOIN servicios s ON r.id_servicio_fk = s.id_servicio
                JOIN negocios n ON s.id_negocio_fk = n.id_negocio
                JOIN estatus e ON r.id_estatus = e.id_estatus
                JOIN categorias c ON s.id_categoria_fk = c.id_categoria
                JOIN ubicaciones u ON n.id_ubicacion_fk = u.id_ubicacion
                WHERE r.id_usuario_fk = ?
                ORDER BY r.fecha_reserva DESC, r.fecha_creacion DESC
            ");
            $stmt->execute([$userId]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
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
