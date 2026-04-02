<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Task-API Documentation</title>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;800&display=swap" rel="stylesheet">
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
            line-height: 1.6;
            overflow-x: hidden;
        }

        header {
            background: linear-gradient(135deg, rgba(99, 102, 241, 0.1) 0%, rgba(168, 85, 247, 0.1) 100%);
            padding: 80px 20px;
            text-align: center;
            border-bottom: 1px solid rgba(255, 255, 255, 0.05);
        }

        h1 {
            font-size: 3.5rem;
            font-weight: 800;
            background: linear-gradient(to right, var(--primary), var(--secondary));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            margin-bottom: 10px;
        }

        .container {
            max-width: 1000px;
            margin: 0 auto;
            padding: 40px 20px;
        }

        .endpoint-card {
            background: var(--card);
            border-radius: 16px;
            padding: 24px;
            margin-bottom: 30px;
            border: 1px solid rgba(255, 255, 255, 0.05);
            transition: all 0.3s ease;
        }

        .endpoint-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
            border-color: rgba(99, 102, 241, 0.3);
        }

        .method {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 6px;
            font-weight: 600;
            font-size: 0.8rem;
            text-transform: uppercase;
            margin-right: 12px;
        }

        .GET { background: rgba(16, 185, 129, 0.1); color: var(--success); }
        .POST { background: rgba(99, 102, 241, 0.1); color: var(--primary); }
        .PUT { background: rgba(245, 158, 11, 0.1); color: var(--warning); }
        .DELETE { background: rgba(239, 68, 68, 0.1); color: var(--error); }

        .path {
            font-family: monospace;
            font-size: 1.1rem;
            color: var(--text);
        }

        .description {
            margin: 15px 0;
            color: var(--muted);
        }

        .params-title {
            font-size: 0.9rem;
            font-weight: 600;
            color: var(--muted);
            margin-bottom: 8px;
            text-transform: uppercase;
        }

        .params-list {
            background: rgba(15, 23, 42, 0.5);
            border-radius: 8px;
            padding: 12px;
            font-family: monospace;
            font-size: 0.9rem;
            margin-bottom: 20px;
        }

        .btn-tester {
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 8px;
            cursor: pointer;
            font-weight: 600;
            transition: all 0.3s;
            text-decoration: none;
        }

        .btn-tester:hover {
            opacity: 0.9;
            transform: scale(1.05);
        }

        /* Tester Modal/Panel */
        #tester-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.8);
            display: none;
            justify-content: center;
            align-items: center;
            z-index: 1000;
        }

        #tester-panel {
            background: var(--card);
            width: 90%;
            max-width: 600px;
            border-radius: 20px;
            padding: 30px;
            position: relative;
        }

        .close-btn {
            position: absolute;
            top: 20px;
            right: 20px;
            cursor: pointer;
            color: var(--muted);
            font-size: 1.5rem;
        }

        .input-group {
            margin-bottom: 15px;
        }

        .input-group label {
            display: block;
            margin-bottom: 5px;
            color: var(--muted);
        }

        .input-group input, .input-group textarea, .input-group select {
            width: 100%;
            padding: 10px;
            background: rgba(15, 23, 42, 0.5);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 8px;
            color: white;
        }

        #response-area {
            margin-top: 20px;
            background: #000;
            padding: 15px;
            border-radius: 8px;
            max-height: 200px;
            overflow-y: auto;
            font-family: monospace;
            font-size: 0.85rem;
            color: #0f0;
            display: none;
        }

        nav {
            position: fixed;
            top: 0;
            width: 100%;
            padding: 20px;
            background: rgba(15, 23, 42, 0.8);
            backdrop-filter: blur(10px);
            z-index: 100;
            border-bottom: 1px solid rgba(255, 255, 255, 0.05);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        nav .logo {
            font-weight: 800;
            font-size: 1.5rem;
            background: linear-gradient(to right, var(--primary), var(--secondary));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        nav .links a {
            color: var(--text);
            text-decoration: none;
            margin-left: 20px;
            font-weight: 600;
            font-size: 0.9rem;
            opacity: 0.8;
            transition: opacity 0.3s;
        }

        nav .links a:hover { opacity: 1; }

        .auth-status {
            margin-bottom: 20px;
            padding: 15px;
            border-radius: 8px;
            background: rgba(255, 255, 255, 0.05);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .status-dot {
            width: 10px;
            height: 10px;
            border-radius: 50%;
            display: inline-block;
            margin-right: 8px;
        }

        .status-dot.online { background: var(--success); }
        .status-dot.offline { background: var(--error); }
    </style>
</head>
<body>

    <nav>
        <div class="logo">TASK API</div>
        <div class="links">
            <a href="/">Dashboard</a>
            <a href="/api-docs">API Docs</a>
        </div>
    </nav>

    <header>
        <h1>API Documentation</h1>
        <p style="color: var(--muted); max-width: 600px; margin: 0 auto;">Comprehensive guide and interactive tester for the Task Management API.</p>
    </header>

    <div class="container" id="app">
        <div class="auth-status">
            <div>
                <span class="status-dot" id="auth-dot"></span>
                <span id="auth-text" style="font-weight: 600;">Checking authentication...</span>
            </div>
            <button class="btn-tester" id="auth-action-btn" style="padding: 6px 15px; font-size: 0.8rem;">Login to Test</button>
        </div>

        <h2 style="margin-bottom: 20px; font-weight: 600;">Endpoints</h2>

        <!-- Auth Endpoints -->
        <div class="endpoint-card">
            <span class="method POST">POST</span>
            <span class="path">/api/register</span>
            <p class="description">Create a new user account.</p>
            <div class="params-title">Parameters (JSON)</div>
            <div class="params-list">
                "name": "string (required)",<br>
                "email": "string (required, unique)",<br>
                "password": "string (required, min:6)"
            </div>
            <button class="btn-tester" onclick="openTester('POST', '/api/register', {name:'', email:'', password:''})">Try it out</button>
        </div>

        <div class="endpoint-card">
            <span class="method POST">POST</span>
            <span class="path">/api/login</span>
            <p class="description">Authenticate and receive a Sanctum token.</p>
            <div class="params-title">Parameters (JSON)</div>
            <div class="params-list">
                "email": "string (required)",<br>
                "password": "string (required)"
            </div>
            <button class="btn-tester" onclick="openTester('POST', '/api/login', {email:'', password:''})">Try it out</button>
        </div>

        <!-- Task Endpoints -->
        <div class="endpoint-card">
            <span class="method GET">GET</span>
            <span class="path">/api/tasks</span>
            <p class="description">Retrieve a list of all tasks for the authenticated user.</p>
            <button class="btn-tester" onclick="openTester('GET', '/api/tasks')">Try it out</button>
        </div>

        <div class="endpoint-card">
            <span class="method POST">POST</span>
            <span class="path">/api/tasks</span>
            <p class="description">Create a new task.</p>
            <div class="params-title">Parameters (JSON)</div>
            <div class="params-list">
                "title": "string (required)",<br>
                "description": "text (optional)",<br>
                "priority": "low|medium|high (default: medium)",<br>
                "status": "pending|in_progress|completed (default: pending)",<br>
                "due_date": "date (optional)"
            </div>
            <button class="btn-tester" onclick="openTester('POST', '/api/tasks', {title:'', description:'', priority:'medium'})">Try it out</button>
        </div>

        <div class="endpoint-card">
            <span class="method GET">GET</span>
            <span class="path">/api/tasks/filter</span>
            <p class="description">Filter tasks based on status, priority, or due date.</p>
            <div class="params-title">Query Parameters</div>
            <div class="params-list">
                "status": "pending|in_progress|completed",<br>
                "priority": "low|medium|high",<br>
                "due_before": "date (YYYY-MM-DD)",<br>
                "due_after": "date (YYYY-MM-DD)"
            </div>
            <button class="btn-tester" onclick="openTester('GET', '/api/tasks/filter', null, true)">Try it out</button>
        </div>

        <div class="endpoint-card">
            <span class="method GET">GET</span>
            <span class="path">/api/logs</span>
            <p class="description">Retrieve activity logs for the authenticated user.</p>
            <button class="btn-tester" onclick="openTester('GET', '/api/logs')">Try it out</button>
        </div>
    </div>

    <!-- Tester Overlay -->
    <div id="tester-overlay">
        <div id="tester-panel">
            <span class="close-btn" onclick="closeTester()">&times;</span>
            <h3 id="tester-title" style="margin-bottom: 20px;">API Tester</h3>
            <p id="tester-path" style="font-family: monospace; color: var(--primary); margin-bottom: 20px; font-weight: 600;"></p>
            
            <div id="tester-inputs"></div>

            <div style="margin-top: 20px;">
                <button class="btn-tester" id="send-request-btn">Send Request</button>
            </div>

            <div id="response-area"></div>
        </div>
    </div>

    <script>
        const API_BASE = '/api';
        let currentMethod = '';
        let currentPath = '';
        let currentToken = localStorage.getItem('api_token');

        function updateAuthStatus() {
            const authDot = document.getElementById('auth-dot');
            const authText = document.getElementById('auth-text');
            const authBtn = document.getElementById('auth-action-btn');

            if (currentToken) {
                authDot.className = 'status-dot online';
                authText.innerText = 'Authenticated (Token stored)';
                authBtn.innerText = 'Logout';
                authBtn.onclick = logout;
            } else {
                authDot.className = 'status-dot offline';
                authText.innerText = 'Not Authenticated';
                authBtn.innerText = 'Go to Login';
                authBtn.onclick = () => window.location.href = '/';
            }
        }

        function logout() {
            localStorage.removeItem('api_token');
            currentToken = null;
            updateAuthStatus();
        }

        function openTester(method, path, bodyParams = null, isQuery = false) {
            currentMethod = method;
            currentPath = path;
            const overlay = document.getElementById('tester-overlay');
            const pathEl = document.getElementById('tester-path');
            const inputsContainer = document.getElementById('tester-inputs');
            const responseArea = document.getElementById('response-area');
            
            overlay.style.display = 'flex';
            pathEl.innerText = `${method} ${path}`;
            inputsContainer.innerHTML = '';
            responseArea.style.display = 'none';

            if (bodyParams) {
                Object.keys(bodyParams).forEach(key => {
                    inputsContainer.innerHTML += `
                        <div class="input-group">
                            <label>${key}</label>
                            <input type="text" id="tester-param-${key}" value="${bodyParams[key]}" placeholder="Enter value for ${key}">
                        </div>
                    `;
                });
            } else if (isQuery) {
                inputsContainer.innerHTML = `
                    <div class="input-group">
                        <label>Query String (e.g. status=pending&priority=high)</label>
                        <input type="text" id="tester-query" placeholder="Enter query string">
                    </div>
                `;
            }
        }

        function closeTester() {
            document.getElementById('tester-overlay').style.display = 'none';
        }

        async function sendRequest() {
            const responseArea = document.getElementById('response-area');
            responseArea.style.display = 'block';
            responseArea.innerText = 'Sending...';

            let body = null;
            let url = currentPath;

            const inputElements = document.querySelectorAll('#tester-inputs input');
            if (inputElements.length > 0) {
                const queryEl = document.getElementById('tester-query');
                if (queryEl) {
                    url += `?${queryEl.value}`;
                } else {
                    body = {};
                    inputElements.forEach(el => {
                        const key = el.id.replace('tester-param-', '');
                        body[key] = el.value;
                    });
                }
            }

            try {
                const options = {
                    method: currentMethod,
                    headers: {
                        'Accept': 'application/json',
                        'Content-Type': 'application/json',
                    }
                };

                if (currentToken) {
                    options.headers['Authorization'] = `Bearer ${currentToken}`;
                }

                if (body && currentMethod !== 'GET') {
                    options.body = JSON.stringify(body);
                }

                const response = await fetch(url, options);
                const data = await response.json();

                // If login/register, save token
                if (data.token) {
                    localStorage.setItem('api_token', data.token);
                    currentToken = data.token;
                    updateAuthStatus();
                }

                responseArea.innerText = `HTTP ${response.status} ${response.statusText}\n\n` + JSON.stringify(data, null, 2);
            } catch (err) {
                responseArea.innerText = 'Error: ' + err.message;
            }
        }

        document.getElementById('send-request-btn').onclick = sendRequest;
        updateAuthStatus();
    </script>
</body>
</html>
