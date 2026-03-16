<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\News;
use App\Models\Research;
use App\Models\Pengabdian;
use App\Models\Slider;
use App\Models\Grant;
use App\Models\Page;
use App\Models\Category;
use App\Models\Cooperation;
use App\Models\Publication;
use App\Models\Journal;
use App\Models\Hki;
use App\Models\Repository;
use App\Models\Download;
use App\Models\GalleryAlbum;
use App\Models\Contact;
use App\Models\ResearchRoadmap;
use App\Models\ResearchScheme;
use App\Models\ResearchGuide;
use App\Models\PkmProgram;
use App\Models\PkmGuide;
use App\Models\OrganizationStructure;
use Illuminate\Http\Request;

class FrontendController extends Controller
{
    public function index()
    {
        $sliders = Slider::with('translations')->where('is_active', true)->orderBy('order')->get();
        
        $latestNews = News::with(['translations', 'category.translations'])
            ->where('is_published', true)
            ->latest('published_at')
            ->take(4)
            ->get();

        $latestResearch = Research::with(['translations', 'researchers'])
            ->where('is_published', true)
            ->latest()
            ->take(3)
            ->get();

        $upcomingEvents = Event::with('translations')
            ->where('is_published', true)
            ->where('start_date', '>=', now())
            ->orderBy('start_date')
            ->take(3)
            ->get();

        // Statistics
        $stats = [
            'research' => Research::count(),
            'pengabdian' => Pengabdian::count(),
            'publications' => Publication::count() + Journal::count(),
            'grants' => Grant::count(),
        ];

        return view('frontend.home', compact(
            'sliders',
            'latestNews',
            'latestResearch',
            'upcomingEvents',
            'stats'
        ));
    }

    public function page($slug)
    {
        $page = Page::where('slug', $slug)->where('is_published', true)->with('translations')->firstOrFail();
        
        $relatedPages = Page::where('id', '!=', $page->id)
            ->where('is_published', true)
            ->with('translations')
            ->orderBy('order')
            ->take(10)
            ->get();
            
        return view('frontend.page', compact('page', 'relatedPages'));
    }

    // Profil
    public function organizationStructure()
    {
        $structures = OrganizationStructure::active()
            ->roots()
            ->with('children')
            ->orderBy('order')
            ->get();
        
        return view('frontend.profile.structure', compact('structures'));
    }

    // Berita
    public function news(Request $request)
    {
        $query = News::with(['translations', 'category.translations', 'author'])
            ->where('is_published', true)
            ->latest('published_at');

        if ($request->filled('category')) {
            $query->whereHas('category', function($q) use ($request) {
                $q->where('slug', $request->category);
            });
        }

        if ($request->filled('search')) {
            $query->whereHas('translations', function($q) use ($request) {
                $q->where('title', 'like', "%{$request->search}%")
                  ->orWhere('content', 'like', "%{$request->search}%");
            });
        }

        $news = $query->paginate(12)->onEachSide(2);
        $categories = Category::where('type', 'news')->where('is_active', true)->withCount('news')->with('translations')->get();
        
        $popularNews = News::with('translations')
            ->where('is_published', true)
            ->orderBy('views', 'desc')
            ->take(5)
            ->get();

        return view('frontend.news.index', compact('news', 'categories', 'popularNews'));
    }

    public function newsDetail($slug)
    {
        $news = News::where('slug', $slug)
            ->where('is_published', true)
            ->with(['translations', 'category.translations', 'author'])
            ->firstOrFail();

        $news->increment('views');
        
        $categories = Category::where('type', 'news')->where('is_active', true)->withCount('news')->with('translations')->get();
        
        $latestNews = News::with('translations')
            ->where('id', '!=', $news->id)
            ->where('is_published', true)
            ->latest('published_at')
            ->take(5)
            ->get();

        $relatedNews = News::with('translations')
            ->where('id', '!=', $news->id)
            ->where('category_id', $news->category_id)
            ->where('is_published', true)
            ->latest()
            ->take(3)
            ->get();

        return view('frontend.news.show', compact('news', 'relatedNews', 'latestNews', 'categories'));
    }

    // Penelitian
    public function researchRoadmap()
    {
        $roadmaps = ResearchRoadmap::with('translations')
            ->active()
            ->orderBy('order')
            ->get();

        return view('frontend.research.roadmap', compact('roadmaps'));
    }

    public function researchSchemes()
    {
        $schemes = ResearchScheme::with('translations')
            ->active()
            ->orderBy('order')
            ->get();

        return view('frontend.research.schemes', compact('schemes'));
    }

    public function researchGuides()
    {
        $guides = ResearchGuide::with('translations')
            ->active()
            ->orderBy('order')
            ->get();

        return view('frontend.research.guides', compact('guides'));
    }

    public function researches(Request $request)
    {
        $query = Research::with(['translations', 'scheme.translations', 'team'])
            ->published()
            ->latest();

        if ($request->filled('scheme')) {
            $query->whereHas('scheme', function($q) use ($request) {
                $q->where('slug', $request->scheme);
            });
        }

        if ($request->filled('year')) {
            $query->where('year', $request->year);
        }

        if ($request->filled('search')) {
            $query->whereHas('translations', function($q) use ($request) {
                $q->where('title', 'like', "%{$request->search}%");
            });
        }

        $researches = $query->paginate(12)->onEachSide(2);
        $schemes = ResearchScheme::active()->with('translations')->get();
        $years = Research::published()->distinct()->pluck('year')->filter()->sort()->reverse();

        return view('frontend.research.index', compact('researches', 'schemes', 'years'));
    }

    public function researchDetail($slug)
    {
        $research = Research::where('slug', $slug)
            ->published()
            ->with(['translations', 'scheme.translations', 'team', 'category.translations'])
            ->firstOrFail();

        return view('frontend.research.detail', compact('research'));
    }

    // Pengabdian
    public function pkmPrograms()
    {
        $programs = PkmProgram::with('translations')
            ->active()
            ->orderBy('order')
            ->get();

        return view('frontend.pengabdian.programs', compact('programs'));
    }

    public function pkmGuides()
    {
        $guides = PkmGuide::with('translations')
            ->active()
            ->orderBy('order')
            ->get();

        return view('frontend.pengabdian.guides', compact('guides'));
    }

    public function pengabdians(Request $request)
    {
        $query = Pengabdian::with(['translations', 'program.translations', 'team'])
            ->published()
            ->latest();

        if ($request->filled('program')) {
            $query->whereHas('program', function($q) use ($request) {
                $q->where('slug', $request->program);
            });
        }

        if ($request->filled('year')) {
            $query->where('year', $request->year);
        }

        $pengabdians = $query->paginate(12)->onEachSide(2);
        $programs = PkmProgram::active()->with('translations')->get();
        $years = Pengabdian::published()->distinct()->pluck('year')->filter()->sort()->reverse();

        return view('frontend.pengabdian.index', compact('pengabdians', 'programs', 'years'));
    }

    public function pengabdianDetail($slug)
    {
        $pengabdian = Pengabdian::where('slug', $slug)
            ->published()
            ->with(['translations', 'program.translations', 'team'])
            ->firstOrFail();

        return view('frontend.pengabdian.detail', compact('pengabdian'));
    }

    // Publikasi
    public function journals()
    {
        $journals = Journal::with('translations')
            ->active()
            ->orderBy('order')
            ->get();

        return view('frontend.publication.journals', compact('journals'));
    }

    public function publications(Request $request)
    {
        $query = Publication::with(['translations', 'journal.translations', 'authors'])
            ->published()
            ->latest();

        if ($request->filled('type')) {
            $query->ofType($request->type);
        }

        if ($request->filled('year')) {
            $query->where('year', $request->year);
        }

        $publications = $query->paginate(12)->onEachSide(2);
        $years = Publication::published()->distinct()->pluck('year')->filter()->sort()->reverse();

        return view('frontend.publication.index', compact('publications', 'years'));
    }

    public function publicationDetail($slug)
    {
        $publication = Publication::where('slug', $slug)
            ->published()
            ->with(['translations', 'journal.translations', 'authors.researcher'])
            ->firstOrFail();

        return view('frontend.publication.detail', compact('publication'));
    }

    public function hkis(Request $request)
    {
        $query = Hki::with('translations')
            ->published()
            ->latest();

        if ($request->filled('type')) {
            $query->ofType($request->type);
        }

        $hkis = $query->paginate(12)->onEachSide(2);

        return view('frontend.publication.hki', compact('hkis'));
    }

    public function repositories(Request $request)
    {
        $query = Repository::with('translations')
            ->published()
            ->latest();

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        if ($request->filled('year')) {
            $query->where('year', $request->year);
        }

        $repositories = $query->paginate(12)->onEachSide(2);

        return view('frontend.publication.repository', compact('repositories'));
    }

    // Kerjasama
    public function cooperations(Request $request)
    {
        $query = Cooperation::with('translations')
            ->published()
            ->latest();

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $cooperations = $query->paginate(12)->onEachSide(2);

        return view('frontend.cooperation.index', compact('cooperations'));
    }

    public function cooperationDetail($slug)
    {
        $cooperation = Cooperation::where('slug', $slug)
            ->published()
            ->with('translations')
            ->firstOrFail();

        return view('frontend.cooperation.detail', compact('cooperation'));
    }

    // Hibah
    public function grants(Request $request)
    {
        $query = Grant::with('translations')
            ->published()
            ->latest();

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $grants = $query->paginate(12)->onEachSide(2);

        return view('frontend.grant.index', compact('grants'));
    }

    public function grantDetail($slug)
    {
        $grant = Grant::where('slug', $slug)
            ->published()
            ->with('translations')
            ->firstOrFail();

        return view('frontend.grant.detail', compact('grant'));
    }

    // Download
    public function downloads(Request $request)
    {
        $query = Download::with(['translations', 'category.translations'])
            ->published()
            ->orderBy('order');

        if ($request->filled('category')) {
            $query->whereHas('category', function($q) use ($request) {
                $q->where('slug', $request->category);
            });
        }

        $downloads = $query->paginate(20)->onEachSide(2);
        $categories = Category::ofType('download')->active()->with('translations')->get();

        return view('frontend.download.index', compact('downloads', 'categories'));
    }

    public function downloadFile(Download $download)
    {
        $download->incrementDownloads();
        return response()->download(storage_path('app/public/' . $download->file));
    }

    // Gallery
    public function gallery(Request $request)
    {
        $query = GalleryAlbum::with(['translations', 'images'])
            ->published()
            ->latest('event_date');

        if ($request->filled('category')) {
            $query->whereHas('category', function($q) use ($request) {
                $q->where('slug', $request->category);
            });
        }

        $albums = $query->paginate(12)->onEachSide(2);
        $categories = Category::ofType('gallery')->active()->with('translations')->get();

        return view('frontend.gallery.index', compact('albums', 'categories'));
    }

    public function galleryDetail($slug)
    {
        $album = GalleryAlbum::where('slug', $slug)
            ->published()
            ->with(['translations', 'images'])
            ->firstOrFail();

        return view('frontend.gallery.detail', compact('album'));
    }

    // Events
    public function events(Request $request)
    {
        $query = Event::with('translations')
            ->published()
            ->orderBy('start_date', 'desc');

        if ($request->filled('upcoming')) {
            $query->upcoming();
        }

        $events = $query->paginate(12)->onEachSide(2);

        return view('frontend.event.index', compact('events'));
    }

    public function eventDetail($slug)
    {
        $event = Event::where('slug', $slug)
            ->published()
            ->with('translations')
            ->firstOrFail();

        return view('frontend.event.detail', compact('event'));
    }

    // Contact
    public function contact()
    {
        return view('frontend.contact');
    }

    public function submitContact(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'subject' => ['required', 'string', 'max:255'],
            'message' => ['required', 'string'],
        ]);

        Contact::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'subject' => $request->subject,
            'message' => $request->message,
            'ip_address' => $request->ip(),
        ]);

        return redirect()->back()
            ->with('success', __('Your message has been sent successfully'));
    }
}
