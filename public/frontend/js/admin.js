(async function () {
  const token = localStorage.getItem('token');
  const user = JSON.parse(localStorage.getItem('user'));

  // Tampilkan role user di navbar
    const roleSpan = document.getElementById('user-role');
    if (roleSpan && user?.role) {
    roleSpan.textContent = `Login sebagai: ${user.role}`;
    }

    // Logout button sudah di-handle sebelumnya
    document.getElementById('logout-btn')?.addEventListener('click', () => {
    localStorage.clear();
    window.location.href = 'index.html';
    });


  if (!token || !user || user.role !== 'admin') {
    return window.location.href = 'index.html';
  }

  const userList = document.getElementById('user-list');
  const msgDiv = document.getElementById('user-msg');
  const form = document.getElementById('user-form');

  // Ambil daftar user
  async function loadUsers() {
    const res = await fetch('/api/users', {
      headers: { Authorization: `Bearer ${token}` }
    });

    if (!res.ok) {
      const text = await res.text();
      console.error('USER API ERROR:', text);
      userList.innerHTML = `<tr><td colspan="3" class="text-danger">Gagal memuat data user.</td></tr>`;
      return;
    }

    const users = await res.json();
    const activeUsers = users.filter(u => u.status);

    userList.innerHTML = activeUsers.map(u => `
      <tr>
        <td>${u.name}</td>
        <td>${u.email}</td>
        <td>${u.role}</td>
      </tr>
    `).join('');
  }

  await loadUsers();

  // Tambah user baru
  form.addEventListener('submit', async e => {
    e.preventDefault();
    msgDiv.textContent = '';

    const body = {
      name: document.getElementById('name').value,
      email: document.getElementById('email').value,
      password: document.getElementById('password').value,
      role: document.getElementById('role').value,
      status: true
    };

    const res = await fetch('/api/users', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        Authorization: `Bearer ${token}`
      },
      body: JSON.stringify(body)
    });

    if (res.ok) {
      msgDiv.innerHTML = '<span class="text-success">User berhasil ditambahkan</span>';
      form.reset();
      loadUsers();
    } else {
      const err = await res.json();
      msgDiv.innerHTML = `<span class="text-danger">${err.message || 'Gagal menambahkan user'}</span>`;
    }
  });

  // Logout
  document.getElementById('logout-btn').addEventListener('click', () => {
    localStorage.clear();
    window.location.href = 'index.html';
  });

})();
