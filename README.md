# Devventure‑TCC

Um projeto Laravel focado em desenvolvimento web moderno, com stack **PHP 8.1+ / Laravel 9.x / MySQL (MariaDB) / Vite / Node.js**. Este README oferece um guia **profissional e completo** para instalar, rodar, testar, padronizar código e publicar o projeto — com ênfase em **Windows + XAMPP (Apache + MySQL)**.

---

## Sumário
- [Visão geral](#visão-geral)
- [Stack & Requisitos](#stack--requisitos)
- [Estrutura do projeto](#estrutura-do-projeto)
- [Configuração do ambiente](#configuração-do-ambiente)
- [Primeira execução (XAMPP)](#primeira-execução-xampp)
- [Execução em desenvolvimento (artisan serve)](#execução-em-desenvolvimento-artisan-serve)
- [Servindo pelo Apache (VirtualHost no XAMPP)](#servindo-pelo-apache-virtualhost-no-xampp)
- [Banco de dados (migrations & seeders)](#banco-de-dados-migrations--seeders)
- [Compilação de assets (Vite)](#compilação-de-assets-vite)
- [Armazenamento (storage)](#armazenamento-storage)
- [Qualidade de código](#qualidade-de-código)
- [Testes](#testes)
- [Comandos úteis](#comandos-úteis)
- [Solução de problemas (FAQ)](#solução-de-problemas-faq)
- [Segurança](#segurança)
- [Contribuição](#contribuição)
- [Licença](#licença)

---

## Visão geral
O **Devventure‑TCC** é um aplicativo Laravel. As instruções abaixo são genéricas e portáveis entre máquinas. Quando for executar comandos, **sempre navegue até a pasta que contém o arquivo `artisan`** (a raiz do Laravel). Se o seu repositório tiver subpastas, garanta que os comandos são executados **na raiz do app**.

---

## Stack & Requisitos
- **PHP** 8.1+ (recomendado via XAMPP)
- **Laravel** 9.x
- **MySQL/MariaDB** (XAMPP → MySQL)
- **Composer** 2.x
- **Node.js** LTS (18 ou 20) + **npm**
- **Vite** (build de assets)

> Dica: no Windows, prefira usar o PHP do XAMPP para evitar conflitos. Exemplo:
>
> ```cmd
> C:/xampp/php/php.exe -v
> ```

---

## Estrutura do projeto
A estrutura típica do Laravel (parcial):

```
app/
bootstrap/
config/
database/
public/
resources/
routes/
storage/
artisan
composer.json
package.json
vite.config.js
```

---

## Configuração do ambiente
1. Copie o arquivo de ambiente, se necessário:
   ```cmd
   copy .env.example .env
   ```
2. Abra `.env` e ajuste pelo menos:
   ```env
   APP_NAME="Devventure-TCC"
   APP_ENV=local
   APP_DEBUG=true
   APP_URL=http://127.0.0.1:8000

   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=devventure_tcc
   DB_USERNAME=root
   DB_PASSWORD=
   ```
3. Gere a chave da aplicação:
   ```cmd
   php artisan key:generate
   ```

> **Importante (Composer):** se você tem a raiz do projeto dentro de subpastas, evite que o Composer use o `composer.json` do diretório pai. Uma saída é rodar sempre na pasta do `artisan` ou usar `--working-dir`. Se necessário:
>
> ```cmd
> composer config --global use-parent-dir false
> ```

---

## Primeira execução (XAMPP)
1. Abra o **XAMPP Control Panel** e dê **Start** em **Apache** e **MySQL**.
2. Acesse **phpMyAdmin** em `http://localhost/phpmyadmin` e **crie o banco** (ex.: `devventure_tcc`).
3. Instale as dependências **na raiz do app** (onde está o `artisan`):
   ```cmd
   composer install
   ```
4. Execute as **migrações** (e seeds se houver):
   ```cmd
   php artisan migrate
   # php artisan db:seed
   ```
5. Instale dependências do front e rode o Vite:
   ```cmd
   npm install
   npm run dev   # para desenvolvimento
   # ou: npm run build  # para produção/Apache
   ```

---

## Execução em desenvolvimento (artisan serve)
Em um terminal:
```cmd
php artisan serve
```
Acesse: **http://127.0.0.1:8000**.

Se estiver usando `npm run dev`, mantenha o Vite rodando em outro terminal para hot reload.

---

## Servindo pelo Apache (VirtualHost no XAMPP)
1. Edite `C:/xampp/apache/conf/extra/httpd-vhosts.conf` e adicione:
   ```apache
   <VirtualHost *:80>
       ServerName devventure.local
       DocumentRoot "C:/caminho/para/sua/pasta/public"

       <Directory "C:/caminho/para/sua/pasta/public">
           AllowOverride All
           Require all granted
       </Directory>

       ErrorLog "logs/devventure-error.log"
       CustomLog "logs/devventure-access.log" common
   </VirtualHost>
   ```
   > **Atenção:** `DocumentRoot` **deve** apontar para a pasta **`public/`** do Laravel.
2. Edite `C:/Windows/System32/drivers/etc/hosts` (como Administrador) e inclua:
   ```
   127.0.0.1   devventure.local
   ```
3. Reinicie o **Apache** no XAMPP e acesse: **http://devventure.local**.

> Se for servir pelo Apache, prefira `npm run build` para gerar assets otimizados.

---

## Banco de dados (migrations & seeders)
Executar migrações:
```cmd
php artisan migrate
```
Popular dados (seeds), quando existir:
```cmd
php artisan db:seed
```
Recriar do zero (ambiente de dev):
```cmd
php artisan migrate:fresh --seed
```

---

## Compilação de assets (Vite)
- **Desenvolvimento:** `npm run dev` (servidor Vite + hot reload)
- **Produção/Apache:** `npm run build` (gera arquivos otimizados em `public/` conforme config)

Se as views usam `@vite(...)`, certifique‑se de que o Vite está rodando (dev) ou que o build foi feito (prod).

---

## Armazenamento (storage)
Para que uploads (ex.: `storage/app/public`) fiquem acessíveis pela web:
```cmd
php artisan storage:link
```

---

## Qualidade de código
- **Padrão de código (Laravel Pint):**
  ```cmd
  vendor/bin/pint --test   # checa estilo
  vendor/bin/pint          # formata
  ```
  > Opcional: adicione no `composer.json` scripts como `"format": "vendor/bin/pint"`.

- **Conventional Commits** (recomendado): `feat:`, `fix:`, `docs:`, `refactor:`, `test:`, `chore:`…

---

## Testes
- **Testes de aplicação** (PHPUnit / Pest, conforme o projeto):
  ```cmd
  php artisan test
  # ou
  vendor/bin/phpunit
  ```

---

## Comandos úteis
```cmd
# caches
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

# banco
php artisan migrate
php artisan migrate:fresh --seed

# app key
php artisan key:generate

# storage
php artisan storage:link
```

---

## Solução de problemas (FAQ)
**1) Failed opening required '.../vendor/autoload.php'**  
Causa comum: rodar comandos fora da raiz do app ou dependências não instaladas.  
**Solução:**
```cmd
composer install
php artisan key:generate
php artisan config:clear
```

**2) Erro de conexão ao banco (SQLSTATE[HY000] [1045] …)**  
Cheque `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD` no `.env`. No XAMPP, o padrão é usuário `root` sem senha. Garanta o MySQL ligado.

**3) CSS/JS não carregam**  
Rode `npm run dev` (dev) ou `npm run build` (prod). Se necessário, limpe caches:  
`php artisan view:clear && php artisan cache:clear && php artisan config:clear`.

**4) 404 ao servir pelo Apache**  
`DocumentRoot` deve apontar para `public/` e `AllowOverride All` precisa estar ativo para o `.htaccess` do Laravel. Reinicie o Apache após editar o `vhosts`.

**5) Diretórios `vendor/` no lugar errado**  
Se houver subpastas, evite que o Composer use diretórios pai: rode os comandos **na raiz do app** ou use `--working-dir`. Se necessário:  
`composer config --global use-parent-dir false`.

---

## Segurança
Não abra issues públicas para vulnerabilidades. Envie um relato privado para a manutenção do projeto (security policy interna) descrevendo passos de reprodução e impacto.

---

## Contribuição
1. Faça um fork e crie uma *branch* descritiva: `feat/minha-feature`.
2. Garanta testes e formatação (Pint) antes do commit.
3. Use **Conventional Commits**.
4. Abra um Pull Request descrevendo motivação, mudanças e passos de teste.

---

## Licença
Distribuído sob a licença indicada em **LICENSE** na raiz do repositório.

