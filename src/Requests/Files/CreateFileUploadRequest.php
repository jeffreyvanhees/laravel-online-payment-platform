<?php

declare(strict_types=1);

namespace JeffreyVanHees\OnlinePaymentPlatform\Requests\Files;

use JeffreyVanHees\OnlinePaymentPlatform\Data\Requests\Files\CreateFileData;
use JeffreyVanHees\OnlinePaymentPlatform\Data\Responses\Files\FileData;
use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;
use Saloon\Traits\Body\HasJsonBody;

class CreateFileUploadRequest extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::POST;

    public function __construct(protected CreateFileData|array $data) {}

    public function resolveEndpoint(): string
    {
        return '/uploads';
    }

    protected function defaultBody(): array
    {
        if ($this->data instanceof CreateFileData) {
            return $this->data->toArray();
        }

        return $this->data;
    }

    public function createDtoFromResponse(Response $response): FileData
    {
        return FileData::from($response->json());
    }
}
