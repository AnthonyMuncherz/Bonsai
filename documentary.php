<?php
$page_title = 'Bonsai Documentary';
$custom_meta = '<meta name="description" content="Immerse yourself in the ancient and fascinating art of bonsai cultivation. Watch our documentary about the history, techniques, and cultural significance of bonsai.">';
$custom_meta .= '<meta property="og:type" content="video">';
$custom_meta .= '<meta property="og:title" content="Bonsai Documentary | Sejuta Ranting">';
$custom_meta .= '<meta property="og:description" content="Learn about the ancient art of bonsai cultivation through our exclusive documentary.">';
$custom_meta .= '<meta property="og:image" content="/Bonsai/Images/Index/IMG_6169.JPG">';
$custom_meta .= '<meta property="og:video" content="/Bonsai/video/documentary2.mp4">';
$custom_meta .= '<meta property="og:video:type" content="video/mp4">';
$custom_meta .= '<meta name="twitter:card" content="player">';

// Custom CSS for cinematic mode
$custom_style = '<style>
    /* Base video container styling */
    #video-container {
        transition: all 0.7s cubic-bezier(0.165, 0.84, 0.44, 1) !important;
        transform-origin: center center !important;
        will-change: transform, box-shadow !important;
        max-width: 120rem !important; /* Much larger size from beginning */
        margin: 0 auto !important;
        box-shadow: 0 25px 50px -15px rgba(0, 0, 0, 0.3) !important;
        border-radius: 8px !important;
    }
    
    .dimmed-section {
        opacity: 0.5 !important; /* Less dimmed since we don\'t have black background */
        transition: opacity 0.7s cubic-bezier(0.165, 0.84, 0.44, 1) !important;
    }
    
    /* Animation for the video container when transitioning */
    @keyframes pulse {
        0% { transform: scale(1); }
        50% { transform: scale(1.02); }
        100% { transform: scale(1); }
    }
    
    .video-pulse {
        animation: pulse 2s infinite;
    }
    
    /* Enhanced video control styles */
    #video-controls {
        transition: opacity 0.3s ease-in-out;
        opacity: 0;
    }
    
    #video-container:hover #video-controls {
        opacity: 1;
    }
    
    .download-btn {
        background-color: rgba(229, 131, 86, 0.8) !important;
        backdrop-filter: blur(4px);
        border-radius: 4px;
        padding: 8px 12px;
        display: flex;
        align-items: center;
        color: white;
        font-weight: 500;
        transition: all 0.3s ease;
        box-shadow: 0 2px 5px rgba(0,0,0,0.2);
    }
    
    .download-btn:hover {
        background-color: rgba(229, 131, 86, 1) !important;
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0,0,0,0.3);
    }
    
    /* Transition effect when entering/exiting cinema mode */
    @keyframes fadeIn {
        from { opacity: 0; transform: scale(0.95); }
        to { opacity: 1; transform: scale(1); }
    }
    
    @keyframes fadeOut {
        from { opacity: 1; transform: scale(1); }
        to { opacity: 0; transform: scale(0.95); }
    }
    
    .fade-in {
        animation: fadeIn 0.5s ease forwards;
    }
    
    .fade-out {
        animation: fadeOut 0.5s ease forwards;
    }
    
    @media (max-width: 768px) {
        #video-container {
            max-width: 100% !important;
            border-radius: 6px !important;
        }
    }
    
    @media (min-width: 769px) and (max-width: 1280px) {
        #video-container {
            max-width: 90vw !important; /* Percentage of viewport width for medium screens */
        }
    }
</style>';

require_once 'includes/db.php';
require_once 'includes/header.php';
?>

<!-- Page Overlay -->
<!-- Removed the page overlay as we no longer need it with the background change -->

<!-- Hero Section -->
<section class="bg-hero py-16 md:py-24">
    <div class="container mx-auto">
        <div class="text-center mb-12" data-aos="fade-up" data-aos-duration="1000">
            <span class="text-primary font-semibold uppercase tracking-wider">Educational</span>
            <h1 class="text-4xl md:text-5xl font-bold mt-2 mb-6 text-dark-olive">Bonsai Documentary</h1>
            <p class="max-w-3xl mx-auto text-lg text-olive-dark">
                Immerse yourself in the ancient and fascinating art of bonsai cultivation. This documentary explores the history, cultural significance, and techniques behind this beautiful practice.
            </p>
        </div>
    </div>
</section>

<!-- Video Section -->
<section class="py-16 md:py-24 bg-white" id="video-section">
    <div class="container mx-auto px-4">
        <div class="max-w-full mx-auto" data-aos="fade-up" data-aos-duration="1000">
            <div class="relative rounded-xl overflow-hidden shadow-xl transition-all" style="padding-top: 56.25%;" id="video-container">
                <video controls preload="metadata" class="absolute inset-0 w-full h-full object-cover" id="bonsai-documentary">
                    <source src="/Bonsai/video/documentary2.mp4" type="video/mp4">
                    Your browser does not support the video tag.
                </video>
                <div class="absolute top-3 right-3" id="video-controls">
                    <a href="/Bonsai/video/documentary2.mp4" download class="download-btn">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                        </svg>
                        Download
                    </a>
                </div>
                <div id="video-notification" class="absolute bottom-0 left-0 right-0 bg-black bg-opacity-70 text-white text-sm text-center py-2 transform translate-y-full transition-transform duration-300">
                    Press F for fullscreen mode, Space to pause/play
                </div>
            </div>
        </div>
        <div class="mt-4 text-center text-sm text-olive-dark flex justify-center items-center" id="video-tips">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            Press 'F' for fullscreen, 'Space' to pause/play
        </div>
    </div>
</section>

<!-- Information Section -->
<section class="py-16 md:py-24 bg-secondary">
    <div class="container mx-auto">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 items-center">
            <div data-aos="fade-right" data-aos-duration="1000">
                <span class="text-primary font-semibold uppercase tracking-wider">DISCOVER</span>
                <h2 class="text-3xl md:text-4xl font-bold mt-2 mb-6 text-dark-olive">What is Bonsai?</h2>
                <p class="mb-4 text-olive-dark">
                    Bonsai (盆栽) is the Japanese art of growing miniature trees in containers. The practice originated in China over a thousand years ago and was later refined in Japan. The term "Bonsai" literally means "planted in a container."
                </p>
                <p class="mb-4 text-olive-dark">
                    Unlike other container gardening, bonsai focuses on long-term cultivation and shaping of one or more small trees in a pot, imitating the shape and style of mature, full-size trees in nature.
                </p>
                <p class="mb-4 text-olive-dark">
                    This documentary explores the history, techniques, and cultural significance of bonsai cultivation, offering insights for both beginners and enthusiasts alike.
                </p>
            </div>
            <div data-aos="fade-left" data-aos-duration="1000" data-aos-delay="200">
                <div class="rounded-lg overflow-hidden shadow-xl">
                    <img src="/Bonsai/Images/Index/IMG_6209.JPG" alt="Bonsai Tree" class="w-full h-auto">
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Key Points Section -->
<section class="py-16 md:py-24">
    <div class="container mx-auto">
        <div class="text-center mb-12" data-aos="fade-up" data-aos-duration="1000">
            <span class="text-primary font-semibold uppercase tracking-wider">HIGHLIGHTS</span>
            <h2 class="text-3xl md:text-4xl font-bold mt-2 mb-4 text-dark-olive">Documentary Highlights</h2>
            <p class="max-w-2xl mx-auto text-olive-dark">
                This documentary covers several key aspects of bonsai cultivation that every enthusiast should know.
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <div class="bg-white p-6 rounded-lg shadow-md" data-aos="fade-up" data-aos-duration="1000" data-aos-delay="0">
                <div class="w-12 h-12 bg-primary rounded-full flex items-center justify-center mb-4">
                    <span class="text-white font-bold">01</span>
                </div>
                <h3 class="text-xl font-bold mb-3 text-dark-olive">Historical Origins</h3>
                <p class="text-olive-dark">
                    Explore the fascinating origins of bonsai in ancient China and its evolution in Japan over centuries, understanding how this art form has developed across cultures.
                </p>
            </div>

            <div class="bg-white p-6 rounded-lg shadow-md" data-aos="fade-up" data-aos-duration="1000" data-aos-delay="100">
                <div class="w-12 h-12 bg-primary rounded-full flex items-center justify-center mb-4">
                    <span class="text-white font-bold">02</span>
                </div>
                <h3 class="text-xl font-bold mb-3 text-dark-olive">Essential Techniques</h3>
                <p class="text-olive-dark">
                    Learn about the core techniques of bonsai, including pruning, wiring, repotting, and watering. These methods are essential for creating and maintaining beautiful bonsai trees.
                </p>
            </div>

            <div class="bg-white p-6 rounded-lg shadow-md" data-aos="fade-up" data-aos-duration="1000" data-aos-delay="200">
                <div class="w-12 h-12 bg-primary rounded-full flex items-center justify-center mb-4">
                    <span class="text-white font-bold">03</span>
                </div>
                <h3 class="text-xl font-bold mb-3 text-dark-olive">Styling & Aesthetics</h3>
                <p class="text-olive-dark">
                    Discover the various styles of bonsai, including formal upright, informal upright, slanting, cascade, and forest arrangements, and learn how each style reflects certain natural forms.
                </p>
            </div>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="bg-dark-olive py-16 relative">
    <div class="absolute inset-0 opacity-60 bg-cover bg-center" style="background-image: url('/Bonsai/Images/Index/tree-dark-background.jpg');"></div>
    <div class="container mx-auto relative z-10">
        <div class="text-center" data-aos="fade-up" data-aos-duration="1000">
            <h2 class="text-3xl md:text-4xl font-bold mb-6 text-white">Start Your Bonsai Journey Today</h2>
            <p class="text-lg mb-8 text-white max-w-2xl mx-auto">
                Interested in starting your own bonsai journey? Explore our curated collection of bonsai books and resources to guide you every step of the way.
            </p>
            <div class="flex flex-col sm:flex-row justify-center space-y-4 sm:space-y-0 sm:space-x-4">
                <a href="catalogue.php" class="btn btn-primary">Browse Catalogue</a>
                <a href="services.php" class="btn bg-white text-primary hover:bg-gray-100">Our Services</a>
            </div>
        </div>
    </div>
</section>

<?php require_once 'includes/footer.php'; ?>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const video = document.getElementById('bonsai-documentary');
    const videoSection = document.getElementById('video-section');
    const videoContainer = document.getElementById('video-container');
    const videoControls = document.getElementById('video-controls');
    const otherSections = document.querySelectorAll('section:not(#video-section)');
    const videoNotification = document.getElementById('video-notification');
    const videoTips = document.getElementById('video-tips');
    
    // Flag to track animation state
    let isAnimating = false;
    
    // Store original dimensions for reference
    const originalWidth = videoContainer.offsetWidth;
    const originalHeight = videoContainer.offsetHeight;
    
    // Initially hide controls
    videoControls.style.opacity = '0';
    
    // Add hover effect on video container for better UX
    videoContainer.addEventListener('mouseenter', function() {
        if (video.paused && !isAnimating) {
            videoContainer.classList.add('video-pulse');
        }
    });
    
    videoContainer.addEventListener('mouseleave', function() {
        if (video.paused) {
            videoContainer.classList.remove('video-pulse');
        }
    });
    
    // Add event listeners to the video
    video.addEventListener('play', function() {
        if (isAnimating) return;
        isAnimating = true;
        
        // Add fade-in animation
        videoContainer.classList.add('fade-in');
        
        // Remove pulse effect if it's there
        videoContainer.classList.remove('video-pulse');
        
        // Dim other sections for cinematic feel
        otherSections.forEach(section => {
            section.classList.add('dimmed-section');
        });
        
        // Hide the video tips
        videoTips.classList.add('opacity-0', 'transition-opacity', 'duration-300');
        
        // Reset animation flag after transition completes
        setTimeout(() => {
            videoContainer.classList.remove('fade-in');
            isAnimating = false;
        }, 700);
        
        // Smooth scroll to video if not in view
        const rect = videoContainer.getBoundingClientRect();
        if (rect.top < 0 || rect.bottom > window.innerHeight) {
            videoContainer.scrollIntoView({ behavior: 'smooth', block: 'center' });
        }
        
        // Show notification briefly
        showNotification();
    });
    
    // Add event listeners for pause and end
    video.addEventListener('pause', resetBackground);
    video.addEventListener('ended', resetBackground);
    
    // Function to reset the background
    function resetBackground() {
        // Only reset if the video is not playing and not already animating
        if (video.paused && !isAnimating) {
            isAnimating = true;
            
            // Add fade-out animation
            videoContainer.classList.add('fade-out');
            
            setTimeout(() => {
                // Restore other sections
                otherSections.forEach(section => {
                    section.classList.remove('dimmed-section');
                });
                
                // Show the video tips again
                videoTips.classList.remove('opacity-0');
                
                // Reset animation flag after transition completes
                setTimeout(() => {
                    videoContainer.classList.remove('fade-out');
                    isAnimating = false;
                }, 700);
            }, 50);
        }
    }
    
    // Function to show notification briefly
    function showNotification() {
        videoNotification.classList.add('transform', 'translate-y-0');
        setTimeout(() => {
            videoNotification.classList.remove('translate-y-0');
            videoNotification.classList.add('translate-y-full');
        }, 3000);
    }
    
    // Keyboard shortcuts
    document.addEventListener('keydown', function(e) {
        // Check if the video is the active element or if we're focused on the document
        const activeElement = document.activeElement;
        const isInputElement = activeElement.tagName === 'INPUT' || 
                               activeElement.tagName === 'TEXTAREA' || 
                               activeElement.isContentEditable;
        
        // Only process shortcuts if we're not in an input field
        if (!isInputElement) {
            // F key for fullscreen
            if (e.key === 'f' || e.key === 'F') {
                e.preventDefault();
                
                if (!document.fullscreenElement) {
                    if (video.requestFullscreen) {
                        video.requestFullscreen();
                    } else if (video.webkitRequestFullscreen) { /* Safari */
                        video.webkitRequestFullscreen();
                    } else if (video.msRequestFullscreen) { /* IE11 */
                        video.msRequestFullscreen();
                    }
                } else {
                    if (document.exitFullscreen) {
                        document.exitFullscreen();
                    } else if (document.webkitExitFullscreen) { /* Safari */
                        document.webkitExitFullscreen();
                    } else if (document.msExitFullscreen) { /* IE11 */
                        document.msExitFullscreen();
                    }
                }
            }
            
            // Space bar for play/pause
            if (e.key === ' ' || e.key === 'Spacebar') {
                e.preventDefault();
                if (video.paused) {
                    video.play();
                } else {
                    video.pause();
                }
            }
        }
    });
    
    // Ensure download button works correctly
    document.querySelector('#video-controls a').addEventListener('click', function(e) {
        // Pause the video when download is clicked
        video.pause();
    });
    
    // Show video controls on hover
    videoContainer.addEventListener('mouseenter', function() {
        if (!video.paused) {
            videoControls.classList.remove('hidden');
        }
    });
    
    // Double click for fullscreen
    videoContainer.addEventListener('dblclick', function() {
        if (!document.fullscreenElement) {
            if (video.requestFullscreen) {
                video.requestFullscreen();
            } else if (video.webkitRequestFullscreen) { /* Safari */
                video.webkitRequestFullscreen();
            } else if (video.msRequestFullscreen) { /* IE11 */
                video.msRequestFullscreen();
            }
        } else {
            if (document.exitFullscreen) {
                document.exitFullscreen();
            } else if (document.webkitExitFullscreen) { /* Safari */
                document.webkitExitFullscreen();
            } else if (document.msExitFullscreen) { /* IE11 */
                document.msExitFullscreen();
            }
        }
    });
});
</script> 