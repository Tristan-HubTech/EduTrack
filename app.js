// ===== BASIC MARZIPANO SETUP =====
var panoElement = document.getElementById("pano");
var viewer = new Marzipano.Viewer(panoElement);

// ===== MANUAL DRAG CONTROLS (mouse only, no scroll zoom) =====
let isDragging = false;
let lastX = 0;
let lastY = 0;

panoElement.addEventListener("mousedown", function (e) {
  isDragging = true;
  lastX = e.clientX;
  lastY = e.clientY;
});

window.addEventListener("mouseup", function () {
  isDragging = false;
});

window.addEventListener("mousemove", function (e) {
  if (!isDragging) return;

  var dx = e.clientX - lastX;
  var dy = e.clientY - lastY;
  lastX = e.clientX;
  lastY = e.clientY;

  var v = viewer.view();
  var yaw = v.yaw();
  var pitch = v.pitch();

  v.setYaw(yaw - dx * 0.002);
  v.setPitch(pitch + dy * 0.002);
});

// ===== EQUIRECTANGULAR SCENE CREATOR =====
function createSceneConfig(imageUrl) {
  var source = Marzipano.ImageUrlSource.fromString(imageUrl);
  var geometry = new Marzipano.EquirectGeometry([{ width: 4000 }]);
  var limiter = Marzipano.RectilinearView.limit.traditional(
    1024,
    120 * Math.PI / 180
  );
  var view = new Marzipano.RectilinearView(null, limiter);

  var scene = viewer.createScene({
    source: source,
    geometry: geometry,
    view: view,
      pinFirstLevel: true
    });

  return { scene: scene, view: view };
}


// ====== DEFINE SCENES ======
var scenes = {
  gate:          createSceneConfig("assets/img/gate.jpg"),
  inside_gate:   createSceneConfig("assets/img/inside_gate.jpg"),
  hallway_1f:    createSceneConfig("assets/img/hallway_1f.jpg"),
  stairs_2f:     createSceneConfig("assets/img/stairs_2f.jpg"),
  hallway_2f:    createSceneConfig("assets/img/hallway_2f.jpg"),
  classroom_2f:  createSceneConfig("assets/img/classroom_2f.jpg"),
  stairs_3f:     createSceneConfig("assets/img/stairs_3f.jpg"),
  hallway_3f:    createSceneConfig("assets/img/hallway_3f.jpg"),
  classroom_3f:  createSceneConfig("assets/img/classroom_3f.jpg")
};

// ====== SCENE SWITCHING FUNCTION ======
function switchScene(name) {
  let url = scenes[name].url; // image URL
  scenes[name].scene.switchTo({ transitionDuration: 800 });
  console.log("Switched to:", name);
  blurFaces(url); // blur faces for that scene
}

// ====== HOTSPOT HELPER ======
function addLinkHotspot(fromScene, toSceneName, yaw, pitch, label) {
  var hotspotElement = document.createElement("div");
  hotspotElement.className = "hotspot-label"; // use your CSS class
  hotspotElement.innerText = label || "Go";

  hotspotElement.addEventListener("click", function () {
    switchScene(toSceneName);
  });

  fromScene.scene.hotspotContainer().createHotspot(
    hotspotElement,
    { yaw: yaw, pitch: pitch }
  );
}

// ====== FACE BLUR FUNCTION (ONLY ONE VERSION) ======
async function blurFaces(imageUrl) {
  const img = new Image();
  img.crossOrigin = "anonymous";
  img.src = imageUrl;

  img.onload = async () => {
    const canvas = document.getElementById("blurCanvas");
    const ctx = canvas.getContext("2d");

    canvas.width = img.width;
    canvas.height = img.height;

    ctx.clearRect(0, 0, canvas.width, canvas.height);
    ctx.drawImage(img, 0, 0);

    try {
      const detections = await faceapi.detectAllFaces(
        img,
        new faceapi.TinyFaceDetectorOptions()
      );

      detections.forEach(det => {
        const { x, y, width, height } = det.box;

        ctx.filter = "blur(40px)";
        ctx.drawImage(img, x, y, width, height, x, y, width, height);
        ctx.filter = "none";
      });
    } catch (e) {
      console.warn("FaceAPI detection failed:", e);
    }
  };
}

// ====== LOAD FACEAPI MODEL ======
Promise.all([
  faceapi.nets.tinyFaceDetector.loadFromUri("https://cdn.jsdelivr.net/npm/face-api.js/weights")
]).then(() => {
  console.log("FaceAPI Loaded");
});

// ====== 1F navigation ======
addLinkHotspot(scenes.gate,        "inside_gate", 0,      0.1, "Enter School");
addLinkHotspot(scenes.inside_gate, "gate",        Math.PI,0.1, "Back to Gate");
addLinkHotspot(scenes.inside_gate, "hallway_1f",  0,      0.1, "To Hallway 1F");
addLinkHotspot(scenes.hallway_1f,  "inside_gate", Math.PI,0.1, "Back to Lobby");
addLinkHotspot(scenes.hallway_1f,  "stairs_2f",   0.5,    0.1, "Up to 2F");

// ====== 2F navigation ======
addLinkHotspot(scenes.stairs_2f,   "hallway_1f",  -0.5, 0.1, "Down to 1F");
addLinkHotspot(scenes.stairs_2f,   "hallway_2f",   0.5, 0.1, "To Hallway 2F");
addLinkHotspot(scenes.hallway_2f,  "stairs_2f",   -0.5, 0.1, "Back to Stairs");
addLinkHotspot(scenes.hallway_2f,  "classroom_2f", 0.4, 0.0, "IT Room 2F");
addLinkHotspot(scenes.classroom_2f,"hallway_2f",  -2.5, 0.0, "Back to Hallway");

// ====== 3F navigation ======
addLinkHotspot(scenes.stairs_3f,    "hallway_2f", -0.5, 0.1, "Down to 2F");
addLinkHotspot(scenes.stairs_3f,    "hallway_3f",  0.5, 0.1, "To Hallway 3F");
addLinkHotspot(scenes.hallway_3f,   "stairs_3f",  -0.5, 0.1, "Back to Stairs");
addLinkHotspot(scenes.hallway_3f,   "classroom_3f", 0.4,0.0, "Classroom 3F");
addLinkHotspot(scenes.classroom_3f, "hallway_3f", -2.5,0.0, "Back to Hallway");

// ====== FLOOR SELECTOR BUTTONS ======
var floorButtons = document.querySelectorAll("#floorSelector button");

floorButtons.forEach(function (btn) {
  btn.addEventListener("click", function () {
    floorButtons.forEach(b => b.classList.remove("active"));
    btn.classList.add("active");

    var floor = btn.getAttribute("data-floor");
    if (floor === "1") switchScene("gate");
    if (floor === "2") switchScene("hallway_2f");
    if (floor === "3") switchScene("hallway_3f");
  });
});

// ====== DEFAULT SCENE ======
switchScene("gate");
document
  .querySelector('#floorSelector button[data-floor="1"]')
  .classList.add("active");
  