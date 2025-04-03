<?php
// Script to download bonsai book cover images
set_time_limit(300); // 5 minutes timeout for all downloads

// Target directory
$targetDir = 'Images/books/';

// Ensure the directory exists
if (!file_exists($targetDir)) {
    mkdir($targetDir, 0777, true);
}

// Book images - URLs and local filenames
$bookImages = [
    // Beginner books
    [
        'url' => 'https://m.media-amazon.com/images/I/51CmQmpMhnL._SY291_BO1,204,203,200_QL40_FMwebp_.jpg',
        'filename' => 'bonsai_basics_step.jpg',
        'category' => 'Beginner'
    ],
    [
        'url' => 'https://m.media-amazon.com/images/I/51Fyqv7h+2L._SY344_BO1,204,203,200_.jpg',
        'filename' => 'bonsai_beginners_chan.jpg',
        'category' => 'Beginner'
    ],
    
    // Guide books
    [
        'url' => 'https://m.media-amazon.com/images/I/51GbHhEKT8L._SY291_BO1,204,203,200_QL40_FMwebp_.jpg',
        'filename' => 'complete_book_tomlinson.jpg',
        'category' => 'Guide'
    ],
    [
        'url' => 'https://m.media-amazon.com/images/I/51+0lyshRnL._SY344_BO1,204,203,200_.jpg',
        'filename' => 'little_book_dupuich.jpg',
        'category' => 'Guide'
    ],
    [
        'url' => 'https://m.media-amazon.com/images/I/51DNn1EX8FL._SY291_BO1,204,203,200_QL40_FMwebp_.jpg',
        'filename' => 'art_of_growing_chan.jpg',
        'category' => 'Guide'
    ],
    
    // Indoor books
    [
        'url' => 'https://m.media-amazon.com/images/I/91xfkaVTZ8L._AC_UY218_.jpg',
        'filename' => 'indoor_bonsai_busch.jpg',
        'category' => 'Indoor'
    ],
    [
        'url' => 'https://images.squarespace-cdn.com/content/v1/5e1af4f9e04ec04cb00b8c88/1634927842399-WBNV8VIEZMW3PXBLJDX8/9780747551089.jpg',
        'filename' => 'indoor_handbook_prescott.jpg',
        'category' => 'Indoor'
    ],
    [
        'url' => 'https://covers.openlibrary.org/b/id/13210-L.jpg',
        'filename' => 'successful_indoor_lesniewicz.jpg',
        'category' => 'Indoor'
    ],
    
    // Species books
    [
        'url' => 'https://m.media-amazon.com/images/I/51HySPKb-RL._SY291_BO1,204,203,200_QL40_FMwebp_.jpg',
        'filename' => 'japanese_maples_adams.jpg',
        'category' => 'Species'
    ],
    [
        'url' => 'https://m.media-amazon.com/images/I/51U0OIHg67L._SY291_BO1,204,203,200_QL40_FMwebp_.jpg',
        'filename' => 'maples_guide_vertrees.jpg',
        'category' => 'Species'
    ]
];

// Function to download images
function downloadImage($url, $targetPath) {
    $ch = curl_init($url);
    $fp = fopen($targetPath, 'wb');
    
    curl_setopt($ch, CURLOPT_FILE, $fp);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 60); // 1 minute timeout per image
    
    $result = curl_exec($ch);
    if (!$result) {
        return false;
    }
    
    curl_close($ch);
    fclose($fp);
    
    return true;
}

// Download all images
$successCount = 0;
foreach ($bookImages as $image) {
    $targetPath = $targetDir . $image['filename'];
    
    echo "Downloading {$image['category']} book image: {$image['filename']}... ";
    
    if (downloadImage($image['url'], $targetPath)) {
        echo "SUCCESS<br>";
        $successCount++;
    } else {
        echo "FAILED<br>";
    }
}

echo "<br>Downloaded $successCount of " . count($bookImages) . " book cover images.";
?> 