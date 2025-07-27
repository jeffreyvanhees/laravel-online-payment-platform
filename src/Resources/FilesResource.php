<?php

declare(strict_types=1);

namespace JeffreyVanHees\OnlinePaymentPlatform\Resources;

use JeffreyVanHees\OnlinePaymentPlatform\Requests\Files\CreateFileUploadRequest;
use JeffreyVanHees\OnlinePaymentPlatform\Requests\Files\GetFilesRequest;
use JeffreyVanHees\OnlinePaymentPlatform\Requests\Files\UploadFileRequest;
use Saloon\Http\BaseResource;
use Saloon\Http\Response;

/**
 * Resource class for managing files
 *
 * Provides methods for creating file upload links, uploading files, and retrieving file lists.
 * Handles document uploads for compliance and verification purposes.
 */
class FilesResource extends BaseResource
{
    /**
     * Get list of files/objects
     *
     * @param  array  $params  Optional query parameters for filtering
     * @return Response API response containing a list of files
     *
     * @throws \JeffreyVanHees\OnlinePaymentPlatform\Exceptions\AuthenticationException When API key is invalid
     * @throws \JeffreyVanHees\OnlinePaymentPlatform\Exceptions\ApiException For other API errors
     */
    public function list(array $params = []): Response
    {
        return $this->connector->send(new GetFilesRequest($params));
    }

    /**
     * Create a file upload link
     *
     * @param  array  $data  Upload data including purpose (e.g., "bank_account_bank_statement")
     * @return Response API response containing the upload token and file UID
     *
     * @throws \JeffreyVanHees\OnlinePaymentPlatform\Exceptions\ValidationException When required fields are missing or invalid
     * @throws \JeffreyVanHees\OnlinePaymentPlatform\Exceptions\AuthenticationException When API key is invalid
     * @throws \JeffreyVanHees\OnlinePaymentPlatform\Exceptions\ApiException For other API errors
     */
    public function createUpload(array $data): Response
    {
        return $this->connector->send(new CreateFileUploadRequest($data));
    }

    /**
     * Upload a file using the upload token
     *
     * @param  string  $fileUid  The file UID from createUpload response
     * @param  string  $token  The upload token from createUpload response
     * @param  string  $filePath  Local path to the file to upload
     * @param  string  $fileName  Original filename for the upload
     * @return Response API response confirming the file upload
     *
     * @throws \JeffreyVanHees\OnlinePaymentPlatform\Exceptions\ValidationException When file or token is invalid
     * @throws \JeffreyVanHees\OnlinePaymentPlatform\Exceptions\AuthenticationException When API key is invalid
     * @throws \JeffreyVanHees\OnlinePaymentPlatform\Exceptions\ApiException For other API errors
     */
    public function upload(string $fileUid, string $token, string $filePath, string $fileName): Response
    {
        return $this->connector->send(new UploadFileRequest($fileUid, $token, $filePath, $fileName));
    }
}
