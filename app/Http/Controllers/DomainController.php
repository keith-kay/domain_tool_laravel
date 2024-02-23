<?php

namespace App\Http\Controllers;

use App\Models\Domain;
use App\Models\Company;
use Illuminate\Http\Request;
use GuzzleHttp\Client;

class DomainController extends Controller
{
    public function index()
    {
        $domains = Domain::all();
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
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'company_id' => 'required|exists:companies,id',
            'expiry_date' => 'required|date',
            'registration_date' => 'required|date',
            'registrar_name' => 'required|string|max:255',
        ]);

        try {
            // Parse the registration date string into a DateTime object
            $registrationDate = new DateTime($validatedData['registration_date']);
            $expiryDate = new DateTime($validatedData['expiry_date']);
            
            // Format the registration date into the desired format (e.g., Y-m-d)
            $formattedRegistrationDate = $registrationDate->format('Y-m-d');
            $formattedExpiryDate = $expiryDate->format('Y-m-d');
            
            // Log parsed and formatted dates
            Log::info('Formatted Registration Date: ' . $formattedRegistrationDate);
            Log::info('Formatted Expiry Date: ' . $formattedExpiryDate);
            
            // Create a new Domain instance with the validated data
            $domain = new Domain();
            $domain->name = $validatedData['name'];
            $domain->company_id = $validatedData['company_id'];
            $domain->expiry_date = $formattedExpiryDate;
            $domain->registration_date = $formattedRegistrationDate;
            $domain->registrar_name = $validatedData['registrar_name'];

            // Save the domain to the database
            $domain->save();

            // Log successful save
            Log::info('Domain saved successfully');

            return redirect()->route('domains.index')->with('success', 'Domain created successfully');
        } catch (\Exception $e) {
            // Log error
            Log::error('Error creating domain: ' . $e->getMessage());

            return redirect()->back()->with('error', 'Failed to create domain: ' . $e->getMessage())->withInput();
        }
    }
}
