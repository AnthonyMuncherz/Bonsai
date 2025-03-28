<?php
$page_title = 'About';
require_once 'includes/header.php';
require_once 'components/breadcrumbs.php';

// Display breadcrumbs
echo breadcrumbs([
    'Home' => 'index.php',
    'About' => ''
], '/Bonsai/Images/About us/tree-dark-background.jpg', 'About Us');
?>

<!-- Mission and Vision -->
<section class="py-16">
    <div class="container mx-auto">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <div>
                <h3 class="text-2xl font-bold mb-4">Our Mission</h3>
                <p class="text-gray-600">
                    Our mission at The Art and Benefits of Bonsai is to inspire Malaysians by promoting the rich heritage and therapeutic benefits of Bonsai cultivation, providing education tailored to Malaysia's tropical environment, fostering a vibrant community of Bonsai enthusiasts, and encouraging sustainable gardening practices to preserve this living art form for future generations.
                </p>
            </div>
            <div>
                <h3 class="text-2xl font-bold mb-4">Our Vision</h3>
                <p class="text-gray-600">
                    Our vision is to become Malaysia's leading platform for Bonsai cultivation, inspiring a deep appreciation for the art of Bonsai while fostering a harmonious connection between people and nature. We aspire to create a community where tradition meets innovation, making Bonsai a cherished part of every Malaysian's life.
                </p>
            </div>
        </div>
    </div>
</section>

<!-- Values -->
<section class="bg-secondary py-16">
    <div class="container mx-auto">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            <!-- Feature 1 -->
            <div class="bg-white p-8 rounded-lg shadow-md">
                <div class="text-primary mb-4">
                    <svg class="w-10 h-10" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                        <path d="M10.894 2.553a1 1 0 00-1.788 0l-7 14a1 1 0 001.169 1.409l5-1.429A1 1 0 009 15.571V11a1 1 0 112 0v4.571a1 1 0 00.725.962l5 1.428a1 1 0 001.17-1.408l-7-14z"></path>
                    </svg>
                </div>
                <h4 class="text-xl font-bold mb-3">Years of Experience</h4>
                <p class="text-gray-600">
                    Bring years of passion and dedication to cultivating and sharing this timeless tradition.
                </p>
            </div>
            
            <!-- Feature 2 -->
            <div class="bg-white p-8 rounded-lg shadow-md">
                <div class="text-primary mb-4">
                    <svg class="w-10 h-10" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                        <path d="M5 4a1 1 0 00-2 0v7.268a2 2 0 000 3.464V16a1 1 0 102 0v-1.268a2 2 0 000-3.464V4zM11 4a1 1 0 10-2 0v1.268a2 2 0 000 3.464V16a1 1 0 102 0V8.732a2 2 0 000-3.464V4zM16 3a1 1 0 011 1v7.268a2 2 0 010 3.464V16a1 1 0 11-2 0v-1.268a2 2 0 010-3.464V4a1 1 0 011-1z"></path>
                    </svg>
                </div>
                <h4 class="text-xl font-bold mb-3">Creative Approach</h4>
                <p class="text-gray-600">
                    Embrace a creative approach by combining traditional Bonsai cultivation techniques with modern design and technology.
                </p>
            </div>
            
            <!-- Feature 3 -->
            <div class="bg-white p-8 rounded-lg shadow-md">
                <div class="text-primary mb-4">
                    <svg class="w-10 h-10" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                        <path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z"></path>
                    </svg>
                </div>
                <h4 class="text-xl font-bold mb-3">Customer-Oriented Service</h4>
                <p class="text-gray-600">
                    Customer-oriented experience by prioritizing the needs and interests of our audience.
                </p>
            </div>
        </div>
    </div>
</section>

<?php require_once 'includes/footer.php'; ?> 