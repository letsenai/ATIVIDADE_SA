# Relatório de correções e pendências

## Corrigido
1. Pastas organizadas (config, includes, assets, usuarios, banco): o código não achava os arquivos.
2. Arquivos vazios `download`, `download (1)` e `download (2)` removidos.
3. Telas de trens, detalhe, sensores e configurações convertidas para PHP, protegidas por sessão (antes usavam localStorage e apontavam para um login.html inexistente).
4. Criadas as páginas que faltavam: recuperação de senha, rotas, usuários e alertas; cadastro público também.
5. Perfis diferenciados (administrador, operador, técnico) com bloqueio no servidor (erro 403) e menu filtrado.
6. Layout único (`layout_inicio/fim`) no lugar do HTML repetido em cada tela.
7. Dashboard com contagem de alertas ativos vinda dos dados simulados.
8. Troca de senha agora grava no MySQL.
9. Removida a conta de demonstração insegura (aceitava qualquer senha).
10. Criados `PLANO_IMPLEMENTACAO.md` (com a estrutura real), cronograma e Kanban.
11. Conferido: o hash do admin corresponde à senha `Admin@2026`.

12. **Entrega 6:** trens e sensores no MySQL, com criar, editar, excluir, filtros e persistência. Perfis: admin e operador alteram trens; admin e técnico alteram sensores. Token CSRF nos formulários.

## Pendente
- Entrega 7: rotas, leituras de sensores e alertas no banco (hoje simulados).
- Entrega 8: painel de relatórios.
- Entrega final: apresentação.
- Mockup no Canva e GitHub: não consegui verificar.
- Datas e responsáveis no cronograma, e o Kanban real na ferramenta da turma.
- Idioma: interface em inglês e dados de Nova York; traduzir se o professor exigir português.
- Segurança extra: limite de tentativas de login.

## Fechamento (13/11/2026)
- Documentação refeita: `README.md` (modelo de nove seções), `manual-do-usuario.pdf` (com capturas das telas reais) e `docs/ROTEIRO_TESTES.md`.
- README antigo dizia que a porta do MySQL era 3308; o código usa **3306**. Corrigido no README.
- Pré-teste do roteiro em ambiente local emulado (mysqli sobre SQLite): 10 dos 12 testes passaram; os testes 1 (importar script) e 11 (ver senha no phpMyAdmin) precisam do MySQL real.
- Defeito encontrado e **não corrigido** (documentado como limitação): links de `rotas.php` e `alertas.php` para `trem.php` mostram "Train not found", porque usam códigos de exemplo (ex.: `TR-003`) e a página espera o id numérico.
- Arquivos JS antigos (`mock-data.js`, `trains.js`, `train-detail.js`, `sensors.js`) não são carregados por nenhuma página; mantidos para não alterar a estrutura.
