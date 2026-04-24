<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Loan extends Model {
    protected $guarded = [];

        protected $casts = [
        'due_date' => 'date',
        'release_date' => 'date',
    ];


    public function borrower() {
        return $this->belongsTo(Borrower::class);
    }

    public function payments() {
        return $this->hasMany(Payment::class);
    }
}
