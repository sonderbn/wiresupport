<div class="modal fade" id="nuevoTicketModal" tabindex="-1" aria-labelledby="nuevoTicketModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="nuevoTicketModalLabel">Crear Nuevo Ticket</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="nuevoTicketForm">
                    @csrf
                    <div class="mb-3">
                        <label for="sedeSelect" class="form-label">Clínica</label>
                        <select class="form-select" id="sedeSelect" name="id_clinica" required>
                            <option value="" disabled selected>Seleccionar clínica</option>
                            @foreach ($clinicas as $clinica)
                                <option value="{{ $clinica->Id_clinica }}">{{ $clinica->nombre }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="equipoSearch" class="form-label">Equipo</label>
                        <input type="text" id="equipoSearch" class="form-control" placeholder="Buscar equipo..." autocomplete="off">
                        <ul id="equipoResults" class="list-group" style="display: none;"></ul>
                        <input type="hidden" id="id_equipo" name="id_equipo">
                    </div>

                    <div class="mb-3">
                        <label for="descripcionSelect" class="form-label">Descripción</label>
                        <select class="form-select" id="descripcionSelect" name="descripcion" required>
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
                        </select>
                    </div>

                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Guardar</button>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="successModalNew" tabindex="-1" aria-labelledby="successModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="successModalLabel">Éxito</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <i class="bi bi-check-circle text-success"></i> Su ticket se registró correctamente, pronto iremos a resolverlo.
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" id="okButtonNew">Ok</button>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const descripcionSelect = document.getElementById('descripcionSelect');
        const otroProblemaDiv = document.getElementById('otroProblemaDiv');

        descripcionSelect.addEventListener('change', function() {
            if (this.value === 'Otro') {
                otroProblemaDiv.style.display = 'block';
            } else {
                otroProblemaDiv.style.display = 'none';
                document.getElementById('otroProblema').value = '';
            }
        });
    });
    document.addEventListener('DOMContentLoaded', function () {
    const sedeSelect = document.getElementById('sedeSelect');
    const equipoSearch = document.getElementById('equipoSearch');
    const equipoResults = document.getElementById('equipoResults');
    const equipoIdField = document.getElementById('id_equipo');
    let equipos = [];

    sedeSelect.addEventListener('change', function () {
        const sedeId = this.value;

        if (sedeId) {
            fetch(`/equipos/sede/${sedeId}`)
                .then(res => res.json())
                .then(data => {
                    equipos = data.equipos.filter(equipo => equipo.estado === 'activo'); 
                    equipoResults.innerHTML = '';
                })
                .catch(err => {
                    console.error('Error al cargar equipos:', err);
                });
        }
    });

    equipoSearch.addEventListener('input', function () {
        const query = equipoSearch.value.toLowerCase();
        const filteredEquipos = equipos.filter(equipo => equipo.nombre_equipo.toLowerCase().includes(query));

        equipoResults.innerHTML = '';
        if (query) {
            filteredEquipos.forEach(equipo => {
                const li = document.createElement('li');
                li.classList.add('list-group-item');
                li.textContent = equipo.nombre_equipo;
                li.addEventListener('click', function () {
                    equipoSearch.value = equipo.nombre_equipo;
                    equipoResults.style.display = 'none';
                    equipoIdField.value = equipo.Id_equipo;
                });
                equipoResults.appendChild(li);
            });
            equipoResults.style.display = 'block';
        } else {
            equipoResults.style.display = 'none';
        }
    });
});

</script>
