<?php
  include "path.php";
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Eco Alert</title>
    <link rel="stylesheet" href="src/css/HomePage.css">
    <script src="src/js/HomePage.js"></script>
</head>

<body>
    <header>
        <div id="title">
            <h1>Eco-Alert</h1>
        </div>
        <div id="head-links">
            <a href="<?php echo BASE_URL; ?>/login-form.php">Log In</a>
            <a href="<?php echo BASE_URL; ?>/signup-form.php">Sign Up</a>
        </div>
    </header>

    <div id="mission">
        <div>
            <img src="image-assets/env-icon.jpg" alt="Environmental Icons">
        </div>

        <div id="m-statement">
            <h2 style="color: #525174;">Our Mission</h2>
            <p>We aim to motivate communities to contribute to the creation of healthier, safer, and more sustainable environments. Through our platform, we seek to bridge the gap between communities and governmental organizations by implementing open reporting of environmental concerns and providing real-time updates. Our goal is to keep communities informed about environmental issues through public reporting. Additionally, this platform encourages communities to organize events that enhance their surroundings without having to rely on government intervention. Our website fosters community engagement, helping to improve areas for everyone.</p>
        </div>
    </div>

    <div class="revealable">
        <h2 id="web-info">Feature Highlights</h2>
        <div class="feature-container">
            <div class="point">
                <img style='border: none; box-shadow: none;'src="image-assets/report.png" alt="report">
                <h3>Report Management</h3>
                <p>This platform enables users within communities to report environmental concerns such as pollution, illegal dumping, and contaminated water. Governmental organizations will have the capability to review these reports and address them accordingly. Additionally, the platform will maintain a record of past environmental issues.</p>
            </div>
            <div class="point">
                <img style='border: none; box-shadow: none;' src="image-assets/bell.png" alt="bell">
                <h3>Adaptive Updates</h3>
                <p>Our site allows government organizations to provide timely updates to communities, enhancing transparency between groups. Communities will be able to track progress on various reports through status updates and descriptions provided by the government.</p>
            </div>
            <div class="point">
                <img style='border: none; box-shadow: none; margin-top: 25px;' src="image-assets/people.png" alt="people">
                <h3 style='margin-top: 45px;'>Event Coordination</h3>
                <p>Our platform promotes community awareness initiatives and events, such as clean-up meetings and various community activities. This fosters greater community involvement in maintaining environmental health in their areas.</p>
            </div>
        </div>
    </div>

    <div id="footer">
        <p style="color: #FFFFFF; text-align: right; font-size: 1.3em">Zainab Sirajo, Hafsa Hassan, Karlissa Brown - Database Systems (Fall 2024)</p>
    </div>
</body>

</html>
