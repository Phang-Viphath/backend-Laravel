<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>API Documentation</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Fira+Code:wght@400;500;600&display=swap" rel="stylesheet">
    <style>
        :root {
            --bg-color: #0B0E14;
            --sidebar-bg: #11141D;
            --card-bg: #151923;
            --text-main: #E2E8F0;
            --text-muted: #8B949E;
            --accent: #00E676;
            --accent-glow: rgba(0, 230, 118, 0.4);
            --border-color: #222735;
            --code-bg: #0D1117;
            --method-post: #00E676;
            --method-get: #38BDF8;
            --method-put: #FBBF24;
            --method-delete: #F87171;
            --method-patch: #F59E0B;
        }

        * { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--bg-color);
            color: var(--text-main);
            display: flex;
            min-height: 100vh;
            line-height: 1.6;
            -webkit-font-smoothing: antialiased;
            overflow-x: hidden;
        }

        /* Sidebar */
        .sidebar {
            width: 280px;
            background-color: var(--sidebar-bg);
            border-right: 1px solid var(--border-color);
            position: fixed;
            height: 100vh;
            overflow-y: auto;
            padding: 2rem 1.5rem;
            display: flex;
            flex-direction: column;
            gap: 2.5rem;
            z-index: 10;
        }

        .logo {
            font-size: 1.4rem;
            font-weight: 700;
            color: #fff;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            text-decoration: none;
            letter-spacing: -0.02em;
        }

        .logo-icon {
            width: 32px;
            height: 32px;
            background: linear-gradient(135deg, var(--accent) 0%, #00B8D4 100%);
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 0 15px var(--accent-glow);
        }

        .logo-icon svg { width: 18px; height: 18px; fill: #000; }

        .nav-group { display: flex; flex-direction: column; gap: 0.5rem; }

        .nav-group-title {
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            color: var(--text-muted);
            font-weight: 600;
            margin-bottom: 0.5rem;
        }

        .nav-link {
            color: var(--text-muted);
            text-decoration: none;
            font-size: 0.9rem;
            padding: 0.5rem 0.75rem;
            border-radius: 6px;
            transition: all 0.2s ease;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            font-weight: 500;
            cursor: pointer;
        }

        .nav-link:hover {
            background-color: rgba(255,255,255,0.03);
            color: #fff;
            transform: translateX(4px);
        }

        .nav-link.active {
            background-color: rgba(255,255,255,0.08);
            color: #fff;
        }

        .nav-method {
            font-family: 'Fira Code', monospace;
            font-size: 0.65rem;
            padding: 2px 5px;
            border-radius: 4px;
            font-weight: 600;
        }
        
        .nav-method.post { background: rgba(0, 230, 118, 0.15); color: var(--method-post); }
        .nav-method.get { background: rgba(56, 189, 248, 0.15); color: var(--method-get); }
        .nav-method.put { background: rgba(251, 191, 36, 0.15); color: var(--method-put); }
        .nav-method.delete { background: rgba(248, 113, 113, 0.15); color: var(--method-delete); }
        .nav-method.patch { background: rgba(245, 158, 11, 0.15); color: var(--method-patch); }

        /* Main Content */
        .main-content {
            margin-left: 280px;
            flex: 1;
            padding: 4rem 5rem;
            max-width: 1400px;
        }

        header { margin-bottom: 4rem; }

        .api-version {
            display: inline-block;
            background: rgba(255,255,255,0.05);
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            font-size: 0.8rem;
            color: var(--text-muted);
            margin-bottom: 1rem;
            border: 1px solid var(--border-color);
        }

        h1 {
            font-size: 3.5rem;
            font-weight: 700;
            margin-bottom: 1rem;
            color: #fff;
            letter-spacing: -0.03em;
            line-height: 1.1;
        }

        p.lead {
            font-size: 1.2rem;
            color: var(--text-muted);
            max-width: 600px;
        }

        .endpoint-section {
            display: grid;
            grid-template-columns: 1fr 450px;
            gap: 4rem;
            margin-bottom: 5rem;
            padding-top: 4rem;
            border-top: 1px solid var(--border-color);
        }

        @media (max-width: 1100px) {
            .endpoint-section { grid-template-columns: 1fr; }
            .main-content { padding: 3rem; }
        }

        .endpoint-info { display: flex; flex-direction: column; gap: 1.5rem; }

        .endpoint-header {
            display: inline-flex;
            align-items: center;
            gap: 1rem;
            background: var(--card-bg);
            padding: 0.75rem 1.25rem;
            border-radius: 8px;
            border: 1px solid var(--border-color);
            box-shadow: 0 4px 6px rgba(0,0,0,0.2);
            word-break: break-all;
        }

        .method-badge {
            font-family: 'Fira Code', monospace;
            font-size: 0.85rem;
            font-weight: 700;
            padding: 0.25rem 0.6rem;
            border-radius: 4px;
            text-transform: uppercase;
        }

        .method-badge.post { color: var(--method-post); background: rgba(0, 230, 118, 0.1); }
        .method-badge.get { color: var(--method-get); background: rgba(56, 189, 248, 0.1); }
        .method-badge.put { color: var(--method-put); background: rgba(251, 191, 36, 0.1); }
        .method-badge.delete { color: var(--method-delete); background: rgba(248, 113, 113, 0.1); }
        .method-badge.patch { color: var(--method-patch); background: rgba(245, 158, 11, 0.1); }

        .endpoint-path {
            font-family: 'Fira Code', monospace;
            font-size: 1.1rem;
            color: #fff;
        }

        .endpoint-desc {
            color: var(--text-muted);
            font-size: 1.05rem;
        }

        h3 {
            font-size: 1.2rem;
            color: #fff;
            margin-top: 1.5rem;
            margin-bottom: 1rem;
            font-weight: 600;
            border-bottom: 1px solid var(--border-color);
            padding-bottom: 0.5rem;
            display: inline-block;
        }

        /* Tables */
        .params-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 0.95rem;
            background: var(--card-bg);
            border-radius: 8px;
            overflow: hidden;
            border: 1px solid var(--border-color);
        }

        .params-table th {
            text-align: left;
            padding: 1rem 1.25rem;
            color: var(--text-muted);
            font-weight: 600;
            background: rgba(255,255,255,0.02);
            border-bottom: 1px solid var(--border-color);
        }

        .params-table td {
            padding: 1rem 1.25rem;
            border-bottom: 1px solid var(--border-color);
            vertical-align: top;
        }

        .params-table tr:last-child td { border-bottom: none; }

        .param-name {
            font-family: 'Fira Code', monospace;
            color: #fff;
            font-weight: 500;
            display: block;
            margin-bottom: 0.2rem;
        }

        .param-meta { display: flex; align-items: center; gap: 0.5rem; }

        .param-type { color: var(--accent); font-size: 0.85rem; font-family: 'Fira Code', monospace; }
        
        .param-req {
            font-size: 0.7rem;
            color: #fff;
            background: #E53935;
            padding: 2px 6px;
            border-radius: 4px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }
        
        .param-opt {
            font-size: 0.7rem;
            color: #fff;
            background: #4B5563;
            padding: 2px 6px;
            border-radius: 4px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .param-desc { color: var(--text-muted); font-size: 0.9rem; margin-top: 0.3rem;}

        /* Code Blocks */
        .code-col { display: flex; flex-direction: column; gap: 2rem; }

        .code-window {
            background-color: var(--code-bg);
            border-radius: 12px;
            border: 1px solid var(--border-color);
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0,0,0,0.4);
            transition: transform 0.3s ease, box-shadow 0.3s ease, border-color 0.3s ease;
        }

        .code-window:hover {
            transform: translateY(-4px);
            box-shadow: 0 15px 40px rgba(0,0,0,0.6), 0 0 20px rgba(0, 230, 118, 0.05);
            border-color: rgba(0, 230, 118, 0.3);
        }

        .code-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0.8rem 1.2rem;
            background-color: rgba(255,255,255,0.03);
            border-bottom: 1px solid var(--border-color);
        }

        .window-controls { display: flex; gap: 8px; }

        .control-dot { width: 12px; height: 12px; border-radius: 50%; }

        .dot-red { background-color: #FF5F56; }
        .dot-yellow { background-color: #FFBD2E; }
        .dot-green { background-color: #27C93F; }

        .code-title {
            font-size: 0.75rem;
            color: var(--text-muted);
            font-family: 'Inter', sans-serif;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            font-weight: 600;
        }

        .code-content { padding: 1.5rem; overflow-x: auto; }

        pre {
            font-family: 'Fira Code', monospace;
            font-size: 0.85rem;
            color: #E2E8F0;
            line-height: 1.6;
            margin: 0;
            white-space: pre-wrap;
        }

        /* Syntax Highlighting */
        .key { color: #7EE787; }
        .string { color: #A5D6FF; }
        .number { color: #79C0FF; }
        .boolean { color: #FF7B72; }
        .null { color: #FF7B72; }
        .http-method { font-weight: 600; }
        .http-method.post { color: var(--method-post); }
        .http-method.get { color: var(--method-get); }
        .http-method.put { color: var(--method-put); }
        .http-method.delete { color: var(--method-delete); }
        .http-method.patch { color: var(--method-patch); }
        .http-path { color: #fff; }
        .http-header { color: #8B949E; }
        .http-header-val { color: #E2E8F0; }

        /* Animations */
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(15px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .animate-fade-in { animation: fadeIn 0.4s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
        
        /* Scrollbar */
        ::-webkit-scrollbar { width: 8px; height: 8px; }
        ::-webkit-scrollbar-track { background: var(--bg-color); }
        ::-webkit-scrollbar-thumb { background: #30363D; border-radius: 4px; }
        ::-webkit-scrollbar-thumb:hover { background: #8B949E; }
        
        /* Loading */
        .loading-state {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 300px;
            color: var(--text-muted);
        }
    </style>
</head>
<body>

    <!-- Sidebar -->
    <aside class="sidebar" id="sidebar"></aside>

    <!-- Main Content -->
    <main class="main-content">
        <header class="animate-fade-in">
            <div class="api-version">Version 1.0</div>
            <h1>API Reference</h1>
            <p class="lead">Complete documentation for all endpoints in the system including authentication, public resources, and protected admin routes.</p>
        </header>

        <div id="endpoints-container"></div>
    </main>

    <script>
        // API Definitions
        const endpointsData = [
            {
                group: "Auth Routes",
                endpoints: [
                    {
                        id: "register", method: "POST", path: "/api/register",
                        desc: "Register a new user in the system. The endpoint will create the user and an associated profile, and return the complete user object with the profile data.",
                        params: [
                            { name: "name", type: "string", req: true, desc: "The full name of the user." },
                            { name: "email", type: "string", req: true, desc: "A valid and unique email address." },
                            { name: "password", type: "string", req: true, desc: "Password must be at least 6 characters." },
                            { name: "password_confirmation", type: "string", req: true, desc: "Confirm the password." },
                            { name: "phone", type: "string", req: false, desc: "User's phone number for profile creation." },
                            { name: "address", type: "string", req: false, desc: "User's physical address." }
                        ],
                        reqExample: `{\n  "name": "Phang Viphath",\n  "email": "viphath@example.com",\n  "password": "secret123",\n  "password_confirmation": "secret123",\n  "phone": "012345678",\n  "address": "Phnom Penh"\n}`,
                        resCode: "201 Created",
                        resExample: `{\n  "message": "User registered successfully",\n  "user": {\n    "id": 1,\n    "name": "Phang Viphath",\n    "email": "viphath@example.com",\n    "created_at": "2026-06-22T09:30:00.000000Z",\n    "updated_at": "2026-06-22T09:30:00.000000Z",\n    "profile": {\n      "id": 1,\n      "user_id": 1,\n      "phone": "012345678",\n      "address": "Phnom Penh",\n      "image": null,\n      "type": "profiles/jg8fmg59x5lceacsbs3m",\n      "created_at": "2026-06-22T09:30:00.000000Z",\n      "updated_at": "2026-06-22T09:30:00.000000Z"\n    }\n  }\n}`
                    },
                    {
                        id: "login", method: "POST", path: "/api/login",
                        desc: "Authenticate a user and retrieve a JWT token along with permissions and roles.",
                        params: [
                            { name: "email", type: "string", req: true, desc: "The user's email address." },
                            { name: "password", type: "string", req: true, desc: "The user's password." }
                        ],
                        reqExample: `{\n  "email": "viphath@example.com",\n  "password": "secret123"\n}`,
                        resCode: "200 OK",
                        resExample: `{\n  "token": "eyJ0eXAi...",\n  "user": {\n    "id": 1,\n    "name": "Phang Viphath",\n    "email": "viphath@example.com"\n  },\n  "permissions": [],\n  "roles": []\n}`
                    },
                    {
                        id: "logout", method: "POST", path: "/api/logout",
                        desc: "Invalidate the current JWT token.",
                        params: [],
                        reqExample: "",
                        resCode: "200 OK",
                        resExample: `{\n  "message": "User logged out successfully"\n}`
                    }
                ]
            },
            {
                group: "Public Endpoints",
                endpoints: [
                    { id: "get-public-rooms", method: "GET", path: "/api/public/rooms", desc: "Retrieve a list of rooms publicly available for booking.", params: [], reqExample: "", resCode: "200 OK", resExample: `{\n  "data": [\n    {\n      "id": 1,\n      "name": "Deluxe Suite",\n      "price": "150.00",\n      "status": "Available"\n    }\n  ]\n}` },
                    { id: "post-public-guests", method: "POST", path: "/api/public/guests", desc: "Register a new guest.", params: [ { name: "name", type: "string", req: true, desc: "Guest Name" }, { name: "email", type: "string", req: true, desc: "Guest Email" }, { name: "phone", type: "string", req: true, desc: "Guest Phone" } ], reqExample: `{\n  "name": "John Doe",\n  "email": "john@example.com",\n  "phone": "012345678"\n}`, resCode: "201 Created", resExample: `{\n  "message": "Guest created successfully",\n  "guest": { "id": 1, "name": "John Doe", "email": "john@example.com" }\n}` },
                    { id: "login-public-guests", method: "POST", path: "/api/public/guests/login", desc: "Login as a guest by email.", params: [{ name: "email", type: "string", req: true, desc: "Guest email" }], reqExample: `{\n  "email": "john@example.com"\n}`, resCode: "200 OK", resExample: `{\n  "message": "Login successful",\n  "guest": { "id": 1, "email": "john@example.com" }\n}` },
                    { id: "get-public-reservations-history", method: "GET", path: "/api/public/reservations/history", desc: "View public reservation history for a guest.", params: [], reqExample: "", resCode: "200 OK", resExample: `{\n  "data": []\n}` },
                    { id: "post-public-reservations", method: "POST", path: "/api/public/reservations", desc: "Create a new reservation publicly.", params: [ { name: "room_id", type: "integer", req: true, desc: "Room ID to book" }, { name: "check_in", type: "date", req: true, desc: "Check-in date (Y-m-d)" }, { name: "check_out", type: "date", req: true, desc: "Check-out date (Y-m-d)" }, { name: "guest_id", type: "integer", req: true, desc: "Guest ID" } ], reqExample: `{\n  "room_id": 1,\n  "check_in": "2026-07-01",\n  "check_out": "2026-07-05",\n  "guest_id": 1\n}`, resCode: "201 Created", resExample: `{\n  "message": "Reservation created",\n  "reservation": { "id": 1, "status": "Pending" }\n}` },
                    { id: "confirm-payment-reservations", method: "POST", path: "/api/public/reservations/{id}/confirm-payment", desc: "Confirm payment for a reservation.", params: [], reqExample: "", resCode: "200 OK", resExample: `{\n  "message": "Payment confirmed"\n}` },
                    { id: "cancel-public-reservations", method: "POST", path: "/api/public/reservations/{id}/cancel", desc: "Cancel a public reservation.", params: [], reqExample: "", resCode: "200 OK", resExample: `{\n  "message": "Reservation cancelled"\n}` },
                    { id: "bakong-khqr", method: "POST", path: "/api/public/bakong/khqr", desc: "Generate Bakong KHQR for payment.", params: [ { name: "amount", type: "numeric", req: true, desc: "Amount to pay" } ], reqExample: `{\n  "amount": 150.00\n}`, resCode: "200 OK", resExample: `{\n  "khqr": "00020101021229..."\n}` },
                    { id: "bakong-verify", method: "POST", path: "/api/public/bakong/verify", desc: "Verify Bakong payment webhook.", params: [], reqExample: "{}", resCode: "200 OK", resExample: `{\n  "status": "success"\n}` },
                    { id: "bakong-verify-transaction", method: "POST", path: "/api/public/bakong/verify-transaction", desc: "Verify specific transaction.", params: [ { name: "md5", type: "string", req: true, desc: "Transaction MD5 hash" } ], reqExample: `{\n  "md5": "abc123def456"\n}`, resCode: "200 OK", resExample: `{\n  "status": "Verified"\n}` },
                    { id: "public-notifications", method: "GET", path: "/api/public/notifications", desc: "Get public notifications.", params: [], reqExample: "", resCode: "200 OK", resExample: `{\n  "data": []\n}` }
                ]
            },
            {
                group: "Protected Admin Routes",
                endpoints: [
                    { id: "resource-role", method: "GET", path: "/api/role", desc: "List or manage roles (Supports standard CRUD: GET, POST, PUT, DELETE).", params: [], reqExample: "", resCode: "200 OK", resExample: `{\n  "data": [{ "id": 1, "name": "Admin" }]\n}` },
                    { id: "resource-permissions", method: "GET", path: "/api/permissions", desc: "List or manage permissions.", params: [], reqExample: "", resCode: "200 OK", resExample: `{\n  "data": [{ "id": 1, "name": "manage_users" }]\n}` },
                    { id: "resource-users", method: "GET", path: "/api/users", desc: "List or manage users.", params: [], reqExample: "", resCode: "200 OK", resExample: `{\n  "data": [{ "id": 1, "name": "John" }]\n}` },
                    { id: "resource-rooms", method: "GET", path: "/api/rooms", desc: "List or manage rooms.", params: [], reqExample: "", resCode: "200 OK", resExample: `{\n  "data": []\n}` },
                    { id: "resource-guests", method: "GET", path: "/api/guests", desc: "List or manage guests.", params: [], reqExample: "", resCode: "200 OK", resExample: `{\n  "data": []\n}` },
                    { id: "resource-reservations", method: "GET", path: "/api/reservations", desc: "List or manage reservations.", params: [], reqExample: "", resCode: "200 OK", resExample: `{\n  "data": []\n}` },
                    { id: "resource-historys", method: "GET", path: "/api/historys", desc: "List or manage history logs.", params: [], reqExample: "", resCode: "200 OK", resExample: `{\n  "data": []\n}` },
                    { id: "get-reports", method: "GET", path: "/api/reports", desc: "Get system reports.", params: [], reqExample: "", resCode: "200 OK", resExample: `{\n  "total_revenue": "15000.00",\n  "bookings": 10\n}` }
                ]
            },
            {
                group: "Profile & User Details",
                endpoints: [
                    { id: "get-profile", method: "GET", path: "/api/user", desc: "Get the authenticated user's profile.", params: [], reqExample: "", resCode: "200 OK", resExample: `{\n  "user": { "id": 1, "name": "Admin", "profile": {} }\n}` },
                    { id: "put-profile", method: "PUT", path: "/api/profile", desc: "Update user profile information.", params: [ { name: "name", type: "string", req: true, desc: "Name" }, { name: "phone", type: "string", req: false, desc: "Phone" }, { name: "address", type: "string", req: false, desc: "Address" } ], reqExample: `{\n  "name": "Updated Name"\n}`, resCode: "200 OK", resExample: `{\n  "message": "Profile updated"\n}` },
                    { id: "post-profile-image", method: "POST", path: "/api/profile/image", desc: "Upload a new profile image (multipart/form-data).", params: [ { name: "image", type: "file", req: true, desc: "Image file" } ], reqExample: `// FormData\nimage: (binary file)`, resCode: "200 OK", resExample: `{\n  "message": "Image uploaded"\n}` },
                    { id: "delete-profile-image", method: "DELETE", path: "/api/profile/image", desc: "Remove profile image.", params: [], reqExample: "", resCode: "200 OK", resExample: `{\n  "message": "Image removed"\n}` },
                    { id: "put-profile-password", method: "PUT", path: "/api/profile/password", desc: "Change password.", params: [ { name: "current_password", type: "string", req: true, desc: "Old password" }, { name: "new_password", type: "string", req: true, desc: "New password" } ], reqExample: `{\n  "current_password": "old123",\n  "new_password": "new123"\n}`, resCode: "200 OK", resExample: `{\n  "message": "Password updated"\n}` }
                ]
            },
            {
                group: "Notifications & Telegram",
                endpoints: [
                    { id: "telegram-updates", method: "GET", path: "/api/telegram/updates", desc: "Fetch recent telegram webhook updates.", params: [], reqExample: "", resCode: "200 OK", resExample: `{\n  "updates": []\n}` },
                    { id: "notifications-index", method: "GET", path: "/api/notifications", desc: "List all notifications for the authenticated user.", params: [], reqExample: "", resCode: "200 OK", resExample: `{\n  "data": []\n}` },
                    { id: "notifications-unread", method: "GET", path: "/api/notifications/unread-count", desc: "Get unread notification count.", params: [], reqExample: "", resCode: "200 OK", resExample: `{\n  "unread": 5\n}` },
                    { id: "notifications-mark-all", method: "POST", path: "/api/notifications/mark-all-read", desc: "Mark all notifications as read.", params: [], reqExample: "", resCode: "200 OK", resExample: `{\n  "message": "All marked as read"\n}` },
                    { id: "notifications-mark", method: "POST", path: "/api/notifications/{id}/mark-read", desc: "Mark a specific notification as read.", params: [], reqExample: "", resCode: "200 OK", resExample: `{\n  "message": "Marked as read"\n}` }
                ]
            }
        ];

        // Utility: Highlight JSON
        function syntaxHighlight(json) {
            if (!json) return '';
            json = json.replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;');
            return json.replace(/("(\\u[a-zA-Z0-9]{4}|\\[^u]|[^\\"])*"(\s*:)?|\b(true|false|null)\b|-?\d+(?:\.\d*)?(?:[eE][+\-]?\d+)?)/g, function (match) {
                let cls = 'number';
                if (/^"/.test(match)) {
                    if (/:$/.test(match)) {
                        cls = 'key';
                    } else {
                        cls = 'string';
                    }
                } else if (/true|false/.test(match)) {
                    cls = 'boolean';
                } else if (/null/.test(match)) {
                    cls = 'null';
                }
                return '<span class="' + cls + '">' + match + '</span>';
            });
        }

        function renderSidebar() {
            const sidebar = document.getElementById('sidebar');
            
            let html = `
                <a href="#" class="logo">
                    <div class="logo-icon">
                        <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"/>
                        </svg>
                    </div>
                    API Docs
                </a>
            `;

            endpointsData.forEach(group => {
                html += `<nav class="nav-group"><div class="nav-group-title">${group.group}</div>`;
                group.endpoints.forEach(ep => {
                    const methodLower = ep.method.toLowerCase();
                    html += `
                        <a href="#${ep.id}" class="nav-link" onclick="setActive(this)">
                            <span class="nav-method ${methodLower}">${ep.method}</span> ${ep.path.split('/').pop() || ep.path}
                        </a>
                    `;
                });
                html += `</nav>`;
            });

            sidebar.innerHTML = html;
        }

        function renderContent() {
            const container = document.getElementById('endpoints-container');
            let html = '';

            let delay = 1;
            endpointsData.forEach(group => {
                group.endpoints.forEach(ep => {
                    const methodLower = ep.method.toLowerCase();
                    const hasParams = ep.params && ep.params.length > 0;
                    
                    let paramsHtml = '';
                    if (hasParams) {
                        paramsHtml = `
                            <h3>Request Body / Parameters</h3>
                            <table class="params-table">
                                <thead>
                                    <tr><th>Parameter</th><th>Description</th></tr>
                                </thead>
                                <tbody>
                                    ${ep.params.map(p => `
                                        <tr>
                                            <td>
                                                <span class="param-name">${p.name}</span>
                                                <div class="param-meta">
                                                    <span class="param-type">${p.type}</span>
                                                    ${p.req ? '<span class="param-req">required</span>' : '<span class="param-opt">optional</span>'}
                                                </div>
                                            </td>
                                            <td><div class="param-desc">${p.desc}</div></td>
                                        </tr>
                                    `).join('')}
                                </tbody>
                            </table>
                        `;
                    } else {
                        paramsHtml = `<p class="endpoint-desc" style="margin-top:1rem; font-style:italic;">No additional parameters required.</p>`;
                    }

                    // Request window
                    let reqWindow = '';
                    if (ep.reqExample) {
                        const isJson = ep.reqExample.startsWith('{');
                        const highlightedReq = isJson ? syntaxHighlight(ep.reqExample) : ep.reqExample;
                        
                        reqWindow = `
                            <div class="code-window animate-fade-in" style="animation-delay: 0.${delay+1}s">
                                <div class="code-header">
                                    <div class="window-controls">
                                        <div class="control-dot dot-red"></div>
                                        <div class="control-dot dot-yellow"></div>
                                        <div class="control-dot dot-green"></div>
                                    </div>
                                    <div class="code-title">Example Request</div>
                                </div>
                                <div class="code-content">
<pre><code><span class="http-method ${methodLower}">${ep.method}</span> <span class="http-path">${ep.path}</span> HTTP/1.1
<span class="http-header">Host:</span> <span class="http-header-val">api.example.com</span>
<span class="http-header">Content-Type:</span> <span class="http-header-val">application/json</span>

${highlightedReq}</code></pre>
                                </div>
                            </div>
                        `;
                    }

                    // Response window
                    const highlightedRes = syntaxHighlight(ep.resExample);
                    const resWindow = `
                        <div class="code-window animate-fade-in" style="animation-delay: 0.${delay+2}s">
                            <div class="code-header">
                                <div class="window-controls">
                                    <div class="control-dot dot-red"></div>
                                    <div class="control-dot dot-yellow"></div>
                                    <div class="control-dot dot-green"></div>
                                </div>
                                <div class="code-title">${ep.resCode}</div>
                            </div>
                            <div class="code-content">
<pre><code>${highlightedRes}</code></pre>
                            </div>
                        </div>
                    `;

                    html += `
                        <section id="${ep.id}" class="endpoint-section animate-fade-in" style="animation-delay: 0.${delay}s">
                            <div class="endpoint-info">
                                <div class="endpoint-header">
                                    <span class="method-badge ${methodLower}">${ep.method}</span>
                                    <span class="endpoint-path">${ep.path}</span>
                                </div>
                                <p class="endpoint-desc">${ep.desc}</p>
                                ${paramsHtml}
                            </div>
                            <div class="code-col">
                                ${reqWindow}
                                ${resWindow}
                            </div>
                        </section>
                    `;
                    delay = delay > 5 ? 1 : delay + 1; // loop animation delays slightly
                });
            });

            container.innerHTML = html;
        }

        function setActive(element) {
            document.querySelectorAll('.nav-link').forEach(l => l.classList.remove('active'));
            element.classList.add('active');
        }

        // Initialize
        document.addEventListener('DOMContentLoaded', () => {
            renderSidebar();
            renderContent();
            
            // Set first active if none
            if(window.location.hash) {
                const el = document.querySelector(`.nav-link[href="${window.location.hash}"]`);
                if(el) el.classList.add('active');
            } else {
                const first = document.querySelector('.nav-link');
                if(first) first.classList.add('active');
            }
        });
    </script>
</body>
</html>
