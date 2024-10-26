<?php
namespace App\Services;

use App\Repositories\MenuRepository;
use Yajra\DataTables\DataTables;
use App\Models\Menu;

class MenuService
{
    public $menuRepositories;

    public function __construct(MenuRepository $menuRepositories){
        $this->menuRepositories = $menuRepositories;
    }

    public function getMenuDatatable($request = null)
    {
        if ($request->ajax()) {
            $query = Menu::query();

            // Check for search input
            if ($request->has('search') && !empty($request->search)) {
                $query->where('name', 'like', '%' . $request->search['value'] . '%');
            }

            return DataTables::of($query)
                ->addColumn('action', function ($row) {
                    return '<a href="' . route('menu.edit', $row->id) . '" class="btn btn-sm btn-primary">Edit</a>
                            <form action="' . route('menu.delete', $row->id) . '" method="POST" style="display:inline;">
                                ' . csrf_field() . '
                                ' . method_field('DELETE') . '
                                <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                            </form>';
                })
                ->rawColumns(['action'])
                ->make(true);
        }
    }

    public function createMenu($request)
    {
        $request->validate([
            'name' => 'required|unique:menus,name',
            'url' => 'required|url',
            'order_number' => 'required|integer',
            'is_active' => 'required'
        ]);

        $attributes = [];
        $attributes['name'] = $request->name ?? NULL;
        $attributes['parent_id'] = $request->parent_id ?? NULL;
        $attributes['url'] = $request->url ?? NULL;
        $attributes['order_number'] = $request->order_number ?? NULL;
        $attributes['is_active'] = $request->is_active ?? NULL;

        return $this->menuRepositories->create($attributes);
    }

    public function updateMenu($request, $id)
    {
        $menuData = $this->menuRepositories->getMenuById($id);

        $request->validate([
            'name' => 'required|unique:menus,name,'.$menuData->id,
            'url' => 'required|url',
            'order_number' => 'required|integer',
        ]);

        $attributes = [];
        $attributes['name'] = $request->name ?? NULL;
        $attributes['parent_id'] = $request->parent_id ?? NULL;
        $attributes['url'] = $request->url ?? NULL;
        $attributes['order_number'] = $request->order_number ?? NULL;
        $attributes['is_active'] = $request->is_active ?? NULL;

        $menuData->update($attributes);

        //create in history table record
        $historyAttributes = [];
        $historyAttributes['menu_id'] = $id;
        $historyAttributes['old_menu_data'] = json_encode($menuData);
        $historyAttributes['new_menu_data'] = json_encode($attributes);
        $this->menuRepositories->saveRecoreds($historyAttributes);
       
    }

    public function showMenu() {
        return $this->menuRepositories->getMenuOrderBy();
        
    }
}
