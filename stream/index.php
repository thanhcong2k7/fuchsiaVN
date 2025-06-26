<?php
// Get the album name from the URL
$albumName = $_GET['associated'] ?? '';

// If no album name provided, show a default page
if(empty($albumName)) {
    // Show a simple default page
    echo '<!DOCTYPE html><html><head><title>Music Releases</title></head><body>';
    echo '<h1>Welcome to Music Releases</h1>';
    echo '<p>Browse our latest albums by visiting their unique URLs.</p>';
    echo '</body></html>';
    exit;
}

// Fetch album data from API
$apiUrl = "http://dashboard.fuchsia.viiic.net/stream/api/index.php?associated=$albumName";
$json = @file_get_contents($apiUrl);

if($json === false) {
    // Show simple error page
    echo '<!DOCTYPE html><html><head><title>Error</title></head><body>';
    echo '<h1>Album Not Found</h1>';
    echo '<p>The album you requested could not be found.</p>';
    echo '</body></html>';
    var_dump($json);
    exit;
}

$albumData = json_decode($json, true);

// If album not found, show 404
if(!$albumData || isset($albumData['error'])) {
    echo '<!DOCTYPE html><html><head><title>Album Not Found</title></head><body>';
    echo '<h1>Album Not Found</h1>';
    echo '<p>The album "' . htmlspecialchars($albumName) . '" could not be found.</p>';
    echo '</body></html>';
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($albumData['albumName']) ?> - Album Release</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        body {
            background: linear-gradient(135deg, #0d0f1b 0%, #141726 50%, #1a1f32 100%);
            color: #f0f0f0;
            min-height: 100vh;
            padding: 40px 20px;
            display: flex;
            justify-content: center;
            align-items: center;
            position: relative;
            overflow-x: hidden;
        }
        
        /* Animated light source */
        .light-source {
            position: fixed;
            width: 500px;
            height: 500px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(105, 90, 166, 0.3) 0%, rgba(16, 23, 41, 0) 70%);
            filter: blur(80px);
            z-index: 0;
            animation: moveLight 25s infinite alternate ease-in-out;
        }
        
        .light-source:nth-child(2) {
            width: 400px;
            height: 400px;
            background: radial-gradient(circle, rgba(255, 107, 107, 0.2) 0%, rgba(16, 23, 41, 0) 70%);
            filter: blur(60px);
            animation-delay: -5s;
            animation-duration: 20s;
        }
        
        .light-source:nth-child(3) {
            width: 300px;
            height: 300px;
            background: radial-gradient(circle, rgba(78, 205, 196, 0.15) 0%, rgba(16, 23, 41, 0) 70%);
            filter: blur(40px);
            animation-delay: -10s;
            animation-duration: 15s;
        }
        
        @keyframes moveLight {
            0% {
                transform: translate(-30%, -30%);
            }
            25% {
                transform: translate(30%, -20%);
            }
            50% {
                transform: translate(20%, 30%);
            }
            75% {
                transform: translate(-20%, 20%);
            }
            100% {
                transform: translate(-30%, -30%);
            }
        }
        
        .container {
            max-width: 1200px;
            width: 100%;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 40px;
            position: relative;
            z-index: 2;
            backdrop-filter: blur(10px);
            background: rgba(20, 23, 40, 0.3);
            border-radius: 20px;
            padding: 50px 40px;
            box-shadow: 0 20px 50px rgba(0, 0, 0, 0.3);
            border: 1px solid rgba(255, 255, 255, 0.05);
            margin: 40px 0;
        }
        
        .header {
            text-align: center;
            width: 100%;
            padding: 20px 0;
            animation: fadeIn 1s ease;
        }
        
        .logo {
            font-size: clamp(2.2rem, 5vw, 2.8rem);
            font-weight: 700;
            margin-bottom: 10px;
            background: linear-gradient(90deg, #ff9a9e, #fad0c4);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            letter-spacing: 1px;
        }
        
        .tagline {
            font-size: clamp(0.9rem, 2vw, 1.1rem);
            color: #a0a0c0;
            letter-spacing: 1px;
        }
        
        .album-container {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 60px;
            align-items: center;
            max-width: 1000px;
            width: 100%;
            animation: fadeIn 1.5s ease;
        }
        
        .album-art {
            flex: 1;
            min-width: 280px;
            max-width: 450px;
            position: relative;
            perspective: 1000px;
        }
        
        .album-cover {
            width: 100%;
            border-radius: 12px;
            box-shadow: 0 25px 50px rgba(0, 0, 0, 0.5);
            transform-style: preserve-3d;
            transition: transform 0.5s ease;
            aspect-ratio: 1/1;
            background: linear-gradient(45deg, #2d4059, #4e4e6a);
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            position: relative;
        }
        
        .album-cover img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.5s ease;
        }
        
        .album-cover:hover img {
            transform: scale(1.05);
        }
        
        .album-cover::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(45deg, rgba(45, 64, 89, 0.2), rgba(78, 78, 106, 0.2));
            z-index: 1;
        }
        
        .album-info {
            flex: 1;
            min-width: 280px;
            max-width: 500px;
            padding: 20px;
            text-align: center;
        }
        
        .album-title {
            font-size: clamp(2.5rem, 6vw, 3.5rem);
            margin-bottom: 15px;
            font-weight: 800;
            line-height: 1.1;
            background: linear-gradient(90deg, #ff9a9e, #fad0c4);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            letter-spacing: -1px;
        }
        
        .album-artist {
            font-size: clamp(1.5rem, 3.5vw, 2rem);
            margin-bottom: 30px;
            color: #a0a0c0;
            font-weight: 300;
            letter-spacing: 2px;
        }
        
        .release-date {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 10px;
            margin-bottom: 40px;
            font-size: clamp(0.9rem, 2vw, 1.1rem);
            color: #a0a0c0;
        }
        
        .release-date i {
            color: #4ecdc4;
        }
        
        .dsp-section {
            width: 100%;
            max-width: 800px;
            animation: fadeIn 1.5s ease;
        }
        
        .dsp-title {
            text-align: center;
            font-size: clamp(1.8rem, 4vw, 2.2rem);
            margin-bottom: 40px;
            position: relative;
            padding-bottom: 15px;
            font-weight: 600;
        }
        
        .dsp-title::after {
            content: "";
            position: absolute;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 100px;
            height: 3px;
            background: linear-gradient(90deg, #ff6b6b, #4ecdc4);
            border-radius: 3px;
        }
        
        .dsp-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 25px;
        }
        
        .dsp-card {
            background: rgba(30, 30, 46, 0.5);
            border-radius: 15px;
            padding: 30px;
            text-align: center;
            transition: all 0.3s ease;
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2);
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 20px;
        }
        
        .dsp-card:hover {
            transform: translateY(-10px);
            background: rgba(40, 40, 62, 0.7);
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.3);
        }
        
        .dsp-icon {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2.2rem;
            background: linear-gradient(135deg, #4568dc, #b06ab3);
            color: white;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
        }
        
        .dsp-name {
            font-size: 1.5rem;
            font-weight: 600;
            color: #f0f0f0;
        }
        
        .dsp-btn {
            background: linear-gradient(90deg, #ff6b6b, #4ecdc4);
            color: white;
            border: none;
            padding: 14px 32px;
            border-radius: 30px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s ease;
            width: 100%;
            max-width: 200px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            text-decoration: none;
            font-size: 1.1rem;
            box-shadow: 0 4px 10px rgba(255, 107, 107, 0.3);
        }
        
        .dsp-btn:hover {
            transform: scale(1.05);
            box-shadow: 0 8px 20px rgba(255, 107, 107, 0.5);
        }
        
        .dsp-btn i {
            font-size: 1.2rem;
        }
        
        footer {
            text-align: center;
            padding: 30px 0 0;
            color: #a0a0c0;
            font-size: 0.9rem;
            width: 100%;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            margin-top: 20px;
            animation: fadeIn 2s ease;
        }
        
        .pulse {
            animation: pulse 3s infinite;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
        
        @keyframes slideUp {
            from { 
                opacity: 0;
                transform: translateY(50px);
            }
            to { 
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        @keyframes pulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.03); }
            100% { transform: scale(1); }
        }
        
        /* Mobile-specific adjustments */
        @media (max-width: 768px) {
            body {
                padding: 20px 15px;
            }
            
            .container {
                padding: 30px 20px;
                margin: 20px 0;
            }
            
            .album-container {
                flex-direction: column;
                gap: 40px;
            }
            
            .album-art {
                min-width: 100%;
            }
            
            .dsp-grid {
                grid-template-columns: 1fr;
                gap: 15px;
            }
            
            .dsp-card {
                padding: 25px;
            }
            
            .dsp-icon {
                width: 70px;
                height: 70px;
                font-size: 1.8rem;
            }
            
            .dsp-name {
                font-size: 1.3rem;
            }
            
            .dsp-btn {
                padding: 12px 24px;
                font-size: 1rem;
            }
            
            .release-date {
                margin-bottom: 30px;
            }
        }
        
        /* Small mobile screens */
        @media (max-width: 480px) {
            .container {
                padding: 25px 15px;
            }
            
            .album-title {
                font-size: 2.2rem;
            }
            
            .album-artist {
                font-size: 1.4rem;
                margin-bottom: 20px;
            }
            
            .dsp-title {
                margin-bottom: 30px;
            }
            
            .dsp-card {
                padding: 20px;
            }
            
            .dsp-icon {
                width: 60px;
                height: 60px;
                font-size: 1.6rem;
            }
            
            .dsp-name {
                font-size: 1.2rem;
            }
            
            .dsp-btn {
                max-width: 160px;
            }
        }
    </style>
</head>
<body>
    <!-- Animated light sources -->
    <div class="light-source"></div>
    <div class="light-source"></div>
    <div class="light-source"></div>
    
    <div class="container">
        <div class="album-container">
            <div class="album-art">
                <div class="album-cover pulse">
                    <img id="albumImage" src="<?= htmlspecialchars($albumData['albumImage']) ?>" alt="Album Cover">
                </div>
            </div>
            
            <div class="album-info">
                <h1 class="album-title"><?= htmlspecialchars($albumData['albumName']) ?></h1>
                <h2 class="album-artist">by <?= htmlspecialchars($albumData['artist']) ?></h2>
                
                <div class="release-date">
                    <i class="fas fa-calendar-alt"></i>
                    <span id="releaseDate">Released: <?= date('F j, Y', strtotime($albumData['releaseDate'])) ?></span>
                </div>
            </div>
        </div>
        
        <div class="dsp-section">
            <h2 class="dsp-title">Stream Now</h2>
            
            <div class="dsp-grid" id="dspContainer">
                <?php 
                // Sort DSPs by their order
                usort($albumData['dspURLs'], function($a, $b) {
                    return $a['order'] <=> $b['order'];
                });
                
                foreach($albumData['dspURLs'] as $dsp): 
                    $iconClass = 'fas fa-music';
                    if (stripos($dsp['name'], 'Spotify') !== false) $iconClass = 'fab fa-spotify';
                    if (stripos($dsp['name'], 'Apple') !== false) $iconClass = 'fab fa-apple';
                    if (stripos($dsp['name'], 'YouTube') !== false) $iconClass = 'fab fa-youtube';
                    if (stripos($dsp['name'], 'Amazon') !== false) $iconClass = 'fab fa-amazon';
                    if (stripos($dsp['name'], 'Tidal') !== false) $iconClass = 'fas fa-water';
                    if (stripos($dsp['name'], 'Deezer') !== false) $iconClass = 'fab fa-deezer';
                ?>
                <div class="dsp-card">
                    <div class="dsp-icon">
                        <i class="<?= $iconClass ?>"></i>
                    </div>
                    <div class="dsp-name"><?= htmlspecialchars($dsp['name']) ?></div>
                    <a href="<?= htmlspecialchars($dsp['url']) ?>" target="_blank" class="dsp-btn">
                        <i class="fas fa-play"></i> Listen Now
                    </a>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
        <footer>
            <p>&copy; <?= date('Y') ?> Fuchsia Media Ltd. All rights reserved.</p>
            <p>Experience music like never before.</p>
        </footer>
    </div>
</body>
</html>