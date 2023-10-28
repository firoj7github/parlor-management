<?php

namespace App\Http\Controllers\Frontend;

use Exception;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\ContactRequest;
use App\Models\Admin\SiteSections;
use App\Constants\SiteSectionConst;
use App\Http\Controllers\Controller;
use App\Models\Admin\Area;
use App\Models\Admin\Blog;
use App\Models\Admin\BlogCategory;
use App\Models\Admin\ParlourList;
use App\Models\Admin\ServiceType;
use App\Models\Admin\UsefullLink;
use Illuminate\Support\Facades\Validator;

class SiteController extends Controller
{
    /**
     * Method for view index page
     */
    public function index(){
        $slider_slug            = Str::slug(SiteSectionConst::SLIDER_SECTION);
        $sliders                = SiteSections::getData($slider_slug)->first();
        $areas                  = Area::where('status',true)->get();
        $parlour_lists          = ParlourList::where('status',true)->latest()->take(4)->get();
        $how_its_work_slug      = Str::slug(SiteSectionConst::HOW_ITS_WORK_SECTION);
        $how_its_work           = SiteSections::getData($how_its_work_slug)->first();
        $testimonial_slug       = Str::slug(SiteSectionConst::TESTIMONIAL_SECTION);
        $testimonial            = SiteSections::getData($testimonial_slug)->first();
        $statistic_slug         = Str::slug(SiteSectionConst::STATISTIC_SECTION);
        $statistic              = SiteSections::getData($statistic_slug)->first();
        $photo_gallery_slug     = Str::slug(SiteSectionConst::PHOTO_GALLERY_SECTION);
        $photo_gallery          = SiteSections::getData($photo_gallery_slug)->first();
        $download_app_slug      = Str::slug(SiteSectionConst::DOWNLOAD_APP_SECTION);
        $download_app           = SiteSections::getData($download_app_slug)->first();
        $footer_slug            = Str::slug(SiteSectionConst::FOOTER_SECTION);
        $footer                 = SiteSections::getData($footer_slug)->first();
        $usefull_links          = UsefullLink::where('status',true)->get();
        $contact_slug           = Str::slug(SiteSectionConst::CONTACT_SECTION);
        $contact                = SiteSections::getData($contact_slug)->first();

        return view('frontend.index',compact(
            'sliders',
            'areas',
            'parlour_lists',
            'how_its_work',
            'testimonial',
            'statistic',
            'photo_gallery',
            'download_app',
            'footer',
            'usefull_links',
            'contact'
        ));
    }
    /**
     * Method for view the find parlour page
     * @return view
     */
    public function findParlour(){
        $page_title             = "| Find Parlour";
        $areas                  = Area::where('status',true)->get();
        $parlour_lists          = ParlourList::where('status',true)->inRandomOrder()->get();
        $footer_slug            = Str::slug(SiteSectionConst::FOOTER_SECTION);
        $footer                 = SiteSections::getData($footer_slug)->first();
        $usefull_links          = UsefullLink::where('status',true)->get();
        $contact_slug           = Str::slug(SiteSectionConst::CONTACT_SECTION);
        $contact                = SiteSections::getData($contact_slug)->first();
        return view('frontend.pages.find-parlour',compact(
            'page_title',
            'areas',
            'parlour_lists',
            'footer',
            'usefull_links',
            'contact'
        ));
    }
    /**
     * Method for search doctor
     * @param string $slug
     * @param \Illuminate\Http\Request  $request
    */
    public function searchParlour(Request $request){
      
        $page_title             = "| Find Parlour";
        $areas                  = Area::where('status',true)->get();
        $footer_slug            = Str::slug(SiteSectionConst::FOOTER_SECTION);
        $footer                 = SiteSections::getData($footer_slug)->first();
        $usefull_links          = UsefullLink::where('status',true)->get();
        $contact_slug           = Str::slug(SiteSectionConst::CONTACT_SECTION);
        $contact                = SiteSections::getData($contact_slug)->first();

        $validator = Validator::make($request->all(),[
            'area'          => 'nullable',
            'name'       => 'nullable',
        ]);
        if($validator->fails()) {
            return back()->with(['error' => ['Something went wrong! Please try again.']]);
        }
        if($request->area && $request->name ){
            
            $parlour_lists    = ParlourList::where('area_id',$request->area)->where('name','like','%'.$request->name.'%')->get(); 
            
        }else if($request->area){
            $parlour_lists    = ParlourList::where('area_id',$request->area)->get();
        }
        else {
            $parlour_lists    = ParlourList::where('name','like','%'.$request->name.'%')->get();
        }
        $areaString     = $request->area;
        $nameString     = $request->name;
        
        return view('frontend.pages.find-parlour',compact(
            'page_title',
            'page_title',
            'areas',
            'areaString',
            'nameString',
            'parlour_lists',
            'footer',
            'usefull_links',
            'contact'
        ));     
    }
    /**
     * Method for view the about page
     * @return view
     */
    public function about(){
        $page_title             = "| About";
        $about_slug             = Str::slug(SiteSectionConst::ABOUT_SECTION);
        $about                  = SiteSections::getData($about_slug)->first();
        $faq_slug               = Str::slug(SiteSectionConst::FAQ_SECTION);
        $faq                    = SiteSections::getData($faq_slug)->first();
        $footer_slug            = Str::slug(SiteSectionConst::FOOTER_SECTION);
        $footer                 = SiteSections::getData($footer_slug)->first();
        $usefull_links          = UsefullLink::where('status',true)->get();
        $contact_slug           = Str::slug(SiteSectionConst::CONTACT_SECTION);
        $contact                = SiteSections::getData($contact_slug)->first();
        return view('frontend.pages.about',compact(
            'page_title',
            'about',
            'faq',
            'footer',
            'usefull_links',
            'contact'
        ));
    }
    /**
     * Method for view the about page
     * @return view
     */
    public function service(){
        $page_title             = "| Services";
        $service_slug           = Str::slug(SiteSectionConst::SERVICE_SECTION);
        $service                = SiteSections::getData($service_slug)->first();
        $footer_slug            = Str::slug(SiteSectionConst::FOOTER_SECTION);
        $footer                 = SiteSections::getData($footer_slug)->first();
        $usefull_links          = UsefullLink::where('status',true)->get();
        $contact_slug           = Str::slug(SiteSectionConst::CONTACT_SECTION);
        $contact                = SiteSections::getData($contact_slug)->first();
        return view('frontend.pages.service',compact(
            'page_title',
            'service',
            'footer',
            'usefull_links',
            'contact'
        ));
    }
    /**
     * Method for view the about page
     * @return view
     */
    public function blog(){
        $page_title             = "| Blogs";
        $blog_slug              = Str::slug(SiteSectionConst::BLOG_SECTION);
        $blog                   = SiteSections::getData($blog_slug)->first();
        $blogs                  = Blog::where('status',true)->paginate(6);
        $footer_slug            = Str::slug(SiteSectionConst::FOOTER_SECTION);
        $footer                 = SiteSections::getData($footer_slug)->first();
        $usefull_links          = UsefullLink::where('status',true)->get();
        $contact_slug           = Str::slug(SiteSectionConst::CONTACT_SECTION);
        $contact                = SiteSections::getData($contact_slug)->first();
        return view('frontend.pages.blog',compact(
            'page_title',
            'blog',
            'blogs',
            'footer',
            'usefull_links',
            'contact'
        ));
    }
    /**
     * Method for show the blog details page
     * @param string $slug
     * @param \Illuminate\Http\Request $request
     */
    public function blogDetails($slug){
        $page_title             = "| Blog Details";
        $blog                   = Blog::where('slug',$slug)->first();
        if(!$blog) abort(404);
        $category               = BlogCategory::withCount('blog')->get();
        $recent_posts           = Blog::where('status',true)->where('slug','!=',$slug)->get();
        $footer_slug            = Str::slug(SiteSectionConst::FOOTER_SECTION);
        $footer                 = SiteSections::getData($footer_slug)->first();
        $usefull_links          = UsefullLink::where('status',true)->get();
        $contact_slug           = Str::slug(SiteSectionConst::CONTACT_SECTION);
        $contact                = SiteSections::getData($contact_slug)->first();

        return view('frontend.pages.blog-details',compact(
            'page_title',
            'blog',
            'category',
            'recent_posts',
            'footer',
            'usefull_links',
            'contact'
        ));

    }
    /**
     * Method for view the about page
     * @return view
     */
    public function contact(){
        $page_title             = "| Contact";
        $footer_slug            = Str::slug(SiteSectionConst::FOOTER_SECTION);
        $footer                 = SiteSections::getData($footer_slug)->first();
        $usefull_links          = UsefullLink::where('status',true)->get();
        $contact_slug           = Str::slug(SiteSectionConst::CONTACT_SECTION);
        $contact                = SiteSections::getData($contact_slug)->first();

        return view('frontend.pages.contact',compact(
            'page_title',
            'footer',
            'usefull_links',
            'contact'
        ));
    }
    /**
     * Method for contact request
     * @param string
     * @param \Illuminate\Http\Request $request
     */
    public function contactRequest(Request $request) {

        $validator        = Validator::make($request->all(),[
            'name'        => "required|string|max:255|unique:contact_requests",
            'email'       => "required|string|email|max:255|unique:contact_requests",
            'message'     => "required|string|max:5000",
        ]);
        if($validator->fails()) return back()->withErrors($validator)->withInput();
        $validated = $validator->validate();
        try{
            ContactRequest::create([
                'name'            => $validated['name'],
                'email'           => $validated['email'],
                'message'         => $validated['message'],
                'created_at'      => now(),
            ]);
        }catch(Exception $e) {
            return back()->with(['error' => ['Failed to Contact Request. Try again']]);
        }
        return back()->with(['success' => ['Contact Request successfully send!']]);
    }
    /**
     * Method for show parlour package page
     */
    public function parlourPackage(){
        $page_title     = "| Parlour Package";
        $usefull_links               = UsefullLink::where('status',true)->get();
        $section_slug               = Str::slug(SiteSectionConst::CONTACT_SECTION);
        $contact                    = SiteSections::getData($section_slug)->first();
        $footer_section_slug        = Str::slug(SiteSectionConst::FOOTER_SECTION);
        $footer                     = SiteSections::getData($footer_section_slug)->first();

        return view('frontend.pages.parlour-package',compact(
            'page_title',
            'footer',
            'usefull_links',
            'contact'
        ));
    }
    /**
     * Method for show useful links 
     */
    public function link($slug){
        $link                       = UsefullLink::where('slug',$slug)->first();
        $usefull_links               = UsefullLink::where('status',true)->get();
        $section_slug               = Str::slug(SiteSectionConst::CONTACT_SECTION);
        $contact                    = SiteSections::getData($section_slug)->first();
        $footer_section_slug        = Str::slug(SiteSectionConst::FOOTER_SECTION);
        $footer                     = SiteSections::getData($footer_section_slug)->first();

        return view('frontend.pages.link',compact(
            'link',
            'footer',
            'usefull_links',
            'contact'
        ));
    }
}
