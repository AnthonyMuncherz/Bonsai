<?php
// Get the current page from the URL
$current_page = basename($_SERVER['PHP_SELF']);
?>
<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="format-detection" content="telephone=no">
    <title><?php echo isset($page_title) ? $page_title . ' | ' : ''; ?>SEJUTA RANTING</title>
    <link rel="icon" href="/Bonsai/Images/Index/Sejuta Ranting.png" type="image/x-icon">
    <!-- Google Fonts -->
    <link rel="stylesheet" type="text/css" href="//fonts.googleapis.com/css2?family=Mulish:ital,wght@0,200;0,300;0,400;0,500;0,600;0,700;1,300;1,400&amp;family=Volkhov:wght@400;700&amp;display=swap">
    <!-- AOS CSS -->
    <link rel="stylesheet" href="https://unpkg.com/aos@2.3.1/dist/aos.css">
    <!-- Styles -->
    <script src="/Bonsai/assets/dist/styles.bundle.js"></script>
</head>
<body class="bg-white text-gray-800 flex flex-col min-h-screen">
    <!-- Page loader -->
    <div id="page-loader" class="fixed inset-0 bg-white z-50 flex items-center justify-center">
        <div class="animate-spin rounded-full h-16 w-16 border-t-4 border-b-4 border-primary"></div>
    </div>

    <!-- Page -->
    <div class="flex flex-col min-h-screen">
        <!-- Header -->
        <header class="bg-white py-4 transition-all duration-300">
            <div class="container mx-auto">
                <div class="flex items-center justify-between">
                    <!-- Logo -->
                    <a href="index.php" class="flex items-center" data-aos="fade-right" data-aos-duration="1000">
                        <img src="/Bonsai/Images/About us/Sejuta Ranting.PNG" alt="Sejuta Ranting" class="h-10 md:h-12">
                    </a>

                    <!-- Mobile menu button -->
                    <button id="mobile-menu-toggle" class="md:hidden flex items-center p-2">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                        </svg>
                    </button>

                    <!-- Desktop Navigation -->
                    <nav class="hidden md:flex items-center space-x-8" data-aos="fade-left" data-aos-duration="1000">
                        <a href="index.php" class="<?php echo $current_page == 'index.php' ? 'text-primary font-bold' : 'hover:text-primary'; ?>">
                            Home
                        </a>
                        <a href="about.php" class="<?php echo $current_page == 'about.php' ? 'text-primary font-bold' : 'hover:text-primary'; ?>">
                            About
                        </a>
                        <div class="relative group">
                            <a href="services.php" class="<?php echo $current_page == 'services.php' || $current_page == 'single-service.php' ? 'text-primary font-bold' : 'hover:text-primary'; ?> flex items-center">
                                Services
                                <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </a>
                            <div class="absolute left-0 mt-2 w-48 bg-white shadow-lg rounded-lg hidden group-hover:block z-10">
                                <a href="services.php" class="block px-4 py-2 hover:bg-gray-100">Services</a>
                                <a href="single-service.php" class="block px-4 py-2 hover:bg-gray-100">Single Service</a>
                            </div>
                        </div>
                        <a href="portfolio.php" class="<?php echo $current_page == 'portfolio.php' ? 'text-primary font-bold' : 'hover:text-primary'; ?>">
                            Collection
                        </a>
                        <div class="relative group">
                            <a href="#" class="flex items-center hover:text-primary">
                                Pages
                                <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </a>
                            <div class="absolute left-0 mt-2 w-48 bg-white shadow-lg rounded-lg hidden group-hover:block z-10">
                                <div class="grid grid-cols-2 gap-2 p-4">
                                    <div>
                                        <p class="font-bold mb-2 text-sm">Special Pages</p>
                                        <a href="404-page.php" class="block py-1 text-sm hover:text-primary">404 Page</a>
                                        <a href="search-results.php" class="block py-1 text-sm hover:text-primary">Search Results</a>
                                        <a href="privacy-policy.php" class="block py-1 text-sm hover:text-primary">Privacy Policy</a>
                                        <a href="accordion.php" class="block py-1 text-sm hover:text-primary">Accordion</a>
                                    </div>
                                    <div>
                                        <p class="font-bold mb-2 text-sm">Elements</p>
                                        <a href="buttons.php" class="block py-1 text-sm hover:text-primary">Buttons</a>
                                        <a href="typography.php" class="block py-1 text-sm hover:text-primary">Typography</a>
                                        <a href="tabs.php" class="block py-1 text-sm hover:text-primary">Tabs</a>
                                        <a href="contact-forms.php" class="block py-1 text-sm hover:text-primary">Contact Forms</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <a href="contacts.php" class="<?php echo $current_page == 'contacts.php' ? 'text-primary font-bold' : 'hover:text-primary'; ?>">
                            Contacts
                        </a>
                        <a href="tel:+60195855800" class="btn btn-primary flex items-center">
                            <svg class="w-4 h-4 mr-2" viewBox="0 0 16 16" fill="currentColor">
                                <path d="M9.5 0H9V1H9.5C10.2604 1 10.974 1.14583 11.6406 1.4375C12.3073 1.71875 12.8906 2.10938 13.3906 2.60938C13.8906 3.10938 14.2812 3.69271 14.5625 4.35938C14.8542 5.02604 15 5.73958 15 6.5V7H16V6.5C16 5.60417 15.8281 4.76042 15.4844 3.96875C15.151 3.17708 14.6875 2.48958 14.0938 1.90625C13.5104 1.3125 12.8229 0.848958 12.0312 0.515625C11.2396 0.171875 10.3958 0 9.5 0ZM13 7V6.5C13 6.02083 12.9062 5.56771 12.7188 5.14062C12.5417 4.71354 12.2917 4.34375 11.9688 4.03125C11.6562 3.70833 11.2865 3.45833 10.8594 3.28125C10.4323 3.09375 9.97917 3 9.5 3H9V4H9.5C10.1875 4 10.776 4.24479 11.2656 4.73438C11.7552 5.22396 12 5.8125 12 6.5V7H13ZM12.5938 16L14.8438 13.7344C14.9479 13.6406 15 13.526 15 13.3906C15 13.2448 14.9479 13.1198 14.8438 13.0156L11.8906 10.0625C11.7969 9.96875 11.6771 9.92188 11.5312 9.92188C11.3958 9.92188 11.2812 9.96875 11.1875 10.0625L8.92188 12.3281L3.67188 7.07812L5.9375 4.8125C6.03125 4.71875 6.07812 4.60417 6.07812 4.46875C6.07812 4.32292 6.03125 4.20312 5.9375 4.10938L2.98438 1.15625C2.88021 1.05208 2.75521 1 2.60938 1C2.47396 1 2.35938 1.05208 2.26562 1.15625L0 3.40625C0 5.14583 0.328125 6.78125 0.984375 8.3125C1.65104 9.84375 2.55208 11.1771 3.6875 12.3125C4.82292 13.4479 6.15625 14.3438 7.6875 15C9.21875 15.6667 10.8542 16 12.5938 16Z"></path>
                            </svg>
                            +60 19 585 5800
                        </a>
                    </nav>
                </div>

                <!-- Mobile Navigation -->
                <div id="mobile-menu" class="md:hidden mt-4 pb-4 hidden">
                    <nav class="flex flex-col space-y-3">
                        <a href="index.php" class="<?php echo $current_page == 'index.php' ? 'text-primary font-bold' : ''; ?> py-2">
                            Home
                        </a>
                        <a href="about.php" class="<?php echo $current_page == 'about.php' ? 'text-primary font-bold' : ''; ?> py-2">
                            About
                        </a>
                        <div class="py-2">
                            <div class="flex items-center justify-between cursor-pointer" onclick="this.nextElementSibling.classList.toggle('hidden')">
                                <span class="<?php echo $current_page == 'services.php' || $current_page == 'single-service.php' ? 'text-primary font-bold' : ''; ?>">Services</span>
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </div>
                            <div class="hidden ml-4 mt-2 space-y-2">
                                <a href="services.php" class="block py-1">Services</a>
                                <a href="single-service.php" class="block py-1">Single Service</a>
                            </div>
                        </div>
                        <a href="portfolio.php" class="<?php echo $current_page == 'portfolio.php' ? 'text-primary font-bold' : ''; ?> py-2">
                            Collection
                        </a>
                        <div class="py-2">
                            <div class="flex items-center justify-between cursor-pointer" onclick="this.nextElementSibling.classList.toggle('hidden')">
                                <span>Pages</span>
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </div>
                            <div class="hidden ml-4 mt-2 space-y-2">
                                <p class="font-bold mt-2">Special Pages</p>
                                <a href="404-page.php" class="block py-1">404 Page</a>
                                <a href="search-results.php" class="block py-1">Search Results</a>
                                <a href="privacy-policy.php" class="block py-1">Privacy Policy</a>
                                <a href="accordion.php" class="block py-1">Accordion</a>
                                
                                <p class="font-bold mt-3">Elements</p>
                                <a href="buttons.php" class="block py-1">Buttons</a>
                                <a href="typography.php" class="block py-1">Typography</a>
                                <a href="tabs.php" class="block py-1">Tabs</a>
                                <a href="contact-forms.php" class="block py-1">Contact Forms</a>
                            </div>
                        </div>
                        <a href="contacts.php" class="<?php echo $current_page == 'contacts.php' ? 'text-primary font-bold' : ''; ?> py-2">
                            Contacts
                        </a>
                        <a href="tel:+60195855800" class="btn btn-primary flex items-center justify-center">
                            <svg class="w-4 h-4 mr-2" viewBox="0 0 16 16" fill="currentColor">
                                <path d="M9.5 0H9V1H9.5C10.2604 1 10.974 1.14583 11.6406 1.4375C12.3073 1.71875 12.8906 2.10938 13.3906 2.60938C13.8906 3.10938 14.2812 3.69271 14.5625 4.35938C14.8542 5.02604 15 5.73958 15 6.5V7H16V6.5C16 5.60417 15.8281 4.76042 15.4844 3.96875C15.151 3.17708 14.6875 2.48958 14.0938 1.90625C13.5104 1.3125 12.8229 0.848958 12.0312 0.515625C11.2396 0.171875 10.3958 0 9.5 0ZM13 7V6.5C13 6.02083 12.9062 5.56771 12.7188 5.14062C12.5417 4.71354 12.2917 4.34375 11.9688 4.03125C11.6562 3.70833 11.2865 3.45833 10.8594 3.28125C10.4323 3.09375 9.97917 3 9.5 3H9V4H9.5C10.1875 4 10.776 4.24479 11.2656 4.73438C11.7552 5.22396 12 5.8125 12 6.5V7H13ZM12.5938 16L14.8438 13.7344C14.9479 13.6406 15 13.526 15 13.3906C15 13.2448 14.9479 13.1198 14.8438 13.0156L11.8906 10.0625C11.7969 9.96875 11.6771 9.92188 11.5312 9.92188C11.3958 9.92188 11.2812 9.96875 11.1875 10.0625L8.92188 12.3281L3.67188 7.07812L5.9375 4.8125C6.03125 4.71875 6.07812 4.60417 6.07812 4.46875C6.07812 4.32292 6.03125 4.20312 5.9375 4.10938L2.98438 1.15625C2.88021 1.05208 2.75521 1 2.60938 1C2.47396 1 2.35938 1.05208 2.26562 1.15625L0 3.40625C0 5.14583 0.328125 6.78125 0.984375 8.3125C1.65104 9.84375 2.55208 11.1771 3.6875 12.3125C4.82292 13.4479 6.15625 14.3438 7.6875 15C9.21875 15.6667 10.8542 16 12.5938 16Z"></path>
                            </svg>
                            +60 19 585 5800
                        </a>
                    </nav>
                </div>
            </div>
        </header>

        <!-- Main content -->
        <main class="flex-grow"><?php // Main content will go here ?> 