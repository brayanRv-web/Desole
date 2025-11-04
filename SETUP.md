# Setup rápido para colaboradores (Windows / PowerShell)

Este documento ayuda a que cualquier colaborador pueda clonar el repo y levantar la aplicación Laravel en Windows (XAMPP) con PowerShell.

Requisitos mínimos
- PHP 8.2+
- Composer
- Node.js + npm (o pnpm)
- MySQL (XAMPP es una opción común)
- Git

Pasos (PowerShell)

1. Clona el repositorio:

```powershell
git clone https://github.com/brayanRv-web/Desole.git
cd Desole
```

2. Instala dependencias PHP y Node:

```powershell
composer install
npm install
```

3. Copia el ejemplo de entorno y configura la base de datos (edita `.env`):

```powershell
copy .env.example .env
# Edita .env y ajusta DB_HOST/DB_DATABASE/DB_USERNAME/DB_PASSWORD
```

4. Genera la key de la aplicación:

```powershell
php artisan key:generate
```

5. Crea la base de datos (por ejemplo en phpMyAdmin) y ejecuta migraciones y seeders:

```powershell
php artisan migrate --force
php artisan db:seed --class=AdminSeeder --force
php artisan db:seed --class=CategoriaSeeder --force
php artisan db:seed --class=EmpleadoSeeder --force
```

6. Enlaza el directorio de almacenamiento (para que `storage` sea accesible desde `public`):

```powershell
php artisan storage:link
```

7. Compila los assets (Vite):

- Para desarrollo (hot reload):
```powershell
npm run dev
```

- Para producción / generar el manifiesto de Vite (recomendado antes de desplegar):
```powershell
npm run build
```

8. Levanta la aplicación (opcional):

```powershell
php artisan serve
# o usar tu stack (Apache de XAMPP apuntando a public/)
```

Problemas frecuentes y soluciones
- Error "Unable to locate file in Vite manifest: resources/css/desole.css": asegura haber ejecutado `npm run build` (o `npm run dev` en modo dev) para generar `public/build/manifest.json`.
- Asegúrate de que `.env` contiene credenciales correctas y que MySQL está corriendo.
- Si ves problemas con permisos de `storage` o `bootstrap/cache`, en Windows normalmente no es necesario pero asegúrate de que tu usuario puede escribir en esas carpetas.

Automatización (opcional)
- Puedes ejecutar el script `scripts/bootstrap.ps1` para un setup automatizado en PowerShell (ver `scripts/bootstrap.ps1`).

Buenas prácticas antes de push/pull
- Añade tu `.env` a `.gitignore` (ya debería estarlo).
- Al sincronizar con el repositorio remoto, ejecuta `composer install` y `npm install` si hay cambios en `composer.json` o `package.json`.

Si quieres, puedo crear un `scripts/bootstrap.ps1` que automatice los pasos 2-7 y validaré que funciona en tu entorno (yo no puedo ejecutar git push). Pide "crea script bootstrap" y lo añado.
