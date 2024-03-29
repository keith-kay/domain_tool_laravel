@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <h1>Dashboard</h1>
@stop

@section('content')
    <div class="container-fluid">
        <div class="row">
          <div class="col-6">
              <div class="info-box">
                <span class="info-box-icon bg-info elevation-1"><i class="fas fa-check-circle"></i></span>
                  <div class="info-box-content">
                    <span class="info-box-text "><h4 class="fw-bold">Active Domains</h4></span>
                      <span class="info-box-number" style="font-size: 22px;">{{ $activeCount }}</span>
                  </div>
              </div>
          </div>
          <div class="col-6">
              <div class="info-box mb-3">
                <span class="info-box-icon bg-danger elevation-1"><i class="fas fa-exclamation-circle"></i></span>
                  <div class="info-box-content">
                      <span class="info-box-text fw-bold"><h4 class="fw-bold">Expired Domains</h4></span>
                      <span class="info-box-number" style="font-size: 22px;">{{ $expiredCount}}</span>
                  </div>
              </div>
          </div>
        </div>
    </div>
    <div class="col-lg-12 mx-1">
    <div class="jumbotron">
        
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
                searching:false,
                pageLength: 10,
               
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

@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
@stop

@section('js')
    <script> console.log('Hi!'); </script>
@stop