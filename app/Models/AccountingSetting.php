<?php
/**
 * MediNexa - credentials for an external accounting API.
 *
 * One row. The model shipped empty, with no table name, no fillable list and no
 * migration behind it, so the accounting page could neither read it nor build a
 * blank one to render the form.
 *
 * MediNexa Healthcare Platform
 * MediNexa - AI-Powered Healthcare Platform
 * Proprietary BCA Major Project.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AccountingSetting extends Model
{
    protected $table = 'accounting_settings';

    protected $fillable = [
        'company_id',
        'api_link',
        'username',
        'password',
    ];

    /**
     * The API password is a secret, keep it out of arrays and JSON.
     */
    protected $hidden = [
        'password',
    ];
}
