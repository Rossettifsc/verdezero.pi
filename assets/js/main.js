
// VerdeZero - Main JavaScript
document.addEventListener('DOMContentLoaded', function() {
    
    // Smooth scrolling para links internos
    const smoothScrollLinks = document.querySelectorAll('a[href^="#"]');
    smoothScrollLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            const targetId = this.getAttribute('href');
            const targetElement = document.querySelector(targetId);
            
            if (targetElement) {
                targetElement.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        });
    });

    // Animação de entrada para elementos quando entram na viewport
    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    };

    const observer = new IntersectionObserver(function(entries) {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('vz-animate-in');
                observer.unobserve(entry.target);
            }
        });
    }, observerOptions);

    // Observar elementos para animação
    const animateElements = document.querySelectorAll('.vz-card, .vz-banner, .vz-ebook, .vz-stat');
    animateElements.forEach(el => {
        observer.observe(el);
    });

    // Header sticky com efeito de blur
    const header = document.querySelector('.vz-header');
    let lastScrollY = window.scrollY;

    window.addEventListener('scroll', function() {
        const currentScrollY = window.scrollY;
        
        if (currentScrollY > 100) {
            header.classList.add('vz-header--scrolled');
        } else {
            header.classList.remove('vz-header--scrolled');
        }

        // Efeito de parallax sutil para o hero
        if (window.innerWidth > 768) {
            const hero = document.querySelector('.vz-hero');
            if (hero) {
                const scrolled = window.pageYOffset;
                const rate = scrolled * -0.5;
                hero.style.transform = `translateY(${rate}px)`;
            }
        }

        lastScrollY = currentScrollY;
    });

    // Melhorar a experiência de formulários
    const formInputs = document.querySelectorAll('.vz-form input, .vz-form select, .vz-form textarea');
    formInputs.forEach(input => {
        // Adicionar classe quando o input tem foco
        input.addEventListener('focus', function() {
            this.parentElement.classList.add('vz-form__row--focused');
        });

        input.addEventListener('blur', function() {
            if (!this.value) {
                this.parentElement.classList.remove('vz-form__row--focused');
            }
        });

        // Adicionar classe quando o input tem valor
        if (input.value) {
            input.parentElement.classList.add('vz-form__row--focused');
        }
    });

    // Melhorar acessibilidade dos cards
    const cards = document.querySelectorAll('.vz-card');
    cards.forEach(card => {
        card.addEventListener('keydown', function(e) {
            if (e.key === 'Enter' || e.key === ' ') {
                e.preventDefault();
                const link = this.querySelector('a');
                if (link) {
                    link.click();
                }
            }
        });

        // Adicionar indicador de foco visual
        card.addEventListener('focus', function() {
            this.classList.add('vz-card--focused');
        });

        card.addEventListener('blur', function() {
            this.classList.remove('vz-card--focused');
        });
    });

    // Lazy loading para imagens
    if ('IntersectionObserver' in window) {
        const imageObserver = new IntersectionObserver((entries, observer) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const img = entry.target;
                    img.src = img.dataset.src || img.src;
                    img.classList.remove('vz-lazy');
                    imageObserver.unobserve(img);
                }
            });
        });

        const lazyImages = document.querySelectorAll('img[data-src]');
        lazyImages.forEach(img => imageObserver.observe(img));
    }

    // Melhorar a experiência de navegação
    const navLinks = document.querySelectorAll('.vz-nav a');
    const currentPath = window.location.pathname;
    
    navLinks.forEach(link => {
        if (link.getAttribute('href') && link.getAttribute('href').includes(currentPath.split('/').pop())) {
            link.classList.add('vz-nav__link--active');
        }
    });

    // Adicionar efeito de hover nos botões
    const buttons = document.querySelectorAll('.vz-btn, .vz-btn-outline');
    buttons.forEach(button => {
        button.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-2px)';
        });

        button.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0)';
        });
    });

    // Melhorar a experiência de pesquisa
    const searchForm = document.querySelector('.vz-search');
    const searchInput = document.querySelector('.vz-search input');
    
    if (searchForm && searchInput) {
        searchForm.addEventListener('submit', function(e) {
            if (!searchInput.value.trim()) {
                e.preventDefault();
                searchInput.focus();
                searchInput.classList.add('vz-search__input--error');
                
                setTimeout(() => {
                    searchInput.classList.remove('vz-search__input--error');
                }, 2000);
            }
        });

        // Auto-resize do input de pesquisa
        searchInput.addEventListener('input', function() {
            this.style.width = 'auto';
            this.style.width = this.scrollWidth + 'px';
        });
    }

    // Adicionar indicadores de carregamento
    const loadingStates = document.querySelectorAll('[data-loading]');
    loadingStates.forEach(element => {
        element.addEventListener('click', function() {
            if (!this.classList.contains('vz-loading')) {
                this.classList.add('vz-loading');
                this.innerHTML = '<span class="vz-spinner"></span> Carregando...';
                
                // Simular carregamento (remover em produção)
                setTimeout(() => {
                    this.classList.remove('vz-loading');
                    this.innerHTML = this.dataset.originalText || 'Concluído';
                }, 2000);
            }
        });
    });

    // Melhorar a experiência em dispositivos móveis
    if (window.innerWidth <= 768) {
        // Adicionar swipe gestures para navegação
        let startX = 0;
        let startY = 0;
        
        document.addEventListener('touchstart', function(e) {
            startX = e.touches[0].clientX;
            startY = e.touches[0].clientY;
        });

        document.addEventListener('touchend', function(e) {
            if (!startX || !startY) return;
            
            const endX = e.changedTouches[0].clientX;
            const endY = e.changedTouches[0].clientY;
            
            const diffX = startX - endX;
            const diffY = startY - endY;
            
            // Swipe horizontal
            if (Math.abs(diffX) > Math.abs(diffY) && Math.abs(diffX) > 50) {
                if (diffX > 0) {
                    // Swipe left - próximo
                    console.log('Swipe left');
                } else {
                    // Swipe right - anterior
                    console.log('Swipe right');
                }
            }
            
            startX = 0;
            startY = 0;
        });
    }

    // Adicionar classe para dispositivos com hover
    if (window.matchMedia('(hover: hover)').matches) {
        document.body.classList.add('vz-has-hover');
    }

    // Melhorar performance com debounce para eventos de scroll
    let scrollTimeout;
    window.addEventListener('scroll', function() {
        if (scrollTimeout) {
            clearTimeout(scrollTimeout);
        }
        
        scrollTimeout = setTimeout(function() {
            // Executar código após o scroll parar
            document.body.classList.add('vz-scroll-stopped');
            
            setTimeout(() => {
                document.body.classList.remove('vz-scroll-stopped');
            }, 100);
        }, 150);
    });

    // Adicionar suporte para tema escuro (se implementado no futuro)
    const prefersDark = window.matchMedia('(prefers-color-scheme: dark)');
    
    function handleThemeChange(e) {
        if (e.matches) {
            document.body.classList.add('vz-theme-dark');
        } else {
            document.body.classList.remove('vz-theme-dark');
        }
    }
    
    prefersDark.addListener(handleThemeChange);
    handleThemeChange(prefersDark);

    // Console log para desenvolvedores
    console.log('🚀 VerdeZero carregado com sucesso!');
    console.log('📱 Versão responsiva ativa');
    console.log('✨ Animações e interações habilitadas');
});

// Utilitários globais
window.VZUtils = {
    // Debounce function
    debounce: function(func, wait) {
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

    // Throttle function
    throttle: function(func, limit) {
        let inThrottle;
        return function() {
            const args = arguments;
            const context = this;
            if (!inThrottle) {
                func.apply(context, args);
                inThrottle = true;
                setTimeout(() => inThrottle = false, limit);
            }
        };
    },

    // Smooth scroll to element
    scrollToElement: function(element, offset = 0) {
        const elementPosition = element.getBoundingClientRect().top;
        const offsetPosition = elementPosition + window.pageYOffset - offset;
        
        window.scrollTo({
            top: offsetPosition,
            behavior: 'smooth'
        });
    }
};
