 class EnhancedSlideshow {
            constructor(container) {
                this.container = container;
                this.slides = container.querySelectorAll('.slide');
                this.dots = container.querySelectorAll('.dot');
                this.prevBtn = container.querySelector('.slideshow-nav.prev');
                this.nextBtn = container.querySelector('.slideshow-nav.next');
                this.progressFill = container.querySelector('.progress-fill');
                this.currentSlideSpan = container.querySelector('.current-slide');
                this.totalSlidesSpan = container.querySelector('.total-slides');
                
                this.currentSlide = 0;
                this.slideCount = this.slides.length;
                this.slideInterval = null;
                this.progressInterval = null;
                this.slideDuration = 2000; // 2 seconds per slide
                this.isTransitioning = false;
                
                // Touch support
                this.touchStartX = 0;
                this.touchEndX = 0;
                this.minSwipeDistance = 50;
                
                this.init();
            }

            init() {
                this.totalSlidesSpan.textContent = this.slideCount;
                this.updateSlideCounter();
                this.setupEventListeners();
                this.startSlideshow();
            }

            setupEventListeners() {
                // Navigation buttons
                this.prevBtn.addEventListener('click', () => this.prevSlide());
                this.nextBtn.addEventListener('click', () => this.nextSlide());

                // Dots navigation
                this.dots.forEach((dot, index) => {
                    dot.addEventListener('click', () => this.goToSlide(index));
                });

                // Pause on hover
                this.container.addEventListener('mouseenter', () => this.pauseSlideshow());
                this.container.addEventListener('mouseleave', () => this.resumeSlideshow());

                // Touch events for mobile
                this.container.addEventListener('touchstart', (e) => this.handleTouchStart(e), { passive: true });
                this.container.addEventListener('touchend', (e) => this.handleTouchEnd(e), { passive: true });

                // Keyboard navigation
                document.addEventListener('keydown', (e) => this.handleKeyPress(e));

                // Visibility API to pause when tab is not active
                document.addEventListener('visibilitychange', () => {
                    if (document.hidden) {
                        this.pauseSlideshow();
                    } else {
                        this.resumeSlideshow();
                    }
                });
            }

            showSlide(index, direction = 'next') {
                if (this.isTransitioning) return;
                
                this.isTransitioning = true;
                
                // Reset all slides
                this.slides.forEach(slide => {
                    slide.classList.remove('active', 'next', 'prev');
                });
                
                this.dots.forEach(dot => {
                    dot.classList.remove('active');
                });

                // Update current slide index
                this.currentSlide = (index + this.slideCount) % this.slideCount;

                // Apply transitions
                const currentSlideEl = this.slides[this.currentSlide];
                currentSlideEl.classList.add('active');
                
                if (direction === 'next') {
                    currentSlideEl.classList.add('next');
                    setTimeout(() => currentSlideEl.classList.remove('next'), 50);
                } else if (direction === 'prev') {
                    currentSlideEl.classList.add('prev');
                    setTimeout(() => currentSlideEl.classList.remove('prev'), 50);
                }

                // Update dots
                this.dots[this.currentSlide].classList.add('active');
                
                // Update counter
                this.updateSlideCounter();

                // Reset transition flag
                setTimeout(() => {
                    this.isTransitioning = false;
                }, 800);

                // Add loading animation
                const loadingEl = currentSlideEl.querySelector('.slide-loading');
                if (loadingEl) {
                    loadingEl.style.animation = 'none';
                    setTimeout(() => {
                        loadingEl.style.animation = 'slideLoading 1.5s ease-in-out';
                    }, 100);
                }
            }

            nextSlide() {
                this.showSlide(this.currentSlide + 1, 'next');
            }

            prevSlide() {
                this.showSlide(this.currentSlide - 1, 'prev');
            }

            goToSlide(index) {
                const direction = index > this.currentSlide ? 'next' : 'prev';
                this.showSlide(index, direction);
                this.restartSlideshow();
            }

            updateSlideCounter() {
                this.currentSlideSpan.textContent = this.currentSlide + 1;
            }

            startSlideshow() {
                this.slideInterval = setInterval(() => {
                    this.nextSlide();
                }, this.slideDuration);
                
                this.startProgressBar();
            }

            startProgressBar() {
                let progress = 0;
                const increment = 100 / (this.slideDuration / 50);
                
                this.progressInterval = setInterval(() => {
                    progress += increment;
                    this.progressFill.style.width = Math.min(progress, 100) + '%';
                    
                    if (progress >= 100) {
                        progress = 0;
                    }
                }, 50);
            }

            pauseSlideshow() {
                clearInterval(this.slideInterval);
                clearInterval(this.progressInterval);
            }

            resumeSlideshow() {
                this.startSlideshow();
            }

            restartSlideshow() {
                this.pauseSlideshow();
                this.progressFill.style.width = '0%';
                setTimeout(() => this.startSlideshow(), 100);
            }

            // Touch event handlers
            handleTouchStart(e) {
                this.touchStartX = e.changedTouches[0].screenX;
            }

            handleTouchEnd(e) {
                this.touchEndX = e.changedTouches[0].screenX;
                this.handleSwipe();
            }

            handleSwipe() {
                const swipeDistance = this.touchStartX - this.touchEndX;
                
                if (Math.abs(swipeDistance) > this.minSwipeDistance) {
                    if (swipeDistance > 0) {
                        // Swipe left - next slide
                        this.nextSlide();
                    } else {
                        // Swipe right - previous slide
                        this.prevSlide();
                    }
                    this.restartSlideshow();
                }
            }

            // Keyboard navigation
            handleKeyPress(e) {
                if (e.key === 'ArrowLeft') {
                    this.prevSlide();
                    this.restartSlideshow();
                } else if (e.key === 'ArrowRight') {
                    this.nextSlide();
                    this.restartSlideshow();
                }
            }
        }

        // Initialize slideshow when DOM is loaded
        document.addEventListener('DOMContentLoaded', function() {
            const slideshowContainer = document.querySelector('.header__slideshow');
            if (slideshowContainer) {
                new EnhancedSlideshow(slideshowContainer);
            }
        });