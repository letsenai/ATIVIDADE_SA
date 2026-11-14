# PLANO DE IMPLEMENTAÇÃO — Ferrorama / GoMobi

> Versão do fechamento (13/11/2026). Descreve o sistema **como ele está entregue**, com o que foi feito e o que ficou como limitação conhecida.

## 1. Identificação e visão geral
**Equipe GoMobi:** Letícia de Lima Alves, Flávia Faust, Eduardo Tadeu, Bryan Tavares
**Unidade Curricular:** Desenvolvimento de Sistemas — Escola SESI de Referência Joinville Moinho
**Repositório:** https://github.com/letsenai/ATIVIDADE_SA
**Mockup mobile:** https://canva.link/a19mz1jpvr9b3z8
**Mockup desktop:** https://canva.link/r20ptvlegvo51mw

**Problema:** acompanhar trens e sensores de uma ferrovia exige juntar informações espalhadas. O GoMobi é um sistema web de monitoramento ferroviário em que operadores, técnicos e administradores entram com login e acompanham trens, sensores, rotas e alertas em um só lugar, cada um com as permissões do seu perfil.

**Stack:** HTML5, CSS3, JavaScript, PHP 8 (`mysqli`), MySQL (XAMPP), Git/GitHub.

## 2. Organização de pastas e arquivos
```
ferrorama/
├── index.php              login
├── cadastro.php           cadastro público (cria sempre o perfil operador)
├── recuperar.php          recuperação de senha (link com validade de 30 min)
├── principal.php          dashboard
├── trens.php / trem.php   lista com cadastro/edição/exclusão e detalhe do trem
├── sensores.php           sensores (cadastro, edição, exclusão, filtros)
├── rotas.php              rotas (dados de exemplo)
├── alertas.php            alertas por severidade (dados de exemplo)
├── configuracoes.php      troca de senha
├── logout.php             encerra a sessão
├── usuarios/              index.php (lista) e cadastrar.php (admin)
├── config/conexao.php     única conexão com o MySQL
├── includes/              proteger, permissao, sidebar, cabecalho, layout_inicio/fim, dados_simulados
├── assets/                css/, js/ (só common.js é usado), img/
├── banco/script.sql       criação do banco e dados de exemplo
├── docs/                  cronograma, kanban, relatório de correções e roteiro de testes
├── README.md              instalação e visão técnica
└── manual-do-usuario.pdf  manual para quem usa o sistema
```
Separar `config`, `includes` e `assets` permite que cada integrante trabalhe numa parte sem conflitos no Git. A estrutura foi congelada no fechamento: nada foi renomeado.

## 3. Banco de dados (`banco/script.sql`, banco `ferrovias`)
| Tabela | Campos principais | Observações |
|---|---|---|
| `usuario` | id, nome, login (único), senha (hash), papel | `papel` é `ENUM` (administrador, operador, tecnico); senha guardada com `password_hash` |
| `recuperacao_senha` | id, usuario_id, token_hash, expira_em, usado | só o hash do token é guardado; `ON DELETE CASCADE` com o usuário |
| `trem` | id, codigo (único), linha, status, proxima_estacao, velocidade | `status` é `ENUM` (Online, Warning, Offline) |
| `sensor` | id, codigo (único), nome, tipo, localizacao, threshold, status, trem_id | `trem_id` é chave estrangeira para `trem`, com `ON DELETE SET NULL` |

Relação: **um trem tem vários sensores** (`sensor.trem_id` → `trem.id`). Não existem tabelas para rotas, leituras e alertas (ver seção 8).

## 4. Componentes reutilizáveis
- **Conexão** (`config/conexao.php`): único arquivo que conhece servidor, usuário, senha e banco; mostra mensagem clara se a conexão falhar.
- **Proteção de página** (`includes/proteger.php`): exige login, confere o perfil no servidor (erro 403), envia `Cache-Control: no-store`, e oferece CSRF (`csrf_campo`, `csrf_ok`), mensagens que sobrevivem ao redirecionamento (`flash`) e a função de escape `h()`.
- **Permissões** (`includes/permissao.php`): papéis, quem abre cada página (`ACESSO`) e quem altera trens e sensores (`EDICAO`).
- **Layout** (`layout_inicio.php` / `layout_fim.php`): estrutura comum de toda tela interna.
- **Sidebar** (`includes/sidebar.php`) e **cabeçalho** (`includes/cabecalho.php`): menu filtrado pelo perfil; nome, email e perfil de quem está logado, com Logout.
- **Estilos** (`assets/css/style.css`): card, botão, campo, badge de status, tabela, filtros.

## 5. Ordem de desenvolvimento
| Etapa | Situação |
|---|---|
| 1. Mockup (Canva) | Concluída |
| 2. Plano de implementação | Concluída |
| 3. Telas em HTML/CSS/JS com dados simulados | Concluída |
| 4. Cronograma e Kanban | Concluída |
| 5. Conexão, login e cadastro (PHP + MySQL) | Concluída |
| 6. Persistência, logout, gestão de trens e sensores | Concluída |
| 7. Rotas, leituras e alertas no banco | **Não implementada** — telas existem com dados de exemplo |
| 8. Relatórios e ajustes | Relatórios **não implementados**; ajustes feitos no fechamento |
| Fechamento: README, manual, roteiro de testes | Concluída (testes 1 e 11 a confirmar no XAMPP) |

## 6. Fluxos de navegação
- **Novo usuário:** Login → Sign up → Login → Dashboard.
- **Recuperar senha:** Login → Forgot password → link na tela → nova senha → Login.
- **Operador:** Login → Dashboard → Trens → cadastrar/editar/excluir ou ver detalhe do trem → Alertas.
- **Técnico:** Login → Sensores → filtrar por nome, tipo ou status → cadastrar/editar sensor.
- **Administrador:** Login → Users → Register User (escolhe o perfil).
- **Todos:** ícone do usuário → Logout → Login.

## 7. Perfis e permissões
| Página | Administrador | Operador | Técnico |
|---|:-:|:-:|:-:|
| Dashboard, Alertas, Configurações | ✔ | ✔ | ✔ |
| Trens (consultar) | ✔ | ✔ | ✔ |
| Trens (cadastrar, editar, excluir) | ✔ | ✔ | — |
| Sensores (consultar e alterar) | ✔ | — | ✔ |
| Rotas | ✔ | ✔ | — |
| Usuários (listar e cadastrar) | ✔ | — | — |

O bloqueio é feito no servidor (erro 403); o menu apenas esconde o que o perfil não pode abrir.

## 8. Escopo final e limitações conhecidas
**Entregue:** login, cadastro, recuperação e troca de senha, perfis, dashboard, CRUD de trens, CRUD e filtros de sensores, detalhe do trem, lista de usuários e cadastro por administrador.
**Não entregue (por falta de tempo, registrado no README):** rotas, leituras e alertas no banco; painel de relatórios; limite de tentativas de login; interface em português; links de Rotas/Alertas para o detalhe do trem.

## 9. Critérios para considerar uma tela pronta
- [x] HTML semântico e componentes reutilizados
- [x] Responsiva em 390 px e 1440 px (conferido na tela de trens)
- [x] Navegação e proteção por perfil funcionando
- [x] Dados carregando do banco (exceto rotas e alertas, simulados)
- [ ] Sem erros no log do PHP no XAMPP (a equipe confirma ao rodar o roteiro)
- [ ] Revisada por outro integrante e commit descritivo (a equipe confirma)
