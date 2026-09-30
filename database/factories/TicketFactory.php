<?php

namespace Database\Factories;

use App\Models\Tenant;
use App\Models\TicketRequest;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class TicketFactory extends Factory
{
    public function definition(): array
    {
        return [
            'tenant_id' => Tenant::factory(),
            'request_id' => TicketRequest::factory(),
            'created_by' => User::factory(),
            'assigned_to' => null,
            'title' => fake()->sentence(4),
            'description' => fake()->paragraph(),
            'priority' => 'medium',
            'status' => 'open',
        ];
    }
}