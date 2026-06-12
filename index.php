<?php include 'includes/header.php'; ?>

<div class="container mt-5">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-center mb-4">
        <h3 class=" mb-3 mb-md-0"><i class="fa-solid fa-cart-shopping me-2"></i>Histórico de Compras</h3>
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalNovaCompra">
            <i class="fa-solid fa-plus me-1"></i> Nova Compra
        </button>
    </div>
    
   
            <div class="container p-3" style="background-color: #fff; border-radius: 12px;">
            <div class="table-responsive ">
                <table class="table mt-3 mb-3" id="tableCompras">
                    <thead class="table-dark">
                        <tr>
                            <th>Valor Original</th>
                            <th>Desconto</th>
                            <th>Valor Final</th>
                            <th>Data</th>
                            <th></th> </tr>
                    </thead>
                    <tbody id="tabelaCompras">
                        <tr><td colspan="5" class="text-center py-4 text-muted"><i class="fa-solid fa-spinner fa-spin me-2"></i>A carregar...</td></tr>
                    </tbody>
                </table>
            </div>

    </div>
</div>

<div class="modal fade" id="modalNovaCompra" tabindex="-1" aria-labelledby="modalNovaCompraLabel" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content shadow-lg border-0">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="modalNovaCompraLabel"><i class="fa-solid fa-barcode me-2"></i>Adicionar Produtos</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Fechar"></button>
            </div>
            <div class="modal-body bg-light">
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-12 col-md-5">
                                <label class="form-label small text-muted">Nome do Produto</label>
                                <input type="text" id="nome" class="form-control" placeholder="Ex: Arroz 5kg">
                            </div>
                            <div class="col-12 col-md-2">
                                <label class="form-label small text-muted">Qtd.</label>
                                <input type="number" id="quantidade" class="form-control" placeholder="0" min="1">
                            </div>
                            <div class="col-12 col-md-3">
                                <label class="form-label small text-muted">Preço Unit. (R$)</label>
                                <input type="number" id="preco" class="form-control" placeholder="0.00" step="0.01" min="0">
                            </div>
                            <div class="col-12 col-md-2 d-flex align-items-end">
                                <button class="btn btn-primary w-100" id="btnAdicionar">
                                    <i class="fa-solid fa-check"></i> Inserir
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                
                <h6 class="text-secondary mb-3"><i class="fa-solid fa-basket-shopping me-2"></i>Carrinho Atual</h6>
                <ul id="listaProdutos" class="list-group shadow-sm mb-2"></ul>
                
                <div class="d-flex justify-content-end mt-3 border-top pt-2">
                    <h5 class="mb-0">Total: <span id="totalCarrinho" class="text-success fw-bold">R$ 0,00</span></h5>
                </div>
            </div>
            <div class="modal-footer bg-white">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-success px-4" id="btnFinalizar">
                    <i class="fa-solid fa-money-bill-wave me-2"></i> Finalizar Compra
                </button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalVerItens" tabindex="-1" aria-labelledby="modalVerItensLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content shadow-lg border-0">
            <div class="modal-header bg-dark text-white">
                <h5 class="modal-title" id="modalVerItensLabel"><i class="fa-solid fa-receipt me-2"></i>Cupom da Compra </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Fechar"></button>
                <input type="hidden" id="detalheIdCompra">
            </div>
            <div class="modal-body bg-light">
                <p class="text-muted mb-3"><i class="fa-solid fa-calendar-days me-1"></i> Data: <span id="detalheDataCompra" class="text-dark fw-semibold"></span></p>
                
                <div class="card border-0 shadow-sm mb-3">
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th class="ps-3">Produto</th>
                                        <th class="text-center">Quantidade</th>
                                        <th class="text-end">Preço Unitário</th>
                                        <th class="text-end pe-3">Subtotal Item</th>
                                    </tr>
                                </thead>
                                <tbody id="tabelaItensCompraDetalhe"></tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="card border-0 shadow-sm">
                    <div class="card-body bg-white rounded-3">
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Valor Original:</span>
                            <span id="detalheValorOriginal" class="fw-semibold">R$ 0,00</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2 text-danger" id="divDetalheDesconto">
                            <span>Desconto Aplicado (10%):</span>
                            <span id="detalheDesconto">- R$ 0,00</span>
                        </div>
                        <hr class="my-2">
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="fs-5 fw-bold text-dark">Valor Final:</span>
                            <span id="detalheValorFinal" class="fs-4 fw-bold text-success">R$ 0,00</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>