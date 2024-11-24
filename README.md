Gestão de Eventos

Esse é o meu projeto de Gestão de Eventos, uma aplicação simples para cadastrar, visualizar, atualizar e excluir eventos. Desenvolvi usando PHP e um banco de dados MySQL.
Funcionalidades

    Cadastro de Eventos: Permite adicionar novos eventos ao sistema.
    Visualização: Exibe todos os eventos cadastrados.
    Edição: Possibilidade de atualizar eventos existentes.
    Exclusão: Permite remover eventos do sistema.

Tecnologias Usadas

    PHP: A linguagem utilizada para desenvolver o backend.
    MySQL: Banco de dados relacional para armazenar os dados dos eventos.
    HTML/CSS: Para estruturar e estilizar as páginas.
    JavaScript: Para algumas interações na página (como confirmação de exclusão).

Requisitos

Para rodar a aplicação localmente, você vai precisar ter o seguinte instalado na sua máquina:

    PHP 7.x ou superior
    Servidor Web (Apache ou Nginx)
    MySQL ou MariaDB (Banco de Dados)
    Git (caso queira clonar o repositório)

Como Rodar Localmente

    Clone o repositório
    Primeiro, clone o repositório com o seguinte comando:

git clone https://github.com/kkemel2418/gestao_eventos.git

Acesse o diretório do projeto

Entre na pasta do projeto:

cd gestao_eventos

Criação do Banco de Dados

Você pode criar o banco de dados executando o script create_db.sql, que está na pasta database. Esse arquivo cria todas as tabelas necessárias para a aplicação.

Como executar via terminal MySQL:

mysql -u root -p < database/create_db.sql

Configuração do Banco de Dados

Após criar o banco de dados, edite o arquivo src/App/config/database.php para configurar a conexão com o seu banco de dados. Ajuste as credenciais conforme seu ambiente.

define('DB_SERVER', 'localhost');
define('DB_USERNAME', 'root'); // Seu usuário do MySQL
define('DB_PASSWORD', 'senha'); // Sua senha do MySQL
define('DB_DATABASE', 'gestao_eventos'); // Nome do banco de dados

Rodando o Projeto Localmente

Se você estiver usando o Apache, basta colocar os arquivos na pasta htdocs do seu servidor local (se estiver usando o XAMPP, por exemplo).

Para rodar com o PHP embutido (sem servidor web), basta usar este comando:

    php -S localhost:8000
    Acesse a aplicação em http://localhost:8000.
    Acessando a Aplicação
    Abra o navegador e vá para http://localhost:8000 para ver a aplicação em funcionamento!

Estrutura do Projeto

Aqui está como o projeto está organizado:

    public/: Contém arquivos acessíveis ao navegador, como o CSS, JS e as páginas PHP principais.
    src/: Aqui está o backend, com os modelos, repositórios e utilitários.
    database/: Contém o arquivo SQL que cria o banco de dados.

