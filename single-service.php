<?php
// Get book category from query parameter
$book_category = isset($_GET['service']) ? $_GET['service'] : 'beginner';

// Book data
$books = [
    'beginner' => [
        'title' => 'Bonsai Basics: The Complete Guide',
        'image' => '/Bonsai/Images/Index/IMG_6171.JPG',
        'author' => 'Colin Lewis',
        'publisher' => 'Sterling Publishing',
        'short_description' => 'The perfect introduction for beginners, this comprehensive guide covers essential techniques with over 200 full-color photos.',
        'full_description' => 'This bestselling guide is the perfect introduction to the art of bonsai. Colin Lewis, a renowned bonsai expert, presents a comprehensive approach that covers all the basics—from choosing the right tree to proper pruning techniques. With over 200 full-color photographs and step-by-step instructions, beginners will quickly learn how to care for their first bonsai. The book covers soil composition, watering schedules, seasonal care, styling basics, and common tropical species suitable for Malaysia\'s climate. Written in accessible language, it demystifies bonsai cultivation and makes this ancient art form approachable for anyone interested in starting their bonsai journey.',
        'features' => [
            'Over 200 full-color photographs and illustrations',
            'Step-by-step instructions for basic bonsai care',
            'Special section on tropical species for Malaysian growers',
            'Troubleshooting guide for common beginner problems',
            'Seasonal care calendar for year-round maintenance'
        ],
        'pricing' => 'RM89.90',
        'isbn' => '978-1-58017-595-2',
        'pages' => '224',
        'published' => '2019'
    ],
    'styling' => [
        'title' => 'Advanced Bonsai Techniques',
        'image' => '/Bonsai/Images/Index/IMG_6168.JPG',
        'author' => 'Harry Tomlinson',
        'publisher' => 'DK Publishing',
        'short_description' => 'Master complex styling, wiring, and artistic refinement with this comprehensive guide for experienced enthusiasts.',
        'full_description' => 'Take your bonsai skills to the next level with this advanced guide from master bonsai artist Harry Tomlinson. This in-depth reference explores sophisticated styling techniques that transform good bonsai into exceptional works of art. Tomlinson shares professional insights on creating visual drama through advanced wiring, branch refinement, and ramification techniques. The book delves into advanced aesthetics, discussing concepts like asymmetry, proportion, focal points, and the creation of aged appearance. With detailed case studies showing the progression of trees over years of refinement, this guide is perfect for enthusiasts ready to elevate their bonsai practice from simple maintenance to true artistic expression.',
        'features' => [
            'Advanced wiring and shaping techniques',
            'Detailed illustrations of branch structure development',
            'Guide to creating deadwood features (jin and shari)',
            'Professional repotting and root management',
            'Long-term development strategies with year-by-year progressions'
        ],
        'pricing' => 'RM115.00',
        'isbn' => '978-0-7894-5305-1',
        'pages' => '288',
        'published' => '2020'
    ],
    'species' => [
        'title' => 'Species-Specific Bonsai Guide',
        'image' => '/Bonsai/Images/Index/IMG_6179.JPG',
        'author' => 'Peter Warren',
        'publisher' => 'Timber Press',
        'short_description' => 'Specialized books focusing on particular bonsai species, with detailed care instructions tailored to their unique requirements.',
        'full_description' => 'This comprehensive species guide by Peter Warren covers over 40 different tree species commonly used for bonsai, with special emphasis on their individual care requirements and styling potential. For each species, Warren provides detailed information on growth patterns, pruning timing, wiring considerations, and seasonal care. The book is organized by tree type (deciduous, coniferous, tropical, etc.) with specific sections dedicated to species that thrive in Southeast Asian climates. Particularly valuable for Malaysian bonsai enthusiasts are the chapters on ficus varieties, tropical junipers, and native Malaysian species that make excellent bonsai subjects but are rarely covered in Western bonsai literature.',
        'features' => [
            'Individual profiles of 40+ bonsai species',
            'Species-specific styling advice and examples',
            'Seasonal care calendars tailored to each species',
            'Propagation and development techniques by species',
            'Special section on Malaysian native species for local growers'
        ],
        'pricing' => 'RM98.50',
        'isbn' => '978-0-88192-970-7',
        'pages' => '256',
        'published' => '2021'
    ],
    'tropical' => [
        'title' => 'Tropical Bonsai Masterclass',
        'image' => '/Bonsai/Images/Index/IMG_6177.JPG',
        'author' => 'Peter Chan',
        'publisher' => 'Herons Bonsai',
        'short_description' => 'Books specifically focused on tropical bonsai varieties suitable for Malaysia\'s climate, including native Southeast Asian species.',
        'full_description' => 'Renowned bonsai master Peter Chan presents the definitive guide to tropical bonsai cultivation, specifically written for enthusiasts in tropical climates like Malaysia. This groundbreaking book fills an important gap in bonsai literature, which has traditionally focused on temperate species. Chan draws on decades of experience working with tropical species to provide in-depth guidance on collecting, styling, and maintaining tropical bonsai that thrive in high-humidity environments. The book features extensive sections on ficus varieties, tropical flowering species, and native Southeast Asian trees that make excellent bonsai. With special attention to pest management in tropical environments and adaptation of traditional bonsai techniques to suit tropical growth patterns, this book is an essential resource for Malaysian bonsai enthusiasts.',
        'features' => [
            'Tropical-specific soil and fertilization techniques',
            'Year-round maintenance calendar for tropical climates',
            'Styling approaches suited to fast-growing tropical species',
            'Pest and disease management specific to high-humidity environments',
            'Gallery of tropical bonsai masterpieces from Southeast Asian artists'
        ],
        'pricing' => 'RM125.00',
        'isbn' => '978-0-9553309-1-8',
        'pages' => '320',
        'published' => '2018'
    ],
    'advanced' => [
        'title' => 'Bonsai Art & Technique',
        'image' => '/Bonsai/Images/Index/IMG_6174.JPG',
        'author' => 'John Naka',
        'publisher' => 'Bonsai Institute',
        'short_description' => 'Sophisticated resources for experienced enthusiasts, covering advanced techniques, problem-solving, and artistic refinement.',
        'full_description' => 'This collector\'s edition brings together the wisdom of legendary bonsai master John Naka, considered one of the greatest bonsai artists of all time. More than a technical manual, this book delves into the philosophy and artistic principles that elevate bonsai from craft to art form. Naka shares his artistic process through detailed case studies, showing how he approaches design decisions and develops trees over decades. The advanced techniques covered include creating aged appearance, establishing natural movement, refined wiring methods, and the development of ramification. While the book presents technical challenges suited for advanced practitioners, Naka\'s clear explanations and philosophical insights make this a treasured resource for serious bonsai enthusiasts at any level who wish to deepen their understanding of bonsai as an art form.',
        'features' => [
            'Philosophical approach to bonsai as fine art',
            'Advanced styling principles for creating masterpiece bonsai',
            'Case studies showing decades-long development of specimen trees',
            'Detailed drawings explaining design concepts',
            'Rare photographs of museum-quality bonsai with detailed analysis'
        ],
        'pricing' => 'RM175.00',
        'isbn' => '978-0-8096-0003-9',
        'pages' => '376',
        'published' => '2013'
    ],
    'journals' => [
        'title' => 'The Bonsai Journal: Five-Year Record Book',
        'image' => '/Bonsai/Images/Index/IMG_6169.JPG',
        'author' => 'Amy Liang',
        'publisher' => 'Tuttle Publishing',
        'short_description' => 'Track your bonsai development with our selection of specialized journals, maintenance planners, and record-keeping guides.',
        'full_description' => 'This beautifully designed journal by Amy Liang provides a structured system for tracking the development of your bonsai collection over a five-year period. Far more than just a notebook, this specialized journal includes seasonal care reminders, maintenance tracking sheets, and development planning tools. Each tree in your collection gets dedicated pages with photo documentation areas, styling notes, and seasonal care records. The journal includes reference sections on seasonal tasks, fertilization schedules, and pest management, making it both a record-keeping system and a practical reference guide. Perfect for both serious hobbyists and professional bonsai artists, this journal helps create a valuable record of your bonsai journey while improving your care practices through organized documentation.',
        'features' => [
            'Five-year development tracking for multiple trees',
            'Photo documentation pages for before/after comparison',
            'Seasonal care checklists and maintenance schedules',
            'Styling plan worksheets with branch diagrams',
            'Reference sections for quick care guidance'
        ],
        'pricing' => 'RM78.50',
        'isbn' => '978-4-8053-1467-2',
        'pages' => '192',
        'published' => '2022'
    ]
];

// Set default if book category not found
if (!isset($books[$book_category])) {
    $book_category = 'beginner';
}

// Get current book data
$current_book = $books[$book_category];

// Set page title
$page_title = $current_book['title'];

require_once 'includes/header.php';
require_once 'components/breadcrumbs.php';

echo breadcrumbs([
    'Home' => 'index.php',
    'Books & Services' => 'services.php',
    $current_book['title'] => ''
]);
?>

<!-- Book Hero -->
<section class="py-16">
    <div class="container mx-auto">
        <div class="flex flex-col md:flex-row gap-12">
            <!-- Image -->
            <div class="md:w-1/2">
                <div class="rounded-lg overflow-hidden shadow-xl">
                    <img src="<?php echo $current_book['image']; ?>" alt="<?php echo $current_book['title']; ?>" class="w-full h-auto">
                </div>
            </div>
            
            <!-- Content -->
            <div class="md:w-1/2">
                <h1 class="text-4xl font-bold mb-4"><?php echo $current_book['title']; ?></h1>
                <p class="text-xl mb-6 text-gray-600">By <?php echo $current_book['author']; ?></p>
                <p class="mb-6"><?php echo $current_book['short_description']; ?></p>
                
                <div class="grid grid-cols-2 gap-4 mb-6">
                    <div>
                        <p class="font-semibold">Publisher:</p>
                        <p class="text-gray-600"><?php echo $current_book['publisher']; ?></p>
                    </div>
                    <div>
                        <p class="font-semibold">ISBN:</p>
                        <p class="text-gray-600"><?php echo $current_book['isbn']; ?></p>
                    </div>
                    <div>
                        <p class="font-semibold">Pages:</p>
                        <p class="text-gray-600"><?php echo $current_book['pages']; ?></p>
                    </div>
                    <div>
                        <p class="font-semibold">Published:</p>
                        <p class="text-gray-600"><?php echo $current_book['published']; ?></p>
                    </div>
                </div>
                
                <div class="mb-8">
                    <p class="font-bold text-lg mb-2">Price:</p>
                    <p class="text-primary text-2xl"><?php echo $current_book['pricing']; ?></p>
                </div>
                
                <div class="flex space-x-4">
                    <a href="#" class="btn btn-primary">Add to Cart</a>
                    <a href="contacts.php?book=<?php echo $book_category; ?>" class="btn bg-gray-200 text-gray-800 hover:bg-gray-300">Inquire About Availability</a>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Book Description -->
<section class="bg-gray-50 py-16">
    <div class="container mx-auto">
        <div class="max-w-4xl mx-auto">
            <h2 class="text-3xl font-bold mb-6">About This Book</h2>
            <p class="text-gray-600 mb-10">
                <?php echo $current_book['full_description']; ?>
            </p>
            
            <h3 class="text-2xl font-bold mb-4">Key Features</h3>
            <ul class="space-y-3 mb-10">
                <?php foreach ($current_book['features'] as $feature): ?>
                <li class="flex items-start">
                    <svg class="w-5 h-5 text-primary mr-2 mt-1" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                    </svg>
                    <span><?php echo $feature; ?></span>
                </li>
                <?php endforeach; ?>
            </ul>
            
            <div class="bg-white p-8 rounded-lg shadow-md">
                <h3 class="text-2xl font-bold mb-4">Ready to Enhance Your Bonsai Knowledge?</h3>
                <p class="mb-6">
                    Add this book to your collection today or browse our other bonsai resources to build your bonsai library.
                </p>
                <div class="flex flex-col sm:flex-row gap-4">
                    <a href="#" class="btn btn-primary">Add to Cart</a>
                    <a href="services.php" class="btn bg-gray-200 text-gray-800 hover:bg-gray-300">View All Books</a>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Related Books -->
<section class="py-16">
    <div class="container mx-auto">
        <h2 class="text-3xl font-bold mb-10 text-center">You May Also Like</h2>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <?php
            $count = 0;
            foreach ($books as $key => $other_book) {
                if ($key != $book_category && $count < 3) {
                    echo '<div class="bg-white rounded-lg shadow-lg overflow-hidden">';
                    echo '<img src="' . $other_book['image'] . '" alt="' . $other_book['title'] . '" class="w-full h-48 object-cover">';
                    echo '<div class="p-6">';
                    echo '<h3 class="text-xl font-bold mb-2">' . $other_book['title'] . '</h3>';
                    echo '<p class="text-gray-600 mb-1">By ' . $other_book['author'] . '</p>';
                    echo '<p class="text-primary font-bold mb-4">' . $other_book['pricing'] . '</p>';
                    echo '<a href="single-service.php?service=' . $key . '" class="text-primary font-semibold hover:underline inline-flex items-center">';
                    echo 'View Details';
                    echo '<svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">';
                    echo '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>';
                    echo '</svg>';
                    echo '</a>';
                    echo '</div>';
                    echo '</div>';
                    $count++;
                }
            }
            ?>
        </div>
    </div>
</section>

<?php require_once 'includes/footer.php'; ?> 