FROM php:8.2-cli

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libpng-dev \
    libjpeg62-turbo-dev \
    libfreetype6-dev \
    libzip-dev \
    procps

# 使用PECL安装并启用xdebug和grpc
#RUN pecl install xdebug-3.0.0 grpc \
#    && docker-php-ext-enable xdebug grpc

RUN pecl install xdebug-3.2.1 grpc-1.59.1 && \
    docker-php-ext-enable xdebug grpc && \
    echo 'extension=grpc.so' >> /usr/local/etc/php/conf.d/docker-php-ext-grpc.ini

# 安装 Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

ENV PATH="/usr/local/bin:${PATH}"

# 设置容器内的工作目录
WORKDIR /www/html

# 复制项目文件和依赖定义
COPY . /www/html

# 安装 Composer 依赖
RUN composer install --no-scripts --no-autoloader && rm -rf /root/.composer

# 暴露端口9000
EXPOSE 9000

# 在容器启动时执行的命令
CMD ["tail", "-f", "/dev/null"]
