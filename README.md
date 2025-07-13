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

    MySQL ou MariaDB

    Laravel Sanctum para autenticação via API

    Postman ou Insomnia para testes de endpoints da API

    Laravel Herd como ambiente de desenvolvimento local  
    Swagger para documentação da API  

### Pré-requisitos
    Antes de rodar a aplicação, verifique se você tem os seguintes pré-requisitos instalados:

    PHP (versão 8.3 ou superior).

    Composer.

    MySQL ou MariaDB.

    Laravel Herd 

    .env configurado com as credenciais do banco de dados.


### Como Rodar o Projeto Localmente
    Clone o repositório:

 git clone https://github.com/JoelsonPedroMutute/agenda-api.git


###  Instale as dependências:
    composer install

### Copie o arquivo de ambiente e gere a chave da aplicação: 
    copy .env.example .env 
    php artisan key:generate

### Configure seu banco de dados no arquivo .env. com os dados corretos do banco de dados. Exemplo: 
    DB_CONNECTION=mysql  
    DB_HOST=127.0.0.1  
    DB_PORT=3306  
    DB_DATABASE=agenda  
    DB_USERNAME=root  
    DB_PASSWORD=
   

###  Comandos para Migrations, Seeders, Factories e Resources
    Rodar Migrations: php artisan migrate

### Se quiser resetar as tabelas e rodar novamente, use:
    php artisan migrate:fresh

### Rodar Seeders
    php artisan db:seed

### Se você quiser rodar um seeder específico, por exemplo, o seeder de User
    php artisan db:seed --class=UserSeeder

###  Inicie o servidor:
    Se estiver utilizando Laravel Herd:
    O servidor é iniciado automaticamente. A API estará disponível em: http://agenda_api.test
    
    Se estiver utilizando Laragon ou ambiente tradicional:
    Inicie o servidor com:
    php artisan serve
    A API estará disponível em http://127.0.0.1:8000

### Contribuindo
    Se você quiser contribuir para o projeto, sinta-se à vontade para criar uma pull request com suas alterações. Basta fazer um fork do repositório, criar uma branch com sua feature, fazer os commits necessários e abrir uma pull request com uma descrição clara do que foi implementado.