let carrinho = [];

document.getElementById('btnAdicionar')?.addEventListener('click', () => {
    const nome = document.getElementById('nome').value.trim();
    const quantidade = parseFloat(document.getElementById('quantidade').value);
    const preco = parseFloat(document.getElementById('preco').value);

    if (!nome) return toastr.error('Nome obrigatório.');
    if (isNaN(quantidade) || quantidade <= 0) return toastr.error('Quantidade inválida.');
    if (isNaN(preco) || preco < 0) return toastr.error('Preço inválido.');

    carrinho.push({ nome, quantidade, preco });
    atualizarLista();
    toastr.success(`${nome} adicionado ao carrinho temporário.`);

    document.getElementById('nome').value = '';
    document.getElementById('quantidade').value = '';
    document.getElementById('preco').value = '';
});

function atualizarLista() {
    const lista = document.getElementById('listaProdutos');
    const totalCarrinhoEl = document.getElementById('totalCarrinho');
    if (!lista) return;

    lista.innerHTML = '';
    let total = 0;

    carrinho.forEach((item, index) => {
        total += (item.quantidade * item.preco);

        const li = document.createElement('li');
        li.className = 'list-group-item d-flex justify-content-between align-items-center';
        li.innerHTML = `
            <div class="d-flex w-100 align-items-center">
                <span class="fw-bold flex-grow-1 text-truncate item-nome">${item.nome}</span>
                <span class="badge bg-secondary text-white mx-3 item-qtd">${item.quantidade} unidades</span>
                <span class="text-muted me-3 item-preco">R$ ${item.preco.toFixed(2).replace('.', ',')}</span>
            </div>
            <button class="btn btn-sm btn-outline-danger" onclick="removerItem(${index})">
                <i class="fa-solid fa-trash"></i>
            </button>
        `;
        lista.appendChild(li);
    });

    if (totalCarrinhoEl) {
        totalCarrinhoEl.innerText = `R$ ${total.toFixed(2).replace('.', ',')}`;
    }
}

function removerItem(index) {
    carrinho.splice(index, 1);
    atualizarLista();
}

document.getElementById('btnFinalizar')?.addEventListener('click', async () => {
    if (carrinho.length === 0) return toastr.warning('O carrinho está vazio.');

    try {
        const response = await fetch('controller/controller_processa.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(carrinho)
        });

        const result = await response.json();

        if (result.status === 'success') {
            let htmlMsg = `<p>Original: <b>R$ ${result.valorOriginal}</b></p>`;
            if (result.desconto !== "0,00") {
                htmlMsg += `<p>Desconto (10%): <b>- R$ ${result.desconto}</b></p>`;
            }
            htmlMsg += `<h4>Total: R$ ${result.valorFinal}</h4>`;

            Swal.fire({
                title: 'Produtos e Compra Salvos!',
                html: htmlMsg,
                icon: 'success',
                confirmButtonText: 'OK'
            }).then(() => {
                carrinho = [];
                atualizarLista();

                const modalElement = document.getElementById('modalNovaCompra');
                const modalInstance = bootstrap.Modal.getInstance(modalElement) || new bootstrap.Modal(modalElement);
                modalInstance.hide();

                carregarCompras();
            });
        } else {
            toastr.error(result.message);
        }
    } catch (error) {
        toastr.error('Erro ao conectar com o servidor.');
    }
});

async function carregarCompras() {
    const tabela = document.getElementById('tabelaCompras');
    if (!tabela) return;

    try {
        const response = await fetch('controller/controller_listar.php');
        const result = await response.json();


        if ($.fn.DataTable.isDataTable('#tableCompras')) {
            $('#tableCompras').DataTable().clear().destroy();
        }

        if (result.status === 'success') {
            tabela.innerHTML = '';

            if (result.data.length === 0) {
                tabela.innerHTML = '<tr><td colspan="5" class="text-center">Nenhuma compra realizada.</td></tr>';
                return;
            }

            result.data.forEach(compra => {
                const tr = document.createElement('tr');
                tr.innerHTML = `
                    <td>R$ ${parseFloat(compra.ValorOriginal).toFixed(2).replace('.', ',')}</td>
                    <td class="text-danger">R$ ${parseFloat(compra.Desconto).toFixed(2).replace('.', ',')}</td>
                    <td class="text-success"><strong>R$ ${parseFloat(compra.ValorFinal).toFixed(2).replace('.', ',')}</strong></td>
                    <td >${compra.DataFormatada}</td>
                    <td>
                        <a href="#" class="cursor-pointer" onclick="verDetalhesCompra(${compra.Id})">
                            <i class="fa-solid fa-receipt"></i>
                        </a>
                    </td>
                `;
                tabela.appendChild(tr);
            });


            $('#tableCompras').DataTable({
                language: {
                    url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/pt-BR.json'
                },
                destroy: true,
                order: []
            });

        } else {
            tabela.innerHTML = '<tr><td colspan="5" class="text-center text-danger">Erro ao carregar dados.</td></tr>';
            toastr.error(result.message);
        }
    } catch (error) {
        tabela.innerHTML = '<tr><td colspan="5" class="text-center text-danger">Falha na ligação ao servidor.</td></tr>';
        toastr.error('Erro de comunicação.');
    }
}


async function verDetalhesCompra(compraId) {
    try {
        const response = await fetch(`controller/controller_detalhes.php?id=${compraId}`);
        const result = await response.json();

        if (result.status === 'success') {
            const compra = result.compra;
            const itens = result.itens;


            document.getElementById('detalheIdCompra').innerText = `#${compra.Id}`;
            document.getElementById('detalheDataCompra').innerText = compra.DataFormatada;
            document.getElementById('detalheValorOriginal').innerText = `R$ ${parseFloat(compra.ValorOriginal).toFixed(2).replace('.', ',')}`;


            const divDesconto = document.getElementById('divDetalheDesconto');
            if (parseFloat(compra.Desconto) > 0) {
                document.getElementById('detalheDesconto').innerText = `- R$ ${parseFloat(compra.Desconto).toFixed(2).replace('.', ',')}`;
                divDesconto.style.display = 'flex';
            } else {
                divDesconto.style.display = 'none';
            }

            document.getElementById('detalheValorFinal').innerText = `R$ ${parseFloat(compra.ValorFinal).toFixed(2).replace('.', ',')}`;


            const tabelaItens = document.getElementById('tabelaItensCompraDetalhe');
            tabelaItens.innerHTML = '';

            itens.forEach(item => {
                const qtd = parseFloat(item.Quantidade);
                const precoUnit = parseFloat(item.PrecoUnitario);
                const subtotalItem = qtd * precoUnit;

                const tr = document.createElement('tr');
                tr.innerHTML = `
                    <td class="ps-3 fw-semibold">${item.Nome}</td>
                    <td class="text-center">${qtd}</td>
                    <td class="text-end">R$ ${precoUnit.toFixed(2).replace('.', ',')}</td>
                    <td class="text-end pe-3 fw-bold text-dark">R$ ${subtotalItem.toFixed(2).replace('.', ',')}</td>
                `;
                tabelaItens.appendChild(tr);
            });


            const modalElement = document.getElementById('modalVerItens');
            const modalInstance = new bootstrap.Modal(modalElement);
            modalInstance.show();

        } else {
            toastr.error(result.message);
        }
    } catch (error) {
        toastr.error('Erro ao buscar os detalhes da compra no servidor.');
    }
}

document.addEventListener('DOMContentLoaded', carregarCompras);