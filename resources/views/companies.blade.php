@extends('layouts.app')
<link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
<script src="https://code.jquery.com/jquery-3.5.1.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>

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

.col-span-4 {
    grid-column: span 4;
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


</style>

@section('content')
<div class="container mx-auto p-6">
    <!-- Add New Company Button -->
    <div class="flex justify-between items-center mb-6">
        <nav class="flex items-center space-x-2 text-gray-600 text-sm" aria-label="Breadcrumb">
            <a class="hover:text-gray-900 font-rajdhani">Unternehmer</a>
            <span class="ml-2 mr-2"> > </span>
            <span class="text-red-600 active font-rajdhani font-semibold">Liste der Unternehmer</span>
        </nav>

        <button onclick="openModal()" class="bg-black text-white px-4 py-2 rounded flex items-center font-rajdhani font-semibold">
            <span class="mr-2">+</span> Unternehmer hinzufügen
        </button>
        
        <!-- Modal Overlay -->
        <div id="modalOverlay">
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
                    <div class="col-span-4">
                        <label class="block text-gray-700 font-rajdhani font-semibold">ID</label>
                        <input type="text" name="id" class="w-full border border-gray-300 p-2 rounded font-rajdhani" readonly>
                    </div>

                    <!-- Row 2: Anrede, Vorname, Nachname -->
                    <div class="col-span-1">
                        <label class="block text-gray-700 font-rajdhani font-semibold">Anrede</label>
                        <select name="anrede" class="w-full border border-gray-300 p-2 rounded font-rajdhani">
                            <option value="Herr">Herr</option>
                            <option value="Frau">Frau</option>
                        </select>
                    </div>
                    <div class="col-span-1">
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
                    <div class="col-span-1">
                        <label class="block text-gray-700 font-rajdhani font-semibold">Jobtitel</label>
                        <input type="text" name="jobtitel" class="w-full border border-gray-300 p-2 rounded font-rajdhani">
                    </div>

                    <!-- Row 4: E-Mail-Adresse -->
                    <div class="col-span-4">
                        <label class="block text-gray-700 font-rajdhani font-semibold">E-Mail-Adresse</label>
                        <input type="email" name="email" class="w-full border border-gray-300 p-2 rounded font-rajdhani">
                    </div>

                    <!-- Row 5: Straße -->
                    <div class="col-span-4">
                        <label class="block text-gray-700 font-rajdhani font-semibold">Straße</label>
                        <input type="text" name="strasse" class="w-full border border-gray-300 p-2 rounded font-rajdhani">
                    </div>

                    <!-- Row 6: Hausnummer, PLZ -->
                    <div class="col-span-1">
                        <label class="block text-gray-700 font-rajdhani font-semibold">Hausnummer</label>
                        <input type="text" name="hausnummer" class="w-full border border-gray-300 p-2 rounded font-rajdhani">
                    </div>
                    <div class="col-span-1">
                        <label class="block text-gray-700 font-rajdhani font-semibold">PLZ</label>
                        <input type="text" name="plz" class="w-full border border-gray-300 p-2 rounded font-rajdhani">
                    </div>

                    <!-- Row 7: Ort, Land -->
                    <div class="col-span-1">
                        <label class="block text-gray-700 font-rajdhani font-semibold">Ort</label>
                        <input type="text" name="ort" class="w-full border border-gray-300 p-2 rounded font-rajdhani">
                    </div>
                    <div class="col-span-1">
                        <label class="block text-gray-700 font-rajdhani font-semibold">Land</label>
                        <input type="text" name="land" class="w-full border border-gray-300 p-2 rounded font-rajdhani">
                    </div>

                    <!-- Row 8: Telefonnummer, Telefonnummer (Firma) -->
                    <div class="col-span-2">
                        <label class="block text-gray-700 font-rajdhani font-semibold">Telefonnummer</label>
                        <input type="text" name="telefonnummer" class="w-full border border-gray-300 p-2 rounded font-rajdhani">
                    </div>
                    <div class="col-span-2">
                        <label class="block text-gray-700 font-rajdhani font-semibold">Telefonnummer (Firma)</label>
                        <input type="text" name="telefonnummer_firma" class="w-full border border-gray-300 p-2 rounded font-rajdhani">
                    </div>

                    <div class="col-span-2">
                        <label class="block text-gray-700 font-rajdhani font-semibold">E-Mail-Adresse (Firma)</label>
                        <input type="text" name="email_adresse_firma" class="w-full border border-gray-300 p-2 rounded font-rajdhani">
                    </div>
                    <div class="col-span-2">
                        <label class="block text-gray-700 font-rajdhani font-semibold">LinkedIn Account (Firma)</label>
                        <input type="text" name="linkedin_account_firma" class="w-full border border-gray-300 p-2 rounded font-rajdhani">
                    </div>

                    <div class="col-span-2">
                        <label class="block text-gray-700 font-rajdhani font-semibold">NACE-Code (Ebene 1)</label>
                        <input type="text" name="nace_code_ebene_1" class="w-full border border-gray-300 p-2 rounded font-rajdhani">
                    </div>
                    <div class="col-span-2">
                        <label class="block text-gray-700 font-rajdhani font-semibold">NACE-Code (Ebene 2)</label>
                        <input type="text" name="nace_code_ebene_2" class="w-full border border-gray-300 p-2 rounded font-rajdhani">
                    </div>

                    <div class="col-span-2">
                        <label class="block text-gray-700 font-rajdhani font-semibold">Beschreibung NACE-Code (Ebene 2)</label>
                        <input type="text" name="beschreibung_nace_code_ebene_2" class="w-full border border-gray-300 p-2 rounded font-rajdhani">
                    </div>
                    <div class="col-span-2">
                        <label class="block text-gray-700 font-rajdhani font-semibold">WZ-Code</label>
                        <input type="text" name="wz_code" class="w-full border border-gray-300 p-2 rounded font-rajdhani">
                    </div>
                    @csrf
                    <div class="col-span-2">
                        <label class="block text-gray-700 font-rajdhani font-semibold">Beschreibung WZ-Code</label>
                        <input type="text" name="beschreibung_wz_code" class="w-full border border-gray-300 p-2 rounded font-rajdhani">
                    </div>
                    <div class="col-span-2">
                        <label class="block text-gray-700 font-rajdhani font-semibold">Branche (Hauptkategorie)</label>
                        <input type="text" name="branche_hauptkategorie" class="w-full border border-gray-300 p-2 rounded font-rajdhani">
                    </div>
                    <div class="col-span-2">
                        <label class="block text-gray-700 font-rajdhani font-semibold">Branche (Unterkategorie)</label>
                        <input type="text" name="branche_unterkategorie" class="w-full border border-gray-300 p-2 rounded font-rajdhani">
                    </div>
                    <div class="col-span-2"></div>
                    <!-- Row 9: Action Buttons (Aligned Right) -->
                    <div class="col-span-2 mt-6 ">
                        <button type="button" onclick="closeModal()" class="px-4 py-2 w-full bg-gray-200 text-gray-700 rounded font-rajdhani font-semibold">Abbrechen</button>
                    </div>
                    <div class="col-span-2 mt-6 flex justify-end space-x-4">
                        <button id="modalSubmitButton" type="submit" class="px-4 py-2 w-full bg-black text-white rounded font-rajdhani font-semibold">Speichern</button>
                    </div>
                </form>
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
@endsection

<script>
    $(document).ready(function() {
        const table = $('#companiesTable').DataTable({
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
            "dom": "<'top'f><'clear'>t<'bottom'l><'bottom'p>",
            "paging": true,
            "ordering": true,
            "info": true,
            "searching": true,
            "order": [[1, 'asc']],
            "lengthMenu": [10, 25, 50, 60, 80, 100],
            "pagingType": "full_numbers",
            "language": {
                "decimal": ",",
                "thousands": ".",
                "lengthMenu": "Zeige _MENU_ Einträge",
                "zeroRecords": "Keine passenden Einträge gefunden",
                "info": "Zeige Eintrag _START_ bis _END_ von _TOTAL_ Einträgen",
                "infoEmpty": "Keine Einträge verfügbar",
                "infoFiltered": "(gefiltert von _MAX_ Einträgen insgesamt)",
                "search": "Suche:",
                "paginate": {
                    "first": "&#8592;",
                    "last": "&#8594;",
                    "next": "Nächste",
                    "previous": "Vorherige"
                }
            }
        });


        $('#companiesTable').on('click', '.view-company-btn', function () {
            const companyId = $(this).data('id'); // Get the company ID from data attribute

            // AJAX request to fetch company details
            $.ajax({
                url: `/companies/${companyId}/edit`,
                type: 'GET',
                success: function (data) {
                    // Populate modal fields with the received data
                    $('input[name="id"]').val(data.id);
                    $('input[name="anrede"]').val(data.anrede);
                    $('input[name="vorname"]').val(data.vorname);
                    $('input[name="nachname"]').val(data.nachname);
                    $('input[name="firmen_id"]').val(data.firmen_id);
                    $('input[name="firmenname"]').val(data.firmenname);
                    $('input[name="jobtitel"]').val(data.jobtitel);
                    $('input[name="email"]').val(data.email_adresse);
                    $('input[name="strasse"]').val(data.strasse);
                    $('input[name="hausnummer"]').val(data.hausnummer);
                    $('input[name="plz"]').val(data.plz);
                    $('input[name="ort"]').val(data.ort);
                    $('input[name="land"]').val(data.land);
                    $('input[name="telefonnummer"]').val(data.telefonnummer);
                    $('input[name="telefonnummer_firma"]').val(data.telefonnummer_firma);
                    $('input[name="beschreibung_nace_code_ebene_2"]').val(data.beschreibung_nace_code_ebene_2);
                    $('input[name="email_adresse_firma"]').val(data.email_adresse_firma);
                    $('input[name="linkedin_account_firma"]').val(data.linkedin_account_firma);
                    $('input[name="nace_code_ebene_1"]').val(data.nace_code_ebene_1);
                    $('input[name="nace_code_ebene_2"]').val(data.nace_code_ebene_2);
                    $('input[name="wz_code"]').val(data.wz_code);
                    $('input[name="beschreibung_wz_code"]').val(data.beschreibung_wz_code);
                    $('input[name="branche_hauptkategorie"]').val(data.branche_hauptkategorie);
                    $('input[name="branche_unterkategorie"]').val(data.branche_unterkategorie);

                    // Show the modal
                    openModal();
                },
                error: function (xhr) {
                    alert('An error occurred while fetching the data.'); // Error handling
                }
            });
        });

        $('#companiesTable').on('click', '.view-company-btn, .edit-company-btn', function () {
            const companyId = $(this).data('id');
            const isEdit = $(this).hasClass('edit-company-btn');

            $.ajax({
                url: `/companies/${companyId}/edit`,
                type: 'GET',
                success: function (data) {
                    $('#companyForm').data('isEdit', isEdit); // Save edit mode in form data
                    populateForm(data);
                    openModal();
                },
                error: function () {
                    showMessage('Fehler beim Abrufen der Daten', 'error');
                }
            });
        });

        // Save button handler
        $('#companyForm').on('submit', function (e) {
            e.preventDefault();
            const isEdit = $(this).data('isEdit');
            const companyId = $('input[name="id"]').val();
            const url = isEdit ? `/companies/${companyId}` : '/companies';
            const method = isEdit ? 'PUT' : 'POST';

            $.ajax({
                url: url,
                type: method,
                data: $(this).serialize(),
                success: function () {
                    closeModal();
                    table.ajax.reload();
                    showMessage(isEdit ? 'Erfolgreich aktualisiert' : 'Erfolgreich erstellt', 'success');
                },
                error: function () {
                    showMessage('Fehler beim Speichern der Daten', 'error');
                }
            });
        });

        $('#companiesTable').on('click', '.delete-company-btn', function () {
            const companyId = $(this).data('id');
            if (confirm('Möchten Sie diesen Eintrag wirklich löschen?')) {
                deleteCompany(companyId);
            }
        });
    });

    function deleteCompany(companyId) {
        $.ajax({
            url: `/companies/${companyId}`,
            type: 'POST', 
            data: {
                _method: 'DELETE', 
                _token: $('meta[name="csrf-token"]').attr('content') 
            },
            success: function(response) {
                showMessage('Unternehmen erfolgreich gelöscht', 'success');
                $('#companiesTable').DataTable().ajax.reload();
            },
            error: function(xhr) {
                alert('Beim Löschen der Firma ist ein Fehler aufgetreten.');
            }
        });
    }

    // Populate form fields with company data
    function populateForm(data) {
        Object.keys(data).forEach(key => {
            $(`input[name="${key}"]`).val(data[key]);
        });
    }

    // Display message function
    function showMessage(message, type) {
        const messageBox = $('<div>').text(message).addClass(`message-box ${type}`);
        $('body').append(messageBox);
        setTimeout(() => messageBox.fadeOut(), 3000);
    }


    function openModal() {
        // Directly set display to 'flex' to make modal visible
        document.getElementById('modalOverlay').style.display = 'flex';
        document.body.style.overflow = 'hidden'; // Prevent background scroll
    }

    function closeModal() {
        // Directly set display to 'none' to hide modal
        document.getElementById('modalOverlay').style.display = 'none';
        document.body.style.overflow = 'auto'; // Restore background scroll
    }

    // Ensure the modal is hidden on load
    document.addEventListener("DOMContentLoaded", function() {
        closeModal(); // Ensure modal is hidden when the page loads
    });
</script>
