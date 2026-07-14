document.addEventListener('DOMContentLoaded', function() {
    // Accordion handler
    var accordionHeaders = document.querySelectorAll('.univ-accordion-header');
    accordionHeaders.forEach(function(header) {
        header.addEventListener('click', function() {
            var item = this.parentElement;
            var isActive = item.classList.contains('active');
            
            document.querySelectorAll('.univ-accordion-item').forEach(function(el) {
                el.classList.remove('active');
            });
            
            if (!isActive) {
                item.classList.add('active');
            }
        });
    });

    // Video modal handler
    var videoModal = document.getElementById('univ-video-modal');
    var modalIframe = document.getElementById('univ-modal-iframe');
    var videoTriggers = document.querySelectorAll('.univ-video-trigger');
    var modalClose = document.querySelector('.univ-modal-close');
    var modalOverlay = document.querySelector('.univ-modal-overlay');

    if (videoTriggers && videoModal && modalIframe) {
        videoTriggers.forEach(function(trigger) {
            trigger.addEventListener('click', function() {
                var videoUrl = this.getAttribute('data-video-url');
                modalIframe.setAttribute('src', videoUrl);
                videoModal.style.display = 'flex';
            });
        });
    }

    function closeModal() {
        if (videoModal && modalIframe) {
            videoModal.style.display = 'none';
            modalIframe.setAttribute('src', '');
        }
    }

    if (modalClose) {
        modalClose.addEventListener('click', closeModal);
    }
    if (modalOverlay) {
        modalOverlay.addEventListener('click', closeModal);
    }
    
    // Close on escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && videoModal && videoModal.style.display === 'flex') {
            closeModal();
        }
    });
});
