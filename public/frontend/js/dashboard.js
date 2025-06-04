(async function () {
  const token = localStorage.getItem('token');
  const user = JSON.parse(localStorage.getItem('user'));
  if (!token || !user) {
    return window.location.href = 'index.html';
  }

  const taskListDiv = document.getElementById('task-list');
  const assignedSelect = document.getElementById('assigned_to');
  const taskForm = document.getElementById('task-form');
  const msgDiv = document.getElementById('task-msg');

  // Ambil daftar task
  try {
    const res = await fetch('/api/tasks', {
      headers: {
        Authorization: `Bearer ${token}`,
        // Accept: 'application/json'
      }
    });

    if (!res.ok) {
      const text = await res.text();
      console.error('TASK API ERROR:', text);

      if (res.status === 401 || res.status === 403) {
        localStorage.clear();
        return window.location.href = 'index.html';
      }

      if (taskListDiv) {
        taskListDiv.innerHTML = `<p class="text-danger">Gagal mengambil task: ${res.status}</p>`;
      }
      return;
    }

    const tasks = await res.json();
    console.log('Tasks:', tasks);

    taskListDiv.innerHTML = tasks.map(task => `
      <div class="card mb-2">
        <div class="card-body">
          <h5>${task.title}</h5>
          <p>${task.description}</p>
          <span class="badge bg-${task.status === 'done' ? 'success' : task.status === 'in_progress' ? 'warning' : 'secondary'}">
            ${task.status}
          </span>
        </div>
      </div>
    `).join('');

  } catch (e) {
    console.error('TASK FETCH FAIL:', e);
    if (taskListDiv) {
      taskListDiv.innerHTML = '<p class="text-danger">Gagal mengambil data task (network error).</p>';
    }
  }

  // Ambil daftar user
  async function loadUsers() {
    try {
      const res = await fetch('/api/users', {
        headers: { Authorization: `Bearer ${token}`,
          // Accept: 'application/json'
         }
      });

      if (!res.ok) {
        const text = await res.text();
        console.error('USER API ERROR:', text);

        if (res.status === 401 || res.status === 403) {
          localStorage.clear();
          return window.location.href = 'index.html';
        }

        return;
      }

      const users = await res.json();
      console.log('Users:', users);

      users.forEach(u => {
        if (user.role === 'manager' && u.role !== 'staff') return;

        const option = document.createElement('option');
        option.value = u.id;
        option.textContent = `${u.name} (${u.role})`;
        assignedSelect.appendChild(option);
      });
    } catch (e) {
      console.error('USER FETCH FAIL:', e);
    }
  }
  await loadUsers();

  // Submit Task
  taskForm.addEventListener('submit', async e => {
    e.preventDefault();
    msgDiv.textContent = '';

    const body = {
      title: document.getElementById('title').value,
      description: document.getElementById('description').value,
      assigned_to: assignedSelect.value,
      due_date: document.getElementById('due_date').value,
      status: 'pending'
    };

    const res = await fetch('/api/tasks', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        Authorization: `Bearer ${token}`
      },
      body: JSON.stringify(body)
    });

    if (res.ok) {
      msgDiv.innerHTML = '<span class="text-success">Task berhasil ditambahkan!</span>';
      taskForm.reset();
    } else {
      let message = 'Gagal menambahkan task';
      try {
        const err = await res.json();
        message = err.message || message;
      } catch (e) {
        console.error('POST TASK ERROR (non-JSON):', e);
      }
      msgDiv.innerHTML = `<span class="text-danger">${message}</span>`;
    }
  });

  // Logout
  document.getElementById('logout-btn').addEventListener('click', () => {
    localStorage.removeItem('token');
    localStorage.removeItem('user');
    window.location.href = 'index.html';
  });

})();
