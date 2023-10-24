<?php
namespace App\Http\Controllers\User;
use Exception;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;


class DashboardController extends Controller
{
    public function index()
    {
        $page_title = "Dashboard";
        return view('user.dashboard',compact("page_title"));
    }

    public function logout(Request $request) {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('index');
    }
    /**
     * 
     */
    public function deleteAccount(Request $request) {
        $validator = Validator::make($request->all(),[
            'target'        => 'required',
        ]);
        $validated = $validator->validate();
        $user = auth()->user();
        try{
            $user->status = 0;
            $user->save();
            Auth::logout();
            return redirect()->route('index')->with(['success' => ['Your account deleted successfully!']]);
        }catch(Exception $e) {
            return back()->with(['error' => ['Something went worng! Please try again.']]);
        }
    }
}
