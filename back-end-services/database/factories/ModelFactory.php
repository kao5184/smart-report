<?php

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your model factories. Model factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
*/

$foundation = [
    'department_ids' => [1, 2, 3, 4, 5],
    'spu_ids'        => [1, 2, 3, 4, 5],
    'building_ids'   => [1, 2, 3, 4, 5]
];

$factory->define(App\Models\Organization::class, function (Faker\Generator $faker) {
    return [
        'organization_id' => $faker->numberBetween(1, 200),
        'creator_id'      => $faker->numberBetween(1, 200),
        'is_disabled'     => $faker->boolean(),
    ];
});

$factory->define(App\Models\Item::class, function (Faker\Generator $faker) use ($foundation) {
    return [
        'organization_id' => $faker->numberBetween(1, 200),
        'is_suit'         => $faker->boolean(),
        'short_code'      => $faker->numerify('######'),
        'serial_number'   => $faker->randomNumber(),
        'asset_number'    => $faker->randomNumber(),
        'department_id'   => $faker->randomElement($foundation['department_ids']),
        'amount'          => $faker->numberBetween(1, 10),
        'status'          => $faker->randomElement(['idle', 'scrapped', 'in-use']),
        'location'        => [
            'floor_id'     => $faker->numberBetween(1, 100),
            'building_id'  => $faker->randomElement($foundation['building_ids']),
            'doorplate_no' => $faker->name(),
        ],
        'data'            => [
            'memo' => $faker->text(),
        ],
        'cfda'            => $faker->randomNumber(),
        'spu'             => $faker->randomElement($foundation['spu_ids']),
    ];
});

$factory->define(App\Models\MaintenanceTicket::class, function (Faker\Generator $faker) {
    return [
        'organization_id'           => $faker->numberBetween(1, 200),
        'item_id'                   => $faker->numberBetween(1, 200000),
        'priority'                  => $faker->randomNumber(),
        'creator_id'                => $faker->numberBetween(1, 200),
        'is_thirdparty_maintainer'  => $faker->boolean(),
        'failure_classification_id' => $faker->randomNumber(),
        'status'                    => $faker->randomElement(['newly', 'under_repair', 'to_be_confirmed', 'completed']),
        'data'                      => [
            'estimated_end_at'  => date('Y-m-d H:i:s'),
        ],
        'code'                      => $faker->numerify('17######'),
    ];
});

$factory->define(App\Models\ItemTag::class, function (Faker\Generator $faker) {
    return [
        'item_id'   => rand(1, 10000000),
        'tag_id'    => rand(1, 10000000),
    ];
});

$factory->define(App\Models\PreparationItem::class, function (Faker\Generator $faker) {
    return [
        'item_id'            => $faker->numberBetween(1, 2000),
        'collector_id'       => $faker->numberBetween(1, 200),
        'status'             => $faker->randomElement(['previewed', 'collected', 'completed']),
        'is_correct'         => $faker->boolean(),
        'validation'         => [],
        'preparation_status' => $faker->randomElement(['found', 'match']),
        'preparation_id'     => $faker->randomNumber(),
    ];
});

$factory->define(App\Models\PreparationOperatingLog::class, function (Faker\Generator $faker) {
    return [
        'operator_id'         => $faker->numberBetween(1, 200),
        'preparation_item_id' => $faker->numberBetween(1, 2000),
        'operation'           => $faker->randomElement(['modify_item', 'assign', 'create_item']),
        'data'                => [],
    ];
});

$factory->define(App\Models\TicketOperatingLog::class, function (Faker\Generator $faker) {
    return [
        'ticket_id'     => $faker->numberBetween(1, 200),
        'operator_id'   => $faker->numberBetween(1, 200),
        'operation'     => $faker->randomElement(['create', 'accept', 'finish']),
        'data'          => [],
    ];
});

$factory->define(App\Models\TicketCost::class, function (Faker\Generator $faker) {
    return [
        'ticket_id' => 1,
        'type' => 'detail',//accessory|detail
        'name' => 1,
        'amount' => 1,
        'cost' => $faker->numberBetween(1, 200),
    ];
});

$factory->define(App\Models\QualityTodoOption::class, function (Faker\Generator $faker) {
    return [
        'name' => $faker->name,
        'description' => $faker->streetAddress,
        'type' => 'checkbox',
        'organization_id' => 1000000
    ];
});

$factory->define(App\Models\QualityTodoSchedule::class, function (Faker\Generator $faker) {
    return [
        'item_id' => rand(1, 100),    //单个设备
        'organization_id' => rand(1, 100),
        'type' => \App\Repositories\V2\QualityRepository::SHECDULE_TYPE[0],
        'executor_ids' => [91, 92],
        'todo_options' => [],
        'data' => [
            'is_repeat' => $faker->boolean,
            'start_date' => $faker->date('Y-m-d'),
            'repeat_type' => 'day',
            'repeat_value' => 9,
        ],
        'creator_id' => rand(1, 10000000),
    ];
});

$factory->define(App\Models\QualityTodoList::class, function (Faker\Generator $faker) {
    return [
        'schedule_id' => $faker->numberBetween(0, 10000),
        'item_id' => $faker->numberBetween(0, 10000),
        'code' => '123456',
        'creator_id' => 1,
        'schedule_type' => 'daily',
        'organization_id' => $faker->numberBetween(0, 10000),
        'status' => 'passed',
        'executor_ids' => [$faker->numberBetween(0, 10000)],
        'data' => [
            'using_department_id' => 86,
            'parts' => [
                ['name' => '10个球管，总价100元'],
                ['name' => '-个呼吸阀，总价-元']
            ]
        ],
        'created_at' => \Carbon\Carbon::createFromDate(2017, 11, 11)
    ];
});

$factory->define(App\Models\TicketFee::class, function (Faker\Generator $faker) {
    return [
        'ticket_id'     => $faker->numberBetween(1, 200),
        'type'          => 'accessory',
        'data'          => [],
        'total'         => 10,
        'is_deleted'    => false,
    ];
});
