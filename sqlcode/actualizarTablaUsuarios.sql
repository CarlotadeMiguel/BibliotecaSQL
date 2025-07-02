-- Actualiza la tabla usuarios para agregar la columna password
ALTER TABLE usuarios
ADD COLUMN password VARCHAR(255) AFTER telefono;

-- Actualiza los datos de prueba
UPDATE usuarios SET password = '$2y$10$w1oJr3c8v1bK2gHk6Q1j0uJxK5Dq9t9h8Q1hE6x2Jw7kB5pL9v1q2K' WHERE email = 'ana.garcia@example.com';
UPDATE usuarios SET password = '$2y$10$w1oJr3c8v1bK2gHk6Q1j0uJxK5Dq9t9h8Q1hE6x2Jw7kB5pL9v1q2K' WHERE email = 'luis.martinez@example.com';
UPDATE usuarios SET password = '$2y$10$w1oJr3c8v1bK2gHk6Q1j0uJxK5Dq9t9h8Q1hE6x2Jw7kB5pL9v1q2K' WHERE email = 'maria.ruiz@example.com';
UPDATE usuarios SET password = '$2y$10$w1oJr3c8v1bK2gHk6Q1j0uJxK5Dq9t9h8Q1hE6x2Jw7kB5pL9v1q2K' WHERE email = 'admin@biblioteca.com';
