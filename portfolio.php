<?php
$page_title = 'Collection';
require_once 'includes/db.php'; // Add this line to ensure session is available
require_once 'includes/header.php';
require_once 'components/breadcrumbs.php';

// Output breadcrumbs
echo breadcrumbs([
    'Home' => 'index.php',
    'Book Collection' => ''
], '/Bonsai/Images/Index/tree-dark-background.jpg', 'Our Bonsai Book Collection');
?>

<!-- Collection Categories -->
<section class="py-12">
    <div class="container mx-auto max-w-6xl">
        <!-- Category Filters -->
        <div class="flex flex-wrap justify-center gap-4 mb-12">
            <button class="px-4 py-2 bg-primary text-white rounded-md hover:bg-primary-dark transition">All Books</button>
            <button class="px-4 py-2 bg-white border border-gray-300 text-gray-700 rounded-md hover:bg-gray-50 transition">Beginner Guides</button>
            <button class="px-4 py-2 bg-white border border-gray-300 text-gray-700 rounded-md hover:bg-gray-50 transition">Advanced Techniques</button>
            <button class="px-4 py-2 bg-white border border-gray-300 text-gray-700 rounded-md hover:bg-gray-50 transition">Tropical Species</button>
            <button class="px-4 py-2 bg-white border border-gray-300 text-gray-700 rounded-md hover:bg-gray-50 transition">Style & Design</button>
            <button class="px-4 py-2 bg-white border border-gray-300 text-gray-700 rounded-md hover:bg-gray-50 transition">Care & Maintenance</button>
        </div>
        
        <!-- Book Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            <!-- Book 1 -->
            <div class="bg-white rounded-lg shadow-md overflow-hidden transition-transform hover:scale-105">
                <div class="h-64 bg-gray-100 overflow-hidden">
                    <img src="/Bonsai/Images/Index/IMG_6171.JPG" alt="Bonsai Basics" class="w-full h-full object-cover">
                </div>
                <div class="p-6">
                    <span class="inline-block px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-xs font-medium mb-2">Beginner</span>
                    <h3 class="text-xl font-bold mb-2">Bonsai Basics</h3>
                    <p class="text-gray-700 mb-3">By Colin Lewis</p>
                    <p class="text-gray-600 mb-4 line-clamp-3">
                        The perfect introduction for beginners, this comprehensive guide covers essential techniques with over 200 full-color photos.
                    </p>
                    <div class="flex justify-between items-center">
                        <span class="font-bold text-primary">RM 24.50</span>
                        <a href="catalogue.php" class="text-primary hover:text-primary-dark font-medium">View Details →</a>
                    </div>
                </div>
            </div>
            
            <!-- Book 2 -->
            <div class="bg-white rounded-lg shadow-md overflow-hidden transition-transform hover:scale-105">
                <div class="h-64 bg-gray-100 overflow-hidden">
                    <img src="/Bonsai/Images/Index/IMG_6168.JPG" alt="The Complete Book of Bonsai" class="w-full h-full object-cover">
                </div>
                <div class="p-6">
                    <span class="inline-block px-3 py-1 bg-green-100 text-green-800 rounded-full text-xs font-medium mb-2">Guide</span>
                    <h3 class="text-xl font-bold mb-2">The Complete Book of Bonsai</h3>
                    <p class="text-gray-700 mb-3">By Harry Tomlinson</p>
                    <p class="text-gray-600 mb-4 line-clamp-3">
                        A comprehensive guide to the art and practice of Bonsai with detailed instructions for cultivating successful bonsai specimens.
                    </p>
                    <div class="flex justify-between items-center">
                        <span class="font-bold text-primary">RM 35.99</span>
                        <a href="catalogue.php" class="text-primary hover:text-primary-dark font-medium">View Details →</a>
                    </div>
                </div>
            </div>
            
            <!-- Book 3 -->
            <div class="bg-white rounded-lg shadow-md overflow-hidden transition-transform hover:scale-105">
                <div class="h-64 bg-gray-100 overflow-hidden">
                    <img src="/Bonsai/Images/Index/IMG_6179.JPG" alt="The Bonsai Bible" class="w-full h-full object-cover">
                </div>
                <div class="p-6">
                    <span class="inline-block px-3 py-1 bg-purple-100 text-purple-800 rounded-full text-xs font-medium mb-2">Reference</span>
                    <h3 class="text-xl font-bold mb-2">The Bonsai Bible</h3>
                    <p class="text-gray-700 mb-3">By Peter Chan</p>
                    <p class="text-gray-600 mb-4 line-clamp-3">
                        The definitive guide to choosing and growing bonsai with step-by-step projects, growing tips, and stunning photography.
                    </p>
                    <div class="flex justify-between items-center">
                        <span class="font-bold text-primary">RM 29.99</span>
                        <a href="catalogue.php" class="text-primary hover:text-primary-dark font-medium">View Details →</a>
                    </div>
                </div>
            </div>
            
            <!-- Book 4 -->
            <div class="bg-white rounded-lg shadow-md overflow-hidden transition-transform hover:scale-105">
                <div class="h-64 bg-gray-100 overflow-hidden">
                    <img src="/Bonsai/Images/Index/IMG_6174.JPG" alt="Indoor Bonsai" class="w-full h-full object-cover">
                </div>
                <div class="p-6">
                    <span class="inline-block px-3 py-1 bg-yellow-100 text-yellow-800 rounded-full text-xs font-medium mb-2">Indoor</span>
                    <h3 class="text-xl font-bold mb-2">Indoor Bonsai</h3>
                    <p class="text-gray-700 mb-3">By Paul Lesniewicz</p>
                    <p class="text-gray-600 mb-4 line-clamp-3">
                        A complete guide to caring for bonsai trees indoors, perfect for urban dwellers or those in extreme climates.
                    </p>
                    <div class="flex justify-between items-center">
                        <span class="font-bold text-primary">RM 19.95</span>
                        <a href="catalogue.php" class="text-primary hover:text-primary-dark font-medium">View Details →</a>
                    </div>
                </div>
            </div>
            
            <!-- Book 5 -->
            <div class="bg-white rounded-lg shadow-md overflow-hidden transition-transform hover:scale-105">
                <div class="h-64 bg-gray-100 overflow-hidden">
                    <img src="/Bonsai/Images/Index/IMG_6177.JPG" alt="Japanese Maples" class="w-full h-full object-cover">
                </div>
                <div class="p-6">
                    <span class="inline-block px-3 py-1 bg-red-100 text-red-800 rounded-full text-xs font-medium mb-2">Species</span>
                    <h3 class="text-xl font-bold mb-2">Japanese Maples</h3>
                    <p class="text-gray-700 mb-3">By J. D. Vertrees</p>
                    <p class="text-gray-600 mb-4 line-clamp-3">
                        The definitive guide to Japanese maples for bonsai and landscape use, covering over 150 cultivars.
                    </p>
                    <div class="flex justify-between items-center">
                        <span class="font-bold text-primary">RM 42.99</span>
                        <a href="catalogue.php" class="text-primary hover:text-primary-dark font-medium">View Details →</a>
                    </div>
                </div>
            </div>
            
            <!-- Book 6 -->
            <div class="bg-white rounded-lg shadow-md overflow-hidden transition-transform hover:scale-105">
                <div class="h-64 bg-gray-100 overflow-hidden">
                    <img src="/Bonsai/Images/Index/IMG_6169.JPG" alt="Tropical Bonsai Guide" class="w-full h-full object-cover">
                </div>
                <div class="p-6">
                    <span class="inline-block px-3 py-1 bg-green-100 text-green-800 rounded-full text-xs font-medium mb-2">Tropical</span>
                    <h3 class="text-xl font-bold mb-2">Tropical Bonsai Guide</h3>
                    <p class="text-gray-700 mb-3">By Azmi Ibrahim</p>
                    <p class="text-gray-600 mb-4 line-clamp-3">
                        Specialized guide for tropical species native to Malaysia and Southeast Asia, with climate-specific care instructions.
                    </p>
                    <div class="flex justify-between items-center">
                        <span class="font-bold text-primary">RM 32.50</span>
                        <a href="catalogue.php" class="text-primary hover:text-primary-dark font-medium">View Details →</a>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Pagination -->
        <div class="flex justify-center mt-12">
            <nav class="inline-flex rounded-md shadow">
                <a href="#" class="px-4 py-2 border border-gray-300 bg-white text-gray-700 rounded-l-md hover:bg-gray-50">Previous</a>
                <a href="#" class="px-4 py-2 border-t border-b border-gray-300 bg-primary text-white">1</a>
                <a href="#" class="px-4 py-2 border-t border-b border-gray-300 bg-white text-gray-700 hover:bg-gray-50">2</a>
                <a href="#" class="px-4 py-2 border-t border-b border-gray-300 bg-white text-gray-700 hover:bg-gray-50">3</a>
                <a href="#" class="px-4 py-2 border border-gray-300 bg-white text-gray-700 rounded-r-md hover:bg-gray-50">Next</a>
            </nav>
        </div>
        
        <!-- Call to Action -->
        <div class="bg-gray-50 rounded-lg p-8 mt-16 text-center">
            <h2 class="text-2xl font-bold mb-4">Want to see our full collection?</h2>
            <p class="text-gray-700 mb-6 max-w-3xl mx-auto">
                Visit our catalogue to see all available books with detailed descriptions, reviews, and purchasing options.
            </p>
            <a href="catalogue.php" class="bg-primary text-white px-6 py-3 rounded-md hover:bg-primary-dark transition inline-flex items-center">
                Go to Full Catalogue
                <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                </svg>
            </a>
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