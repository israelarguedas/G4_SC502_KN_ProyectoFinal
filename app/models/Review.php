<?php

class Review {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function getByServiceId($serviceId, $limit = 10) {
        try {
            $stmt = $this->pdo->prepare("
                SELECT r.*, u.nombre_completo, u.ruta_foto_perfil
                FROM resenas r
                JOIN usuarios u ON r.id_usuario_fk = u.id_usuario
                WHERE r.id_servicio_fk = ?
                ORDER BY r.fecha_resena DESC
                LIMIT ?
            ");
            $stmt->bindParam(1, $serviceId, PDO::PARAM_INT);
            $stmt->bindParam(2, $limit, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            Logger::error("Error obteniendo reseñas: " . $e->getMessage());
            return [];
        }
    }

    public function create($data) {
        try {
            // Verificar si ya existe una reseña
            $checkStmt = $this->pdo->prepare("
                SELECT id_resena FROM resenas 
                WHERE id_usuario_fk = ? AND id_servicio_fk = ?
            ");
            $checkStmt->execute([$data['id_usuario_fk'], $data['id_servicio_fk']]);

            if ($checkStmt->rowCount() > 0) {
                return ['success' => false, 'message' => 'Ya has reseñado este servicio'];
            }

            // Validar calificación
            if ($data['calificacion'] < 1 || $data['calificacion'] > 5) {
                return ['success' => false, 'message' => 'La calificación debe estar entre 1 y 5'];
            }

            $stmt = $this->pdo->prepare("
                INSERT INTO resenas (id_usuario_fk, id_servicio_fk, calificacion, comentario)
                VALUES (?, ?, ?, ?)
            ");

            $result = $stmt->execute([
                $data['id_usuario_fk'],
                $data['id_servicio_fk'],
                (int)$data['calificacion'],
                $data['comentario'] ?? null
            ]);

            if ($result) {
                Logger::info("Reseña creada para servicio: " . $data['id_servicio_fk']);
                return ['success' => true, 'message' => 'Reseña creada exitosamente'];
            }

            return ['success' => false, 'message' => 'Error al crear reseña'];
        } catch (PDOException $e) {
            Logger::error("Error creando reseña: " . $e->getMessage());
            return ['success' => false, 'message' => 'Error en la base de datos'];
        }
    }

    public function getAverageRating($serviceId) {
        try {
            $stmt = $this->pdo->prepare("
                SELECT 
                    AVG(calificacion) as promedio,
                    COUNT(*) as total_resenas
                FROM resenas
                WHERE id_servicio_fk = ?
            ");
            $stmt->execute([$serviceId]);
            return $stmt->fetch();
        } catch (PDOException $e) {
            Logger::error("Error obteniendo calificación promedio: " . $e->getMessage());
            return ['promedio' => 0, 'total_resenas' => 0];
        }
    }
}
