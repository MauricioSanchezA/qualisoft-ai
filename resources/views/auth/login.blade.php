@extends('layouts.guest')

@section('title', 'Iniciar sesión | ' . config('app.name'))

@section('content')

<style>
    .background {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: linear-gradient(-45deg, #1e3c72, #2a5298, #1e3c72, #2a5298);
        background-size: 400% 400%;
        animation: gradientBG 15s ease infinite;
        z-index: -1;
    }

    @keyframes gradientBG {
        0% { background-position: 0% 50%; }
        50% { background-position: 100% 50%; }
        100% { background-position: 0% 50%; }
    }

    .login-card {
        background-color: white;
        border-radius: 1rem;
        padding: 2rem;
        box-shadow: 0 10px 25px rgba(0,0,0,0.2);
        width: 100%;
        max-width: 400px;
        margin: 5rem auto;
        position: relative;
        z-index: 1;
    }

    .login-card h2 {
        font-size: 2rem;
        font-weight: 600;
        margin-bottom: 1.5rem;
        text-align: center;
        color: #1e3c72;
    }

    .login-card label {
        font-weight: 500;
        margin-bottom: 0.5rem;
        display: block;
        color: #333;
    }

    .login-card input {
        width: 100%;
        padding: 0.75rem;
        border: 1px solid #ccc;
        border-radius: 0.5rem;
        margin-bottom: 1rem;
    }

    .login-card button {
        background-color: #1e3c72;
        color: white;
        padding: 0.75rem 1.5rem;
        border: none;
        border-radius: 0.5rem;
        font-weight: bold;
        cursor: pointer;
        transition: background-color 0.3s ease;
    }

    .login-card button:hover {
        background-color: #2a5298;
    }

    .login-card a {
        font-size: 0.9rem;
        color: #555;
        text-decoration: underline;
    }

    .footer {
        position: absolute;
        bottom: 0;
        width: 100%;
        overflow: hidden;
        white-space: nowrap;
        background-color: rgba(0,0,0,0.2);
        color: white;
        font-size: 1rem;
        padding: 0.5rem;
    }

    .footer-text {
        display: inline-block;
        padding-left: 100%;
        animation: scroll-left 20s linear infinite;
    }

    @keyframes scroll-left {
        0% { transform: translateX(0); }
        100% { transform: translateX(-100%); }
    }

    .input-error {
    border: 2px solid #e74c3c !important;
    animation: shake 0.3s ease;
}

.input-success {
    border: 2px solid #2ecc71 !important;
}

@keyframes shake {
    0% { transform: translateX(0); }
    25% { transform: translateX(-5px); }
    50% { transform: translateX(5px); }
    75% { transform: translateX(-5px); }
    100% { transform: translateX(0); }
}

.error-message {
    color: #e74c3c;
    font-size: 0.9rem;
    margin-top: -0.5rem;
    margin-bottom: 0.5rem;
}

</style>

<div class="background"></div>

<div class="login-card" data-aos="fade-up" data-aos-duration="1000">
    <h2>Iniciar Sesión</h2>

    <form method="POST" action="{{ route('login.post') }}">
        @csrf

        <div>
            <label for="email">Correo electrónico</label>
            <input id="email" name="email" type="email" required autofocus />
        </div>

        <div>
            <label for="password">Contraseña</label>
            <input id="password" name="password" type="password" required />
        </div>

        <div class="flex items-center justify-between mt-4">
            <button type="submit">Ingresar</button>
            <a href="{{ route('welcome') }}">Volver</a>
        </div>
    </form>
</div>

<div class="footer" data-aos="fade-up" data-aos-delay="800">
    <div class="footer-text">• Desarrollado por Mauricio Sánchez Abella • Todos los derechos reservados - 2025 • Qualisoft AI • </div>
</div>
<script>
document.addEventListener('DOMContentLoaded', () => {
    const form = document.querySelector('form');
    const email = document.getElementById('email');
    const password = document.getElementById('password');

    form.addEventListener('submit', (e) => {
        let valid = true;

        // Limpia clases previas
        email.classList.remove('input-error', 'input-success');
        password.classList.remove('input-error', 'input-success');

        // Validación básica
        if (!email.value.includes('@')) {
            email.classList.add('input-error');
            valid = false;
        } else {
            email.classList.add('input-success');
        }

        if (password.value.length < 6) {
            password.classList.add('input-error');
            valid = false;
        } else {
            password.classList.add('input-success');
        }

        if (!valid) {
            e.preventDefault(); // Evita envío si hay errores
        }
    });
});
</script>

@endsection
