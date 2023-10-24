<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\SetupPage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Http\Helpers\Response;
use Exception;

class SetupPagesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $page_title = "Setup Pages";
        $setup_pages = SetupPage::get();
        return view('admin.sections.setup-pages.index',compact(
            'page_title',
            'setup_pages',
        ));
    }

    public function statusUpdate(Request $request) {
        $validator = Validator::make($request->all(),[
            'status'                    => 'required|boolean',
            'data_target'               => 'required|string',
        ]);
        if ($validator->stopOnFirstFailure()->fails()) {
            $error = ['error' => $validator->errors()];
            return Response::error($error,null,400);
        }
        $validated = $validator->safe()->all();
        $page_slug = $validated['data_target'];

        $page = SetupPage::where('slug',$page_slug)->first();
        if(!$page) {
            $error = ['error' => ['Page not found!']];
            return Response::error($error,null,404);
        }

        try{
            $page->update([
                'status' => ($validated['status'] == true) ? false : true,
            ]);
        }catch(Exception $e) {
            return $e;
            $error = ['error' => ['Something went wrong!. Please try again.']];
            return Response::error($error,null,500);
        }

        $success = ['success' => ['Setup Page status updated successfully!']];
        return Response::success($success,null,200);
    }
}
