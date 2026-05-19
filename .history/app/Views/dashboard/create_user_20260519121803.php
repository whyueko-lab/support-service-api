<?= $this->extend('layouts/dashboard_layout') ?>

<?= $this->section('content') ?>

<!-- Custom Style Khusus untuk Halaman Form Management User -->
<style>
    .form-container {
        max-width: 850px;
        background: white;
        border-radius: 16px;
        padding: 32px;
        border: 1px solid #e2e8f0;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.02), 0 2px 4px -1px rgba(0, 0, 0, 0.02);
    }

    .form-container h3 {
        font-size: 20px;
        font-weight: 700;
        color: #0f172a;
        letter-spacing: -0.5px;
        margin-bottom: 6px;
    }

    .form-container p {
        font-size: 14px;
        color: #64748b;
        margin-bottom: 28px;
    }

    /* Form Grid untuk efisiensi ruang */
    .form-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 20px;
    }

    @media (max-width: 640px) {
        .form-grid {
            grid-template-columns: 1fr;
        }
    }

    .form-group {
        margin-bottom: 20px;
    }

    .form-group.full-width {
        grid-column: span 2;
    }

    @media (max-width: 640px) {
        .form-group.full-width {
            grid-column: span 1;
        }
    }

    .form-group label {
        display: block;
        margin-bottom: 8px;
        color: #475569;
        font-weight: 600;
        font-size: 13.5px;
        letter-spacing: 0.1px;
    }

    /* Container Input dengan Ikon */
    .input-group {
        position: relative;
        display: flex;
        align-items: center;
    }

    .input-group .input-icon {
        position: absolute;
        left: 16px;
        color: #94a3b8;
        pointer-events: none;
        transition: color 0.2s ease;
        font-size: 18px;
    }

    /* Reset & Styling Input, Select */
    .input-group input, .input-group select {
        width: 100%;
        padding: 12px 16px 12px 46px;
        border: 1.5px solid #e2e8f0;
        border-radius: 12px;
        font-size: 14px;
        color: #0f172a;
        background-color: #f8fafc;
        outline: none;
        transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .input-group select {
        appearance: none;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%2394a3b8' stroke-width='2'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' d='M19 9l-7 7-7-7'/%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-position: right 16px center;
        background-size: 18px;
        padding-right: 44px;
        cursor: pointer;
    }

    /* Interaksi Fokus */
    .input-group input:focus, .input-group select:focus {
        background-color: #ffffff;
        border-color: #4f46e5;
        box-shadow: 0 0 0 4px rgba(79, 70, 229, 0.1);
    }

    .input-group input:focus ~ .input-icon, .input-group select:focus ~ .input-icon {
        color: #4f46e5;
    }

    /* Tombol Intip Password */
    .toggle-password {
        position: absolute;
        right: 16px;
        color: #94a3b8;
        cursor: pointer;
        background: none;
        border: none;
        padding: 0;
        display: flex;
        align-items: center;
        font-size: 18px;
    }
    
    .toggle-password:hover {
        color: #4f46e5;
    }

    /* Container Tombol Aksi */
    .form-actions {
        display: flex;
        align-items: center;
        gap: 12px;
        margin-top: 24px;
        border-top: 1px solid #f1f5f9;
        padding-top: 20px;
    }

    .btn-submit {
        background: linear-gradient(135deg, #4f46e5 0%, #3b82f6 100%);
        color: white;
        border: none;
        padding: 12px 24px;
        border-radius: 12px;
        font-size: 14px;
        font-weight: 600;
        cursor: pointer;
        box-shadow: 0 4px 12px rgba(79, 70, 229, 0.15);
        transition: all 0.2s ease;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }

    .btn-submit:hover {
        transform: translateY(-1px);
        box-shadow: 0 6px 16px rgba(79, 70, 229, 0.25);
        filter: brightness(1.05);
    }

    .btn-cancel {
        background: #ffffff;
        color: #64748b;
        border: 1.5px solid #e2e8f0;
        padding: 11px 24px;
        border-radius: 12px;
        font-size: 14px;
        font-weight: 600;
        text-decoration: none;
        transition: all 0.2s ease;
        display: inline-flex;
        align-items: center;
    }

    .btn-cancel:hover {
        background: #f8fafc;
        color: #334155;
        border-color: #cbd5e1;
    }
</style>

<div class="form-container">
    <h3>Tambah User Baru</h3>
    <p>Gunakan form ini untuk menambahkan kredensial customer, teknisi, atau administrator baru ke dalam sistem.</p>

    <form action="/dashboard/users/store" method="post">
        <div class="form-grid">
            
            <!-- Input Nama -->
            <div class="form-group">
                <label>Nama Lengkap</label>
                <div class="input-group">
                    <i class="uil uil-user input-icon"></i>
                    <input type="text" name="nama" placeholder="Masukkan nama user" required>
                </div>
            </div>

            <!-- Input Role -->
            <div class="form-group">
                <label>Hak Akses / Role</label>
                <div class="input-group">
                    <i class="uil uil-shield-check input-icon"></i>
                    <select name="role" required>
                        <option value="">-- Pilih Role Akses --</option>
                        <option value="customer">Customer (Pelanggan)</option>
                        <option value="teknisi">Teknisi (Lap. Lapangan)</option>
                        <option value="admin">Admin (Staf Operasional)</option>
                    </select>
                </div>
            </div>

            <!-- Input Email -->
            <div class="form-group">
                <label>Alamat Email</label>
                <div class="input-group">
                    <i class="uil uil-envelope input-icon"></i>
                    <input type="email" name="email" placeholder="contoh@support.com" required>
                </div>
            </div>

            <!-- Input Password -->
            <div class="form-group">
                <label>Kata Sandi (Password)</label>
                <div class="input-group">
                    <i class="uil uil-lock input-icon"></i>
                    <input type="password" id="password" name="password" placeholder="Buat password aman" required>
                    
                    <button type="button" class="toggle-password" onclick="togglePassword()">
                        <i id="eye-icon" class="uil uil-eye"></i>
                    </button>
                </div>
            </div>

        </div>

        <!-- Tombol Konfirmasi -->
        <div class="form-actions">
            <button type="submit" class="btn-submit">
                <i class="uil uil-user-plus"></i> Simpan User Baru
            </button>
            <a href="/dashboard/users" class="btn-cancel">Kembali</a>
        </div>
    </form>
</div>

<!-- JavaScript Interaktif untuk Fitur Intip Password -->
<script>
    function togglePassword() {
        const passwordInput = document.getElementById('password');
        const eyeIcon = document.getElementById('eye-icon');
        
        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            eyeIcon.classList.remove('uil-eye');
            eyeIcon.classList.add('uil-eye-slash');
        } else {
            passwordInput.type = 'password';
            eyeIcon.classList.remove('uil-eye-slash');
            eyeIcon.classList.add('uil-eye');
        }
    }
</script>

<?= $this->endSection() ?>