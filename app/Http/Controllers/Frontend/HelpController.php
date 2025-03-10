<?php 

namespace App\Http\Controllers\Frontend;

use App\Models\Blog;
use App\Models\CategoryBlog;
use App\Models\CategoryHelp;
use App\Models\Help;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class HelpController extends Controller
{
    protected $help = '';
    protected $categoryHelp = '';

    public function __construct(Help $help, CategoryHelp $categoryHelp)
    {
        $this->help = $help;
        $this->categoryHelp = $categoryHelp;
    }

    public function helpDetail($id)
    {
        $postDetail = $this->help->where('id', $id)->first();
        $productHot = Product::where('prd_status', 1)
                             ->where('prd_hot', 1)
                             ->get();
                             
        return view('frontend.help_detail', compact('postDetail', 'productHot'));
    }

    public function helpCate($id)
    {
        $productHot = Product::where('prd_status', 1)
                             ->where('prd_hot', 1)
                             ->get();

        if ($id == 0) {
            $postList = $this->help->paginate(16);
            return view('frontend.help_list', compact('postList', 'productHot'));
        } else {
            $postList = $this->help->where('h_category_id', $id)->paginate(16);
            $categoryHelp = $this->categoryHelp->where('id', $id)->first();
            
            return view('frontend.help_list', compact('postList', 'categoryHelp', 'productHot'));
        }
    }
}
