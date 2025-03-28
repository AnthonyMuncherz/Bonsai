<?php
$page_title = 'Portfolio';
require_once 'includes/header.php';
require_once 'components/breadcrumbs.php';

// Output breadcrumbs
echo breadcrumbs([
    'Home' => 'index.php',
    'Collection' => ''
], '', 'Our Bonsai Collection');
?>

<section class="py-16 bg-white">
    <div class="container mx-auto">
        <div class="text-center mb-12 fade-in">
            <h2 class="text-3xl md:text-4xl font-bold mb-4">Exquisite Bonsai Collection</h2>
            <p class="text-lg text-gray-600 max-w-3xl mx-auto">
                Explore our handcrafted bonsai collection, each piece representing years of careful cultivation and artistic vision. 
                From traditional Japanese styles to modern Malaysian interpretations, our collection showcases the beauty and diversity of the bonsai art form.
            </p>
        </div>
        
        <!-- Filter navigation -->
        <div class="flex flex-wrap justify-center mb-10 fade-in fade-in-delay-1">
            <button class="portfolio-filter active px-4 py-2 m-1 rounded-full bg-primary text-white hover:bg-primary-dark transition-all" data-filter="all">All</button>
            <button class="portfolio-filter px-4 py-2 m-1 rounded-full bg-gray-200 hover:bg-primary hover:text-white transition-all" data-filter="shohin">Shohin</button>
            <button class="portfolio-filter px-4 py-2 m-1 rounded-full bg-gray-200 hover:bg-primary hover:text-white transition-all" data-filter="formal">Formal Upright</button>
            <button class="portfolio-filter px-4 py-2 m-1 rounded-full bg-gray-200 hover:bg-primary hover:text-white transition-all" data-filter="forest">Forest Style</button>
            <button class="portfolio-filter px-4 py-2 m-1 rounded-full bg-gray-200 hover:bg-primary hover:text-white transition-all" data-filter="cascade">Cascade</button>
        </div>
        
        <!-- Portfolio grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            <!-- Item 1 -->
            <div class="portfolio-item fade-in fade-in-delay-1" data-category="shohin">
                <div class="bg-white rounded-lg overflow-hidden shadow-lg transition-all hover:shadow-xl">
                    <div class="relative overflow-hidden">
                        <img src="/Bonsai/Images/Portfolio/IMG_6171.JPG" alt="Shohin Bonsai" class="w-full h-72 object-cover hover-zoom">
                        <div class="absolute inset-0 bg-black bg-opacity-20 opacity-0 hover:opacity-100 transition-opacity flex items-center justify-center">
                            <a href="#" class="gallery-item bg-white text-primary rounded-full w-12 h-12 flex items-center justify-center hover:bg-primary hover:text-white transition-all" data-src="/Bonsai/Images/Portfolio/IMG_6171.JPG">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                </svg>
                            </a>
                        </div>
                    </div>
                    <div class="p-6">
                        <h3 class="text-xl font-bold mb-2">Shohin Ficus</h3>
                        <p class="text-gray-600">A miniature bonsai masterpiece, carefully trained to showcase perfect proportions despite its small size.</p>
                    </div>
                </div>
            </div>
            
            <!-- Item 2 -->
            <div class="portfolio-item fade-in fade-in-delay-2" data-category="formal">
                <div class="bg-white rounded-lg overflow-hidden shadow-lg transition-all hover:shadow-xl">
                    <div class="relative overflow-hidden">
                        <img src="/Bonsai/Images/Portfolio/IMG_6180.JPG" alt="Formal Upright Bonsai" class="w-full h-72 object-cover hover-zoom">
                        <div class="absolute inset-0 bg-black bg-opacity-20 opacity-0 hover:opacity-100 transition-opacity flex items-center justify-center">
                            <a href="#" class="gallery-item bg-white text-primary rounded-full w-12 h-12 flex items-center justify-center hover:bg-primary hover:text-white transition-all" data-src="/Bonsai/Images/Portfolio/IMG_6180.JPG">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                </svg>
                            </a>
                        </div>
                    </div>
                    <div class="p-6">
                        <h3 class="text-xl font-bold mb-2">Formal Upright Juniper</h3>
                        <p class="text-gray-600">A classic formal upright style bonsai with straight trunk and symmetrical branching pattern.</p>
                    </div>
                </div>
            </div>
            
            <!-- Item 3 -->
            <div class="portfolio-item fade-in fade-in-delay-3" data-category="forest">
                <div class="bg-white rounded-lg overflow-hidden shadow-lg transition-all hover:shadow-xl">
                    <div class="relative overflow-hidden">
                        <img src="/Bonsai/Images/Portfolio/IMG_6197.JPG" alt="Forest Style Bonsai" class="w-full h-72 object-cover hover-zoom">
                        <div class="absolute inset-0 bg-black bg-opacity-20 opacity-0 hover:opacity-100 transition-opacity flex items-center justify-center">
                            <a href="#" class="gallery-item bg-white text-primary rounded-full w-12 h-12 flex items-center justify-center hover:bg-primary hover:text-white transition-all" data-src="/Bonsai/Images/Portfolio/IMG_6197.JPG">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                </svg>
                            </a>
                        </div>
                    </div>
                    <div class="p-6">
                        <h3 class="text-xl font-bold mb-2">Miniature Forest</h3>
                        <p class="text-gray-600">A group of carefully arranged trees creating the impression of a natural forest landscape.</p>
                    </div>
                </div>
            </div>
            
            <!-- Item 4 -->
            <div class="portfolio-item fade-in fade-in-delay-1" data-category="cascade">
                <div class="bg-white rounded-lg overflow-hidden shadow-lg transition-all hover:shadow-xl">
                    <div class="relative overflow-hidden">
                        <img src="/Bonsai/Images/Portfolio/IMG_6179.JPG" alt="Cascade Bonsai" class="w-full h-72 object-cover hover-zoom">
                        <div class="absolute inset-0 bg-black bg-opacity-20 opacity-0 hover:opacity-100 transition-opacity flex items-center justify-center">
                            <a href="#" class="gallery-item bg-white text-primary rounded-full w-12 h-12 flex items-center justify-center hover:bg-primary hover:text-white transition-all" data-src="/Bonsai/Images/Portfolio/IMG_6179.JPG">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                </svg>
                            </a>
                        </div>
                    </div>
                    <div class="p-6">
                        <h3 class="text-xl font-bold mb-2">Cascade Pine</h3>
                        <p class="text-gray-600">A dramatic cascade style bonsai that mimics trees growing on mountainsides with trunks extending downward.</p>
                    </div>
                </div>
            </div>
            
            <!-- Item 5 -->
            <div class="portfolio-item fade-in fade-in-delay-2" data-category="shohin">
                <div class="bg-white rounded-lg overflow-hidden shadow-lg transition-all hover:shadow-xl">
                    <div class="relative overflow-hidden">
                        <img src="/Bonsai/Images/Portfolio/IMG_6173.JPG" alt="Shohin Maple Bonsai" class="w-full h-72 object-cover hover-zoom">
                        <div class="absolute inset-0 bg-black bg-opacity-20 opacity-0 hover:opacity-100 transition-opacity flex items-center justify-center">
                            <a href="#" class="gallery-item bg-white text-primary rounded-full w-12 h-12 flex items-center justify-center hover:bg-primary hover:text-white transition-all" data-src="/Bonsai/Images/Portfolio/IMG_6173.JPG">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                </svg>
                            </a>
                        </div>
                    </div>
                    <div class="p-6">
                        <h3 class="text-xl font-bold mb-2">Miniature Maple</h3>
                        <p class="text-gray-600">A delicate shohin-sized Japanese maple bonsai showcasing beautiful leaf patterns.</p>
                    </div>
                </div>
            </div>
            
            <!-- Item 6 -->
            <div class="portfolio-item fade-in fade-in-delay-3" data-category="formal">
                <div class="bg-white rounded-lg overflow-hidden shadow-lg transition-all hover:shadow-xl">
                    <div class="relative overflow-hidden">
                        <img src="/Bonsai/Images/Portfolio/IMG_6177.JPG" alt="Formal Bonsai" class="w-full h-72 object-cover hover-zoom">
                        <div class="absolute inset-0 bg-black bg-opacity-20 opacity-0 hover:opacity-100 transition-opacity flex items-center justify-center">
                            <a href="#" class="gallery-item bg-white text-primary rounded-full w-12 h-12 flex items-center justify-center hover:bg-primary hover:text-white transition-all" data-src="/Bonsai/Images/Portfolio/IMG_6177.JPG">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                </svg>
                            </a>
                        </div>
                    </div>
                    <div class="p-6">
                        <h3 class="text-xl font-bold mb-2">Elder Elm</h3>
                        <p class="text-gray-600">A mature formal upright elm bonsai with impressive trunk character and ramification.</p>
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
            <h2 class="text-3xl md:text-4xl font-bold mb-6">Interested in Starting Your Own Collection?</h2>
            <p class="text-lg mb-8 max-w-3xl mx-auto">
                Whether you're looking to purchase a bonsai, learn the art, or need help maintaining your existing collection, 
                our team of experts is ready to assist you.
            </p>
            <div class="flex flex-wrap justify-center gap-4">
                <a href="services.php" class="btn btn-primary">Explore Our Services</a>
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