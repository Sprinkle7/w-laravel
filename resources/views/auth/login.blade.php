<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Account Authentication</title>
    <link href="{{ mix('css/app.css') }}" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <style>
        .w-60 {
            width: 50%;
        }
        #popup-message {
            position: fixed;
            top: 20px;
            right: 20px;
            background-color: #4CAF50; /* Green background for success messages */
            color: white;
            padding: 15px;
            border-radius: 5px;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.2);
            font-family: Arial, sans-serif;
            font-size: 14px;
            opacity: 0; /* Start hidden */
            transition: opacity 0.5s ease-in-out;
            z-index: 1000;
        }
        #popup-message.error {
            background-color: #F44336; /* Red for error messages */
        }
        #popup-message.show {
            opacity: 1; /* Show message */
        }
        .loader {
            width: 25px;
            position: relative;
            float: right;
            right: 34%;
            --b: 8px;
            aspect-ratio: 1;
            border-radius: 50%;
            background: #e6e6e6;
            -webkit-mask: repeating-conic-gradient(#0000 0deg, #fff4f4 1deg 70deg, #0000 71deg 90deg), radial-gradient(farthest-side, #0000 calc(100% - var(--b) - 1px), #f0f0f0 calc(100% - var(--b)));
            -webkit-mask-composite: destination-in;
            mask-composite: intersect;
            animation: l5 1s infinite;
        }
        @keyframes l5 {to{transform: rotate(.5turn)}}
        #modalOverlay {
            display: none; /* Force it to be hidden initially */
            position: fixed;
            inset: 0;
            background-color: rgba(0, 0, 0, 0.5); /* Overlay background with opacity */
            z-index: 50;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px; /* Padding for small screens */
        }
        #modalContent {
            background-color: white;
            width: 100%;
            max-width: 400px;
            max-height: 70vh;
            overflow-y: auto;
            padding: 1.5rem;
            border-radius: 8px;
            box-shadow: 0px 4px 15px rgba(0, 0, 0, 0.2);
            position: relative;
        }
        #modalContent::-webkit-scrollbar {
            width: 8px; /* Scrollbar width */
        }
        #modalContent::-webkit-scrollbar-thumb {
            background-color: #be1622; /* #be1622 color for the scrollbar thumb */
            border-radius: 10px; /* Optional: round the corners */
        }
        #modalContent::-webkit-scrollbar-thumb:hover {
            background-color: #be1622; /* Darker red on hover */
        }
        .close-button {
            position: absolute;
            top: 16px; /* Adjust position as needed */
            right: 16px; /* Adjust position as needed */
            background-color: transparent; /* Transparent background */
            color: #be1622; /* #be1622 color for the "X" icon */
            border: 2px solid #be1622; /* #be1622 border */
            border-radius: 50%; /* Make it circular */
            width: 32px; /* Width of the button */
            height: 32px; /* Height of the button */
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
        }
        .close-button:hover {
            background-color: rgba(255, 0, 0, 0.1); /* Light red background on hover */
        }
        #modalContent {
            scrollbar-width: thin; /* Set width to thin */
            scrollbar-color: #be1622 transparent; /* Red thumb with a transparent track */
        }
    </style>    
</head>
<body class="bg-gray-100 flex items-center justify-center min-h-screen" style="background:url({{ asset('lines.png') }}); background-size:cover;">
    <div class="bg-white shadow-md rounded-lg overflow-hidden flex items-center login" id="auth-container">
        <div class="md:block flex-1">
            <img src="{{ asset('bg.png') }}" alt="Illustration" class="w-full h-full object-cover">
        </div>

        <div class="w-60 p-10 flex-1 flex-col justify-center" id="auth-forms">
            <div id="login-form">
                <img src="{{ asset('logo.png') }}" width="30%" alt="Illustration" class=" object-cover">
                <h1 class="mt-4 login-heading font-playfair">Anmeldung bei Ihrem Konto</h1>
                <small class="font-rajdhani">Bitte geben Sie Ihre genauen Daten ein, um sich anzumelden</small>
                <form method="POST" action="{{ route('login') }}" class="mt-6 space-y-4">
                    @csrf
                    <div>
                        <label for="email" class="block text-sm font-medium font-rajdhani">E-Mail</label>
                        <input type="email" id="email" name="email" placeholder="E-Mail" required class="mt-1 block w-full px-4 py-2 border rounded-md font-rajdhani">
                    </div>
                    <div class="relative">
                        <label for="password" class="block text-sm font-medium font-rajdhani">Passwort</label>

                        <svg id="show-icon-password" width="16" height="16" onclick="togglePasswordVisibility('password')" viewBox="0 0 16 16" class="show-eye cursor-pointer absolute right-3 top-9" fill="none" xmlns="http://www.w3.org/2000/svg" style="display: inline;">
                            <path d="M6.31334 10.1866C6.18668 10.1866 6.06001 10.1399 5.96001 10.0399C5.41334 9.49328 5.11334 8.76661 5.11334 7.99995C5.11334 6.40661 6.40668 5.11328 8.00001 5.11328C8.76668 5.11328 9.49334 5.41328 10.04 5.95995C10.1333 6.05328 10.1867 6.17995 10.1867 6.31328C10.1867 6.44661 10.1333 6.57328 10.04 6.66661L6.66668 10.0399C6.56668 10.1399 6.44001 10.1866 6.31334 10.1866ZM8.00001 6.11328C6.96001 6.11328 6.11334 6.95995 6.11334 7.99995C6.11334 8.33328 6.20001 8.65328 6.36001 8.93328L8.93334 6.35995C8.65334 6.19995 8.33334 6.11328 8.00001 6.11328Z" fill="#BE1622"/>
                            <path d="M3.73335 12.3397C3.62002 12.3397 3.50002 12.2997 3.40668 12.2197C2.69335 11.613 2.05335 10.8663 1.50668 9.99966C0.800018 8.89966 0.800018 7.10633 1.50668 5.99966C3.13335 3.45299 5.50002 1.98633 8.00002 1.98633C9.46668 1.98633 10.9134 2.49299 12.18 3.44633C12.4 3.61299 12.4467 3.92633 12.28 4.14633C12.1134 4.36633 11.8 4.41299 11.58 4.24633C10.4867 3.41966 9.24669 2.98633 8.00002 2.98633C5.84668 2.98633 3.78668 4.27966 2.34668 6.53966C1.84668 7.31966 1.84668 8.67966 2.34668 9.45966C2.84668 10.2397 3.42002 10.913 4.05335 11.4597C4.26002 11.6397 4.28668 11.953 4.10668 12.1663C4.01335 12.2797 3.87335 12.3397 3.73335 12.3397Z" fill="#BE1622"/>
                            <path d="M8.00002 14.0137C7.11335 14.0137 6.24669 13.8337 5.41335 13.4803C5.16002 13.3737 5.04002 13.0803 5.14669 12.827C5.25335 12.5737 5.54669 12.4537 5.80002 12.5603C6.50669 12.8603 7.24669 13.0137 7.99335 13.0137C10.1467 13.0137 12.2067 11.7203 13.6467 9.46032C14.1467 8.68032 14.1467 7.32032 13.6467 6.54032C13.44 6.21366 13.2134 5.90032 12.9734 5.60699C12.8 5.39366 12.8334 5.08032 13.0467 4.90032C13.26 4.72699 13.5734 4.75366 13.7534 4.97366C14.0134 5.29366 14.2667 5.64032 14.4934 6.00032C15.2 7.10032 15.2 8.89366 14.4934 10.0003C12.8667 12.547 10.5 14.0137 8.00002 14.0137Z" fill="#BE1622"/>
                            <path d="M8.45997 10.8469C8.22664 10.8469 8.01331 10.6802 7.96664 10.4402C7.91331 10.1669 8.09331 9.9069 8.36664 9.86023C9.09997 9.7269 9.71331 9.11356 9.84664 8.38023C9.89997 8.1069 10.16 7.93356 10.4333 7.98023C10.7066 8.03356 10.8866 8.29356 10.8333 8.5669C10.62 9.72023 9.69997 10.6336 8.55331 10.8469C8.51997 10.8402 8.49331 10.8469 8.45997 10.8469Z" fill="#BE1622"/>
                            <path d="M1.33336 15.1668C1.20669 15.1668 1.08002 15.1201 0.980022 15.0201C0.786689 14.8268 0.786689 14.5068 0.980022 14.3135L5.96002 9.33348C6.15336 9.14014 6.47336 9.14014 6.66669 9.33348C6.86002 9.52681 6.86002 9.84681 6.66669 10.0401L1.68669 15.0201C1.58669 15.1201 1.46002 15.1668 1.33336 15.1668Z" fill="#BE1622"/>
                            <path d="M9.68669 6.81329C9.56002 6.81329 9.43335 6.76663 9.33335 6.66663C9.14002 6.47329 9.14002 6.15329 9.33335 5.95996L14.3134 0.979961C14.5067 0.786628 14.8267 0.786628 15.02 0.979961C15.2134 1.17329 15.2134 1.49329 15.02 1.68663L10.04 6.66663C9.94002 6.76663 9.81335 6.81329 9.68669 6.81329Z" fill="#BE1622"/>
                        </svg>

                        <svg id="hide-icon-password" width="17" height="16" onclick="togglePasswordVisibility('password')" viewBox="0 0 17 16" class="hide-eye cursor-pointer absolute right-3 top-9" fill="none" xmlns="http://www.w3.org/2000/svg" style="display: none;">
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

                        <input type="password" id="password" placeholder="Passwort" name="password" required class="mt-1 block w-full px-4 py-2 border rounded-md">
                    </div>
                    <div class="text-right">
                        <a href="javascript:void(0);" onclick="showForgotPassword()" class="text-sm text-gray-600 hover:text-gray-900 font-semibold">Passwort vergessen?</a>
                    </div>
                    <button type="submit" class="w-full bg-black text-white py-2 rounded-md font-rajdhani">Anmelden</button>
                </form>
            </div>

            <div id="forgot-password-form" style="display: none;">
                <img src="{{ asset('logo.png') }}" width="30%" alt="Illustration" class=" object-cover">
                <h1 class="mt-4 login-heading font-playfair">Passwort vergessen</h2>
                <small class="font-rajdhani">Bitte geben Sie Ihre genauen Daten ein, um sich anzumelden</small>
                <form id="forgot-password" class="mt-6 space-y-4" onsubmit="sendResetLink(event)">
                    <div>
                        <label for="reset-email" class="block text-sm font-medium font-rajdhani">E-Mail</label>
                        <input type="email" id="reset-email" placeholder="E-Mail" required class="mt-1 block w-full px-4 py-2 border rounded-md font-rajdhani font-rajdhani">
                    </div>
                    <button type="submit" class="w-full bg-black text-white py-2 rounded-md font-rajdhani font-rajdhani">Senden <div id="loader-message" class="loader hidden"></div></button>
                </form>
                <p class="mt-4 text-center font-rajdhani">
                    Sie haben bereits ein Konto? <a href="javascript:void(0);" onclick="showLogin()" class="font-semibold text-gray-800 font-rajdhani">Anmeldung</a>
                </p>
            </div>

            <div id="show-password-form" class="text-center" style="display: none;">
                <svg width="198" height="180" viewBox="0 0 198 180" class="mx-auto" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M41.5349 0.386823L7.63887 13.5257C7.35193 13.6369 7.10549 13.8325 6.93213 14.0867C6.75877 14.341 6.66666 14.6418 6.66798 14.9496C6.6693 15.2573 6.76398 15.5574 6.93951 15.8101C7.11504 16.0628 7.36315 16.2564 7.65103 16.3651L25.4654 23.1017L36.3334 38.7425C36.5092 38.9943 36.757 39.1869 37.0443 39.2951C37.3317 39.4032 37.645 39.4219 37.9431 39.3485C38.2413 39.2751 38.5102 39.1132 38.7145 38.884C38.9188 38.6548 39.0489 38.3691 39.0877 38.0646L43.5899 1.99194C43.6222 1.73132 43.5864 1.46677 43.486 1.2241C43.3856 0.981428 43.224 0.768908 43.0171 0.607253C42.8101 0.445597 42.5648 0.340323 42.305 0.301692C42.0453 0.263061 41.7799 0.292391 41.5349 0.386823ZM190.23 33.3404C190.23 16.9731 171.121 8.34858 158.806 18.6572C155.082 18.0188 159.222 18.4566 70.9408 18.3228C62.8392 18.3228 55.2331 25.1203 55.2331 34.1278V44.4577C55.2331 44.8608 55.3933 45.2474 55.6783 45.5325C55.9634 45.8176 56.35 45.9777 56.7531 45.9777H83.6176V55.9854L54.996 80.2598C54.9838 80.2659 54.9656 80.2628 54.9504 80.2719C54.7196 80.4048 54.5278 80.5962 54.3944 80.8267C54.261 81.0573 54.1906 81.3189 54.1904 81.5852L54.1782 97.8279C54.0163 97.4857 53.732 97.2166 53.3814 97.0737C53.0308 96.9307 52.6394 96.9244 52.2843 97.0558C50.3874 97.7763 48.5026 98.3447 46.6816 98.746C44.9123 99.1321 45.2011 101.75 47.0069 101.75C47.9402 101.75 51.8526 100.473 53.3635 99.8951C53.7205 99.7573 54.0118 99.4889 54.1782 99.1443L54.1357 147.918H32.9712C30.9555 147.918 29.0225 148.719 27.5972 150.144C26.1719 151.569 25.3712 153.502 25.3712 155.518V172.116C25.3712 176.312 28.776 179.716 32.9712 179.716H87.6304C91.8256 179.716 95.2304 176.312 95.2304 172.116V171.314L195.991 172.393H195.997C196.94 172.393 198.268 171.439 197.526 170.058C198.104 75.7636 197.821 81.8983 197.152 81.3177L176.277 63.2875V51.7567C184.324 49.4828 190.23 42.0895 190.23 33.3404ZM164.755 32.0028L167.826 35.3164L177.523 26.0444C178.496 25.102 180.046 25.1324 180.989 26.1356C181.931 27.1084 181.901 28.6588 180.928 29.6012L169.437 40.606C169.201 40.834 168.921 41.0127 168.615 41.1317C168.309 41.2507 167.983 41.3076 167.654 41.2991C167.326 41.2906 167.003 41.2169 166.704 41.0822C166.404 40.9476 166.135 40.7547 165.91 40.5148L161.138 35.3468C160.226 34.3436 160.286 32.7932 161.259 31.8508C162.262 30.9388 163.843 30.9996 164.755 32.0028ZM57.2213 96.9007C57.732 97.53 58.4433 97.6577 59.0605 97.3355C60.8991 96.3853 62.6973 95.3589 64.4504 94.259C64.793 94.0453 65.0368 93.7043 65.128 93.3109C65.2192 92.9175 65.1504 92.504 64.9368 92.1614C64.7231 91.8187 64.3821 91.575 63.9887 91.4838C63.5953 91.3925 63.1818 91.4613 62.8392 91.675C61.0334 92.8059 59.3432 93.7726 57.6712 94.6329C57.5005 94.7225 57.3488 94.8444 57.2243 94.9916L57.2334 84.3425L112.045 119.22L78.4678 147.921H57.1787L57.2213 96.9007ZM194.644 85.8564L194.499 167.186L140.061 119.357L194.644 85.8564ZM192.176 169.192L95.2365 168.128V155.521C95.2365 151.417 91.8955 147.921 87.6365 147.921H83.1494L126.141 111.174L192.176 169.192ZM40.5286 157.771H60.4802C60.8833 157.771 61.2699 157.931 61.555 158.216C61.84 158.501 62.0002 158.888 62.0002 159.291C62.0002 159.694 61.84 160.08 61.555 160.365C61.2699 160.651 60.8833 160.811 60.4802 160.811H40.5286C40.1255 160.811 39.7389 160.651 39.4538 160.365C39.1688 160.08 39.0086 159.694 39.0086 159.291C39.0086 158.888 39.1688 158.501 39.4538 158.216C39.7389 157.931 40.1255 157.771 40.5286 157.771ZM48.153 170.052H39.717C39.3138 170.052 38.9272 169.892 38.6422 169.607C38.3571 169.322 38.197 168.935 38.197 168.532C38.197 168.129 38.3571 167.743 38.6422 167.457C38.9272 167.172 39.3138 167.012 39.717 167.012H48.153C48.5561 167.012 48.9427 167.172 49.2278 167.457C49.5128 167.743 49.673 168.129 49.673 168.532C49.673 168.935 49.5128 169.322 49.2278 169.607C48.9427 169.892 48.5561 170.052 48.153 170.052ZM73.2998 169.566H54.4762C54.073 169.566 53.6864 169.406 53.4013 169.121C53.1163 168.836 52.9562 168.449 52.9562 168.046C52.9562 167.643 53.1163 167.256 53.4013 166.971C53.6864 166.686 54.073 166.526 54.4762 166.526H73.2998C73.703 166.526 74.0896 166.686 74.3746 166.971C74.6597 167.256 74.8198 167.643 74.8198 168.046C74.8198 168.449 74.6597 168.836 74.3746 169.121C74.0896 169.406 73.703 169.566 73.2998 169.566ZM80.912 160.646H67.7761C67.373 160.646 66.9864 160.486 66.7013 160.201C66.4163 159.916 66.2561 159.53 66.2561 159.126C66.2561 158.723 66.4163 158.337 66.7013 158.052C66.9864 157.767 67.373 157.607 67.7761 157.607H80.912C81.3151 157.607 81.7017 157.767 81.9868 158.052C82.2718 158.337 82.432 158.723 82.432 159.126C82.432 159.53 82.2718 159.916 81.9868 160.201C81.7017 160.486 81.3151 160.646 80.912 160.646ZM173.243 95.4233L137.669 117.254L127.15 108.015C126.874 107.776 126.522 107.644 126.156 107.642C125.791 107.64 125.437 107.769 125.159 108.006L114.458 117.15L86.6576 99.4574V34.067C86.5846 33.69 87.1197 26.3606 80.2736 21.3659C104.046 21.375 154.486 21.3263 156.122 21.4115C145.579 34.602 156.198 54.2799 173.237 52.3617L173.243 95.4233Z" fill="black"/>
                    <path d="M126.208 59.1987H156.03C156.433 59.1987 156.82 59.0385 157.105 58.7535C157.39 58.4684 157.55 58.0818 157.55 57.6787C157.55 57.2755 157.39 56.8889 157.105 56.6039C156.82 56.3188 156.433 56.1587 156.03 56.1587H126.208C125.805 56.1587 125.418 56.3188 125.133 56.6039C124.848 56.8889 124.688 57.2755 124.688 57.6787C124.688 58.0818 124.848 58.4684 125.133 58.7535C125.418 59.0385 125.805 59.1987 126.208 59.1987ZM99.5836 69.5286H129.406C129.809 69.5286 130.196 69.3684 130.481 69.0834C130.766 68.7983 130.926 68.4117 130.926 68.0086C130.926 67.6055 130.766 67.2188 130.481 66.9338C130.196 66.6487 129.809 66.4886 129.406 66.4886H99.5836C99.1805 66.4886 98.7939 66.6487 98.5089 66.9338C98.2238 67.2188 98.0637 67.6055 98.0637 68.0086C98.0637 68.4117 98.2238 68.7983 98.5089 69.0834C98.7939 69.3684 99.1805 69.5286 99.5836 69.5286ZM134.398 66.4886C133.995 66.4886 133.608 66.6487 133.323 66.9338C133.038 67.2188 132.878 67.6055 132.878 68.0086C132.878 68.4117 133.038 68.7983 133.323 69.0834C133.608 69.3684 133.995 69.5286 134.398 69.5286H150.735C151.138 69.5286 151.524 69.3684 151.809 69.0834C152.095 68.7983 152.255 68.4117 152.255 68.0086C152.255 67.6055 152.095 67.2188 151.809 66.9338C151.524 66.6487 151.138 66.4886 150.735 66.4886H134.398ZM99.7904 59.1987H118.365C118.768 59.1987 119.155 59.0385 119.44 58.7535C119.725 58.4684 119.885 58.0818 119.885 57.6787C119.885 57.2755 119.725 56.8889 119.44 56.6039C119.155 56.3188 118.768 56.1587 118.365 56.1587H99.7904C99.3872 56.1587 99.0006 56.3188 98.7156 56.6039C98.4305 56.8889 98.2704 57.2755 98.2704 57.6787C98.2704 58.0818 98.4305 58.4684 98.7156 58.7535C99.0006 59.0385 99.3872 59.1987 99.7904 59.1987ZM156.237 79.3812H126.415C126.012 79.3812 125.625 79.5414 125.34 79.8264C125.055 80.1115 124.895 80.4981 124.895 80.9012C124.895 81.3044 125.055 81.691 125.34 81.976C125.625 82.2611 126.012 82.4212 126.415 82.4212H156.237C156.64 82.4212 157.027 82.2611 157.312 81.976C157.597 81.691 157.757 81.3044 157.757 80.9012C157.757 80.4981 157.597 80.1115 157.312 79.8264C157.027 79.5414 156.64 79.3812 156.237 79.3812ZM129.613 89.7112H99.7904C99.3872 89.7112 99.0006 89.8713 98.7156 90.1563C98.4305 90.4414 98.2704 90.828 98.2704 91.2311C98.2704 91.6343 98.4305 92.0209 98.7156 92.306C99.0006 92.591 99.3872 92.7512 99.7904 92.7512H129.613C130.016 92.7512 130.403 92.591 130.688 92.306C130.973 92.0209 131.133 91.6343 131.133 91.2311C131.133 90.828 130.973 90.4414 130.688 90.1563C130.403 89.8713 130.016 89.7112 129.613 89.7112ZM150.941 89.7112H134.604C134.201 89.7112 133.815 89.8713 133.53 90.1563C133.245 90.4414 133.084 90.828 133.084 91.2311C133.084 91.6343 133.245 92.0209 133.53 92.306C133.815 92.591 134.201 92.7512 134.604 92.7512H150.941C151.345 92.7512 151.731 92.591 152.016 92.306C152.301 92.0209 152.461 91.6343 152.461 91.2311C152.461 90.828 152.301 90.4414 152.016 90.1563C151.731 89.8713 151.345 89.7112 150.941 89.7112ZM99.994 79.3812C99.5909 79.3812 99.2043 79.5414 98.9192 79.8264C98.6342 80.1115 98.4741 80.4981 98.4741 80.9012C98.4741 81.3044 98.6342 81.691 98.9192 81.976C99.2043 82.2611 99.5909 82.4212 99.994 82.4212H118.568C118.972 82.4212 119.358 82.2611 119.643 81.976C119.928 81.691 120.088 81.3044 120.088 80.9012C120.088 80.4981 119.928 80.1115 119.643 79.8264C119.358 79.5414 118.972 79.3812 118.568 79.3812H99.994ZM28.5145 100.108C28.7182 100.199 28.931 100.245 29.1408 100.245C30.7732 100.245 31.2505 98.0073 29.767 97.3385C28.0477 96.5688 26.4484 95.5552 25.0185 94.3289C24.7134 94.0653 24.316 93.9336 23.9137 93.963C23.5115 93.9924 23.1374 94.1803 22.8738 94.4855C22.6102 94.7906 22.4785 95.188 22.5079 95.5903C22.5373 95.9925 22.7252 96.3665 23.0304 96.6302C24.6355 98.0164 26.4777 99.1868 28.5145 100.108ZM22.6868 25.178C22.3908 24.905 21.9985 24.7606 21.5961 24.7766C21.1937 24.7925 20.8141 24.9675 20.5406 25.2632C19.1361 26.7832 17.7833 28.3731 16.5217 29.9873C16.3462 30.2118 16.2373 30.481 16.2075 30.7644C16.1776 31.0477 16.228 31.3337 16.3529 31.5898C16.4777 31.8459 16.6721 32.0618 16.9137 32.2127C17.1554 32.3637 17.4346 32.4437 17.7195 32.4436C18.9324 32.4436 18.6771 31.7627 22.775 27.3273C22.9105 27.1805 23.0156 27.0084 23.0845 26.8208C23.1534 26.6333 23.1847 26.434 23.1765 26.2344C23.1683 26.0348 23.1208 25.8387 23.0368 25.6575C22.9528 25.4762 22.8339 25.3133 22.6868 25.178ZM34.1294 78.7124C33.7976 78.4867 33.3899 78.4015 32.9954 78.4752C32.6009 78.5489 32.2516 78.7756 32.0236 79.1059C31.7957 79.4362 31.7076 79.8433 31.7786 80.2383C31.8496 80.6333 32.074 80.9841 32.4027 81.2144C36.0628 83.7376 35.4123 86.0054 37.2424 86.0054C37.5027 86.0054 37.7587 85.9385 37.9858 85.8112C38.2129 85.6838 38.4035 85.5003 38.5393 85.2781C38.6751 85.056 38.7515 84.8027 38.7613 84.5425C38.7711 84.2823 38.7139 84.024 38.5952 83.7923C37.5467 81.7577 36.0129 80.0129 34.1294 78.7124ZM2.69884 57.5115C2.15734 59.5065 1.69691 61.5227 1.31868 63.555C1.24451 63.9513 1.33079 64.3608 1.55855 64.6934C1.78631 65.0261 2.13689 65.2547 2.53316 65.3288C2.92944 65.403 3.33895 65.3167 3.67161 65.089C4.00427 64.8612 4.23282 64.5106 4.307 64.1144C4.6718 62.1627 5.11564 60.211 5.63244 58.308C5.73285 57.9206 5.6765 57.5094 5.47561 57.1633C5.27471 56.8173 4.94549 56.5644 4.55935 56.4596C4.17321 56.3548 3.76131 56.4064 3.41301 56.6033C3.0647 56.8002 2.80809 57.1266 2.69884 57.5115ZM5.9942 53.6355C6.30658 53.6353 6.61134 53.539 6.86699 53.3595C7.12265 53.18 7.31679 52.926 7.423 52.6323C8.10092 50.7627 8.8518 48.9052 9.66652 47.1116C9.75768 46.9288 9.81104 46.7295 9.82344 46.5256C9.83583 46.3217 9.807 46.1174 9.73867 45.9249C9.67033 45.7324 9.56389 45.5556 9.42571 45.4052C9.28752 45.2547 9.12043 45.1337 8.93442 45.0493C8.7484 44.9648 8.54728 44.9188 8.34306 44.9138C8.13884 44.9089 7.93572 44.9451 7.74584 45.0205C7.55595 45.0958 7.38319 45.2086 7.23788 45.3522C7.09258 45.4957 6.9777 45.6671 6.90012 45.8561C6.055 47.7227 5.26764 49.6561 4.56236 51.5987C4.4793 51.8284 4.45284 52.0748 4.48521 52.3169C4.51758 52.559 4.60783 52.7898 4.74832 52.9896C4.88881 53.1895 5.07539 53.3525 5.29227 53.4649C5.50914 53.5774 5.74992 53.6359 5.9942 53.6355Z" fill="black"/>
                    <path d="M26.718 101.564C24.9548 101.953 25.2375 104.568 27.0463 104.568C28.6423 104.568 33.0351 102.464 33.7647 101.503C33.9913 101.776 34.3066 101.96 34.6554 102.023C38.1089 102.668 40.7202 102.504 41.0273 102.516C41.4139 102.486 41.7747 102.311 42.0363 102.025C42.2979 101.739 42.4405 101.363 42.4351 100.976C42.4297 100.588 42.2766 100.217 42.0071 99.9383C41.7376 99.6596 41.372 99.4942 40.9847 99.4757H40.9421L40.1517 99.4879C38.4797 99.4879 36.8199 99.3359 35.2148 99.0319C34.9374 98.9839 34.6523 99.0123 34.3899 99.114C34.1274 99.2158 33.8977 99.3871 33.7252 99.6095C33.4771 99.3128 33.1254 99.1217 32.7415 99.0748C32.3575 99.0279 31.9702 99.1289 31.658 99.3572C30.1866 100.435 28.5026 101.187 26.718 101.564ZM36.9233 90.2615C36.7776 92.0561 36.2211 93.7931 35.2969 95.3383C35.1115 95.6843 35.0672 96.0885 35.1732 96.4665C35.2792 96.8444 35.5272 97.1667 35.8655 97.3658C36.2037 97.565 36.6058 97.6256 36.9877 97.535C37.3696 97.4443 37.7016 97.2095 37.9143 96.8796C39.0969 94.8732 39.8021 92.657 39.9572 90.4743C39.9712 90.275 39.9459 90.075 39.8826 89.8856C39.8193 89.6961 39.7193 89.521 39.5883 89.3703C39.4573 89.2195 39.2979 89.096 39.1192 89.0069C38.9404 88.9178 38.7459 88.8648 38.5466 88.8509C38.1467 88.8327 37.7551 88.9691 37.4529 89.2316C37.1507 89.4942 36.9611 89.8629 36.9233 90.2615ZM19.121 83.4032C18.7211 83.369 18.3236 83.4922 18.013 83.7465C17.7025 84.0008 17.5034 84.3663 17.4581 84.7652C17.2453 87.0999 17.8321 89.3951 18.8869 91.4653C19.0814 91.8028 19.398 92.0527 19.7713 92.1636C20.1447 92.2745 20.5464 92.2378 20.8935 92.0611C21.2406 91.8844 21.5066 91.5813 21.6367 91.2141C21.7668 90.847 21.751 90.444 21.5925 90.0882C20.8173 88.556 20.3066 86.7472 20.486 85.06C20.5053 84.8612 20.4852 84.6605 20.4268 84.4695C20.3685 84.2784 20.2731 84.1007 20.1461 83.9465C20.019 83.7924 19.8628 83.6648 19.6864 83.571C19.51 83.4773 19.3168 83.4192 19.118 83.4002L19.121 83.4032ZM27.4141 79.5424C28.4781 79.5668 29.0861 78.8615 29.1135 78.0711C29.1258 77.6684 28.9777 77.2773 28.7018 76.9838C28.4259 76.6902 28.0448 76.5182 27.6421 76.5055C25.2537 76.4268 22.9081 77.1527 20.9815 78.5666C19.805 79.4421 20.4404 81.3026 21.8905 81.3026C22.218 81.3033 22.537 81.1977 22.7994 81.0016C23.9911 80.114 25.5689 79.5424 27.4141 79.5424ZM1.68359 77.4448H1.70183C1.9017 77.4425 2.09914 77.4007 2.28285 77.3219C2.46655 77.2431 2.63291 77.1289 2.77241 76.9857C2.9119 76.8425 3.02179 76.6733 3.09578 76.4876C3.16976 76.3019 3.2064 76.1034 3.20359 75.9036C3.20967 75.5935 3.12455 73.432 3.46503 70.0029C3.50453 69.6014 3.38292 69.2006 3.12694 68.8888C2.87096 68.5769 2.50158 68.3795 2.10007 68.34C1.69855 68.3005 1.29779 68.4221 0.985936 68.6781C0.674085 68.9341 0.476695 69.3035 0.437188 69.705C0.0784682 73.3925 0.169668 75.6178 0.163588 75.9431C0.168388 76.343 0.330646 76.725 0.615183 77.0061C0.899719 77.2872 1.2836 77.4449 1.68359 77.4448ZM14.5489 102.817C17.3669 104.194 20.3613 104.759 21.0058 104.759C21.39 104.758 21.7594 104.612 22.0399 104.349C22.3203 104.087 22.491 103.728 22.5176 103.344C22.5441 102.961 22.4246 102.582 22.1831 102.283C21.9416 101.985 21.5959 101.789 21.2156 101.734C19.3625 101.469 17.5629 100.913 15.8834 100.087C15.5214 99.9094 15.1038 99.8831 14.7224 100.014C14.341 100.144 14.027 100.421 13.8497 100.783C13.6723 101.145 13.646 101.563 13.7765 101.944C13.9071 102.325 14.1838 102.639 14.5458 102.817H14.5489ZM1.95719 80.4757C1.55979 80.542 1.20496 80.7634 0.970677 81.0911C0.736395 81.4189 0.641824 81.8263 0.707747 82.2237C1.04215 84.2727 1.56503 86.352 2.30071 88.3736C2.43858 88.7526 2.72133 89.0612 3.08678 89.2317C3.45222 89.4022 3.87041 89.4205 4.24935 89.2826C4.62829 89.1447 4.93694 88.862 5.1074 88.4965C5.27787 88.1311 5.29618 87.7129 5.15831 87.334C4.50264 85.514 4.01621 83.6375 3.70519 81.7282C3.67503 81.5301 3.60552 81.34 3.50077 81.1692C3.39601 80.9983 3.25812 80.8502 3.09522 80.7335C2.93232 80.6167 2.74771 80.5338 2.55226 80.4895C2.35681 80.4453 2.15448 80.4406 1.95719 80.4757ZM12.3418 41.7856C13.3025 40.0528 14.3391 38.3383 15.4305 36.6936C15.6534 36.3574 15.7336 35.9464 15.6535 35.5511C15.5734 35.1557 15.3396 34.8083 15.0033 34.5854C14.6671 34.3625 14.2561 34.2822 13.8608 34.3623C13.4654 34.4424 13.118 34.6763 12.8951 35.0125C11.7537 36.7372 10.6818 38.5069 9.68183 40.3173C9.50269 40.6687 9.46718 41.076 9.5828 41.4532C9.69843 41.8303 9.95614 42.1477 10.3015 42.3383C10.6468 42.529 11.0527 42.5779 11.4335 42.4747C11.8142 42.3716 12.1399 42.1245 12.3418 41.7856ZM9.20455 99.1018C9.49883 99.3775 9.8906 99.5251 10.2937 99.512C10.6967 99.4989 11.0781 99.3262 11.3538 99.0319C11.6296 98.7376 11.7771 98.3458 11.764 97.9428C11.7509 97.5397 11.5782 97.1583 11.2839 96.8826C9.94023 95.621 8.73031 94.1466 7.68455 92.4928C7.46928 92.1518 7.12734 91.9102 6.73397 91.8213C6.34059 91.7324 5.92799 91.8033 5.58695 92.0186C5.2459 92.2339 5.00434 92.5758 4.9154 92.9692C4.82646 93.3626 4.89744 93.7751 5.11271 94.1162C6.26085 95.944 7.63573 97.6192 9.20455 99.1018Z" fill="black"/>
                </svg>
                <h2 class="mt-4 login-heading font-playfair">Überprüfen Sie Ihre E-Mail</h2>
                <small class="font-rajdhani text-center px-7">Wir haben Ihnen einen Link zum Vergessen des Passworts an Ihre E-Mail geschickt <span class="font-semibold" id="maskemail"></span>.</small>
            </div>
        </div>

    </div>
    <div id="modalOverlay">
        <div id="modalContent">
            <button onclick="closeModal()" class="close-button">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
            <h2 class="text-2xl font-bold mb-4 font-playfair">Fehlgeschlagen</h2>
            <div id="popup" class=""></div>
        </div>
    </div>
</body>
    <script>
        function openModal() {
            document.getElementById('modalOverlay').style.display = 'flex';
            document.body.style.overflow = 'hidden';
        }

        function closeModal() {
            document.getElementById('modalOverlay').style.display = 'none';
            document.body.style.overflow = 'auto';
        }

        document.addEventListener("DOMContentLoaded", function() {
            @if(!$errors->any())
                closeModal(); 
            @endif
        });

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

        @if($errors->any())
            showPopupMessage('Diese Ausweise stimmen nicht mit unseren Unterlagen überein.', 'error');
        @endif

        function showPopupMessage(message, type = 'success') {
            openModal()
            const popup = document.getElementById('popup');
            popup.textContent = message;
            // if (type === 'error') {
            //     popup.classList.add('error');
            // } else {
            //     popup.classList.remove('error');
            // }
            setTimeout(() => {
                closeModal();
            }, 5000);
        }
        
        // Title

        document.addEventListener('DOMContentLoaded', () => {
            const urlParams = new URLSearchParams(window.location.search);
            if (urlParams.has('token') && urlParams.has('email')) {
                document.getElementById('login-form').style.display = 'none';
                document.getElementById('forgot-password-form').style.display = 'none';
                document.getElementById('reset-token').value = urlParams.get('token');
                document.getElementById('reset-email').value = urlParams.get('email');
            }
        });

        function showForgotPassword() {
            document.getElementById('login-form').style.display = 'none';
            document.getElementById('forgot-password-form').style.display = 'block';
        }

        function showLogin() {
            document.getElementById('forgot-password-form').style.display = 'none';
            document.getElementById('login-form').style.display = 'block';
        }

        function showEmailSent() {
            document.getElementById('forgot-password-form').style.display = 'none';
            document.getElementById('show-password-form').style.display = 'block';
        }

        async function sendResetLink(event) {
            event.preventDefault();
            const popup = document.getElementById('loader-message');
            popup.classList.remove('hidden');

            const email = document.getElementById('reset-email').value;
            try {
                await axios.post('/api/password/email', { email });
                document.getElementById('maskemail').innerText = maskEmail(email);
                // showPopupMessage('Link zum Zurücksetzen wird an Ihre E-Mail gesendet.',error)
                // popup.classList.add('hidden');
                showEmailSent();
            } catch (error) {
                console.log(error);
                popup.classList.add('hidden');
                showForgotPassword()
                showPopupMessage('Fehler beim Senden des Reset-Links.', 'error');
            }
        }

        function maskEmail(email) {
            const [username, domain] = email.split('@');
            if (username.length > 4) {
                const maskedUsername = `${username.slice(0, 2)}*****${username.slice(-2)}`;
                return `${maskedUsername}@${domain}`;
            }
            return email;
        }
    </script>
</body>
</html>
