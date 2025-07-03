@php
    $isEdit = isset($equipo);
@endphp

<div class="col-md-6">
    <label>Nombre del Equipo</label>
    <input type="text" name="nombre_equipo" class="form-control"
        value="{{ $isEdit ? $equipo->nombre_equipo : old('nombre_equipo') }}" required>
</div>

<div class="col-md-6">
    <label>Sede</label>
    <select name="Id_clinica" class="form-select" @if (!empty($readonly)) disabled @endif required>
        <option value="">-- Selecciona una sede --</option>
        @foreach ($clinicas as $clinica)
            <option value="{{ $clinica->Id_clinica }}"
                {{ $isEdit && $equipo->Id_clinica == $clinica->Id_clinica ? 'selected' : '' }}>
                {{ $clinica->nombre }}
            </option>
        @endforeach
    </select>
</div>

<div class="col-md-6">
    <label>Modelo</label>
    <input type="text" name="modelo" class="form-control" value="{{ $isEdit ? $equipo->modelo : old('modelo') }}"
        @if (!empty($readonly)) readonly @endif required>
</div>

<div class="col-md-6">
    <label>Número de Serie</label>
    <input type="text" name="numero_serie" class="form-control @error('numero_serie') is-invalid @enderror"
        value="{{ $isEdit ? $equipo->numero_serie : old('numero_serie') }}"
        @if (!empty($readonly)) readonly @endif required>
    @error('numero_serie')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="col-md-4">
    <label>Procesador</label>
    <input type="text" name="procesador" class="form-control"
        value="{{ $isEdit ? $equipo->procesador : old('procesador') }}"
        @if (!empty($readonly)) readonly @endif>
</div>

<div class="col-md-4">
    <label>Memoria RAM</label>
    <input type="text" name="memoria_ram" class="form-control"
        value="{{ $isEdit ? $equipo->memoria_ram : old('memoria_ram') }}"
        @if (!empty($readonly)) readonly @endif>
</div>

<div class="col-md-4">
    <label>Disco Duro</label>
    <input type="text" name="disco_duro" class="form-control"
        value="{{ $isEdit ? $equipo->disco_duro : old('disco_duro') }}"
        @if (!empty($readonly)) readonly @endif>
</div>

<div class="col-md-6">
    <label>Tipo</label>
    <select name="tipo" class="form-select" @if (!empty($readonly)) disabled @endif required>
        <option value="PC" {{ ($isEdit && $equipo->tipo == 'PC') || !$isEdit ? 'selected' : '' }}>PC</option>
    </select>
</div>

<div class="col-md-6">
    <label>Estado</label>
    <select name="estado" class="form-select" @if (!empty($readonly)) disabled @endif required>
        <option value="activo" {{ ($isEdit && $equipo->estado == 'activo') || !$isEdit ? 'selected' : '' }}>Activo
        </option>
        <option value="en reparacion" {{ $isEdit && $equipo->estado == 'en reparacion' ? 'selected' : '' }}>En
            reparación</option>
    </select>
</div>
