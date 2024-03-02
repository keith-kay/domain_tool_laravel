<?php

namespace App\Http\Controllers;

use DateTime;
use App\Models\Domain;
use App\Models\Company;
use Illuminate\Http\Request;
use GuzzleHttp\Client;
use App\Mail\WeeklyDomainUpdate;
use Illuminate\Support\Facades\Mail;


class DomainController extends Controller
{
    public function index()
    {
        $domains = Domain::all();
        $domains = Domain::with('company')->get();
        return view('domains.index', compact('domains'));
    }
    public function create()
    {
        $companies = Company::all();
        return view('domains.create', compact('companies'));
    }
    public function lookup(Request $request)
    {
        // Validate the request data
        $request->validate([
            'name' => 'required|string',
        ]);
    
        try {
            $domainValue = $request->input('name');
    
            // Your API token for whoisjsonapi.com
            $apiToken = env('API_TOKEN');
    
            // Construct the API URL
            $apiUrl = "https://whoisjsonapi.com/v1/$domainValue";
    
            // Set the headers for the request

            $headers = [
                'Authorization' => "Bearer $apiToken",
            ];
    
            // Create a new Guzzle HTTP client instance
            $client = new Client();
    
            // Send a GET request to the API with headers
            $response = $client->get($apiUrl, [
                'headers' => $headers,
            ]);
    
            // Parse the response JSON data
            $data = json_decode($response->getBody(), true);
    
            // Extract relevant information
            $domainInfo = $data['domain'] ?? [];
            $registrationDate = $domainInfo['created_date'] ?? '';
            $expiryDate = $domainInfo['expiration_date'] ?? '';
            $registrarInfo = $data['registrar'] ?? [];
            $registrarName = $registrarInfo['name'] ?? '';
    
            // Convert the dates to the desired format
            $registrationDate = date('Y-m-d\TH:i:s\Z', strtotime($registrationDate));
            $expiryDate = date('Y-m-d\TH:i:s\Z', strtotime($expiryDate));
    
            // Return the relevant data as JSON response
            return response()->json([
                'status' => 'success',
                'registration_date' => $registrationDate,
                'expiry_date' => $expiryDate,
                'registrar_name' => $registrarName,
            ]);
        } catch (\Exception $e) {
            // Handle exceptions
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ], 500);
        }
    }
    public function store(Request $request)
    {
        // Validate the incoming request data
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'company' => 'required|integer|exists:companies,id',
            'expiry_date' => 'required|date',
            'registration_date' => 'required|date',
            'registrar_name' => 'required|string|max:255',
        ]);

        // Convert the datetime strings to the appropriate format
        $expiryDate = date('Y-m-d H:i:s', strtotime($validatedData['expiry_date']));
        $registrationDate = date('Y-m-d H:i:s', strtotime($validatedData['registration_date']));

        // Create a new Domain instance and fill it with the validated data
        $domain = new Domain();
        $domain->name = $validatedData['name'];
        $domain->company_id = $validatedData['company'];
        $domain->expiry_date = $expiryDate;
        $domain->registration_date = $registrationDate;
        $domain->registrar_name = $validatedData['registrar_name'];

        if ($domain->save()) {
            // Redirect to the domains.index route with a success message
            return redirect()->route('domains.index')->with('success', 'Domain added successfully!');
        } else {
            // Redirect back to the form with an error message
            return redirect()->back()->withInput()->with('error', 'Failed to add domain. Please try again.');
        }
    }
    public function destroy(Domain $domain)
    {
        $domain->delete();
        return redirect()->route('domains.index')->with('error', 'Domain deleted!');
    }
    public function status()
    {
        $domains = Domain::all();
        $domains = Domain::with('company')->get();
        return view('domains.status', compact('domains'));
    }
    
    public function updateExpiryDates(Request $request)
    {
        // Define WHOIS API endpoint URL
        $whoisApiBaseUrl = 'https://whoisjsonapi.com/v1/';
        // Define WHOIS API token
        $apiToken = env('API_TOKEN');

        // Create a new Guzzle HTTP client instance
        $client = new Client();

        try {
            // Get a list of all domains
            $domains = Domain::all();

            foreach ($domains as $domain) {
                // Skip specific domain(s) if necessary
                if ($domain->name === 'wasinimaritime.co.ke') {
                    continue;
                }

                // Construct the complete WHOIS API URL for the domain
                $whoisApiUrl = $whoisApiBaseUrl . $domain->name;

                // Perform WHOIS query for each domain
                $response = $client->request('GET', $whoisApiUrl, [
                    'headers' => [
                        'Authorization' => 'Bearer ' . $apiToken,
                    ],
                ]);

                // Extract expiry date from the WHOIS API response
                $responseBody = $response->getBody()->getContents();
                $responseData = json_decode($responseBody, true);
                $expiryDateStr = $responseData['domain']['expiration_date'];

                // Parse expiry date
                if ($expiryDateStr) {
                    try {
                        // Attempt to create a DateTime object from the expiry date string
                        $expiryDate = new DateTime($expiryDateStr);

                        // Update domain's expiry_date
                        $domain->expiry_date = $expiryDate;
                        $domain->save();
                    } catch (\Exception $e) {
                        // Handle parsing errors if necessary
                        continue;
                    }
                }
            }

            // Return success JSON response
            return response()->json(['success' => true, 'message' => 'Domains updated successfully.']);
        } catch (\Exception $e) {
            // Log error
            \Log::error('Error updating domains: ' . $e->getMessage());
            // Error message
            $message = 'Error updating domains: ' . $e->getMessage();
            
            // Return error JSON response
            return response()->json(['success' => false, 'message' => $message], 500);
        }
    }

    
}