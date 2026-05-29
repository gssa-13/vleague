<?php

// app/Models/ExpenseCategory.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class ExpenseCategory extends BaseModel
{
    use HasFactory;

    protected $fillable = ['legacy_id', 'name'];
}
