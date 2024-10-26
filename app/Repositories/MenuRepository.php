<?php

namespace App\Repositories;
use App\Models\Menu;
use App\Models\MenuHistory;
use DB;

class MenuRepository {

    protected $menu;
    protected $menuHistory;

    public function __construct(Menu $menu, MenuHistory $menuHistory){
        $this->menu = $menu;
        $this->menuHistory = $menuHistory;
    }

    public function create($attributes){
        return $this->menu->create($attributes);
    }

    public function getMenu() {
        return $this->menu->with('parent:id,name')->get();
    }
    public function getMenuToArray() {
        return $this->menu->get()->toArray();
    }
    public function getMenuById($id) {
        return $this->menu->where('id', $id)->first();
        
    }
    public function getParents() {
        return $this->menu->whereNull('parent_id')->get()->toArray();
    }
    public function saveRecoreds($historyAttributes) 
    {
        return $this->menuHistory->create($historyAttributes);
    }
    public function deleteMenu($id) {
        $deleteRecord = $this->getMenuById($id);

        return $deleteRecord->delete();
    }
    public function getMenuOrderBy() {
        return $this->menu->whereNull('parent_id') // Fetch only parent menus
        ->with(['subMenus', 'subMenus.allSubMenus']) // Eager load sub-menus and their child menus
        ->get()
        ->toArray(); // Convert to an array
    }
}