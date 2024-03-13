<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Weekly Domain Update</title>
</head>
<body>
    <h3>Weekly Domain Update</h3>
    <p ><strong>Domains updated successfully!</strong></p>
    <ul>
        <li>Total Domains: {{ $totalDomains }}</li>
        <li>Active Domains: {{ $activeDomains }}</li>
        <li>Expired Domains: {{ $expiredDomains }}</li>
    </ul>    
    <p>Timestamp: {{ $timestamp }}</p>
</body>
</html>
