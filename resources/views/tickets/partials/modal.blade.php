<div class="modal fade" id="editModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <form id="ticketForm">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Detalles del Ticket</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="ticket_id">
                    <div class="mb-2">
                        <label>Equipo</label>
                        <input type="text" id="equipo" class="form-control" readonly>
                    </div>
                    <div class="mb-2">
                        <label>Sede</label>
                        <input type="text" id="sede" class="form-control" readonly>
                    </div>
                    <div class="mb-2">
                        <label>Problema</label>
                        <select id="descripcionSelect" class="form-select" name="descripcion" required disabled>
                            <option value="" disabled selected>Seleccionar descripción</option>
                            <option value="La computadora no enciende">La computadora no enciende</option>
                            <option value="La pantalla está en negro">La pantalla está en negro</option>
                            <option value="Problemas de conexión a Internet">Problemas de conexión a Internet</option>
                            <option value="El software no se abre">El software no se abre</option>
                            <option value="La impresora no imprime">La impresora no imprime</option>
                            <option value="Se acabó el toner">Se acabó el toner</option>
                            <option value="No hay tinta negra">No hay tinta negra</option>
                            <option value="No hay tinta azul">No hay tinta azul</option>
                            <option value="No hay tinta roja">No hay tinta roja</option>
                            <option value="No hay tinta amarilla">No hay tinta amarilla</option>
                            <option value="El teclado no responde">El teclado no responde</option>
                            <option value="Problemas con el sistema operativo">Problemas con el sistema operativo</option>
                            <option value="El mouse no funciona">El mouse no funciona</option>
                            <option value="La computadora se reinicia sola">La computadora se reinicia sola</option>
                            <option value="Otro">Mi problema no aparece aquí</option>
                        </select>
                    </div>
                    <div class="mb-2" id="otroProblemaDiv" style="display: none;">
                        <label for="otroProblema" class="form-label">Especifica tu problema</label>
                        <input type="text" id="otroProblema" name="otroProblema" class="form-control" placeholder="Describe tu problema...">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger d-none" id="btnEliminar">Eliminar</button>
                    <button type="button" class="btn btn-primary d-none" id="btnGuardar">Guardar</button>
                    <button type="button" class="btn btn-warning" id="btnEditar">Editar descripción</button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Modal de éxito -->
<div class="modal fade" id="successModal" tabindex="-1" aria-labelledby="successModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="successModalLabel">Éxito</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <i class="bi bi-check-circle text-success"></i> Su ticket ha sido modificado correctamente.
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" id="okButton">Ok</button>
            </div>
        </div>
    </div>
</div>
