document.addEventListener('DOMContentLoaded', function () {
  const usuario = getUsuarioLogado();
  if (!usuario) return;

  document.getElementById('perfil-nome').textContent = usuario.nome;
  document.getElementById('perfil-email').textContent = usuario.email;
  document.getElementById('perfil-role').textContent = usuario.role;
  document.getElementById('perfil-status').textContent = usuario.status;
  document.getElementById('perfil-membro-desde').textContent = usuario.memberSince || '-';
  document.getElementById('perfil-ultimo-login').textContent = usuario.lastLogin || '-';

  const form = document.getElementById('form-senha');
  const mensagem = document.getElementById('mensagem-senha');

  form.addEventListener('submit', function (e) {
    e.preventDefault();

    const senhaAtual = document.getElementById('senha-atual').value;
    const novaSenha = document.getElementById('nova-senha').value;
    const confirmarSenha = document.getElementById('confirmar-nova-senha').value;

    mensagem.style.display = 'none';

    if (!senhaAtual || !novaSenha || !confirmarSenha) {
      mostrarMensagem('Preencha todos os campos.', 'erro');
      return;
    }
    if (novaSenha.length < 8) {
      mostrarMensagem('A nova senha precisa ter pelo menos 8 caracteres.', 'erro');
      return;
    }
    if (novaSenha !== confirmarSenha) {
      mostrarMensagem('As senhas não conferem.', 'erro');
      return;
    }

    const erro = trocarSenha(senhaAtual, novaSenha);
    if (erro) {
      mostrarMensagem(erro, 'erro');
    } else {
      mostrarMensagem('Senha atualizada com sucesso.', 'sucesso');
      form.reset();
    }
  });

  function mostrarMensagem(texto, tipo) {
    mensagem.textContent = texto;
    mensagem.className = tipo === 'erro' ? 'form-error' : 'form-success';
    mensagem.style.display = 'block';
  }
});
