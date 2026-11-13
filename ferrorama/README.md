# Ferrorama / GoMobi - Como rodar

1. Instale o **XAMPP** e, no painel, inicie **Apache** e **MySQL**.
2. Copie esta pasta para `C:\xampp\htdocs\ferrorama`.
3. Abra `http://localhost/phpmyadmin` → aba **SQL** → cole o conteúdo de `banco/script.sql` → **Executar**.
4. Acesse `http://localhost/ferrorama/index.php`.

| Perfil | Email | Senha |
|---|---|---|
| Administrador | admin@gomobi.com | Admin@2026 |
| Operador | operador@gomobi.com | Operador@2026 |
| Técnico | tecnico@gomobi.com | Tecnico@2026 |

Se o MySQL tiver senha no root ou outra porta, altere `config/conexao.php` (porta atual: 3308).
Documentação: `PLANO_IMPLEMENTACAO.md` e a pasta `docs/`.
