<?php
$page_title = 'Services';
require_once 'includes/header.php';
require_once 'components/breadcrumbs.php';
require_once 'components/service-card.php';

echo breadcrumbs([
    'Home' => 'index.php',
    'Services' => ''
], '/Bonsai/Images/Index/tree-dark-background.jpg', 'Our Services');
?>

<!-- Services Introduction -->
<section class="py-16">
    <div class="container mx-auto">
        <div class="text-center max-w-3xl mx-auto mb-16">
            <h2 class="text-3xl font-bold mb-6">Comprehensive Bonsai Services</h2>
            <p class="text-gray-600">
                At Sejuta Ranting, we offer a wide range of professional bonsai services designed to help you nurture, 
                maintain, and appreciate these living works of art. Whether you're a beginner just starting your bonsai 
                journey or an experienced enthusiast looking to expand your collection, we have services tailored to meet your needs.
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            <?php
            echo service_card([
                'number' => '01',
                'title' => 'Bonsai Design & Styling',
                'description' => 'Our expert bonsai artists can help design and style your tree to create the perfect living art piece for your space.',
                'image' => '/Bonsai/Images/Index/IMG_6171.JPG',
                'link' => 'single-service.php?service=design'
            ]);
            
            echo service_card([
                'number' => '02',
                'title' => 'Bonsai Maintenance',
                'description' => 'Regular maintenance services to keep your bonsai healthy, including pruning, wiring, repotting, and seasonal care.',
                'image' => '/Bonsai/Images/Index/IMG_6168.JPG',
                'link' => 'single-service.php?service=maintenance'
            ]);
            
            echo service_card([
                'number' => '03',
                'title' => 'Bonsai Workshops',
                'description' => 'Learn the art of bonsai through our hands-on workshops for beginners and advanced enthusiasts.',
                'image' => '/Bonsai/Images/Index/IMG_6179.JPG',
                'link' => 'single-service.php?service=workshops'
            ]);
            
            echo service_card([
                'number' => '04',
                'title' => 'Custom Bonsai Creation',
                'description' => 'Commission a custom bonsai design for your home, office, or as a special gift for someone.',
                'image' => '/Bonsai/Images/Index/IMG_6177.JPG',
                'link' => 'single-service.php?service=custom'
            ]);
            
            echo service_card([
                'number' => '05',
                'title' => 'Bonsai Health Consultation',
                'description' => 'Expert advice and solutions for bonsai health issues, pest control, and disease management.',
                'image' => '/Bonsai/Images/Index/IMG_6174.JPG',
                'link' => 'single-service.php?service=health'
            ]);
            
            echo service_card([
                'number' => '06',
                'title' => 'Bonsai Garden Design',
                'description' => 'Create a harmonious bonsai garden display with our professional design and installation services.',
                'image' => '/Bonsai/Images/Index/IMG_6169.JPG',
                'link' => 'single-service.php?service=garden'
            ]);
            ?>
        </div>
    </div>
</section>

<!-- Service Process -->
<section class="bg-gray-50 py-16">
    <div class="container mx-auto">
        <div class="text-center mb-16">
            <h2 class="text-3xl font-bold mb-6">Our Service Process</h2>
            <p class="text-gray-600 max-w-3xl mx-auto">
                We follow a systematic approach to deliver high-quality bonsai services tailored to your needs.
            </p>
        </div>

        <div class="flex flex-col md:flex-row justify-between items-start">
            <!-- Step 1 -->
            <div class="md:w-1/4 text-center mb-8 md:mb-0">
                <div class="bg-primary text-white rounded-full w-16 h-16 flex items-center justify-center mx-auto mb-4">
                    <span class="text-xl font-bold">01</span>
                </div>
                <h3 class="text-xl font-bold mb-2">Consultation</h3>
                <p class="text-gray-600">
                    We begin with a thorough consultation to understand your bonsai goals and requirements.
                </p>
            </div>
            
            <!-- Step 2 -->
            <div class="md:w-1/4 text-center mb-8 md:mb-0">
                <div class="bg-primary text-white rounded-full w-16 h-16 flex items-center justify-center mx-auto mb-4">
                    <span class="text-xl font-bold">02</span>
                </div>
                <h3 class="text-xl font-bold mb-2">Assessment</h3>
                <p class="text-gray-600">
                    Our experts assess your bonsai's health, style, and potential for development.
                </p>
            </div>
            
            <!-- Step 3 -->
            <div class="md:w-1/4 text-center mb-8 md:mb-0">
                <div class="bg-primary text-white rounded-full w-16 h-16 flex items-center justify-center mx-auto mb-4">
                    <span class="text-xl font-bold">03</span>
                </div>
                <h3 class="text-xl font-bold mb-2">Implementation</h3>
                <p class="text-gray-600">
                    We carefully execute the agreed-upon plan, whether styling, maintenance, or education.
                </p>
            </div>
            
            <!-- Step 4 -->
            <div class="md:w-1/4 text-center">
                <div class="bg-primary text-white rounded-full w-16 h-16 flex items-center justify-center mx-auto mb-4">
                    <span class="text-xl font-bold">04</span>
                </div>
                <h3 class="text-xl font-bold mb-2">Follow-up</h3>
                <p class="text-gray-600">
                    We provide aftercare guidance and ongoing support to ensure your bonsai thrives.
                </p>
            </div>
        </div>
    </div>
</section>

<!-- Call to Action -->
<section class="py-16 bg-primary text-white">
    <div class="container mx-auto text-center">
        <h2 class="text-3xl font-bold mb-6">Ready to Transform Your Bonsai Experience?</h2>
        <p class="max-w-3xl mx-auto mb-8">
            Contact us today to discuss how our bonsai services can help you create and maintain beautiful living art.
        </p>
        <div class="flex flex-col sm:flex-row justify-center gap-4">
            <a href="contacts.php" class="btn bg-white text-primary hover:bg-gray-100">Contact Us</a>
            <a href="portfolio.php" class="btn border-2 border-white text-white hover:bg-white hover:text-primary">View Our Portfolio</a>
        </div>
    </div>
</section>

<?php require_once 'includes/footer.php'; ?> 