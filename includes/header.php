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
    <style>
        .btn-primary {
            background-color: #E58356;
            color: white;
            padding: 0.5rem 1rem;
            border-radius: 0.25rem;
            transition: all 0.3s;
        }
        .btn-primary:hover {
            background-color: #d36e43;
        }
    </style>
</head>
<body class="bg-white text-gray-800 flex flex-col min-h-screen">
    <!-- Page loader -->
    <div id="page-loader" class="fixed inset-0 bg-white z-50 flex items-center justify-center">
        <div class="animate-spin rounded-full h-16 w-16 border-t-4 border-b-4 border-primary"></div>
    </div>

    <!-- Page -->
    <div class="flex flex-col min-h-screen">
        <!-- Header -->
        <header class="bg-dark-olive py-4 transition-all duration-300">
            <div class="container mx-auto">
                <div class="flex items-center justify-between">
                    <!-- Logo -->
                    <a href="index.php" class="flex items-center" data-aos="fade-right" data-aos-duration="1000">
                        <img src="/Bonsai/Images/About us/Sejuta Ranting.PNG" alt="Sejuta Ranting" class="h-14 md:h-16">
                    </a>

                    <!-- Mobile menu button -->
                    <button id="mobile-menu-toggle" class="md:hidden flex items-center p-2 text-white">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                        </svg>
                    </button>

                    <!-- Desktop Navigation -->
                    <nav class="hidden md:flex items-center space-x-8 text-white" data-aos="fade-left" data-aos-duration="1000">
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
                                <a href="services.php" class="block px-4 py-2 text-olive-dark hover:bg-gray-100">Services</a>
                                <a href="single-service.php" class="block px-4 py-2 text-olive-dark hover:bg-gray-100">Single Service</a>
                            </div>
                        </div>
                        <a href="portfolio.php" class="<?php echo $current_page == 'portfolio.php' ? 'text-primary font-bold' : 'hover:text-primary'; ?>">
                            Collection
                        </a>
                        <a href="contacts.php" class="<?php echo $current_page == 'contacts.php' ? 'text-primary font-bold' : 'hover:text-primary'; ?>">
                            Contacts
                        </a>
                        <div class="flex space-x-3">
                            <a href="#" class="btn btn-primary">Login</a>
                            <a href="#" class="btn bg-white text-primary hover:bg-gray-100">Register</a>
                        </div>
                    </nav>
                </div>

                <!-- Mobile Navigation -->
                <div id="mobile-menu" class="md:hidden mt-4 pb-4 hidden">
                    <nav class="flex flex-col space-y-3 text-white">
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
                        <a href="contacts.php" class="<?php echo $current_page == 'contacts.php' ? 'text-primary font-bold' : ''; ?> py-2">
                            Contacts
                        </a>
                        <div class="flex flex-col space-y-2">
                            <a href="#" class="btn btn-primary">Login</a>
                            <a href="#" class="btn bg-white text-primary">Register</a>
                        </div>
                    </nav>
                </div>
            </div>
        </header>

        <!-- Main content -->
        <main class="flex-grow"><?php // Main content will go here ?> 