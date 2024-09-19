<?php

namespace App\Models;

use App\Traits\UuidTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserNonActive extends Model
{
    use HasFactory, UuidTrait;
    public $incrementing = false;
    protected $table = 'usernonactive';
    protected $fillable = [
        'user_id',
        'tanggal_non_active',
        'alasan'
    ];

    public function User(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
