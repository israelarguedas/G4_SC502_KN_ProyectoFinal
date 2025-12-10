<?php

class Admin {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function getPendingBusinesses() {
        try {
            $stmt = $this->pdo->prepare("
                SELECT n.*, u.nombre_completo, u.email, ub.provincia, ub.canton, c.nombre_categoria
                FROM negocios n
                JOIN usuarios u ON n.id_usuario_fk = u.id_usuario
                JOIN ubicaciones ub ON n.id_ubicacion_fk = ub.id_ubicacion
                JOIN categorias c ON n.id_categoria_fk = c.id_categoria
                WHERE n.id_estatus = 3 -- CORRECCIÓN: Buscar estatus Pendiente (3)
                ORDER BY n.fecha_solicitud DESC
            ");
            $stmt->execute();
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            Logger::error("Error obteniendo negocios pendientes: " . $e->getMessage());
            return [];
        }
    }

    public function getApprovedBusinesses() {
        try {
            $stmt = $this->pdo->prepare("
                SELECT n.*, u.nombre_completo, u.email, ub.provincia, ub.canton, c.nombre_categoria
                FROM negocios n
                JOIN usuarios u ON n.id_usuario_fk = u.id_usuario
                JOIN ubicaciones ub ON n.id_ubicacion_fk = ub.id_ubicacion
                JOIN categorias c ON n.id_categoria_fk = c.id_categoria
                WHERE n.id_estatus = 1
                ORDER BY n.fecha_aprobacion DESC
            ");
            $stmt->execute();
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            Logger::error("Error obteniendo negocios aprobados: " . $e->getMessage());
            return [];
        }
    }

    public function approveBusiness($businessId) {
        try {
            $this->pdo->beginTransaction();

            // Actualizar estado del negocio a Aprobado (1)
            $stmt = $this->pdo->prepare("
                UPDATE negocios 
                SET id_estatus = 1, fecha_aprobacion = NOW() 
                WHERE id_negocio = ?
            ");
            $stmt->execute([$businessId]);

            $this->pdo->commit();
            Logger::info("Negocio aprobado: " . $businessId);
            
            return ['success' => true, 'message' => 'Negocio aprobado exitosamente'];
        } catch (Exception $e) {
            $this->pdo->rollBack();
            Logger::error("Error aprobando negocio: " . $e->getMessage());
            return ['success' => false, 'message' => 'Error al aprobar el negocio'];
        }
    }

    public function rejectBusiness($businessId, $reason = '') {
        try {
            $this->pdo->beginTransaction();

            // Actualizar estado del negocio a Rechazado (4)
            $stmt = $this->pdo->prepare("
                UPDATE negocios 
                SET id_estatus = 4, motivo_rechazo = ? -- Estatus 4 para Rechazado
                WHERE id_negocio = ?
            ");
            $stmt->execute([$reason, $businessId]);

            $this->pdo->commit();
            Logger::info("Negocio rechazado: " . $businessId);
            
            return ['success' => true, 'message' => 'Negocio rechazado'];
        } catch (Exception $e) {
            $this->pdo->rollBack();
            Logger::error("Error rechazando negocio: " . $e->getMessage());
            return ['success' => false, 'message' => 'Error al rechazar el negocio'];
        }
    }

    public function updateBusinessStatus($businessId, $status) {
        try {
            $stmt = $this->pdo->prepare("UPDATE negocios SET id_estatus = ? WHERE id_negocio = ?");
            $result = $stmt->execute([$status, $businessId]);
            
            if ($result) {
                Logger::info("Estado de negocio actualizado: " . $businessId);
                return ['success' => true, 'message' => 'Estado actualizado'];
            }
            
            return ['success' => false, 'message' => 'Error al actualizar estado'];
        } catch (PDOException $e) {
            Logger::error("Error actualizando estado: " . $e->getMessage());
            return ['success' => false, 'message' => 'Error en la base de datos'];
        }
    }

    public function getReservationStats() {
        try {
            $stmt = $this->pdo->query("
                SELECT 
                    COUNT(*) as total_reservaciones,
                    SUM(CASE WHEN id_estatus = 3 THEN 1 ELSE 0 END) as pendientes, -- Asumiendo 3 es Pendiente Pago
                    SUM(CASE WHEN id_estatus = 2 THEN 1 ELSE 0 END) as confirmadas, -- Asumiendo 2 es Confirmada
                    SUM(CASE WHEN YEAR(fecha_reserva) = YEAR(CURDATE()) THEN 1 ELSE 0 END) as anuales
                FROM reservas
            ");
            return $stmt->fetch();
        } catch (PDOException $e) {
            Logger::error("Error obteniendo estadísticas de reservaciones: " . $e->getMessage());
            return ['total_reservaciones' => 0, 'pendientes' => 0, 'confirmadas' => 0, 'anuales' => 0];
        }
    }

    public function getRecentReservations($limit = 10) {
        try {
            $stmt = $this->pdo->prepare("
                SELECT r.*, u.nombre_completo, n.nombre_publico as negocio
                FROM reservas r
                JOIN usuarios u ON r.id_usuario_fk = u.id_usuario
                JOIN servicios s ON r.id_servicio_fk = s.id_servicio
                JOIN negocios n ON s.id_negocio_fk = n.id_negocio
                ORDER BY r.fecha_creacion DESC
                LIMIT ?
            ");
            $stmt->bindParam(1, $limit, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            Logger::error("Error obteniendo reservaciones recientes: " . $e->getMessage());
            return [];
        }
    }

    public function getGeneralStats() {
        try {
            $stmt = $this->pdo->query("
                SELECT 
                    (SELECT COUNT(*) FROM usuarios WHERE id_estatus = 1) as total_usuarios,
                    (SELECT COUNT(*) FROM negocios WHERE id_estatus = 1) as negocios_activos,
                    (SELECT COUNT(*) FROM reservas) as total_reservaciones,
                    (SELECT COUNT(*) FROM cupones_b2b WHERE id_estatus = 1) as cupones_activos
            ");
            return $stmt->fetch();
        } catch (PDOException $e) {
            Logger::error("Error obteniendo estadísticas generales: " . $e->getMessage());
            return ['total_usuarios' => 0, 'negocios_activos' => 0, 'total_reservaciones' => 0, 'cupones_activos' => 0];
        }
    }

    public function getTopSearches($limit = 5) {
        try {
            // Esta es una implementación simplificada
            // En producción, deberías tener una tabla de búsquedas
            $stmt = $this->pdo->prepare("
                SELECT ub.provincia, COUNT(*) as total
                FROM negocios n
                JOIN ubicaciones ub ON n.id_ubicacion_fk = ub.id_ubicacion
                GROUP BY ub.provincia
                ORDER BY total DESC
                LIMIT ?
            ");
            $stmt->bindParam(1, $limit, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            Logger::error("Error obteniendo búsquedas top: " . $e->getMessage());
            return [];
        }
    }

    public function getPopularBusinesses($limit = 5) {
        try {
            // Se debe corregir id_reservacion a id_reserva
            $stmt = $this->pdo->prepare("
                SELECT n.nombre_publico, COUNT(r.id_reserva) as total_reservas
                FROM negocios n
                JOIN servicios s ON n.id_negocio = s.id_negocio_fk
                LEFT JOIN reservas r ON s.id_servicio = r.id_servicio_fk
                GROUP BY n.id_negocio
                ORDER BY total_reservas DESC
                LIMIT ?
            ");
            $stmt->bindParam(1, $limit, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            Logger::error("Error obteniendo negocios populares: " . $e->getMessage());
            return [];
        }
    }
}