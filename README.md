# Sistema de Monitoramento Ferroviario

## Equipe
Letícia de Lima Alves, Flávia Faust, Eduardo Tadeu e Bryan Tavares - Equipe **GoMobi**.
Turma: DS24/M6 · Unidade Curricular: Desenvolvimento de Sistemas · Escola SESI de Referência Joinville Moinho.
Repositório: https://github.com/letsenai/ATIVIDADE_SA

## O que o sistema faz
O GoMobi (pasta `ferrorama`) é um sistema web para acompanhar a frota de trens e os sensores instalados neles. Antes, essas informações ficariam espalhadas; aqui, operadores, técnicos e administradores entram com login e senha e veem tudo em um só lugar.

Depois de entrar, cada pessoa vê apenas o que o seu perfil permite. É possível cadastrar, editar, excluir e consultar **trens** e **sensores** (cada sensor pertence a um trem), filtrar sensores por nome, tipo e status, e ver o detalhe de um trem com seus sensores. O administrador também cadastra usuários. Quem usa o sistema encontra o passo a passo no **Manual do Usuário** (`manual-do-usuario.pdf`).

As telas de **rotas** e **alertas**, e o contador de alertas do dashboard, ainda usam dados de exemplo escritos no código (veja _Limitações conhecidas_).

## Tecnologias
PHP 8 (extensão `mysqli`), MySQL, HTML5, CSS3 e JavaScript. Ambiente: XAMPP (Apache + MySQL). Versionamento: Git/GitHub.

## Como instalar
1. Copiar a pasta `ferrorama` para `C:\xampp\htdocs\`.
2. Iniciar **Apache** e **MySQL** no painel do XAMPP.
3. No phpMyAdmin (`http://localhost/phpmyadmin`), aba **SQL**, colar o conteúdo de `banco/script.sql` e clicar em **Executar** (o script cria o banco `ferrovias`, as tabelas e os dados de exemplo; pode ser executado mais de uma vez).
4. Acessar `http://localhost/ferrorama/`.

Se o MySQL tiver senha no `root` ou rodar em outra porta, altere só o arquivo `config/conexao.php` (o padrão é `localhost`, usuário `root`, sem senha, porta **3306**). Se o banco estiver fora do ar, o sistema mostra uma mensagem explicando o que conferir.

## Usuarios de teste
| Perfil | Email | Senha |
|---|---|---|
| Administrador | admin@gomobi.com | Admin@2026 |
| Operador | operador@gomobi.com | Operador@2026 |
| Técnico | tecnico@gomobi.com | Tecnico@2026 |

No banco, as senhas ficam guardadas como hash (`password_hash`), nunca em texto.

## Telas do sistema
| Arquivo | O que faz | Quem acessa |
|---|---|---|
| `index.php` | Login | todos |
| `cadastro.php` | Cadastro público (cria sempre o perfil operador) | visitante |
| `recuperar.php` | Recuperação de senha por link com validade de 30 min | visitante |
| `principal.php` | Dashboard: trens e sensores online, velocidade média, alertas | todos |
| `trens.php` | Lista, cadastra, edita e exclui trens | todos veem; admin e operador alteram |
| `trem.php` | Detalhe de um trem e seus sensores | todos |
| `sensores.php` | Lista, filtra, cadastra, edita e exclui sensores | admin e técnico |
| `rotas.php` | Lista de rotas (dados de exemplo) | admin e operador |
| `alertas.php` | Lista de alertas por severidade (dados de exemplo) | todos |
| `usuarios/index.php` | Lista de usuários | admin |
| `usuarios/cadastrar.php` | Cadastro de usuário com escolha do perfil | admin |
| `configuracoes.php` | Troca de senha | todos |
| `logout.php` | Encerra a sessão | todos |

Arquivos de apoio: `config/conexao.php` (única conexão com o banco), `includes/` (proteção de página, permissões, layout, menu e cabeçalho) e `assets/` (CSS, JS e imagens).

## Decisoes tomadas
- **Uma só conexão com o banco** (`config/conexao.php`): se a senha do MySQL mudar, muda em um lugar só, e a falha de conexão mostra mensagem clara em vez de tela em branco.
- **Senha com `password_hash` / `password_verify`**: o banco guarda só o hash, que não tem volta; por isso existe "redefinir senha" e não "ver senha".
- **Sessão (`$_SESSION`) guarda id, nome, login e perfil**: é a memória de quem está logado; é criada no login, lida em toda página interna (`includes/proteger.php`) e destruída em `logout.php`.
- **Toda página interna começa com `proteger.php`**: sem sessão, volta para o login; o cabeçalho `no-store` impede que o botão "voltar" mostre uma tela interna depois de sair.
- **Perfis conferidos no servidor** (administrador, operador, técnico): esconder o menu não basta, então o acesso indevido recebe erro 403.
- **Consultas preparadas (`?`)** em login, cadastros, busca e filtros: o que o usuário digita nunca vira parte do comando SQL.
- **Token CSRF em todos os formulários que gravam**: impede que outro site envie um formulário em nome do usuário logado.
- **Mensagem única no login** ("Email ou senha incorretos."): não revela se o email existe.
- **Padrão POST > redirecionamento > GET com mensagem na sessão**: atualizar a página não repete o cadastro.
- **Sensor ligado ao trem pela coluna `trem_id` (chave estrangeira)**: a tela mostra o código do trem por meio de um `LEFT JOIN`; se o trem for excluído, o sensor continua existindo, sem trem (`ON DELETE SET NULL`).
- **`ENUM` para perfil e status** e `UNIQUE` em login e códigos: o próprio banco recusa valores inválidos e duplicados.
- **Layout único** (`layout_inicio.php` / `layout_fim.php`) e menu filtrado por perfil: cada tela só cuida do seu conteúdo.
- **Congelamento da estrutura no fechamento**: não renomeamos pastas, arquivos nem tabelas na última fase, para não quebrar caminhos que já funcionam.

## Limitacoes conhecidas
- **Rotas e alertas não estão no banco.** Os dados vêm de `includes/dados_simulados.php`. Por isso o contador "Active Alerts" do dashboard também é simulado. As tabelas `rota`, `leitura` e `alerta` não existem (entrega 7 não implementada por falta de tempo).
- **Links das telas Rotas e Alertas para o trem não funcionam.** Eles apontam para códigos de trem de exemplo (como `TR-007` e `TR-011`) e `trem.php` espera o número do trem no banco; a tela mostra "Train not found". Também há trens de exemplo que não existem na tabela `trem`.
- **Painel de relatórios não foi implementado** (entrega 8). Falta o histórico de leituras dos sensores para alimentá-lo.
- **Interface em inglês e dados de exemplo de Nova York** (linhas "A Line", estações "125th St"); só as mensagens de erro e confirmação estão em português.
- **Recuperação de senha em modo demonstração:** não há servidor de e-mail, então o link de redefinição aparece na própria tela. Em produção ele seria enviado por e-mail.
- **Sem limite de tentativas de login** e sem edição ou exclusão de usuários pela tela (só listar e cadastrar).
- **Tabela `recuperacao_senha` começa vazia** de propósito: ela guarda apenas tokens temporários, gerados na hora.
- **Arquivos JS antigos sem uso** (`assets/js/mock-data.js`, `trains.js`, `train-detail.js`, `sensors.js`): sobraram da fase de telas em HTML e nenhuma página os carrega; só `common.js` (menus) é usado. Foram mantidos para não mexer na estrutura.
- **Roteiro de testes:** os testes 1 e 11 dependem do MySQL e do phpMyAdmin reais e precisam ser confirmados pela equipe (veja `docs/ROTEIRO_TESTES.md`).

## Uso de inteligencia artificial
Registro conforme a regra da unidade curricular. A equipe deve conferir e completar as linhas dos blocos anteriores.

| Ferramenta | Para que foi usada | O que foi aproveitado | Revisão da equipe |
|---|---|---|---|
| Claude (Anthropic) | Redigir README, Manual do Usuário e roteiro de testes; rodar o pré-teste e tirar as capturas de tela | Texto dos documentos e capturas de um ambiente de teste local | Leticia |

---
Documentação: `manual-do-usuario.pdf` (para quem usa), `PLANO_IMPLEMENTACAO.md` e pasta `docs/` (roteiro de testes, cronograma, kanban e relatório de correções).
