<?php

declare(strict_types=1);

use JeffreyVanHees\OnlinePaymentPlatform\Requests\Files\CreateFileUploadRequest;
use JeffreyVanHees\OnlinePaymentPlatform\Requests\Files\GetFilesRequest;
use JeffreyVanHees\OnlinePaymentPlatform\Requests\Files\UploadFileRequest;
use Saloon\Enums\Method;

describe('Files Requests', function () {
    describe('CreateFileUploadRequest', function () {
        test('it has correct method and endpoint', function () {
            $data = [
                'purpose' => 'identity_document',
                'filename' => 'document.pdf',
                'size' => 1024,
            ];
            $request = new CreateFileUploadRequest($data);

            expect($request->getMethod())->toBe(Method::POST);
            expect($request->resolveEndpoint())->toBe('/uploads');
        });

        test('it accepts file upload data', function () {
            $data = [
                'purpose' => 'identity_document',
                'filename' => 'passport.pdf',
                'size' => 2048,
                'mime_type' => 'application/pdf',
                'description' => 'Customer passport for verification',
            ];
            $request = new CreateFileUploadRequest($data);

            $body = $request->body()->all();
            expect($body['purpose'])->toBe('identity_document');
            expect($body['filename'])->toBe('passport.pdf');
            expect($body['size'])->toBe(2048);
            expect($body['mime_type'])->toBe('application/pdf');
            expect($body['description'])->toBe('Customer passport for verification');
        });

        test('it handles minimal data', function () {
            $data = [
                'purpose' => 'bank_statement',
                'filename' => 'statement.pdf',
            ];
            $request = new CreateFileUploadRequest($data);

            $body = $request->body()->all();
            expect($body['purpose'])->toBe('bank_statement');
            expect($body['filename'])->toBe('statement.pdf');
            expect($body)->not->toHaveKey('size');
            expect($body)->not->toHaveKey('mime_type');
        });
    });

    describe('UploadFileRequest', function () {
        test('it has correct method and endpoint', function () {
            $fileUid = 'fil_123456789';
            $token = 'upload_token_123';
            $filePath = '/path/to/file.pdf';
            $fileName = 'document.pdf';
            $request = new UploadFileRequest($fileUid, $token, $filePath, $fileName);

            expect($request->getMethod())->toBe(Method::POST);
            expect($request->resolveEndpoint())->toBe("/uploads/{$fileUid}");
        });

        test('it uses file UID in endpoint', function () {
            $fileUid = 'fil_987654321';
            $request = new UploadFileRequest($fileUid, 'token', '/path/to/document.pdf', 'doc.pdf');

            expect($request->resolveEndpoint())->toBe("/uploads/{$fileUid}");
        });

        test('it sets correct headers', function () {
            $token = 'upload_token_456';
            $request = new UploadFileRequest('fil_123', $token, '/path/file.pdf', 'file.pdf');

            $headers = $request->headers()->all();
            expect($headers['x-opp-files-token'])->toBe($token);
        });
    });

    describe('GetFilesRequest', function () {
        test('it has correct method and endpoint', function () {
            $request = new GetFilesRequest;

            expect($request->getMethod())->toBe(Method::GET);
            expect($request->resolveEndpoint())->toBe('/objects');
        });

        test('it accepts empty parameters', function () {
            $request = new GetFilesRequest;

            $query = $request->query()->all();
            expect($query)->toBe([]);
        });

        test('it accepts query parameters', function () {
            $params = [
                'purpose' => 'identity_document',
                'merchant_uid' => 'mer_123456789',
                'limit' => 20,
                'offset' => 40,
            ];
            $request = new GetFilesRequest($params);

            $query = $request->query()->all();
            expect($query['purpose'])->toBe('identity_document');
            expect($query['merchant_uid'])->toBe('mer_123456789');
            expect($query['limit'])->toBe(20);
            expect($query['offset'])->toBe(40);
        });

        test('it handles date filter parameters', function () {
            $params = [
                'created_after' => '2024-01-01',
                'created_before' => '2024-12-31',
                'status' => 'verified',
            ];
            $request = new GetFilesRequest($params);

            $query = $request->query()->all();
            expect($query['created_after'])->toBe('2024-01-01');
            expect($query['created_before'])->toBe('2024-12-31');
            expect($query['status'])->toBe('verified');
        });
    });
});
