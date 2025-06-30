# Sistema de Gestión de Biblioteca - Laravel

Este proyecto es una aplicación básica para gestionar libros, usuarios y préstamos en una biblioteca, desarrollada con Laravel. Incluye controladores con toda la lógica necesaria para operaciones CRUD y reglas específicas para el manejo de préstamos y ejemplares disponibles.

---

## Funcionalidades

### Entidades

- **Usuarios**: Crear, editar, listar, mostrar y eliminar usuarios.
- **Libros**: Crear, editar, listar, mostrar y eliminar libros, con control de ejemplares disponibles.
- **Préstamos**: Registrar préstamos de libros a usuarios, actualizar estados (prestado, devuelto, retrasado) y eliminar préstamos con lógica para actualizar la cantidad de ejemplares disponibles.

---

## Detalles técnicos

### Controladores

- **LibroController**
  - Métodos CRUD para libros.
  - Validación: título obligatorio, ejemplares mínimo 1, ISBN único opcional.
  - Actualización y eliminación simples sin lógica extra.
  
- **UsuarioController**
  - Métodos CRUD para usuarios.
  - Validación: nombre obligatorio, email único obligatorio, teléfono opcional.
  
- **PrestamoController**
  - CRUD con validación estricta.
  - Control de ejemplares disponibles al crear y actualizar préstamos.
  - Al crear un préstamo:
    - Verifica que el libro tenga ejemplares disponibles.
    - Disminuye la cantidad de ejemplares.
  - Al actualizar un préstamo:
    - Si el estado cambia a `devuelto`, se incrementan los ejemplares.
    - Si cambia de `devuelto` a `prestado` o `retrasado`, se decrementan ejemplares (si hay disponibles).
    - Si cambia el libro, se ajustan ejemplares en el libro viejo y el nuevo.
  - Al eliminar un préstamo:
    - Se solicita confirmación para incrementar ejemplares si el préstamo está activo (`prestado` o `retrasado`).
  
### Validaciones

- Uso de validaciones Laravel en todos los métodos `store` y `update`.
- Manejo de errores con `try-catch` y transacciones para mantener la integridad de datos.
- Bloqueos de registros (`lockForUpdate`) para evitar condiciones de carrera en la actualización de ejemplares.

---

## Cómo probar la lógica

Puedes probar la lógica desde la consola `php artisan tinker` con comandos como:

- Crear un usuario:
```php
  $usuario = App\Models\Usuario::create(['nombre' => 'Juan Pérez', 'email' => 'juan@example.com']);
```

- Crear un libro:
```php
$libro = App\Models\Libro::create(['titulo' => 'Laravel Básico', 'autor' => 'Autor Ejemplo', 'ejemplares' => 3]);
```

- Crear un préstamo (decrementa ejemplares):
```php
$prestamo = App\Models\Prestamo::create([
    'usuario_id' => $usuario->id,
    'libro_id' => $libro->id,
    'fecha_prestamo' => now(),
    'estado' => 'prestado',
]);
$libro->decrement('ejemplares');
```

- Actualizar préstamo a devuelto (incrementa ejemplares):
```php
$prestamo = App\Models\Prestamo::findOrFail(ID_DEL_PRESTAMO);
$prestamo->update([
    'estado' => 'devuelto',
    'fecha_devolucion' => now(),
]);
$libro = App\Models\Libro::findOrFail($prestamo->libro_id);
$libro->increment('ejemplares');
```

## Consideraciones

- El sistema no permite préstamos si no hay ejemplares disponibles.
- La eliminación de un préstamo activo pide confirmación para actualizar ejemplares, para evitar inconsistencias.
- El sistema maneja correctamente cambios en préstamos que implican cambio de libro o estado.
- La gestión de usuarios y libros es estándar con validaciones comunes.

## Próximos pasos

- Implementar las vistas y formularios para manejar las confirmaciones.
- Agregar autenticación para controlar el acceso a datos.
- Añadir tests automatizados para validar las reglas de negocio.
- Mejorar UX en la gestión de préstamos y notificaciones para usuarios.

---

¡Listo para usar y mejorar!  
Si tienes dudas o sugerencias, no dudes en comentar.
