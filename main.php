<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>EduTrack Virtual Tour</title>
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <style>
    * { margin:0; padding:0; box-sizing:border-box; }
    html, body {
      height: 100%;
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      overflow: hidden;
      background: #0b2a72;
      color: white;
      position: relative;
    }
    body::before {
      content: "";
      position: fixed;
      inset: 0;
      background: url('Background.jpg') center center/cover no-repeat fixed;
      filter: brightness(0.4) blur(6px);
      z-index: 0;
    }

    header {
      position: fixed;
      top: 0; left: 0; right: 0;
      height: 60px;
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 0 100px;
      box-shadow: 0 4px 8px rgba(0,0,0,0.4);
      user-select: none;
      z-index: 2;
      pointer-events: none;
    }

    .logo {
      position: absolute;
      left: -10px;
      height: 1100px;
      margin-top: 380px;
      user-select: none;
      z-index: 1;
    }
    .logo img {
      height: 100%;
      width: auto;
      display: block;
    }

    main {
      flex: 1;
      display: flex;
      justify-content: center;
      align-items: center;
      padding-top: 100px;
      background: url('Background.jpg') no-repeat center center/cover fixed;
      position: relative;
      z-index: 1;
      height: 100vh;
      box-sizing: border-box;
    }
    main::before {
      content: "";
      position: absolute;
      inset: 0;
      background: rgba(0, 0, 0, 0.5);
      backdrop-filter: blur(8px);
      z-index: -1;
    }

    .search-container {
      position: relative;
      width: 90%;
      max-width: 600px;
      z-index: 2;
    }

    .search-input {
      width: 100%;
      padding: 16px 56px 16px 24px;
      font-size: 18px;
      border-radius: 40px;
      border: none;
      background: rgba(245, 247, 251, 0.95);
      color: #0a1633;
      box-shadow: 0 6px 20px rgba(0,0,0,0.35);
      transition: box-shadow 0.3s ease;
    }
    .search-input::placeholder {
      color: #7a8faf;
    }

    .search-button {
      position: absolute;
      right: 15px;
      top: 50%;
      transform: translateY(-50%);
      background: none;
      border: none;
      cursor: pointer;
      color: #1454b7;
      font-size: 22px;
    }

    .suggestions {
      position: absolute;
      left: 0;
      right: 0;
      top: 100%;
      margin-top: 6px;
      background: rgba(245, 247, 251, 0.98);
      border-radius: 20px;
      box-shadow: 0 8px 20px rgba(0,0,0,0.35);
      max-height: 220px;
      overflow-y: auto;
      padding: 6px 0;
      display: none;
      z-index: 10;
    }

    .suggestion-item {
      padding: 8px 18px;
      font-size: 14px;
      color: #0a1633;
      cursor: pointer;
      white-space: nowrap;
    }

    footer {
      position: fixed;
      bottom: 0; left: 0; right: 0;
      height: 45px;
      background: rgba(0, 0, 0, 0.35);
      backdrop-filter: blur(4px);
      display: flex;
      justify-content: center;
      align-items: center;
      color: #ffffff;
      letter-spacing: 2px;
      font-weight: 600;
    }

    .error-toast {
      position: fixed;
      top: 72px;
      right: 18px;
      max-width: 320px;
      padding: 10px 14px;
      border-radius: 12px;
      background: rgba(30, 30, 30, 0.98);
      border: 1px solid rgba(244, 67, 54, 0.6);
      box-shadow: 0 10px 30px rgba(0,0,0,0.6);
      display: flex;
      gap: 10px;
      font-size: 13px;
      color: #f5f5f5;
      opacity: 0;
      transform: translateY(-8px);
      pointer-events: none;
      transition: opacity .2s, transform .2s;
    }

    .error-toast.visible {
      opacity: 1;
      transform: translateY(0);
      pointer-events: auto;
    }
  </style>
</head>

<body>
  <header>
    <div class="logo">
      <img src="Logo.png">
    </div>
  </header>

  <main>
    <form class="search-container">
      <input id="roomSearch" type="text" placeholder="Search for a room (e.g., 101, CASHIER...)" autocomplete="off" required class="search-input">
      <button type="submit" class="search-button">
        <svg viewBox="0 0 24 24">
          <circle cx="11" cy="11" r="7" />
          <line x1="16.5" y1="16.5" x2="21" y2="21" />
        </svg>
      </button>
      <div id="roomSuggestions" class="suggestions"></div>
    </form>
  </main>

  <footer>
    © 2024 ACLC COLLEGE MANDAUE CITY • EDUTRACK VIRTUAL TOUR
  </footer>

  <div id="roomError" class="error-toast">
    <div class="error-icon">!</div>
    <div class="error-text">
      <div class="error-title">Room not found</div>
      <div id="roomErrorMessage" class="error-message"></div>
      <div id="roomErrorClose" class="error-close">Dismiss</div>
    </div>
  </div>

<script>
  document.addEventListener("DOMContentLoaded", () => {
    const form        = document.querySelector(".search-container");
    const input       = document.getElementById("roomSearch");
    const suggestions = document.getElementById("roomSuggestions");

    const roomError        = document.getElementById("roomError");
    const roomErrorMessage = document.getElementById("roomErrorMessage");
    const roomErrorClose   = document.getElementById("roomErrorClose");

    const roomList = [
      "101","102","103","104","105","106","107","108","110",
      "201","202","203","204","205","206","207","208","209","210","211","214","215",
      "AUDITORIUM","CASHIER","CLINIC","COMFORT ROOM","DEANS OFFICE","FACULTY",
      "GUIDANCE OFFICE","KITCHEN LAB","LIBRARY","LINUX","PROPERTY CUSTODIAN",
      "SCHOLARSHIP OFFICE","SLAB 1","SLAB 2","SLAB 3","SOFTWARE LAB",
      "STUDENT AFFAIRS OFFICE","TECHNICAL OFFICE","NLAB"
    ];

    function showRoomError(room) {
      roomErrorMessage.textContent =
        `We couldn't find "${room}". Please check the room number or office name.`;
      roomError.classList.add("visible");
      setTimeout(() => roomError.classList.remove("visible"), 3500);
    }
function goToRoom() {
  const room = input.value.trim().toUpperCase();

  // 🔐 SECRET ADMIN ACCESS
  if (room === "ADMIN-45652!") {
    window.location.href = "admin-login.php";
    return;
  }

  // normal room search
  if (!roomList.includes(room)) {
    showRoomError(room);
    return;
  }

  window.location.href = "index.php?room=" + encodeURIComponent(room);
}


    form.addEventListener("submit", e => {
      e.preventDefault();
      goToRoom();
    });

    function showSuggestions() {
      const query = input.value.trim().toUpperCase();
      suggestions.innerHTML = "";

      if (!query) return suggestions.style.display = "none";

      const matched = roomList.filter(r => r.includes(query));

      matched.forEach(room => {
        const div = document.createElement("div");
        div.className = "suggestion-item";
        div.textContent = room;
        div.onclick = () => {
          input.value = room;
          suggestions.style.display = "none";
          goToRoom();
        };
        suggestions.appendChild(div);
      });

      suggestions.style.display = matched.length ? "block" : "none";
    }

    input.addEventListener("input", showSuggestions);
  });
</script>

</body>
</html>
