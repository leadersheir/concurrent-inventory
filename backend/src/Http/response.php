<?php
namespace App\Http;

function sendJson(
    array $data,
    int $httpResponseCode=200
): void {
   header(
        'Content-Type: application/json',
        response_code: $httpResponseCode
    ); 

    echo json_encode($data) . "\n";
}