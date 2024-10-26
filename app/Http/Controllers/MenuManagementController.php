<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\MenuRepository;
use App\Services\MenuService;

class MenuManagementController extends Controller
{
    protected $menuRepositories;
    protected $menuServices;

    public function __construct(MenuRepository $menuRepositories, MenuService $menuServices){
        $this->menuRepositories = $menuRepositories;
        $this->menuServices = $menuServices;
    }

    public function index(Request $request){
        $menuList = $this->menuRepositories->getMenu();
        return view('menu.list')->with(compact('menuList'));
    }

    public function getData(Request $request)
    {
        return $this->menuServices->getMenuDatatable($request);
        
    }

    public function create(Request $request){
        $parentMenu = $this->menuRepositories->getMenuToArray();
        return view('menu.create')->with(compact('parentMenu'));
    }

    public function store(Request $request){

        $this->menuServices->createMenu($request);

        return redirect(route('menu.list', absolute: false));
    }

    public function display(){
        $nestedMenu = $this->menuServices->showMenu();
        //dd($nestedMenu);
        return view('menu.display')->with(compact('nestedMenu'));
    }

    public function destroy($id){
        $this->menuRepositories->deleteMenu($id);
        return redirect(route('menu.list', absolute: false));
    }

    public function edit(Request $request, $id){
        $menuData = $this->menuRepositories->getMenuById($id);
        $parentMenu = $this->menuRepositories->getParents();
        return view('menu.edit')->with(compact('parentMenu','menuData'));
    }

    public function update(Request $request, $id){
        $this->menuServices->updateMenu($request, $id);
        return redirect(route('menu.list', absolute: false));
    }
}
