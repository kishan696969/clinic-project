<html>
<head>
    <title>Registration</title>
    <link rel="stylesheet" href="style.css">
    <style>
        /* Camera Styling */
        #camera_view, #photo_preview {
            width: 100%;
            max-width: 320px;
            height: 240px;
            background: #eee;
            border: 2px dashed #bdc3c7;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 10px;
        }
        video { width: 100%; height: 100%; object-fit: cover; }
        .cam-btn { padding: 8px 15px; cursor: pointer; border: none; border-radius: 4px; color: white; margin-right: 5px; }
        .btn-start { background: #3498db; }
        .btn-capture { background: #e74c3c; display: none; }
        .btn-retake { background: #f39c12; display: none; }
    </style>
</head>
<body>

<div class="layout">
    <?php include "navbar.php"; ?>

    <div class="content">
        <div class="container">
            <h2>🏥 New Patient Registration</h2>

            <form method="POST" action="save_patient.php">

                <label>Patient Photo</label>
                
                <div id="camera_view">
                    <span style="color:#7f8c8d;">Camera is Off</span>
                    <video id="video" autoplay playsinline style="display:none;"></video>
                </div>

                <img id="photo_preview" style="display:none; border: 2px solid #27ae60;">

                <input type="hidden" name="webcam_image" id="webcam_image">

                <div style="margin-bottom: 20px;">
                    <button type="button" class="cam-btn btn-start" onclick="startCamera()">📷 Start Camera</button>
                    <button type="button" class="cam-btn btn-capture" id="snap_btn" onclick="takeSnapshot()">🔘 Click Photo</button>
                    <button type="button" class="cam-btn btn-retake" id="retake_btn" onclick="retakePhoto()">🔄 Retake</button>
                </div>
                
                <canvas id="canvas" width="320" height="240" style="display:none;"></canvas>
                <label>Full Name *</label>
                <input type="text" name="name" required placeholder="Enter full name">

                <label>Mobile Number *</label>
                <input type="text" name="mobile" required placeholder="10 digit mobile">

                <label>Age *</label>
                <input type="number" name="age" required>

                <label>Address</label>
                <input type="text" name="address">

                <h3>ID Proofs (Optional)</h3>
                <label>Aadhaar Card No</label>
                <input type="text" name="aadhaar">

                <label>Ration Card No</label>
                <input type="text" name="ration">

                <label>Voter ID Card No</label>
                <input type="text" name="voter_id">

                <label>PAN Card No</label>
                <input type="text" name="pan_card">

                <button type="submit">® Register Patient</button>
            </form>

            <div class="nav-buttons">
                <span></span>
                <a href="add_visit.php">Next → Add Visit</a>
            </div>
        </div>
    </div>
</div>

<script>
    // Elements
    const video = document.getElementById('video');
    const canvas = document.getElementById('canvas');
    const photoPreview = document.getElementById('photo_preview');
    const cameraView = document.getElementById('camera_view');
    const webcamInput = document.getElementById('webcam_image');
    
    const startBtn = document.querySelector('.btn-start');
    const snapBtn = document.getElementById('snap_btn');
    const retakeBtn = document.getElementById('retake_btn');

    // 1. Start Camera
    function startCamera() {
        navigator.mediaDevices.getUserMedia({ video: true })
            .then(function(stream) {
                video.srcObject = stream;
                video.style.display = 'block';
                cameraView.querySelector('span').style.display = 'none'; // Hide text
                
                // Show/Hide Buttons
                startBtn.style.display = 'none';
                snapBtn.style.display = 'inline-block';
                photoPreview.style.display = 'none';
                cameraView.style.display = 'flex';
            })
            .catch(function(err) {
                alert("Error: Camera access denied or not found!");
            });
    }

    function takeSnapshot() {
        const context = canvas.getContext('2d');
        // Draw video frame to canvas
        context.drawImage(video, 0, 0, 320, 240);
        
        // Convert to Base64 Image Data
        const dataURL = canvas.toDataURL('image/jpeg');
        webcamInput.value = dataURL; 

        // Show Preview
        photoPreview.src = dataURL;
        photoPreview.style.display = 'block';
        cameraView.style.display = 'none';

        // Buttons
        snapBtn.style.display = 'none';
        retakeBtn.style.display = 'inline-block';
    }

    function retakePhoto() {
        webcamInput.value = ''; 
        photoPreview.style.display = 'none';
        cameraView.style.display = 'flex'; 
        
        snapBtn.style.display = 'inline-block';
        retakeBtn.style.display = 'none';
    }
</script>

</body>
</html>