FROM php:8.0-apache

# Instala extensões necessárias
RUN docker-php-ext-install pdo pdo_mysql mysqli

# Habilita o mod_rewrite
RUN a2enmod rewrite

# Copia os arquivos do projeto para o diretório do Apache
COPY . /var/www/html/

# Define permissões
RUN chown -R www-data:www-data /var/www/html

# Configura o Apache para permitir .htaccess
RUN sed -i 's/AllowOverride None/AllowOverride All/' /etc/apache2/apache2.conf

# Habilita exibição de erros no PHP (cria o php.ini)
RUN echo "display_errors = On\n" > /usr/local/etc/php/conf.d/docker-php-errors.ini \
    && echo "error_reporting = E_ALL\n" >> /usr/local/etc/php/conf.d/docker-php-errors.ini