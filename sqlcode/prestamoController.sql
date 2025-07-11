
-- 1) index() – Listar préstamos con usuario y libro, paginados

-- Para administradores: todos los préstamos
SELECT p.id,
       u.nombre AS usuario,
       l.titulo AS libro,
       p.fecha_prestamo,
       p.fecha_devolucion,
       p.estado
FROM prestamos p
JOIN usuarios u ON p.usuario_id = u.id
JOIN libros   l ON p.libro_id   = l.id
ORDER BY p.fecha_prestamo DESC
LIMIT 10 OFFSET :offset;

-- Para usuarios normales: solo sus propios préstamos
SELECT p.id,
       u.nombre AS usuario,
       l.titulo AS libro,
       p.fecha_prestamo,
       p.fecha_devolucion,
       p.estado
FROM prestamos p
JOIN usuarios u ON p.usuario_id = u.id
JOIN libros   l ON p.libro_id   = l.id
WHERE p.usuario_id = :usuario_id
ORDER BY p.fecha_prestamo DESC
LIMIT 10 OFFSET :offset;

-- ----------------------------------------
-- 2) store() – Crear un préstamo

-- a) Verificar ejemplares disponibles (bloqueo)
SELECT ejemplares
  FROM libros
 WHERE id = :libro_id
 FOR UPDATE;

-- b) Insertar préstamo
INSERT INTO prestamos (
    usuario_id,
    libro_id,
    fecha_prestamo,
    fecha_devolucion,
    estado,
    created_at,
    updated_at
) VALUES (
    :usuario_id,
    :libro_id,
    :fecha_prestamo,
    :fecha_devolucion,   -- NULL o fecha prevista
    'prestado',
    NOW(),
    NOW()
);

-- c) Decrementar ejemplares en libros
UPDATE libros
   SET ejemplares  = ejemplares - 1,
       updated_at  = NOW()
 WHERE id = :libro_id;

-- ----------------------------------------
-- 3) show() – Mostrar un préstamo específico

SELECT p.*,
       u.nombre AS usuario,
       l.titulo AS libro
FROM prestamos p
JOIN usuarios u ON p.usuario_id = u.id
JOIN libros   l ON p.libro_id   = l.id
WHERE p.id = :id;

-- ----------------------------------------
-- 4) update() – Actualizar un préstamo existente

-- a) Obtener estado y libro antiguo (bloqueo)
SELECT estado, libro_id
  FROM prestamos
 WHERE id = :id
 FOR UPDATE;

-- b) Si cambia de ‘prestado’ a ‘devuelto’, incrementar stock
UPDATE libros
   SET ejemplares  = ejemplares + 1,
       updated_at  = NOW()
 WHERE id = :old_libro_id
   AND :old_estado = 'prestado';

-- c) Actualizar los datos del préstamo
UPDATE prestamos
   SET usuario_id       = :usuario_id,
       libro_id         = :libro_id,
       fecha_prestamo   = :fecha_prestamo,
       fecha_devolucion = :fecha_devolucion,
       estado           = :estado,
       updated_at       = NOW()
 WHERE id = :id;

-- d) Si cambia a ‘prestado’ o ‘retrasado’, decrementar stock
UPDATE libros
   SET ejemplares  = ejemplares - 1,
       updated_at  = NOW()
 WHERE id = :libro_id
   AND :estado IN ('prestado','retrasado');

-- e) Si cambia de libro (ajustar en ambos libros)
--    Incrementar en libro anterior (si aplicable)
UPDATE libros
   SET ejemplares  = ejemplares + 1,
       updated_at  = NOW()
 WHERE id = :old_libro_id
   AND :old_libro_id != :libro_id
   AND :old_estado IN ('prestado','retrasado');

--    Decrementar en libro nuevo (si distinto)
UPDATE libros
   SET ejemplares  = ejemplares - 1,
       updated_at  = NOW()
 WHERE id = :libro_id
   AND :old_libro_id != :libro_id
   AND :estado IN ('prestado','retrasado');

-- ----------------------------------------
-- 5) destroy() – Eliminar un préstamo

-- a) Obtener estado y libro (bloqueo)
SELECT estado, libro_id
  FROM prestamos
 WHERE id = :id
 FOR UPDATE;

-- b) Si el préstamo está devuelto o retrasado, reincrementar stock
UPDATE libros
   SET ejemplares  = ejemplares + 1,
       updated_at  = NOW()
 WHERE id = :libro_id
   AND :estado IN ('devuelto','retrasado');

-- c) Eliminar el registro del préstamo
DELETE FROM prestamos
 WHERE id = :id;
