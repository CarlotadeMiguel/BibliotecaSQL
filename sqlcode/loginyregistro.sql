-- Buscar usuario por email
SELECT * FROM usuarios WHERE email = 'usuario@email.com' LIMIT 1;

-- Verificar contraseña
SELECT * FROM usuarios
WHERE email = 'usuario@email.com'
  AND password = '$2y$10$w1oJr3c8v1bK2gHk6Q1j0uJxK5Dq9t9h8Q1hE6x2Jw7kB5pL9v1q2K'
LIMIT 1;

-- Registro
INSERT INTO usuarios (nombre, email, telefono, password, created_at, updated_at)
VALUES
('Ana García', 'ana.garcia@example.com', '600123456', '$2y$10$w1oJr3c8v1bK2gHk6Q1j0uJxK5Dq9t9h8Q1hE6x2Jw7kB5pL9v1q2K', NOW(), NOW());

