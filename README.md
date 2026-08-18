# 💻 Blog & Portfólio Tech com Painel CMS (PHP + MySQL)

Um sistema completo, dinâmico e modular de **Blog & Portfólio Pessoal/Profissional**, desenvolvido com **PHP**, **MySQL**, **HTML5**, **JavaScript** e **Tailwind CSS**. O projeto conta com um painel administrativo (CMS) completo para gestão de projetos, postagens de blog/laboratório, habilidades, experiências e mensagens recebidas.

---

## 📌 Índice
1. [Sobre o Projeto](#-sobre-o-projeto)
2. [Linguagens e Tecnologias Utilizadas](#-linguagens-e-tecnologias-utilizadas)
3. [Entendendo a Linguagem PHP](#-entendendo-a-linguagem-php)
4. [Arquitetura & Reutilização de Código](#-arquitetura--reutilização-de-código)
5. [Estrutura do Projeto](#-estrutura-do-projeto)
6. [Como Configurar e Executar (Passo a Passo)](#-como-configurar-e-executar-passo-a-passo)
7. [Como Reutilizar o Projeto para Outros Fins](#-como-reutilizar-o-projeto-para-outros-fins)
8. [Painel Administrativo (CMS)](#-painel-administrativo-cms)

---

## 🚀 Sobre o Projeto

O **Blog & Portfólio Tech** foi projetado para servir como um centro de presença online completo para desenvolvedores e profissionais de tecnologia. Ele permite exibir:
- **Perfil Profissional**: Apresentação, links sociais, resumo de trajetória e áreas de atuação.
- **Hard Skills & Soft Skills**: Habilidades organizadas com níveis de proficiência.
- **Portfólio de Projetos**: Vitrine de projetos com descrições, tags de tecnologias, links para demonstração e repositórios.
- **Formação & Experiência**: Cronologia acadêmica e histórico profissional.
- **Laboratório & Hobbies**: Área dedicada a artigos, experimentos e projetos pessoais.
- **Formulário de Contato**: Envio de mensagens diretamente salvas no banco de dados para gestão via CMS.
- **Painel CMS Administrativo**: Área restrita com login seguro para cadastro, edição e exclusão de todos os dados do site.

---

## 🛠️ Linguagens e Tecnologias Utilizadas

| Tecnologia | Função / Aplicação no Projeto |
| :--- | :--- |
| **PHP 8.x** | Linguagem principal do lado do servidor (Server-Side scripting). Gerencia rotas, banco de dados, autenticação e renderização modular de páginas. |
| **MySQL (PDO)** | Banco de dados relacional para persistência de dados. O driver PDO garante consultas seguras através de *prepared statements*. |
| **HTML5** | Marcação estrutural e semântica de todo o conteúdo web. |
| **JavaScript (ES6+)** | Lógica no lado do cliente (Client-Side), interatividade de menus, trocas dinâmicas e validação de formulários. |
| **Tailwind CSS** | Framework CSS utilitário para design moderno, gradientes, responsividade e layout com Flexbox/Grid. |
| **FontAwesome** | Biblioteca de ícones vetoriais integrada para interfaces visuais. |

---

## 💡 Entendendo a Linguagem PHP

### O que é o PHP?
**PHP** (*PHP: Hypertext Preprocessor*) é uma linguagem de programação open-source executada no **servidor** (Server-Side). Ao contrário do JavaScript executado no navegador do usuário, o código PHP roda no servidor web (como Apache ou Nginx) e entrega ao navegador apenas o resultado processado em HTML puro, CSS e JS.

### Por que o PHP foi escolhido para este projeto?
1. **Dinamicismo**: Permite buscar dados do MySQL e gerar a página HTML dinamicamente de acordo com o conteúdo cadastrado no painel admin.
2. **Modularidade e Reutilização**: Com comandos simples como `include` e `require_once`, partes repetitivas da interface (cabeçalho, menu lateral, rodapé) são escritas uma única vez e reaproveitadas em todas as páginas.
3. **Segurança com PDO**: Utiliza a extensão PDO (*PHP Data Objects*) para conectar ao MySQL de forma segura, prevenindo ataques do tipo *SQL Injection*.
4. **Alta Compatibilidade**: Roda em praticamente qualquer servidor de hospedagem (XAMPP localmente, Hostinger, cPanel, AWS, etc.).

---

## 🧩 Arquitetura & Reutilização de Código

Uma das maiores vantagens deste projeto é a **arquitetura limpa e modular baseada em componentes PHP**.

### Como funciona a reutilização de componentes?

Em vez de repetir o código do menu lateral ou do rodapé em cada página do site, o projeto centraliza essas estruturas na pasta `includes/`:

```
includes/
├── head.php              # Cabeçalho HTML, metatags, fontes e estilos gerais
├── sidebar.php           # Menu lateral de navegação dinâmico
├── header_profile.php    # Seção principal "Sobre Mim" e apresentação
├── about_detailed.php    # Bloco de Hard Skills, Formação e Jornada
├── portfolio_section.php # Vitrine dinâmica de projetos
├── contact_section.php   # Formulário de contato dinâmico
└── footer.php            # Rodapé global e scripts JavaScript
```

#### Exemplo prático de criação de uma nova página:
Para criar uma nova página chamada `minha-pagina.php`, basta reutilizar a estrutura modular da seguinte forma:

```php
<?php 
require_once 'config/db.php'; // 1. Conecta ao Banco de Dados

$pageTitle = "Minha Nova Página";
include 'includes/head.php';     // 2. Inclui <head> e CSS
include 'includes/sidebar.php';  // 3. Inclui Menu Lateral
?>

<div id="main-content" class="flex-grow p-6">
    <h1 class="text-3xl font-bold">Conteúdo da Minha Página</h1>
    <p>Aqui você pode inserir qualquer conteúdo dinâmico ou estático!</p>
</div>

<?php 
include 'includes/footer.php';   // 4. Inclui Rodapé e fechamento das tags
?>
```

---

## 📂 Estrutura do Projeto

```
Blog_Portifolio/
├── admin/                     # Painel de Controle Administrativo (CMS)
│   ├── index.php              # Dashboard principal do admin
│   ├── login.php              # Autenticação de usuário
│   ├── logout.php             # Encerramento de sessão
│   ├── projects.php           # Gestão de Projetos
│   ├── posts.php              # Gestão de Artigos/Laboratório
│   ├── skills.php             # Gestão de Habilidades
│   ├── profile.php            # Gestão de Perfil e Dados Pessoais
│   ├── messages.php           # Gestão de Mensagens Recebidas
│   └── ...
├── config/
│   └── db.php                 # Conexão PDO centralizada com o banco de dados
├── includes/                  # Componentes reutilizáveis do front-end
├── pages/                     # Páginas auxiliares
├── src/                       # Fontes e recursos
├── assets/                    # Imagens, CSS customizado e JavaScripts
├── index.php                  # Página principal do Portfólio
├── laboratorio.php            # Página do Laboratório / Hobbies / Artigos
├── post.php                   # Leitura de postagens individuais (dinâmico via slug)
├── send_message.php           # Processador de mensagens via AJAX/POST
└── blog_portfolio_export.sql  # Script de banco de dados SQL pronto para importar
```

---

## ⚙️ Como Configurar e Executar (Passo a Passo)

### Requisitos Prévios
- **XAMPP**, **WAMP** ou **MAMP** (com PHP 8.0+ e MySQL/MariaDB ativados).
- Navegador Web atualizado.

### 1. Clonar ou copiar o projeto
Coloque a pasta `Blog_Portifolio` no diretório raiz do seu servidor local:
- No XAMPP: `C:\xampp\htdocs\Blog_Portifolio`

### 2. Importar o Banco de Dados
1. Abra o phpMyAdmin em seu navegador: `http://localhost/phpmyadmin`
2. Crie um novo banco de dados com o nome: `blog_portfolio` (Collation: `utf8mb4_unicode_ci`).
3. Clique na aba **Importar**.
4. Selecione o arquivo `blog_portfolio_export.sql` localizado na raiz do projeto e clique em **Executar**.

### 3. Configurar a Conexão no PHP
Abra o arquivo `config/db.php` e verifique as credenciais locais:

```php
$host    = 'localhost';
$db      = 'blog_portfolio';
$user    = 'root';
$pass    = ''; // No XAMPP a senha padrão é vazia
```

### 4. Acessar o Projeto
- **Front-end (Portfólio)**: [http://localhost/Blog_Portifolio/](http://localhost/Blog_Portifolio/)
- **Painel Administrativo**: [http://localhost/Blog_Portifolio/admin/](http://localhost/Blog_Portifolio/admin/)

---

## 🔄 Como Reutilizar o Projeto para Outros Fins

Você pode adaptar este projeto para qualquer outro tipo de site (site institucional, página de vendas, blog de tecnologia ou portfólio para outros profissionais):

1. **Alterar Identidade Visual e Temas**:
   - As cores globais podem ser alteradas no banco de dados (`settings.theme_color`) ou diretamente nos arquivos de `includes/`.
2. **Reaproveitar o Sistema CMS (`admin/`)**:
   - O painel admin em `admin/` é totalmente genérico e modular. Você pode reutilizá-lo para gerenciar qualquer outro banco MySQL ajustando apenas as queries SQL nas páginas de gestão.
3. **Reaproveitar os Blocos `includes/`**:
   - Copie a pasta `includes/` e a pasta `config/` para um novo projeto em PHP para reutilizar o layout com barra lateral, tema responsivo e estrutura base do sistema.
4. **Adicionar Novas Tabelas e Módulos**:
   - Para adicionar uma nova seção (ex: "Serviços Prestados"), crie uma tabela no MySQL, adicione o formulário em `admin/` e crie um arquivo de inclusão em `includes/services.php`.

---

## 🔐 Painel Administrativo (CMS)

O painel administrativo permite gerenciar todo o conteúdo em tempo real sem tocar em código:
- **Painel de Mensagens**: Visualize e responda mensagens de contato enviadas por visitantes.
- **Gerenciador de Habilidades**: Adicione hard/soft skills com ícones e barras de progresso.
- **Gerenciador de Projetos**: Cadastre projetos do GitHub, screenshots, tags e descrições detalhadas.
- **Gerenciador de Posts (Laboratório)**: Escreva tutoriais, artigos e notas técnicas.
- **Personalização Global**: Altere URLs sociais, título do site, bio e tema visual.

---

## 📄 Licença e Uso

Este projeto foi desenvolvido como uma solução modular e expansível para portfólio profissional e blog técnico. Fique à vontade para clonar, modificar, adaptar e reutilizar em seus projetos pessoais ou comerciais! 🚀
