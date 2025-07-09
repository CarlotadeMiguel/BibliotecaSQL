-- index() - Obtener todos los libros
SELECT * FROM libros;

-- index() – Obtener todos los libros, paginados
SELECT * 
  FROM libros 
ORDER BY titulo 
LIMIT 10 OFFSET :offset;

-- disponibles() – Mostrar solo libros con ejemplares > 0
SELECT * 
  FROM libros 
 WHERE ejemplares > 0
 ORDER BY titulo;

 -- disponibilidadDetallada() – Stock actual, activos y total inicial
SELECT 
  l.id,
  l.titulo,
  l.autor,
  l.ejemplares      AS stock_actual,
  COUNT(p.id)       AS prestamos_activos,
  (l.ejemplares + COUNT(p.id)) AS total_inicial
FROM libros l
LEFT JOIN prestamos p 
  ON l.id = p.libro_id
 AND p.estado IN ('prestado','retrasado')
GROUP BY l.id, l.titulo, l.autor, l.ejemplares
ORDER BY l.titulo
LIMIT 10 OFFSET :offset;

-- store() - Añadir un libro
INSERT INTO libros (titulo, autor, isbn, ejemplares, created_at, updated_at)
VALUES ('Título del libro', 'Autor', 'ISBN1234', 3, NOW(), NOW());

-- show() — Mostrar un libro específico (por ID)
SELECT * FROM libros WHERE id = {id};

-- Antes de actualizar ejemplares, contar préstamos activos
SELECT COUNT(*) 
  FROM prestamos 
 WHERE libro_id = :id 
   AND estado IN ('prestado','retrasado');

-- update() — Actualizar libro existente
UPDATE libros
SET titulo = 'Nuevo título', autor = 'Nuevo autor', isbn = 'NuevoISBN', ejemplares = 5, updated_at = NOW()
WHERE id = {id};

-- destroy() — Eliminar libro por ID
DELETE FROM libros WHERE id = {id};

-- Mostrar solo libros disponibles
SELECT * FROM libros WHERE ejemplares > 0

-- Consulta avanzada con JOINs para mostrar disponibilidad detallada
SELECT l.*, COUNT(p.id) as prestamos_activos, 
      (l.ejemplares - COUNT(p.id)) as disponibles_reales
      FROM libros l
      LEFT JOIN prestamos p ON l.id = p.libro_id 
      AND p.estado IN ('prestado', 'retrasado')
      GROUP BY l.id
      HAVING disponibles_reales > 0