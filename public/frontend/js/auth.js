// public/js/auth.js
document.getElementById('login-form').addEventListener('submit', async function (e) {
  e.preventDefault();
  
  const email = document.getElementById('email').value;
  const password = document.getElementById('password').value;
  const errorDiv = document.getElementById('error');

  try {
    const res = await fetch('/api/login', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({ email, password })
    });

    const data = await res.json();

    // if (!res.ok) {
    //   errorDiv.textContent = data.message || 'Login gagal';
    //   return;
    // }

    if (!res.ok) {
        const err = await res.json();
        console.error('Login error:', err);
        errorDiv.textContent = err.message || 'Login gagal';
        return;
    }

    // Simpan token dan user
    localStorage.setItem('token', data.token);
    localStorage.setItem('user', JSON.stringify(data.user));

    window.location.href = 'dashboard.html';

  } catch (err) {
    errorDiv.textContent = 'Terjadi kesalahan koneksi';
  }
});
