<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Spatie\Dns\Dns;

class Project extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'custom_domain',
    ];

    /**
     * Return CNAME record of the custom domain.
     *
     * @return string|null
     */
    public function cname(): null|string
    {
        if (!$this->custom_domain) return null;

        return Cache::remember($this->custom_domain, 10, function () {
            $dns = new Dns();
            $records = $dns->getRecords($this->custom_domain, 'CNAME');

            if (!$records) return null;

            return $records[0]->target();
        });
    }
}
