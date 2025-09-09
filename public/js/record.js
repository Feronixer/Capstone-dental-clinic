window.previewForm = function(formName) {
    const formPreviewTitle = document.getElementById('formPreviewTitle');
    const formPreviewContent = document.getElementById('formPreviewContent');
    if (formPreviewTitle && formPreviewContent) {
        formPreviewTitle.textContent = formName;
        if (formName === "Dental Medical Clearance Form") {
            formPreviewContent.innerHTML = `
                <h4>Patient information</h4>
                <div class="field-group"><span class="field-label">Name:</span> <span class="field-value">Juan Dela Cruz</span></div>
                <div class="field-group"><span class="field-label">Date of birth:</span> <span class="field-value">01/15/1990</span></div>
                <div class="field-group"><span class="field-label">Gender:</span> <span class="field-value">Male</span></div>
                <div class="field-group"><span class="field-label">Contact number:</span> <span class="field-value">09171234567</span></div>
                <div class="field-group"><span class="field-label">Address:</span> <span class="field-value">123 Main St, Quezon City</span></div>
                <!-- ...rest of preview content... -->
            `;
        } else {
            formPreviewContent.innerHTML = `<p>Preview for ${formName} would appear here. This is a placeholder.</p>`;
        }
    }
}

// Rating modal logic (same as your JS, no Tailwind needed)
document.addEventListener('DOMContentLoaded', function () {
    const ratingModal = document.getElementById('ratingModal');
    const closeRatingModalBtn = document.getElementById('closeRatingModalBtn');
    const starsRatingContainer = document.getElementById('starsRatingContainer');
    const stars = starsRatingContainer ? starsRatingContainer.querySelectorAll('.star') : [];
    const selectedRatingInput = document.getElementById('selectedRating');
    const submitRatingBtn = document.getElementById('submitRatingBtn');
    const ratingMessageEl = document.getElementById('ratingMessage');
    let currentRating = 0;

    function showRatingModal() {
        if (ratingModal) {
            currentRating = 0;
            if(selectedRatingInput) selectedRatingInput.value = 0;
            if(submitRatingBtn) submitRatingBtn.disabled = true;
            stars.forEach(s => {
                s.classList.remove('selected');
                s.style.pointerEvents = 'auto';
            });
            if(ratingMessageEl) ratingMessageEl.style.display = 'none';
            ratingModal.classList.add('open');
        }
    }
    function hideRatingModal() {
        if (ratingModal) ratingModal.classList.remove('open');
    }
    if (closeRatingModalBtn) closeRatingModalBtn.addEventListener('click', hideRatingModal);
    if (ratingModal) ratingModal.addEventListener('click', function(event) {
        if (event.target === ratingModal) hideRatingModal();
    });
    if (stars.length > 0) {
        stars.forEach(star => {
            star.addEventListener('mouseover', function() {
                const hoverValue = parseInt(this.dataset.value);
                stars.forEach(s => {
                    s.classList.toggle('selected', parseInt(s.dataset.value) <= hoverValue);
                });
            });
            star.addEventListener('mouseout', function() {
                stars.forEach(s => {
                    s.classList.toggle('selected', parseInt(s.dataset.value) <= currentRating);
                });
            });
            star.addEventListener('click', function() {
                currentRating = parseInt(this.dataset.value);
                if(selectedRatingInput) selectedRatingInput.value = currentRating;
                if(submitRatingBtn) submitRatingBtn.disabled = false;
                stars.forEach(s => {
                    s.classList.toggle('selected', parseInt(s.dataset.value) <= currentRating);
                });
            });
        });
    }
    if (submitRatingBtn) {
        submitRatingBtn.addEventListener('click', function() {
            if (currentRating === 0) {
                if(ratingMessageEl) {
                    ratingMessageEl.textContent = 'Please select a rating first.';
                    ratingMessageEl.style.color = 'yellow';
                    ratingMessageEl.style.display = 'block';
                }
                return;
            }
            if(ratingMessageEl) {
                ratingMessageEl.textContent = `Thank you for rating us ${selectedRatingInput.value} stars!`;
                ratingMessageEl.style.color = 'lightgreen';
                ratingMessageEl.style.display = 'block';
            }
            submitRatingBtn.disabled = true;
            stars.forEach(star => star.style.pointerEvents = 'none');
            setTimeout(() => { hideRatingModal(); }, 2500);
        });
    }
    // Show modal randomly (1 in 3 chance)
    if (Math.random() < 0.33) { setTimeout(showRatingModal, 2000); }
});
