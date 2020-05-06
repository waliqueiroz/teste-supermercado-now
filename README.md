# Teste Supermercado Now
A aplicação consiste em uma ferramenta para gestão de um catálogo de produtos.
O backend foi desenvolvido em **[Laravel](https://laravel.com/) ([PHP](https://www.php.net/))** [REST](https://www.w3.org/2001/sw/wiki/REST) e frontend em **[Vue.js](https://vuejs.org/)** com **[Bootstrap 4](https://getbootstrap.com/docs/4.0/getting-started/introduction/)**. A autenticação é feita via OAuth2.

## O desafio proposto

Desenvolva uma aplicação web com as seguintes características
- [x]  A aplicação consiste em uma ferramenta para gestão de um catálogo de produtos, com
as seguintes funcionalidades:
  - [x] Visualização em lista de todos os produtos cadastrados;
  - [x] Adição de novo produto, com possibilidade de upload de imagem do produto;
  - [x] Edição de produto existente;
  - [x] Fluxo simples de aprovação: Status e mudanças de status (pendente, em análise,
      aprovado, reprovado);
  - [x] Controle de acesso, tendo o processo de login e todas as
      chamadas de backend autenticadas. Além disso, é desejável ter dois níveis de permissão:
    - [x] usuário padrão: pode &quot;Enviar para análise&quot;, ou seja, alterar de pendente para em análise;
    - [x] usuário admin: pode tomar as ações de &quot;Aprovar&quot; e &quot;Reprovar&quot;;

## Estrutura do projeto entregue neste repositório
**[Docker compose](https://docs.docker.com/compose/)** contendo:
- servidor [nginx](https://www.nginx.com/) rodando nas portas **8000 (API)** e **8080 (frontend)**.
- [php-fpm](https://www.php.net/manual/en/install.fpm.php) v. `7.4` na porta **9000**.
- [postgreSQL](https://www.postgresql.org/) na porta **5432**. Caso necessite alterar a porta ou configurações do banco de dados, altere [aqui](https://github.com/waliqueiroz/teste-supermercado-now/blob/master/.env) e [aqui](https://github.com/waliqueiroz/teste-supermercado-now/blob/master/htdocs/teste-supermercado-now-api/.env).

### Diretórios do projeto
- **[htdocs](https://github.com/waliqueiroz/teste-supermercado-now/tree/master/htdocs)**: arquivos do projeto.
  - **[teste-supermercado-now-api](https://github.com/waliqueiroz/teste-supermercado-now/tree/master/htdocs/teste-supermercado-now-api)**: projeto **Laravel (PHP)**.
  - **[teste-supermercado-now-web](https://github.com/waliqueiroz/teste-supermercado-now/tree/master/htdocs/teste-supermercado-now-web)** projeto **Vue.js**.
- **[nginx](https://github.com/waliqueiroz/teste-supermercado-now/tree/master/nginx)**: arquivos de configurações do **nginx**.
- **[php-fpm](https://github.com/waliqueiroz/teste-supermercado-now/tree/master/php-fpm)**: arquivos de configurações e build da imagem do **PHP-FPM**.

## Como executar o projeto?

### Pré-requisitos para testes
* git
* docker
* docker-compose
* npm (para gerar build do Vue.js **se necessário**)

Obs: O repositório já contém uma build gerada do projeto Vue.js e um `.env`para o Laravel.

Obs 2: Os arquivos `.env` possuem apenas dados genéricos pertinentes a este projeto e foram mantidos apenas para facilitar o teste.

### Executando o projeto
Clone o projeto:
```
git clone https://github.com/waliqueiroz/teste-supermercado-now.git
```

Vá até a raiz do diretório clonado:
```
cd teste-supermercado-now
```

Prepare os contêineres do docker:
```
docker-compose build
```

Execute os contêineres do docker:
```
docker-compose up -d

```
Entre no contêiner do PHP:
```
docker exec -it php-fpm bash
```
Obs: O comando anterior te levará direto para o WORKDIR do contêiner: /var/www/html.

Dentro do contêiner, vá até o diretório do projeto Laravel:
```
cd teste-supermercado-now-api
```

Instale as dependências:
```
composer install
```

Realize a migração do banco de dados e execute os seeds para criação dos usuários para testes e configurações necessárias:
```
php artisan migrate --seed
```

Gere os clientes e as chaves de criptografia para a autenticação via token:
```
php artisan passport:install
```

Crie um link simbólico entre as pastas `storage/public` e `public/storage` do laravel:

```
php artisan storage:link
```

Feito isso, o projeto estará pronto para uso.

### Para testar utilize
* API Laravel: [http://localhost:8000](http://localhost:8000/)
* Frontend Vue.js: [http://localhost:8080](http://localhost:8080/)

O frontend e a API se comunicam via JSON.

#### Usuário administrador para autenticação

- E-mail: admin@mail.com
- Senha: 123456

#### Usuário padrão para autenticação

- E-mail: user@mail.com
- Senha: 123456

## Sobre o autor
> [Wali Queiroz](https://www.linkedin.com/in/waliqueiroz/)
> 
> [@waliqueiroz](https://github.com/waliqueiroz)
> 
> [wsantos077@gmail.com](mailto:wsantos077@gmail.com)

