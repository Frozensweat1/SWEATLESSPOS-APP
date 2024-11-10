<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasFactory;

    // Specify the table associated with this model (optional if it follows naming conventions)
    protected $table = 'settings';

    // Define which attributes are mass assignable
    protected $fillable = [
        'site_name',
        'currency',
        'tax_rate',
        'contact_email',
        'contact_phone',
        'address',
        'receipt_header',
        'receipt_footer',
        'invoice_prefix',
        'auto_print_receipt',
        'theme_color',
        'logo',
        'favicon',
        'background_img1',
        'background_img2',
        'currency_symbol',
        'enable_two_factor_auth',
        'require_password_change',
        'track_inventory',
        'low_stock_threshold',
        'enable_expiry_tracking',
    ];

    /**
     * Retrieve the logo URL or path for the setting.
     *
     * @return string|null
     */
    public function getLogoUrlAttribute()
    {
        return $this->logo ? asset('storage/' . $this->logo) : null;
    }

    /**
     * Retrieve the favicon URL or path for the setting.
     *
     * @return string|null
     */
    public function getFaviconUrlAttribute()
    {
        return $this->favicon ? asset('storage/' . $this->favicon) : null;
    }

    /**
     * Retrieve the background image 1 URL or path for the setting.
     *
     * @return string|null
     */
    public function getBackgroundImg1UrlAttribute()
    {
        return $this->background_img1 ? asset('storage/' . $this->background_img1) : null;
    }

    /**
     * Retrieve the background image 2 URL or path for the setting.
     *
     * @return string|null
     */
    public function getBackgroundImg2UrlAttribute()
    {
        return $this->background_img2 ? asset('storage/' . $this->background_img2) : null;
    }
}
