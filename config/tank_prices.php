<?php

return [
    // Size-specific brand pricing
    '11kg' => [
        'solane' => 1150,
        'pryce' => 1150,
        'petron' => 1150,
        'petronas' => 1100, // accept petronas spelling
        'phoenix' => 1100,  // treat phoenix same as petronas for 11kg per request
    ],
    '2.7kg' => [
        'pryce' => 380,
    ],
    '50kg' => [
        // Only Petronas/Phoenix are priced at 3900
        'phoenix' => 3900,
        'petronas' => 3900,
    ],
];
