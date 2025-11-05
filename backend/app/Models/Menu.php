<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
class Menu extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'name',
        'url',
        'parent_id',
        'order',
        'is_active',
    ];
    public function children()
    {
        return $this->hasMany(Menu::class, 'parent_id', 'id')
                    ->with('children') // recursivo (subniveles)
                    ->orderBy('order');
    }
 
    public function parent()
    {
        return $this->belongsTo(Menu::class, 'parent_id');
    }
    public static function createOrUpdateMenu($request)
    {
        $order  = 1;
        if($request->parent_id) {
            $order = Menu::where('parent_id', $request->parent_id)->max('order') + 1;
        }
        if ($request->id) {
            $menu = self::find($request->id);   
            $menu->user_updated = auth()->user()->id;
        } else {  
            $menu = new Menu;
            $menu->order = $order;
            $menu->is_active = true;
            $menu->user_created = auth()->user()->id;
            $menu->user_updated = auth()->user()->id;
        }
        $menu->parent_id = $request->parent_id ?? null;
        $menu->order = $order;
        $menu->name = $request->name;
        $menu->url = $request->url;
        $menu->save();
    }
        public function organizations()
    {
        return $this->belongsToMany(Organization::class, 'organization_menu')
                    ->withTimestamps();
    }
}
