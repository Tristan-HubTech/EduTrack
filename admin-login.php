<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<title>EduTrack • Admin Dashboard</title>
<meta name="viewport" content="width=device-width, initial-scale=1" />

<style>
:root {
  --navy:#0b2a72;
  --navy-dark:#020617;
  --navy-soft:#102457;
  --accent-blue:#1f4fb2;
  --accent-red:#e31c24;
  --bg-glass:rgba(10,26,70,0.92);
  --border-soft:rgba(148,163,184,0.35);
  --text-soft:#cbd5f5;
}

* { margin:0; padding:0; box-sizing:border-box; }

body {
  min-height:100vh;
  font-family:"Inter", system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", Arial, sans-serif;
  color:#f9fafb;
  background: radial-gradient(circle at top, #16348f 0, #020617 45%, #000 100%);
  position:relative;
  overflow-x:hidden;
}

/* faint background image blur */
body::before {
  content:"";
  position:fixed;
  inset:0;
  background:url("Background.jpg") center/cover no-repeat;
  mix-blend-mode:soft-light;
  opacity:0.35;
  filter:blur(6px);
  z-index:-1;
}

/* ========= LOGIN OVERLAY ========= */
#loginScreen {
  position:fixed;
  inset:0;
  display:flex;
  align-items:center;
  justify-content:center;
  background:rgba(3,6,23,0.82);
  backdrop-filter:blur(12px);
  z-index:10;
}

.login-box {
  width:340px;
  padding:26px 22px 22px;
  border-radius:22px;
  background:linear-gradient(145deg, rgba(11,42,120,0.96), rgba(2,6,23,0.96));
  border:1px solid var(--border-soft);
  box-shadow:0 22px 55px rgba(0,0,0,0.85);
}

/* small “pill” logo bar */
.login-brand {
  display:flex;
  align-items:center;
  gap:10px;
  margin-bottom:14px;
}
.login-pill {
  padding:4px 10px;
  border-radius:999px;
  font-size:10px;
  letter-spacing:0.18em;
  text-transform:uppercase;
  background:rgba(15,23,42,0.9);
  border:1px solid rgba(255,255,255,0.05);
  color:#e5e7eb;
}
.login-brand span {
  font-weight:650;
  font-size:18px;
}

.login-sub {
  font-size:12px;
  color:var(--text-soft);
  margin-bottom:14px;
}

.login-field {
  margin-top:10px;
}
.login-field label {
  font-size:11px;
  text-transform:uppercase;
  letter-spacing:0.16em;
  color:#9ca3af;
}
.login-input-wrap {
  margin-top:4px;
  display:flex;
  align-items:center;
  gap:6px;
  padding:8px 10px;
  border-radius:12px;
  background:rgba(15,23,42,0.95);
  border:1px solid #1f2937;
  transition:border .18s, box-shadow .18s, background .18s;
}
.login-input-wrap:focus-within {
  border-color:var(--accent-blue);
  box-shadow:0 0 0 1px rgba(31,79,178,0.8);
  background:rgba(15,23,42,1);
}
.login-input-wrap span {
  font-size:13px;
  color:#64748b;
}
.login-box input {
  border:none;
  outline:none;
  background:transparent;
  color:#f9fafb;
  width:100%;
  font-size:13px;
}
.login-box input::placeholder {
  color:#64748b;
}

#loginBtn {
  margin-top:16px;
  width:100%;
  padding:9px 12px;
  border:none;
  border-radius:999px;
  font-weight:650;
  font-size:13px;
  letter-spacing:0.05em;
  text-transform:uppercase;
  cursor:pointer;
  background:linear-gradient(135deg, var(--accent-blue), #2563eb);
  color:#f9fafb;
  box-shadow:0 13px 28px rgba(15,23,42,0.85);
  transition:transform .12s, box-shadow .12s, filter .12s;
}
#loginBtn:hover {
  transform:translateY(-1px);
  box-shadow:0 18px 38px rgba(15,23,42,0.9);
}
#loginBtn:active {
  transform:translateY(0);
  box-shadow:0 10px 24px rgba(15,23,42,0.9);
}

#msg {
  margin-top:10px;
  text-align:center;
  font-size:12px;
  color:#f97373;
}

/* ========= DASHBOARD LAYOUT ========= */
#dashboard {
  display:none;
}

.dashboard-shell {
  max-width:1160px;
  margin:28px auto 32px;
  padding:0 16px 24px;
}

/* Topbar */
.topbar {
  padding:10px 18px;
  border-radius:18px;
  background:linear-gradient(90deg, rgba(10,42,120,0.96), rgba(4,9,30,0.96));
  border:1px solid rgba(148,163,184,0.4);
  display:flex;
  align-items:center;
  justify-content:space-between;
  gap:14px;
  box-shadow:0 18px 45px rgba(0,0,0,0.85);
}

.topbar-left {
  display:flex;
  align-items:center;
  gap:10px;
}
.topbar-logo {
  width:34px;
  height:34px;
  border-radius:12px;
  background:#fff;
  display:flex;
  align-items:center;
  justify-content:center;
  color:var(--navy);
  font-weight:900;
  font-size:16px;
}
.topbar-title {
  font-size:15px;
  font-weight:650;
  letter-spacing:0.1em;
  text-transform:uppercase;
}
.topbar-sub {
  font-size:11px;
  color:#9ca3af;
}

.topbar-right {
  display:flex;
  gap:8px;
}

/* Buttons */
.btn {
  cursor:pointer;
  border:none;
  border-radius:999px;
  padding:7px 15px;
  font-size:12px;
  font-weight:600;
  letter-spacing:0.05em;
  text-transform:uppercase;
  display:inline-flex;
  align-items:center;
  gap:6px;
}
.btn-primary {
  background:var(--accent-blue);
  color:#f9fafb;
  box-shadow:0 10px 24px rgba(15,23,42,0.8);
}
.btn-primary:hover {
  filter:brightness(1.08);
}
.btn-outline {
  background:transparent;
  color:#e5e7eb;
  border:1px solid rgba(148,163,184,0.6);
}
.btn-outline:hover {
  background:rgba(15,23,42,0.85);
}

/* Main content */
.admin-main {
  margin-top:18px;
  padding:18px;
  border-radius:22px;
  background:var(--bg-glass);
  border:1px solid var(--border-soft);
  box-shadow:0 26px 60px rgba(0,0,0,0.9);
  display:grid;
  grid-template-columns:1.6fr 1fr;
  gap:18px;
}
@media (max-width:900px){
  .admin-main { grid-template-columns:1fr; }
}

.panel-title {
  font-size:14px;
  letter-spacing:0.12em;
  text-transform:uppercase;
  color:#e5e7eb;
  margin-bottom:10px;
}

.field-label {
  display:block;
  margin:8px 0 4px;
  font-size:12px;
  color:var(--text-soft);
}
select,
input[type="file"],
textarea {
  width:100%;
  border-radius:12px;
  border:1px solid #1f2937;
  background:#020617;
  color:#f9fafb;
  padding:8px 10px;
  font-size:13px;
  resize:vertical;
}
select:focus,
textarea:focus,
input[type="file"]:focus {
  outline:none;
  border-color:var(--accent-blue);
  box-shadow:0 0 0 1px rgba(31,79,178,0.85);
}

/* preview */
.preview-box {
  margin-top:6px;
  height:170px;
  border-radius:14px;
  border:1px dashed #475569;
  background:rgba(15,23,42,0.95);
  display:flex;
  align-items:center;
  justify-content:center;
  color:#9ca3af;
  text-align:center;
  padding:6px;
  font-size:12px;
}
.preview-box img {
  width:100%;
  height:100%;
  object-fit:cover;
  border-radius:12px;
}

/* log list */
.log-list {
  max-height:340px;
  overflow-y:auto;
  padding-right:4px;
}
.log-entry {
  background:rgba(15,23,42,0.97);
  border-radius:14px;
  border:1px solid #374151;
  padding:9px 10px;
  margin-bottom:8px;
  font-size:12px;
}
.log-entry-header {
  display:flex;
  justify-content:space-between;
  align-items:center;
  margin-bottom:4px;
}
.log-room {
  font-weight:600;
  color:#e5e7eb;
}
.log-date {
  font-size:11px;
  color:#9ca3af;
}
.log-note {
  font-size:12px;
  color:var(--text-soft);
  margin-bottom:2px;
}
.log-files {
  font-size:11px;
  color:#9ca3af;
}

/* small “badge” above log panel */
.badge-soft {
  font-size:10px;
  letter-spacing:0.16em;
  text-transform:uppercase;
  color:#9ca3af;
  margin-bottom:6px;
}
</style>

<!-- Firebase -->
<script src="https://www.gstatic.com/firebasejs/8.10.1/firebase-app.js"></script>
<script src="https://www.gstatic.com/firebasejs/8.10.1/firebase-database.js"></script>
</head>
<body>

<!-- LOGIN SCREEN -->
<div id="loginScreen">
  <form class="login-box" id="loginForm" autocomplete="off">
    <div class="login-brand">
      <div class="login-pill">ACLC • EduTrack</div>
      <span>Admin Portal</span>
    </div>
    <div class="login-sub">
      Secure access for authorized ACLC staff to manage room images and view activity logs.
    </div>

    <div class="login-field">
      <label for="user">Username</label>
      <div class="login-input-wrap">
        <span>👤</span>
        <input id="user" type="text" placeholder="admin" />
      </div>
    </div>

    <div class="login-field">
      <label for="pass">Password</label>
      <div class="login-input-wrap">
        <span>🔒</span>
        <input id="pass" type="password" placeholder="••••••••" />
      </div>
    </div>

    <button id="loginBtn" type="button">Sign in</button>
    <div id="msg"></div>
  </form>
</div>

<!-- DASHBOARD -->
<div id="dashboard">
  <div class="dashboard-shell">

    <header class="topbar">
      <div class="topbar-left">
        <div class="topbar-logo">E</div>
        <div>
          <div class="topbar-title">EduTrack Admin</div>
          <div class="topbar-sub">ACLC College • Virtual Tour Manager</div>
        </div>
      </div>

      <div class="topbar-right">
        <button class="btn btn-outline" id="homeBtn">Home</button>
        <button class="btn btn-outline" id="activityBtn">Activity Logs</button>
        <button class="btn btn-primary" id="logoutBtn">Logout</button>
      </div>
    </header>

    <main class="admin-main">
      <!-- LEFT PANEL -->
      <section>
        <div class="panel-title">Room Image Manager</div>

        <label class="field-label" for="roomSelect">Room</label>
        <select id="roomSelect"></select>

        <label class="field-label" for="imageFile">New panorama image(s)</label>
        <input type="file" id="imageFile" accept="image/*" multiple>
        <div id="previewBox" class="preview-box">No image selected</div>

        <label class="field-label" for="note">Note / description</label>
        <textarea id="note" rows="3" placeholder="Short remark about this update..."></textarea>

        <button class="btn btn-primary" id="saveChangeBtn" style="margin-top:10px;">
          Save update
        </button>
      </section>

      <!-- RIGHT PANEL -->
      <section>
        <div class="badge-soft">system logs</div>
        <div class="panel-title">Recent User Activity</div>
        <div id="logList" class="log-list"></div>
      </section>
    </main>

  </div>
</div>

<script>
// =======================
//  ADMIN TOKEN (same as in activity_logs.php)
// =======================
const ADMIN_SECURITY_TOKEN = "EDUTRACK_ADMIN_PANEL__2025_TOKEN_9832";

// =======================
//  FIREBASE CONFIG
// =======================
const firebaseConfig = {
  apiKey: "YOUR_API_KEY",
  authDomain: "YOUR_PROJECT_ID.firebaseapp.com",
  databaseURL: "https://YOUR_PROJECT_ID-default-rtdb.firebaseio.com",
  projectId: "YOUR_PROJECT_ID",
  storageBucket: "YOUR_PROJECT_ID.appspot.com",
  messagingSenderId: "YOUR_SENDER_ID",
  appId: "YOUR_APP_ID"
};
firebase.initializeApp(firebaseConfig);
const rtdb = firebase.database();

// =======================
//  LOGIN SYSTEM
// =======================
const ADMIN_USER = "admin";
const ADMIN_PASS = "admin";

const loginScreen = document.getElementById("loginScreen");
const dashboard   = document.getElementById("dashboard");
const loginBtn    = document.getElementById("loginBtn");
const logoutBtn   = document.getElementById("logoutBtn");
const homeBtn     = document.getElementById("homeBtn");
const activityBtn = document.getElementById("activityBtn");
const userInput   = document.getElementById("user");
const passInput   = document.getElementById("pass");
const msgBox      = document.getElementById("msg");

function showDashboard() {
  loginScreen.style.display = "none";
  dashboard.style.display   = "block";
}
function showLogin() {
  loginScreen.style.display = "flex";
  dashboard.style.display   = "none";
}

// Auto-login if previously authenticated
if (localStorage.getItem("edutrackAdmin") === "true") {
  showDashboard();
}

loginBtn.addEventListener("click", () => {
  const u = userInput.value.trim();
  const p = passInput.value.trim();

  if (u === ADMIN_USER && p === ADMIN_PASS) {
    localStorage.setItem("edutrackAdmin", "true");
    msgBox.textContent = "";
    showDashboard();
  } else {
    msgBox.textContent = "Incorrect username or password.";
  }
});

// allow Enter key
document.getElementById("loginForm").addEventListener("submit", (e) => {
  e.preventDefault();
  loginBtn.click();
});

logoutBtn.addEventListener("click", () => {
  localStorage.removeItem("edutrackAdmin");
  showLogin();
});

homeBtn.addEventListener("click", () => {
  window.location.href = "main.php";
});

activityBtn.addEventListener("click", () => {
  window.location.href = "activity_logs.php?token=" + encodeURIComponent(ADMIN_SECURITY_TOKEN);
});

// =======================
//  ROOM DROPDOWN
// =======================
const roomSelect = document.getElementById("roomSelect");
const roomList = [
 "101","102","103","104","105","106","107","108","110",
 "201","202","203","204","205","206","207","208","209","210","211","214","215",
 "AUDITORIUM","CASHIER","CLINIC","COMFORT ROOM","DEANS OFFICE","FACULTY",
 "GUIDANCE OFFICE","KITCHEN LAB","LIBRARY","LINUX","PROPERTY CUSTODIAN",
 "SCHOLARSHIP OFFICE","SLAB 1","SLAB 2","SLAB 3","SOFTWARE LAB",
 "STUDENT AFFAIRS OFFICE","TECHNICAL OFFICE","NLAB",
 "DATA STRUCTURE","DSA"
];

function populateRooms() {
  roomSelect.innerHTML = "<option value=''>Select room</option>";
  roomList.forEach(r => {
    const opt = document.createElement("option");
    opt.value = r;
    opt.textContent = r;
    roomSelect.appendChild(opt);
  });
}
populateRooms();

// =======================
//  PREVIEW SELECTED IMAGES
// =======================
const imageFile  = document.getElementById("imageFile");
const previewBox = document.getElementById("previewBox");

imageFile.addEventListener("change", () => {
  const files = Array.from(imageFile.files);
  if (!files.length) {
    previewBox.textContent = "No image selected";
    return;
  }

  const first = files[0];
  const url   = URL.createObjectURL(first);
  const extra = files.length > 1 ? ` (+${files.length - 1} more)` : "";

  previewBox.innerHTML = `
    <div style="position:absolute;top:8px;left:10px;font-size:11px;color:#e5e7eb;
                text-shadow:0 1px 3px rgba(0,0,0,.8);">
      ${first.name}${extra}
    </div>
    <img src="${url}" alt="preview">
  `;
});

// =======================
//  SAVE UPDATE LOG TO FIREBASE
// =======================
const saveChangeBtn = document.getElementById("saveChangeBtn");
const noteInput     = document.getElementById("note");

saveChangeBtn.addEventListener("click", () => {
  const room = roomSelect.value;
  if (!room) {
    alert("Please select a room first.");
    return;
  }

  const files = Array.from(imageFile.files).map(f => f.name);
  const now   = new Date().toLocaleString();

  const logData = {
    room,
    note: noteInput.value.trim() || null,
    files,
    fileCount: files.length,
    timestamp: now,
    adminUser: ADMIN_USER,
    token: ADMIN_SECURITY_TOKEN
  };

  rtdb.ref("roomUpdateLogs").push(logData).catch(err => {
    console.error("Firebase log error:", err);
  });

  alert("Update logged successfully.");
  noteInput.value = "";
});

// =======================
//  LOAD PHP activity_logs
// =======================
const logListDiv = document.getElementById("logList");

function renderActivityLogRow(row) {
  const div = document.createElement("div");
  div.className = "log-entry";

  const roomLabel = row.room_id || "(no room)";
  const dateLabel = row.created_at || "";
  const event     = row.event_type || "(event)";
  const details   = row.details && row.details !== ""
      ? row.details
      : (row.query_text ? "Query: " + row.query_text : "");

  div.innerHTML = `
    <div class="log-entry-header">
      <span class="log-room">${roomLabel}</span>
      <span class="log-date">${dateLabel}</span>
    </div>
    <div class="log-note">${event}</div>
    <div class="log-files">${details || ""}</div>
  `;
  return div;
}

function loadActivityLogs() {
  fetch("get_activity_logs.php?token=" + encodeURIComponent(ADMIN_SECURITY_TOKEN))
    .then(res => res.json())
    .then(rows => {
      if (!Array.isArray(rows)) return;
      logListDiv.innerHTML = "";
      rows.forEach(row => logListDiv.appendChild(renderActivityLogRow(row)));
    })
    .catch(err => console.error("Failed to load activity logs:", err));
}

document.addEventListener("DOMContentLoaded", loadActivityLogs);
</script>

</body>
</html>
