[![Codacy Badge](https://api.codacy.com/project/badge/f325962c9f22472bb7fb5270fc404338)](https://www.codacy.com/app/eminetto/silex-skel)

### Silex Skel

Simples esqueleto para Silex v1.3.4, neste esqueleto temos configuração para `Doctrine 2`, ` Monolog`, `Twig` e `Route`

## Configurando rotas

Para configurar as rotas do sistema basta adiciona-las no arquivo *config/routes.yml*

    home:
        routeName: 'home'
        pattern: /home
        method: [ 'get', 'post' ]
        controller: 'Foo\Controllers\FooController::getAction'

Com esta configuração o sistem será capaz de configurar as rotas, para mais informações veja neste link: [GitHub](https://github.com/marcojanssen/silex-routing-service-provider)

## Configurando banco de dados (Doctrine)

Podemos ter mais de um amiente na aplicação por exemplo `development`, `homolog` e `prodution` o arquivo, *config/doctrine.yml*
tem as configurações para estes três ambientes, com o uso da lib `dflydev/doctrine-orm-service-provider`, para mais informações [GitHub](https://github.com/dflydev/dflydev-doctrine-orm-service-provider)

## Configurando log (Monolog)

No arquivo *config/monolog.yml* também tem as configurações dos três ambientes `development`, `homolog` e `prodution`, cada
ambiente com um retorno diferente de log, para saber mais sobre Monolog [GitHub](https://github.com/Seldaek/monolog)

## CommandLine

O arquivo *console.php* fornece todos os comandos suportado pelo esqueleto, podemos encontrar comando do Doctrine e das Migrations.

## Arquiterura de pastas e arquivos

    config \
        config.yml
        doctrine.yml
        monolog.yml
        routes.yml
    data \
        DoctrineMigrations \
            .gitignore
        DoctrineOrm \
            Proxy \
                .gitignore
        log \
            .gitignore
    public \
        index.php
    src \
        Skel \
            Controller \
                Index.php
            Entity
                User.php
    tests \
        src \
            Skel \
                .gitignore
    views \
        index \
            index.twig
        layout \
            layout.twig
    .gitignore
    bootstrap.php
    composer.json
    composer.lock
    console.php
    phpunit.xml

## Apache / Nginx

Na hora de configurar o apache ou nginx, basta apontar o `document root` para a pasta `public`, nesta pasta tem um arquivo chamado `index.php` é este arquivo que inicia toda a aplicação.

Exemplo apache:
---------------

     <VirtualHost *:80>
         ServerName meusite.localhost
         DocumentRoot /path/to/silex-skel/public
         SetEnv APPLICATION_ENV "development"
         <Directory /path/to/silex-skel/public>
             DirectoryIndex index.php
             AllowOverride All
             Order allow,deny
             Allow from all
         </Directory>
     </VirtualHost>

Exemplo nginx:
---------------

    server {
        server_name meusite.localhost;
        root /path/to/silex-skel/public;

        location / {
            # try to serve file directly, fallback to front controller
            try_files $uri /index.php$is_args$args;
        }

        # If you have 2 front controllers for dev|prod use the following line instead
        # location ~ ^/(index|index_dev)\.php(/|$) {
        location ~ ^/index\.php(/|$) {
            # the ubuntu default
            fastcgi_pass   unix:/var/run/php5-fpm.sock;
            # for running on centos
            #fastcgi_pass   unix:/var/run/php-fpm/www.sock;

            fastcgi_split_path_info ^(.+\.php)(/.*)$;
            include fastcgi_params;
            fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
            fastcgi_param HTTPS off;

            # Prevents URIs that include the front controller. This will 404:
            # http://domain.tld/index.php/some-path
            # Enable the internal directive to disable URIs like this
            # internal;
        }

        #return 404 for all php files as we do have a front controller
        location ~ \.php$ {
            return 404;
        }

        error_log /var/log/nginx/project_error.log;
        access_log /var/log/nginx/project_access.log;
    }

