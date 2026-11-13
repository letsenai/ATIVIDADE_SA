# PLANO DE IMPLEMENTAÇÃO - Ferrorama / GoMobi

## 1. Identificação e visão geral
**Equipe GoMobi:** Letícia de Lima Alves, Flávia Faust, Eduardo Tadeu, Bryan Tavares
**Repositório:** https://github.com/letsenai/ATIVIDADE_SA
**Mockup:** [https://canva.link/a19mz1jpvr9b3z8](https://canva.link/j64jjppfi1mdbbo)

Sistema web de monitoramento ferroviário para operadores, técnicos e administradores acompanharem trens, sensores, rotas e alertas em um só lugar.

**Stack:** HTML5, CSS3, JavaScript, PHP 8 (mysqli), MySQL (XAMPP), GitHub.

## 2. Organização de pastas e arquivos
```
ferrorama/
├── index.php              login
├── cadastro.php           cadastro público (cria perfil operador)
├── recuperar.php          recuperação de senha
├── principal.php          dashboard
├── trens.php / trem.php   monitoramento e detalhe do trem
├── sensores.php           sensores
├── rotas.php              rotas
├── alertas.php            notificações e alertas
├── configuracoes.php      perfil e troca de senha
├── logout.php             encerra a sessão
├── usuarios/              index.php (lista) e cadastrar.php (admin)
├── config/conexao.php     única conexão com o MySQL
├── includes/              proteger, permissao, sidebar, cabecalho, layout_inicio/fim, dados_simulados
├── assets/                css/, js/, img/
├── banco/script.sql       criação do banco e carga inicial
└── docs/                  cronograma, kanban e relatório de correções
```
Separar `config`, `includes` e `assets` permite que cada integrante trabalhe numa parte sem conflitos no Git.

## 3. Componentes reutilizáveis
- **Sidebar** (`includes/sidebar.php`): menu filtrado pelo perfil.
- **Cabeçalho** (`includes/cabecalho.php`): usuário logado, perfil e logout.
- **Layout** (`layout_inicio.php` / `layout_fim.php`): estrutura comum de toda tela interna.
- **Proteção de página** (`proteger.php`): exige login e confere o perfil no servidor.
- **Card, botão, input, badge de status, tabela, dropdown de filtro**: classes de `assets/css/style.css`.

## 4. Ordem de desenvolvimento
1. Mockup (Canva) → 2. Plano → 3. Telas em HTML/CSS/JS com dados simulados → 4. Cronograma e Kanban → 5. Conexão, login e cadastro (PHP + MySQL) → 6. Persistência, logout, gestão de trens e sensores → 7. Rotas, leituras e alertas → 8. Relatórios e ajustes → Entrega final.

## 5. Fluxos de navegação
- **Novo usuário:** Login > Sign up > Login > Dashboard.
- **Recuperar senha:** Login > Forgot password > link > nova senha > Login.
- **Operador:** Login > Dashboard > Trens > Detalhe do trem > Alertas.
- **Técnico:** Login > Sensores > filtrar por tipo/status > analisar.
- **Administrador:** Login > Users > Register User (escolhe o perfil).
- **Todos:** menu do usuário > Logout > Login.

## 6. Perfis e permissões
| Página | Administrador | Operador | Técnico |
|---|:-:|:-:|:-:|
| Dashboard, Trens, Alertas, Configurações | ✔ | ✔ | ✔ |
| Sensores | ✔ | — | ✔ |
| Rotas | ✔ | ✔ | — |
| Usuários (listar/cadastrar) | ✔ | — | — |

## 7. Critérios para considerar uma tela pronta
- [ ] HTML semântico e componentes reutilizados
- [ ] Fiel ao mockup, responsiva em 390 px e 1440 px
- [ ] Sem erros no console nem no log do PHP
- [ ] Navegação e proteção por perfil funcionando
- [ ] Dados carregando (simulados ou do banco)
- [ ] Revisada por outro integrante e commit descritivo
