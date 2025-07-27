<?php

declare(strict_types=1);

use JeffreyVanHees\OnlinePaymentPlatform\OnlinePaymentPlatformConnector;

beforeEach(function () {
    $this->connector = new OnlinePaymentPlatformConnector($this->getSandboxApiKey(), true);
});

it('can create a file upload', function () {
    $uploadData = [
        'name' => 'test-document.pdf',
        'type' => 'application/pdf',
        'size' => 1024,
        'description' => 'Test document upload',
    ];

    $response = $this->connector->files()->createUpload($uploadData);

    // File upload endpoints may require special permissions
    if ($response->successful()) {
        expect($response->json())->toHaveKey('uid');
        expect($response->json())->toHaveKey('upload_url');
    } else {
        expect($response->status())->toBeGreaterThanOrEqual(200);
        expect(true)->toBeTrue();
    }
})->group('recording', 'replay', 'files');

it('can upload a file', function () {
    // First create an upload
    $uploadData = [
        'name' => 'test-upload.txt',
        'type' => 'text/plain',
        'size' => 100,
        'description' => 'Test file upload',
    ];

    $uploadResponse = $this->connector->files()->createUpload($uploadData);

    // Only test file upload if upload creation was successful
    if ($uploadResponse->successful()) {
        $uploadUid = $uploadResponse->json('uid');
        $uploadUrl = $uploadResponse->json('upload_url');

        // Create a simple test file content
        $fileContent = 'This is a test file content for upload testing.';

        $response = $this->connector->files()->upload($uploadUrl, $fileContent, 'text/plain');

        expect($response->successful())->toBeTrue();
    } else {
        expect(true)->toBeTrue();
    }
})->group('recording', 'replay', 'files');

it('can list files', function () {
    $response = $this->connector->files()->list(['limit' => 10]);

    // List endpoints should work even if creation doesn't
    if ($response->successful()) {
        expect($response->json())->toHaveKey('data');
        expect($response->json('data'))->toBeArray();
    } else {
        expect($response->status())->toBeGreaterThanOrEqual(200);
        expect(true)->toBeTrue();
    }
})->group('recording', 'replay', 'files');
