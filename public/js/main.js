$(document).ready(function() {
    // Mobile Menu Toggle
    $('#mobile-menu-button').click(function() {
        $('#mobile-menu').slideToggle(300);
        // Optional: toggle aria-expanded attribute or change icon
        var isExpanded = $(this).attr('aria-expanded') === 'true';
        $(this).attr('aria-expanded', !isExpanded);
        // Example: Change hamburger to X icon (requires two SVGs or font icons)
        // $(this).find('svg').toggleClass('fa-bars fa-times');
    });

    // Smooth Scrolling for specific links (e.g., hero CTA on homepage)
    $('a[href^="#"]').on('click', function(event) {
        var target = $(this.getAttribute('href'));
        if (target.length) {
            event.preventDefault();
            $('html, body').stop().animate({
                scrollTop: target.offset().top - 80 // Adjust -80 for sticky header height
            }, 800);
        }
    });

    // Client-Side Contact Form Validation & AJAX Submission
    const contactForm = document.getElementById('contactForm');
    const formFeedback = document.getElementById('form-feedback');

    if (contactForm) {
        contactForm.addEventListener('submit', function(event) {
            event.preventDefault(); // Stop the default form submission

            // Clear previous feedback
            formFeedback.textContent = '';
            formFeedback.className = 'mt-5 text-center text-base';

            // --- Client-side validation ---
            let isValid = true;
            const name = document.getElementById('name');
            const email = document.getElementById('email');
            const subject = document.getElementById('subject');
            const message = document.getElementById('message');
            
            // Helper to show/hide errors
            const showError = (field, errorId, message) => {
                document.getElementById(errorId).textContent = message;
                document.getElementById(errorId).style.display = 'block';
                field.classList.add('border-red-500');
                isValid = false;
            };
            const clearError = (field, errorId) => {
                document.getElementById(errorId).style.display = 'none';
                field.classList.remove('border-red-500');
            };

            // Validate Name
            if (name.value.trim() === '') {
                showError(name, 'nameError', 'Please enter your name.');
            } else {
                clearError(name, 'nameError');
            }

            // Validate Email
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (email.value.trim() === '' || !emailRegex.test(email.value)) {
                showError(email, 'emailError', 'Please enter a valid email address.');
            } else {
                clearError(email, 'emailError');
            }

            // Validate Subject
            if (subject.value.trim() === '') {
                showError(subject, 'subjectError', 'Please enter a subject.');
            } else {
                clearError(subject, 'subjectError');
            }

            // Validate Message
            if (message.value.trim() === '') {
                showError(message, 'messageError', 'Please enter your message.');
            } else {
                clearError(message, 'messageError');
            }

            if (!isValid) {
                formFeedback.textContent = 'Please correct the errors above.';
                formFeedback.classList.add('text-red-600');
                return;
            }

            // --- AJAX Submission using Fetch ---
            const formData = {
                name: name.value.trim(),
                email: email.value.trim(),
                subject: subject.value.trim(),
                message: message.value.trim()
            };

            const submitButton = contactForm.querySelector('button[type="submit"]');
            const originalButtonText = submitButton.innerHTML;
            submitButton.disabled = true;
            submitButton.innerHTML = 'Sending...';

            fetch('php/contact_handler.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                },
                body: JSON.stringify(formData)
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    formFeedback.textContent = data.message;
                    formFeedback.classList.add('text-green-600');
                    contactForm.reset(); // Clear the form fields
                    // Clear all error states visually
                    clearError(name, 'nameError');
                    clearError(email, 'emailError');
                    clearError(subject, 'subjectError');
                    clearError(message, 'messageError');
                } else {
                    formFeedback.textContent = data.message || 'An unknown error occurred.';
                    formFeedback.classList.add('text-red-600');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                formFeedback.textContent = 'A network error occurred. Please try again later.';
                formFeedback.classList.add('text-red-600');
            })
            .finally(() => {
                // Re-enable the button
                submitButton.disabled = false;
                submitButton.innerHTML = originalButtonText;
            });
        });
    }

    // Update Current Year in Footer
    if ($('#currentYear').length) {
        $('#currentYear').text(new Date().getFullYear());
    }

    // FadeIn for project and testimonial cards on their respective pages
    if ($('#project-list').length) {
        $('#project-list > div').css('opacity', 0).each(function(i) {
            $(this).delay(i * 150).animate({'opacity': 1}, 500);
        });
    }

    if ($('#testimonial-list').length) {
        $('#testimonial-list > div').css('opacity', 0).each(function(i) {
            $(this).delay(i * 150).animate({'opacity': 1}, 500);
        });
    }

    // Scroll-based animations for sections
    const $sectionsToAnimate = $('main section[id]'); // Target sections within main with an ID

    if ("IntersectionObserver" in window) {
        // Set initial state for sections to be animated (except hero)
        $sectionsToAnimate.each(function() {
            if (!$(this).is('#hero')) { // Hero section is typically visible on load
                $(this).addClass('section-hidden');
            } else {
                $(this).addClass('section-visible'); // Ensure hero is marked visible
            }
        });

        let sectionObserver = new IntersectionObserver((entries, observer) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    $(entry.target).removeClass('section-hidden').addClass('section-visible');
                    observer.unobserve(entry.target);
                }
            });
        }, { threshold: 0.15 , rootMargin: "0px 0px -50px 0px"}); // Trigger when 15% of the section is visible, slightly offset from bottom

        $sectionsToAnimate.each(function() {
            if ($(this).hasClass('section-hidden')) { // Only observe initially hidden sections
                sectionObserver.observe(this);
            }
        });

    } else {
        // Fallback for browsers that don't support IntersectionObserver: just show all sections
        $sectionsToAnimate.removeClass('section-hidden').addClass('section-visible');
    }

    // Optional: Prevent FOUC (Flash of Unstyled Content) - Basic approach
    // $('body').css('opacity', 0).animate({opacity: 1}, 300);
    // More robust FOUC prevention is often handled with CSS or by placing scripts correctly.
    // Tailwind's utility-first nature generally minimizes severe FOUC.

});
