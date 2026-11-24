# Manual básico — Sitio Chevrolet

Sitio autoadministrable con Inicio, Nosotros, Vehículos, Promociones y Contacto. Utiliza Laravel, PHP, SQLite, Blade y CSS/JavaScript básicos. El formulario guarda los mensajes en el panel; no envía correos automáticamente.

## 1. Puesta en marcha local

Requisitos: PHP 8.3 o superior y Composer. Desde la carpeta del proyecto:

```bash
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate --seed
php artisan storage:link
php artisan site:create-admin
php artisan serve
```

En Windows, el comando para copiar la configuración es `copy .env.example .env`. Si `.env` ya existe, no lo sobrescriba.

Abra `http://127.0.0.1:8000`. El panel está en `http://127.0.0.1:8000/admin`. El comando `site:create-admin` solicita nombre, correo y una contraseña de al menos 12 caracteres. No hay credenciales predeterminadas ni registro público.

## 2. Administrar el sitio

1. Acceda a **Administración**, en el pie de la web, e inicie sesión.
2. En el panel verá accesos a Vehículos, Promociones, Mensajes, Contenido y Contacto.
3. Después de cada cambio, use **Ver sitio** para comprobar el resultado. Cierre sesión al terminar.

### Vehículos

- **Añadir vehículo**: complete nombre, categoría y descripción. El precio es opcional.
- Marque **Publicado** para mostrarlo en el catálogo y **Destacado en inicio** para incluirlo también en la portada.
- Para cambiar una imagen, seleccione un JPG, JPEG, PNG o WebP de hasta 4 MB. Si no selecciona otra, se conserva la actual.
- **Editar** permite cambiar textos, precio, imagen y estados. **Eliminar** borra el registro y solicita confirmación; no elimina el archivo de imagen del servidor.
- El botón **Consultar** abre Contacto con el asunto prellenado.

### Promociones

- Complete título y descripción. Las fechas inicial y final son opcionales.
- Una promoción se muestra solo si está **Publicada** y la fecha actual está dentro de su vigencia, incluyendo las fechas de inicio y fin.
- Puede añadir o sustituir su imagen con un JPG, JPEG, PNG o WebP de hasta 4 MB. Deje el campo vacío para conservarla.

### Contenido

En **Contenido** puede modificar el título y la descripción de Inicio, el título y la descripción de Nosotros, y subir la imagen principal. La misma imagen se utiliza en Inicio y Nosotros.

Las secciones secundarias, los rótulos y la estructura visual son fijos. Para modificarlos se necesita editar las vistas o el CSS del proyecto.

### Datos de contacto

En **Contacto** del panel puede cambiar el nombre del negocio, teléfono, correo, dirección y WhatsApp. Para WhatsApp ingrese solo números, incluido el código de país, con un total de 8 a 15 dígitos.

### Mensajes

En **Mensajes** se guardan las consultas del formulario. Para responder, utilice el correo o teléfono indicado por la persona. Revise esta sección regularmente: no hay aviso automático por correo.

## 3. Imágenes y contenido de ejemplo

Antes de publicar:

- Sustituya los tres vehículos y la promoción de ejemplo por información real.
- Reemplace las fotografías de vehículos descargadas de TEMA por imágenes propias o con autorización.
- La imagen principal actual es una ilustración generada de una SUV genérica. Puede conservarla o subir una fotografía autorizada desde **Contenido**.
- Verifique precios, disponibilidad, vigencia de promociones, dirección y teléfonos.
- Defina quién revisará los mensajes y con qué frecuencia.

## 4. Comprobaciones antes de publicar

```bash
php artisan test
php artisan view:cache
```

Compruebe manualmente los cinco enlaces principales, los enlaces de Consultar, el menú y los filtros en celular y computadora, el formulario con datos válidos e inválidos, la creación y edición de vehículos y promociones, la carga de imágenes, los textos, los datos de contacto y el inicio y cierre de sesión.

El boceto actualizado está en `public/boceto.html`.

## 5. Dominio y publicación

El proyecto está preparado, pero **no está publicado** y no tiene un dominio configurado. Se necesita elegir proveedor de hosting, dominio y método de acceso.

1. Contratar el dominio y un plan con PHP 8.3+, extensiones de SQLite y terminal o acceso SSH.
2. Subir el proyecto sin `vendor`, `.env`, archivos de prueba ni bases de datos locales. En el servidor ejecutar `composer install --no-dev --optimize-autoloader`.
3. Configurar `.env` con `APP_ENV=production`, `APP_DEBUG=false`, `APP_URL=https://sudominio.com`, `DB_CONNECTION=sqlite` y la ruta absoluta de la base del servidor en `DB_DATABASE`.
4. Ejecutar en el servidor:

```bash
php artisan migrate --force
php artisan storage:link
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

5. Crear el administrador con `php artisan site:create-admin`.
6. Configurar el documento raíz del proveedor en la carpeta `public` del proyecto. No exponer la carpeta completa.
7. Dirigir el dominio al hosting en el panel DNS del proveedor y activar HTTPS.
8. Probar la web, el panel y las imágenes desde el dominio público.

Guarde una copia de seguridad de la base de datos y de `storage/app/public` antes de cada actualización. Nunca comparta `.env`, contraseñas ni claves de acceso.

## 6. Soluciones rápidas

- **No veo imágenes nuevas:** ejecute `php artisan storage:link` y confirme que existe el enlace `public/storage`.
- **No puedo entrar:** compruebe el correo y los permisos de administrador. Si hay demasiados intentos, espere un minuto.
- **Una promoción no aparece:** revise que esté publicada y que hoy esté dentro de su vigencia.
- **Un vehículo no aparece en Inicio:** debe estar publicado y marcado como destacado.
- **Cambios no visibles:** en producción ejecute `php artisan view:cache`.

## 7. Publicar la versión estática en GitHub Pages

El archivo `index.html` de la raíz contiene una versión estática de una sola página con Inicio, Nosotros, Vehículos, Promociones y Contacto. Usa rutas relativas, por lo que funciona tanto en un dominio como en la dirección de proyecto `https://usuario.github.io/chevrolet/`.

GitHub Pages no ejecuta PHP ni Laravel. Por eso, en esta versión:

- El panel de administración no está disponible.
- Los mensajes no se guardan en una base de datos.
- El formulario prepara un correo en la aplicación del visitante. Para activarlo, reemplace `data-contact-email=""` en `index.html` por el correo real, por ejemplo `data-contact-email="contacto@sudominio.com"`.
- Los vehículos, promociones, textos e imágenes se cambian editando `index.html` y los archivos de `public/`.

### Publicación automática

1. Confirme el correo de contacto en `index.html` y sustituya los contenidos de ejemplo.
2. Revise que las fotografías tengan autorización para publicación.
3. Suba los cambios a la rama `main` del repositorio de GitHub.
4. En GitHub, abra **Settings → Pages** y seleccione **GitHub Actions** como fuente.
5. Ejecute el workflow **Publicar sitio estático en GitHub Pages** o espere a que se ejecute al subir cambios a `main`.
6. Cuando termine, GitHub mostrará la dirección pública en **Settings → Pages** y en la pestaña **Actions**.

El workflow `.github/workflows/github-pages.yml` copia únicamente `index.html`, el favicon, CSS, JavaScript y las cuatro imágenes utilizadas. No publica `.env`, la base de datos, `vendor`, `storage` ni el código administrativo.

Para actualizar el sitio, edite los archivos, confirme los cambios y súbalos a `main`. El workflow volverá a publicar automáticamente. Esta versión estática es independiente del panel Laravel; los cambios realizados en uno no se reflejan automáticamente en el otro.

---

<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## About Laravel

Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable and creative experience to be truly fulfilling. Laravel takes the pain out of development by easing common tasks used in many web projects, such as:

- [Simple, fast routing engine](https://laravel.com/docs/routing).
- [Powerful dependency injection container](https://laravel.com/docs/container).
- Multiple back-ends for [session](https://laravel.com/docs/session) and [cache](https://laravel.com/docs/cache) storage.
- Expressive, intuitive [database ORM](https://laravel.com/docs/eloquent).
- Database agnostic [schema migrations](https://laravel.com/docs/migrations).
- [Robust background job processing](https://laravel.com/docs/queues).
- [Real-time event broadcasting](https://laravel.com/docs/broadcasting).

Laravel is accessible, powerful, and provides tools required for large, robust applications.

## Learning Laravel

Laravel has the most extensive and thorough [documentation](https://laravel.com/docs) and video tutorial library of all modern web application frameworks, making it a breeze to get started with the framework.

In addition, [Laracasts](https://laracasts.com) contains thousands of video tutorials on a range of topics including Laravel, modern PHP, unit testing, and JavaScript. Boost your skills by digging into our comprehensive video library.

You can also watch bite-sized lessons with real-world projects on [Laravel Learn](https://laravel.com/learn), where you will be guided through building a Laravel application from scratch while learning PHP fundamentals.

## Agentic Development

Laravel's predictable structure and conventions make it ideal for AI coding agents like Claude Code, Cursor, and GitHub Copilot. Install [Laravel Boost](https://laravel.com/docs/ai) to supercharge your AI workflow:

```bash
composer require laravel/boost --dev

php artisan boost:install
```

Boost provides your agent 15+ tools and skills that help agents build Laravel applications while following best practices.

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
