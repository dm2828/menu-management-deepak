<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    use HasFactory;

    protected $table = "menus";

    protected $guarded = [];

    public function parent(){
        return $this->belongsTo(Menu::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(Menu::class, 'parent_id');
    }

    // Sub-menu relationship
    public function subMenus()
    {
        return $this->hasMany(Menu::class, 'parent_id');
    }

    // Recursive relationship for child menus
    public function allSubMenus()
    {
        return $this->hasMany(Menu::class, 'parent_id')->with('allSubMenus'); // Load child menus recursively
    }
    
    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($menu) {
            // Automatically delete associated child menus when a menu is deleted
            $menu->children()->delete();
        });
    }
}
