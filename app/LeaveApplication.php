<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LeaveApplication extends Model
{
    use SoftDeletes;

    protected $dates = ['deleted_at'];
    protected $table = 'leave_application';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'type', 'startDate', 'user_id', 'endDate', 'reliever', 'leave_days', 'releiver_approval', 'HOD', 'HR', 'MD'
    ];

    public function user()
    {
        //return $this->belongsTo(User::class, 'userD');
        return $this->belongsTo(User::class);
    }

    public function reason()
    {
        return $this->hasOne(Reason::class);
    }
}
