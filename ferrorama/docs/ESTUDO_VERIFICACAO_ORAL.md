# Estudo para a verificação oral (as 6 perguntas)

Abra o arquivo, aponte a linha e explique. Cada integrante escolhe duas perguntas e explica para os outros.

## 1. Como o login funciona?  → `index.php`
1. O formulário (dois campos e um botão) envia por POST para o próprio `index.php`: `index.php` linha 78.
2. O PHP lê email e senha e **busca no banco o usuário com aquele login** (consulta preparada): `index.php` linha 30.
3. Compara a senha digitada com a guardada usando `password_verify`: `index.php` linha 41.
4. Se bate: `session_regenerate_id`, grava a sessão e redireciona para `principal.php`. Se não bate, mostra "Email ou senha incorretos.".

**Não pode faltar:** a senha no banco não é a senha, é um embaralhamento (hash) feito com `password_hash`. Não tem volta; só dá para conferir se a senha digitada corresponde. Por isso existe "redefinir senha" e nunca "ver minha senha".

## 2. O que é a `$_SESSION` e o que guardamos nela?
A sessão é a memória do sistema sobre quem está usando; sem ela cada página esqueceria o login.
| Momento | Onde | O que acontece |
|---|---|---|
| Login | `index.php` linha 13 | Guarda `usuario_id`, `nome`, `login` e `papel` |
| Cada página interna | `includes/proteger.php` linha 24 | Se não existe, volta para o login |
| Cabeçalho | `includes/cabecalho.php` | Lê nome e papel da sessão para mostrar no topo |
| Sair | `logout.php` | Esvazia, apaga o cookie e destrói a sessão |

## 3. Onde fica a conexão com o banco?  → `config/conexao.php`
- Guarda servidor, usuário, senha, banco e porta: `config/conexao.php` linha 13.
- É o **único** arquivo que conhece esses dados; as outras páginas só fazem `require` dele.
- Se a senha do banco mudar, muda em um lugar só.
- Verificação de falha: `config/conexao.php` linha 31. Sem ela o sistema seguiria usando uma conexão que não existe e o usuário veria tela em branco.

## 4. Onde está a consulta que traz os trens para a tela?  → `trens.php`
1. Inclui a conexão: `trens.php` linha 6.
2. Monta e executa a consulta: `trens.php` linha 66.
3. Guarda o resultado em `$trens` com um laço `while`.
4. Na tabela, um `foreach` desenha uma linha por trem: `trens.php` linha 107.

**Frase final:** cada linha na tela é uma volta do laço, e cada volta é um registro do banco. **Demonstração:** apague um trem no phpMyAdmin, recarregue a tela e mostre que sumiu.

## 5. Por que a consulta usa `?` no lugar do valor?
Exemplo: `index.php` linha 30 e `trens.php` linha 43. O valor digitado vai separado, depois, com `bind_param`. Se fosse colado dentro do comando, alguém poderia digitar um pedaço de comando no campo e o banco executaria (SQL injection). Com `?`, o que o usuário digita é sempre texto.

## 6. Como o sistema sabe que um sensor pertence a um trem?
- No banco, a tabela `sensor` tem a coluna `trem_id` com chave estrangeira para `trem.id`: `banco/script.sql` linha 77 e `banco/script.sql` linha 80.
- Na tela, a consulta junta as duas tabelas (`LEFT JOIN`) para mostrar o código do trem (TR-001) em vez do número: `sensores.php` linha 74.
- Ao cadastrar um sensor, o trem é escolhido em uma lista (`<select>`), não digitado.

## Divisão sugerida
| Todos precisam saber | Cada um aprofunda um |
|---|---|
| Como o login funciona; o que é a sessão; onde fica a conexão; por que a senha é embaralhada | Tela de trens e consulta / Tela de sensores e a ligação entre tabelas / Script do banco e tabelas / Decisões do README |

Combinado: se perguntarem sobre a parte de outro integrante, explique o que você entende dela, não "não fui eu que fiz".
