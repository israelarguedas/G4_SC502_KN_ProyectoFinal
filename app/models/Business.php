<?php

class Business {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function getProvincias() {
        try {
            $stmt = $this->pdo->query("SELECT DISTINCT provincia FROM ubicaciones ORDER BY provincia");
            return $stmt->fetchAll(PDO::FETCH_COLUMN);
        } catch (PDOException $e) {
            Logger::error("Error obteniendo provincias: " . $e->getMessage());
            return [];
        }
    }

    public function createApplication($data) {
        try {
            $this->pdo->beginTransaction();

            // Obtener id_categoria
            $stmt_cat = $this->pdo->prepare("SELECT id_categoria FROM categorias WHERE nombre_categoria = ?");
            $stmt_cat->execute([$data['tipo_negocio']]);
            $id_categoria_fk = $stmt_cat->fetchColumn();

            if (!$id_categoria_fk) {
                throw new Exception("Categoría de negocio no válida.");
            }

            // Obtener id_ubicacion
            $stmt_ubi = $this->pdo->prepare("
                SELECT id_ubicacion FROM ubicaciones 
                WHERE provincia = ? AND canton = ? AND distrito = ?
                LIMIT 1
            ");
            $stmt_ubi->execute([$data['provincia'], $data['canton'], $data['distrito']]);
            $id_ubicacion_fk = $stmt_ubi->fetchColumn();

            if (!$id_ubicacion_fk) {
                throw new Exception("Ubicación no válida.");
            }

            // Determinar id_rol basado en tipo de negocio
            $id_rol_asignar = 6; // Por defecto
            if ($data['tipo_negocio'] == 'Hospedaje') {
                $id_rol_asignar = 4;
            } elseif ($data['tipo_negocio'] == 'Tour / Experiencia') {
                $id_rol_asignar = 5;
            }

            // Insertar negocio
            $sql = "INSERT INTO negocios (
                id_usuario_fk, id_categoria_fk, id_ubicacion_fk, 
                nombre_legal, nombre_publico, descripcion_corta,
                telefono_contacto, correo_contacto, cedula_hacienda,
                nombre_representante, no_licencia_municipal,
                direccion_exacta, link_google_maps, link_waze,
                tipo_cedula, id_estatus
            ) VALUES (
                :id_usuario, :id_categoria, :id_ubicacion,
                :nombre_legal, :nombre_publico, :descripcion,
                :telefono, :correo, :cedula,
                :representante, :licencia,
                :direccion, :google_maps, :waze,
                :tipo_cedula, 2
            )";

            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([
                'id_usuario' => $data['id_usuario_fk'],
                'id_categoria' => $id_categoria_fk,
                'id_ubicacion' => $id_ubicacion_fk,
                'nombre_legal' => $data['nombre_legal'],
                'nombre_publico' => $data['nombre_publico'],
                'descripcion' => $data['descripcion_corta'],
                'telefono' => $data['telefono_contacto'],
                'correo' => $data['correo_contacto'],
                'cedula' => $data['cedula_hacienda'],
                'representante' => $data['nombre_representante'],
                'licencia' => $data['no_licencia_municipal'],
                'direccion' => $data['direccion_exacta'],
                'google_maps' => $data['link_google_maps'],
                'waze' => $data['link_waze'],
                'tipo_cedula' => $data['tipo_cedula']
            ]);

            // Actualizar rol del usuario
            $stmt_rol = $this->pdo->prepare("UPDATE usuarios SET id_rol = ? WHERE id_usuario = ?");
            $stmt_rol->execute([$id_rol_asignar, $data['id_usuario_fk']]);

            $this->pdo->commit();
            Logger::info("Aplicación de negocio creada para usuario: " . $data['id_usuario_fk']);
            
            return ['success' => true, 'message' => 'Aplicación enviada exitosamente. En revisión.'];
        } catch (Exception $e) {
            $this->pdo->rollBack();
            Logger::error("Error creando aplicación de negocio: " . $e->getMessage());
            return ['success' => false, 'message' => 'Error al crear la aplicación'];
        }
    }

    public function getActiveCoupons() {
        try {
            $stmt = $this->pdo->prepare("
                SELECT cb.*, n.nombre_publico as nombre_negocio, n.id_ubicacion_fk, 
                       u.provincia, u.canton
                FROM cupones_b2b cb
                JOIN negocios n ON cb.id_negocio_fk = n.id_negocio
                JOIN ubicaciones u ON n.id_ubicacion_fk = u.id_ubicacion
                WHERE cb.id_estatus = 1
                AND cb.fecha_inicio <= CURDATE()
                AND cb.fecha_fin >= CURDATE()
                ORDER BY cb.fecha_fin ASC
            ");
            $stmt->execute();
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            Logger::error("Error obteniendo cupones activos: " . $e->getMessage());
            return [];
        }
    }

    public function getBusinessIdByUserId($userId) {
        try {
            $stmt = $this->pdo->prepare("
                SELECT id_negocio FROM negocios 
                WHERE id_usuario_fk = ? AND id_estatus != 4
                LIMIT 1
            ");
            $stmt->execute([$userId]);
            return $stmt->fetchColumn();
        } catch (PDOException $e) {
            Logger::error("Error obteniendo ID de negocio: " . $e->getMessage());
            return null;
        }
    }

    public function getCouponsByBusinessId($businessId) {
        try {
            $stmt = $this->pdo->prepare("
                SELECT * FROM cupones_b2b 
                WHERE id_negocio_fk = ?
                ORDER BY fecha_creacion DESC
            ");
            $stmt->execute([$businessId]);
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            Logger::error("Error obteniendo cupones por negocio: " . $e->getMessage());
            return [];
        }
    }

    public function createCoupon($data) {
        try {
            // Verificar si el código ya existe
            $checkStmt = $this->pdo->prepare("SELECT id_cupon FROM cupones_b2b WHERE codigo_cupon = ?");
            $checkStmt->execute([$data['codigo_cupon']]);
            
            if ($checkStmt->rowCount() > 0) {
                return ['success' => false, 'message' => 'El código de cupón ya existe'];
            }

            $stmt = $this->pdo->prepare("
                INSERT INTO cupones_b2b (
                    id_negocio_fk, codigo_cupon, tipo_descuento, 
                    valor_descuento, fecha_inicio, fecha_fin, 
                    usos_restantes, id_estatus
                ) VALUES (
                    :id_negocio, :codigo, :tipo_descuento, 
                    :valor_descuento, :fecha_inicio, :fecha_fin, 
                    :usos_restantes, 1
                )
            ");

            $result = $stmt->execute([
                'id_negocio' => $data['id_negocio_fk'],
                'codigo' => $data['codigo_cupon'],
                'tipo_descuento' => $data['tipo_descuento'],
                'valor_descuento' => $data['valor_descuento'],
                'fecha_inicio' => $data['fecha_inicio'],
                'fecha_fin' => $data['fecha_fin'],
                'usos_restantes' => $data['usos_restantes'] ?? null
            ]);

            if ($result) {
                Logger::info("Cupón creado: " . $data['codigo_cupon']);
                return ['success' => true, 'message' => 'Cupón creado exitosamente'];
            }

            return ['success' => false, 'message' => 'Error al crear cupón'];
        } catch (PDOException $e) {
            Logger::error("Error creando cupón: " . $e->getMessage());
            return ['success' => false, 'message' => 'Error en la base de datos'];
        }
    }

    public function validateCoupon($codigo, $idServicio) {
        try {
            $stmt = $this->pdo->prepare("
                SELECT cb.*, s.precio_base
                FROM cupones_b2b cb
                JOIN servicios s ON cb.id_negocio_fk = s.id_negocio_fk
                WHERE cb.codigo_cupon = ?
                AND s.id_servicio = ?
                AND cb.id_estatus = 1
                AND cb.fecha_inicio <= CURDATE()
                AND cb.fecha_fin >= CURDATE()
                AND (cb.usos_restantes IS NULL OR cb.usos_restantes > 0)
            ");
            $stmt->execute([$codigo, $idServicio]);
            return $stmt->fetch();
        } catch (PDOException $e) {
            Logger::error("Error validando cupón: " . $e->getMessage());
            return null;
        }
    }

    public function searchBusinesses($filters) {
        try {
            $sql = "SELECT DISTINCT n.*, u.provincia, u.canton, u.distrito, 
                           c.nombre_categoria
                    FROM negocios n
                    JOIN ubicaciones u ON n.id_ubicacion_fk = u.id_ubicacion
                    LEFT JOIN categorias c ON n.id_categoria_fk = c.id_categoria
                    WHERE n.id_estatus = 1"; // Solo negocios activos
            
            $params = [];
            
            // Filtrar por provincia
            if (!empty($filters['provincia'])) {
                $sql .= " AND u.provincia = ?";
                $params[] = $filters['provincia'];
            }
            
            // Filtrar por cantón
            if (!empty($filters['canton'])) {
                $sql .= " AND u.canton = ?";
                $params[] = $filters['canton'];
            }
            
            // Filtrar por distrito
            if (!empty($filters['distrito'])) {
                $sql .= " AND u.distrito = ?";
                $params[] = $filters['distrito'];
            }
            
            // Filtrar por categoría
            if (!empty($filters['categoria'])) {
                $sql .= " AND c.nombre_categoria = ?";
                $params[] = $filters['categoria'];
            }
            
            $sql .= " ORDER BY n.nombre_publico";
            
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute($params);
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            Logger::error("Error buscando negocios: " . $e->getMessage());
            return [];
        }
    }

    public function getFeaturedBusinesses($limit = 10) {
        try {
            $sql = "SELECT n.*, u.provincia, u.canton, u.distrito, c.nombre_categoria
                    FROM negocios n
                    JOIN ubicaciones u ON n.id_ubicacion_fk = u.id_ubicacion
                    LEFT JOIN categorias c ON n.id_categoria_fk = c.id_categoria
                    WHERE n.id_estatus = 1
                    ORDER BY n.id_negocio DESC
                    LIMIT ?";
            
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$limit]);
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            Logger::error("Error obteniendo negocios destacados: " . $e->getMessage());
            return [];
        }
    }
}
