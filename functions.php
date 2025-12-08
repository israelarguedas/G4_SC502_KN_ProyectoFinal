<?php

function obtenerCuponesPorNegocio($pdo, $id_negocio) {
    try {
        $stmt = $pdo->prepare("
            SELECT * FROM cupones_b2b 
            WHERE id_negocio_fk = :id_negocio 
            ORDER BY fecha_creacion DESC
        ");
        $stmt->execute([':id_negocio' => $id_negocio]);
        return $stmt->fetchAll();
    } catch (PDOException $e) {
        error_log("Error obteniendo cupones: " . $e->getMessage());
        return [];
    }
}


function crearCuponB2B($pdo, $data) {
    try {
      
        $checkStmt = $pdo->prepare("SELECT id_cupon FROM cupones_b2b WHERE codigo_cupon = :codigo");
        $checkStmt->execute([':codigo' => $data['codigo_cupon']]);
        
        if ($checkStmt->rowCount() > 0) {
            return ['success' => false, 'message' => 'El código de cupón ya existe'];
        }

        $stmt = $pdo->prepare("
            INSERT INTO cupones_b2b (
                id_negocio_fk, codigo_cupon, tipo_descuento, 
                valor_descuento, fecha_inicio, fecha_fin, 
                usos_restantes, id_estatus
            ) VALUES (
                :id_negocio, :codigo, :tipo_descuento, 
                :valor_descuento, :fecha_inicio, :fecha_fin, 
                :usos_restantes, :id_estatus
            )
        ");

        $result = $stmt->execute([
            ':id_negocio' => $data['id_negocio_fk'],
            ':codigo' => $data['codigo_cupon'],
            ':tipo_descuento' => $data['tipo_descuento'],
            ':valor_descuento' => $data['valor_descuento'],
            ':fecha_inicio' => $data['fecha_inicio'],
            ':fecha_fin' => $data['fecha_fin'],
            ':usos_restantes' => $data['usos_restantes'] ?? NULL,
            ':id_estatus' => 1 
        ]);

        return [
            'success' => $result,
            'message' => $result ? 'Cupón creado exitosamente' : 'Error al crear cupón'
        ];
    } catch (PDOException $e) {
        error_log("Error creando cupón: " . $e->getMessage());
        return ['success' => false, 'message' => 'Error en la base de datos'];
    }
}


function validarCupon($pdo, $codigo_cupon, $id_servicio) {
    try {
        $stmt = $pdo->prepare("
            SELECT cb.*, s.precio_base
            FROM cupones_b2b cb
            JOIN servicios s ON cb.id_negocio_fk = s.id_negocio_fk
            WHERE cb.codigo_cupon = :codigo 
            AND s.id_servicio = :id_servicio
            AND cb.id_estatus = 1
            AND cb.fecha_inicio <= CURDATE()
            AND cb.fecha_fin >= CURDATE()
            AND (cb.usos_restantes IS NULL OR cb.usos_restantes > 0)
        ");

        $stmt->execute([
            ':codigo' => $codigo_cupon,
            ':id_servicio' => $id_servicio
        ]);

        return $stmt->fetch();
    } catch (PDOException $e) {
        error_log("Error validando cupón: " . $e->getMessage());
        return null;
    }
}


function calcularDescuento($precio_base, $tipo_descuento, $valor_descuento) {
    if ($tipo_descuento === 'Porcentaje') {
        return round($precio_base * ($valor_descuento / 100), 2);
    } else if ($tipo_descuento === 'MontoFijo') {
        return min($valor_descuento, $precio_base); 
    }
    return 0;
}


function usarCupon($pdo, $id_cupon) {
    try {
        $stmt = $pdo->prepare("
            UPDATE cupones_b2b 
            SET usos_restantes = GREATEST(0, usos_restantes - 1)
            WHERE id_cupon = :id_cupon
        ");
        return $stmt->execute([':id_cupon' => $id_cupon]);
    } catch (PDOException $e) {
        error_log("Error usando cupón: " . $e->getMessage());
        return false;
    }
}


function obtenerResenasPorServicio($pdo, $id_servicio, $limite = 10) {
    try {
        $stmt = $pdo->prepare("
            SELECT r.*, u.nombre_completo, u.foto_perfil
            FROM resenas r
            JOIN usuarios u ON r.id_usuario_fk = u.id_usuario
            WHERE r.id_servicio_fk = :id_servicio
            ORDER BY r.fecha_resena DESC
            LIMIT :limite
        ");
        $stmt->bindParam(':id_servicio', $id_servicio, PDO::PARAM_INT);
        $stmt->bindParam(':limite', $limite, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    } catch (PDOException $e) {
        error_log("Error obteniendo reseñas: " . $e->getMessage());
        return [];
    }
}


function crearResena($pdo, $data) {
    try {

        $checkStmt = $pdo->prepare("
            SELECT id_resena FROM resenas 
            WHERE id_usuario_fk = :id_usuario 
            AND id_servicio_fk = :id_servicio
        ");
        $checkStmt->execute([
            ':id_usuario' => $data['id_usuario_fk'],
            ':id_servicio' => $data['id_servicio_fk']
        ]);

        if ($checkStmt->rowCount() > 0) {
            return ['success' => false, 'message' => 'Ya has reseñado este servicio'];
        }

   
        if ($data['calificacion'] < 1 || $data['calificacion'] > 5) {
            return ['success' => false, 'message' => 'La calificación debe estar entre 1 y 5'];
        }

        $stmt = $pdo->prepare("
            INSERT INTO resenas (id_usuario_fk, id_servicio_fk, calificacion, comentario)
            VALUES (:id_usuario, :id_servicio, :calificacion, :comentario)
        ");

        $result = $stmt->execute([
            ':id_usuario' => $data['id_usuario_fk'],
            ':id_servicio' => $data['id_servicio_fk'],
            ':calificacion' => (int)$data['calificacion'],
            ':comentario' => $data['comentario'] ?? null
        ]);

        return [
            'success' => $result,
            'message' => $result ? 'Reseña creada exitosamente' : 'Error al crear reseña'
        ];
    } catch (PDOException $e) {
        error_log("Error creando reseña: " . $e->getMessage());
        return ['success' => false, 'message' => 'Error en la base de datos'];
    }
}


function obtenerCalificacionPromedio($pdo, $id_servicio) {
    try {
        $stmt = $pdo->prepare("
            SELECT 
                AVG(calificacion) as promedio,
                COUNT(*) as total_resenas
            FROM resenas
            WHERE id_servicio_fk = :id_servicio
        ");
        $stmt->execute([':id_servicio' => $id_servicio]);
        return $stmt->fetch();
    } catch (PDOException $e) {
        error_log("Error obteniendo calificación promedio: " . $e->getMessage());
        return ['promedio' => 0, 'total_resenas' => 0];
    }
}



function actualizarPerfilUsuario($pdo, $id_usuario, $data) {
    try {
        $campos = [];
        $bindings = [':id_usuario' => $id_usuario];

        $campos_permitidos = ['nombre_completo', 'email', 'telefono', 'fecha_nacimiento', 'biografia', 'genero', 'foto_perfil'];
        
        foreach ($campos_permitidos as $campo) {
            if (isset($data[$campo])) {
                $campos[] = "`$campo` = :$campo";
                $bindings[":$campo"] = $data[$campo];
            }
        }

        if (empty($campos)) {
            return ['success' => false, 'message' => 'No hay datos para actualizar'];
        }

        $query = "UPDATE usuarios SET " . implode(", ", $campos) . ", `ultima_actualizacion` = NOW() WHERE id_usuario = :id_usuario";
        
        $stmt = $pdo->prepare($query);
        $result = $stmt->execute($bindings);

        return [
            'success' => $result,
            'message' => $result ? 'Perfil actualizado exitosamente' : 'Error al actualizar perfil'
        ];
    } catch (PDOException $e) {
        error_log("Error actualizando perfil: " . $e->getMessage());
        return ['success' => false, 'message' => 'Error en la base de datos'];
    }
}


function subirFotoPerfil($pdo, $id_usuario, $archivo) {
    try {

        $tipos_permitidos = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
        
        if (!in_array($archivo['type'], $tipos_permitidos)) {
            return ['success' => false, 'message' => 'El archivo debe ser una imagen válida'];
        }


        if ($archivo['size'] > 5 * 1024 * 1024) {
            return ['success' => false, 'message' => 'La imagen no debe superar 5MB'];
        }


        $extension = pathinfo($archivo['name'], PATHINFO_EXTENSION);
        $nombre_archivo = "perfil_" . $id_usuario . "_" . time() . "." . $extension;
        $ruta_destino = "assets/images/" . $nombre_archivo;


        if (!is_dir('assets/images')) {
            mkdir('assets/images', 0755, true);
        }


        if (!move_uploaded_file($archivo['tmp_name'], $ruta_destino)) {
            return ['success' => false, 'message' => 'Error al subir la imagen'];
        }

        $stmt = $pdo->prepare("UPDATE usuarios SET foto_perfil = :foto WHERE id_usuario = :id_usuario");
        $result = $stmt->execute([
            ':foto' => $ruta_destino,
            ':id_usuario' => $id_usuario
        ]);

        return [
            'success' => $result,
            'message' => $result ? 'Foto de perfil actualizada' : 'Error al guardar en base de datos',
            'ruta' => $ruta_destino
        ];
    } catch (Exception $e) {
        error_log("Error subiendo foto: " . $e->getMessage());
        return ['success' => false, 'message' => 'Error en la base de datos'];
    }
}


function obtenerUsuario($pdo, $id_usuario) {
    try {
        $stmt = $pdo->prepare("
            SELECT u.*, r.nombre_rol 
            FROM usuarios u
            JOIN roles r ON u.id_rol = r.id_rol
            WHERE u.id_usuario = :id_usuario
        ");
        $stmt->execute([':id_usuario' => $id_usuario]);
        return $stmt->fetch();
    } catch (PDOException $e) {
        error_log("Error obteniendo usuario: " . $e->getMessage());
        return null;
    }
}

?>
