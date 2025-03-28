<?php
$page_title = 'Books & Services';
require_once 'includes/header.php';
require_once 'components/breadcrumbs.php';
require_once 'components/service-card.php';

echo breadcrumbs([
    'Home' => 'index.php',
    'Books & Services' => ''
], '/Bonsai/Images/Index/tree-dark-background.jpg', 'Our Books & Services');
?>

<!-- Services Introduction -->
<section class="py-16">
    <div class="container mx-auto">
        <div class="text-center max-w-3xl mx-auto mb-16">
            <h2 class="text-3xl font-bold mb-6">Comprehensive Bonsai Book Collection</h2>
            <p class="text-gray-600">
                At Sejuta Ranting, we offer a wide range of bonsai books and educational resources designed to help you 
                master the art of bonsai cultivation. Whether you're a beginner just starting your bonsai 
                journey or an experienced enthusiast looking to refine your techniques, we have the perfect books to guide your path.
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            <?php
            echo service_card([
                'number' => '01',
                'title' => 'Beginner Guides',
                'description' => 'Start your bonsai journey with our collection of beginner-friendly books covering essential care, basic styling, and species selection.',
                'image' => '/Bonsai/Images/Index/IMG_6171.JPG',
                'link' => 'single-service.php?service=beginner'
            ]);
            
            echo service_card([
                'number' => '02',
                'title' => 'Styling & Technique Books',
                'description' => 'Enhance your bonsai styling skills with comprehensive guides on pruning, wiring, repotting, and artistic design principles.',
                'image' => '/Bonsai/Images/Index/IMG_6168.JPG',
                'link' => 'single-service.php?service=styling'
            ]);
            
            echo service_card([
                'number' => '03',
                'title' => 'Species-Specific Guides',
                'description' => 'Specialized books focusing on particular bonsai species, with detailed care instructions tailored to their unique requirements.',
                'image' => '/Bonsai/Images/Index/IMG_6179.JPG',
                'link' => 'single-service.php?service=species'
            ]);
            
            echo service_card([
                'number' => '04',
                'title' => 'Tropical Bonsai Collection',
                'description' => 'Books specifically focused on tropical bonsai varieties suitable for Malaysia\'s climate, including native Southeast Asian species.',
                'image' => '/Bonsai/Images/Index/IMG_6177.JPG',
                'link' => 'single-service.php?service=tropical'
            ]);
            
            echo service_card([
                'number' => '05',
                'title' => 'Advanced Practitioner Texts',
                'description' => 'Sophisticated resources for experienced enthusiasts, covering advanced techniques, problem-solving, and artistic refinement.',
                'image' => '/Bonsai/Images/Index/IMG_6174.JPG',
                'link' => 'single-service.php?service=advanced'
            ]);
            
            echo service_card([
                'number' => '06',
                'title' => 'Bonsai Journal & Planners',
                'description' => 'Track your bonsai development with our selection of specialized journals, maintenance planners, and record-keeping guides.',
                'image' => '/Bonsai/Images/Index/IMG_6169.JPG',
                'link' => 'single-service.php?service=journals'
            ]);
            ?>
        </div>
    </div>
</section>

<!-- Service Process -->
<section class="bg-gray-50 py-16">
    <div class="container mx-auto">
        <div class="text-center mb-16">
            <h2 class="text-3xl font-bold mb-6">Our Bookstore Services</h2>
            <p class="text-gray-600 max-w-3xl mx-auto">
                Beyond our book collection, we offer several services to enhance your bonsai learning experience.
            </p>
        </div>

        <div class="flex flex-col md:flex-row justify-between items-start">
            <!-- Step 1 -->
            <div class="md:w-1/4 text-center mb-8 md:mb-0">
                <div class="bg-primary text-white rounded-full w-16 h-16 flex items-center justify-center mx-auto mb-4">
                    <span class="text-xl font-bold">01</span>
                </div>
                <h3 class="text-xl font-bold mb-2">Nationwide Delivery</h3>
                <p class="text-gray-600">
                    We offer fast and reliable shipping to all locations across Malaysia. Penghantaran kilat kemana-mana!
                </p>
            </div>
            
            <!-- Step 2 -->
            <div class="md:w-1/4 text-center mb-8 md:mb-0">
                <div class="bg-primary text-white rounded-full w-16 h-16 flex items-center justify-center mx-auto mb-4">
                    <span class="text-xl font-bold">02</span>
                </div>
                <h3 class="text-xl font-bold mb-2">Book Consultations</h3>
                <p class="text-gray-600">
                    Not sure which book is right for you? Our knowledgeable staff can provide personalized recommendations.
                </p>
            </div>
            
            <!-- Step 3 -->
            <div class="md:w-1/4 text-center mb-8 md:mb-0">
                <div class="bg-primary text-white rounded-full w-16 h-16 flex items-center justify-center mx-auto mb-4">
                    <span class="text-xl font-bold">03</span>
                </div>
                <h3 class="text-xl font-bold mb-2">Special Orders</h3>
                <p class="text-gray-600">
                    We can source rare and specialized bonsai books that aren't in our regular inventory upon request.
                </p>
            </div>
            
            <!-- Step 4 -->
            <div class="md:w-1/4 text-center">
                <div class="bg-primary text-white rounded-full w-16 h-16 flex items-center justify-center mx-auto mb-4">
                    <span class="text-xl font-bold">04</span>
                </div>
                <h3 class="text-xl font-bold mb-2">Book Club</h3>
                <p class="text-gray-600">
                    Join our monthly bonsai book club where we discuss techniques, share experiences, and learn together.
                </p>
            </div>
        </div>
    </div>
</section>

<!-- Call to Action -->
<section class="py-16 bg-primary text-white">
    <div class="container mx-auto text-center">
        <h2 class="text-3xl font-bold mb-6">Ready to Elevate Your Bonsai Knowledge?</h2>
        <p class="max-w-3xl mx-auto mb-8">
            Browse our collection today or contact us for personalized book recommendations to help you on your bonsai journey.
        </p>
        <div class="flex flex-col sm:flex-row justify-center gap-4">
            <a href="contacts.php" class="btn bg-white text-primary hover:bg-gray-100">Contact Us</a>
            <a href="portfolio.php" class="btn border-2 border-white text-white hover:bg-white hover:text-primary">View Our Collection</a>
        </div>
    </div>
</section>

<?php require_once 'includes/footer.php'; ?> 