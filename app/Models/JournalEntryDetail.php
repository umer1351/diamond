<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JournalEntryDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'journal_entry_id',
        'explanation',
        'bill_no',
        'check_no',
        'check_date',
        'credit',
        'debit',
        'currency',
        'doc_date',
        'account_id',
        'amount',
        'createdBy',
        'created_at',
        'updated_at',
        'amount_in_words',
        'account_code'
    ];
    public function journal_entry()
    {
        return $this->belongsTo(JournalEntry::class, 'journal_entry_id');
    }
    public function account_name()
    {
        return $this->belongsTo(Account::class, 'account_id');
    }
}
