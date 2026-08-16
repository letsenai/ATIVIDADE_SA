# GoMobi — Sistema de Monitoramento Ferroviário

## Seção 1 — Identificação e visão geral

### Equipe GoMobi

**Integrantes:**

* Letícia de Lima Alves
* Flávia Faust
* Eduardo Tadeu
* Bryan Tavares

### Mockup

**Mobile:**
https://canva.link/a19mz1jpvr9b3z8

**Desktop:**
https://canva.link/r20ptvlegvo51mw

### Resumo do Sistema

O **GoMobi** é um sistema inteligente de monitoramento ferroviário desenvolvido para auxiliar operadores, técnicos e administradores no acompanhamento da frota ferroviária em tempo real.

A plataforma permite visualizar trens, sensores, alertas e indicadores operacionais por meio de uma interface moderna e intuitiva. Seu principal diferencial é a centralização das informações, facilitando a tomada de decisões rápidas e contribuindo para a segurança e eficiência das operações ferroviárias.

### Stack Tecnológica

* **JavaScript** 
* **HTML5** 
* **CSS3** 
* **GitHub** 

---

# Seção 2 — Arquitetura de arquivos e pastas

## Estrutura do Projeto

```text
ATIVIDADE_SA/
│
├── gomobi-html/
│   ├── src/
│   │   ├── components/
│   │   ├── pages/
│   │   ├── data/
│   │   ├── assets/
│   │   └── App.jsx
│   │
│   ├── public/
│   ├── package.json
│   └── ...
│
└── README.md
```

> **Observação:** a estrutura acima representa a organização planejada para o desenvolvimento do sistema. Os diretórios e arquivos serão ajustados conforme a implementação do projeto evoluir.

## Justificativa da Organização

A organização do projeto busca facilitar a manutenção, reutilização e evolução do sistema.

Os **componentes** serão responsáveis por elementos reutilizáveis da interface, enquanto as **páginas** representarão as principais telas do sistema. Os **dados** ficarão separados da interface para facilitar sua manipulação e futura substituição por uma API ou banco de dados.

A separação dos **assets** também facilita o gerenciamento de imagens, ícones e outros recursos utilizados na aplicação.

Essa organização permite que diferentes integrantes da equipe trabalhem simultaneamente em partes distintas do projeto, reduzindo conflitos e facilitando o versionamento por meio do Git.

---

# Seção 3 — Componentes reutilizáveis identificados

Durante o desenvolvimento do GoMobi, foram identificados componentes que podem ser reutilizados em diferentes telas da aplicação.

### Navbar / Header

Responsável pela identificação da página e acesso a funcionalidades principais.

### Sidebar

Menu lateral utilizado para facilitar a navegação entre as diferentes áreas do sistema.

### Card

Componente utilizado para apresentar informações de forma organizada, como indicadores, trens, sensores e alertas.

### Botão

Componente padronizado para ações como cadastrar, acessar, editar, filtrar e sair do sistema.

### Input

Utilizado em formulários de login, cadastro, filtros e edição de informações.

### Badge de Status

Utilizado para representar visualmente situações como:

* Ativo
* Inativo
* Normal
* Atenção
* Crítico

### Alertas

Componente utilizado para destacar ocorrências e situações que necessitam de atenção do operador.

### Tabela

Utilizada para apresentar informações estruturadas, principalmente em consultas de sensores e demais registros do sistema.

### Modal

Componente destinado à exibição de informações adicionais ou confirmação de determinadas ações sem a necessidade de trocar de página.

---

# Seção 4 — Ordem de implementação

A implementação do GoMobi será realizada seguindo uma ordem que permita construir primeiro a estrutura principal e, posteriormente, adicionar as funcionalidades específicas.

1. **Configuração inicial do projeto**

   * Organização das pastas.
   * Configuração do CSS.
   * Configuração do js.

2. **Estrutura visual**

   * Definição das cores.
   * Tipografia.
   * Espaçamentos.
   * Responsividade.

3. **Componentes reutilizáveis**

   * Sidebar.
   * Header.
   * Botões.
   * Cards.
   * Inputs.
   * Badges.
   * Tabelas.
   * Modais.

4. **Tela de Login**

   * Formulário de acesso.
   * Validação dos campos.
   * Navegação para cadastro.

5. **Tela de Cadastro**

   * Formulário de criação de conta.
   * Validação das informações.
   * Retorno para o Login.

6. **Dashboard**

   * Indicadores gerais.
   * Informações da frota.
   * Alertas.
   * Status dos sensores.

7. **Monitoramento de Trens**

   * Lista de trens.
   * Detalhes do trem selecionado.
   * Status dos sensores.
   * Informações operacionais.

8. **Gerenciamento de Sensores**

   * Listagem.
   * Filtros.
   * Status.
   * Atualização das informações.

9. **Sistema de alertas**

   * Identificação de alertas.
   * Classificação por nível de criticidade.
   * Visualização dos detalhes.

10. **Navegação**

    * Configuração das rotas.
    * Navegação entre telas.
    * Encerramento da sessão.

11. **Responsividade**

    * Ajustes para resolução mobile de 390px.
    * Ajustes para desktop de 1440px.

12. **Testes e revisão**

    * Testes de navegação.
    * Testes dos componentes.
    * Verificação do console.
    * Revisão visual com o mockup.
    * Revisão por outro integrante.

13. **Versionamento**

    * Commits descritivos.
    * Revisão do código.
    * Envio da versão final para o GitHub.

---

# Seção 5 — Fluxos de navegação do usuário

## Fluxo 1 — Cadastrar-se e acessar o sistema

**Perfil:** Novo usuário

1. Tela de Login
2. Clicar em "Criar Conta"
3. Tela de Cadastro
4. Preencher informações
5. Clicar em "Cadastrar"
6. Retornar para Login
7. Fazer Login
8. Acessar Dashboard

---

## Fluxo 2 — Operador monitora um trem

**Perfil:** Operador

1. Login
2. Dashboard
3. Selecionar um trem
4. Abrir tela de Monitoramento
5. Visualizar status dos sensores
6. Consultar detalhes do trem

---

## Fluxo 3 — Operador verifica alerta crítico

**Perfil:** Operador

1. Login
2. Dashboard
3. Identificar alerta crítico
4. Abrir detalhes do trem afetado
5. Verificar sensor responsável
6. Tomar ação corretiva

---

## Fluxo 4 — Técnico consulta sensores

**Perfil:** Técnico

1. Login
2. Menu Sensores
3. Visualizar sensores cadastrados
4. Filtrar por status
5. Analisar informações
6. Atualizar dados do sensor

---

## Fluxo 5 — Encerrar sessão

**Perfil:** Todos os usuários

1. Acessar menu lateral
2. Clicar em "Sair"
3. Sistema encerra sessão
4. Retorna para Login

---

# Seção 6 — Critérios de "Pronto"

Uma tela será considerada finalizada quando atender todos os critérios abaixo:

* [ ] HTML semântico utilizado corretamente.
* [ ] Componentes reutilizáveis utilizados quando aplicável.
* [ ] Layout fiel ao mockup aprovado.
* [ ] Responsivo para mobile (390px).
* [ ] Responsivo para desktop (1440px).
* [ ] Nenhum erro exibido no console.
* [ ] Navegação funcionando corretamente.
* [ ] Dados carregando corretamente.
* [ ] Padrão visual consistente em toda a aplicação.
* [ ] Código comentado quando necessário.
* [ ] Revisado por outro integrante da equipe.
* [ ] Commit realizado com mensagem descritiva.
* [ ] Testado antes do envio ao repositório.

---

## Repositório

O código-fonte do projeto está disponível no GitHub:

https://github.com/letsenai/ATIVIDADE_SA

## Equipe

**GoMobi — Sistema de Monitoramento Ferroviário**

Desenvolvido por:

* Letícia de Lima Alves
* Flávia Faust
* Eduardo Tadeu
* Bryan Tavares
