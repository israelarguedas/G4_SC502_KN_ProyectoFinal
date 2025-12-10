<?php

class Location {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    /**
     * Get all provinces from ubicaciones table
     */
    public function getProvincias() {
        try {
            $stmt = $this->pdo->query("
                SELECT DISTINCT provincia 
                FROM ubicaciones 
                ORDER BY provincia
            ");
            return $stmt->fetchAll(PDO::FETCH_COLUMN);
        } catch (PDOException $e) {
            Logger::error("Error obteniendo provincias: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Get cantones by provincia
     */
    public function getCantonesByProvincia($provincia) {
        try {
            $stmt = $this->pdo->prepare("
                SELECT DISTINCT canton 
                FROM ubicaciones 
                WHERE provincia = :provincia
                ORDER BY canton
            ");
            $stmt->execute(['provincia' => $provincia]);
            return $stmt->fetchAll(PDO::FETCH_COLUMN);
        } catch (PDOException $e) {
            Logger::error("Error obteniendo cantones: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Get distritos by provincia and canton
     */
    public function getDistritosByCanton($provincia, $canton) {
        try {
            $stmt = $this->pdo->prepare("
                SELECT DISTINCT distrito 
                FROM ubicaciones 
                WHERE provincia = :provincia AND canton = :canton
                ORDER BY distrito
            ");
            $stmt->execute([
                'provincia' => $provincia,
                'canton' => $canton
            ]);
            return $stmt->fetchAll(PDO::FETCH_COLUMN);
        } catch (PDOException $e) {
            Logger::error("Error obteniendo distritos: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Get location ID by provincia, canton, distrito
     */
    public function getLocationId($provincia, $canton, $distrito) {
        try {
            $stmt = $this->pdo->prepare("
                SELECT id_ubicacion 
                FROM ubicaciones 
                WHERE provincia = :provincia 
                  AND canton = :canton 
                  AND distrito = :distrito
                LIMIT 1
            ");
            $stmt->execute([
                'provincia' => $provincia,
                'canton' => $canton,
                'distrito' => $distrito
            ]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result ? $result['id_ubicacion'] : null;
        } catch (PDOException $e) {
            Logger::error("Error obteniendo ID de ubicación: " . $e->getMessage());
            return null;
        }
    }

    /**
     * Get all geographic data from JSON file (fallback for client-side)
     */
    public function getGeoDataFromJson() {
        $jsonPath = __DIR__ . '/../../app/public/js/apiMockup/infoGeografica.json';
        if (file_exists($jsonPath)) {
            $jsonContent = file_get_contents($jsonPath);
            return json_decode($jsonContent, true);
        }
        return [];
    }
}
