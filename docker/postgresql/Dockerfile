# Usa la imagen oficial de PostgreSQL sobre Alpine
FROM postgres:18.1-alpine3.23

# Información opcional
LABEL maintainer="gssa-13 <gssa-13@protonmail.com>"
LABEL description="PostgreSQL 18.0 con Alpine Linux y extensiones adicionales"

# Instalar extensiones opcionales de Postgres
RUN apk add --no-cache postgresql-contrib

# Variables de entorno por defecto (pueden sobreescribirse en docker-compose)
ENV POSTGRES_DB=laravel
ENV POSTGRES_USER=laravel
ENV POSTGRES_PASSWORD=secret

# Exponer el puerto por defecto de Postgres
EXPOSE 5432
