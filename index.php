<?php
$page_title = 'Home';
require_once 'includes/header.php';
require_once 'components/service-card.php';
require_once 'components/project-card.php';
?>

<!-- Hero Section -->
<section class="bg-secondary py-16 md:py-24">
    <div class="container mx-auto">
        <div class="flex flex-col md:flex-row items-center">
            <div class="md:w-1/2 md:pr-12 mb-8 md:mb-0" data-aos="fade-right" data-aos-duration="1000">
                <h1 class="text-4xl md:text-5xl font-bold mb-6">The Art and Benefits of Bonsai</h1>
                <p class="text-lg mb-8">
                    Welcome to Sejuta Ranting, Malaysia's premier platform for exploring the captivating world of Bonsai. Here, we celebrate the artistry, heritage, and therapeutic essence of Bonsai cultivation, tailored to Malaysia's unique tropical environment. Let us inspire you with the knowledge and tools to nurture miniature trees that bring harmony and beauty into your life.
                </p>
                <a href="about.php" class="btn btn-primary inline-flex items-center">
                    Read more
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
            <span class="text-primary font-semibold uppercase tracking-wider">Services</span>
            <h2 class="text-3xl md:text-4xl font-bold mt-2 mb-4">Sejuta Ranting's Bonsai Gallery</h2>
            <p class="max-w-2xl mx-auto text-gray-600">
                At Sejuta Ranting, we offer a diverse range of Bonsai-related services to help you cultivate and appreciate the timeless beauty of Bonsai trees. Here are some of the most popular services and resources we provide to enthusiasts across Malaysia.
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <?php
            echo service_card([
                'number' => '01',
                'title' => 'Decorating',
                'description' => 'Our decorating & painting services will help you transform your house and protect it against weather.',
                'image' => '/Bonsai/Images/Index/IMG_6171.JPG',
                'link' => 'single-service.php',
                'delay' => 0
            ]);
            
            echo service_card([
                'number' => '02',
                'title' => 'Garden design',
                'description' => 'We design custom-made gardens and outdoor spaces that work for you, your family, and your lifestyle.',
                'image' => '/Bonsai/Images/Index/IMG_6168.JPG',
                'link' => 'single-service.php',
                'delay' => 100
            ]);
            
            echo service_card([
                'number' => '03',
                'title' => 'Project planning',
                'description' => 'You can fully rely on our professional project planning services that guarantee the best results.',
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
                <h2 class="text-3xl md:text-4xl font-bold mt-2 mb-4">Pulpy Garden Sdn Bhd</h2>
            </div>
            <div class="md:w-1/2" data-aos="zoom-in-left" data-aos-duration="1000" data-aos-delay="200">
                <h3 class="text-xl md:text-2xl font-bold mb-6">Founded in 2024, we are dedicated to promoting the beauty and benefits of Bonsai cultivation in Malaysia. Our mission is to inspire harmony between people and nature through the art of Bonsai.</h3>
                <p class="mb-4">Pulpy Garden Sdn Bhd is your trusted partner in Bonsai care and education. We believe that cultivating Bonsai trees not only enhances your space but also nurtures mindfulness and patience.</p>
                <p class="mb-6">Our holistic approach allows us to offer high-quality resources, tools, and guidance tailored to Malaysia's tropical environment. Whether you're a beginner or an experienced Bonsai enthusiast.</p>
                <a href="about.php" class="btn btn-primary inline-flex items-center">
                    Read more
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
            <span class="text-primary font-semibold uppercase tracking-wider">our projects</span>
            <h2 class="text-3xl md:text-4xl font-bold mt-2 text-primary">Exquisite Bonsai Creations for Everyone</h2>
        </div>

        <?php
        echo project_card([
            'title' => 'Bonsai Haven Project',
            'description' => 'One of our recent highlights is the creation of a tranquil Bonsai garden, blending traditional Bonsai cultivation techniques with innovative landscaping designs to inspire harmony and mindfulness.',
            'image' => '/Bonsai/Images/Index/IMG_6177.JPG',
            'location' => 'Pulpy Garden, Kuala Lumpur',
            'project_type' => 'Bonsai Showcase',
            'designer' => 'Shazriq Azrin bin Senawi',
            'reverse' => false,
            'delay' => 0
        ]);
        
        echo project_card([
            'title' => 'Serenity Bonsai Garden',
            'description' => 'This project was designed for a small residential area in Kuala Lumpur, Malaysia. It features a beautifully curated Bonsai garden, combining traditional techniques with modern landscaping aesthetics to create a serene and peaceful outdoor space.',
            'image' => '/Bonsai/Images/Index/IMG_6169.JPG',
            'location' => 'Kuala Lumpur, Malaysia',
            'project_type' => 'Bonsai Styling and Garden Design',
            'designer' => 'Shazriq Azrin bin Senawi',
            'reverse' => true,
            'delay' => 100
        ]);
        
        echo project_card([
            'title' => 'Harmony Bonsai Sanctuary',
            'description' => 'For this award-winning project by Pulpy Garden, we crafted a tranquil Bonsai retreat designed to complement the natural surroundings and evoke a sense of timeless elegance. The space integrates traditional Bonsai techniques with modern landscaping to create a peaceful haven for relaxation and mindfulness.',
            'image' => '/Bonsai/Images/Index/IMG_6174.JPG',
            'location' => 'Kuala Lumpur, Malaysia',
            'project_type' => 'Bonsai Landscaping and Design',
            'designer' => 'Musthaqim Ahmad',
            'reverse' => false,
            'delay' => 200
        ]);
        ?>

        <div class="text-center mt-8" data-aos="fade-up" data-aos-duration="1000">
            <a href="portfolio.php" class="btn btn-primary inline-flex items-center">
                More projects
                <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
            </a>
        </div>
    </div>
</section>

<!-- Testimonial Section -->
<section class="py-16 md:py-24 bg-cover bg-center relative parallax-element" style="background-image: linear-gradient(rgba(0, 0, 0, 0.7), rgba(0, 0, 0, 0.7)), url('/Bonsai/Images/Index/tree-dark-background.jpg');" data-parallax-speed="0.2">
    <div class="container mx-auto relative z-10 text-white">
        <div class="max-w-3xl mx-auto text-center" data-aos="zoom-in" data-aos-duration="1200">
            <svg class="mx-auto mb-8 opacity-30 w-16 h-16" fill="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path d="M14.017 21v-7.391c0-5.704 3.731-9.57 8.983-10.609l.995 2.151c-2.432.917-3.995 3.638-3.995 5.849h4v10h-9.983zm-14.017 0v-7.391c0-5.704 3.748-9.57 9-10.609l.996 2.151c-2.433.917-3.996 3.638-3.996 5.849h3.983v10h-9.983z" />
            </svg>
            <p class="text-xl md:text-2xl mb-8 italic">
                The team at Pulpy Garden is incredibly knowledgeable, friendly, and detail-oriented. They listened to our ideas and transformed them into a breathtaking Bonsai garden. Every detail was meticulously planned, and the final result exceeded our expectations.
            </p>
            <div data-aos="fade-up" data-aos-delay="300" data-aos-duration="800">
                <h3 class="text-xl font-bold">Badrul Adam</h3>
                <p class="text-gray-300">Client</p>
            </div>
        </div>
    </div>
</section>

<?php require_once 'includes/footer.php'; ?> 