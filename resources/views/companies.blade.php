@extends('layouts.app')
<link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
<script src="https://code.jquery.com/jquery-3.5.1.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>

<style>
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
                        <input type="text" name="telefon" class="w-full border border-gray-300 p-2 rounded">
                    </div>
                    <div class="col-span-2">
                        <label class="block text-gray-700">Telefonnummer (Firma)</label>
                        <input type="text" name="telefon_firma" class="w-full border border-gray-300 p-2 rounded">
                    </div>

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
                @foreach($companies as $company)
                    <tr class="border-b hover:bg-gray-50">
                        <td class="px-4 py-3"><input type="checkbox" class="form-checkbox"></td>
                        <td class="px-4 py-3 text-sm text-gray-900">{{ $company->id }}</td>
                        <td class="px-4 py-3 text-sm text-gray-900">{{ $company->anrede }} {{ $company->vorname }} {{ $company->nachname }}</td>
                        <td class="px-4 py-3 text-sm text-gray-900 uppercase">{{ $company->firmen_id }}</td>
                        <td class="px-4 py-3 text-sm text-red-600">
                            <a href="{{ $company->webseite }}" target="_blank">{{ $company->firmenname }}</a>
                        </td>
                        <td class="px-4 py-3 text-sm text-gray-900">{{ $company->jobtitel }}</td>
                        <td class="px-4 py-3 text-sm text-gray-900">{{ $company->webseite }}</td>
                        <td class="px-4 py-3 text-sm">
                            <!-- Action icons here (edit, delete) -->
                            <button class="text-gray-600 hover:text-gray-800">
                                <svg width="17" height="16" viewBox="0 0 17 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M3.49996 12.6667H4.44996L10.9666 6.15L10.0166 5.2L3.49996 11.7167V12.6667ZM2.16663 14V11.1667L10.9666 2.38333C11.1 2.26111 11.2472 2.16667 11.4083 2.1C11.5694 2.03333 11.7388 2 11.9166 2C12.0944 2 12.2666 2.03333 12.4333 2.1C12.6 2.16667 12.7444 2.26667 12.8666 2.4L13.7833 3.33333C13.9166 3.45556 14.0139 3.6 14.075 3.76667C14.1361 3.93333 14.1666 4.1 14.1666 4.26667C14.1666 4.44444 14.1361 4.61389 14.075 4.775C14.0139 4.93611 13.9166 5.08333 13.7833 5.21667L4.99996 14H2.16663ZM10.4833 5.68333L10.0166 5.2L10.9666 6.15L10.4833 5.68333Z" fill="#1D1B20"/>
                                </svg>

                            </button>
                            <button class="text-gray-600 hover:text-gray-800 ml-2">
                                <svg width="17" height="16" viewBox="0 0 17 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M4.83325 14C4.46659 14 4.1527 13.8694 3.89159 13.6083C3.63047 13.3472 3.49992 13.0333 3.49992 12.6667V4H2.83325V2.66667H6.16659V2H10.1666V2.66667H13.4999V4H12.8333V12.6667C12.8333 13.0333 12.7027 13.3472 12.4416 13.6083C12.1805 13.8694 11.8666 14 11.4999 14H4.83325ZM11.4999 4H4.83325V12.6667H11.4999V4ZM6.16659 11.3333H7.49992V5.33333H6.16659V11.3333ZM8.83325 11.3333H10.1666V5.33333H8.83325V11.3333Z" fill="#1D1B20"/>
                                </svg>
                            </button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection

<script>
    $(document).ready(function() {
        $('#companiesTable').DataTable({
            "pageLength": 10,
            "lengthMenu": [10, 25, 50, 100],
            "language": {
                "search": "Hier suchen:",
                "lengthMenu": "Anzeigen _MENU_ Einträge",
                "paginate": {
                    "first": "Erste",
                    "last": "Letzte",
                    "next": "Nächster",
                    "previous": "Vorherige"
                }
            },
            "columnDefs": [
                { "orderable": false, "targets": 0 },
                { "orderable": false, "targets": -1 }
            ]
        });
    });

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
