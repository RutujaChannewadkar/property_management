<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Property Rental Management</title>

  <style>
    body {
      margin: 0;
      font-family: Arial, sans-serif;
    }

    .main_div {
      background-color: #000080;
      width: 100%;
      position: fixed;
      padding: 16px 0;
      top: 0;
      left: 0;
      z-index: 999;
      display: flex;
      box-shadow: 0 4px 6px rgba(0, 0, 0, 0.3);
    }

    .main_div a {
      color: white;
      padding: 14px 20px;
      text-decoration: none;
      font-size: 17px;
      margin-left: 50px;
    }

    .main_div a:hover {
      background-color: skyblue;
      border-radius: 5px;
    }

    .img1_div {
      margin-top: 70px;
      border-radius: 7px;
      box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2); 
      width: 100%;
      max-width: 1000px;
      opacity: 0;
      transform: scale(1);
      transition: transform 3.5s ease, opacity 3.5s ease;
      position: absolute;
      left: 50%;
      transform: translateX(-50%) scale(1);
    }

    .slideshow-container {
      position: relative;
      height: auto;
      min-height: 400px;
      margin-top: 120px;
    }

    .logindiv {
      display: flex;
      justify-content: center;
      flex-wrap: wrap;
      margin-top: 25%;
      gap: 80px;
    }

    .img2_div {
      border-radius: 10px;
      overflow: hidden;
      height: 30%;
      width: 25%;
      box-shadow: 0 4px 10px rgba(0, 0, 0, 0.3); 
      transition: box-shadow 0.5s ease, transform 0.3s ease;
    }

    .img2_div:hover {
        box-shadow: 0 0 25px rgba(0, 123, 255, 0.8); /* Blue glow */
        transform: scale(1.05); /* Slight zoom for feedback */
      }

    .img2_div img {
      width: 100%;
      height: auto;
      border-radius: 7px;
    }

    .img2_div h4 {
      text-align: center;
      margin: 10px 0;
      color: #000;
    }

    .img2_div a {
      text-decoration: none;
      color: inherit;
    }

    @media (max-width: 768px) {
      .img2_div {
        width: 90%;
      }

      .main_div {
        flex-direction: column;
        align-items: center;
      }

      .main_div a {
        margin: 5px 0;
      }
    }

    .contactdiv {
      
      margin-top: 15%;

    }
  </style>
</head>
<body>

  <div class="main_div">
    <a href="#homediv">Home</a>
    <a href="#logindiv">Login</a>
    <a href="#contactdiv">Contact</a>
  </div>

  <div id="homediv" class="slideshow-container">
    <center>
      <img src="img/56.jpg" alt="Main Image 1" class="img1_div" />
      <img src="img/57.jpg" alt="Main Image 2" class="img1_div" />
      <img src="img/58.jpg" alt="Main Image 3" class="img1_div" />
    </center>
  </div>

  <center id="logindiv">
    <div class="logindiv">
      <div class="img2_div">
        <a href="owner_login.php" class="blink">
          <img src="img/009.png" alt="Login Option 1" />
          <h4>Owner Login</h4>

        </a>
      </div>

      <div class="img2_div">
        <a href="tenant_login.php" class="blink">
          <img src="img/008.png" alt="Login Option 2" />
          <h4>Tenant Login</h4>
          <p>Tenat Payment Method</p>
        </a>
      </div>

      <div class="img2_div">
        <a href="vendor_login.php" class="blink">
          <img src="img/0001.png" alt="Login Option 3"/>
          <h4>Vendor Login</h4>
          <!-- <p>Vendor form</p> -->
        </a>
      </div>
    </div>
  </center>

  <center>
    <div id="contactdiv" class="contactdiv">
      <h3>Contact</h3>
      <!-- <h4>Visit us :</h4>
      Starbucks Coffee, Law College RD,<br>
      Shanti Sheela Society, Prabhat nagar,<br>
      Erandwane, Pune, Maharashtra<br>
      411004
      <div id="map">
        <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d121115.52964026592!2d73.75584664596008!3d18.44465094650473!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3bc2bf74ccf6f30d%3A0x6a898ab560728c41!2sStarbucks%20Coffee!5e0!3m2!1sen!2sin!4v1702649094283!5m2!1sen!2sin" width="700" height="400" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
      </div> -->
    </div>
  </center>

  <!-- JS for Zoom Animation on img1_div -->
  <script>
    const zoomImages = document.querySelectorAll('.img1_div');
    let currentIndex = 0;

    function animateZoom() {
      zoomImages.forEach((img, i) => {
        img.style.transform = 'translateX(-50%) scale(1)';
        img.style.opacity = '0';
        img.style.zIndex = '0';
      });

      const current = zoomImages[currentIndex];
      current.style.transform = 'translateX(-50%) scale(1.2)';
      current.style.opacity = '1';
      current.style.zIndex = '1';

      currentIndex = (currentIndex + 1) % zoomImages.length;
    }

    animateZoom();
    setInterval(animateZoom, 5000);
  </script>
</body>
</html>
