<?php

use Alphaolomi\Cellulant\CellulantService;

it('can instantiate the cellulant class', function () {
    $cellulant = new CellulantService();
    expect($cellulant)->toBeInstanceOf(CellulantService::class);
});
