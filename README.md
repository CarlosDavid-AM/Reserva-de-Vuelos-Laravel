# ✈️ Sistema de Reserva de Vuelos (Flight Reservation API)

API RESTful desarrollada en **Laravel 13** y **PHP 8.3+** diseñada para la gestión integral de reservas de vuelos, control de pasajeros, itinerarios con segmentos de vuelo y desglose de precios.

---

## 📋 Tabla de Contenido

- [Descripción General](#-descripción-general)
- [Características Principales](#-características-principales)
- [Tecnologías Utilizadas](#-tecnologías-utilizadas)
- [Requisitos Previos](#-requisitos-previos)
- [Instalación y Configuración](#-instalación-y-configuración)
- [Comandos para Ejecutar el Proyecto](#-comandos-para-ejecutar-el-proyecto)
- [Comandos Adicionales](#-comandos-adicionales)
- [Documentación de la API](#-documentación-de-la-api)
    - [Endpoints](#endpoints)
    - [Ejemplo de Petición (Crear Reserva)](#ejemplo-de-petición-crear-reserva)
    - [Ejemplo de Respuesta](#ejemplo-de-respuesta)
- [Estructura del Proyecto](#-estructura-del-proyecto)
- [Licencia](#-licencia)

---

## 📖 Descripción General

Este proyecto proporciona un servicio backend completo para la administración de reservas aéreas. Permite registrar reservas asociadas a uno o más pasajeros y a uno o varios segmentos de vuelo (trayectos con origen, destino, fechas y aerolínea), administrando de forma estructurada los costos (precio base, impuestos y precio total).

Las respuestas siguen el estándar JSON a través de Eloquent API Resources (`ReservationResource`) y cuentan con validaciones estrictas para garantizar la consistencia e integridad de los datos.

---

## ✨ Características Principales

- **CRUD de Reservas**: Endpoints RESTful para consultar, crear, ver en detalle, actualizar y eliminar reservas (`/api/reservations`).
- **Gestión de Pasajeros**: Asociación de pasajeros a reservas con validación de tipo de documento (`DNI`, `PASSPORT`, `CC`), número de identificación y fecha de nacimiento.
- **Gestión de Itinerarios y Segmentos**: Soporte para múltiples tramos de vuelo con validación de código IATA de 3 letras (`origin`, `destination`), fechas de salida/llegada y aerolínea transportista (`carrier`).
- **Desglose de Costos**: Cálculo y almacenamiento de `base_price`, `total_tax` y `total_price`.
- **Formato Consistente con API Resources**: Serialización limpia y uniforme de datos en las respuestas JSON.
- **Datos de Prueba y Factories**: Model Factories y Seeders listos para poblar el sistema con reservas y pasajeros de prueba.
- **Pruebas Automatizadas**: Configuración y soporte para suite de pruebas con Pest PHP.

---

## 🛠️ Tecnologías Utilizadas

- **Backend**: [PHP 8.3+](https://www.php.net/) / [Laravel 13](https://laravel.com/)
- **Base de Datos**: SQLite (configurada por defecto; compatible con MySQL y PostgreSQL)

---

## ⚙️ Requisitos Previos

Antes de comenzar, asegúrate de contar con lo siguiente instalado en tu entorno de desarrollo:

- **PHP** >= 8.3 (con extensiones `pdo`, `pdo_sqlite` o `pdo_mysql`, `mbstring`, `openssl`, `curl`, `xml`, `bcmath`)
- **Composer** >= 2.x ([getcomposer.org](https://getcomposer.org/))
- **Node.js** >= 18.x y **npm** ([nodejs.org](https://nodejs.org/))
- **Git** (opcional, para clonar el repositorio)

---

## 🚀 Instalación y Configuración

Puedes instalar y configurar el proyecto de forma automática o manual.

### Opción A: Instalación Automatizada (Recomendada)

El proyecto incluye un script de configuración predeterminado en Composer:

```bash
composer run setup
```

Este comando ejecuta de forma secuencial: instalación de dependencias PHP, creación del archivo `.env`, generación de la clave de aplicación, ejecución de migraciones y compilación de assets de frontend.

---

### Opción B: Instalación Manual Paso a Paso

1. **Clonar el repositorio o abrir la carpeta del proyecto:**

    ```bash
    git clone https://github.com/CarlosDavid-AM/Reserva-de-Vuelos-Laravel.git
    cd Reserva-de-Vuelos-Laravel-main
    ```

2. **Instalar dependencias de PHP:**

    ```bash
    composer install
    ```

3. **Configurar el archivo de entorno (`.env`):**
   Copia el archivo de ejemplo:
    - En Linux / macOS / Git Bash:
        ```bash
        cp .env.example .env
        ```
    - En Windows (PowerShell):
        ```powershell
        Copy-Item .env.example .env
        ```

4. **Generar la clave de la aplicación (`APP_KEY`):**

    ```bash
    php artisan key:generate
    ```

5. **Configurar la Base de Datos y ejecutar migraciones:**
   Por defecto, la aplicación utiliza SQLite (`DB_CONNECTION=sqlite`). Si el archivo de base de datos no existe en `database/database.sqlite`, créalo o deja que Laravel lo cree automáticamente:
    - En Linux / macOS:
        ```bash
        touch database/database.sqlite
        ```
    - En Windows (PowerShell):
        ```powershell
        New-Item -ItemType File -Path database/database.sqlite -Force
        ```

    Luego ejecuta las migraciones:

    ```bash
    php artisan migrate
    ```

    _(Opcional)_ Si deseas poblar la base de datos con datos de prueba:

    ```bash
    php artisan db:seed
    # O en un solo paso:
    php artisan migrate --seed
    ```

6. **Instalar dependencias de JavaScript y compilar assets:**
    ```bash
    npm install
    npm run build
    ```

---

## 💻 Comandos para Ejecutar el Proyecto

Para poner en marcha la aplicación en tu entorno local:

### 1. Servidor de Desarrollo Backend

Inicia el servidor local de Laravel:

```bash
php artisan serve
```

Por defecto, la API estará disponible en: **`http://localhost:8000`** (o `http://127.0.0.1:8000`).

### 2. Modo de Desarrollo Concurrente

También puedes usar el script de desarrollo integrado:

```bash
composer run dev
# o alternativamente:
php artisan dev
```

---

### Reiniciar la Base de Datos con Seeders

```bash
php artisan migrate:fresh --seed
```

### Formateo de Código (Pint)

```bash
vendor/bin/pint
```

### Limpiar Caché de la Aplicación

```bash
php artisan optimize:clear
```

---

## 📡 Documentación de la API

La API cuenta con los siguientes endpoints bajo el prefijo `/api`:

### Endpoints

| Método        | Endpoint                 | Descripción                                            |
| :------------ | :----------------------- | :----------------------------------------------------- |
| `GET`         | `/api/reservations`      | Lista todas las reservas con sus pasajeros y segmentos |
| `POST`        | `/api/reservations`      | Crea una nueva reserva con pasajeros e itinerario      |
| `GET`         | `/api/reservations/{id}` | Muestra el detalle de una reserva específica           |
| `PUT / PATCH` | `/api/reservations/{id}` | Actualiza parcialmente o totalmente una reserva        |
| `DELETE`      | `/api/reservations/{id}` | Elimina una reserva y sus registros dependientes       |

> [!NOTE]
> Recuerda enviar siempre las cabeceras:
>
> - `Content-Type: application/json`
> - `Accept: application/json`

---

### Ejemplo de Petición (Crear Reserva)

**`POST /api/reservations`**

```json
{
    "passengers": [
        {
            "firstName": "Carlos",
            "lastName": "Gómez",
            "documentNumber": "1020304050",
            "documentType": "CC",
            "birthday": "1992-08-20"
        }
    ],
    "itinerary": {
        "segment": [
            {
                "origin": "BOG",
                "destination": "MDE",
                "departure": "2026-11-15",
                "arrival": "2026-11-15",
                "carrier": "AV"
            },
            {
                "origin": "MDE",
                "destination": "CTG",
                "departure": "2026-11-20",
                "arrival": "2026-11-20",
                "carrier": "AV"
            }
        ],
        "price": {
            "basePrice": 320.0,
            "totalTax": 60.8,
            "totalPrice": 380.8
        }
    }
}
```

---

### Ejemplo de Respuesta

**`Status: 201 Created`**

```json
{
    "data": {
        "id": 1,
        "passengers": [
            {
                "firstName": "Carlos",
                "lastName": "Gómez",
                "documentNumber": "1020304050",
                "documentType": "CC",
                "birthday": "1992-08-20"
            }
        ],
        "itinerary": {
            "segment": [
                {
                    "origin": "BOG",
                    "destination": "MDE",
                    "departure": "2026-11-15",
                    "arrival": "2026-11-15",
                    "carrier": "AV"
                },
                {
                    "origin": "MDE",
                    "destination": "CTG",
                    "departure": "2026-11-20",
                    "arrival": "2026-11-20",
                    "carrier": "AV"
                }
            ],
            "price": {
                "totalPrice": 380.8,
                "totalTax": 60.8,
                "basePrice": 320
            }
        }
    }
}
```

---

## 📂 Estructura del Proyecto

Principales archivos y directorios del proyecto:

```text
reserva_de_vuelos/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   └── ReservationController.php   # Lógica del CRUD de reservas
│   │   └── Resources/
│   │       └── ReservationResource.php     # Formato de respuesta JSON de la API
│   └── Models/
│       ├── Passenger.php                   # Modelo de Pasajero
│       ├── Reservation.php                 # Modelo de Reserva
│       ├── Segment.php                     # Modelo de Segmento de vuelo
│       └── User.php                        # Modelo de Usuario
├── database/
│   ├── factories/                          # Factories para Passenger, Reservation, Segment
│   ├── migrations/                         # Migraciones de las tablas de la BD
│   └── seeders/                            # Seeders para poblar datos de prueba
├── routes/
│   ├── api.php                             # Rutas de la API (/api/reservations)
│   └── web.php                             # Rutas web
├── tests/                                  # Pruebas automatizadas con Pest
├── composer.json                           # Dependencias y scripts PHP
└── package.json                            # Dependencias y scripts de frontend
```

---

## 📄 Licencia

Este proyecto es de código abierto bajo la licencia [MIT](https://opensource.org/licenses/MIT).
