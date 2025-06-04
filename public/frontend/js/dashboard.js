// public/frontend/js/dashboard.js
(async function () {
  const token = localStorage.getItem('token');
  const user = JSON.parse(localStorage.getItem('user'));
  if (!token || !user) {
    return window.location.href = 'index.html';
  }

  const taskListDiv = document.getElementById('task-list');
  try {
    const res = await fetch('/api/tasks', {
      headers: {
        Authorization: `Bearer ${token}`
      }
    });
    const tasks = await res.json();

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
    taskListDiv.innerHTML = '<p class="text-danger">Gagal mengambil data task.</p>';
  }
})();