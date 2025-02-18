@extends('layouts.app')
@section('content')
<link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
<script src="https://code.jquery.com/jquery-3.5.1.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
<link href="https://cdn.bootcdn.net/ajax/libs/font-awesome/5.13.0/css/all.min.css" rel="stylesheet">
<link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">
<link href="https://cdn.quilljs.com/1.3.7/quill.snow.css" rel="stylesheet">
<style>
    .dataTables_wrapper {
        font-family: 'rajdhani', sans-serif; /* Replace 'YourCustomFont' with your preferred font */
    }

    th{
        text-align: left !important;
    }
    td a{
        padding: 0 !important;
    }
    td {
        text-align: left;
        padding-left: 0px !important;
    }
    /* Modal overlay and content styling */
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
        max-width: 700px;
        max-height: 70vh;
        overflow-y: auto;
        padding: 1.5rem;
        border-radius: 8px;
        box-shadow: 0px 4px 15px rgba(0, 0, 0, 0.2);
        position: relative;
    }
    /* For WebKit browsers (Chrome, Safari, Edge) */
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

    table.dataTable thead th, table.dataTable thead td {
        padding:10px 0 !important;
    }

    .close-button:hover {
        background-color: rgba(255, 0, 0, 0.1); /* Light red background on hover */
    }

    /* For Firefox */
    #modalContent {
        scrollbar-width: thin; /* Set width to thin */
        scrollbar-color: #be1622 transparent; /* Red thumb with a transparent track */
    }

    .grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 16px;
    }

    .col-span-1 {
        grid-column: span 1;
    }

    .dataTables_wrapper .dataTables_paginate {
        display: flex !important;
        visibility: visible !important;
    }

    .dataTables_paginate .paginate_button {
        display: inline-block !important;
        visibility: visible !important;
    }

    .col-span-2 {
        grid-column: span 2;
    }

    .col-span-5 {
        grid-column: span 5;
    }

    .col-span-3 {
        grid-column: span 3;
    }

    .w-full {
        width: 100%;
    }

    .border {
        border: 1px solid #d1d5db;
    }

    .p-2 {
        padding: 8px;
    }

    .rounded {
        border-radius: 8px;
    }


    .text-gray-700 {
        color: #4b5563;
    }

    .mt-6 {
        margin-top: 24px;
    }

    .bg-gray-200 {
        background-color: #e5e7eb;
    }

    .bg-black {
        background-color: #000;
    }

    .text-white {
        color: #fff;
    }
    #companiesTable_length, #companiesTable_paginate{
        margin-top: 1em;
    }
    .dataTables_wrapper .dataTables_paginate .paginate_button {
        padding: 0.5em !important;
    }
    .dataTables_wrapper .dataTables_paginate .paginate_button.current, .dataTables_wrapper .dataTables_paginate .paginate_button.current:hover{
        background: white;
        border: 2px solid #be1622;
        border-radius: 50%;
        font-weight: bold;
        padding: 5px 14px !important;
        color: #be1622 !important;
    }
    .message-box {
        position: fixed;
        top: 20px;
        right: 20px;
        padding: 10px 20px;
        border-radius: 5px;
        color: #fff;
        z-index: 100;
    }
    .message-box.success {
        background-color: #4CAF50; /* Green for success */
    }
    .message-box.error {
        background-color: #F44336; /* Red for error */
    }
    .mr-right {
        float: right;
    }
    .bg-green {
        background-color: #4CAF50;
    }

    #csvModalOverlay {
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
    #csvModalContent {
        background-color: white;
        width: 100%;
        max-width: 700px;
        max-height: 70vh;
        overflow-y: auto;
        padding: 1.5rem;
        border-radius: 8px;
        box-shadow: 0px 4px 15px rgba(0, 0, 0, 0.2);
        position: relative;
    }
    /* For WebKit browsers (Chrome, Safari, Edge) */
    #csvModalContent::-webkit-scrollbar {
        width: 8px; /* Scrollbar width */
    }
    #csvModalContent::-webkit-scrollbar-thumb {
        background-color: #be1622; /* #be1622 color for the scrollbar thumb */
        border-radius: 10px; /* Optional: round the corners */
    }
    #csvModalContent::-webkit-scrollbar-thumb:hover {
        background-color: #be1622; /* Darker red on hover */
    }

    .success {
        color: #178d1e;
    }
    .error {
        color: #be1622;
    }
    #ModalOverlayText {
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
    #ModalContentText {
        background-color: white;
        width: 100%;
        max-width: 700px;
        max-height: 70vh;
        overflow-y: auto;
        padding: 1.5rem;
        border-radius: 8px;
        box-shadow: 0px 4px 15px rgba(0, 0, 0, 0.2);
        position: relative;
        scrollbar-width: thin;
        scrollbar-color: #be1622 transparent; 
    }
    #ModalContentText::-webkit-scrollbar {
        width: 8px; /* Scrollbar width */
    }
    #ModalContentText::-webkit-scrollbar-thumb {
        background-color: #be1622; /* #be1622 color for the scrollbar thumb */
        border-radius: 10px; /* Optional: round the corners */
    }
    #ModalContentText::-webkit-scrollbar-thumb:hover {
        background-color: #be1622; /* Darker red on hover */
    }

    #ModalText {
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
    #ModalCext {
        background-color: white;
        width: 100%;
        max-width: 400px;
        max-height: 70vh;
        overflow-y: auto;
        padding: 1.5rem;
        border-radius: 8px;
        box-shadow: 0px 4px 15px rgba(0, 0, 0, 0.2);
        position: relative;
        scrollbar-width: thin;
        scrollbar-color: #be1622 transparent; 
    }
    #ModalCext::-webkit-scrollbar {
        width: 8px; /* Scrollbar width */
    }
    #ModalCext::-webkit-scrollbar-thumb {
        background-color: #be1622; /* #be1622 color for the scrollbar thumb */
        border-radius: 10px; /* Optional: round the corners */
    }
    #ModalCext::-webkit-scrollbar-thumb:hover {
        background-color: #be1622; /* Darker red on hover */
    }

    /* Progress Bar */
    #progressContainer {
        background-color: #e5e7eb;
        border-radius: 8px;
        overflow: hidden;
        height: 4px;
        margin-top: 10px;
    }
    #progressBar {
        width: 0;
        height: 100%;
    }
    /* Popup Message Box */
    .message-box {
        position: fixed;
        top: 20px;
        right: 20px;
        padding: 10px 20px;
        border-radius: 5px;
        color: #fff;
        z-index: 100;
    }
    .message-box.success {
        background-color: #4CAF50; /* Green for success */
    }
    .message-box.error {
        background-color: #F44336; /* Red for error */
    }
    .bg-red {
        background: #be1622;
    }
</style>
<div class="container mx-auto p-6">
    <div class="flex justify-between items-center mb-6">
        <nav class="flex items-center space-x-2 text-gray-600 text-sm" aria-label="Breadcrumb">
            <a class="hover:text-gray-900 font-rajdhani">Unternehmer</a>
            <span class="ml-2 mr-2"> > </span>
            <span class="text-red-600 active font-rajdhani font-semibold">Liste der Unternehmer</span>
        </nav>
        <div class="flex">
            <button onclick="openCsvModal()" class="bg-green text-white px-4 py-2 mr-2 rounded flex font-rajdhani font-semibold">
                <span class="mr-2">+</span> Csv-Datei hochladen
            </button>
            <button onclick="openModal()" class="bg-black text-white px-4 py-2 rounded flex items-center font-rajdhani font-semibold">
                <span class="mr-2">+</span> Unternehmer hinzufügen
            </button>
        </div>

        <div id="csvModalOverlay" style="display: none;">
            <div id="csvModalContent">
                <!-- Close Button -->
                <button onclick="closeCsvModal()" class="close-button">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>

                <h2 class="text-2xl font-bold mb-4 font-playfair">CSV-Datei hochladen</h2>
                <form id="csvUploadForm" enctype="multipart/form-data">
                    @csrf
                    <input type="file" name="file" accept=".csv" required class="w-full border border-gray-300 p-2 rounded mb-4 font-rajdhani font-semibold" />
                    <div id="progressContainer" style="display: none;" class="mb-4">
                        <div id="progressBar" class="bg-green-600 h-4 rounded font-rajdhani font-semibold"></div>
                    </div>
                    <button type="submit" class="bg-black text-white w-full py-2 rounded font-rajdhani font-semibold">Daten hochladen</button>
                </form>
            </div>
        </div>
        
        <div id="modalOverlay" style="display: none;">
            <!-- Modal Content -->
            <div id="modalContent">
                <!-- Close Button -->

                <button onclick="closeModal()" class="close-button">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>

                <!-- Modal Header -->
                <h2 class="text-2xl font-bold mb-4 font-playfair">Unternehmerprofil</h2>

                <!-- Modal Form Content -->
                <form id="companyForm" class="grid grid-cols-4 gap-4">
                    <!-- Row 1: ID -->
                    <div class="col-span-5">
                        <label class="block text-gray-700 font-rajdhani font-semibold">ID</label>
                        <input type="text" name="cid" class="w-full border border-gray-300 p-2 rounded font-rajdhani">
                        <input type="hidden" name="id" class="w-full border border-gray-300 p-2 rounded font-rajdhani">
                    </div>

                    <!-- Row 2: Anrede, Vorname, Nachname -->
                    <div class="col-span-1">
                        <label class="block text-gray-700 font-rajdhani font-semibold">Anrede</label>
                        <select name="anrede" class="w-full border border-gray-300 p-2 rounded font-rajdhani">
                            <option value="Herr">Herr</option>
                            <option value="Frau">Frau</option>
                        </select>
                    </div>
                    <div class="col-span-2">
                        <label class="block text-gray-700 font-rajdhani font-semibold">Vorname</label>
                        <input type="text" name="vorname" class="w-full border border-gray-300 p-2 rounded font-rajdhani">
                    </div>
                    <div class="col-span-2">
                        <label class="block text-gray-700 font-rajdhani font-semibold">Nachname</label>
                        <input type="text" name="nachname" class="w-full border border-gray-300 p-2 rounded font-rajdhani">
                    </div>

                    <!-- Row 3: Firmen-ID, Firmenname, Jobtitel -->
                    <div class="col-span-1">
                        <label class="block text-gray-700 font-rajdhani font-semibold">Firmen-ID</label>
                        <input type="text" name="firmen_id" class="w-full border border-gray-300 p-2 rounded font-rajdhani">
                    </div>
                    <div class="col-span-2">
                        <label class="block text-gray-700 font-rajdhani font-semibold">Firmenname</label>
                        <input type="text" name="firmenname" class="w-full border border-gray-300 p-2 rounded font-rajdhani">
                    </div>
                    <div class="col-span-2">
                        <label class="block text-gray-700 font-rajdhani font-semibold">Jobtitel</label>
                        <input type="text" name="jobtitel" class="w-full border border-gray-300 p-2 rounded font-rajdhani">
                    </div>

                    <!-- Row 4: E-Mail-Adresse -->
                    <div class="col-span-5">
                        <label class="block text-gray-700 font-rajdhani font-semibold">E-Mail-Adresse</label>
                        <input type="email" name="email_adresse" class="w-full border border-gray-300 p-2 rounded font-rajdhani">
                    </div>

                    <!-- Row 5: Straße -->
                    <div class="col-span-5">
                        <label class="block text-gray-700 font-rajdhani font-semibold">Straße</label>
                        <input type="text" name="strasse" class="w-full border border-gray-300 p-2 rounded font-rajdhani">
                    </div>

                    <!-- Row 6: Hausnummer, PLZ -->
                    <div class="col-span-2">
                        <label class="block text-gray-700 font-rajdhani font-semibold">Hausnummer</label>
                        <input type="text" name="hausnummer" class="w-full border border-gray-300 p-2 rounded font-rajdhani">
                    </div>
                    <div class="col-span-3">
                        <label class="block text-gray-700 font-rajdhani font-semibold">PLZ</label>
                        <input type="text" name="plz" class="w-full border border-gray-300 p-2 rounded font-rajdhani">
                    </div>

                    <!-- Row 7: Ort, Land -->
                    <div class="col-span-2">
                        <label class="block text-gray-700 font-rajdhani font-semibold">Ort</label>
                        <input type="text" name="ort" class="w-full border border-gray-300 p-2 rounded font-rajdhani">
                    </div>
                    <div class="col-span-3">
                        <label class="block text-gray-700 font-rajdhani font-semibold">Land</label>
                        <input type="text" name="land" class="w-full border border-gray-300 p-2 rounded font-rajdhani">
                    </div>

                    <!-- Row 8: Telefonnummer, Telefonnummer (Firma) -->
                    <div class="col-span-2">
                        <label class="block text-gray-700 font-rajdhani font-semibold">Telefonnummer</label>
                        <input type="text" name="telefonnummer" class="w-full border border-gray-300 p-2 rounded font-rajdhani">
                    </div>
                    <div class="col-span-3">
                        <label class="block text-gray-700 font-rajdhani font-semibold">Telefonnummer (Firma)</label>
                        <input type="text" name="telefonnummer_firma" class="w-full border border-gray-300 p-2 rounded font-rajdhani">
                    </div>

                    <div class="col-span-2">
                        <label class="block text-gray-700 font-rajdhani font-semibold">E-Mail-Adresse (Firma)</label>
                        <input type="text" name="email_adresse_firma" class="w-full border border-gray-300 p-2 rounded font-rajdhani">
                    </div>
                    <div class="col-span-3">
                        <label class="block text-gray-700 font-rajdhani font-semibold">LinkedIn Account (Firma)</label>
                        <input type="text" name="linkedin_account_firma" class="w-full border border-gray-300 p-2 rounded font-rajdhani">
                    </div>

                    <div class="col-span-2">
                        <label class="block text-gray-700 font-rajdhani font-semibold">NACE-Code (Ebene 1)</label>
                        <input type="text" name="nace_code_ebene_1" class="w-full border border-gray-300 p-2 rounded font-rajdhani">
                    </div>
                    <div class="col-span-3">
                        <label class="block text-gray-700 font-rajdhani font-semibold">NACE-Code (Ebene 2)</label>
                        <input type="text" name="nace_code_ebene_2" class="w-full border border-gray-300 p-2 rounded font-rajdhani">
                    </div>

                    <div class="col-span-5">
                        <label class="block text-gray-700 font-rajdhani font-semibold">Beschreibung NACE-Code (Ebene 2)</label>
                        <textarea cols="30" rows="4" name="beschreibung_nace_code_ebene_2" class="w-full border border-gray-300 p-2 rounded font-rajdhani"> </textarea>
                    </div>
                    <div class="col-span-5">
                        <label class="block text-gray-700 font-rajdhani font-semibold">WZ-Code</label>
                        <input type="text" name="wz_code" class="w-full border border-gray-300 p-2 rounded font-rajdhani">
                    </div>
                    @csrf
                    <div class="col-span-5">
                        <label class="block text-gray-700 font-rajdhani font-semibold">Beschreibung WZ-Code</label>
                        <textarea cols="30" rows="4" name="beschreibung_wz_code" class="w-full border border-gray-300 p-2 rounded font-rajdhani"> </textarea>
                    </div>
                    <div class="col-span-2">
                        <label class="block text-gray-700 font-rajdhani font-semibold">Branche (Hauptkategorie)</label>
                        <input type="text" name="branche_hauptkategorie" class="w-full border border-gray-300 p-2 rounded font-rajdhani">
                    </div>
                    <div class="col-span-3">
                        <label class="block text-gray-700 font-rajdhani font-semibold">Branche (Unterkategorie)</label>
                        <input type="text" name="branche_unterkategorie" class="w-full border border-gray-300 p-2 rounded font-rajdhani">
                    </div>
                    <div class="col-span-5">
                        <label class="block text-gray-700 font-rajdhani font-semibold">Webseite</label>
                        <input type="text" name="webseite" class="w-full border border-gray-300 p-2 rounded font-rajdhani">
                    </div>
                    <div class="col-span-5">
                        <div id="editor" style="height: 300px;" class="h-80 border border-gray-300 rounded-md"></div>
                    </div>
                    <div class="col-span-5">
                        <label class="block text-gray-700 font-rajdhani font-semibold">Meta-Titel</label>
                        <input type="text" name="meta_title" class="w-full border border-gray-300 p-2 rounded font-rajdhani">
                    </div>
                    <div class="col-span-5">
                        <label class="block text-gray-700 font-rajdhani font-semibold">Meta Description</label>
                        <input type="text" name="meta_description" class="w-full border border-gray-300 p-2 rounded font-rajdhani">
                    </div>
                    <!-- Row 9: Action Buttons (Aligned Right) -->
                    <div class="col-span-2 mt-6 dont-show-in-view">
                        <button type="button" onclick="closeModal()" class="px-4 py-2 w-full bg-gray-200 text-gray-700 rounded font-rajdhani font-semibold">Abbrechen</button>
                    </div>
                    <div class="col-span-2 mt-6 flex dont-show-in-view justify-end space-x-4">
                        <button id="modalSubmitButton" type="submit" class="px-4 py-2 w-full bg-black text-white rounded font-rajdhani font-semibold">Speichern</button>
                    </div>
                </form>
            </div>
        </div>

        <div id="ModalOverlayText" style="display: none;">
            <div id="ModalContentText">
                <button onclick="closeModalS()" class="close-button">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
                <h2 class="text-2xl font-bold mb-4 font-playfair" id="popup-error">Unternehmerprofil</h2>
                <p id="popup-message" class="text-xl font-rajdhani font-semibold"></p>
            </div>
        </div>

        <div id="ModalText" style="display: none;">
            <div id="ModalCext">
                <button onclick="closeModalT()" class="close-button">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
                <h2 class="text-xl font-bold mb-4 font-playfair">Unternehmerprofil löschen</h2>
                <hr>
                <h2 class="text-xl font-bold mt-6 font-playfair" id="dl_name">Unternehmerprofil löschen</h2>
                <small><i class="fas fa-map-marker-alt"></i><span id="location" class="font-rajdhani"> Elly-Heuss-Knapp-Straße</span></small>
                <div class="flex flex-rows mt-4">
                    <div class="col-span-1 text-right">
                        <i class="fas fa-building mt-2 mr-2"></i> 
                    </div>
                    <div class="col-span-3">
                        <h2 class="text-xl font-rajdhani font-semibold">Firmenname</h2>
                        <h2 class="text-md font-rajdhani" id="firmaname">ASO Service Gmbh</h2>
                    </div>
                </div>
                <p id="description" class="text-md font-regular mt-4 font-rajdhani">Sind sie sicher, dass Sie diesen Unternehmer aus der Liste entfernen möchten? Der Datensatz wird unwiderruflich entfernt.</p>

                <!-- Row 9: Action Buttons (Aligned Right) -->
                <div id="companyForm" class="grid grid-cols-4 gap-4">
                    <div class="col-span-2 mt-6 dont-show-in-view">
                        <button type="button" onclick="closeModalT()" class="px-4 py-2 w-full bg-gray-200 text-gray-700 rounded font-rajdhani font-semibold">Abbrechen</button>
                    </div>
                    <div class="col-span-2 mt-6 flex dont-show-in-view justify-end space-x-4" id="submitbutton">
                        
                    </div>
                </div>
            </div>
        </div>

    </div>

    <div class="bg-white overflow-x-auto p-6">  
        <div class="w-full py-4 mb-6">
            <svg width="24" height="24" viewBox="0 0 24 24" class="mt-2" style="float: left;" fill="none" xmlns="http://www.w3.org/2000/svg">
                <rect width="24" height="24" rx="12" fill="#FFCC00"/>
                <path d="M12 7V3H2V21H22V7H12ZM6 19H4V17H6V19ZM6 15H4V13H6V15ZM6 11H4V9H6V11ZM6 7H4V5H6V7ZM10 19H8V17H10V19ZM10 15H8V13H10V15ZM10 11H8V9H10V11ZM10 7H8V5H10V7ZM20 19H12V17H14V15H12V13H14V11H12V9H20V19ZM18 11H16V13H18V11ZM18 15H16V17H18V15Z" fill="#1E1E1E"/>
            </svg>
            <h2 class="ml-4 mb-4 heading font-playfair font-semibold" style="float: left;">Liste der Unternehmer</h2>
        </div>
            
        <table id="companiesTable" class="min-w-full mt-4 leading-normal font-rajdhani">
            <thead>
                <tr class="border-b">
                    <th class="text-xs font-semibold text-gray-600 uppercase">
                        <input type="checkbox" class="form-checkbox">
                    </th>
                    <th class="text-sm font-semibold text-gray-600 uppercase font-rajdhani">ID</th>
                    <th class="text-sm font-semibold text-gray-600 uppercase font-rajdhani">Unternehmer</th>
                    <th class="text-sm font-semibold text-gray-600 uppercase font-rajdhani">Firmen-ID</th>
                    <th class="text-sm font-semibold text-gray-600 uppercase font-rajdhani">Firmenname</th>
                    <th class="text-sm font-semibold text-gray-600 uppercase font-rajdhani">Jobtitel</th>
                    <th class="text-sm font-semibold text-gray-600 uppercase font-rajdhani">Webseite</th>
                    <th class="text-sm font-semibold text-gray-600 uppercase font-rajdhani" width="80">Aktion</th>
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </div>
</div>
<script src="https://cdn.quilljs.com/1.3.7/quill.min.js"></script>
<script>
    var quill = new Quill('#editor', {
        theme: 'snow'
    });

    $(document).ready(function() {
        $('#companiesTable').DataTable({
            "processing": true,
            "serverSide": true,
            "ajax": {
                "url": "{{ route('companies.index') }}",
                "type": "GET"
            },
            "columns": [
                { "data": "checkbox", "orderable": false },
                { "data": "id" },
                { "data": "name" },
                { "data": "firmen_id" },
                { "data": "firmenname" },
                { "data": "jobtitel" },
                { "data": "webseite" },
                { "data": "actions", "orderable": false }
            ],
            "paging": true,
            "ordering": true,
            "info": true,
            "searching": true,
            "lengthMenu": [10, 25, 50, 100],
            "pagingType": "full_numbers", 
            "language": {
                "lengthMenu": "Zeige _MENU_ Einträge",
                "zeroRecords": "Keine passenden Einträge gefunden",
                "info": "Zeige Eintrag _START_ bis _END_ von _TOTAL_ Einträgen",
                "infoEmpty": "Keine Einträge verfügbar",
                "infoFiltered": "(gefiltert von _MAX_ Einträgen insgesamt)",
                "search": "Suche:",
                "paginate": {
                    "first": "Erste",
                    "last": "Letzte",
                    "next": "Nächste",
                    "previous": "Vorherige"
                }
            }
        });

        $('#companiesTable').on('click', '.delete-company-btn', function () {
            const companyId = $(this).data('id');
            $.ajax({
                url: `/companies/${companyId}/edit`,
                type: 'GET',
                success: function (data) {
                    $('input[name="id"]').val(data.id);
                    $('#dl_name').text(`${data.anrede} ${data.vorname} ${data.nachname}`);
                    $('#location').text(` ${data.hausnummer} ${data.strasse} ${data.ort}, ${data.plz} ${data.land}`);
                    $('#firmaname').text(data.firmenname);
                    $('#description').text(data.beschreibung_nace_code_ebene_2);
                    $('#submitbutton').html(`<button id="submitButton" onclick="deleteCompany(${data.id})" type="button" class="px-4 py-2 w-full bg-red text-white rounded font-rajdhani font-semibold">Löschen</button>`)
                    openModalT();
                },
                error: function (xhr) {
                    showMessage('Beim Abrufen der Daten ist ein Fehler aufgetreten.','Scheitern','error');
                }
            });
        });

        $('#companiesTable').on('click', '.edit-company-btn', function () {
            const companyId = $(this).data('id');
            const isEdit = $(this).hasClass('edit-company-btn');
            $.ajax({
                url: `/companies/${companyId}/edit`,
                type: 'GET',
                success: function (data) {
                    $('#companyForm').data('isEdit', isEdit); 
                    openModal();
                    console.log(data);
                    populateForm(data, isEdit);
                },
                error: function () {
                    showMessage('Fehler beim Abrufen der Daten','Scheitern','error');
                }
            });
        });

        $('#companyForm').on('submit', function (e) {
            e.preventDefault();
            const isEdit = $(this).data('isEdit');
            const companyId = $('input[name="id"]').val();
            console.log("🚀 ~ companyId:", companyId)
            const url = isEdit ? `/companies/${companyId}` : '/companies';
            const method = isEdit ? 'PUT' : 'POST';

            const formData = $(this).serializeArray(); // Get form data as an array
            formData.push(
                { name: 'html_content', value: quill.root.innerHTML } 
            );
            console.log("🚀 ~ formData:", formData)

            $.ajax({
                url: url,
                type: method,
                data: $.param(formData),
                success: function () {
                    closeModal();
                    $('#companiesTable').DataTable().ajax.reload();
                    showMessage(isEdit ? 'Unternehmen erfolgreich aktualisiert' : 'Unternehmen erfolgreich aktualisiert', 'Erfolg', 'success');
                },
                error: function () {
                    showMessage('Fehler beim Speichern der Daten', 'Scheitern', 'error');
                }
            });
        });
    });

    function deleteCompany(companyId) {
        closeModalT();
        $.ajax({
            url: `/companies/${companyId}`,
            type: 'POST', 
            data: {
                _method: 'DELETE', 
                _token: $('meta[name="csrf-token"]').attr('content') 
            },
            success: function(response) {
                showMessage('Unternehmen erfolgreich gelöscht','Erfolg','success');
                $('#companiesTable').DataTable().ajax.reload();
            },
            error: function(xhr) {
                showMessage('Beim Löschen der Firma ist ein Fehler aufgetreten.','Scheitern','error');
            }
        });
    }

    function populateForm(data,flag) {
        if(!flag) {
            $('.dont-show-in-view').hide();
        } else {
            $('.dont-show-in-view').show();
        }
        Object.keys(data).forEach(key => {
            if(key !== 'beschreibung_nace_code_ebene_2' && key !== 'beschreibung_wz_code' && key !== 'html_content') {
                $(`input[name="${key}"]`).val(data[key]);
            } else {
                if(key === 'html_content') {
                    quill.root.innerHTML = data[key]
                } else {
                    $(`textarea[name="${key}"]`).val(data[key]);
                }
            }
        });
    }

    function showMessage(message, type, visual) {
        var pop = document.getElementById('popup-error');
        pop.textContent = type;
        pop.classList.add(visual);

        var pops = document.getElementById('popup-message');
        pops.textContent = message;
        pops.classList.add(visual);
        openModalS();
        setTimeout(() => {
            closeModal();
            closeModalS();
            closeModalT();
        }, 4000);
    }

    function openModal() {
        document.getElementById("companyForm").reset();
        document.getElementById('modalOverlay').style.display = 'flex';
        document.body.style.overflow = 'hidden';
    }

    function closeModal() {
        document.getElementById('modalOverlay').style.display = 'none';
        document.body.style.overflow = 'auto';
    }

    function openModalT() {
        document.getElementById('ModalText').style.display = 'flex';
        document.body.style.overflow = 'hidden';
    }

    function closeModalT() {
        document.getElementById('ModalText').style.display = 'none';
        document.body.style.overflow = 'auto';
    }

    function openModalS() {
        document.getElementById('ModalOverlayText').style.display = 'flex';
        document.body.style.overflow = 'hidden';
    }

    function closeModalS() {
        document.getElementById('ModalOverlayText').style.display = 'none';
        document.body.style.overflow = 'auto';
    }

    document.addEventListener("DOMContentLoaded", function() {
        closeModal(); 
        closeModalS(); 
        closeModalT();
    });

    function openCsvModal() {
        document.getElementById('csvModalOverlay').style.display = 'flex';
    }

    function closeCsvModal() {
        document.getElementById('csvModalOverlay').style.display = 'none';
    }

    $('#csvUploadForm').on('submit', function(event) {
        event.preventDefault();

        const formData = new FormData(this);
        const progressBar = $('#progressBar');
        const progressContainer = $('#progressContainer');

        progressContainer.show();
        progressBar.width('0%'); 

        $.ajax({
            url: "{{ route('companies.import_frontend') }}", 
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            xhr: function() {
                const xhr = new window.XMLHttpRequest();
                xhr.upload.addEventListener("progress", function(event) {
                    if (event.lengthComputable) {
                        const percentComplete = (event.loaded / event.total) * 100;
                        progressBar.width(percentComplete + '%');
                    }
                }, false);
                return xhr;
            },
            success: function(response) {
                console.log("🚀 ~ $ ~ response:", response)
                showMessage(response.message, 'Erfolg', 'success');
                closeCsvModal();

                $('#csvUploadForm')[0].reset();
                progressContainer.hide(); 

                $('#companiesTable').DataTable().ajax.reload(); 
            },
            error: function(xhr) {
                const errorMessage = xhr.responseJSON && xhr.responseJSON.error ? xhr.responseJSON.error : 'Fehler beim Hochladen';
                showMessage(errorMessage, 'Scheitern','error');
                progressContainer.hide();
            }
        });
    });

</script>
@endsection

