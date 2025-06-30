-- index() - Obtener todos los libros
SELECT * FROM libros;


-- store() - Añadir un libro
INSERT INTO libros (titulo, autor, isbn, ejemplares, created_at, updated_at)
VALUES ('Título del libro', 'Autor', 'ISBN1234', 3, NOW(), NOW());

-- show() — Mostrar un libro específico (por ID)
SELECT * FROM libros WHERE id = {id};

-- update() — Actualizar libro existente
UPDATE libros
SET titulo = 'Nuevo título', autor = 'Nuevo autor', isbn = 'NuevoISBN', ejemplares = 5, updated_at = NOW()
WHERE id = {id};

-- destroy() — Eliminar libro por ID
DELETE FROM libros WHERE id = {id};

