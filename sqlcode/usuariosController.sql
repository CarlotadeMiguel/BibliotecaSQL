-- index() - Listar usuarios
SELECT * FROM usuarios;


-- store() - Crear un usuario
INSERT INTO usuarios (nombre, email, telefono, created_at, updated_at)
VALUES ('Nombre', 'email@ejemplo.com', '123456789', NOW(), NOW());

-- show() — Mostrar un usuario específico (por ID)
SELECT * FROM usuarios WHERE id = {id};

-- update() — Editar un usuario
UPDATE usuarios
SET nombre = 'Nombre actualizado', email = 'nuevo@email.com', telefono = '987654321', updated_at = NOW()
WHERE id = {id};

-- destroy() — Eliminar libro por ID
DELETE FROM usuarios WHERE id = {id};
