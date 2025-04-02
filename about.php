<?php
$page_title = 'About Us';
require_once 'includes/db.php'; // Add this line to ensure session is available
require_once 'includes/header.php';
require_once 'components/breadcrumbs.php';

// Display breadcrumbs
echo breadcrumbs([
    'Home' => 'index.php',
    'About' => ''
], '/Bonsai/Images/About us/tree-dark-background.jpg', 'About Us');
?>

<!-- About Header -->
<section class="pt-16 pb-8 bg-gray-50">
    <div class="container mx-auto">
        <h1 class="text-4xl font-bold text-center">About Sejuta Ranting</h1>
        <p class="text-center text-gray-600 mt-2">Malaysia's Premier Bonsai Bookstore</p>
    </div>
</section>

<!-- About Content -->
<section class="py-12">
    <div class="container mx-auto max-w-4xl">
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <div class="md:flex">
                <div class="md:w-1/2">
                    <img src="/Bonsai/Images/Index/IMG_6209.JPG" alt="Bonsai Tree" class="w-full h-full object-cover">
                </div>
                <div class="md:w-1/2 p-8">
                    <h2 class="text-2xl font-bold mb-4">Our Story</h2>
                    <p class="mb-4 text-gray-700">
                        Founded in 2024, Sejuta Ranting (meaning "Thousand Branches") is Malaysia's first bookstore dedicated entirely to the art and practice of bonsai cultivation.
                    </p>
                    <p class="mb-4 text-gray-700">
                        Our mission is to promote the beauty and benefits of Bonsai cultivation in Malaysia through high-quality literature and resources tailored to our unique tropical climate and local species.
                    </p>
                    <p class="text-gray-700">
                        We believe that access to quality information is the foundation for successful bonsai cultivation, which is why we've curated the most comprehensive collection of bonsai books in Southeast Asia.
                    </p>
                </div>
            </div>
        </div>

        <div class="mt-12 bg-white rounded-lg shadow-md p-8">
            <h2 class="text-2xl font-bold mb-6 text-center">Why Choose Us</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="text-center">
                    <div class="bg-primary bg-opacity-10 p-4 rounded-full mx-auto w-20 h-20 flex items-center justify-center mb-4">
                        <svg class="w-10 h-10 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold mb-2">Expert Selection</h3>
                    <p class="text-gray-600">Each book in our collection is personally reviewed and selected by bonsai experts</p>
                </div>
                
                <div class="text-center">
                    <div class="bg-primary bg-opacity-10 p-4 rounded-full mx-auto w-20 h-20 flex items-center justify-center mb-4">
                        <svg class="w-10 h-10 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold mb-2">Nationwide Delivery</h3>
                    <p class="text-gray-600">We ship throughout Malaysia with fast and reliable service</p>
                </div>
                
                <div class="text-center">
                    <div class="bg-primary bg-opacity-10 p-4 rounded-full mx-auto w-20 h-20 flex items-center justify-center mb-4">
                        <svg class="w-10 h-10 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold mb-2">Community Support</h3>
                    <p class="text-gray-600">Join our growing community of bonsai enthusiasts across Malaysia</p>
                </div>
            </div>
        </div>
        
        <div class="mt-12 bg-white rounded-lg shadow-md p-8">
            <h2 class="text-2xl font-bold mb-6">Our Team</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div class="flex items-center">
                    <div class="bg-gray-200 rounded-full w-24 h-24 flex-shrink-0">
                        <div class="w-full h-full flex items-center justify-center text-gray-400">
                            <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-6">
                        <h3 class="text-xl font-semibold">Ahmad Razali</h3>
                        <p class="text-gray-600 mb-2">Founder & Bonsai Expert</p>
                        <p class="text-gray-700">With over 15 years of experience in bonsai cultivation, Ahmad founded Sejuta Ranting to share his passion with fellow Malaysians.</p>
                    </div>
                </div>
                
                <div class="flex items-center">
                    <div class="bg-gray-200 rounded-full w-24 h-24 flex-shrink-0">
                        <div class="w-full h-full flex items-center justify-center text-gray-400">
                            <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-6">
                        <h3 class="text-xl font-semibold">Nurul Izzati</h3>
                        <p class="text-gray-600 mb-2">Literature Specialist</p>
                        <p class="text-gray-700">Nurul curates our book collection, ensuring we offer the most relevant and high-quality bonsai literature available.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php require_once 'includes/footer.php'; ?> 