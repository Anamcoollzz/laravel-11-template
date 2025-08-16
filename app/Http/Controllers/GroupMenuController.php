<?php

namespace App\Http\Controllers;

use App\Http\Requests\GroupMenuRequest;
use App\Models\MenuGroup;
use App\Repositories\MenuGroupRepository;
use Illuminate\Http\Response;

class GroupMenuController extends StislaController
{

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

        $this->icon                = 'fa fa-bars';
        $this->menuGroupRepository = new MenuGroupRepository;
        $this->viewFolder          = 'group-menus';

        $this->defaultMiddleware('Grup Menu');
    }

    /**
     * showing menu page
     *
     * @return Response
     */
    public function index()
    {
        $data = $this->menuGroupRepository->getLatest();

        $defaultData = $this->getDefaultDataIndex(__('Grup Menu'), 'Grup Menu', 'group-menus');

        return view('stisla.group-menus.index', array_merge($defaultData, [
            'data'    => $data,
        ]));
    }

    /**
     * showing add new menu page
     *
     * @return Response
     */
    public function create()
    {
        $title         = __('Grup Menu');
        $fullTitle     = __('Tambah Grup Menu');
        $defaultData   = $this->getDefaultDataCreate($title, 'group-menus');

        return view('stisla.group-menus.form', array_merge($defaultData, [
            'fullTitle'     => $fullTitle,
        ]));
    }

    /**
     * save new group menu to db
     *
     * @param GroupMenuRequest $request
     * @return Response
     */
    public function store(GroupMenuRequest $request)
    {
        $data = $request->only([
            'group_name',
        ]);

        $result = $this->menuGroupRepository->create($data);
        logCreate("Grup Menu", $result);
        $successMessage = successMessageCreate("Grup Menu");
        return backSuccess($successMessage);
    }

    /**
     * get detail data
     *
     * @param MenuGroup $groupMenu
     * @param bool $isDetail
     * @return array
     */
    protected function getDetailDataOld(MenuGroup $groupMenu, bool $isDetail = false)
    {
        $title         = __('Grup Menu');
        $defaultData   = $this->getDefaultDataDetail($title, 'group-menus', $groupMenu, $isDetail);

        return array_merge($defaultData, [
            'title'     => $title,
            'fullTitle' => $isDetail ? __('Detail Grup Menu') : __('Ubah Grup Menu'),
        ]);
    }

    /**
     * showing edit group menu page
     *
     * @param MenuGroup $groupMenu
     * @return Response
     */
    public function edit(MenuGroup $groupMenu)
    {
        $data = $this->getDetailDataOld($groupMenu);
        return view('stisla.group-menus.form', $data);
    }

    /**
     * update data to db
     *
     * @param GroupMenuRequest $request
     * @param MenuGroup $groupMenu
     * @return Response
     */
    public function update(GroupMenuRequest $request, MenuGroup $groupMenu)
    {
        $data = $request->only([
            'group_name',
        ]);

        $newData = $this->menuGroupRepository->update($data, $groupMenu->id);
        logUpdate("Grup Menu", $groupMenu, $newData);
        $successMessage = successMessageUpdate("Grup Menu");
        return backSuccess($successMessage);
    }

    /**
     * showing detail group menu page
     *
     * @param MenuGroup $groupMenu
     * @return Response
     */
    public function show(MenuGroup $groupMenu)
    {
        $data = $this->getDetailDataOld($groupMenu, true);
        return view('stisla.group-menus.form', $data);
    }

    /**
     * delete group menu from db
     *
     * @param MenuGroup $groupMenu
     * @return Response
     */
    public function destroy(MenuGroup $groupMenu)
    {
        $this->menuGroupRepository->delete($groupMenu->id);
        logDelete("Grup Menu", $groupMenu);
        $successMessage = successMessageDelete("Grup Menu");
        return backSuccess($successMessage);
    }
}
