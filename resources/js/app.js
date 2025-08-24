import "./bootstrap";

import Alpine from "alpinejs";

// Prevent multiple Alpine instances
if (!window.Alpine) {
    window.Alpine = Alpine;
}

// Portfolio scroll and animation utilities
window.portfolioUtils = {
    // Smooth scroll to section with offset for fixed navigation
    scrollToSection(sectionId, offset = 64) {
        const element = document.getElementById(sectionId);
        if (element) {
            const targetPosition = element.offsetTop - offset;
            window.scrollTo({
                top: targetPosition,
                behavior: "smooth",
            });

            // Update URL hash without triggering scroll
            history.replaceState(null, null, `#${sectionId}`);
        }
    },

    // Animate elements when they come into view
    initScrollAnimations() {
        const observerOptions = {
            root: null,
            rootMargin: "0px 0px -100px 0px",
            threshold: 0.1,
        };

        const observer = new IntersectionObserver((entries) => {
            entries.forEach((entry) => {
                if (entry.isIntersecting) {
                    entry.target.classList.add("animate-in");
                }
            });
        }, observerOptions);

        // Observe all elements with animation classes
        document
            .querySelectorAll(".section-content, .stagger-animation")
            .forEach((el) => {
                observer.observe(el);
            });
    },

    // Initialize hash routing
    initHashRouting() {
        // Handle initial hash on page load
        const hash = window.location.hash.substring(1);
        if (
            hash &&
            ["home", "about", "skills", "projects", "contact"].includes(hash)
        ) {
            setTimeout(() => {
                this.scrollToSection(hash);
            }, 100);
        }

        // Handle hash changes
        window.addEventListener("hashchange", () => {
            const hash = window.location.hash.substring(1);
            if (
                hash &&
                ["home", "about", "skills", "projects", "contact"].includes(
                    hash
                )
            ) {
                this.scrollToSection(hash);
            }
        });
    },

    // Debounce function for scroll events
    debounce(func, wait) {
        let timeout;
        return function executedFunction(...args) {
            const later = () => {
                clearTimeout(timeout);
                func(...args);
            };
            clearTimeout(timeout);
            timeout = setTimeout(later, wait);
        };
    },

    // Get current section based on scroll position
    getCurrentSection(
        sections = ["home", "about", "skills", "projects", "contact"]
    ) {
        const scrollPosition = window.scrollY + 100;

        for (let i = sections.length - 1; i >= 0; i--) {
            const section = document.getElementById(sections[i]);
            if (section && section.offsetTop <= scrollPosition) {
                return sections[i];
            }
        }
        return sections[0];
    },

    // Show loading state for components
    showLoading(element) {
        if (element) {
            element.classList.add("opacity-50");
            const spinner = element.querySelector(".loading-spinner");
            if (spinner) {
                spinner.classList.remove("hidden");
            }
        }
    },

    // Hide loading state for components
    hideLoading(element) {
        if (element) {
            element.classList.remove("opacity-50");
            const spinner = element.querySelector(".loading-spinner");
            if (spinner) {
                spinner.classList.add("hidden");
            }
        }
    },

    // Initialize component transitions
    initComponentTransitions() {
        // Check if Livewire is available before setting up hooks
        if (typeof Livewire !== "undefined") {
            // Listen for Livewire component updates
            document.addEventListener("livewire:init", () => {
                if (Livewire.hook) {
                    Livewire.hook("morph.updating", ({ component }) => {
                        const element = component.el;
                        this.showLoading(element);
                    });

                    Livewire.hook("morph.updated", ({ component }) => {
                        const element = component.el;
                        this.hideLoading(element);

                        // Re-initialize animations for new content
                        setTimeout(() => {
                            this.initScrollAnimations();
                        }, 100);
                    });
                }
            });
        }
    },
};

// Portfolio component integration
window.portfolioIntegration = {
    // Initialize all portfolio functionality
    init() {
        this.setupGlobalEventListeners();
        this.initializeComponents();
        this.handlePreloadedContent();
    },

    // Set up global event listeners for component communication
    setupGlobalEventListeners() {
        // Check if Livewire is available before setting up listeners
        if (typeof Livewire !== "undefined") {
            // Listen for section changes from navigation
            document.addEventListener("livewire:init", () => {
                if (Livewire.on) {
                    Livewire.on("setActiveSection", (data) => {
                        const section = data.section || data[0]?.section;
                        if (section) {
                            window.portfolioUtils.scrollToSection(section);
                        }
                    });
                }
            });
        }

        // Handle browser back/forward navigation
        window.addEventListener("popstate", () => {
            const hash = window.location.hash.substring(1);
            if (hash) {
                window.portfolioUtils.scrollToSection(hash);
            }
        });
    },

    // Initialize all components
    initializeComponents() {
        // Initialize scroll animations
        window.portfolioUtils.initScrollAnimations();

        // Initialize hash routing
        window.portfolioUtils.initHashRouting();

        // Initialize component transitions
        window.portfolioUtils.initComponentTransitions();

        // Initialize image optimization
        window.imageOptimization.init();
    },

    // Handle any preloaded content or initial state
    handlePreloadedContent() {
        // Trigger initial animations after a short delay
        setTimeout(() => {
            const sections = document.querySelectorAll(
                ".section-content, .stagger-animation"
            );
            sections.forEach((section, index) => {
                if (this.isElementInViewport(section)) {
                    setTimeout(() => {
                        section.classList.add("animate-in");
                    }, index * 100);
                }
            });
        }, 300);
    },

    // Check if element is in viewport
    isElementInViewport(el) {
        const rect = el.getBoundingClientRect();
        return (
            rect.top >= 0 &&
            rect.left >= 0 &&
            rect.bottom <=
                (window.innerHeight || document.documentElement.clientHeight) &&
            rect.right <=
                (window.innerWidth || document.documentElement.clientWidth)
        );
    },
};

// Image lazy loading and optimization utilities
window.imageOptimization = {
    // Initialize lazy loading for images
    initLazyLoading() {
        // Use Intersection Observer for better performance
        const imageObserver = new IntersectionObserver(
            (entries, observer) => {
                entries.forEach((entry) => {
                    if (entry.isIntersecting) {
                        const img = entry.target;
                        this.loadImage(img);
                        observer.unobserve(img);
                    }
                });
            },
            {
                root: null,
                rootMargin: "50px 0px",
                threshold: 0.01,
            }
        );

        // Observe all lazy images
        document.querySelectorAll("img[data-src]").forEach((img) => {
            imageObserver.observe(img);
        });

        // Also observe images that might be added dynamically
        this.observeNewImages(imageObserver);
    },

    // Load individual image
    loadImage(img) {
        const src = img.getAttribute("data-src");
        if (!src) return;

        // Create a new image to preload
        const imageLoader = new Image();

        imageLoader.onload = () => {
            // Image loaded successfully, update src and add loaded class
            img.src = src;
            img.classList.add("image-loaded");
            img.classList.remove("image-loading");

            // Remove data-src attribute
            img.removeAttribute("data-src");

            // Trigger fade-in animation
            setTimeout(() => {
                img.style.opacity = "1";
            }, 50);
        };

        imageLoader.onerror = () => {
            // Handle error - show placeholder or fallback
            img.classList.add("image-error");
            img.classList.remove("image-loading");
            console.warn("Failed to load image:", src);
        };

        // Add loading class and start loading
        img.classList.add("image-loading");
        img.style.opacity = "0.5";
        imageLoader.src = src;
    },

    // Observe new images added to DOM (for Livewire updates)
    observeNewImages(observer) {
        // Watch for DOM changes
        const mutationObserver = new MutationObserver((mutations) => {
            mutations.forEach((mutation) => {
                mutation.addedNodes.forEach((node) => {
                    if (node.nodeType === 1) {
                        // Element node
                        // Check if the node itself is a lazy image
                        if (node.matches && node.matches("img[data-src]")) {
                            observer.observe(node);
                        }

                        // Check for lazy images within the node
                        const lazyImages = node.querySelectorAll
                            ? node.querySelectorAll("img[data-src]")
                            : [];
                        lazyImages.forEach((img) => observer.observe(img));
                    }
                });
            });
        });

        mutationObserver.observe(document.body, {
            childList: true,
            subtree: true,
        });
    },

    // Preload critical images
    preloadCriticalImages() {
        const criticalImages = document.querySelectorAll(
            'img[data-critical="true"]'
        );
        criticalImages.forEach((img) => {
            this.loadImage(img);
        });
    },

    // Initialize WebP support detection
    initWebPSupport() {
        const webpSupported = this.supportsWebP();
        document.documentElement.classList.toggle(
            "webp-supported",
            webpSupported
        );
        document.documentElement.classList.toggle("no-webp", !webpSupported);
    },

    // Check WebP support
    supportsWebP() {
        const canvas = document.createElement("canvas");
        canvas.width = 1;
        canvas.height = 1;
        return canvas.toDataURL("image/webp").indexOf("data:image/webp") === 0;
    },

    // Initialize all image optimization features
    init() {
        this.initWebPSupport();
        this.initLazyLoading();
        this.preloadCriticalImages();
    },
};

// Alpine.js global data and utilities
window.Alpine.data("portfolioNavigation", () => ({
    mobileMenuOpen: false,
    activeSection: "home",

    init() {
        // Check if we're in a Livewire component context
        if (this.$wire) {
            // Use Livewire entanglement if available
            try {
                this.mobileMenuOpen = this.$wire.entangle("mobileMenuOpen");
                this.activeSection = this.$wire.entangle("activeSection");
            } catch (error) {
                console.warn(
                    "Livewire entanglement failed, using local state:",
                    error
                );
            }
        }

        // Initialize navigation state
        this.updateActiveSection();

        // Listen for scroll events to update active section
        window.addEventListener(
            "scroll",
            window.portfolioUtils.debounce(() => {
                this.updateActiveSection();
            }, 100)
        );
    },

    updateActiveSection() {
        const currentSection = window.portfolioUtils.getCurrentSection();
        if (this.$wire && this.$wire.set) {
            this.$wire.set("activeSection", currentSection);
        } else {
            this.activeSection = currentSection;
        }
    },

    toggleMobileMenu() {
        if (this.$wire && this.$wire.set) {
            this.$wire.set("mobileMenuOpen", !this.mobileMenuOpen);
        } else {
            this.mobileMenuOpen = !this.mobileMenuOpen;
        }
    },

    navigateToSection(section) {
        if (this.$wire && this.$wire.call) {
            this.$wire.call("scrollToSection", section);
        } else {
            this.activeSection = section;
            this.mobileMenuOpen = false;
            window.portfolioUtils.scrollToSection(section);
        }
    },
}));

window.Alpine.data("portfolioSection", () => ({
    isVisible: false,
    animateOnLoad: false,

    init() {
        // Intersection Observer for scroll-triggered animations
        const observer = new IntersectionObserver(
            (entries) => {
                entries.forEach((entry) => {
                    if (entry.isIntersecting) {
                        this.isVisible = true;
                        this.animateOnLoad = true;

                        // Trigger animations for child skill progress bars
                        this.animateChildSkills();
                    }
                });
            },
            { threshold: 0.2 }
        );

        observer.observe(this.$el);
    },

    animateChildSkills() {
        // Find and animate child skill progress components
        setTimeout(() => {
            const skillElements = this.$el.querySelectorAll(
                '[x-data*="skillProgress"]'
            );
            skillElements.forEach((element, index) => {
                // Try to access the Alpine component and trigger animation
                if (
                    element._x_dataStack &&
                    element._x_dataStack[0] &&
                    element._x_dataStack[0].animate
                ) {
                    setTimeout(() => {
                        element._x_dataStack[0].animate();
                    }, index * 100);
                }
            });
        }, 300);
    },
}));

// Add a specific skills section component
window.Alpine.data("portfolioSkills", () => ({
    isVisible: false,
    animateOnLoad: false,

    init() {
        // Check if we're in a Livewire component context
        if (this.$wire) {
            try {
                this.animateOnLoad = this.$wire.entangle("animateOnLoad");
            } catch (error) {
                console.warn(
                    "Livewire entanglement failed, using local state:",
                    error
                );
            }
        }

        // Intersection Observer for scroll-triggered animations
        const observer = new IntersectionObserver(
            (entries) => {
                entries.forEach((entry) => {
                    if (entry.isIntersecting) {
                        this.isVisible = true;
                        if (this.$wire && this.$wire.call) {
                            this.$wire.call("startAnimation");
                        } else {
                            this.animateOnLoad = true;
                        }

                        // Trigger skill bar animations with staggered delays
                        this.animateSkillBars();
                    }
                });
            },
            { threshold: 0.2 }
        );

        observer.observe(this.$el);
    },

    animateSkillBars() {
        // Animate all skill progress bars in this section
        setTimeout(() => {
            const skillBars = this.$el.querySelectorAll(
                '[x-data*="skillProgress"]'
            );
            skillBars.forEach((bar, index) => {
                setTimeout(() => {
                    // Dispatch a custom event that skill bars can listen to
                    bar.dispatchEvent(
                        new CustomEvent("animate-skill", {
                            detail: { delay: index * 100 },
                        })
                    );
                }, index * 100);
            });
        }, 200);
    },
}));

window.Alpine.data("portfolioContact", () => ({
    isVisible: false,
    animateOnLoad: false,
    isSubmitted: false,
    isSubmitting: false,
    emailError: null,

    init() {
        // Check if we're in a Livewire component context
        if (this.$wire) {
            try {
                this.animateOnLoad = this.$wire.entangle("animateOnLoad");
                this.isSubmitted = this.$wire.entangle("isSubmitted");
                this.isSubmitting = this.$wire.entangle("isSubmitting");
                this.emailError = this.$wire.entangle("emailError");
            } catch (error) {
                console.warn(
                    "Livewire entanglement failed, using local state:",
                    error
                );
            }
        }

        // Intersection Observer for scroll-triggered animations
        const observer = new IntersectionObserver(
            (entries) => {
                entries.forEach((entry) => {
                    if (entry.isIntersecting) {
                        this.isVisible = true;
                        if (this.$wire && this.$wire.call) {
                            this.$wire.call("startAnimation");
                        } else {
                            this.animateOnLoad = true;
                        }
                    }
                });
            },
            { threshold: 0.2 }
        );

        observer.observe(this.$el);
    },

    submitForm() {
        if (this.$wire && this.$wire.call) {
            this.$wire.call("submit");
        } else {
            // Fallback for standalone mode
            this.isSubmitting = true;
            setTimeout(() => {
                this.isSubmitting = false;
                this.isSubmitted = true;
            }, 1000);
        }
    },

    resetForm() {
        if (this.$wire && this.$wire.call) {
            this.$wire.call("resetForm");
        } else {
            this.isSubmitted = false;
            this.emailError = null;
        }
    },
}));

window.Alpine.data("portfolioProjects", () => ({
    isVisible: false,
    animateOnLoad: false,
    showModal: false,
    currentImageIndex: 0,

    init() {
        // Check if we're in a Livewire component context
        if (this.$wire) {
            try {
                this.animateOnLoad = this.$wire.entangle("animateOnLoad");
                this.showModal = this.$wire.entangle("showModal");
            } catch (error) {
                console.warn(
                    "Livewire entanglement failed, using local state:",
                    error
                );
            }
        }

        // Intersection Observer for scroll-triggered animations
        const observer = new IntersectionObserver(
            (entries) => {
                entries.forEach((entry) => {
                    if (entry.isIntersecting) {
                        this.isVisible = true;
                        if (this.$wire && this.$wire.call) {
                            this.$wire.call("startAnimation");
                        } else {
                            this.animateOnLoad = true;
                        }
                    }
                });
            },
            { threshold: 0.1 }
        );

        observer.observe(this.$el);

        // Handle keyboard navigation for modal
        document.addEventListener("keydown", (e) => {
            if (this.showModal) {
                if (e.key === "Escape") {
                    this.closeModal();
                } else if (e.key === "ArrowLeft") {
                    this.previousImage();
                } else if (e.key === "ArrowRight") {
                    this.nextImage();
                }
            }
        });
    },

    openModal(imageIndex = 0) {
        this.currentImageIndex = imageIndex;
        if (this.$wire && this.$wire.call) {
            this.$wire.call("openModal", imageIndex);
        } else {
            this.showModal = true;
        }
    },

    closeModal() {
        if (this.$wire && this.$wire.call) {
            this.$wire.call("closeModal");
        } else {
            this.showModal = false;
        }
    },

    nextImage() {
        if (this.$wire && this.$wire.call) {
            this.$wire.call("nextImage");
        } else {
            console.log("Next image");
        }
    },

    previousImage() {
        if (this.$wire && this.$wire.call) {
            this.$wire.call("previousImage");
        } else {
            console.log("Previous image");
        }
    },
}));

window.Alpine.data("skillProgress", (targetWidth, delay = 0) => ({
    width: 0,
    hasAnimated: false,

    init() {
        // Listen for custom animate event
        this.$el.addEventListener("animate-skill", (event) => {
            if (!this.hasAnimated) {
                const eventDelay = event.detail?.delay || 0;
                setTimeout(() => {
                    this.animate();
                }, eventDelay);
            }
        });

        // Multiple ways to trigger the animation
        this.startAnimation(delay);
    },

    startAnimation(delay = 0) {
        setTimeout(() => {
            // Try to check parent visibility first (with safety checks)
            try {
                if (
                    this.$parent &&
                    typeof this.$parent.isVisible !== "undefined"
                ) {
                    if (this.$parent.isVisible) {
                        this.animate();
                    } else {
                        // Watch for parent visibility change
                        this.$watch("$parent.isVisible", (isVisible) => {
                            if (isVisible && !this.hasAnimated) {
                                this.animate();
                            }
                        });
                    }
                } else {
                    // Fallback: use intersection observer
                    this.animateWithObserver();
                }
            } catch (error) {
                // If $parent access fails, use intersection observer
                console.warn(
                    "Parent access failed, using intersection observer:",
                    error
                );
                this.animateWithObserver();
            }
        }, delay);
    },

    animateWithObserver() {
        // Create intersection observer for this element
        const observer = new IntersectionObserver(
            (entries) => {
                entries.forEach((entry) => {
                    if (entry.isIntersecting && !this.hasAnimated) {
                        this.animate();
                        observer.unobserve(entry.target);
                    }
                });
            },
            { threshold: 0.3 }
        );

        // Observe the element or its parent container
        const elementToObserve =
            this.$el.closest(".skills-container") ||
            this.$el.closest(".skill-category") ||
            this.$el.closest("section") ||
            this.$el;
        observer.observe(elementToObserve);
    },

    // Method to manually trigger animation
    animate() {
        if (!this.hasAnimated) {
            this.width = targetWidth;
            this.hasAnimated = true;
        }
    },
}));

// Initialize portfolio functionality when DOM is loaded
document.addEventListener("DOMContentLoaded", () => {
    try {
        // Only initialize if Alpine hasn't started yet
        if (!window.Alpine.version) {
            window.portfolioIntegration.init();
            Alpine.start();
        } else {
            // Alpine already started, just initialize portfolio integration
            window.portfolioIntegration.init();
        }
    } catch (error) {
        console.error("Error initializing portfolio:", error);
        // Fallback: try to start Alpine anyway
        if (typeof Alpine !== "undefined" && !window.Alpine.version) {
            Alpine.start();
        }
    }
});
