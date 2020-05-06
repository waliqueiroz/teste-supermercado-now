<?php

namespace App\Services;

use App\Models\Status;
use Illuminate\Support\Facades\Storage;

class StatusService
{
    public function index()
    {
        $statuses = Status::all();
        return $statuses;
    }
}
