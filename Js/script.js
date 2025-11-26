const logoutBtn = document.getElementById("logoutBtn");
const OrcamentoCard = document.getElementById("OrcamentoCard");
const VendasCard = document.getElementById("vendasCard");
const ProducaoCard = document.getElementById("ProducaoCard");
const EntregaCard = document.getElementById("entregaCard");
const clienteCard = document.getElementById("clienteCard");
const configuracoesCard = document.getElementById("configuracoesCard");
const desempenhoCard = document.getElementById("desempenhoCard");
const receitaCard = document.getElementById("receitaCard");
const ticketCard = document.getElementById("TicketCard");
const vendacard = document.getElementById("vendaCard");
const tempoCard = document.getElementById("tempoCard");
const clientesCard = document.getElementById("clientesCard");
const volumeCard = document.getElementById("volumeCard");
const conversaoCard = document.getElementById("conversaoCard");
const vendasPCard = document.getElementById("vendasPCard");
const falhasCard = document.getElementById("falhasCard");
const vitrineCard = document.getElementById("vitrineCard");
const backToDashboard = document.getElementById("backToDashboard");
const novoItemBtn = document.getElementById("novoItemBtn");
const novoItemBtnVitrine = document.getElementById("novoItemBtnVitrine");
const cancelarVitrineBtn = document.getElementById("cancelarVitrineBtn");
const VoltOrca = document.getElementById("VoltOrca");

if (logoutBtn) {
  logoutBtn.addEventListener("click", () => {
    window.location.href = "../Paginas/logout.php";
  });
}

if (OrcamentoCard) {
  OrcamentoCard.addEventListener("click", () => {
    window.location.href = "../Paginas/Orcamento.php";
  });
}

if (VendasCard) {
  VendasCard.addEventListener("click", () => {
    window.location.href = "../Paginas/Venda.php";
  });
}

if (ProducaoCard) {
  ProducaoCard.addEventListener("click", () => {
    window.location.href = "../Paginas/Producao.php";
  });
}

if (EntregaCard) {
  EntregaCard.addEventListener("click", () => {
    window.location.href = "../Paginas/Entrega.php";
  });
}

if (clienteCard) {
  clienteCard.addEventListener("click", () => {
    window.location.href = "../Paginas/Cliente.php";
  });
}

if (desempenhoCard) {
  desempenhoCard.addEventListener("click", () => {
    window.location.href = "../Paginas/Desempenho.php";
  });
}

if (receitaCard) {
  receitaCard.addEventListener("click", () => {
    document.getElementById("cardReceitaDetalhes").classList.toggle("hidden");
  });
}

if (ticketCard) {
  ticketCard.addEventListener("click", () => {
    document.getElementById("cardTicketDetalhe").classList.toggle("hidden");
  });
}

if (vendacard) {
  vendacard.addEventListener("click", () => {
    document.getElementById("cardVendasDetalhe").classList.toggle("hidden");
  }); 
}

if (tempoCard) {
  tempoCard.addEventListener("click", () => {
    document.getElementById("cardTempoDetalhe").classList.toggle("hidden");
  });
}
if (clientesCard) {
  clientesCard.addEventListener("click", () => {
    document.getElementById("cardClientesDetalhe").classList.toggle("hidden");
  });
}

if (volumeCard) {
  volumeCard.addEventListener("click", () => {
    document.getElementById("cardVolumeDetalhe").classList.toggle("hidden");
  }); 
}

if (conversaoCard) {
  conversaoCard.addEventListener("click", () => {
    document.getElementById("cardConversaoDetalhe").classList.toggle("hidden");
  });
}

if (vendasPCard) {
  vendasPCard.addEventListener("click", () => {
    document.getElementById("cardVendasPDetalhe").classList.toggle("hidden");
  }); 
}

if (falhasCard) {
  falhasCard.addEventListener("click", () => {
    document.getElementById("cardFalhasDetalhe").classList.toggle("hidden");
  }); 
}




if (configuracoesCard) {
  configuracoesCard.addEventListener("click", () => {
    window.location.href = "../Paginas/Configuracao.php";
  });
}

if (vitrineCard) {
  vitrineCard.addEventListener("click", () => {
    window.location.href = "../Paginas/VitrineDash.php";
  });
}
if (novoItemBtnVitrine) {
  novoItemBtnVitrine.addEventListener("click", () => {
    document.getElementById("novoVitrineModal").classList.toggle("hidden");
  });
}

if (cancelarVitrineBtn) {
  cancelarVitrineBtn.addEventListener("click", () => {
    document.getElementById("novoVitrineModal").classList.toggle("hidden");
  });
}

if (backToDashboard) {
  backToDashboard.addEventListener("click", () => {
    window.location.href = "../Paginas/Dashboard.php";
  });
}

if (novoItemBtn) {
  novoItemBtn.addEventListener("click", () => {
    window.location.href = "../Paginas/CadOrcamento.php";
  });
}

if (VoltOrca) {
  VoltOrca.addEventListener("click", () => {
    window.location.href = "../Paginas/Orcamento.php";
  });
}

const CEL = document.getElementById("orcClienteCEL");

CEL.addEventListener("input", function (e) {
  let value = e.target.value.replace(/\D/g, "");

  if (value.length > 11) value = value.slice(0, 11);

  if (value.length > 10) {
    value = value.replace(/^(\d{2})(\d{5})(\d{4})$/, "($1) $2-$3");
  } else if (value.length > 5) {
    value = value.replace(/^(\d{2})(\d{4})(\d{0,4})$/, "($1) $2-$3");
  } else if (value.length > 2) {
    value = value.replace(/^(\d{2})(\d{0,5})$/, "($1) $2");
  } else {
    value = value.replace(/^(\d{0,2})$/, "($1");
  }

  e.target.value = value;
});

const TEL = document.getElementById("orcClienteTEL");

TEL.addEventListener("input", function (e) {
  let value = e.target.value.replace(/\D/g, "");

  if (value.length > 10) value = value.slice(0, 10);

  if (value.length > 6) {
    value = value.replace(/^(\d{2})(\d{4})(\d{0,4})$/, "($1) $2-$3");
  } else if (value.length > 2) {
    value = value.replace(/^(\d{2})(\d{0,4})$/, "($1) $2");
  } else {
    value = value.replace(/^(\d{0,2})$/, "($1");
  }

  e.target.value = value;
});

const CPFCPNJ = document.getElementById("orcClienteCPFPJ");

CPFCPNJ.addEventListener("input", function (e) {
  let value = e.target.value.replace(/\D/g, "");

  if (value.length <= 11) {
    value = value.slice(0, 11); // CPF máximo 11 dígitos
  } else {
    value = value.slice(0, 14); // CNPJ máximo 14 dígitos
  }

  // CPF
  if (value.length <= 11) {
    value = value.replace(/(\d{3})(\d)/, "$1.$2");
    value = value.replace(/(\d{3})(\d)/, "$1.$2");
    value = value.replace(/(\d{3})(\d{1,2})$/, "$1-$2");
  }
  // CNPJ
  else {
    value = value.replace(/^(\d{2})(\d)/, "$1.$2");
    value = value.replace(/^(\d{2})\.(\d{3})(\d)/, "$1.$2.$3");
    value = value.replace(/\.(\d{3})(\d)/, ".$1/$2");
    value = value.replace(/(\d{4})(\d{1,2})$/, "$1-$2");
  }

  e.target.value = value;
});

const CEP = document.getElementById("orcClienteCEP");
const Endereco = document.getElementById("orcClienteEnde");
const Bairro = document.getElementById("orcClienteBai");
const Cidade = document.getElementById("orcClienteCid");

CEP.addEventListener("input", function (e) {
  let value = e.target.value.replace(/\D/g, "");

  if (value.length > 8) value = value.slice(0, 8);

  if (value.length > 5) {
    value = value.replace(/^(\d{5})(\d{1,3})$/, "$1-$2");
  }

  e.target.value = value;

  fetch(`https://viacep.com.br/ws/${value}/json/`)
    .then((response) => response.json())
    .then((data) => {
      if (data.erro) {
        alert("CEP não encontrado!");
        Endereco.value = "";
        Bairro.value = "";
        Cidade.value = "";
        return;
      }

      // Preenche os campos do formulário com os dados da API
      Endereco.value = data.logradouro;
      Bairro.value = data.bairro;
      Cidade.value = data.localidade;
    });
});

const Money = document.getElementById("ProPreco");

Money.addEventListener("input", function (e) {
  let value = e.target.value.replace(/\D/g, ""); // remove tudo que não for número

  if (value === "") {
    e.target.value = "";
    return;
  }

  // Divide centavos
  value = (parseInt(value, 10) / 100).toFixed(2);

  // Troca ponto por vírgula (para decimal)
  value = value.replace(".", ",");

  // Adiciona separadores de milhar
  const parts = value.split(",");
  parts[0] = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, ".");
  value = parts.join(",");

  e.target.value = "R$ " + value;
});

function toggleDetalhes(botao) {
  const linha = botao.closest("tr");
  const detalhes = linha.nextElementSibling;
  const menu = detalhes.querySelector(".menu");
  const aberto = menu.classList.toggle("mostrar");

  if (aberto) {
    detalhes.style.display = "table-row";
  } else {
    menu.style.maxHeight = "0";
    setTimeout(() => (detalhes.style.display = "none"), 400);
  }
}

function toggleEdit(botao) {
  const OrcDescricao = document.getElementById("OrcDescricao");
  const OrcTipo = document.getElementById("OrcTipo");
  const OrcPreco = document.getElementById("OrcPreco");
  const CliEmail = document.getElementById("CliEmail");
  const CliEndereco = document.getElementById("CliEndereco");
  const CliCidade = document.getElementById("CliCidade");
  const CliTelefone = document.getElementById("CliTelefone");
  const CliCelular = document.getElementById("CliCelular");

  OrcDescricao.disabled = !OrcDescricao.disabled;
  OrcTipo.disabled = !OrcTipo.disabled;
  OrcPreco.disabled = !OrcPreco.disabled;
  CliEmail.disabled = !CliEmail.disabled;
  CliEndereco.disabled = !CliEndereco.disabled;
  CliCidade.disabled = !CliCidade.disabled;
  CliTelefone.disabled = !CliTelefone.disabled;
  CliCelular.disabled = !CliCelular.disabled;

  if (CliEmail.disabled) {
  } else {
    OrcDescricao.focus();
  }
}