<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Task Dashboard</title>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary: #6366f1;
            --secondary: #a855f7;
            --bg: #0f172a;
            --card: #1e293b;
            --text: #f8fafc;
            --muted: #94a3b8;
            --success: #10b981;
            --error: #ef4444;
            --warning: #f59e0b;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Outfit', sans-serif;
        }

        body {
            background-color: var(--bg);
            color: var(--text);
            min-height: 100vh;
        }

        /* Auth Screen */
        #auth-screen {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background: radial-gradient(circle at top right, rgba(99, 102, 241, 0.1), transparent),
                        radial-gradient(circle at bottom left, rgba(168, 85, 247, 0.1), transparent);
        }

        .auth-card {
            background: var(--card);
            padding: 40px;
            border-radius: 24px;
            width: 100%;
            max-width: 400px;
            box-shadow: 0 20px 50px rgba(0, 0, 0, 0.3);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .auth-card h2 {
            font-size: 2rem;
            margin-bottom: 24px;
            text-align: center;
            background: linear-gradient(to right, var(--primary), var(--secondary));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        /* Dashboard Layout */
        #app-screen {
            display: none;
            grid-template-columns: 280px 1fr 340px;
            height: 100vh;
        }

        aside {
            background: rgba(15, 23, 42, 0.5);
            border-right: 1px solid rgba(255, 255, 255, 0.05);
            padding: 30px;
            display: flex;
            flex-direction: column;
        }

        main {
            padding: 40px;
            overflow-y: auto;
            background: #0f172a;
        }

        .sidebar-right {
            background: rgba(15, 23, 42, 0.5);
            border-left: 1px solid rgba(255, 255, 255, 0.05);
            padding: 30px;
            overflow-y: auto;
        }

        /* Components */
        .btn {
            padding: 12px 20px;
            border-radius: 12px;
            border: none;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            color: white;
        }

        .btn-primary:hover { opacity: 0.9; transform: scale(1.02); }

        .input-group { margin-bottom: 20px; }
        .input-group label { display: block; margin-bottom: 8px; color: var(--muted); font-size: 0.9rem; }
        .input-group input, .input-group textarea, .input-group select {
            width: 100%;
            padding: 12px;
            background: rgba(15, 23, 42, 0.8);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 12px;
            color: white;
            transition: border-color 0.3s;
        }
        .input-group input:focus { border-color: var(--primary); outline: none; }

        .task-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 20px;
            margin-top: 30px;
        }

        .task-card {
            background: var(--card);
            border-radius: 20px;
            padding: 24px;
            border: 1px solid rgba(255, 255, 255, 0.05);
            position: relative;
            transition: all 0.3s;
        }

        .task-card:hover { transform: translateY(-5px); border-color: var(--primary); }

        .priority-badge {
            font-size: 0.7rem;
            text-transform: uppercase;
            font-weight: 800;
            padding: 4px 10px;
            border-radius: 100px;
            margin-bottom: 15px;
            display: inline-block;
        }

        .priority-high { background: rgba(239, 68, 68, 0.1); color: var(--error); }
        .priority-medium { background: rgba(245, 158, 11, 0.1); color: var(--warning); }
        .priority-low { background: rgba(16, 185, 129, 0.1); color: var(--success); }

        .task-info h3 { margin-bottom: 10px; font-weight: 600; }
        .task-info p { color: var(--muted); font-size: 0.9rem; margin-bottom: 20px; }

        .task-footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-top: 1px solid rgba(255, 255, 255, 0.05);
            padding-top: 15px;
        }

        .task-actions { display: flex; gap: 10px; }
        .task-actions i { cursor: pointer; color: var(--muted); transition: color 0.3s; }
        .task-actions i:hover { color: var(--text); }
        .task-actions i.delete:hover { color: var(--error); }

        .activity-item {
            padding: 15px 0;
            border-bottom: 1px solid rgba(255, 255, 255, 0.05);
        }

        .activity-item .action { font-weight: 600; color: var(--primary); text-transform: capitalize; }
        .activity-item .time { display: block; font-size: 0.75rem; color: var(--muted); margin-top: 4px; }

        /* Modal */
        #modal-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.8);
            display: none;
            justify-content: center;
            align-items: center;
            z-index: 2000;
        }

        #task-modal {
            background: var(--card);
            width: 90%;
            max-width: 500px;
            border-radius: 24px;
            padding: 30px;
        }

        .header-actions {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
        }

        .logo-sm {
            font-weight: 800;
            font-size: 1.5rem;
            background: linear-gradient(to right, var(--primary), var(--secondary));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .filter-group { margin-bottom: 20px; }
        .filter-group .label { color: var(--muted); font-size: 0.8rem; margin-bottom: 10px; display: block; }
        .filter-options { display: flex; flex-wrap: wrap; gap: 8px; }
        .filter-tag {
            padding: 6px 12px;
            border-radius: 8px;
            background: rgba(255, 255, 255, 0.05);
            font-size: 0.85rem;
            cursor: pointer;
            transition: all 0.3s;
        }
        .filter-tag.active { background: var(--primary); color: white; }

        @media (max-width: 1024px) {
            #app-screen { grid-template-columns: 1fr; }
            aside, .sidebar-right { display: none; }
        }
    </style>
</head>
<body>

    <!-- Auth Screen -->
    <div id="auth-screen">
        <div class="auth-card" id="login-form">
            <h2>Welcome Back</h2>
            <div class="input-group">
                <label>Email</label>
                <input type="email" id="login-email" placeholder="email@example.com">
            </div>
            <div class="input-group">
                <label>Password</label>
                <input type="password" id="login-password" placeholder="••••••••">
            </div>
            <button class="btn btn-primary" style="width: 100%;" onclick="handleLogin()">Login</button>
            <p style="text-align: center; margin-top: 20px; font-size: 0.9rem; color: var(--muted);">
                Don't have an account? <a href="#" onclick="toggleAuth('register')" style="color: var(--primary); text-decoration: none;">Register</a>
            </p>
            <p style="text-align: center; margin-top: 15px;">
                <a href="/api-docs" style="color: var(--muted); font-size: 0.8rem; text-decoration: none;">View API Docs</a>
            </p>
        </div>

        <div class="auth-card" id="register-form" style="display: none;">
            <h2>Join Us</h2>
            <div class="input-group">
                <label>Name</label>
                <input type="text" id="reg-name" placeholder="John Doe">
            </div>
            <div class="input-group">
                <label>Email</label>
                <input type="email" id="reg-email" placeholder="email@example.com">
            </div>
            <div class="input-group">
                <label>Password</label>
                <input type="password" id="reg-password" placeholder="••••••••">
            </div>
            <button class="btn btn-primary" style="width: 100%;" onclick="handleRegister()">Register</button>
            <p style="text-align: center; margin-top: 20px; font-size: 0.9rem; color: var(--muted);">
                Already have an account? <a href="#" onclick="toggleAuth('login')" style="color: var(--primary); text-decoration: none;">Login</a>
            </p>
        </div>
    </div>

    <!-- Application Screen -->
    <div id="app-screen">
        <aside>
            <div class="logo-sm" style="margin-bottom: 40px;">TASK API</div>
            
            <div class="filter-group">
                <span class="label">STATUS</span>
                <div class="filter-options">
                    <span class="filter-tag active" data-filter="status" data-value="">All</span>
                    <span class="filter-tag" data-filter="status" data-value="pending">Pending</span>
                    <span class="filter-tag" data-filter="status" data-value="in_progress">Doing</span>
                    <span class="filter-tag" data-filter="status" data-value="completed">Done</span>
                </div>
            </div>

            <div class="filter-group">
                <span class="label">PRIORITY</span>
                <div class="filter-options">
                    <span class="filter-tag active" data-filter="priority" data-value="">All</span>
                    <span class="filter-tag" data-filter="priority" data-value="high">High</span>
                    <span class="filter-tag" data-filter="priority" data-value="medium">Medium</span>
                    <span class="filter-tag" data-filter="priority" data-value="low">Low</span>
                </div>
            </div>

            <div style="margin-top: auto;">
                <div style="background: rgba(255,255,255,0.05); padding: 15px; border-radius: 12px; margin-bottom: 20px;">
                    <p id="user-name" style="font-weight: 600; font-size: 0.9rem;"></p>
                    <p id="user-email" style="font-size: 0.75rem; color: var(--muted);"></p>
                </div>
                <button class="btn" style="width: 100%; background: transparent; border: 1px solid rgba(255,255,255,0.1); color: var(--muted);" onclick="handleLogout()">
                    <i class="fas fa-sign-out-alt"></i> Logout
                </button>
            </div>
        </aside>

        <main>
            <div class="header-actions">
                <div>
                    <h2 style="font-size: 1.8rem; font-weight: 700;">My Tasks</h2>
                    <p style="color: var(--muted); font-size: 0.9rem;">Management of your daily goals.</p>
                </div>
                <button class="btn btn-primary" onclick="openTaskModal()">
                    <i class="fas fa-plus"></i> New Task
                </button>
            </div>

            <div class="task-grid" id="task-list">
                <!-- Task Cards will appear here -->
            </div>
        </main>

        <div class="sidebar-right">
            <h3 style="margin-bottom: 20px; font-weight: 700;">Activity Logs</h3>
            <div id="activity-logs">
                <!-- Activity Logs will appear here -->
            </div>
        </div>
    </div>

    <!-- Task Modal -->
    <div id="modal-overlay">
        <div id="task-modal">
            <h3 id="modal-title" style="margin-bottom: 25px;">Create Task</h3>
            <div class="input-group">
                <label>Title</label>
                <input type="text" id="task-title" placeholder="What needs to be done?">
            </div>
            <div class="input-group">
                <label>Description</label>
                <textarea id="task-desc" rows="3" placeholder="Additional details..."></textarea>
            </div>
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
                <div class="input-group">
                    <label>Priority</label>
                    <select id="task-priority">
                        <option value="low">Low</option>
                        <option value="medium" selected>Medium</option>
                        <option value="high">High</option>
                    </select>
                </div>
                <div class="input-group">
                    <label>Status</label>
                    <select id="task-status">
                        <option value="pending">Pending</option>
                        <option value="in_progress">In Progress</option>
                        <option value="completed">Completed</option>
                    </select>
                </div>
            </div>
            <div style="display: flex; gap: 10px; margin-top: 20px;">
                <button class="btn btn-primary" id="save-task-btn" style="flex: 1;">Save Task</button>
                <button class="btn" onclick="closeTaskModal()" style="background: rgba(255,255,255,0.05); color: var(--muted);">Cancel</button>
            </div>
        </div>
    </div>

    <script>
        let currentUser = null;
        let apiToken = localStorage.getItem('api_token');
        let editingTaskId = null;
        let filters = { status: '', priority: '' };

        // Initialization
        if (apiToken) {
            checkAuth();
        }

        function toggleAuth(type) {
            document.getElementById('login-form').style.display = type === 'login' ? 'block' : 'none';
            document.getElementById('register-form').style.display = type === 'register' ? 'block' : 'none';
        }

        async function api(path, options = {}) {
            const url = '/api' + path;
            const defaultOptions = {
                headers: {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                    'Authorization': apiToken ? `Bearer ${apiToken}` : ''
                }
            };
            const response = await fetch(url, { ...defaultOptions, ...options });
            if (response.status === 401) {
                handleLogout();
                return null;
            }
            return response.json();
        }

        async function handleLogin() {
            const email = document.getElementById('login-email').value;
            const password = document.getElementById('login-password').value;
            const data = await api('/login', {
                method: 'POST',
                body: JSON.stringify({ email, password })
            });

            if (data && data.token) {
                localStorage.setItem('api_token', data.token);
                apiToken = data.token;
                checkAuth();
            } else {
                alert(data.message || 'Login failed');
            }
        }

        async function handleRegister() {
            const name = document.getElementById('reg-name').value;
            const email = document.getElementById('reg-email').value;
            const password = document.getElementById('reg-password').value;
            const data = await api('/register', {
                method: 'POST',
                body: JSON.stringify({ name, email, password })
            });

            if (data && data.token) {
                localStorage.setItem('api_token', data.token);
                apiToken = data.token;
                checkAuth();
            } else {
                alert(data.message || 'Registration failed');
            }
        }

        function handleLogout() {
            localStorage.removeItem('api_token');
            apiToken = null;
            document.getElementById('auth-screen').style.display = 'flex';
            document.getElementById('app-screen').style.display = 'none';
        }

        async function checkAuth() {
            const data = await api('/user');
            if (data && data.id) {
                currentUser = data;
                document.getElementById('auth-screen').style.display = 'none';
                document.getElementById('app-screen').style.display = 'grid';
                document.getElementById('user-name').innerText = data.name;
                document.getElementById('user-email').innerText = data.email;
                loadTasks();
                loadLogs();
            }
        }

        async function loadTasks() {
            const query = new URLSearchParams(filters).toString();
            const tasks = await api(`/tasks/filter?${query}`);
            const listEl = document.getElementById('task-list');
            listEl.innerHTML = '';

            if (!tasks || tasks.length === 0) {
                listEl.innerHTML = '<div style="grid-column: 1/-1; text-align: center; padding: 40px; color: var(--muted);">No tasks found. Create one to get started!</div>';
                return;
            }

            tasks.forEach(task => {
                const card = document.createElement('div');
                card.className = 'task-card';
                card.innerHTML = `
                    <span class="priority-badge priority-${task.priority}">${task.priority}</span>
                    <div class="task-info">
                        <h3>${task.title}</h3>
                        <p>${task.description || 'No description provided.'}</p>
                    </div>
                    <div class="task-footer">
                        <span style="font-size: 0.8rem; font-weight: 600; text-transform: uppercase; color: ${task.status === 'completed' ? 'var(--success)' : 'var(--muted)'}">
                            ${task.status.replace('_', ' ')}
                        </span>
                        <div class="task-actions">
                            <i class="fas fa-edit" onclick="openTaskModal(${JSON.stringify(task).replace(/"/g, '&quot;')})"></i>
                            <i class="fas fa-trash delete" onclick="deleteTask(${task.id})"></i>
                        </div>
                    </div>
                `;
                listEl.appendChild(card);
            });
        }

        async function loadLogs() {
            const logs = await api('/logs');
            const logsEl = document.getElementById('activity-logs');
            logsEl.innerHTML = '';

            if (logs) {
                logs.forEach(log => {
                    const item = document.createElement('div');
                    item.className = 'activity-item';
                    item.innerHTML = `
                        <div>Task ID #${log.task_id} was <span class="action">${log.action}</span></div>
                        <span class="time">${new Date(log.created_at).toLocaleString()}</span>
                    `;
                    logsEl.appendChild(item);
                });
            }
        }

        function openTaskModal(task = null) {
            editingTaskId = task ? task.id : null;
            document.getElementById('modal-title').innerText = task ? 'Edit Task' : 'Create Task';
            document.getElementById('task-title').value = task ? task.title : '';
            document.getElementById('task-desc').value = task ? task.description : '';
            document.getElementById('task-priority').value = task ? task.priority : 'medium';
            document.getElementById('task-status').value = task ? task.status : 'pending';
            document.getElementById('modal-overlay').style.display = 'flex';
        }

        function closeTaskModal() {
            document.getElementById('modal-overlay').style.display = 'none';
        }

        document.getElementById('save-task-btn').onclick = async () => {
            const title = document.getElementById('task-title').value;
            const description = document.getElementById('task-desc').value;
            const priority = document.getElementById('task-priority').value;
            const status = document.getElementById('task-status').value;

            const path = editingTaskId ? `/tasks/${editingTaskId}` : '/tasks';
            const method = editingTaskId ? 'PUT' : 'POST';

            await api(path, {
                method: method,
                body: JSON.stringify({ title, description, priority, status })
            });

            closeTaskModal();
            loadTasks();
            setTimeout(loadLogs, 500); // Wait for terminable middleware
        };

        async function deleteTask(id) {
            if (confirm('Are you sure you want to delete this task?')) {
                await api(`/tasks/${id}`, { method: 'DELETE' });
                loadTasks();
                setTimeout(loadLogs, 500);
            }
        }

        // Filters
        document.querySelectorAll('.filter-tag').forEach(tag => {
            tag.onclick = function() {
                const type = this.dataset.filter;
                const value = this.dataset.value;
                
                // Update UI
                document.querySelectorAll(`.filter-tag[data-filter="${type}"]`).forEach(t => t.classList.remove('active'));
                this.classList.add('active');

                // Update Filter
                filters[type] = value;
                loadTasks();
            }
        });
    </script>
</body>
</html>
