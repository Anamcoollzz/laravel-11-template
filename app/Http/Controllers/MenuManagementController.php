<?php

namespace App\Http\Controllers;

use App\Http\Requests\MenuRequest;
use App\Models\Menu;
use App\Repositories\MenuGroupRepository;
use App\Repositories\MenuRepository;
use Illuminate\Http\Response;

class MenuManagementController extends StislaController
{

    /**
     * menu repository
     *
     * @var MenuRepository
     */
    private MenuRepository $menuRepository;

    /**
     * menu group repository
     *
     * @var MenuGroupRepository
     */
    private MenuGroupRepository $menuGroupRepository;

    /**
     * constructor method
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();

        $this->icon                   = 'fa fa-bars';
        $this->prefixRoute            = 'menu-managements';
        $this->viewFolder             = 'menu-managements';
        $this->paperSize              = 'Legal';

        $this->importExcelExamplePath = public_path('excel_examples/sample_menus.xlsx');

        $this->menuRepository         = new MenuRepository;
        $this->menuGroupRepository    = new MenuGroupRepository;

        $this->defaultMiddleware('Menu');
    }

    /**
     * get index data
     *
     * @return array
     */
    protected function getIndexData()
    {
        $data = $this->menuRepository->getFullData();
        $defaultData = $this->getDefaultDataIndex(__('Menu'), 'Menu', 'menu-managements');
        $data        = array_merge(['data' => $data], $defaultData);
        return $data;
    }

    /**
     * showing menu page
     *
     * @return Response
     */
    public function index()
    {
        $data = $this->getIndexData();
        return view('stisla.menu-managements.index', $data);
    }

    /**
     * showing add new menu page
     *
     * @return Response
     */
    public function create()
    {
        $title         = __('Menu');
        $fullTitle     = __('Tambah Menu');
        $groupOptions  = $this->menuGroupRepository->getSelectOptions('group_name', 'id');
        $parentOptions = $this->menuRepository->getSelectOptions('menu_name', 'id');
        $defaultData   = $this->getDefaultDataCreate($title, 'menu-managements');

        $parentOptions[''] = __('Tidak Ada');

        return view('stisla.menu-managements.form', array_merge($defaultData, [
            'fullTitle'     => $fullTitle,
            'groupOptions'  => $groupOptions,
            'parentOptions' => $parentOptions,
        ]));
    }

    /**
     * save new menu to db
     *
     * @param MenuRequest $request
     * @return Response
     */
    public function store(MenuRequest $request)
    {
        $data = $request->only([
            'menu_name',
            'route_name',
            'icon',
            'parent_menu_id',
            'permission',
            'is_active_if_url_includes',
            'is_blank',
            'uri',
            'menu_group_id',
        ]);
        $result = $this->menuRepository->create($data);

        logCreate("Manajemen Menu", $result);

        $successMessage = successMessageCreate("Menu");
        return backSuccess($successMessage);
    }

    /**
     * get detail data
     *
     * @param Menu $menuManagement
     * @param bool $isDetail
     * @return array
     */
    protected function getDetailDataOld(Menu $menuManagement, bool $isDetail = false)
    {
        $title         = __('Manajemen Menu');
        $defaultData   = $this->getDefaultDataDetail($title, 'menu-managements', $menuManagement, $isDetail);
        $groupOptions  = $this->menuGroupRepository->getSelectOptions('group_name', 'id');
        $parentOptions = $this->menuRepository->getSelectOptions('menu_name', 'id');

        $parentOptions[''] = __('Tidak Ada');
        return array_merge($defaultData, [
            'title'         => $title,
            'fullTitle'     => $isDetail ? __('Detail Menu') : __('Ubah Menu'),
            'groupOptions'  => $groupOptions,
            'parentOptions' => $parentOptions,
        ]);
    }

    /**
     * showing edit menu page
     *
     * @param Menu $menuManagement
     * @return Response
     */
    public function edit(Menu $menuManagement)
    {
        $data = $this->getDetailDataOld($menuManagement);
        return view('stisla.menu-managements.form', $data);
    }

    /**
     * update data to db
     *
     * @param MenuRequest $request
     * @param Menu $menuManagement
     * @return Response
     */
    public function update(MenuRequest $request, Menu $menuManagement)
    {
        $data = $request->only([
            'menu_name',
            'route_name',
            'icon',
            'parent_menu_id',
            'permission',
            'is_active_if_url_includes',
            'is_blank',
            'uri',
            'menu_group_id',
        ]);
        $newData = $this->menuRepository->update($data, $menuManagement->id);

        logUpdate("Manajemen Menu", $menuManagement, $newData);

        $successMessage = successMessageUpdate("Menu");
        return backSuccess($successMessage);
    }

    /**
     * showing detail menu page
     *
     * @param Menu $menuManagement
     * @return Response
     */
    public function show(Menu $menuManagement)
    {
        $data = $this->getDetailDataOld($menuManagement, true);
        return view('stisla.menu-managements.form', $data);
    }

    /**
     * delete menu from db
     *
     * @param Menu $menuManagement
     * @return Response
     */
    public function destroy(Menu $menuManagement)
    {
        $this->menuRepository->delete($menuManagement->id);

        logDelete("Manajemen Menu", $menuManagement);

        $successMessage = successMessageDelete("Menu");
        return backSuccess($successMessage);
    }
}
