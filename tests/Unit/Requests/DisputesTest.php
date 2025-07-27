<?php

declare(strict_types=1);

use JeffreyVanHees\OnlinePaymentPlatform\Requests\Disputes\CreateDisputeRequest;
use JeffreyVanHees\OnlinePaymentPlatform\Requests\Disputes\GetDisputeRequest;
use JeffreyVanHees\OnlinePaymentPlatform\Requests\Disputes\GetDisputesRequest;
use Saloon\Enums\Method;

require_once __DIR__.'/../../Helpers/TestHelpers.php';
require_once __DIR__.'/../../Datasets/RequestDatasets.php';

describe('Disputes Requests', function () {

    test('create dispute request', function (array $data) {
        $request = new CreateDisputeRequest($data);

        assertRequest($request, Method::POST, '/disputes');
        assertRequestBody($request, $data);
    })->with('dispute_scenarios');

    test('get dispute request', function () {
        $disputeUid = 'dis_123456789';
        $request = new GetDisputeRequest($disputeUid);

        assertRequest($request, Method::GET, "/disputes/{$disputeUid}");
    });

    test('get dispute request with query params', function (array $params) {
        $disputeUid = 'dis_123456789';
        $request = new GetDisputeRequest($disputeUid, $params);

        assertRequest($request, Method::GET, "/disputes/{$disputeUid}");
        assertRequestQuery($request, $params);
    })->with([
        'no params' => [[]],
        'with include' => [['include' => 'transaction']],
        'with expand' => [['expand' => 'merchant,files']],
    ]);

    test('get disputes request', function (array $params) {
        $request = new GetDisputesRequest($params);

        assertRequest($request, Method::GET, '/disputes');
        assertRequestQuery($request, $params);
    })->with('complex_query_scenarios');

    test('get disputes with status filter', function (array $statusFilter) {
        $params = array_merge(['limit' => 25], $statusFilter);
        $request = new GetDisputesRequest($params);

        assertRequest($request, Method::GET, '/disputes');
        assertRequestQuery($request, $params);
    })->with('status_filters');

    test('get disputes with date filters', function (array $dateFilters) {
        $request = new GetDisputesRequest($dateFilters);

        assertRequest($request, Method::GET, '/disputes');
        assertRequestQuery($request, $dateFilters);
    })->with('date_filter_params');
});
