<?php

declare(strict_types=1);

namespace JeffreyVanHees\OnlinePaymentPlatform\Requests\Files;

use Saloon\Contracts\Body\HasBody;
use Saloon\Data\MultipartValue;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Traits\Body\HasMultipartBody;

class UploadFileRequest extends Request implements HasBody
{
    use HasMultipartBody;

    protected Method $method = Method::POST;

    public function __construct(
        protected string $fileUid,
        protected string $token,
        protected string $filePath,
        protected string $fileName
    ) {
    }

    public function resolveEndpoint(): string
    {
        return "/uploads/{$this->fileUid}";
    }

    protected function defaultHeaders(): array
    {
        return [
            'x-opp-files-token' => $this->token,
        ];
    }

    protected function defaultBody(): array
    {
        return [
            'file' => new MultipartValue(
                name: 'file',
                value: file_get_contents($this->filePath),
                filename: $this->fileName
            ),
        ];
    }
}