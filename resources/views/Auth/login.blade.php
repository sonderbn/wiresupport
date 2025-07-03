@extends('layouts.app')

@section('content')
    <div style="height: 100vh; display: flex;">
        <div style="width: 50%; background-color: white; display: flex; flex-direction: column; justify-content: center; align-items: center;">
            <h1 class="text-black mb-4">WireSupport</h1>
            <p class="text-center" style="font-size: 1.2rem;">Por favor, ingresa tus credenciales</p>
            <div class="col-md-6">
                <form method="POST" action="{{ route('login') }}">
                    @csrf
                    <div class="form-group mb-3">
                        <input type="text" id="usuario" name="usuario" class="form-control form-control-sm border-0 rounded-0 @error('usuario') is-invalid @enderror" required placeholder="Usuario" maxlength="30">
                        @error('usuario')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group mb-3">
                        <input type="password" id="password" name="password" class="form-control form-control-sm border-0 rounded-0 @error('password') is-invalid @enderror" required placeholder="Contraseña" maxlength="20">
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <button type="submit" class="btn" style="background: linear-gradient(to right, #4CAF50, #2196F3); color: white; width: 100%; height: 40px;">Iniciar sesión</button>
                </form>

                @if ($errors->any())
                    <div class="alert alert-danger mt-3">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }} </li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="d-flex justify-content-between mt-3">
                    <span>¿No tienes una cuenta?</span>
                    <a href="{{ route('register.show') }}" class="btn btn-outline-primary btn-sm" style="width: 120px;">Registrate ahora</a>
                </div>
            </div>
        </div>
        <div style="width: 50%; background: linear-gradient(to right, #4CAF50, #2196F3);"></div>
    </div>
@endsection

@push('styles')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" rel="stylesheet">
@endpush

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const inputs = document.querySelectorAll('input[type="text"], input[type="password"]');
            inputs.forEach(input => {
                input.addEventListener('input', function() {
                    if (this.value.length > this.maxLength) {
                        this.value = this.value.slice(0, this.maxLength);
                    }
                });
            });
        });
    </script>
@endpush