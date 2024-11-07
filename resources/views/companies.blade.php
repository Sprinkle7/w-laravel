@extends('layouts.app')
<link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
<script src="https://code.jquery.com/jquery-3.5.1.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>

<style>
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
    background-color: red; /* Red color for the scrollbar thumb */
    border-radius: 10px; /* Optional: round the corners */
}

#modalContent::-webkit-scrollbar-thumb:hover {
    background-color: darkred; /* Darker red on hover */
}

.close-button {
    position: absolute;
    top: 16px; /* Adjust position as needed */
    right: 16px; /* Adjust position as needed */
    background-color: transparent; /* Transparent background */
    color: red; /* Red color for the "X" icon */
    border: 2px solid red; /* Red border */
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

/* For Firefox */
#modalContent {
    scrollbar-width: thin; /* Set width to thin */
    scrollbar-color: red transparent; /* Red thumb with a transparent track */
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


</style>

@section('content')
<div class="container mx-auto p-6">
    <!-- Add New Company Button -->
    <div class="flex justify-between items-center mb-6">
        <nav class="flex items-center space-x-2 text-gray-600 text-sm" aria-label="Breadcrumb">
            <a class="hover:text-gray-900">Unternehmer</a>
            <span class="ml-2 mr-2"> > </span>
            <span class="text-red-600 active">Liste der Unternehmer</span>
        </nav>

        <button onclick="openModal()" class="bg-black text-white px-4 py-2 rounded flex items-center">
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
                <h2 class="text-2xl font-bold mb-4">Unternehmerprofil</h2>

                <!-- Modal Form Content -->
                <form id="companyForm" class="grid grid-cols-4 gap-4">
                    <!-- Row 1: ID -->
                    <div class="col-span-4">
                        <label class="block text-gray-700">ID</label>
                        <input type="text" name="id" class="w-full border border-gray-300 p-2 rounded" readonly>
                    </div>

                    <!-- Row 2: Anrede, Vorname, Nachname -->
                    <div class="col-span-1">
                        <label class="block text-gray-700">Anrede</label>
                        <select name="anrede" class="w-full border border-gray-300 p-2 rounded">
                            <option value="Herr">Herr</option>
                            <option value="Frau">Frau</option>
                        </select>
                    </div>
                    <div class="col-span-1">
                        <label class="block text-gray-700">Vorname</label>
                        <input type="text" name="vorname" class="w-full border border-gray-300 p-2 rounded">
                    </div>
                    <div class="col-span-2">
                        <label class="block text-gray-700">Nachname</label>
                        <input type="text" name="nachname" class="w-full border border-gray-300 p-2 rounded">
                    </div>

                    <!-- Row 3: Firmen-ID, Firmenname, Jobtitel -->
                    <div class="col-span-1">
                        <label class="block text-gray-700">Firmen-ID</label>
                        <input type="text" name="firmen_id" class="w-full border border-gray-300 p-2 rounded">
                    </div>
                    <div class="col-span-2">
                        <label class="block text-gray-700">Firmenname</label>
                        <input type="text" name="firmenname" class="w-full border border-gray-300 p-2 rounded">
                    </div>
                    <div class="col-span-1">
                        <label class="block text-gray-700">Jobtitel</label>
                        <input type="text" name="jobtitel" class="w-full border border-gray-300 p-2 rounded">
                    </div>

                    <!-- Row 4: E-Mail-Adresse -->
                    <div class="col-span-4">
                        <label class="block text-gray-700">E-Mail-Adresse</label>
                        <input type="email" name="email" class="w-full border border-gray-300 p-2 rounded">
                    </div>

                    <!-- Row 5: Straße -->
                    <div class="col-span-4">
                        <label class="block text-gray-700">Straße</label>
                        <input type="text" name="strasse" class="w-full border border-gray-300 p-2 rounded">
                    </div>

                    <!-- Row 6: Hausnummer, PLZ -->
                    <div class="col-span-1">
                        <label class="block text-gray-700">Hausnummer</label>
                        <input type="text" name="hausnummer" class="w-full border border-gray-300 p-2 rounded">
                    </div>
                    <div class="col-span-1">
                        <label class="block text-gray-700">PLZ</label>
                        <input type="text" name="plz" class="w-full border border-gray-300 p-2 rounded">
                    </div>

                    <!-- Row 7: Ort, Land -->
                    <div class="col-span-1">
                        <label class="block text-gray-700">Ort</label>
                        <input type="text" name="ort" class="w-full border border-gray-300 p-2 rounded">
                    </div>
                    <div class="col-span-1">
                        <label class="block text-gray-700">Land</label>
                        <input type="text" name="land" class="w-full border border-gray-300 p-2 rounded">
                    </div>

                    <!-- Row 8: Telefonnummer, Telefonnummer (Firma) -->
                    <div class="col-span-2">
                        <label class="block text-gray-700">Telefonnummer</label>
                        <input type="text" name="telefonnummer" class="w-full border border-gray-300 p-2 rounded">
                    </div>
                    <div class="col-span-2">
                        <label class="block text-gray-700">Telefonnummer (Firma)</label>
                        <input type="text" name="telefonnummer_firma" class="w-full border border-gray-300 p-2 rounded">
                    </div>

                    <div class="col-span-2">
                        <label class="block text-gray-700">E-Mail-Adresse (Firma)</label>
                        <input type="text" name="email_adresse_firma" class="w-full border border-gray-300 p-2 rounded">
                    </div>
                    <div class="col-span-2">
                        <label class="block text-gray-700">LinkedIn Account (Firma)</label>
                        <input type="text" name="linkedin_account_firma" class="w-full border border-gray-300 p-2 rounded">
                    </div>

                    <div class="col-span-2">
                        <label class="block text-gray-700">NACE-Code (Ebene 1)</label>
                        <input type="text" name="nace_code_ebene_1" class="w-full border border-gray-300 p-2 rounded">
                    </div>
                    <div class="col-span-2">
                        <label class="block text-gray-700">NACE-Code (Ebene 2)</label>
                        <input type="text" name="nace_code_ebene_2" class="w-full border border-gray-300 p-2 rounded">
                    </div>

                    <div class="col-span-2">
                        <label class="block text-gray-700">Beschreibung NACE-Code (Ebene 2)</label>
                        <input type="text" name="beschreibung_nace_code_ebene_2" class="w-full border border-gray-300 p-2 rounded">
                    </div>
                    <div class="col-span-2">
                        <label class="block text-gray-700">WZ-Code</label>
                        <input type="text" name="wz_code" class="w-full border border-gray-300 p-2 rounded">
                    </div>

                    <div class="col-span-2">
                        <label class="block text-gray-700">Beschreibung WZ-Code</label>
                        <input type="text" name="beschreibung_wz_code" class="w-full border border-gray-300 p-2 rounded">
                    </div>
                    <div class="col-span-2">
                        <label class="block text-gray-700">Branche (Hauptkategorie)</label>
                        <input type="text" name="branche_hauptkategorie" class="w-full border border-gray-300 p-2 rounded">
                    </div>
                    <div class="col-span-2">
                        <label class="block text-gray-700">Branche (Unterkategorie)</label>
                        <input type="text" name="branche_unterkategorie" class="w-full border border-gray-300 p-2 rounded">
                    </div>
                    <div class="col-span-2"></div>
                    <!-- Row 9: Action Buttons (Aligned Right) -->
                    <div class="col-span-2 mt-6 ">
                        <button type="button" onclick="closeModal()" class="px-4 py-2 w-full bg-gray-200 text-gray-700 rounded">Abbrechen</button>
                    </div>
                    <div class="col-span-2 mt-6 flex justify-end space-x-4">
                        <button type="button" onclick="submitForm()" class="px-4 w-full py-2 bg-black text-white rounded">Speichern</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="bg-white overflow-x-auto p-6">
        <table id="companiesTable" class="min-w-full leading-normal">
            <thead>
                <tr class="border-b">
                    <th class="text-xs font-semibold text-gray-600 uppercase">
                        <input type="checkbox" class="form-checkbox">
                    </th>
                    <th class="text-xs font-semibold text-gray-600 uppercase">ID</th>
                    <th class="text-xs font-semibold text-gray-600 uppercase">Unternehmer</th>
                    <th class="text-xs font-semibold text-gray-600 uppercase">Firmen-ID</th>
                    <th class="text-xs font-semibold text-gray-600 uppercase">Firmenname</th>
                    <th class="text-xs font-semibold text-gray-600 uppercase">Jobtitel</th>
                    <th class="text-xs font-semibold text-gray-600 uppercase">Webseite</th>
                    <th class="text-xs font-semibold text-gray-600 uppercase">Aktion</th>
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </div>
</div>
@endsection

<script>
    // $(document).ready(function() {
    //     $('#companiesTable').DataTable({
    //         "pageLength": 10,
    //         "lengthMenu": [10, 25, 50, 100],
    //         "language": {
    //             "search": "Hier suchen:",
    //             "lengthMenu": "Anzeigen _MENU_ Einträge",
    //             "paginate": {
    //                 "first": "Erste",
    //                 "last": "Letzte",
    //                 "next": "Nächster",
    //                 "previous": "Vorherige"
    //             }
    //         },
    //         "columnDefs": [
    //             { "orderable": false, "targets": 0 },
    //             { "orderable": false, "targets": -1 }
    //         ]
    //     });
    // });
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
            "paging": true,           // Enable pagination
            "ordering": true,         // Enable ordering
            "info": true,             // Show table info
            "searching": true,        // Enable search functionality
            "order": [[1, 'asc']],    // Order by an existing column by default
            "lengthMenu": [12, 24, 30, 40, 50, 60, 80, 100], // Optional: control the page length
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
                    "first": "Erste",
                    "last": "Letzte",
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
    });

    // function submitForm() {
    //     const companyId = $('input[name="id"]').val();

    //     $.ajax({
    //         url: companyId ? `/companies/${companyId}` : '/companies',
    //         type: companyId ? 'PUT' : 'POST',
    //         data: $('#companyForm').serialize(),
    //         success: function (response) {
    //             closeModal();
    //             table.ajax.reload(); // Reload the DataTable to reflect changes
    //             alert('Company updated successfully');
    //         },
    //         error: function (xhr) {
    //             alert('An error occurred while saving the data.');
    //         }
    //     });
    // }

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
