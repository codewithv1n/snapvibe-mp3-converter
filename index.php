<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Snapvibe</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    
   <div class="snapvibe-container">
      <form action="../controllers/snapvibe_process.php" method="POST" id="converter-form">

          <div class="snapvibe-form">
            <div class="snapvibe-header">
              <img src="logo/snapvibe.jpg" alt="snapvibe">
              <h1>Snapvibe</h1>
              <p>Convert youtube video into mp3 for free.</p>
            </div>
       

            <div class="form-text"> 
              <input type="url" name="youtube_url" id="youtube_url" placeholder="Enter your url" required>
              <button type="submit" class="cnvrt-btn">Convert</button>
            </div>
           
            <div class="status-message"></div>
            
          </div>

      </form>
   </div>



 <script src="script.js?v=2"></script>
</body>
</html>