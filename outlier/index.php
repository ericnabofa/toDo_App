<?php
/**
 * Loads the cached featured listings if available and valid.
 *
 * @param string $cache_file The path to the cache file.
 * @param int $cache_time The cache expiration time in seconds.
 * @return array|null Returns the cached featured listings as an array or null if cache is expired or missing.
 */
function load_cached_listings($cache_file, $cache_time) {
    if (file_exists($cache_file) && (time() - filemtime($cache_file) < $cache_time)) {
        return unserialize(file_get_contents($cache_file));
    } else {
        return null;
    }
}

/**
 * Saves data to the cache file.
 *
 * @param string $cache_file The path to the cache file.
 * @param array $data The data to be cached, provided as an array.
 */
function save_to_cache($cache_file, $data) {
    file_put_contents($cache_file, serialize($data));
}

// Define cache location and expiration time (5 minutes)
$cache_file = __DIR__ . "/cache/homepage.cache";
$cache_time = 300; // 5 minutes in seconds

// Attempt to load cached listings; generate new data if cache is unavailable or expired
$featured_listings = load_cached_listings($cache_file, $cache_time);
if ($featured_listings === null) {
    // Define the hardcoded array of featured listings
    $featured_listings = [
        [
            'id' => 1,
            'title' => 'Lagos Mainland Terrace',
            'description' => 'A stunning apartment located in the heart of the city with all modern amenities.',
            'price' => 150,
        ],
        [
            'id' => 2,
            'title' => 'Lagos Beachside Apartments',
            'description' => 'A perfect getaway with ocean views, private beach access, and relaxing ambiance.',
            'price' => 200,
        ],
        [
            'id' => 3,
            'title' => 'Epe Jungle Homes',
            'description' => 'Escape to this tranquil cabin in the mountains, ideal for nature lovers.',
            'price' => 180,
        ]
    ];

    // Cache the listings data
    save_to_cache($cache_file, $featured_listings);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Homepage</title>
    <style>
        /* Styles for the featured listings container and individual listings */
        .featured-listings { display: flex; flex-wrap: wrap; gap: 20px; }
        .listing { border: 1px solid #ddd; padding: 10px; width: 30%; }
    </style>
</head>
<body>
    <h1>Welcome to Shortlet Housing</h1>
    <div class="featured-listings">
        <?php foreach ($featured_listings as $listing): ?>
            <div class="listing">
                <h2><?php echo htmlspecialchars($listing['title'], ENT_QUOTES, 'UTF-8'); ?></h2>
                <p><?php echo htmlspecialchars($listing['description'], ENT_QUOTES, 'UTF-8'); ?></p>
                <p><strong>Price per night:</strong> $<?php echo htmlspecialchars($listing['price'], ENT_QUOTES, 'UTF-8'); ?></p>
                <a href="property_detail.php?id=<?php echo (int)$listing['id']; ?>">View Details</a>
            </div>
        <?php endforeach; ?>
    </div>
</body>
</html>
