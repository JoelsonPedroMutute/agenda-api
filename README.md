# AGENDA-API

## Descrição: 
    A Agenda API é uma aplicação backend que permite a criação, leitura, atualização e exclusão de agendament(Appointments) e lembretes (Reminders), utilizando a arquitetura RESTful, com autenticação via Laravel Sanctum.

### Funcionalidades

Agendamentos (Appointments)
    Criação de agendamentos com título, descrição, status, data, hora de início e fim.

    Relacionamento entre usuários e agendamentos (um usuário pode ter múltiplos agendamentos).

    Eager loading para retornar lembretes associados aos agendamentos.

Lembretes (Reminders)
    Criação de lembretes associados aos agendamentos.

    Definição de horário para o lembrete e método de notificação (email, mensagem, notificação).

Filtros
    Filtros para busca de agendamentos e lembretes por diferentes parâmetros (data, status, método de notificação).

### Tecnologias
    PHP 8.x

    Laravel 12.x

    MySQL ou MariaDB

    Laravel Sanctum para autenticação via API

    Postman para testes da API

### Pré-requisitos
    Antes de rodar a aplicação, verifique se você tem os seguintes pré-requisitos instalados:

    PHP (versão 8.3 ou superior).

    Composer.

    MySQL ou MariaDB.

    .env configurado com as credenciais do banco de dados.


### Como Rodar o Projeto Localmente
    Clone o repositório:

 git clone https://github.com/JoelsonPedroMutute/agenda-api.git
cd agenda-api 

###  Instale as dependências:
    composer install

### Configure seu banco de dados no arquivo .env.
    php artisan key:generate

###  Inicie o servidor:
    php artisan serve
    A API estará disponível em http://127.0.0.1:8000

###  Comandos para Migrations, Seeders, Factories e Resources
    Rodar Migrations:
            php artisan migrate
### Se quiser resetar as tabelas e rodar novamente, use:
    php artisan migrate:fresh

### Rodar Seeders
php artisan db:seed

### Se você quiser rodar um seeder específico, por exemplo, o seeder de User
php artisan db:seed --class=UserSeeder

### Criar Resources para User, Appointment e Reminder
php artisan make:resource UserResource
php artisan make:resource AppointmentResource
php artisan make:resource ReminderResource

### Contribuindo
Se você quiser contribuir para o projeto, sinta-se à vontade para criar uma pull request com suas alterações.