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

    // Client-Side Contact Form Validation
    $('#contactForm').submit(function(event) {
        var isValid = true;
        var formMessages = $('#form-feedback');
        formMessages.empty().removeClass('text-green-600 text-red-600');

        // Validate Name
        var name = $('#name').val().trim();
        if (name === '') {
            $('#nameError').text('Please enter your name.').show();
            $('#name').addClass('border-red-500');
            isValid = false;
        } else {
            $('#nameError').hide();
            $('#name').removeClass('border-red-500');
        }

        // Validate Email
        var email = $('#email').val().trim();
        var emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (email === '' || !emailRegex.test(email)) {
            $('#emailError').text('Please enter a valid email address.').show();
            $('#email').addClass('border-red-500');
            isValid = false;
        } else {
            $('#emailError').hide();
            $('#email').removeClass('border-red-500');
        }

        // Validate Subject
        var subject = $('#subject').val().trim();
        if (subject === '') {
            $('#subjectError').text('Please enter a subject.').show();
            $('#subject').addClass('border-red-500');
            isValid = false;
        } else {
            $('#subjectError').hide();
            $('#subject').removeClass('border-red-500');
        }

        // Validate Message
        var message = $('#message').val().trim();
        if (message === '') {
            $('#messageError').text('Please enter your message.').show();
            $('#message').addClass('border-red-500');
            isValid = false;
        } else {
            $('#messageError').hide();
            $('#message').removeClass('border-red-500');
        }

        if (!isValid) {
            event.preventDefault(); // Prevent form submission if validation fails
            formMessages.text('Please correct the errors above.').addClass('text-red-600');
            return false;
        }

        // If client-side validation passes, you can proceed with AJAX submission or normal form submission.
        // For this phase, we'll allow normal form submission if JS validation passes.
        // If using AJAX, you would do:
        /*
        event.preventDefault(); // Prevent default form submission
        var formData = $(this).serialize();
        $.ajax({
            type: 'POST',
            url: $(this).attr('action'), // Get URL from form's action attribute
            data: formData
        })
        .done(function(response) {
            // Make sure that the formMessages div has the 'success' class.
            formMessages.removeClass('text-red-600');
            formMessages.addClass('text-green-600');

            // Set the message text.
            formMessages.text('Message sent successfully! We will get back to you soon.');

            // Clear the form.
            $('#contactForm')[0].reset();
            $('#name').removeClass('border-red-500');
            $('#email').removeClass('border-red-500');
            $('#subject').removeClass('border-red-500');
            $('#message').removeClass('border-red-500');
        })
        .fail(function(data) {
            // Make sure that the formMessages div has the 'error' class.
            formMessages.removeClass('text-green-600');
            formMessages.addClass('text-red-600');

            // Set the message text.
            if (data.responseText !== '') {
                formMessages.text(data.responseText);
            } else {
                formMessages.text('Oops! An error occurred and your message could not be sent.');
            }
        });
        */
    });

    // Update Current Year in Footer
    if ($('#currentYear').length) {
        $('#currentYear').text(new Date().getFullYear());
    }

    // Optional: Prevent FOUC (Flash of Unstyled Content) - Basic approach
    // $('body').css('opacity', 0).animate({opacity: 1}, 300);
    // More robust FOUC prevention is often handled with CSS or by placing scripts correctly.
    // Tailwind's utility-first nature generally minimizes severe FOUC.

});
