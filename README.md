# AGENDA-API

## Descrição: 
    A Agenda API é uma aplicação backend que permite a criação, leitura, atualização e exclusão de agendament(Appointments) e lembretes (Reminders), utilizando a arquitetura RESTful, com autenticação via Laravel Sanctum. Ideal para integração com aplicações frontend (web ou mobile).

### Funcionalidades

Agendamentos (Appointments)
    Criação de agendamentos com título, descrição, status, data, hora de início e fim.

    Relacionamento entre usuários e agendamentos (um usuário pode ter múltiplos agendamentos).

    Eager loading para retornar lembretes associados aos agendamentos.

Lembretes (Reminders)
    Criação de lembretes associados aos agendamentos.

    Definição de horário para o lembrete e método de notificação (email, mensagem, notificação), entre outros.

Filtros
    Filtros para busca de agendamentos e lembretes por diferentes parâmetros (data, status, método de notificação).
    Suporte a soft deletes com filtros como `trashed=only` ou `trashed=with`.

### Tecnologias
   PHP 8.x

    Laravel 12.x

    PostgreSQL (substituindo o MySQL/MariaDB)

    Laravel Sanctum para autenticação

    Swagger (via l5-swagger) para documentação da API

    WAMP Server como ambiente de desenvolvimento local

    Docker e Docker Compose para containerização da aplicação

    Postman ou Insomnia para testes de endpoints 

### Pré-requisitos
    Antes de rodar a aplicação, verifique se você tem os seguintes pré-requisitos instalados:

    PHP (versão 8.3 ou superior).

    Composer

    PostgreSQL

    Git

    Docker e Docker Compose

    WAMP (como ambiente alternativo ao Laravel Herd)

    .env corretamente configurado com credenciais atualizadas do banco PostgreSQL


### Como Rodar o Projeto Localmente
    Clone o repositório:

 git clone https://github.com/JoelsonPedroMutute/agenda-api.git


###  Instale as dependências:
    composer install

### Copie o arquivo de ambiente e gere a chave da aplicação: 
    copy .env.example .env 
    php artisan key:generate

### Configure seu banco de dados no arquivo .env. com os dados corretos do banco de dados. Exemplo: 
    DB_CONNECTION=pgsql
    DB_HOST=127.0.0.1
    DB_PORT=5432
    DB_DATABASE=agenda
    DB_USERNAME=postgres
    DB_PASSWORD=secret

###  Comandos para Migrations
    Rodar Migrations: php artisan migrate

### Se quiser resetar as tabelas e rodar novamente, use:
    php artisan migrate:fresh

### Rodar Seeders
    php artisan db:seed

### Se você quiser rodar um seeder específico, por exemplo, o seeder de User
    php artisan db:seed --class=UserSeeder

### Usando Docker
    docker-compose up -d --build

### Acessando a Documentação Swagger
    http://localhost:8000/api/documentation

### Ambiente de Desenvolvimento
    Se não estiver usando Docker, inicie com::

    php artisan serve

    A API estará disponível em  http://localhost:8000

### Contribuindo
    Se você quiser contribuir para o projeto, sinta-se à vontade para criar uma pull request com suas alterações. Basta fazer um fork do repositório, criar uma branch com sua feature, fazer os commits necessários e abrir uma pull request com uma descrição clara do que foi implementado.