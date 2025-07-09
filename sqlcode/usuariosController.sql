-- index() - Listar usuarios
SELECT * FROM usuarios;

-- index() – Listar usuarios paginados
SELECT * 
  FROM usuarios 
ORDER BY nombre 
LIMIT 10 OFFSET :offset;

-- store() - Crear un usuario
INSERT INTO usuarios (nombre,email,telefono,password,created_at,updated_at)
VALUES (:nombre,:email,:telefono,:password,NOW(),NOW());

-- Asignar rol (model_has_roles)
INSERT INTO model_has_roles(role_id,model_type,model_id)
VALUES ((SELECT id FROM roles WHERE name = :rol),'App\\Models\\Usuario',LAST_INSERT_ID());

-- show() — Mostrar un usuario específico (por ID)
SELECT * FROM usuarios WHERE id = {id};

-- update() — Editar un usuario
UPDATE usuarios
   SET nombre     = :nombre,
       email      = :email,
       telefono   = :telefono,
       updated_at = NOW()
 WHERE id = :id;

-- Cambiar rol
DELETE FROM model_has_roles
 WHERE model_type = 'App\\Models\\Usuario'
   AND model_id   = :id;
INSERT INTO model_has_roles(role_id,model_type,model_id)
VALUES ((SELECT id FROM roles WHERE name = :rol),'App\\Models\\Usuario',:id);

-- destroy() — Eliminar libro por ID
-- Sólo eliminar si no hay préstamos activos
SELECT COUNT(*) 
  FROM prestamos 
 WHERE usuario_id = :id 
   AND estado IN ('prestado','retrasado');

DELETE FROM usuarios 
 WHERE id = :id;
