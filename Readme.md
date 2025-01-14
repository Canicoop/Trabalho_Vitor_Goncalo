# Gonvi

Este projeto consiste no desenvolvimento de uma loja online especializada em artigos desportivos. A aplicação web permite que os utilizadores explorem uma vasta gama de produtos, incluindo roupas, calçados, equipamentos e acessórios para diferentes modalidades, como futebol, corrida, fitness e muito mais.

## 📋 Funcionalidades

### Utilizadores
- **Administrador:**
  - Acesso total a todas as funcionalidades da plataforma.
  - Gerenciamento de produtos, utilizadores e pedidos.
- **Gestor:**
  - Permissões limitadas para gerenciar produtos e visualizar pedidos.
- **Cliente:**
  - Exploração de produtos com possibilidade de filtragem e verificação de tamanhos disponíveis.
  - Adição de produtos ao carrinho de compras.
  - Finalização de compras, com criação automática de encomendas.

### Outras Funcionalidades
- Sistema de autenticação para acesso às funcionalidades de compra.
- Integração com bibliotecas externas como:
  - **PHPMailer:** Para envio de emails.
  - **FPDF:** Para geração de relatórios ou faturas em PDF.

## 🛠️ Tecnologias Utilizadas
- **Backend:** PHP
- **Frontend:** HTML, CSS, JavaScript
- **Bibliotecas:**
  - PHPMailer
  - FPDF

## 🚀 Instalação

1. Clone o repositório para a sua máquina local:
   ```bash
   git clone https://github.com/seu-usuario/gonvi.git
   ```

2. Configure o servidor web (recomendado: Apache como o xampp e também tem que ter ligado a função mysql) e assegure-se de que o PHP está corretamente instalado.

3. Importe o base de dados:
   - Localize o arquivo `database.sql` na raiz do projeto.
   - vá ao phpmyadmin e importe o proejto depois de cirar a base de dados loja_roupa.

4. Configure as variáveis de ambiente no arquivo `conexao.php` para conectar ao base de dados e ajustar outros parâmetros, como credenciais para envio de emails.

5. Inicie o servidor local e acesse o projeto pelo navegador:
   ```
   http://localhost/Trabalho_Vitor_Goncalo
   ```

## 📖 Como Usar

1. Registe-se na plataforma ou faça login com uma conta existente.
2. Explore os produtos disponíveis.
3. Adicione os itens desejados ao carrinho se tiverem stock.
4. Finalize a compra para gerar a encomenda coloque um email verdadeiro para estar correto.


![image](https://github.com/user-attachments/assets/79f4cc74-9ab8-4398-9b70-7459321e318d)


