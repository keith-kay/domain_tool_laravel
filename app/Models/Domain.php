<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Domain extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'company_id', 'expiry_date', 'registration_date', 'registrar_name'];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }
}
