<?php

it('can test', function () {

    $response = app(\Sevaske\Payfort\Payfort::class)
        ->merchant()
        ->api()
        ->checkStatus(merchantReference: '5000900')
        ->getData();

    dd($response);

    expect(true)->toBeTrue();
});
