<?php
$page_title = 'Book Collection';
require_once 'includes/header.php';
require_once 'components/breadcrumbs.php';

// Output breadcrumbs
echo breadcrumbs([
    'Home' => 'index.php',
    'Book Collection' => ''
], '', 'Our Bonsai Book Collection');
?>

<section class="py-16 bg-white">
    <div class="container mx-auto">
        <div class="text-center mb-12 fade-in">
            <h2 class="text-3xl md:text-4xl font-bold mb-4">Comprehensive Bonsai Library</h2>
            <p class="text-lg text-gray-600 max-w-3xl mx-auto">
                Browse our extensive collection of bonsai books, featuring titles from renowned experts worldwide. 
                From beginner guides to specialized techniques, our carefully curated selection offers knowledge 
                for every stage of your bonsai journey.
            </p>
        </div>
        
        <!-- Filter navigation -->
        <div class="flex flex-wrap justify-center mb-10 fade-in fade-in-delay-1">
            <button class="portfolio-filter active px-4 py-2 m-1 rounded-full bg-primary text-white hover:bg-primary-dark transition-all" data-filter="all">All Books</button>
            <button class="portfolio-filter px-4 py-2 m-1 rounded-full bg-gray-200 hover:bg-primary hover:text-white transition-all" data-filter="beginner">Beginner</button>
            <button class="portfolio-filter px-4 py-2 m-1 rounded-full bg-gray-200 hover:bg-primary hover:text-white transition-all" data-filter="advanced">Advanced</button>
            <button class="portfolio-filter px-4 py-2 m-1 rounded-full bg-gray-200 hover:bg-primary hover:text-white transition-all" data-filter="species">Species Guides</button>
            <button class="portfolio-filter px-4 py-2 m-1 rounded-full bg-gray-200 hover:bg-primary hover:text-white transition-all" data-filter="tropical">Tropical</button>
        </div>
        
        <!-- Portfolio grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            <!-- Item 1 -->
            <div class="portfolio-item fade-in fade-in-delay-1" data-category="beginner">
                <div class="bg-white rounded-lg overflow-hidden shadow-lg transition-all hover:shadow-xl">
                    <div class="relative overflow-hidden">
                        <img src="/Bonsai/Images/Portfolio/IMG_6171.JPG" alt="Bonsai Basics Book" class="w-full h-72 object-cover hover-zoom">
                        <div class="absolute inset-0 bg-black bg-opacity-20 opacity-0 hover:opacity-100 transition-opacity flex items-center justify-center">
                            <a href="#" class="gallery-item bg-white text-primary rounded-full w-12 h-12 flex items-center justify-center hover:bg-primary hover:text-white transition-all" data-src="/Bonsai/Images/Portfolio/IMG_6171.JPG">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                </svg>
                            </a>
                        </div>
                    </div>
                    <div class="p-6">
                        <h3 class="text-xl font-bold mb-2">Bonsai Basics: The Complete Guide</h3>
                        <p class="text-gray-600">By Colin Lewis - The perfect introduction for beginners with over 200 full-color photos and essential care techniques.</p>
                    </div>
                </div>
            </div>
            
            <!-- Item 2 -->
            <div class="portfolio-item fade-in fade-in-delay-2" data-category="advanced">
                <div class="bg-white rounded-lg overflow-hidden shadow-lg transition-all hover:shadow-xl">
                    <div class="relative overflow-hidden">
                        <img src="/Bonsai/Images/Portfolio/IMG_6180.JPG" alt="Advanced Bonsai Book" class="w-full h-72 object-cover hover-zoom">
                        <div class="absolute inset-0 bg-black bg-opacity-20 opacity-0 hover:opacity-100 transition-opacity flex items-center justify-center">
                            <a href="#" class="gallery-item bg-white text-primary rounded-full w-12 h-12 flex items-center justify-center hover:bg-primary hover:text-white transition-all" data-src="/Bonsai/Images/Portfolio/IMG_6180.JPG">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                </svg>
                            </a>
                        </div>
                    </div>
                    <div class="p-6">
                        <h3 class="text-xl font-bold mb-2">Advanced Bonsai Techniques</h3>
                        <p class="text-gray-600">By Harry Tomlinson - Master complex styling, wiring, and artistic refinement with this comprehensive guide for experienced enthusiasts.</p>
                    </div>
                </div>
            </div>
            
            <!-- Item 3 -->
            <div class="portfolio-item fade-in fade-in-delay-3" data-category="species">
                <div class="bg-white rounded-lg overflow-hidden shadow-lg transition-all hover:shadow-xl">
                    <div class="relative overflow-hidden">
                        <img src="/Bonsai/Images/Portfolio/IMG_6197.JPG" alt="Pine Bonsai Guide" class="w-full h-72 object-cover hover-zoom">
                        <div class="absolute inset-0 bg-black bg-opacity-20 opacity-0 hover:opacity-100 transition-opacity flex items-center justify-center">
                            <a href="#" class="gallery-item bg-white text-primary rounded-full w-12 h-12 flex items-center justify-center hover:bg-primary hover:text-white transition-all" data-src="/Bonsai/Images/Portfolio/IMG_6197.JPG">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                </svg>
                            </a>
                        </div>
                    </div>
                    <div class="p-6">
                        <h3 class="text-xl font-bold mb-2">The Complete Guide to Pine Bonsai</h3>
                        <p class="text-gray-600">By John Naka - Detailed species-specific guide focusing on pine varieties, with specialized care and styling information.</p>
                    </div>
                </div>
            </div>
            
            <!-- Item 4 -->
            <div class="portfolio-item fade-in fade-in-delay-1" data-category="tropical">
                <div class="bg-white rounded-lg overflow-hidden shadow-lg transition-all hover:shadow-xl">
                    <div class="relative overflow-hidden">
                        <img src="/Bonsai/Images/Portfolio/IMG_6179.JPG" alt="Tropical Bonsai Book" class="w-full h-72 object-cover hover-zoom">
                        <div class="absolute inset-0 bg-black bg-opacity-20 opacity-0 hover:opacity-100 transition-opacity flex items-center justify-center">
                            <a href="#" class="gallery-item bg-white text-primary rounded-full w-12 h-12 flex items-center justify-center hover:bg-primary hover:text-white transition-all" data-src="/Bonsai/Images/Portfolio/IMG_6179.JPG">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                </svg>
                            </a>
                        </div>
                    </div>
                    <div class="p-6">
                        <h3 class="text-xl font-bold mb-2">Tropical Bonsai Masterclass</h3>
                        <p class="text-gray-600">By Peter Chan - Specialized guide for cultivating tropical bonsai varieties in hot and humid climates like Malaysia.</p>
                    </div>
                </div>
            </div>
            
            <!-- Item 5 -->
            <div class="portfolio-item fade-in fade-in-delay-2" data-category="beginner">
                <div class="bg-white rounded-lg overflow-hidden shadow-lg transition-all hover:shadow-xl">
                    <div class="relative overflow-hidden">
                        <img src="/Bonsai/Images/Portfolio/IMG_6173.JPG" alt="Bonsai for Beginners" class="w-full h-72 object-cover hover-zoom">
                        <div class="absolute inset-0 bg-black bg-opacity-20 opacity-0 hover:opacity-100 transition-opacity flex items-center justify-center">
                            <a href="#" class="gallery-item bg-white text-primary rounded-full w-12 h-12 flex items-center justify-center hover:bg-primary hover:text-white transition-all" data-src="/Bonsai/Images/Portfolio/IMG_6173.JPG">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                </svg>
                            </a>
                        </div>
                    </div>
                    <div class="p-6">
                        <h3 class="text-xl font-bold mb-2">Bonsai for Beginners</h3>
                        <p class="text-gray-600">By Bonsai Empire - Step-by-step guide for newcomers with easy-to-follow instructions for selecting, potting and maintaining your first bonsai.</p>
                    </div>
                </div>
            </div>
            
            <!-- Item 6 -->
            <div class="portfolio-item fade-in fade-in-delay-3" data-category="species">
                <div class="bg-white rounded-lg overflow-hidden shadow-lg transition-all hover:shadow-xl">
                    <div class="relative overflow-hidden">
                        <img src="/Bonsai/Images/Portfolio/IMG_6177.JPG" alt="Maple Bonsai Guide" class="w-full h-72 object-cover hover-zoom">
                        <div class="absolute inset-0 bg-black bg-opacity-20 opacity-0 hover:opacity-100 transition-opacity flex items-center justify-center">
                            <a href="#" class="gallery-item bg-white text-primary rounded-full w-12 h-12 flex items-center justify-center hover:bg-primary hover:text-white transition-all" data-src="/Bonsai/Images/Portfolio/IMG_6177.JPG">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                </svg>
                            </a>
                        </div>
                    </div>
                    <div class="p-6">
                        <h3 class="text-xl font-bold mb-2">Bonsai with Japanese Maples</h3>
                        <p class="text-gray-600">By Peter Adams - Specialized guide to creating and maintaining stunning maple bonsai with seasonal care instructions.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Call to action section -->
<section class="py-16 bg-secondary">
    <div class="container mx-auto">
        <div class="text-center fade-in">
            <h2 class="text-3xl md:text-4xl font-bold mb-6">Ready to Start Your Bonsai Library?</h2>
            <p class="text-lg mb-8 max-w-3xl mx-auto">
                Whether you're looking for your first bonsai book or adding to your collection, 
                we're here to help you find the perfect resources for your bonsai journey. Our knowledgeable team 
                can provide personalized recommendations based on your experience level and interests.
            </p>
            <div class="flex flex-wrap justify-center gap-4">
                <a href="services.php" class="btn btn-primary">View All Categories</a>
                <a href="contacts.php" class="btn btn-outline">Contact Us</a>
            </div>
        </div>
    </div>
</section>

<script>
// Portfolio filter functionality
document.addEventListener('DOMContentLoaded', function() {
    const filterButtons = document.querySelectorAll('.portfolio-filter');
    const portfolioItems = document.querySelectorAll('.portfolio-item');
    
    filterButtons.forEach(button => {
        button.addEventListener('click', function() {
            // Remove active class from all buttons
            filterButtons.forEach(btn => {
                btn.classList.remove('active', 'bg-primary', 'text-white');
                btn.classList.add('bg-gray-200');
            });
            
            // Add active class to clicked button
            this.classList.add('active', 'bg-primary', 'text-white');
            this.classList.remove('bg-gray-200');
            
            const filter = this.getAttribute('data-filter');
            
            // Show/hide items based on filter
            portfolioItems.forEach(item => {
                if (filter === 'all' || item.getAttribute('data-category') === filter) {
                    item.style.display = 'block';
                } else {
                    item.style.display = 'none';
                }
            });
        });
    });
});
</script>

<?php require_once 'includes/footer.php'; ?> 