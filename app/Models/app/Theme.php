<?php
namespace App\Models\app;
use App\Models\BaseModel;
class Theme extends BaseModel
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'app_theme';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    public function effect_img()
    {
        return $this->hasMany('App\Models\app\Themeeffect');
    }
}