#!/bin/bash
set -e

echo "🚀 Старт деплоя Laravel проекта..."

# === 1. Обновление системы ===
echo "🔄 Обновляем пакеты..."
apt update && apt upgrade -y

# === 2. Установка Docker и Docker Compose ===
if ! command -v docker &> /dev/null
then
    echo "🐳 Устанавливаем Docker..."
    apt install -y docker.io
fi

if ! command -v docker compose &> /dev/null
then
    echo "📦 Устанавливаем Docker Compose..."
    apt install -y docker-compose
fi

# === 3. Подготовка проекта ===
APP_DIR="/var/www/laravel-app"

if [ ! -d "$APP_DIR" ]; then
    echo "📂 Создаём директорию проекта..."
    mkdir -p $APP_DIR
    echo "⚠️ Скопируйте файлы Laravel проекта в $APP_DIR и перезапустите deploy.sh"
    exit 1
fi

cd $APP_DIR

# === 4. Генерация .env если его нет ===
if [ ! -f ".env" ]; then
    echo "⚙️ Создаём .env..."
    cat > .env <<EOL
APP_NAME=Laravel
APP_ENV=production
APP_KEY=
APP_DEBUG=false
APP_URL=http://localhost

LOG_CHANNEL=stack

DB_CONNECTION=mysql
DB_HOST=db
DB_PORT=3306
DB_DATABASE=laravel
DB_USERNAME=laravel
DB_PASSWORD=secret

BROADCAST_DRIVER=log
CACHE_DRIVER=file
QUEUE_CONNECTION=sync
SESSION_DRIVER=file
SESSION_LIFETIME=120
EOL
fi

# === 5. Генерация Nginx-конфига ===
mkdir -p docker/nginx
cat > docker/nginx/default.conf <<EOL
server {
    listen 80;
    server_name _;

    root /var/www/public;
    index index.php index.html;

    location / {
        try_files \$uri \$uri/ /index.php?\$query_string;
    }

    location ~ \.php\$ {
        include fastcgi_params;
        fastcgi_pass app:9000;
        fastcgi_index index.php;
        fastcgi_param SCRIPT_FILENAME \$document_root\$fastcgi_script_name;
    }

    location ~ /\.ht {
        deny all;
    }
}
EOL

# === 6. Docker Build & Up ===
echo "🐳 Собираем контейнеры..."
docker compose down || true
docker compose up -d --build

# === 7. Laravel настройка ===
echo "⚙️ Настраиваем Laravel..."
docker exec -it laravel-app bash -c "php artisan key:generate"
docker exec -it laravel-app bash -c "php artisan migrate --seed"

echo "✅ Деплой завершён! Открой http://$(hostname -I | awk '{print $1}')"