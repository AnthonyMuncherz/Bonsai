// Main TypeScript file for Sejuta Ranting Bonsai website

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

// Mobile navigation toggle
document.addEventListener('DOMContentLoaded', () => {
  const mobileMenuToggle = document.getElementById('mobile-menu-toggle');
  const mobileMenu = document.getElementById('mobile-menu');
  
  if (mobileMenuToggle && mobileMenu) {
    mobileMenuToggle.addEventListener('click', () => {
      mobileMenu.classList.toggle('hidden');
    });
  }

  // Initialize current year in footer
  const yearElement = document.querySelector('.copyright-year');
  if (yearElement) {
    yearElement.textContent = new Date().getFullYear().toString();
  }

  // Add smooth scrolling for anchor links
  document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function(this: HTMLAnchorElement, e: Event) {
      const href = this.getAttribute('href');
      if (href) {
        const target = document.querySelector(href);
        if (target) {
          e.preventDefault();
          target.scrollIntoView({
            behavior: 'smooth'
          });
        }
      }
    });
  });
});

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