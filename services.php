<?php
$page_title = 'Services';
require_once 'includes/db.php';
require_once 'includes/header.php';
require_once 'components/breadcrumbs.php';
require_once 'components/service-card.php';

echo breadcrumbs([
    'Home' => 'index.php',
    'Services' => ''
], '/Bonsai/Images/Index/tree-dark-background.jpg', 'Our Services');
?>

<!-- Services Header -->
<section class="pt-16 pb-8 bg-gray-50">
    <div class="container mx-auto">
        <h1 class="text-4xl font-bold text-center">Our Bonsai Services</h1>
        <p class="text-center text-gray-600 mt-2">Beyond books, we offer expertise and resources for your bonsai journey</p>
    </div>
</section>

<!-- Services Content -->
<section class="py-12">
    <div class="container mx-auto max-w-6xl">
        <!-- Main Services -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <!-- Service 1 -->
            <div class="bg-white rounded-lg shadow-md overflow-hidden">
                <div class="h-48 bg-primary bg-opacity-10 flex items-center justify-center">
                    <svg class="w-20 h-20 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                    </svg>
                </div>
                <div class="p-6">
                    <h2 class="text-xl font-bold mb-3">Book Consultations</h2>
                    <p class="text-gray-700 mb-4">
                        Not sure which bonsai book is right for your skill level or interests? Our expert staff provide personalized consultations to recommend the perfect resources for your specific bonsai journey.
                    </p>
                    <a href="#" class="text-primary font-medium hover:text-primary-dark transition">Learn more →</a>
                </div>
            </div>
            
            <!-- Service 2 -->
            <div class="bg-white rounded-lg shadow-md overflow-hidden">
                <div class="h-48 bg-primary bg-opacity-10 flex items-center justify-center">
                    <svg class="w-20 h-20 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                    </svg>
                </div>
                <div class="p-6">
                    <h2 class="text-xl font-bold mb-3">Special Orders</h2>
                    <p class="text-gray-700 mb-4">
                        Looking for a rare or out-of-print bonsai book? We can help source and import specialized titles from around the world. Our network of publishers and suppliers ensures you can find even the most niche resources.
                    </p>
                    <a href="#" class="text-primary font-medium hover:text-primary-dark transition">Learn more →</a>
                </div>
            </div>
            
            <!-- Service 3 -->
            <div class="bg-white rounded-lg shadow-md overflow-hidden">
                <div class="h-48 bg-primary bg-opacity-10 flex items-center justify-center">
                    <svg class="w-20 h-20 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                    </svg>
                </div>
                <div class="p-6">
                    <h2 class="text-xl font-bold mb-3">Book Subscriptions</h2>
                    <p class="text-gray-700 mb-4">
                        Stay updated with the latest in bonsai literature with our curated subscription service. Receive new releases or curated selections quarterly, tailored to your interests and delivered to your doorstep.
                    </p>
                    <a href="#" class="text-primary font-medium hover:text-primary-dark transition">Learn more →</a>
                </div>
            </div>
        </div>
        
        <!-- Additional Services -->
        <div class="mt-16">
            <h2 class="text-2xl font-bold mb-8 text-center">Additional Services</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <!-- Additional Service 1 -->
                <div class="flex bg-white rounded-lg shadow-md p-6">
                    <div class="flex-shrink-0 mr-4">
                        <div class="bg-primary bg-opacity-10 p-3 rounded-full">
                            <svg class="w-8 h-8 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold mb-2">Bonsai Workshops</h3>
                        <p class="text-gray-700">
                            Join our regular workshops led by local bonsai masters. Learn techniques directly from our books in hands-on sessions, perfect for beginners and intermediate enthusiasts.
                        </p>
                    </div>
                </div>
                
                <!-- Additional Service 2 -->
                <div class="flex bg-white rounded-lg shadow-md p-6">
                    <div class="flex-shrink-0 mr-4">
                        <div class="bg-primary bg-opacity-10 p-3 rounded-full">
                            <svg class="w-8 h-8 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"></path>
                            </svg>
                        </div>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold mb-2">Author Meet & Greets</h3>
                        <p class="text-gray-700">
                            We regularly host bonsai authors for book signings and Q&A sessions. Meet the experts behind your favorite bonsai guides and get personalized advice.
                        </p>
                    </div>
                </div>
                
                <!-- Additional Service 3 -->
                <div class="flex bg-white rounded-lg shadow-md p-6">
                    <div class="flex-shrink-0 mr-4">
                        <div class="bg-primary bg-opacity-10 p-3 rounded-full">
                            <svg class="w-8 h-8 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"></path>
                            </svg>
                        </div>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold mb-2">Online Forums</h3>
                        <p class="text-gray-700">
                            Join our online community where customers can discuss bonsai techniques, share their experiences with books from our collection, and connect with fellow enthusiasts.
                        </p>
                    </div>
                </div>
                
                <!-- Additional Service 4 -->
                <div class="flex bg-white rounded-lg shadow-md p-6">
                    <div class="flex-shrink-0 mr-4">
                        <div class="bg-primary bg-opacity-10 p-3 rounded-full">
                            <svg class="w-8 h-8 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold mb-2">Digital Resources</h3>
                        <p class="text-gray-700">
                            Complement your physical books with our exclusive digital content including video tutorials, growth charts, and maintenance calendars specifically for Malaysian bonsai species.
                        </p>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Testimonial -->
        <div class="mt-16 bg-primary bg-opacity-5 rounded-lg p-8 text-center">
            <svg class="w-10 h-10 text-primary mx-auto mb-4 opacity-60" fill="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path d="M14.017 21v-7.391c0-5.704 3.731-9.57 8.983-10.609l.995 2.151c-2.432.917-3.995 3.638-3.995 5.849h4v10h-9.983zm-14.017 0v-7.391c0-5.704 3.748-9.57 9-10.609l.996 2.151c-2.433.917-3.996 3.638-3.996 5.849h3.983v10h-9.983z" />
            </svg>
            <p class="text-lg italic text-gray-700 mb-4">
                "The book consultation service was incredible! I was overwhelmed by all the bonsai books out there, but the team at Sejuta Ranting spent time understanding my experience level and interests. They recommended the perfect beginner's guide that matched my specific needs. Truly personalized service!"
            </p>
            <p class="font-semibold">Siti Aminah, Kuala Lumpur</p>
        </div>
        
        <!-- Call to Action -->
        <div class="mt-16 text-center">
            <h2 class="text-2xl font-bold mb-4">Ready to enhance your bonsai journey?</h2>
            <p class="text-gray-700 mb-6 max-w-2xl mx-auto">
                Contact us today to learn more about our services or visit our bookstore to explore our extensive collection of bonsai literature.
            </p>
            <div class="flex justify-center space-x-4">
                <a href="contacts.php" class="bg-primary text-white px-6 py-3 rounded-md hover:bg-primary-dark transition">
                    Contact Us
                </a>
                <a href="catalogue.php" class="bg-white border border-primary text-primary px-6 py-3 rounded-md hover:bg-gray-50 transition">
                    Browse Books
                </a>
            </div>
        </div>
    </div>
</section>

<?php require_once 'includes/footer.php'; ?> 