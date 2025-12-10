<?php

class Business {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }
    
    // =================================================================
    // MÉTODOS DE UBICACIÓN (CORRECCIÓN DEL ERROR FATAL)
    // =================================================================

    public function getProvincias() {
        try {
            $stmt = $this->pdo->query("SELECT DISTINCT provincia FROM ubicaciones ORDER BY provincia");
            return $stmt->fetchAll(PDO::FETCH_COLUMN);
        } catch (PDOException $e) {
            error_log("Error obteniendo provincias: " . $e->getMessage());
            return [];
        }
    }


    // =================================================================
    // MÉTODOS DE APLICACIÓN DE NEGOCIO (CORRECCIÓN DE ROL/CATEGORÍA/ARCHIVO)
    // =================================================================

    /**
     * Procesa la solicitud de certificación de negocio.
     * @param array $data Los datos del formulario.
     * @return array Resultado de la operación.
     */
    public function createApplication($data) {
        try {
            $this->pdo->beginTransaction();

            // 1. Obtener id_categoria
            $stmt_cat = $this->pdo->prepare("SELECT id_categoria FROM categorias WHERE nombre_categoria = ?");
            $stmt_cat->execute([$data['tipo_negocio']]);
            $id_categoria_fk = $stmt_cat->fetchColumn();

            if (!$id_categoria_fk) {
                // Genera el primer error reportado
                throw new Exception("Error: Categoría de negocio '{$data['tipo_negocio']}' no válida o no encontrada.");
            }

            // 2. Obtener id_ubicacion
            $stmt_ubi = $this->pdo->prepare("
                SELECT id_ubicacion FROM ubicaciones 
                WHERE provincia = ? AND canton = ? AND distrito = ?
                LIMIT 1
            ");
            $stmt_ubi->execute([$data['provincia'], $data['canton'], $data['distrito']]);
            $id_ubicacion_fk = $stmt_ubi->fetchColumn();

            if (!$id_ubicacion_fk) {
                throw new Exception("Error: La ubicación seleccionada ({$data['provincia']}/{$data['canton']}/{$data['distrito']}) no es válida o no existe.");
            }

            // 3. Determinar id_rol basado en tipo de negocio (LÓGICA REQUERIDA)
            // 'Hospedaje'=4, 'Tour / Experiencia'=5, Resto (Gastronomía, Artesanías, Transporte, Otros)=6.
            $id_rol_asignar = 6; 
            if ($data['tipo_negocio'] == 'Hospedaje') {
                $id_rol_asignar = 4;
            } elseif ($data['tipo_negocio'] == 'Tour / Experiencia') {
                $id_rol_asignar = 5;
            } 

            // 4. Placeholder para rutas de archivos (Se establece a NULL para pruebas si la columna lo permite)
            $ruta_cedula_frente_placeholder = NULL; 
            $ruta_cedula_reverso_placeholder = NULL; 

            // 5. Insertar negocio (Usar id_estatus 3 = Pendiente)
            $sql = "INSERT INTO negocios (
                id_usuario_fk, id_categoria_fk, id_ubicacion_fk, 
                nombre_legal, nombre_publico, descripcion_corta,
                telefono_contacto, correo_contacto, tipo_cedula, cedula_hacienda,
                nombre_representante, no_licencia_municipal,
                ruta_cedula_frente, ruta_cedula_reverso,
                direccion_exacta, link_google_maps, link_waze,
                id_estatus
            ) VALUES (
                :id_usuario, :id_categoria, :id_ubicacion,
                :nombre_legal, :nombre_publico, :descripcion,
                :telefono, :correo, :tipo_cedula, :cedula,
                :representante, :licencia,
                :ruta_cedula_frente, :ruta_cedula_reverso,
                :direccion, :google_maps, :waze,
                3
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
                'tipo_cedula' => $data['tipo_cedula'],
                'cedula' => $data['cedula_hacienda'],
                'representante' => $data['nombre_representante'],
                'licencia' => $data['no_licencia_municipal'],
                'ruta_cedula_frente' => $ruta_cedula_frente_placeholder,
                'ruta_cedula_reverso' => $ruta_cedula_reverso_placeholder,
                'direccion' => $data['direccion_exacta'],
                'google_maps' => $data['link_google_maps'],
                'waze' => $data['link_waze']
            ]);

            // 6. Actualizar rol y estatus del usuario (id_estatus = 3 para Pendiente)
            $stmt_rol = $this->pdo->prepare("UPDATE usuarios SET id_rol = ?, id_estatus = 3 WHERE id_usuario = ?");
            $stmt_rol->execute([$id_rol_asignar, $data['id_usuario_fk']]);

            $this->pdo->commit();
            error_log("Aplicación de negocio creada para usuario: " . $data['id_usuario_fk']);
            
            return ['success' => true, 'message' => 'Su solicitud ha sido enviada con éxito. Su cuenta está ahora en estado PENDIENTE y será revisada por un administrador.'];
        } catch (PDOException $e) {
            $this->pdo->rollBack();
            $mensaje = 'Error al crear la aplicación. Error de base de datos.';
            if ($e->getCode() == '23000') { 
                $mensaje = 'Error: La cédula de hacienda ya está registrada en otra solicitud.';
            }
            error_log("Error creando aplicación de negocio (PDO): " . $e->getMessage());
            return ['success' => false, 'message' => $mensaje];
        } catch (Exception $e) {
            $this->pdo->rollBack();
            error_log("Error creando aplicación de negocio (Logic): " . $e->getMessage());
            return ['success' => false, 'message' => $e->getMessage()];
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

    public function getById($id) {
        try {
            $sql = "SELECT n.*, u.provincia, u.canton, u.distrito, c.nombre_categoria,
                           e.nombre as nombre_estatus
                    FROM negocios n
                    JOIN ubicaciones u ON n.id_ubicacion_fk = u.id_ubicacion
                    LEFT JOIN categorias c ON n.id_categoria_fk = c.id_categoria
                    LEFT JOIN estatus e ON n.id_estatus = e.id_estatus
                    WHERE n.id_negocio = ?";
            
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$id]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            Logger::error("Error obteniendo negocio: " . $e->getMessage());
            return null;
        }
    }

    public function getServicesByBusinessId($businessId) {
        try {
            $sql = "SELECT s.*, c.nombre_categoria
                    FROM servicios s
                    LEFT JOIN categorias c ON s.id_categoria_fk = c.id_categoria
                    WHERE s.id_negocio_fk = ? AND s.id_estatus = 1
                    ORDER BY s.id_servicio DESC";
            
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$businessId]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            Logger::error("Error obteniendo servicios del negocio: " . $e->getMessage());
            return [];
        }
    }

    public function getAllServices($businessId) {
        try {
            $sql = "SELECT s.*, c.nombre_categoria
                    FROM servicios s
                    LEFT JOIN categorias c ON s.id_categoria_fk = c.id_categoria
                    WHERE s.id_negocio_fk = ?
                    ORDER BY s.id_estatus DESC, s.id_servicio DESC";
            
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$businessId]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            Logger::error("Error obteniendo servicios: " . $e->getMessage());
            return [];
        }
    }

    public function createService($data) {
        try {
            $stmt = $this->pdo->prepare("
                INSERT INTO servicios (
                    id_negocio_fk, id_categoria_fk, nombre_servicio,
                    descripcion, precio_base, id_estatus
                ) VALUES (
                    :id_negocio, :id_categoria, :nombre,
                    :descripcion, :precio, 1
                )
            ");

            $result = $stmt->execute([
                'id_negocio' => $data['id_negocio_fk'],
                'id_categoria' => $data['id_categoria_fk'] ?? null,
                'nombre' => $data['nombre_servicio'],
                'descripcion' => $data['descripcion'],
                'precio' => $data['precio_base']
            ]);

            if ($result) {
                Logger::info("Servicio creado para negocio: " . $data['id_negocio_fk']);
                return ['success' => true, 'message' => 'Servicio creado exitosamente'];
            }

            return ['success' => false, 'message' => 'Error al crear servicio'];
        } catch (PDOException $e) {
            Logger::error("Error creando servicio: " . $e->getMessage());
            return ['success' => false, 'message' => 'Error en la base de datos'];
        }
    }

    public function updateService($serviceId, $data) {
        try {
            $stmt = $this->pdo->prepare("
                UPDATE servicios SET
                    nombre_servicio = :nombre,
                    descripcion = :descripcion,
                    precio_base = :precio,
                    id_categoria_fk = :id_categoria
                WHERE id_servicio = :id_servicio
            ");

            $result = $stmt->execute([
                'nombre' => $data['nombre_servicio'],
                'descripcion' => $data['descripcion'],
                'precio' => $data['precio_base'],
                'id_categoria' => $data['id_categoria_fk'] ?? null,
                'id_servicio' => $serviceId
            ]);

            if ($result) {
                Logger::info("Servicio actualizado: " . $serviceId);
                return ['success' => true, 'message' => 'Servicio actualizado exitosamente'];
            }

            return ['success' => false, 'message' => 'Error al actualizar servicio'];
        } catch (PDOException $e) {
            Logger::error("Error actualizando servicio: " . $e->getMessage());
            return ['success' => false, 'message' => 'Error en la base de datos'];
        }
    }

    public function deleteService($serviceId) {
        try {
            $stmt = $this->pdo->prepare("UPDATE servicios SET id_estatus = 4 WHERE id_servicio = ?");
            $stmt->execute([$serviceId]);

            Logger::info("Servicio eliminado: " . $serviceId);
            return ['success' => true, 'message' => 'Servicio eliminado exitosamente'];
        } catch (PDOException $e) {
            Logger::error("Error eliminando servicio: " . $e->getMessage());
            return ['success' => false, 'message' => 'Error en la base de datos'];
        }
    }

    public function getBusinessStats($businessId) {
        try {
            $stats = [];

            // Total de servicios
            $stmt = $this->pdo->prepare("SELECT COUNT(*) as total FROM servicios WHERE id_negocio_fk = ? AND id_estatus = 1");
            $stmt->execute([$businessId]);
            $stats['total_servicios'] = $stmt->fetchColumn();

            // Total de cupones activos
            $stmt = $this->pdo->prepare("SELECT COUNT(*) as total FROM cupones_b2b WHERE id_negocio_fk = ? AND id_estatus = 1 AND fecha_fin >= CURDATE()");
            $stmt->execute([$businessId]);
            $stats['cupones_activos'] = $stmt->fetchColumn();

            // Total de reservas
            $stmt = $this->pdo->prepare("
                SELECT COUNT(*) as total FROM reservas r
                JOIN servicios s ON r.id_servicio_fk = s.id_servicio
                WHERE s.id_negocio_fk = ?
            ");
            $stmt->execute([$businessId]);
            $stats['total_reservas'] = $stmt->fetchColumn();

            // Ingresos totales
            $stmt = $this->pdo->prepare("
                SELECT COALESCE(SUM(r.monto_total), 0) as ingresos FROM reservas r
                JOIN servicios s ON r.id_servicio_fk = s.id_servicio
                WHERE s.id_negocio_fk = ?
            ");
            $stmt->execute([$businessId]);
            $stats['ingresos_totales'] = $stmt->fetchColumn();

            // Reservas próximas (próximos 7 días)
            $stmt = $this->pdo->prepare("
                SELECT COUNT(*) as total FROM reservas r
                JOIN servicios s ON r.id_servicio_fk = s.id_servicio
                WHERE s.id_negocio_fk = ?
                AND r.fecha_reserva BETWEEN CURDATE() AND DATE_ADD(CURDATE(), INTERVAL 7 DAY)
            ");
            $stmt->execute([$businessId]);
            $stats['proximas_reservas'] = $stmt->fetchColumn();

            return $stats;
        } catch (PDOException $e) {
            Logger::error("Error obteniendo estadísticas: " . $e->getMessage());
            return [];
        }
    }

    public function getReservationsByBusinessId($businessId, $limit = 10) {
        try {
            $stmt = $this->pdo->prepare("
                SELECT r.*, s.nombre_servicio, u.nombre, u.correo
                FROM reservas r
                JOIN servicios s ON r.id_servicio_fk = s.id_servicio
                JOIN usuarios u ON r.id_usuario_fk = u.id_usuario
                WHERE s.id_negocio_fk = ?
                ORDER BY r.fecha_reserva DESC
                LIMIT ?
            ");
            $stmt->execute([$businessId, $limit]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            Logger::error("Error obteniendo reservas: " . $e->getMessage());
            return [];
        }
    }

    public function updateCoupon($couponId, $data) {
        try {
            $stmt = $this->pdo->prepare("
                UPDATE cupones_b2b SET
                    codigo_cupon = :codigo,
                    tipo_descuento = :tipo_descuento,
                    valor_descuento = :valor_descuento,
                    fecha_inicio = :fecha_inicio,
                    fecha_fin = :fecha_fin,
                    usos_restantes = :usos_restantes,
                    id_estatus = :id_estatus
                WHERE id_cupon = :id_cupon
            ");

            $result = $stmt->execute([
                'codigo' => $data['codigo_cupon'],
                'tipo_descuento' => $data['tipo_descuento'],
                'valor_descuento' => $data['valor_descuento'],
                'fecha_inicio' => $data['fecha_inicio'],
                'fecha_fin' => $data['fecha_fin'],
                'usos_restantes' => $data['usos_restantes'] ?? null,
                'id_estatus' => $data['id_estatus'],
                'id_cupon' => $couponId
            ]);

            if ($result) {
                Logger::info("Cupón actualizado: " . $couponId);
                return ['success' => true, 'message' => 'Cupón actualizado exitosamente'];
            }

            return ['success' => false, 'message' => 'Error al actualizar cupón'];
        } catch (PDOException $e) {
            Logger::error("Error actualizando cupón: " . $e->getMessage());
            return ['success' => false, 'message' => 'Error en la base de datos'];
        }
    }

    public function deleteCoupon($couponId) {
        try {
            $stmt = $this->pdo->prepare("UPDATE cupones_b2b SET id_estatus = 4 WHERE id_cupon = ?");
            $stmt->execute([$couponId]);

            Logger::info("Cupón eliminado: " . $couponId);
            return ['success' => true, 'message' => 'Cupón eliminado exitosamente'];
        } catch (PDOException $e) {
            Logger::error("Error eliminando cupón: " . $e->getMessage());
            return ['success' => false, 'message' => 'Error en la base de datos'];
        }
    }

    public function getCategories() {
        try {
            $stmt = $this->pdo->query("SELECT id_categoria, nombre_categoria FROM categorias ORDER BY nombre_categoria");
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            // Logger::error("Error obteniendo categorías: " . $e->getMessage());
            error_log("Error obteniendo categorías: " . $e->getMessage());
            return [];
        }
    }
}
