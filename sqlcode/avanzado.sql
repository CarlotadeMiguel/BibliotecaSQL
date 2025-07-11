-- 1. Procedimiento almacenado para marcar vencidos
DROP PROCEDURE IF EXISTS mark_vencidos;
CREATE PROCEDURE mark_vencidos()
BEGIN
  UPDATE prestamos
  SET estado = 'retrasado'
  WHERE fecha_devolucion IS NULL
    AND DATE_ADD(fecha_prestamo, INTERVAL plazo DAY) < NOW();
END;

-- 2. Índices de optimización
CREATE INDEX idx_prestamos_usuario_id ON prestamos(usuario_id);
CREATE INDEX idx_prestamos_libro_id ON prestamos(libro_id);
CREATE INDEX idx_prestamos_fecha_prestamo ON prestamos(fecha_prestamo);
CREATE INDEX idx_prestamos_estado ON prestamos(estado);
CREATE INDEX idx_libros_titulo ON libros(titulo);
CREATE INDEX idx_libros_autor ON libros(autor);
CREATE INDEX idx_libros_categoria ON libros(categoria);

-- 3. Vista de libros más prestados
DROP VIEW IF EXISTS libros_mas_prestados;
CREATE VIEW libros_mas_prestados AS
SELECT
  l.id,
  l.titulo,
  l.autor,
  l.categoria,
  COUNT(p.id) AS total_prestamos
FROM libros l
LEFT JOIN prestamos p ON l.id = p.libro_id
GROUP BY l.id, l.titulo, l.autor, l.categoria
ORDER BY total_prestamos DESC;

-- 4. Consultas para dashboard y notificaciones
-- Préstamos por mes
SELECT
  DATE_FORMAT(fecha_prestamo, '%Y-%m') AS mes,
  COUNT(*) AS total_prestamos
FROM prestamos
GROUP BY DATE_FORMAT(fecha_prestamo, '%Y-%m')
ORDER BY mes DESC;

-- Préstamos próximos a vencer (3 días)
SELECT
  p.id,
  u.nombre,
  u.email,
  l.titulo,
  DATE_ADD(p.fecha_prestamo, INTERVAL p.plazo DAY) AS fecha_vencimiento
FROM prestamos p
JOIN usuarios u ON p.usuario_id = u.id
JOIN libros l ON p.libro_id = l.id
WHERE p.estado = 'prestado'
  AND DATE_ADD(p.fecha_prestamo, INTERVAL p.plazo DAY)
      BETWEEN CURDATE() AND DATE_ADD(CURDATE(), INTERVAL 3 DAY);
