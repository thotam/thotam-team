<?php

namespace Thotam\ThotamTeam\Http\Controllers;

use Auth;
use Illuminate\Http\Request;
use Thotam\ThotamTeam\DataTables\NhomDataTable;

class TeamController extends Controller
{
    /**
     * index
     *
     * @return void
     */
    public function index(NhomDataTable $dataTable)
    {
        if (Auth::user()->hr->hasAnyPermission(["view-team", "add-team", "edit-team", "delete-team", "set-member-team"])) {
            return $dataTable->render('thotam-team::team', ['title' => 'Quản lý Nhóm']);
        } else {
            return view('errors.dynamic', [
                'error_code' => '403',
                'error_description' => 'Không có quyền truy cập',
                'title' => 'Quản lý Nhóm',
            ]);
        }
    }
}
