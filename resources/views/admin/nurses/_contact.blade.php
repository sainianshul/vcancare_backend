<div class="card shadow-none border border-gray-300 bg-body">
    <div class="card-header border-bottom border-gray-200 pt-5 pb-4">
        <h3 class="card-title fw-bold text-gray-900 fs-5">
            <i class="ki-outline ki-message-text-2 text-dark fs-3 me-2"></i> Contact Nurse
        </h3>
    </div>
    <div class="card-body pt-8">
        <form id="contact-nurse-form" action="{{ route('admin.nurses.contact', $user->id) }}" method="POST">
            @csrf
            
            <!-- Channel Selection -->
            <div class="mb-8">
                <label class="form-label fw-bold text-gray-900 fs-6 mb-3">Select Communication Channel</label>
                <div class="d-flex gap-5">
                    <label class="form-check form-check-custom form-check-solid form-check-primary">
                        <input class="form-check-input" type="radio" name="channel" value="email" checked />
                        <span class="form-check-label fw-semibold text-gray-700">Email</span>
                    </label>
                    <label class="form-check form-check-custom form-check-solid form-check-info">
                        <input class="form-check-input" type="radio" name="channel" value="sms" />
                        <span class="form-check-label fw-semibold text-gray-700">SMS Message</span>
                    </label>
                </div>
            </div>

            <!-- Subject (Only for Email) -->
            <div class="fv-row mb-8" id="subject-container">
                <label class="form-label required fw-bold text-gray-900 fs-6">Subject</label>
                <input type="text" name="subject" class="form-control border border-gray-300" placeholder="e.g. Action required on your profile" />
            </div>

            <!-- Message Body -->
            <div class="fv-row mb-8">
                <label class="form-label required fw-bold text-gray-900 fs-6">Message</label>
                <textarea name="message" class="form-control border border-gray-300" rows="6" placeholder="Type your message here..."></textarea>
                <div class="text-muted fs-8 mt-2" id="sms-counter" style="display: none;">
                    Characters: <span id="char-count">0</span>/160
                </div>
            </div>

            <div class="d-flex justify-content-end">
                <button type="submit" class="btn btn-primary fw-bold" id="contact-submit-btn">
                    <span class="indicator-label">
                        <i class="ki-outline ki-send fs-4 me-1"></i> Send Message
                    </span>
                    <span class="indicator-progress" style="display: none;">
                        Please wait... <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                    </span>
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    // Radio toggle logic
    $('input[name="channel"]').on('change', function() {
        if ($(this).val() === 'sms') {
            $('#subject-container').slideUp();
            $('#sms-counter').show();
        } else {
            $('#subject-container').slideDown();
            $('#sms-counter').hide();
        }
    });

    // SMS char counter logic
    $('textarea[name="message"]').on('input', function() {
        if ($('input[name="channel"]:checked').val() === 'sms') {
            let count = $(this).val().length;
            $('#char-count').text(count);
            if (count > 160) {
                $('#char-count').addClass('text-danger');
            } else {
                $('#char-count').removeClass('text-danger');
            }
        }
    });

    // Form submission logic
    $('#contact-nurse-form').on('submit', function(e) {
        e.preventDefault();
        
        let form = $(this);
        let btn = $('#contact-submit-btn');
        let label = btn.find('.indicator-label');
        let progress = btn.find('.indicator-progress');

        // Basic validation
        let channel = $('input[name="channel"]:checked').val();
        let subject = $('input[name="subject"]').val().trim();
        let message = $('textarea[name="message"]').val().trim();

        if (channel === 'email' && !subject) {
            Swal.fire('Validation Error', 'Subject is required for Email.', 'error');
            return;
        }
        if (!message) {
            Swal.fire('Validation Error', 'Message body is required.', 'error');
            return;
        }

        // Loading state
        btn.prop('disabled', true);
        label.hide();
        progress.show();

        $.ajax({
            url: form.attr('action'),
            type: 'POST',
            data: form.serialize(),
            success: function(response) {
                if (response.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Sent!',
                        text: response.message,
                        showConfirmButton: false,
                        timer: 1500
                    });
                    form.trigger('reset');
                    $('#subject-container').slideDown(); // Reset view to email default
                    $('#sms-counter').hide();
                    $('#char-count').text('0').removeClass('text-danger');
                } else {
                    Swal.fire('Error', response.message || 'Failed to send message.', 'error');
                }
            },
            error: function(xhr) {
                let msg = 'An error occurred.';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    msg = xhr.responseJSON.message;
                }
                Swal.fire('Error', msg, 'error');
            },
            complete: function() {
                // Restore state
                btn.prop('disabled', false);
                progress.hide();
                label.show();
            }
        });
    });
</script>
