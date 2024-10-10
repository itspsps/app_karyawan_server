<?php

namespace App\Models;

use App\Traits\UuidTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class KaryawanNonActive extends Model
{
    use HasFactory, UuidTrait;
    public $incrementing = false;
    protected $table = 'karyawan_nonactive';
    protected $fillable = [
        'karyawan_id',
        'tanggal_non_active',
        'alasan'
    ];

    public function Karyawan(): BelongsTo
    {
        return $this->belongsTo(Karyawan::class, 'karyawan_id', 'id');
    }
}
