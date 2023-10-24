<?php

namespace App\Http\Controllers\Admin;

use Exception;
use Illuminate\Http\Request;
use App\Models\ContactRequest;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Notification;
use App\Notifications\websiteSubscribeNotification;

class ContactMessageController extends Controller
{
    public function index(){
        $page_title       = "Contact Messages";
        $contact_requests = ContactRequest::orderByDesc("id")->paginate(10);
        return view('admin.sections.contact.index',compact(
            'page_title',
            'contact_requests'
        ));
    }
    public function reply(Request $request){
        $validator = Validator::make($request->all(),[
            'target'        => "required|integer|exists:contact_requests,id",
            'subject'       => "required|string|max:255",
            'message'       => "required|string|max:3000",
        ]);

        if($validator->fails()) return back()->withErrors($validator)->withInput()->with('modal','send-reply');
        $validated       = $validator->validate();
        $contact_request = ContactRequest::find($validated['target']);

        try{
            Notification::route("mail",$contact_request->email)->notify(new websiteSubscribeNotification($validated));
            $contact_request->update([
                'reply' => true,
            ]);
        }catch(Exception $e) {
            return back()->with(['error' => ['Something went wrong! Please try again']]);
        }
        return back()->with(['success' => ['Reply sended successfully!']]);
    }
}
