<x-guest-layout>
    <style>
        #popup-message {
            position: fixed;
            top: 20px;
            right: 20px;
            background-color: #4CAF50; 
            color: white;
            padding: 15px;
            border-radius: 5px;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.2);
            font-family: Arial, sans-serif;
            font-size: 14px;
            opacity: 0;
            transition: opacity 0.5s ease-in-out;
            z-index: 1000;
        }
        #popup-message.error {
            background-color: #F44336; 
        }
        #popup-message.show {
            opacity: 1;
        }
    </style>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <div class="bg-white shadow-md rounded-lg overflow-hidden flex items-center login" id="auth-container">
        <div class="md:block flex-1">
            <img src="{{ asset('bg.png') }}" alt="Illustration" class="w-full h-full object-cover">
        </div>
        <div id="popup-message" class=""></div>
        <div class="w-full h-full p-10 flex-1 flex-col justify-center relative" id="auth-forms">
            <img src="{{ asset('logo.png') }}" width="30%" class="absolute top-7" alt="Illustration" class="object-cover">
            <h1 class="mt-10 login-heading font-playfair">Ihr Passwort zurücksetzen</h2>
            <small class="font-rajdhani">Bitte geben Sie Ihre Daten ein, um Ihr Passwort zurückzusetzen</small>
            <form id="reset-password" class="mt-6 space-y-4" onsubmit="updatePassword(event)">
                <input type="hidden" id="reset-token" name="token" value="">
                <input type="hidden" id="reset-email" name="email" value="">

                <div class="relative">
                    <label for="new-password" class="block text-sm">Neues Passwort</label>
                    <input type="password" id="new-password" placeholder="Neues Passwort" required class="mt-1 block w-full px-4 py-2 border rounded-md font-rajdhani">

                    <svg id="show-icon-new-password" width="16" height="16" onclick="togglePasswordVisibility('new-password')" viewBox="0 0 16 16" class="show-eye cursor-pointer absolute right-3 top-9" fill="none" xmlns="http://www.w3.org/2000/svg" style="display: inline;">
                        <path d="M6.31334 10.1866C6.18668 10.1866 6.06001 10.1399 5.96001 10.0399C5.41334 9.49328 5.11334 8.76661 5.11334 7.99995C5.11334 6.40661 6.40668 5.11328 8.00001 5.11328C8.76668 5.11328 9.49334 5.41328 10.04 5.95995C10.1333 6.05328 10.1867 6.17995 10.1867 6.31328C10.1867 6.44661 10.1333 6.57328 10.04 6.66661L6.66668 10.0399C6.56668 10.1399 6.44001 10.1866 6.31334 10.1866ZM8.00001 6.11328C6.96001 6.11328 6.11334 6.95995 6.11334 7.99995C6.11334 8.33328 6.20001 8.65328 6.36001 8.93328L8.93334 6.35995C8.65334 6.19995 8.33334 6.11328 8.00001 6.11328Z" fill="#BE1622"/>
                        <path d="M3.73335 12.3397C3.62002 12.3397 3.50002 12.2997 3.40668 12.2197C2.69335 11.613 2.05335 10.8663 1.50668 9.99966C0.800018 8.89966 0.800018 7.10633 1.50668 5.99966C3.13335 3.45299 5.50002 1.98633 8.00002 1.98633C9.46668 1.98633 10.9134 2.49299 12.18 3.44633C12.4 3.61299 12.4467 3.92633 12.28 4.14633C12.1134 4.36633 11.8 4.41299 11.58 4.24633C10.4867 3.41966 9.24669 2.98633 8.00002 2.98633C5.84668 2.98633 3.78668 4.27966 2.34668 6.53966C1.84668 7.31966 1.84668 8.67966 2.34668 9.45966C2.84668 10.2397 3.42002 10.913 4.05335 11.4597C4.26002 11.6397 4.28668 11.953 4.10668 12.1663C4.01335 12.2797 3.87335 12.3397 3.73335 12.3397Z" fill="#BE1622"/>
                        <path d="M8.00002 14.0137C7.11335 14.0137 6.24669 13.8337 5.41335 13.4803C5.16002 13.3737 5.04002 13.0803 5.14669 12.827C5.25335 12.5737 5.54669 12.4537 5.80002 12.5603C6.50669 12.8603 7.24669 13.0137 7.99335 13.0137C10.1467 13.0137 12.2067 11.7203 13.6467 9.46032C14.1467 8.68032 14.1467 7.32032 13.6467 6.54032C13.44 6.21366 13.2134 5.90032 12.9734 5.60699C12.8 5.39366 12.8334 5.08032 13.0467 4.90032C13.26 4.72699 13.5734 4.75366 13.7534 4.97366C14.0134 5.29366 14.2667 5.64032 14.4934 6.00032C15.2 7.10032 15.2 8.89366 14.4934 10.0003C12.8667 12.547 10.5 14.0137 8.00002 14.0137Z" fill="#BE1622"/>
                        <path d="M8.45997 10.8469C8.22664 10.8469 8.01331 10.6802 7.96664 10.4402C7.91331 10.1669 8.09331 9.9069 8.36664 9.86023C9.09997 9.7269 9.71331 9.11356 9.84664 8.38023C9.89997 8.1069 10.16 7.93356 10.4333 7.98023C10.7066 8.03356 10.8866 8.29356 10.8333 8.5669C10.62 9.72023 9.69997 10.6336 8.55331 10.8469C8.51997 10.8402 8.49331 10.8469 8.45997 10.8469Z" fill="#BE1622"/>
                        <path d="M1.33336 15.1668C1.20669 15.1668 1.08002 15.1201 0.980022 15.0201C0.786689 14.8268 0.786689 14.5068 0.980022 14.3135L5.96002 9.33348C6.15336 9.14014 6.47336 9.14014 6.66669 9.33348C6.86002 9.52681 6.86002 9.84681 6.66669 10.0401L1.68669 15.0201C1.58669 15.1201 1.46002 15.1668 1.33336 15.1668Z" fill="#BE1622"/>
                        <path d="M9.68669 6.81329C9.56002 6.81329 9.43335 6.76663 9.33335 6.66663C9.14002 6.47329 9.14002 6.15329 9.33335 5.95996L14.3134 0.979961C14.5067 0.786628 14.8267 0.786628 15.02 0.979961C15.2134 1.17329 15.2134 1.49329 15.02 1.68663L10.04 6.66663C9.94002 6.76663 9.81335 6.81329 9.68669 6.81329Z" fill="#BE1622"/>
                    </svg>

                    <svg id="hide-icon-new-password" width="17" height="16" onclick="togglePasswordVisibility('new-password')" viewBox="0 0 17 16" class="hide-eye cursor-pointer absolute right-3 top-9" fill="none" xmlns="http://www.w3.org/2000/svg" style="display: none;">
                        <g clip-path="url(#clip0_151_9707)">
                        <path d="M1.49976 8.00033C1.49976 8.00033 4.16642 2.66699 8.83309 2.66699C13.4998 2.66699 16.1664 8.00033 16.1664 8.00033C16.1664 8.00033 13.4998 13.3337 8.83309 13.3337C4.16642 13.3337 1.49976 8.00033 1.49976 8.00033Z" stroke="#1E1E1E" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M8.83309 10.0003C9.93766 10.0003 10.8331 9.1049 10.8331 8.00033C10.8331 6.89576 9.93766 6.00033 8.83309 6.00033C7.72852 6.00033 6.83309 6.89576 6.83309 8.00033C6.83309 9.1049 7.72852 10.0003 8.83309 10.0003Z" stroke="#1E1E1E" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                        </g>
                        <defs>
                        <clipPath id="clip0_151_9707">
                        <rect width="16" height="16" fill="white" transform="translate(0.833252)"/>
                        </clipPath>
                        </defs>
                    </svg>
                </div>

                <div class="relative">
                    <label for="confirm-password" class="block text-sm">Neues Passwort bestätigen</label>
                    <input type="password" id="confirm-password" placeholder="Neues Passwort bestätigen" required class="mt-1 block w-full px-4 py-2 border rounded-md font-rajdhani">

                    <svg id="show-icon-confirm-password" width="16" height="16" onclick="togglePasswordVisibility('confirm-password')" viewBox="0 0 16 16" class="show-eye cursor-pointer absolute right-3 top-9" fill="none" xmlns="http://www.w3.org/2000/svg" style="display: inline;">
                        <path d="M6.31334 10.1866C6.18668 10.1866 6.06001 10.1399 5.96001 10.0399C5.41334 9.49328 5.11334 8.76661 5.11334 7.99995C5.11334 6.40661 6.40668 5.11328 8.00001 5.11328C8.76668 5.11328 9.49334 5.41328 10.04 5.95995C10.1333 6.05328 10.1867 6.17995 10.1867 6.31328C10.1867 6.44661 10.1333 6.57328 10.04 6.66661L6.66668 10.0399C6.56668 10.1399 6.44001 10.1866 6.31334 10.1866ZM8.00001 6.11328C6.96001 6.11328 6.11334 6.95995 6.11334 7.99995C6.11334 8.33328 6.20001 8.65328 6.36001 8.93328L8.93334 6.35995C8.65334 6.19995 8.33334 6.11328 8.00001 6.11328Z" fill="#BE1622"/>
                        <path d="M3.73335 12.3397C3.62002 12.3397 3.50002 12.2997 3.40668 12.2197C2.69335 11.613 2.05335 10.8663 1.50668 9.99966C0.800018 8.89966 0.800018 7.10633 1.50668 5.99966C3.13335 3.45299 5.50002 1.98633 8.00002 1.98633C9.46668 1.98633 10.9134 2.49299 12.18 3.44633C12.4 3.61299 12.4467 3.92633 12.28 4.14633C12.1134 4.36633 11.8 4.41299 11.58 4.24633C10.4867 3.41966 9.24669 2.98633 8.00002 2.98633C5.84668 2.98633 3.78668 4.27966 2.34668 6.53966C1.84668 7.31966 1.84668 8.67966 2.34668 9.45966C2.84668 10.2397 3.42002 10.913 4.05335 11.4597C4.26002 11.6397 4.28668 11.953 4.10668 12.1663C4.01335 12.2797 3.87335 12.3397 3.73335 12.3397Z" fill="#BE1622"/>
                        <path d="M8.00002 14.0137C7.11335 14.0137 6.24669 13.8337 5.41335 13.4803C5.16002 13.3737 5.04002 13.0803 5.14669 12.827C5.25335 12.5737 5.54669 12.4537 5.80002 12.5603C6.50669 12.8603 7.24669 13.0137 7.99335 13.0137C10.1467 13.0137 12.2067 11.7203 13.6467 9.46032C14.1467 8.68032 14.1467 7.32032 13.6467 6.54032C13.44 6.21366 13.2134 5.90032 12.9734 5.60699C12.8 5.39366 12.8334 5.08032 13.0467 4.90032C13.26 4.72699 13.5734 4.75366 13.7534 4.97366C14.0134 5.29366 14.2667 5.64032 14.4934 6.00032C15.2 7.10032 15.2 8.89366 14.4934 10.0003C12.8667 12.547 10.5 14.0137 8.00002 14.0137Z" fill="#BE1622"/>
                        <path d="M8.45997 10.8469C8.22664 10.8469 8.01331 10.6802 7.96664 10.4402C7.91331 10.1669 8.09331 9.9069 8.36664 9.86023C9.09997 9.7269 9.71331 9.11356 9.84664 8.38023C9.89997 8.1069 10.16 7.93356 10.4333 7.98023C10.7066 8.03356 10.8866 8.29356 10.8333 8.5669C10.62 9.72023 9.69997 10.6336 8.55331 10.8469C8.51997 10.8402 8.49331 10.8469 8.45997 10.8469Z" fill="#BE1622"/>
                        <path d="M1.33336 15.1668C1.20669 15.1668 1.08002 15.1201 0.980022 15.0201C0.786689 14.8268 0.786689 14.5068 0.980022 14.3135L5.96002 9.33348C6.15336 9.14014 6.47336 9.14014 6.66669 9.33348C6.86002 9.52681 6.86002 9.84681 6.66669 10.0401L1.68669 15.0201C1.58669 15.1201 1.46002 15.1668 1.33336 15.1668Z" fill="#BE1622"/>
                        <path d="M9.68669 6.81329C9.56002 6.81329 9.43335 6.76663 9.33335 6.66663C9.14002 6.47329 9.14002 6.15329 9.33335 5.95996L14.3134 0.979961C14.5067 0.786628 14.8267 0.786628 15.02 0.979961C15.2134 1.17329 15.2134 1.49329 15.02 1.68663L10.04 6.66663C9.94002 6.76663 9.81335 6.81329 9.68669 6.81329Z" fill="#BE1622"/>
                    </svg>

                    <svg id="hide-icon-confirm-password" width="17" height="16" onclick="togglePasswordVisibility('confirm-password')" viewBox="0 0 17 16" class="hide-eye cursor-pointer absolute right-3 top-9" fill="none" xmlns="http://www.w3.org/2000/svg" style="display: none;">
                        <g clip-path="url(#clip0_151_9707)">
                        <path d="M1.49976 8.00033C1.49976 8.00033 4.16642 2.66699 8.83309 2.66699C13.4998 2.66699 16.1664 8.00033 16.1664 8.00033C16.1664 8.00033 13.4998 13.3337 8.83309 13.3337C4.16642 13.3337 1.49976 8.00033 1.49976 8.00033Z" stroke="#1E1E1E" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M8.83309 10.0003C9.93766 10.0003 10.8331 9.1049 10.8331 8.00033C10.8331 6.89576 9.93766 6.00033 8.83309 6.00033C7.72852 6.00033 6.83309 6.89576 6.83309 8.00033C6.83309 9.1049 7.72852 10.0003 8.83309 10.0003Z" stroke="#1E1E1E" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                        </g>
                        <defs>
                        <clipPath id="clip0_151_9707">
                        <rect width="16" height="16" fill="white" transform="translate(0.833252)"/>
                        </clipPath>
                        </defs>
                    </svg>
                </div>

                <button type="submit" class="w-full bg-black text-white py-2 rounded-md font-rajdhani">Passwort aktualisieren</button>
            </form>
        </div>
    </div>
    <script>
        function showPopupMessage(message, type = 'success') {
            const popup = document.getElementById('popup-message');
            popup.textContent = message;
            popup.className = 'show'; 
            popup.classList.add(type === 'error' ? 'error' : '');
            setTimeout(() => {
                popup.classList.remove('show');
            }, 3000);
        }

        async function updatePassword(event) {
            event.preventDefault();
            const password = document.getElementById('new-password').value;
            const token = document.getElementById('reset-token').value;
            const email = document.getElementById('reset-email').value;
            const confirmPassword = document.getElementById('confirm-password').value;
            if (password !== confirmPassword) {
                showPopupMessage('Die Kennwörter stimmen nicht überein.','error');
                return;
            }
            try {
                await axios.post('/api/password/reset', { password, token, email, password_confirmation:confirmPassword });
                showPopupMessage('Passwort erfolgreich aktualisiert.','success');
            } catch (error) {
                showPopupMessage('Passwort erfolgreich aktualisiert.','error');
            }
        }

        function togglePasswordVisibility(inputId) {
            const passwordInput = document.getElementById(inputId);
            const showIcon = document.getElementById(`show-icon-${inputId}`);
            const hideIcon = document.getElementById(`hide-icon-${inputId}`);

            if (passwordInput.type === "password") {
                passwordInput.type = "text";
                showIcon.style.display = "none";  
                hideIcon.style.display = "inline"; 
            } else {
                passwordInput.type = "password";
                showIcon.style.display = "inline"; 
                hideIcon.style.display = "none";   
            }
        }

        document.addEventListener("DOMContentLoaded", function() {
            const pathParts = window.location.pathname.split('/');
            const token = pathParts[pathParts.length - 1]; 
            const urlParams = new URLSearchParams(window.location.search);
            const email = urlParams.get('email');
            
            if (token) {
                document.getElementById('reset-token').value = token;
            }
            if (email) {
                document.getElementById('reset-email').value = email;
            }
        });
    </script>
</x-guest-layout>
