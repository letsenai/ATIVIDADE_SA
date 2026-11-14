# Roteiro de testes

Doze verificações do fechamento. A coluna **Pré-teste** foi preenchida em 13/11/2026 por uma execução automatizada (navegador Chromium controlado por script, PHP 8.3) em um **ambiente de teste local que emula o `mysqli` sobre SQLite**, com os mesmos dados do `banco/script.sql`. Ela confirma o comportamento do código PHP/HTML, mas **não substitui o teste da equipe no XAMPP**: preencha **PASSOU?** em dupla (um opera, o outro anota) e anote falhas na observação.

| Nº | O que testar | Resultado esperado | Pré-teste | PASSOU? | Observação |
|---|---|---|---|---|---|
| 1 | Importar o script em banco vazio | Cria tudo, sem erro | **Não testado** (precisa de MySQL real) | ( ) sim ( ) não | Importar em um banco novo, não no que já tem as tabelas |
| 2 | Abrir o sistema pelo localhost | Tela de login aparece | OK | ( ) sim ( ) não | |
| 3 | Entrar com senha errada | Mensagem de erro, sem entrar | OK — "Email ou senha incorretos." | ( ) sim ( ) não | |
| 4 | Entrar com usuário do script | Entra e vai para a tela principal | OK com admin, operador e técnico (senhas do README) | ( ) sim ( ) não | |
| 5 | Abrir tela interna em janela anônima | Redireciona para o login | OK nas 9 telas internas testadas | ( ) sim ( ) não | |
| 6 | Conferir o topo da tela | Mostra o nome de quem está logado | OK — menu do usuário mostra nome, email e perfil | ( ) sim ( ) não | |
| 7 | Cadastrar um trem | Aparece na listagem | OK — "Trem cadastrado." | ( ) sim ( ) não | |
| 8 | Cadastrar um trem com campo vazio | Mensagem, sem gravar | OK — o navegador bloqueia (campo obrigatório) e o servidor também recusa: "Informe o código e a linha." | ( ) sim ( ) não | |
| 9 | Editar e excluir um trem | A alteração persiste ao recarregar | OK — edição e exclusão persistem; a exclusão pede confirmação | ( ) sim ( ) não | |
| 10 | Cadastrar um sensor | Escolhe o trem em lista, não digitando | OK — campo "Train" é uma lista; sensor aparece com o código do trem | ( ) sim ( ) não | |
| 11 | Olhar a senha no phpMyAdmin | Está embaralhada, não legível | Parcial — as 3 senhas do script são hashes bcrypt (`$2y$...`, 60 caracteres) | ( ) sim ( ) não | Confirmar visualmente no phpMyAdmin |
| 12 | Sair e apertar o botão voltar | Não volta para dentro do sistema | OK — volta para o login | ( ) sim ( ) não | |

## Verificações extras do pré-teste
| Verificação | Resultado |
|---|---|
| Operador abre `sensores.php` e `usuarios/index.php` | Bloqueado (403), como esperado |
| Técnico abre `rotas.php` | Bloqueado (403), como esperado |
| Técnico em `trens.php` | Vê a lista, sem formulário nem botões Edit/Delete |
| Filtro de sensores por tipo (Brake) | Retorna 1 sensor |
| Tela de trens a 390 px (celular) | Sem rolagem horizontal |
| **Link de um trem em `rotas.php` / `alertas.php`** | **Falhou:** abre "Train not found" (os links usam o código de exemplo, ex.: `TR-003`, e `trem.php` espera o número do trem). Registrado como limitação conhecida no README. |

## Teste que falhou
Quando um teste falhar na sua execução, não apague a linha: escreva na observação que falhou e que foi corrigido (ou que virou limitação conhecida).
