'use strict';

document.addEventListener('DOMContentLoaded', () => {

    const form = document.getElementById('nexora-register-form');

    if (!form) {
        return;
    }

    const modeButtons = document.querySelectorAll(
        '[data-registration-mode]'
    );

    const registrationType = document.getElementById(
        'registration_type'
    );

    const professionalFields = document.querySelectorAll(
        '.professional-field'
    );

    const aliasInput = document.getElementById('alias');
    const aliasStatus = document.getElementById('alias-status');

    const passwordInput = document.getElementById('password');
    const passwordConfirmInput = document.getElementById(
        'password_confirm'
    );

    const passwordStrength = document.getElementById(
        'password-strength'
    );

    const passwordStrengthBar = passwordStrength
        ? passwordStrength.querySelector(
            '.password-strength-bar span'
        )
        : null;

    const passwordStrengthText = passwordStrength
        ? passwordStrength.querySelector(
            '.password-strength-text'
        )
        : null;

    const passwordMatch = document.getElementById(
        'password-match'
    );

    const avatarInput = document.getElementById('avatar');
    const avatarPreview = document.getElementById(
        'avatar-preview'
    );

    const removeAvatarButton = document.getElementById(
        'remove-avatar'
    );

    const responseBox = document.getElementById(
        'register-response'
    );

    const submitButton = document.getElementById(
        'register-submit'
    );

    const submitNormal = submitButton
        ? submitButton.querySelector(
            '.register-submit-normal'
        )
        : null;

    const submitLoading = submitButton
        ? submitButton.querySelector(
            '.register-submit-loading'
        )
        : null;


    let aliasTimer = null;
    let aliasCheckController = null;
    let avatarObjectUrl = null;


    /*
     * Registration mode
     */
    function setRegistrationMode(mode) {
    
        const isProfessional = mode === 'professional';
    
        registrationType.value = mode;
    
        modeButtons.forEach(button => {
    
            const active =
                button.dataset.registrationMode === mode;
    
            button.classList.toggle(
                'active',
                active
            );
    
            button.setAttribute(
                'aria-pressed',
                active ? 'true' : 'false'
            );
    
        });
    
        professionalFields.forEach(field => {
    
            field.hidden = !isProfessional;
    
            const input = field.querySelector('input');
    
            if (!input) {
                return;
            }
    
            if (input.id === 'email') {
                input.required = isProfessional;
            }
    
            if (input.id === 'first_name') {
                input.required = isProfessional;
            }
    
            if (input.id === 'last_name') {
                input.required = isProfessional;
            }
    
            if (!isProfessional) {
                input.value = '';
                input.required = false;
            }
        });


        const email = document.getElementById('email');

        if (email) {
            email.required = false;
        }

    }


    modeButtons.forEach(button => {

        button.addEventListener('click', () => {

            setRegistrationMode(
                button.dataset.registrationMode
            );

        });

    });


    /*
     * Password visibility
     */
    document.querySelectorAll(
        '[data-password-target]'
    ).forEach(button => {

        button.addEventListener('click', () => {

            const targetId =
                button.dataset.passwordTarget;

            const input =
                document.getElementById(targetId);

            if (!input) {
                return;
            }

            const icon =
                button.querySelector('i');

            if (input.type === 'password') {

                input.type = 'text';

                button.setAttribute(
                    'aria-label',
                    'Hide password'
                );

                if (icon) {
                    icon.className =
                        'fa-solid fa-eye-slash';
                }

            } else {

                input.type = 'password';

                button.setAttribute(
                    'aria-label',
                    'Show password'
                );

                if (icon) {
                    icon.className =
                        'fa-solid fa-eye';
                }

            }

        });

    });


    /*
     * Password strength
     */
    function calculatePasswordStrength(password) {

        if (!password) {
            return {
                score: 0,
                label: 'Enter a password'
            };
        }

        let score = 0;

        if (password.length >= 12) {
            score++;
        }

        if (password.length >= 16) {
            score++;
        }

        if (/[a-z]/.test(password)) {
            score++;
        }

        if (/[A-Z]/.test(password)) {
            score++;
        }

        if (/[0-9]/.test(password)) {
            score++;
        }

        if (/[^A-Za-z0-9]/.test(password)) {
            score++;
        }

        if (score <= 2) {
            return {
                score: 1,
                label: 'Weak'
            };
        }

        if (score <= 4) {
            return {
                score: 2,
                label: 'Moderate'
            };
        }

        if (score === 5) {
            return {
                score: 3,
                label: 'Strong'
            };
        }

        return {
            score: 4,
            label: 'Excellent'
        };
    }


    function updatePasswordStrength() {

        const result =
            calculatePasswordStrength(
                passwordInput.value
            );

        if (passwordStrengthBar) {

            passwordStrengthBar.style.width =
                `${result.score * 25}%`;

        }

        if (passwordStrengthText) {

            passwordStrengthText.textContent =
                result.label;

        }

        passwordStrength.classList.remove(
            'strength-weak',
            'strength-moderate',
            'strength-strong',
            'strength-excellent'
        );

        if (result.score === 1) {
            passwordStrength.classList.add(
                'strength-weak'
            );
        }

        if (result.score === 2) {
            passwordStrength.classList.add(
                'strength-moderate'
            );
        }

        if (result.score === 3) {
            passwordStrength.classList.add(
                'strength-strong'
            );
        }

        if (result.score === 4) {
            passwordStrength.classList.add(
                'strength-excellent'
            );
        }

    }


    function updatePasswordMatch() {

        if (!passwordConfirmInput.value) {

            passwordMatch.textContent = '';
            passwordMatch.className =
                'register-password-match';

            return;

        }

        if (
            passwordInput.value ===
            passwordConfirmInput.value
        ) {

            passwordMatch.innerHTML =
                '<i class="fa-solid fa-circle-check"></i> Passwords match.';

            passwordMatch.className =
                'register-password-match valid';

        } else {

            passwordMatch.innerHTML =
                '<i class="fa-solid fa-circle-xmark"></i> Passwords do not match.';

            passwordMatch.className =
                'register-password-match invalid';

        }

    }


    passwordInput.addEventListener(
        'input',
        updatePasswordStrength
    );

    passwordInput.addEventListener(
        'input',
        updatePasswordMatch
    );

    passwordConfirmInput.addEventListener(
        'input',
        updatePasswordMatch
    );


    /*
     * Alias validation / availability
     *
     * This endpoint is intentionally optional.
     * Registration itself remains the authoritative check.
     */
    function normalizeAlias(value) {

        return value
            .trim()
            .replace(/\s+/g, ' ');

    }


    async function checkAliasAvailability() {

        const alias =
            normalizeAlias(aliasInput.value);

        if (alias.length < 3) {

            aliasStatus.textContent = '';
            aliasStatus.className =
                'register-field-status';

            return;

        }

        if (aliasCheckController) {
            aliasCheckController.abort();
        }

        aliasCheckController =
            new AbortController();

        aliasStatus.innerHTML =
            '<i class="fa-solid fa-spinner fa-spin"></i> Checking…';

        aliasStatus.className =
            'register-field-status checking';

        try {

            const response = await fetch(
                `/api/check-username.php?alias=${encodeURIComponent(alias)}`,
                {
                    method: 'GET',
                    credentials: 'same-origin',
                    headers: {
                        'Accept': 'application/json'
                    },
                    signal:
                        aliasCheckController.signal
                }
            );

            if (!response.ok) {
                throw new Error(
                    'Availability request failed.'
                );
            }

            const data =
                await response.json();

            if (data.available) {

                aliasStatus.innerHTML =
                    '<i class="fa-solid fa-circle-check"></i> Available';

                aliasStatus.className =
                    'register-field-status available';

            } else {

                aliasStatus.innerHTML =
                    '<i class="fa-solid fa-circle-xmark"></i> Already in use';

                aliasStatus.className =
                    'register-field-status unavailable';

            }

        } catch (error) {

            if (
                error.name ===
                'AbortError'
            ) {
                return;
            }

            aliasStatus.textContent = '';

        }

    }


    aliasInput.addEventListener(
        'input',
        () => {

            clearTimeout(aliasTimer);

            aliasStatus.textContent = '';

            aliasTimer = setTimeout(
                checkAliasAvailability,
                450
            );

        }
    );


    /*
     * Avatar preview
     */
    avatarInput.addEventListener(
        'change',
        () => {

            const file =
                avatarInput.files[0];

            if (!file) {
                return;
            }

            if (!file.type.startsWith('image/')) {

                showResponse(
                    'error',
                    'Please select a valid image file.'
                );

                avatarInput.value = '';

                return;

            }

            if (file.size > 5 * 1024 * 1024) {

                showResponse(
                    'error',
                    'Avatar files must be 5 MB or smaller.'
                );

                avatarInput.value = '';

                return;

            }

            if (avatarObjectUrl) {
                URL.revokeObjectURL(
                    avatarObjectUrl
                );
            }

            avatarObjectUrl =
                URL.createObjectURL(file);

            avatarPreview.innerHTML = '';

            const image =
                document.createElement('img');

            image.src = avatarObjectUrl;
            image.alt = 'Avatar preview';

            avatarPreview.appendChild(image);

            removeAvatarButton.hidden = false;

        }
    );


    removeAvatarButton.addEventListener(
        'click',
        () => {

            avatarInput.value = '';

            if (avatarObjectUrl) {

                URL.revokeObjectURL(
                    avatarObjectUrl
                );

                avatarObjectUrl = null;

            }

            avatarPreview.innerHTML =
                '<i class="fa-solid fa-user"></i>';

            removeAvatarButton.hidden = true;

        }
    );


    /*
     * Response handling
     */
    function showResponse(type, message) {

        responseBox.hidden = false;

        responseBox.className =
            `register-response ${type}`;

        responseBox.innerHTML = '';

        const icon =
            document.createElement('i');

        icon.className =
            type === 'success'
                ? 'fa-solid fa-circle-check'
                : 'fa-solid fa-triangle-exclamation';

        const text =
            document.createElement('span');

        text.textContent = message;

        responseBox.appendChild(icon);
        responseBox.appendChild(text);

        responseBox.scrollIntoView({
            behavior: 'smooth',
            block: 'center'
        });

    }


    function clearResponse() {

        responseBox.hidden = true;
        responseBox.textContent = '';

    }


    function setLoading(loading) {

        submitButton.disabled = loading;

        submitNormal.hidden = loading;
        submitLoading.hidden = !loading;

    }


    /*
     * Client-side validation
     */
    function validateForm() {

        const alias =
            normalizeAlias(aliasInput.value);

        const password =
            passwordInput.value;

        const confirmation =
            passwordConfirmInput.value;

        if (alias.length < 3) {

            showResponse(
                'error',
                'Please choose an alias containing at least 3 characters.'
            );

            aliasInput.focus();

            return false;

        }

        if (alias.length > 80) {

            showResponse(
                'error',
                'Your alias cannot exceed 80 characters.'
            );

            aliasInput.focus();

            return false;

        }

        if (password.length < 12) {

            showResponse(
                'error',
                'Your password must contain at least 12 characters.'
            );

            passwordInput.focus();

            return false;

        }

        if (password.length > 128) {

            showResponse(
                'error',
                'Your password cannot exceed 128 characters.'
            );

            passwordInput.focus();

            return false;

        }

        if (password !== confirmation) {

            showResponse(
                'error',
                'Your passwords do not match.'
            );

            passwordConfirmInput.focus();

            return false;

        }

        const requiredCheckboxes =
            form.querySelectorAll(
                'input[type="checkbox"][required]'
            );

        for (const checkbox of requiredCheckboxes) {

            if (!checkbox.checked) {

                showResponse(
                    'error',
                    'Please acknowledge the required privacy and registration statements.'
                );

                checkbox.focus();

                return false;

            }

        }

        return true;

    }


    /*
     * Registration submission
     */
    form.addEventListener(
        'submit',
        async event => {

            event.preventDefault();

            clearResponse();

            if (!validateForm()) {
                return;
            }

            setLoading(true);

            try {

                const formData =
                    new FormData(form);

                const response =
                    await fetch(
                        '/api/register.php',
                        {
                            method: 'POST',
                            body: formData,
                            credentials: 'same-origin',
                            headers: {
                                'Accept':
                                    'application/json'
                            }
                        }
                    );

                const raw =
                    await response.text();

                let data = null;

                try {

                    data =
                        JSON.parse(raw);

                } catch (jsonError) {

                    console.error(
                        'Nexora registration returned non-JSON:',
                        raw
                    );

                    throw new Error(
                        'The registration service returned an unexpected response.'
                    );

                }

                if (
                    !response.ok ||
                    !data.success
                ) {

                    throw new Error(
                        data.message ||
                        'Registration could not be completed.'
                    );

                }

                showResponse(
                    'success',
                    data.message ||
                    'Your Nexora identity has been created.'
                );

                /*
                 * The server establishes the session.
                 * Redirect only after the successful response.
                 */
                window.setTimeout(
                    () => {

                        window.location.href =
                            data.redirect ||
                            '/users/dashboard.php';

                    },
                    850
                );

            } catch (error) {

                console.error(
                    'Nexora registration error:',
                    error
                );

                showResponse(
                    'error',
                    error.message ||
                    'Registration could not be completed. Please try again.'
                );

            } finally {

                setLoading(false);

            }

        }
    );

});