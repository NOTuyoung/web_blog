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

if ! docker compose version &> /dev/null
then
    echo "📦 Устанавливаем Docker Compose (v2)..."
    apt install -y docker-compose-plugin
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

# === 4. Генерация Nginx-конфига ===
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

# === 5. Docker Build & Up ===
echo "🐳 Собираем контейнеры..."
docker compose down || true
docker compose up -d --build

# === 6. Laravel настройка ===
echo "⚙️ Настраиваем Laravel..."
docker compose exec app composer install
docker compose exec app cp .env.example .env || true
docker compose exec app php artisan key:generate
docker compose exec app php artisan migrate --seed


echo "✅ Деплой завершён! Открой http://$(hostname -I | awk '{print $1}')"
