document.addEventListener('DOMContentLoaded', function () {

    const form = document.getElementById('nexora-contact-form');
    const statusBox = document.getElementById('nexora-contact-status');
    const submitButton = document.getElementById('nexora-contact-submit');
    const submitText = document.querySelector('.nexora-submit-text');
    const submitLoading = document.querySelector('.nexora-submit-loading');

    const messageField = document.getElementById('contact_message');
    const messageCount = document.getElementById('nexora-message-count');


    /*
    |--------------------------------------------------------------------------
    | Character Counter
    |--------------------------------------------------------------------------
    */

    if (messageField && messageCount) {

        const updateCounter = function () {

            const length = messageField.value.length;

            messageCount.textContent = String(length);
        };

        messageField.addEventListener(
            'input',
            updateCounter
        );

        updateCounter();
    }


    /*
    |--------------------------------------------------------------------------
    | Contact Form
    |--------------------------------------------------------------------------
    */

    if (!form) {
        return;
    }


    form.addEventListener('submit', async function (event) {

        event.preventDefault();


        /*
        |--------------------------------------------------------------------------
        | Reset Status
        |--------------------------------------------------------------------------
        */

        statusBox.hidden = true;

        statusBox.textContent = '';

        statusBox.className =
            'nexora-contact-alert';


        /*
        |--------------------------------------------------------------------------
        | Browser Validation
        |--------------------------------------------------------------------------
        */

        if (!form.checkValidity()) {

            form.classList.add('was-validated');

            statusBox.textContent =
                'Please complete all required fields before transmitting your message.';

            statusBox.classList.add('is-error');

            statusBox.hidden = false;

            return;
        }


        /*
        |--------------------------------------------------------------------------
        | Disable Submission
        |--------------------------------------------------------------------------
        */

        submitButton.disabled = true;

        submitText.hidden = true;

        submitLoading.hidden = false;


        try {

            const response = await fetch(
                '/api/contact.php',
                {
                    method: 'POST',
                    body: new FormData(form),
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    }
                }
            );


            /*
            |--------------------------------------------------------------------------
            | Safely Parse JSON
            |--------------------------------------------------------------------------
            */

            let data;

            try {

                data = await response.json();

            } catch (parseError) {

                throw new Error(
                    'The communications server returned an invalid response.'
                );
            }


            /*
            |--------------------------------------------------------------------------
            | API Failure
            |--------------------------------------------------------------------------
            */

            if (!response.ok || !data.success) {

                throw new Error(
                    data.message ||
                    'Unable to transmit your message right now.'
                );
            }


            /*
            |--------------------------------------------------------------------------
            | Success
            |--------------------------------------------------------------------------
            */

            statusBox.textContent =
                data.message ||
                'Transmission received successfully.';

            statusBox.classList.add('is-success');

            statusBox.hidden = false;


            /*
            |--------------------------------------------------------------------------
            | Reset Form
            |--------------------------------------------------------------------------
            */

            form.reset();

            form.classList.remove('was-validated');


            if (messageCount) {

                messageCount.textContent = '0';
            }


            /*
            |--------------------------------------------------------------------------
            | Bring Confirmation Into View
            |--------------------------------------------------------------------------
            */

            statusBox.scrollIntoView({
                behavior: 'smooth',
                block: 'center'
            });


        } catch (error) {

            /*
            |--------------------------------------------------------------------------
            | Safe User-Facing Error
            |--------------------------------------------------------------------------
            */

            statusBox.textContent =
                error instanceof Error
                    ? error.message
                    : 'Unable to transmit your message right now.';

            statusBox.classList.add('is-error');

            statusBox.hidden = false;


        } finally {

            /*
            |--------------------------------------------------------------------------
            | Restore Button
            |--------------------------------------------------------------------------
            */

            submitButton.disabled = false;

            submitText.hidden = false;

            submitLoading.hidden = true;
        }

    });

});