<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Badge extends Model
{
    protected $table = 'badges';
    protected $fillable = ['user_id', 'rfid', 'status'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function setActiveRfid()
    {
        DB::transaction(function () {
            // Update all other RFIDs associated with the same user to 'inactive'
            Badge::where('user_id', $this->user_id)
                ->where('id', '!=', $this->id)
                ->update(['status' => 'inactive']);

            // Set the current RFID's status to 'active'
            $this->status = 'active';
            $this->save();
        });
    }
}
