<?php
$page_title = 'Contact Us';
require_once 'includes/header.php';
require_once 'components/breadcrumbs.php';

echo breadcrumbs([
    'Home' => 'index.php',
    'Contact Us' => ''
], '', 'Contact Us');
?>

<!-- Contact Content -->
<section class="py-16">
    <div class="container mx-auto">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">
            <!-- Contact Information -->
            <div>
                <h2 class="text-3xl font-bold mb-6">Get in Touch</h2>
                <p class="text-gray-600 mb-8">
                    We'd love to hear from you! Whether you have questions about our bonsai services, 
                    need advice on caring for your bonsai, or want to collaborate with us, 
                    please feel free to reach out using the contact information below.
                </p>
                
                <div class="space-y-6">
                    <div class="flex items-start">
                        <div class="text-primary mr-4 mt-1">
                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"></path>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold mb-2">Address</h3>
                            <address class="not-italic text-gray-600">
                                Slate @The Row, 48, Jalan Doraisamy,<br>
                                Chow Kit, 50300 Kuala Lumpur,<br>
                                Wilayah Persekutuan Kuala Lumpur
                            </address>
                        </div>
                    </div>
                    
                    <div class="flex items-start">
                        <div class="text-primary mr-4 mt-1">
                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                <path d="M2 3a1 1 0 011-1h2.153a1 1 0 01.986.836l.74 4.435a1 1 0 01-.54 1.06l-1.548.773a11.037 11.037 0 006.105 6.105l.774-1.548a1 1 0 011.059-.54l4.435.74a1 1 0 01.836.986V17a1 1 0 01-1 1h-2C7.82 18 2 12.18 2 5V3z"></path>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold mb-2">Phone</h3>
                            <p class="text-gray-600">
                                <a href="tel:+60195855800" class="hover:text-primary">+60 19 585 5800</a>
                            </p>
                        </div>
                    </div>
                    
                    <div class="flex items-start">
                        <div class="text-primary mr-4 mt-1">
                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z"></path>
                                <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z"></path>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold mb-2">Email</h3>
                            <p class="text-gray-600">
                                <a href="mailto:pulpygarden@gmail.com" class="hover:text-primary">pulpygarden@gmail.com</a>
                            </p>
                        </div>
                    </div>
                    
                    <div class="flex items-start">
                        <div class="text-primary mr-4 mt-1">
                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"></path>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold mb-2">Business Hours</h3>
                            <p class="text-gray-600">
                                Monday - Friday: 9:00 AM - 6:00 PM<br>
                                Saturday: 10:00 AM - 4:00 PM<br>
                                Sunday: Closed
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Contact Form -->
            <div class="bg-gray-50 p-8 rounded-lg">
                <h2 class="text-3xl font-bold mb-6">Send Us a Message</h2>
                
                <form action="#" method="POST" class="space-y-6">
                    <div>
                        <label for="name" class="block text-gray-700 font-medium mb-2">Your Name</label>
                        <input type="text" id="name" name="name" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary">
                    </div>
                    
                    <div>
                        <label for="email" class="block text-gray-700 font-medium mb-2">Your Email</label>
                        <input type="email" id="email" name="email" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary">
                    </div>
                    
                    <div>
                        <label for="subject" class="block text-gray-700 font-medium mb-2">Subject</label>
                        <input type="text" id="subject" name="subject" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary">
                    </div>
                    
                    <div>
                        <label for="message" class="block text-gray-700 font-medium mb-2">Your Message</label>
                        <textarea id="message" name="message" rows="5" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary"></textarea>
                    </div>
                    
                    <div>
                        <button type="submit" class="btn btn-primary w-full">Send Message</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>

<!-- Map Section -->
<section class="py-16 bg-gray-50">
    <div class="container mx-auto">
        <h2 class="text-3xl font-bold mb-8 text-center">Our Location</h2>
        <div class="w-full h-96 rounded-lg overflow-hidden">
            <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3983.7502497812303!2d101.69681931470738!3d3.1647210536927134!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x31cc4839e2aec055%3A0xa65a18e15c0ff6ec!2sSlate%20%40%20The%20Row!5e0!3m2!1sen!2smy!4v1648454720652!5m2!1sen!2smy" width="100%" height="100%" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
        </div>
    </div>
</section>

<?php require_once 'includes/footer.php'; ?> 