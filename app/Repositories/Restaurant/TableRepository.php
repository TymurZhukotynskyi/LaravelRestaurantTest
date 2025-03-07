<?php

namespace App\Repositories\Restaurant;

use App\Models\Restaurant\Table;
use Illuminate\Database\Eloquent\Collection;

class TableRepository
{
    public function getAll(): Collection
    {
        return Table::query()->get();
    }
}
