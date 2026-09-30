<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Tenant extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
    ];

    /* employee relationships */
    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    /* ticket request relationship */
    public function ticketRequests(): HasMany
    {
        return $this->hasMany(TicketRequest::class);
    }

    /* ticket relationship */
    public function tickets(): HasMany
    {
        return $this->hasMany(Ticket::class);
    }
}