<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Domain;
use App\Mail\WeeklyDomainUpdate;
use Illuminate\Support\Facades\Mail;

class SendEmails extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:send-emails';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        try {
            // Perform domain updates
            $this->updateDomains();

            // Retrieve data for email content
            $totalDomains = Domain::count();
            $activeDomains = Domain::where('expiry_date', '>=', now())->count();
            $expiredDomains = Domain::where('expiry_date', '<', now())->count();
            $timestamp = now()->toDateTimeString();

            // Send email if updates are successful
            $this->sendEmail($totalDomains, $activeDomains, $expiredDomains, $timestamp);
        } catch (\Exception $e) {
            // Handle errors
            $this->error('Error updating domains: ' . $e->getMessage());
            return 1; // Return error status
        }

        return 0; // Return success status
    }

    protected function updateDomains()
    {
        // Define WHOIS API endpoint URL
        $whoisApiBaseUrl = 'https://whoisjsonapi.com/v1/';

        // Define WHOIS API token
        $apiToken = env('API_TOKEN');

        // Create a new Guzzle HTTP client instance
        $client = new \GuzzleHttp\Client();

        // Get a list of all domains
        $domains = \App\Models\Domain::all();

        foreach ($domains as $domain) {
            // Skip specific domain(s) if necessary
            if ($domain->name === 'wasinimaritime.co.ke') {
                continue;
            }

            try {
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
                    // Attempt to create a DateTime object from the expiry date string
                    $expiryDate = new \DateTime($expiryDateStr);

                    // Update domain's expiry_date
                    $domain->expiry_date = $expiryDate;
                    $domain->save();
                }
            } catch (\Exception $e) {
                // Handle any errors if necessary
                $this->error('Error updating domain ' . $domain->name . ': ' . $e->getMessage());
            }
        }
    }

    protected function sendEmail($totalDomains, $activeDomains, $expiredDomains, $timestamp)
    {
        // Send email
        Mail::to('keith.rhova@bulkstream.com')->send(new WeeklyDomainUpdate(
            $totalDomains,
            $activeDomains,
            $expiredDomains,
            $timestamp
        ));
    }
}
