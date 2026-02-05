# 🔒 PassShare

O **PassShare** é uma aplicação segura para compartilhamento de senhas e arquivos sensíveis.  
O sistema garante que a informação seja acessada apenas uma vez (ou conforme configurado) e depois se autodestrua.

![Status](https://img.shields.io/badge/Status-WIP-orange)
![Laravel](https://img.shields.io/badge/Laravel-10.x-red)
![PHP](https://img.shields.io/badge/PHP-8.2+-blue)

## 🚀 Funcionalidades

- **Criptografia Ponta-a-Ponta:** Todos os dados são criptografados antes de serem salvos no banco.
- **Autodestruição:**
  - Por visualização (ex: "Queimar após 1 leitura").
  - Por tempo (ex: "Expirar em 1 hora").
- **Upload Seguro:** Compartilhamento de arquivos (PDF, ZIP, Imagens) com anotações de texto.
- **Identificação:** Opção de informar o remetente.
- **Interface Moderna:** UI limpa e responsiva (Dark Mode) inspirada em ferramentas corporativas.
- **Segurança:** Bloqueio de múltiplos downloads simultâneos e limpeza automática de arquivos físicos.

## 🛠️ Tecnologias Utilizadas

- **Backend:** Laravel Framework
- **Frontend:** Blade Templates + Tailwind CSS (via CDN)
- **Banco de Dados:** MySQL
- **Segurança:** Laravel Encryption (OpenSSL AES-256-CBC)

## 📦 Como rodar este projeto

1. **Clone o repositório**

   ```bash
   git clone https://github.com/vitor-assis/PassShare.git
   cd PassShare
   ```
2. **Instale as dependências**
   ```bash
   composer install
   ```
3. **Configure o ambiente**
   ```bash
    cp .env.example .env
    php artisan key:generate
   ```
   Configure as credenciais do seu banco de dados no arquivo .env.
4. **Prepare o banco de dados**
   ```bash
    php artisan migrate
   ```
5. **Inicie o servidor**
   ```bash
    php artisan serve
   ```
   Acesse: http://localhost:8000

## 📚 Sobre o Projeto 
Desenvolvido como projeto de estudo de segurança e arquitetura Laravel.