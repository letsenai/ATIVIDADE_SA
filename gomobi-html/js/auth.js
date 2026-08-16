/* autenticação usando localstorage */

const CHAVE_CONTAS = 'gomobi_contas';
const CHAVE_SESSAO = 'gomobi_sessao';

// pega a lista de contas cadastradas
function getContas() {
  const salvo = localStorage.getItem(CHAVE_CONTAS);
  if (!salvo) return [];
  try {
    return JSON.parse(salvo);
  } catch (erro) {
    return [];
  }
}

function salvarContas(contas) {
  localStorage.setItem(CHAVE_CONTAS, JSON.stringify(contas));
}

// monta o objeto de user que fica salvo como "logado"
function montarUsuario(conta) {
  const hoje = new Date().toLocaleDateString('pt-BR');
  return {
    nome: conta.nome || 'User',
    email: conta.email,
    role: 'viewer',
    status: 'active',
    memberSince: hoje,
    lastLogin: hoje,
  };
}

// retorna o user logado no momento
function getUsuarioLogado() {
  const salvo = localStorage.getItem(CHAVE_SESSAO);
  if (!salvo) return null;
  try {
    return JSON.parse(salvo);
  } catch (erro) {
    return null;
  }
}

// faz login: procura a conta pelo email e verifica a senha, retorna uma mensagem de erro (string) se deu certo
function fazerLogin(email, senha) {
  const contas = getContas();
  let conta = contas.find((c) => c.email.toLowerCase() === email.toLowerCase());

  // Conta de demonstração, para teste
  if (!conta && email.toLowerCase() === 'admin@gomobi.com') {
    conta = { nome: 'Admin', email: email, senha: senha || 'password' };
    contas.push(conta);
    salvarContas(contas);
  }

  if (!conta) {
    return 'Não encontramos uma conta com esse email.';
  }
  if (conta.senha !== senha) {
    return 'Senha incorreta.';
  }

  localStorage.setItem(CHAVE_SESSAO, JSON.stringify(montarUsuario(conta)));
  return null;
}

// cria uma conta nova, retorna mensagem de erro ou null se deu certo
function fazerCadastro(nome, email, senha) {
  const contas = getContas();
  const jaExiste = contas.some((c) => c.email.toLowerCase() === email.toLowerCase());
  if (jaExiste) {
    return 'Já existe uma conta com esse email.';
  }

  const novaConta = { nome: nome, email: email, senha: senha };
  contas.push(novaConta);
  salvarContas(contas);

  localStorage.setItem(CHAVE_SESSAO, JSON.stringify(montarUsuario(novaConta)));
  return null;
}

function fazerLogout() {
  localStorage.removeItem(CHAVE_SESSAO);
}

// troca a senha da conta logada, retorna mensagem de erro ou null
function trocarSenha(senhaAtual, novaSenha) {
  const usuario = getUsuarioLogado();
  if (!usuario) return 'Você precisa estar logado.';

  const contas = getContas();
  const index = contas.findIndex((c) => c.email.toLowerCase() === usuario.email.toLowerCase());
  if (index === -1) return 'Conta não encontrada.';

  if (contas[index].senha !== senhaAtual) {
    return 'Senha atual incorreta.';
  }

  contas[index].senha = novaSenha;
  salvarContas(contas);
  return null;
}

function protegerPagina() {
  const usuario = getUsuarioLogado();
  if (!usuario) {
    window.location.href = 'login.html';
  }
  return usuario;
}
