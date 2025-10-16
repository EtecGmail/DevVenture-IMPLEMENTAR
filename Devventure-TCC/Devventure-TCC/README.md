<p align="center">
  <a href="https://laravel.com" target="_blank">
    <img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo">
  </a>
</p>

<p align="center">
  <a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
  <a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
  <a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

# DevVenture

Sistema web desenvolvido com foco em autenticação segura, isolamento de perfis e escalabilidade. A arquitetura é baseada em múltiplos guards para diferentes tipos de usuários (Admin, Professor, Aluno), seguindo boas práticas de engenharia de software e cibersegurança.

## 🧩 Estrutura de Acesso

O sistema oferece fluxos completos e independentes para cada perfil de usuário:

| Perfil     | Cadastro              | Login            | Dashboard            | Logout                |
|------------|------------------------|------------------|----------------------|------------------------|
| Admin      | via Seeder             | `/loginAdm`      | `/admDashboard`      | `/logout-adm`          |
| Professor  | `/cadastrar-prof`      | `/loginProfessor`| `/dashboardProfessor`| `/logout-professor`    |
| Aluno      | `/cadastrar-aluno`     | `/loginAluno`    | `/dashboardAluno`    | `/logout-aluno`        |

- O admin é criado via banco (Seeder), não havendo cadastro via interface.
- Alunos e professores se registram pela interface, mas precisam efetuar login manualmente.
- Cada dashboard e rota são protegidos por autenticação específica via middleware.

## 🔒 Autenticação Multi-guard

O sistema utiliza configuração de múltiplos guards em `config/auth.php`:

- Sessões independentes para cada perfil
- Redirecionamentos exclusivos por tipo de usuário
- Proteção contra colisão de sessão

## 🧭 Navegação Segura

A navegação é adaptativa:

- A navbar identifica o tipo de usuário autenticado e exibe os links apropriados.
- Não há sobreposição de acessos entre perfis.
- Todas as rotas críticas estão protegidas por middleware de autenticação.

## 🧪 Testes Automatizados

O repositório inclui testes de autenticação e proteção de rotas com PHPUnit.

### Teste de acesso do Admin

📄 `tests/Feature/AdminAccessTest.php`

Valida:

- Redirecionamento de visitantes para login
- Bloqueio de acesso a dashboards por perfis indevidos
- Autenticação correta via login

```bash
php artisan test
```

## 🧹 Padrões de Código

Utilize o Pint para manter a padronização:

```bash
./vendor/bin/pint
```

## 🚀 Instalação

### Pré-requisitos

- PHP >= 8.0
- Composer
- MySQL ou PostgreSQL

### Passo a passo

```bash
git clone https://github.com/EtecGmail/EtecGmail-Devventure-TCC.git
cd Devventure-TCC
cd Devventure-TCC
composer install
cp .env.example .env
php artisan key:generate
```

Configure o `.env` com suas credenciais de banco e dados do administrador:

```dotenv
DB_DATABASE=devventure
DB_USERNAME=root
DB_PASSWORD=

ADMIN_EMAIL=devventure@gmail.com
ADMIN_PASSWORD=devteste123
```

Execute as migrações e (opcionalmente) a semente:

```bash
php artisan migrate
php artisan db:seed --class=AdminSeeder
```

Rode o comando para salvar as Images na Storage:
```bash
php artisan storage:link
```

Inicie o servidor:

```bash
php artisan serve
```

Acesse os seguintes endpoints via navegador:

- `/loginAdm`
- `/loginProfessor` e `/cadastrar-prof`
- `/loginAluno` e `/cadastrar-aluno`


## 🧭 Git Flow e Versionamento

O projeto adota estratégia com as branches:

- `main` — Produção
- `develop` — Desenvolvimento contínuo

## 📦 Commits Convencionais

Padrão: [Conventional Commits](https://www.conventionalcommits.org/)

Exemplos:

```bash
feat(auth): implementar login do professor
fix(dashboard): corrigir acesso não autorizado
```


---  

© Todos os direitos reservados à equipe DevVenture.
