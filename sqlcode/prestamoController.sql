-- index() - Listar préstamos con usuario y libro
	-- Admin ve todos:
	SELECT prestamos.*, usuarios.nombre AS nombre_usuario, libros.titulo AS titulo_libro
	FROM prestamos
	JOIN usuarios ON prestamos.usuario_id = usuarios.id
	JOIN libros ON prestamos.libro_id = libros.id;

	-- Usuario normal solo sus préstamos:
	SELECT prestamos.*, usuarios.nombre AS nombre_usuario, libros.titulo AS titulo_libro
	FROM prestamos
	JOIN usuarios ON prestamos.usuario_id = usuarios.id
	JOIN libros ON prestamos.libro_id = libros.id
	WHERE prestamos.usuario_id = ?;
-- store() - Crear un prestamo
	-- verificar ejemplares disponibles
		SELECT ejemplares FROM libros WHERE id = {id} FOR UPDATE;
	--insertar prestamo
		INSERT INTO prestamos (usuario_id, libro_id, fecha_prestamo, fecha_devolucion, estado, created_at, updated_at)
VALUES ({usuario.id}, {libro.id}, '2025-06-30', NULL, 'prestado', NOW(), NOW());
	-- actualizar ejemplares
	UPDATE libros SET ejemplares = ejemplares - 1, updated_at = NOW() WHERE id = {libro.id};

-- show() — Mostrar un préstamo específico (por ID)
SELECT * FROM prestamos WHERE id = {id};

-- update() — Actualizar préstamo existente
	-- obtener estado y libro viejo (para comparar)
	SELECT estado, libro_id FROM prestamos WHERE id = {id} FOR UPDATE;
	-- actualizar préstamo
	UPDATE prestamos
	SET usuario_id = {usuario.id}, libro_id = {libro.id}, fecha_prestamo = {fecha_prestamo}, fecha_devolucion = {fecha_devolucion}, estado = {estado}, updated_at = NOW()
	WHERE id = {id};
	--Si cambia estado a devuelto, incrementar ejemplares
	UPDATE libros SET ejemplares = ejemplares + 1, updated_at = NOW() WHERE id = {libro.id};
	-- Si cambia estado de 'devuelto' a otro, disminuir ejemplares (verificar antes disponibilidad)
	SELECT ejemplares FROM libros WHERE id = {libro.id} FOR UPDATE;
	UPDATE libros SET ejemplares = ejemplares - 1, updated_at = NOW() WHERE id = {libro.id};
	-- Si cambia el libro en el préstamo, ajustar ejemplares
		-- Incrementar ejemplares en libro viejo
		UPDATE libros SET ejemplares = ejemplares + 1, updated_at = NOW() WHERE id = {libro.id};
		-- Verificar ejemplares libro nuevo
		SELECT ejemplares FROM libros WHERE id = {libro.id} FOR UPDATE;
		-- Disminuir ejemplares libro nuevo
		UPDATE libros SET ejemplares = ejemplares - 1, updated_at = NOW() WHERE id = ?;


-- destroy() — Eliminar préstamo
	--si el préstamo está activo (prestado o retrasado), incrementar ejemplares del libro:
	UPDATE libros SET ejemplares = ejemplares + 1, updated_at = NOW() WHERE id = {libro.id};
	-- eliminar préstamo
	DELETE FROM prestamos WHERE id = {id};

