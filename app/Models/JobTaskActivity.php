<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobTaskActivity extends Model
{
    use HasFactory;
    protected $fillable = [
        'id',
        'job_task_id',
        'category',
        'design_no',
        'weight',
        'picture',
        'description',
        'is_deleted',
        'createdby_id',
        'deletedby_id',
        'created_at',
        'updated_at',
    ];
    protected $dates = [
        'updated_at',
        'created_at'
    ];
    public function job_task()
    {
        return $this->belongsTo(JobTask::class, 'job_task_id');
    }

    public function created_by()
    {
        return $this->belongsTo(User::class, 'createdby_id');
    }
}
