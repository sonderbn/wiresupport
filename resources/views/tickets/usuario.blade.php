@extends('layouts.nav')

@section('content')
    <div class="container py-4">
        <div class="d-flex justify-content-between align-items-center">
            <h5 class="mb-3">Mis Tickets</h5>
            <button class="btn btn-danger" id="btnNuevoTicket" data-bs-toggle="modal" data-bs-target="#nuevoTicketModal">
                <i class="bi bi-plus-circle"></i> Crear nuevo ticket
            </button>
        </div>

        <h6 class="mb-2">Tickets Pendientes</h6>

        <h6 class="mt-3">PC</h6>
        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-4 g-3">
            @foreach ($ticketsPendientes as $ticket)
                @if ($ticket->equipo->tipo == 'PC')
                    @include('tickets.partials.card', ['ticket' => $ticket])
                @endif
            @endforeach
        </div>
        {{ $ticketsPendientes->links() }}

        <h6 class="mt-3">Impresora</h6>
        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-4 g-3">
            @foreach ($ticketsPendientes as $ticket)
                @if ($ticket->equipo->tipo == 'Impresora')
                    @include('tickets.partials.card', ['ticket' => $ticket])
                @endif
            @endforeach
        </div>
        {{ $ticketsPendientes->links() }}

        <h6 class="mt-5 mb-2">Tickets Terminados</h6>

        <h6 class="mt-3">PC</h6>
        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-4 g-3">
            @foreach ($ticketsTerminados as $ticket)
                @if ($ticket->equipo->tipo == 'PC')
                    @include('tickets.partials.card', ['ticket' => $ticket])
                @endif
            @endforeach
        </div>
        {{ $ticketsTerminados->links() }}

        <h6 class="mt-3">Impresora</h6>
        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-4 g-3">
            @foreach ($ticketsTerminados as $ticket)
                @if ($ticket->equipo->tipo == 'Impresora')
                    @include('tickets.partials.card', ['ticket' => $ticket])
                @endif
            @endforeach
        </div>
        {{ $ticketsTerminados->links() }}
    </div>

    @include('tickets.partials.modal')
    @include('tickets.partials.new_modal')
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            window.verTicketUsuario = function (id) {
                console.log("Ver ticket con ID:", id);
                fetch(`/tickets/${id}`)
                    .then(res => res.json())
                    .then(ticket => {
                        document.getElementById('ticket_id').value = ticket.Id_tickets;
                        document.getElementById('equipo').value = ticket.equipo?.nombre_equipo ?? 'No asignado';
                        document.getElementById('sede').value = ticket.clinica?.nombre ?? 'No asignada';

                        const descripcionSelect = document.getElementById('descripcionSelect');
                        descripcionSelect.value = ticket.descripcion; 

                        const otroProblemaDiv = document.getElementById('otroProblemaDiv');
                        const otroProblemaInput = document.getElementById('otroProblema');


                        if (ticket.descripcion === 'Otro') {
                            otroProblemaDiv.style.display = 'block';
                            otroProblemaInput.value = ticket.otroProblema || ''; 
                        } else {
                            otroProblemaDiv.style.display = 'none';
                        }

                        const isEditable = ticket.estado === 'pendiente';

                        descripcionSelect.disabled = true; 
                        otroProblemaInput.readOnly = true; 
                        document.getElementById('btnEditar').classList.toggle('d-none', !isEditable);
                        document.getElementById('btnEliminar').classList.add('d-none');
                        document.getElementById('btnGuardar').classList.add('d-none');

                        new bootstrap.Modal(document.getElementById('editModal')).show();
                    });
            }

            document.getElementById('btnEditar').addEventListener('click', function () {
                const descripcionSelect = document.getElementById('descripcionSelect');
                const otroProblemaInput = document.getElementById('otroProblema');

                descripcionSelect.disabled = false; 
                otroProblemaInput.readOnly = false;
                this.classList.add('d-none');
                document.getElementById('btnGuardar').classList.remove('d-none');
                document.getElementById('btnEliminar').classList.remove('d-none');
            });

            document.getElementById('btnGuardar').addEventListener('click', function () {
                const id = document.getElementById('ticket_id').value;
                const descripcionSelect = document.getElementById('descripcionSelect');
                const otroProblemaInput = document.getElementById('otroProblema');

                const descripcion = descripcionSelect.value === 'Otro'
                    ? otroProblemaInput.value
                    : descripcionSelect.value;

                fetch(`/tickets/${id}`, {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ descripcion })
                })
                    .then(res => {
                        if (!res.ok) {
                            throw new Error('Error en la respuesta de la red');
                        }
                        return res.json();
                    })
                    .then(data => {
                        if (data.success) {
                            const editModal = new bootstrap.Modal(document.getElementById('editModal'));
                            editModal.hide();

                            const successModal = new bootstrap.Modal(document.getElementById('successModal'));
                            successModal.show();
                        } else {
                            alert('Hubo un error al modificar el ticket');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('Hubo un error al modificar el ticket: ' + error.message);
                    });
            });

            document.getElementById('okButton').addEventListener('click', function () {
                const successModal = new bootstrap.Modal(document.getElementById('successModal'));
                successModal.hide();
                location.reload();
            });

            document.getElementById('btnEliminar').addEventListener('click', function () {
                const id = document.getElementById('ticket_id').value;

                if (confirm('¿Estás seguro de que deseas eliminar este ticket?')) {
                    fetch(`/tickets/${id}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        }
                    }).then(() => location.reload());
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

            document.getElementById('nuevoTicketForm').addEventListener('submit', function (event) {
                event.preventDefault();

                if (!equipoIdField.value) {
                    alert('Debe seleccionar un equipo');
                    return;
                }

                const formData = new FormData(this);
                fetch('/tickets', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    },
                    body: formData
                })
                    .then(res => {
                        if (!res.ok) {
                            throw new Error('Error en la respuesta de la red');
                        }
                        return res.json();
                    })
                    .then(data => {
                        if (data.success) {
                            const nuevoTicketModalEl = document.getElementById('nuevoTicketModal');
                            const nuevoTicketModal = bootstrap.Modal.getInstance(nuevoTicketModalEl) || new bootstrap.Modal(nuevoTicketModalEl);
                            nuevoTicketModal.hide();

                            const successModalEl = document.getElementById('successModalNew');
                            const successModal = bootstrap.Modal.getInstance(successModalEl) || new bootstrap.Modal(successModalEl);
                            successModal.show();
                        } else {
                            alert('Hubo un error al crear el ticket');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('Hubo un error al crear el ticket: ' + error.message);
                    });
            });

            document.getElementById('okButtonNew').addEventListener('click', function () {
                const successModalEl = document.getElementById('successModalNew');
                const successModal = bootstrap.Modal.getInstance(successModalEl);
                successModal.hide();
                location.reload();
            });
        });

        document.addEventListener('DOMContentLoaded', function () {
            const descripcionSelect = document.getElementById('descripcionSelect');
            const otroProblemaDiv = document.getElementById('otroProblemaDiv');
            const otroProblemaInput = document.getElementById('otroProblema');

            descripcionSelect.addEventListener('change', function () {
                if (this.value === 'Otro') {
                    otroProblemaDiv.style.display = 'block'; 
                } else {
                    otroProblemaDiv.style.display = 'none'; 
                    otroProblemaInput.value = ''; 
                }
            });
        });
    </script>
@endpush