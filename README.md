### Silex Skel

Simples esqueleto para Silex v1.3.4, neste esquelo temos configuração para `Doctrine 2`, ` Monolog`, `Twig` e `Route` 

## Configurando rotas

Para configurar as rotas do sistema basta adicioná-las no arquivo *config/routes.yml*

    home:
        routeName: 'home'
        pattern: /home
        method: [ 'get', 'post' ]
        controller: 'Foo\Controllers\FooController::getAction'

Com esta configuração o sistem será capaz de configurar as rotas, para mais informações veja neste link: [GitHub](https://github.com/marcojanssen/silex-routing-service-provider)

## Configurando banco de dados (Doctrine)

Podemos ter mais de um amiente na aplicação por exemplo `development`, `homolog` e `prodution` o arquivo, *config/doctrine.yml*
tem as configurações para estes três ambiantes, com o uso da lib `dflydev/doctrine-orm-service-provider`, para mais informações [GitHub](https://github.com/dflydev/dflydev-doctrine-orm-service-provider)

## Configurando log (Monolog)

No arquivo *config/monolog.yml* também tem as configurações dos três ambiantes `development`, `homolog` e `prodution`, cada
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

Na hora de configurar o apache ou nginx, basta apontar o `document root` para a pasta `public`, nesta pasta tem um arquivo chamado `index.php`
é este arquivo que inicia toda a aplicação.

