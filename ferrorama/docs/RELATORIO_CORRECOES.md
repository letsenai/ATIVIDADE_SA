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
- Não consegui executar o PHP aqui: teste no XAMPP antes de entregar.
