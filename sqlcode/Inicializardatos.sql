-- INICIALIZACIÓN DE DATOS PARA BIBLIOTECA CON ROLES Y PERMISOS (SPATIE)

SET FOREIGN_KEY_CHECKS=0;

-- Limpiar tablas dependientes
DELETE FROM role_has_permissions;
DELETE FROM model_has_roles;
DELETE FROM model_has_permissions;
DELETE FROM prestamos;
DELETE FROM libros;
DELETE FROM usuarios;
DELETE FROM roles;
DELETE FROM permissions;

SET FOREIGN_KEY_CHECKS=1;

-- 1. PERMISOS
INSERT INTO permissions (id, name, guard_name, created_at, updated_at) VALUES
  (1, 'manage-users',        'web', NOW(), NOW()),
  (2, 'manage-books',        'web', NOW(), NOW()),
  (3, 'manage-all-loans',    'web', NOW(), NOW()),
  (4, 'manage-own-loans',    'web', NOW(), NOW()),
  (5, 'view-reports',        'web', NOW(), NOW());

-- 2. ROLES
INSERT INTO roles (id, name, guard_name, created_at, updated_at) VALUES
  (1, 'admin', 'web', NOW(), NOW()),
  (2, 'user',  'web', NOW(), NOW());

-- 3. ASIGNAR PERMISOS A ROLES (role_has_permissions)
INSERT INTO role_has_permissions (permission_id, role_id) VALUES
  (1, 1), -- admin: manage-users
  (2, 1), -- admin: manage-books
  (3, 1), -- admin: manage-all-loans
  (5, 1), -- admin: view-reports
  (4, 2); -- user: manage-own-loans

-- 4. USUARIOS
INSERT INTO usuarios (id, nombre, email, telefono, created_at, updated_at) VALUES
  (1, 'Ana García',    'ana.garcia@example.com',    '600123456', NOW(), NOW()),
  (2, 'Luis Martínez', 'luis.martinez@example.com', '600234567', NOW(), NOW()),
  (3, 'María Ruiz',    'maria.ruiz@example.com',    '600345678', NOW(), NOW()),
  (4, 'Admin Biblioteca', 'admin@biblioteca.com',   '600000000', NOW(), NOW());

-- 5. ASIGNAR ROLES A USUARIOS (model_has_roles)
-- Ajusta el campo model_type si tu namespace es distinto
INSERT INTO model_has_roles (role_id, model_type, model_id) VALUES
  (2, 'App\\Models\\Usuario', 1), -- Ana García → user
  (2, 'App\\Models\\Usuario', 2), -- Luis Martínez → user
  (2, 'App\\Models\\Usuario', 3), -- María Ruiz → user
  (1, 'App\\Models\\Usuario', 4); -- Admin Biblioteca → admin

-- 6. LIBROS
INSERT INTO libros (id, titulo, autor, isbn, ejemplares, created_at, updated_at) VALUES
  (1, 'Cien Años de Soledad',    'Gabriel García Márquez', '978-0307474728', 3, NOW(), NOW()),
  (2, 'Don Quijote de la Mancha','Miguel de Cervantes',    '978-8491050294', 2, NOW(), NOW()),
  (3, 'La Sombra del Viento',    'Carlos Ruiz Zafón',      '978-0143126393', 4, NOW(), NOW()),
  (4, '1984',                    'George Orwell',          '978-0451524935', 5, NOW(), NOW());

-- 7. PRÉSTAMOS
INSERT INTO prestamos (usuario_id, libro_id, fecha_prestamo, fecha_devolucion, estado, created_at, updated_at) VALUES
  (1, 4, DATE_SUB(CURDATE(), INTERVAL 10 DAY), DATE_ADD(CURDATE(), INTERVAL 5 DAY), 'prestado', NOW(), NOW()),
  (2, 1, DATE_SUB(CURDATE(), INTERVAL 20 DAY), DATE_SUB(CURDATE(), INTERVAL 5 DAY), 'devuelto', NOW(), NOW()),
  (3, 3, DATE_SUB(CURDATE(), INTERVAL 15 DAY), DATE_SUB(CURDATE(), INTERVAL 1 DAY), 'retrasado', NOW(), NOW());

-- (Opcional) Reiniciar los AUTO_INCREMENT
ALTER TABLE usuarios AUTO_INCREMENT = 5;
ALTER TABLE libros AUTO_INCREMENT = 5;
ALTER TABLE roles AUTO_INCREMENT = 3;
ALTER TABLE permissions AUTO_INCREMENT = 6;

-- FIN DEL SCRIPT
