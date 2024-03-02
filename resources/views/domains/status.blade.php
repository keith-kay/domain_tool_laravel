@extends('adminlte::page')

@section('title', 'Domain Status')

@section('content_header')
    <h1>Status</h1>
@stop

@section('content')
<div class="col-lg-12 mx-1">
    <div class="jumbotron">
        <form method="post" action="" id="updateDomainsForm">
            @csrf
            <div class="row">
                <div class="col-md-8">
                    <h3 class="display-6 mb-0">Domain Status</h3>
                </div>
                @if(auth()->user()->is_admin)
                <div class="col-md-4 text-right">
                    <button type="submit" class="btn btn-outline-success" id="updateDomainsButton">
                        <i class="fas fa-file-export"></i> Export data
                    </button>
                </div>
                @endif
            </div>
        </form>
        <hr class="my-4">

        <table class="display" id="domainTable">
            <thead class="border-bottom font-weight-bold">
                <tr>
                    <td> Domain Name </td>
                    <td> Date of Registration</td>
                    <td> Date of Expiry</td>
                    <td> Company <br></td>
                    <td> Registrar Name <br></td>&nbsp;                   
                </tr>
            </thead>
            <tbody>
                @foreach($domains as $domain)
                <tr>
                    <td> {{ $domain->name }}</td>
                    <td> {{ $domain->registration_date }}</td>
                    <td> {{ $domain->expiry_date }}</td>
                    <td> {{ $domain->company->name }}</td>
                    <td> {{ $domain->registrar_name }}</td>
                </tr>
                @endforeach
            </tbody>    
        </table>
    </div>
</div>
</div>
@stop

@section('js')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.css" />
    <script type="text/javascript" src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <script src="//cdn.jsdelivr.net/npm/alertifyjs@1.13.1/build/alertify.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.16.9/xlsx.full.min.js"></script> <!-- Add XLSX library -->
    <script>
        $(document).ready(function () {
            var table = $('#domainTable').DataTable({
                lengthChange: false,
                pageLength: 10,
                initComplete: function () {
                    // Delay the execution by 100 milliseconds
                    setTimeout(function () {
                        fetchAndUpdateUpdatedColumn(table);
                    }, 100);
                    this.api().columns().every(function (index) {
                        // Check if it's the 1st or 4th column (index 0 or 3)
                        if (index === 0 || index === 3) {
                            let column = this;
                            let title = column.header().textContent;

                            // Create input element
                            let input = document.createElement('input');
                            input.placeholder = title;

                            // Append input to the header row
                            $(column.header()).append(input);

                            // Event listener for user input
                            input.addEventListener('keyup', () => {
                                if (column.search() !== input.value) {
                                    column.search(input.value).draw();
                                }
                            });
                        }
                    });
                }
            });

            // Add an event listener to the export button
            $('#updateDomainsForm').on('submit', function (event) {
                event.preventDefault(); // Prevent the default form submission
                exportDataToExcel();
            });

            // Function to export data to Excel
            function exportDataToExcel() {
                // Check if DataTable is initialized
                if (!table) {
                    console.error('DataTable is not initialized. Aborting exportDataToExcel.');
                    return;
                }

                // Check if DataTable has rows
                if (!table.rows) {
                    console.error('DataTable does not have rows. Aborting exportDataToExcel.');
                    return;
                }

                // Iterate through each row and fetch data
                const exportData = [];
                table.rows().every(function () {
                    const row = this.node();
                    const domainName = $(row).find('td:eq(0)').text().trim();
                    const registrationDate = $(row).find('td:eq(1)').text().trim();
                    const expiryDate = $(row).find('td:eq(2)').text().trim();
                    const company = $(row).find('td:eq(3)').text().trim();
                    const registrarName = $(row).find('td:eq(4)').text().trim();

                    exportData.push({
                        domainName,
                        registrationDate,
                        expiryDate,
                        company,
                        registrarName,
                    });
                });

                // Create a worksheet with the extracted data
                const ws = XLSX.utils.json_to_sheet(exportData);

                // Create a workbook and add the worksheet
                const wb = XLSX.utils.book_new();
                XLSX.utils.book_append_sheet(wb, ws, 'DomainData');

                // Save the workbook to an Excel file
                XLSX.writeFile(wb, 'exported_domain_data.xlsx');
            }
        });
    </script>
@stop
