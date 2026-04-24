# Use the official Nginx image from docker Hub
FROM nginx:1.29.4-alpine3.23

ARG UID
ARG GID

ENV UID=${UID}
ENV GID=${GID}

RUN mkdir -p /var/www/html

# MacOS staff group's gid is 20
RUN delgroup dialout

# Add laravel user and group
RUN addgroup -g ${GID} --system laravel
RUN adduser -G laravel --system -D -s /bin/sh -u ${UID} laravel
#RUN sed -i "s/user  nginx/user laravel/g" /etc/nginx/default.conf

# Install Node.js and npm from Alpine repositories
RUN apk add --no-cache nodejs npm

# Change ownership of nginx directories
# Change ownership of nginx directories
RUN chown -R laravel:laravel /var/cache/nginx \
    && chown -R laravel:laravel /var/run \
    && chown -R laravel:laravel /var/log/nginx

COPY ./default.conf /etc/nginx/conf.d/

## Copy nginx configuration file
#COPY default.conf /etc/nginx/default.conf
#
# Expose port 80
EXPOSE 80
