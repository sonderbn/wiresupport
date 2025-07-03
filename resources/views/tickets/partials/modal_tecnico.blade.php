<div class="modal fade" id="ticketModal" tabindex="-1" aria-labelledby="ticketModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="ticketModalLabel">Detalles del Ticket</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p><strong>Sede:</strong> <span id="modalClinica"></span></p>
                <p><strong>Equipo:</strong> <span id="modalEquipo"></span></p>
                <p><strong>Creado el:</strong> <span id="modalFechaCreacion"></span></p>
                <p><strong>Descripción:</strong> <span id="modalDescripcion"></span></p>
                <div class="mb-3">
                    <label for="solucionDescripcion" class="form-label">Descripción de la Solución</label>
                    <textarea id="solucionDescripcion" class="form-control" rows="3"
                        placeholder="Escribe alguna recomendación"></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-success" id="btnMarcarSolucionado">Marcar como
                    Solucionado</button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
            </div>
        </div>

    </div>
</div>
