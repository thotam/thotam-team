<?php

namespace Thotam\ThotamTeam\Http\Controllers;

use Auth;
use Illuminate\Http\Request;
use Thotam\ThotamHr\Models\HR;
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

    /**
     * quanly_select
     *
     * @param  mixed $request
     * @return void
     */
    public function quanly_select(Request $request)
    {
        $hrs = HR::whereNull('deleted_by');

        if (!!$request->search) {
            $hrs->where(function($query) use ($request) {
                $query->where('key', 'like', "%" . $request->search . "%")
                      ->orWhere('hoten', 'like', "%" . $request->search . "%");
            })->select('key', 'hoten');
        }

        $response['total_count'] = $hrs->count();

        if (!!$request->perPage) {
            $hrs->limit($request->perPage);

            if (!!$request->page) {
                $hrs->offset(($request->page - 1) * $request->perPage);
            }
        }

        $response_data = [];

        foreach ($hrs->get() as $hr) {
            $response_data[] = [
                "id" => $hr->key,
                "text" => '[' . $hr->key . ']' . $hr->hoten,
            ];
        }

        $response['data'] = $response_data;

        return collect($response)->toJson(JSON_PRETTY_PRINT);
    }
}
