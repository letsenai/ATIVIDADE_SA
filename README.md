# Sistema de Monitoramento Ferroviário (Ferrorama / GoMobi)

## Equipe
Letícia de Lima Alves, Flávia Faust, Eduardo Tadeu e Bryan Tavares - Turma: DS24/M6.
Repositório: https://github.com/letsenai/ATIVIDADE_SA

## O que o sistema faz
O GoMobi ajuda operadores, técnicos e administradores a acompanhar uma frota ferroviária num só lugar. Antes, informações de trens e sensores ficam espalhadas; aqui elas ficam centralizadas e protegidas por login.

Cada pessoa entra com seu perfil e vê só o que pode usar: o administrador gerencia usuários, trens e sensores; o operador gerencia trens e consulta rotas e alertas; o técnico gerencia sensores e consulta trens e alertas. Trens e sensores ficam gravados no banco MySQL e continuam lá depois de fechar o navegador.

## Tecnologias
PHP 8 (mysqli), MySQL/MariaDB, HTML, CSS e JavaScript. Ambiente: XAMPP. Versionamento: Git/GitHub.

## Como instalar
1. Copiar a pasta do projeto para `C:\xampp\htdocs\ferrorama`.
2. Iniciar **Apache** e **MySQL** no painel do XAMPP.
3. Importar `banco/script.sql` pelo phpMyAdmin (aba **SQL**, colar o conteúdo e executar; ou aba **Importar**). O script cria o banco `ferrovias`, as tabelas e os dados de exemplo.
4. Acessar `http://localhost/ferrorama/` (se o Apache usa outra porta, por exemplo 8080: `http://localhost:8080/ferrorama/`).

Se o MySQL do seu computador usa outra porta ou tem senha no `root`, copie `config/local.exemplo.php` para `config/local.php` e ajuste. Esse arquivo é local e não vai para o GitHub.

## Usuários de teste
| Perfil | Email | Senha |
|---|---|---|
| Administrador | admin@gomobi.com | Admin@2026 |
| Operador | operador@gomobi.com | Operador@2026 |
| Técnico | tecnico@gomobi.com | Tecnico@2026 |

## Telas do sistema
| Arquivo | O que faz |
|---|---|
| `index.php` | Login |
| `cadastro.php` | Cadastro público (cria perfil operador) |
| `recuperar.php` | Recuperação de senha |
| `principal.php` | Dashboard com totais e alertas |
| `trens.php` / `trem.php` | Lista, cadastro, edição e exclusão de trens / detalhe do trem e seus sensores |
| `sensores.php` | Lista com filtros, cadastro, edição e exclusão de sensores |
| `rotas.php` | Rotas (dados de exemplo) |
| `alertas.php` | Alertas por nível (dados de exemplo) |
| `usuarios/index.php` e `usuarios/cadastrar.php` | Lista e cadastro de usuários (só administrador) |
| `configuracoes.php` | Perfil e troca de senha |
| `logout.php` | Encerra a sessão |

## Decisões tomadas
- **Senhas com `password_hash` / `password_verify`:** nem quem acessa o banco lê a senha de ninguém.
- **Consultas preparadas (`?`):** o que o usuário digita nunca vira parte do comando SQL.
- **Uma única conexão (`config/conexao.php`):** se algo do banco mudar, muda em um lugar só.
- **Sessão em `$_SESSION` + `includes/proteger.php`:** toda tela interna exige login, conferido no servidor.
- **Perfis com permissões em `includes/permissao.php`:** a regra fica num arquivo só, e o servidor bloqueia mesmo que alguém digite a URL.
- **Token CSRF e padrão POST → redirecionamento:** evita formulários forjados e reenvio ao recarregar.
- **`config/local.php` fora do GitHub:** cada computador usa sua porta e senha sem quebrar quem baixa o projeto.
- **Erros técnicos só no log do PHP:** o usuário vê mensagem em português.

## Limitações conhecidas
- **Rotas e alertas** usam dados de exemplo no código (`includes/dados_simulados.php`), não têm tabela no banco nem cadastro. Ficaram fora por falta de tempo no fechamento.
- **Leituras dos sensores** não foram implementadas: o sensor guarda só o limite (threshold) e o status, sem valores lidos nem alerta automático.
- **Relatórios** não foram implementados.
- **Recuperação de senha** funciona em modo demonstração: sem servidor de e-mail, o link aparece na própria tela. Em produção seria enviado por e-mail.
- **Sem limite de tentativas de login.**
- **Interface em inglês** e exemplos de linhas/estações de Nova York; o texto das mensagens do sistema está em português.
- **Responsividade** prevista no CSS, mas não testada formalmente em todas as telas.

## Uso de inteligência artificial
Veja `docs/USO_DE_IA.md`.

## Documentos do projeto
`PLANO_IMPLEMENTACAO.md`, `docs/ROTEIRO_DE_TESTES.md`, `docs/CRONOGRAMA.md`, `docs/KANBAN.md`, `docs/ESTUDO_VERIFICACAO_ORAL.md` e o `manual-do-usuario.pdf`.
