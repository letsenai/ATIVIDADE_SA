# Roteiro de testes

Teste em dupla: um opera, o outro anota. **Preencha "Passou?" com o que realmente aconteceu.**
Se algo falhou e foi corrigido, não apague a linha: escreva na observação.

| Nº | O que testar | Como fazer | Resultado esperado | Passou? | Observação |
|---|---|---|---|---|---|
| 1 | Importar o script em banco vazio | No phpMyAdmin, apague o banco `ferrovias` (se existir) e importe `banco/script.sql` | Cria tudo, sem erro | ( ) sim ( ) não | |
| 2 | Abrir o sistema pelo localhost | Abrir `http://localhost/ferrorama/` | Tela de login aparece | ( ) sim ( ) não | |
| 3 | Entrar com senha errada | Email do admin com senha `errada123` | Mensagem de erro, sem entrar | ( ) sim ( ) não | |
| 4 | Entrar com usuário do script | `admin@gomobi.com` / `Admin@2026` | Entra e vai para a tela principal | ( ) sim ( ) não | |
| 5 | Abrir tela interna em janela anônima | Janela anônima → `http://localhost/ferrorama/principal.php` | Redireciona para o login | ( ) sim ( ) não | |
| 6 | Conferir o topo da tela | Olhar o canto superior direito logado | Mostra o nome de quem está logado | ( ) sim ( ) não | |
| 7 | Cadastrar um trem | Train Monitoring → preencher e Create Train | Aparece na listagem | ( ) sim ( ) não | |
| 8 | Cadastrar um trem com campo vazio | Deixar Code ou Line em branco e enviar | Mensagem, sem gravar | ( ) sim ( ) não | |
| 9 | Editar e excluir um trem | Edit → alterar → salvar; depois Delete | A alteração persiste ao recarregar | ( ) sim ( ) não | |
| 10 | Cadastrar um sensor | Sensors → escolher o trem na lista | Escolhe o trem em lista, não digitando | ( ) sim ( ) não | |
| 11 | Olhar a senha no phpMyAdmin | Tabela `usuario`, coluna `senha` | Está embaralhada, não legível | ( ) sim ( ) não | |
| 12 | Sair e apertar o botão voltar | Menu do usuário → Logout → botão voltar do navegador | Não volta para dentro do sistema | ( ) sim ( ) não | |

## Testes extras (por perfil)
| Teste | Esperado | Passou? |
|---|---|---|
| Operador abre `sensores.php` pela URL | "Acesso negado" | ( ) sim ( ) não |
| Técnico abre `rotas.php` pela URL | "Acesso negado" | ( ) sim ( ) não |
| Operador abre `usuarios/index.php` | "Acesso negado" | ( ) sim ( ) não |
| Abrir `http://localhost/ferrorama/config/conexao.php` | Acesso negado (403) | ( ) sim ( ) não |
