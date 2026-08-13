@extends('admin.layout')

@section('content')
<div style="display: flex; justify-content: center; align-items: center; min-height: 80vh;">
    <div class="glass-card" style="padding: 50px 40px; width: 100%; max-width: 420px; text-align: center;">
        <h2 style="margin-bottom: 5px; font-weight: 800; color: var(--color-text);">Masuk Panel Admin</h2>
        <p style="color: #ccc; font-size: 0.95rem; margin-bottom: 30px;">Silakan otentikasi untuk mengelola sistem pendaftaran lomba.</p>

        @if(session('error'))
            <div style="background: rgba(220, 53, 69, 0.2); color: #ff6b6b; padding: 12px; border-radius: 8px; margin-bottom: 20px; border: 1px solid rgba(220, 53, 69, 0.4); font-size: 0.9rem;">
                {{ session('error') }}
            </div>
        @endif

        <form action="{{ url('/admin/login') }}" method="POST">
            @csrf
            <div class="form-group" style="text-align: left; margin-bottom: 20px;">
                <label style="display: block; color: var(--color-text); opacity: 0.8; font-size: 0.85rem; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 8px;">Username</label>
                <input type="text" name="username" class="form-control" style="background: var(--glass-bg); border: 1px solid var(--glass-border); color: var(--color-text); padding: 12px 15px; border-radius: 8px; font-size: 1rem;" required autofocus>
            </div>
            
            <div class="form-group" style="text-align: left; margin-bottom: 30px;">
                <label style="display: block; color: var(--color-text); opacity: 0.8; font-size: 0.85rem; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 8px;">Password</label>
                <input type="password" name="password" class="form-control" style="background: var(--glass-bg); border: 1px solid var(--glass-border); color: var(--color-text); padding: 12px 15px; border-radius: 8px; font-size: 1rem;" required>
            </div>

            <button type="submit" class="btn" style="width: 100%; background: var(--color-primary); color: var(--color-text); padding: 15px; border-radius: 8px; font-weight: 700; font-size: 1.05rem; letter-spacing: 0.5px; border: none; box-shadow: 0 10px 20px rgba(255, 71, 71, 0.3); transition: all 0.3s ease;">
                LOGIN SEKARANG
            </button>
        </form>
    </div>
</div>
@endsection

