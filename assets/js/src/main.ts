// Main TypeScript file for Sejuta Ranting Bonsai website
// AOS is loaded via script tag in footer, so we don't need to import it here
// import AOS from 'aos';
// import 'aos/dist/aos.css';

// Declare AOS as a global variable since it's loaded via script tag
declare const AOS: any;

// Hide page loader when page is loaded
window.addEventListener('load', () => {
  const pageLoader = document.getElementById('page-loader');
  if (pageLoader) {
    pageLoader.style.display = 'none';
  }
  
  // Apply page transition effect to main content
  const mainContent = document.querySelector('main');
  if (mainContent) {
    mainContent.classList.add('page-transition');
  }
  
  // Initialize fade-in animations with Intersection Observer
  initFadeAnimations();
});

// Initialize fade animations for elements with fade-in class
function initFadeAnimations() {
  const fadeElements = document.querySelectorAll('.fade-in');
  
  if (fadeElements.length > 0) {
    const fadeObserver = new IntersectionObserver((entries) => {
      entries.forEach(entry => {
        if (entry.isIntersecting) {
          (entry.target as HTMLElement).style.animationPlayState = 'running';
          fadeObserver.unobserve(entry.target);
        }
      });
    }, {
      threshold: 0.1,
      rootMargin: '0px 0px -50px 0px'
    });
    
    fadeElements.forEach(element => {
      (element as HTMLElement).style.animationPlayState = 'paused';
      fadeObserver.observe(element);
    });
  }
}

// Initialize AOS with custom settings
document.addEventListener('DOMContentLoaded', () => {
  // Initialize AOS if it exists (loaded via script tag)
  if (typeof AOS !== 'undefined') {
    AOS.init({
      duration: 800,
      easing: 'ease-in-out',
      once: false,
      mirror: true,
      offset: 50
    });
  }
  
  // Handle mobile menu toggle
  const menuToggle = document.querySelector('.mobile-menu-toggle');
  const mobileMenu = document.querySelector('.mobile-menu');
  
  if (menuToggle && mobileMenu) {
    menuToggle.addEventListener('click', () => {
      mobileMenu.classList.toggle('hidden');
    });
  }
  
  // Add smooth scrolling for anchor links
  document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function(this: HTMLAnchorElement, e: Event) {
      e.preventDefault();
      
      const target = document.querySelector(this.getAttribute('href') as string);
      if (target) {
        target.scrollIntoView({
          behavior: 'smooth'
        });
      }
    });
  });

  // Initialize current year in footer
  const yearElement = document.querySelector('.copyright-year');
  if (yearElement) {
    yearElement.textContent = new Date().getFullYear().toString();
  }
});

// Reinitialize AOS when window is resized
window.addEventListener('resize', () => {
  if (typeof AOS !== 'undefined') {
    AOS.refresh();
  }
});

// Custom animations for specific elements
const setupCustomAnimations = () => {
  // Staggered animations for service items
  const serviceItems = document.querySelectorAll('.service-item');
  serviceItems.forEach((item, index) => {
    item.setAttribute('data-aos-delay', (index * 100).toString());
  });
  
  // Staggered animations for portfolio items
  const portfolioItems = document.querySelectorAll('.portfolio-item');
  portfolioItems.forEach((item, index) => {
    item.setAttribute('data-aos-delay', (index * 150).toString());
  });
};

// Call setup function when DOM is loaded
document.addEventListener('DOMContentLoaded', setupCustomAnimations);

// Handle parallax effects
const handleParallax = () => {
  const parallaxElements = document.querySelectorAll('.parallax-element');
  
  window.addEventListener('scroll', () => {
    const scrollTop = window.pageYOffset;
    
    parallaxElements.forEach(element => {
      const speed = parseFloat(element.getAttribute('data-parallax-speed') || '0.5');
      const offset = scrollTop * speed;
      (element as HTMLElement).style.transform = `translateY(${offset}px)`;
    });
  });
};

document.addEventListener('DOMContentLoaded', handleParallax);

// Add sticky header functionality
window.addEventListener('scroll', () => {
  const header = document.querySelector('header');
  if (header) {
    if (window.scrollY > 100) {
      header.classList.add('sticky', 'bg-white', 'shadow');
    } else {
      header.classList.remove('sticky', 'bg-white', 'shadow');
    }
  }
});

// Image gallery functionality
const initGallery = (): void => {
  const galleryItems = document.querySelectorAll('.gallery-item');
  const modal = document.getElementById('image-modal');
  const modalImage = document.getElementById('modal-image') as HTMLImageElement;
  const closeModal = document.getElementById('close-modal');

  if (galleryItems.length && modal && modalImage && closeModal) {
    galleryItems.forEach(item => {
      item.addEventListener('click', (e) => {
        const target = e.currentTarget as HTMLElement;
        const imgSrc = target.dataset.src || '';
        if (imgSrc) {
          modalImage.src = imgSrc;
          modal.classList.remove('hidden');
          document.body.classList.add('overflow-hidden');
        }
      });
    });

    closeModal.addEventListener('click', () => {
      modal.classList.add('hidden');
      document.body.classList.remove('overflow-hidden');
    });

    // Close modal on outside click
    modal.addEventListener('click', (e) => {
      if (e.target === modal) {
        modal.classList.add('hidden');
        document.body.classList.remove('overflow-hidden');
      }
    });
  }
};

// Initialize gallery if present on page
document.addEventListener('DOMContentLoaded', () => {
  if (document.querySelector('.gallery-item')) {
    initGallery();
  }
}); 