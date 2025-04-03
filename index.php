<?php
$page_title = 'Home';
require_once 'includes/db.php';
require_once 'includes/header.php';
require_once 'components/service-card.php';
require_once 'components/project-card.php';
?>

<!-- Hero Section -->
<section class="bg-hero py-16 md:py-24">
    <div class="container mx-auto">
        <div class="flex flex-col md:flex-row items-center">
            <div class="md:w-1/2 md:pr-12 mb-8 md:mb-0" data-aos="fade-right" data-aos-duration="1000">
                <h1 class="text-4xl md:text-5xl font-bold mb-6 text-dark-olive">Sejuta Ranting Bonsai Books</h1>
                <p class="text-lg mb-8 text-olive-dark">
                    Welcome to Sejuta Ranting, Malaysia's premier bonsai bookstore. Discover our carefully curated collection of guides, how-tos, and artistic references that will help you master the ancient art of bonsai cultivation. From pemula (beginner) to pakar (expert), we have the perfect resource for your bonsai journey. Jom explore our collection and let the knowledge grow like your little pokok!
                </p>
                <a href="about.php" class="btn btn-primary inline-flex items-center">
                    Explore Our Collection
                    <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </a>
            </div>
            <div class="md:w-1/2" data-aos="fade-left" data-aos-duration="1000" data-aos-delay="200">
                <div class="rounded-lg overflow-hidden shadow-xl">
                    <img src="/Bonsai/Images/Index/IMG_6209.JPG" alt="Bonsai Tree" class="w-full h-auto">
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Services Section -->
<section class="py-16 md:py-24">
    <div class="container mx-auto">
        <div class="text-center mb-12" data-aos="fade-up" data-aos-duration="1000">
            <span class="text-primary font-semibold uppercase tracking-wider">Featured Books</span>
            <h2 class="text-3xl md:text-4xl font-bold mt-2 mb-4 text-dark-olive">Our Bestselling Bonsai Guides</h2>
            <p class="max-w-2xl mx-auto text-olive-dark">
                At Sejuta Ranting, we offer a diverse range of bonsai books to help you nurture and master the art of bonsai cultivation. From beginner guides to advanced techniques, our collection is curated to inspire your bonsai journey. Mesti try these bestsellers!
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <?php
            echo service_card([
                'number' => '01',
                'title' => 'Bonsai Basics',
                'description' => 'By Colin Lewis - The perfect introduction for beginners, this comprehensive guide covers essential techniques with over 200 full-color photos.',
                'image' => '/Bonsai/Images/Index/IMG_6171.JPG',
                'link' => 'single-service.php',
                'delay' => 0
            ]);
            
            echo service_card([
                'number' => '02',
                'title' => 'The Complete Book of Bonsai',
                'description' => 'By Harry Tomlinson - A timeless masterpiece exploring the art and cultivation of bonsai with detailed illustrations and step-by-step instructions.',
                'image' => '/Bonsai/Images/Index/IMG_6168.JPG',
                'link' => 'single-service.php',
                'delay' => 100
            ]);
            
            echo service_card([
                'number' => '03',
                'title' => 'Bonsai with Malaysian Species',
                'description' => 'By Peter Chan - Discover how to cultivate bonsai using native Malaysian tree species, tailored specifically for our tropical climate.',
                'image' => '/Bonsai/Images/Index/IMG_6179.JPG',
                'link' => 'single-service.php',
                'delay' => 200
            ]);
            ?>
        </div>
    </div>
</section>

<!-- About Section -->
<section class="bg-secondary py-16 md:py-24">
    <div class="container mx-auto">
        <div class="flex flex-col md:flex-row">
            <div class="md:w-1/2 mb-8 md:mb-0" data-aos="zoom-in-right" data-aos-duration="1000">
                <span class="text-primary font-semibold uppercase tracking-wider">About us</span>
                <h2 class="text-3xl md:text-4xl font-bold mt-2 mb-4 text-dark-olive">Sejuta Ranting Bookstore</h2>
            </div>
            <div class="md:w-1/2" data-aos="zoom-in-left" data-aos-duration="1000" data-aos-delay="200">
                <h3 class="text-xl md:text-2xl font-bold mb-6 text-olive-dark">Founded in 2024, we are dedicated to promoting the beauty and benefits of Bonsai cultivation in Malaysia through high-quality literature and resources.</h3>
                <p class="mb-4 text-olive-dark">Sejuta Ranting (meaning "Thousand Branches") is your trusted partner in bonsai education. We believe that access to quality information is the foundation for successful bonsai cultivation.</p>
                <p class="mb-6 text-olive-dark">Our bookstore features carefully selected titles from renowned bonsai masters worldwide, with special focus on resources tailored to Malaysia's tropical environment. We ship seluruh Malaysia with kecepatan kilat! Betul betul best!</p>
                <a href="about.php" class="btn btn-primary inline-flex items-center">
                    Learn Our Story
                    <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </a>
            </div>
        </div>
    </div>
</section>

<!-- Projects Section -->
<section class="py-16 md:py-24">
    <div class="container mx-auto">
        <div class="mb-16" data-aos="fade-up" data-aos-duration="1000">
            <span class="text-primary font-semibold uppercase tracking-wider">Book Categories</span>
            <h2 class="text-3xl md:text-4xl font-bold mt-2 text-dark-olive">Explore Our Bonsai Book Collection</h2>
        </div>

        <?php
        echo project_card([
            'title' => 'Beginner Guides',
            'description' => 'Start your bonsai journey with our selection of beginner-friendly guides. These books provide step-by-step instructions for selecting your first bonsai, basic care techniques, and simple styling tips perfect for newcomers.',
            'image' => '/Bonsai/Images/Index/IMG_6177.JPG',
            'location' => 'For Beginners',
            'project_type' => 'Starter Collection',
            'designer' => 'Various Authors',
            'reverse' => false,
            'delay' => 0
        ]);
        
        echo project_card([
            'title' => 'Advanced Techniques',
            'description' => 'Elevate your bonsai skills with our advanced technique collection. These comprehensive books cover complex styling methods, advanced wiring techniques, grafting, and specialized care information for experienced enthusiasts.',
            'image' => '/Bonsai/Images/Index/IMG_6169.JPG',
            'location' => 'For Experienced Enthusiasts',
            'project_type' => 'Advanced Collection',
            'designer' => 'Master Bonsai Artists',
            'reverse' => true,
            'delay' => 100
        ]);
        
        echo project_card([
            'title' => 'Tropical Species Guides',
            'description' => 'Our specially curated collection of books focusing on tropical bonsai varieties perfect for Malaysia\'s climate. Learn how to collect, cultivate and style indigenous species with guidance from local and international experts.',
            'image' => '/Bonsai/Images/Index/IMG_6174.JPG',
            'location' => 'Malaysia & Southeast Asia',
            'project_type' => 'Regional Specialty',
            'designer' => 'Tropical Bonsai Experts',
            'reverse' => false,
            'delay' => 200
        ]);
        ?>

        <div class="text-center mt-8" data-aos="fade-up" data-aos-duration="1000">
            <a href="portfolio.php" class="btn btn-primary inline-flex items-center">
                View All Categories
                <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
            </a>
        </div>
    </div>
</section>

<!-- Testimonial Section -->
<section class="bg-dark-olive py-16 md:py-24 relative z-50">
    <div class="absolute inset-0 opacity-60 bg-cover bg-center" style="background-image: url('/Bonsai/Images/Index/tree-dark-background.jpg');"></div>
    <div class="container mx-auto relative z-10">
        <div class="max-w-3xl mx-auto text-center text-white" data-aos="zoom-in" data-aos-duration="1200">
            <svg class="mx-auto mb-8 opacity-30 w-16 h-16" fill="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path d="M14.017 21v-7.391c0-5.704 3.731-9.57 8.983-10.609l.995 2.151c-2.432.917-3.995 3.638-3.995 5.849h4v10h-9.983zm-14.017 0v-7.391c0-5.704 3.748-9.57 9-10.609l.996 2.151c-2.433.917-3.996 3.638-3.996 5.849h3.983v10h-9.983z" />
            </svg>
            <p class="text-xl md:text-2xl mb-8 italic">
                The selection of bonsai books at Sejuta Ranting is incredible! As a beginner, I was overwhelmed at first, but their staff recommended the perfect guide for me. The book was detailed yet easy to understand, and now my first bonsai is thriving. Best bonsai resource in KL, hands down. Memang terbaik!
            </p>
            <div data-aos="fade-up" data-aos-delay="300" data-aos-duration="800">
                <h3 class="text-xl font-bold">Badrul Adam</h3>
                <p class="text-gray-300">Satisfied Customer</p>
            </div>
        </div>
    </div>
</section>

<?php require_once 'includes/footer.php'; ?> 