<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Order;
use App\Models\Product;
use App\Models\Promotion;
use App\Models\Slide;
use App\Models\Client;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;
use Intervention\Image\Laravel\Facades\Image;

class AdminController extends Controller
{
    public function index()
    {
        $totalProducts = Product::count();

    //return view('admin.index', compact('totalProducts'));
        return view('admin.index', compact('totalProducts'));

    }

    public function clients()
    {
        $clients = Client::latest()->paginate(10);
        return view('admin.clients', compact('clients'));   
    }

    public function add_client()
    {
        return view('admin.client-add');
    }

    public function client_store(Request $request)
    {
        // validasi input
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        // simpan ke database
        Client::create([
            'name' => $request->name,
        ]);

        // redirect dengan pesan sukses
        return redirect()->route('admin.clients')->with('status', 'Client added successfully!');
    }

     public function client_delete($id)
    {
        $clients = Client::find($id);
        $clients->delete();
        return redirect()->route('admin.clients')->with('success','Client Deleted Successfully');
    }



    public function brands()
    {
        $brands = Brand::withCount('products')->orderBy('id','DESC')->paginate(10);
        return view('admin.brands', compact('brands'));
    }


    public function add_brand()
    {
        return view('admin.brand-add');
    }

    public function brand_store(Request $request)
    {
        $request->validate([
            'name'=>'required',
            'slug'=>'required|unique:brands,slug'
            // 'image'=> 'mimes:png,jpg,jpeg|max:2048'
        ]);

        $brand = new Brand();
        $brand->name = $request->name;
        $brand->slug = str::slug($request->name);
        // $image = $request->file('image');
        // $file_extention = $request->file('image')->extension();
        // $file_name = Carbon::now()->timestamp.'.'.$file_extention;
        // $this->GenerateBrandThumbailsImage($image,$file_name);
        // $brand->image = $file_name;
        $brand->save();
        return redirect()->route('admin.brands')->with('status','Brand has been added successfully!');
    }

    public function brand_edit($id)
    {
        $brand = Brand::find($id);
        return view('admin.brand-edit',compact('brand'));
    }

    public function brand_update(Request $request)
    {
        $request->validate([
            'name'=>'required',
            'slug'=>'required|unique:brands,slug'
            // 'image'=> 'mimes:png,jpg,jpeg|max:2048'
        ]);

        $brand = Brand::find($request->id);
        $brand->name = $request->name;
        $brand->slug = str::slug($request->name);
        // if($request->hasFile('image'))
        // {
        //     if(File::exists(public_path('uploads/brands').'/'.$brand->image))
        //     {
        //         File::delete(public_path('uploads/brands').'/'.$brand->image);
        //     }
        //     $image = $request->file('image');
        //     $file_extention = $request->file('image')->extension();
        //     $file_name = Carbon::now()->timestamp.'.'.$file_extention;
        //     $this->GenerateBrandThumbailsImage($image,$file_name);
        //     $brand->image = $file_name;
        // }
        
        $brand->save();
        return redirect()->route('admin.brands')->with('status','Brand has been added successfully!');
    }

    /*public function GenerateBrandThumbailsImage($image,$imageName)
    {
        $destinationPath = public_path('uploads/brands');
        $img = Image::read($image->path());
        $img->cover(124,124,"top");
        $img->resize(124,124,function($constraint){
            $constraint->aspectRatio();
        })->save($destinationPath.'/'.$imageName);
    }*/
    public function GenerateBrandThumbailsImage($image, $imageName)
{
    $destinationPath = public_path('uploads/brands');

    if (!File::exists($destinationPath)) {
        File::makeDirectory($destinationPath, 0755, true);
    }

    $img = Image::read($image->path());
    $img->cover(124, 124, "top")
        ->resize(124, 124, function($constraint) {
            $constraint->aspectRatio();
        })
        ->save($destinationPath . '/' . $imageName);
}


    public function categories()
    {
        $categories = Category::withCount('products')->orderBy('id','DESC')->paginate(10);
        return view('admin.categories',compact('categories'));
    }

    public function category_add()
    {
        return view('admin.category-add');
    }

    public function category_store(Request $request)
    {
        $request->validate([
            'name'=>'required',
            'slug'=>'required|unique:categories,slug'
            // 'image'=> 'mimes:png,jpg,jpeg|max:2048'
        ]);

        $category = new Category();
        $category->name = $request->name;
        $category->slug = str::slug($request->name);
        // $image = $request->file('image');
        // $file_extention = $request->file('image')->extension();
        // $file_name = Carbon::now()->timestamp.'.'.$file_extention;
        // $this->GenerateCategoryThumbailsImage($image,$file_name);
        // $category->image = $file_name;
        $category->save();
        return redirect()->route('admin.categories')->with('status','Category has been added successfully!');
    }

    public function GenerateCategoryThumbailsImage($image,$imageName)
    {
        $destinationPath = public_path('uploads/categories');
        $img = Image::read($image->path());
        $img->cover(124,124,"top");
        $img->resize(124,124,function($constraint){
            $constraint->aspectRatio();
        })->save($destinationPath.'/'.$imageName);
    }

    public function products()
    {
        $products = Product::orderBy('id','ASC')->paginate(10);
        $firstProduct = $products->first(); // bisa null
        return view('admin.products', compact('products','firstProduct'));

    }

    public function product_add(){
        $categories = Category::select('id','name')->orderBy('name')->get();
        $brands = Brand::select('id','name')->orderBy('name')->get();
        return view('admin.product-add',compact('categories','brands'));
    }

    public function product_product(){
        $categories = Category::select('id','name')->orderBy('name')->get();
        $brands = Brand::select('id','name')->orderBy('name')->get();
        return view('admin.product-add',compact('categories','brands'));
    }

    public function product_edit($id)
    {
        // $slide = Slide::find($id);
        $product = Product::find($id);
        $categories = Category::select('id','name')->orderBy('name')->get();
        $brands = Brand::select('id','name')->orderBy('name')->get();
        return view('admin.product-edit',compact('product','categories','brands'));
    }

   public function product_update(Request $request, $id)
{
    $product = Product::findOrFail($id);

    $request->validate([
        // 'name' => 'required',
        // 'slug' => 'required|unique:products,slug,' . $product->id, // slug unique kecuali current
        'short_description' => 'required',
        'description' => 'required',
        'regular_price' => 'required',
        'sale_price' => 'required',
        'SKU' => 'required',
        'stock_status' => 'required',
        'featured' => 'required',
        'quantity' => 'required',
        'category_id' => 'required',
        'brand_id' => 'required',
        'image' => 'nullable|mimes:png,jpg,jpeg|max:2048',
    ]);

    // update field teks
    // $product->name = $request->name;
    // $product->slug = Str::slug($request->name);
    $product->short_description = $request->short_description;
    $product->description = $request->description;
    $product->regular_price = $request->regular_price;
    $product->sale_price = $request->sale_price;
    $product->SKU = $request->SKU;
    $product->stock_status = $request->stock_status;
    $product->featured = $request->featured;
    $product->quantity = $request->quantity;
    $product->brand_id = $request->brand_id;
    $product->category_id = $request->category_id;

    $current_timestamp = Carbon::now()->timestamp;

    // cek apakah ada upload gambar baru
    if ($request->hasFile('image')) {
        // hapus file lama kalau ada
        $oldImage = public_path('uploads/products/thumbnails/' . $product->image);
        if ($product->image && File::exists($oldImage)) {
            File::delete($oldImage);
        }

        // simpan gambar baru
        $image = $request->file('image');
        $imageName = $image->getClientOriginalName();
        $this->GenerateProductThumbnailImage($image, $imageName);
        $product->image = $imageName;
    }

    // update multiple images (gallery)
    $gallery_arr = [];
    $gallery_images = $product->images; // default pakai yang lama
    $counter = 1;

    if ($request->hasFile('images')) {
        // hapus semua gambar lama dulu
        if ($product->images) {
            foreach (explode(',', $product->images) as $oldGallery) {
                $oldPath = public_path('uploads/products/thumbnails/' . $oldGallery);
                if (File::exists($oldPath)) {
                    File::delete($oldPath);
                }
            }
        }

        $allowedfileRxtion = ['jpg','png','jpeg'];
        $files = $request->file('images');
        foreach ($files as $file) {
            $gextension = $file->getClientOriginalExtension();
            if (in_array($gextension, $allowedfileRxtion)) {
                $gfileName = $file->getClientOriginalName();
                $this->GenerateProductThumbnailImage($file, $gfileName);
                $gallery_arr[] = $gfileName;
                $counter++;
            }
        }
        $gallery_images = implode(',', $gallery_arr);
    }

    $product->images = $gallery_images;

    $product->save();

    return redirect()->route('admin.products')->with('status','Product updated successfully');
}

    public function product_delete($id)
    {
        $product = Product::find($id);
        if(File::exists(public_path('uploads/products').'/'.$product->image))
        {
            File::delete(public_path('uploads/products').'/'.$product->image);
        }
        $product->delete();
        return redirect()->route('admin.products')->with('success','Slide Deleted Successfully');
    }

    public function show($id)
    {
        $product = Product::with(['category','brand'])->findOrFail($id);
        return response()->json($product);
    }


    public function product_store(Request $request)
    {
        $request->validate([
            'name' =>'required',
            'slug' =>'required|unique:products,slug',
            'short_description' =>'required',
            'description' =>'required',
            'regular_price' =>'required',
            'sale_price' =>'required',
            'SKU' =>'required',
            'stock_status' =>'required',
            'featured' =>'required',
            'quantity' =>'required',
            'image' =>'required|mimes:png,jpg,jpeg|max:2048',
            'category_id' => 'required',
            'brand_id' => 'required'
        ]);

        $product = new Product();
        $product->name = $request->name;
        $product->slug = Str::slug($request->name);
        $product->short_description = $request->short_description;
        $product->description = $request->description;
        $product->regular_price = $request->regular_price;
        $product->sale_price = $request->sale_price;
        $product->SKU = $request->SKU;
        $product->stock_status = $request->stock_status;
        $product->featured = $request->featured;
        $product->quantity = $request->quantity;
        
        $product->brand_id = $request->brand_id;
        $product->category_id = $request->category_id;

        $current_timestamp = Carbon::now()->timestamp;

        if($request->hasFile('image'))
        {
            $image = $request->file('image');
            //$imageName = $current_timestamp . '.' . $image->extension();
            $imageName = $image->getClientOriginalName();
            $this->GenerateProductThumbnailImage($image,$imageName);
            $product->image = $imageName;
        }

        $gallery_arr = array();
        $gallery_images = "";
        $counter = 1;

        if($request->hasFile('images'))
        {
            $allowedfileRxtion = ['jpg','png','jpeg'];
            $files = $request->file('images');
            foreach($files as $file)
            {
                $gextension = $file->getClientOriginalExtension();
                $gcheck = in_array($gextension,$allowedfileRxtion);
                if($gcheck)
                {
                    $gfileName = $file->getClientOriginalName();
                    //$gfileName = $current_timestamp . "-" . $counter . "." . $gextension;
                    $this->GenerateProductThumbnailImage($file,$gfileName);
                    array_push($gallery_arr,$gfileName);
                    $counter++;

                }
            }
            $gallery_images = implode(',',$gallery_arr);
        }
        $product->images = $gallery_images;
        $product->save();
        return redirect()->route('admin.products')->with('status','Product has been added successfully');
    }

    public function GenerateProductThumbnailImage($image, $imageName)
    {
      $destinationPath = public_path('uploads/products/thumbnails');

    // pastikan folder ada
    if (!file_exists($destinationPath)) {
        mkdir($destinationPath, 0755, true);
    }

    // Resize gambar dengan menjaga aspect ratio & mencegah upscaling
    Image::read($image->getRealPath())
        ->resize(500, 500, function ($constraint) {
            $constraint->aspectRatio(); // biar tidak gepeng
            $constraint->upsize();      // jangan diperbesar kalau lebih kecil
        })
        ->toJpeg(90) // simpan dengan kualitas bagus
        ->save($destinationPath . '/' . $imageName);
    }

    public function orders()
    {
        $orders = Order::orderby('created_at','DESC')->paginate(12);
        return view('admin.orders',compact('orders'));
    }

    public function slides()
    {
        $slides = Slide::orderBy('id','DESC')->paginate(12);
        return view('admin.slides',compact('slides'));
    }

    public function slide_add()
    {
        return view('admin.slide-add');
    }

    public function slide_store(Request $request)
    {
        $request->validate(
            [
                'tagline' => 'required',
                'title' => 'required',
                'subtitle' => 'required',
                'link' => 'required',
                'status' => 'required',
                'image' => 'required|mimes:jpg,jpeg,png|max:2048'
            ]);
            $slide = new Slide();
            $slide->tagline = $request->tagline;
            $slide->title = $request->title;
            $slide->subtitle = $request->subtitle;
            $slide->link = $request->link;
            $slide->status = $request->status;
            
            $image = $request->file('image');
            $file_extention = $request->file('image')->extension();
            $file_name = Carbon::now()->timestamp.'.'.$file_extention;
            $this->GenerateSlideThumbailsImage($image,$file_name);
            $slide->image = $file_name;
            $slide->save();
            return redirect()->route('admin.slides')->with('success','Slide Added Successfully');

    }

    public function GenerateSlideThumbailsImage($image,$imageName)
    {
        $destinationPath = public_path('uploads/slides');
        $img = Image::read($image->path());
        $img->cover(400,690,"top");
        $img->resize(400,690,function($constraint){
            $constraint->aspectRatio();
        })->save($destinationPath.'/'.$imageName);
    }

    public function slide_edit($id)
    {
        $slide = Slide::find($id);
        return view('admin.slide-edit',compact('slide'));
    }

    public function slide_update(Request $request)
    {
        $request->validate(
            [
                'tagline' => 'required',
                'title' => 'required',
                'subtitle' => 'required',
                'link' => 'required',
                'status' => 'required',
                'image' => 'mimes:jpg,jpeg,png|max:2048'
            ]);
            $slide = Slide::find($request->id);
            $slide->tagline = $request->tagline;
            $slide->title = $request->title;
            $slide->subtitle = $request->subtitle;
            $slide->link = $request->link;
            $slide->status = $request->status;
            
            if($request->hasFile('image'))
            {
                if(File::exists(public_path('uploads/slides').'/'.$slide->image))
                {
                    File::delete(public_path('uploads/slides').'/'.$slide->image);
                }
                $image = $request->file('image');
                $file_extention = $request->file('image')->extension();
                $file_name = Carbon::now()->timestamp.'.'.$file_extention;
                $this->GenerateSlideThumbailsImage($image,$file_name);
                $slide->image = $file_name;
            }
           
            $slide->save();
            return redirect()->route('admin.slides')->with('success','Slide Updated Successfully');
    }

    public function slide_delete($id)
    {
        $slide = Slide::find($id);
        if(File::exists(public_path('uploads/slides').'/'.$slide->image))
        {
            File::delete(public_path('uploads/slides').'/'.$slide->image);
        }
        $slide->delete();
        return redirect()->route('admin.slides')->with('success','Slide Deleted Successfully');
    }

    public function promotions()
    {
        $promotions = Promotion::orderBy('id','DESC')->paginate(12);
        return view('admin.promotions',compact('promotions'));
    }

    public function promotion_add()
    {
        return view('admin.promotion-add');
    }

    public function promotion_store(Request $request)
    {
        $request->validate(
            [
                'tagline' => 'required',
                'title' => 'required',
                'subtitle' => 'required',
                'deskripsi' => 'required',
                'status' => 'required',
                'image' => 'required|mimes:jpg,jpeg,png|max:2048'
            ]);
            $promotion = new Promotion();
            $promotion->tagline = $request->tagline;
            $promotion->title = $request->title;
            $promotion->subtitle = $request->subtitle;
            $promotion->deskripsi = $request->deskripsi;
            $promotion->status = $request->status;
            
            $image = $request->file('image');
            $file_extention = $request->file('image')->extension();
            $file_name = Carbon::now()->timestamp.'.'.$file_extention;
            $this->GeneratePromotionThumbailsImage($image,$file_name);
            $promotion->image = $file_name;
            $promotion->save();
            return redirect()->route('admin.promotions')->with('success','Promotion Added Successfully');

    }

    public function GeneratePromotionThumbailsImage($image,$imageName)
    {
        $destinationPath = public_path('uploads/promotions');
        $img = Image::read($image->path());
        $img->cover(1262,900,"top");
        $img->resize(1262,900,function($constraint){
            $constraint->aspectRatio();
        })->save($destinationPath.'/'.$imageName);
    }

    public function promotion_edit($id)
    {
        $promotion = Promotion::find($id);
        return view('admin.promotion-edit',compact('promotion'));
    }

    public function promotion_update(Request $request)
    {
        $request->validate(
            [
                'tagline' => 'required',
                'title' => 'required',
                'subtitle' => 'required',
                'deskripsi' => 'required',
                'status' => 'required',
                'image' => 'mimes:jpg,jpeg,png|max:2048'
            ]);
            $promotion = Promotion::find($request->id);
            $promotion->tagline = $request->tagline;
            $promotion->title = $request->title;
            $promotion->subtitle = $request->subtitle;
            $promotion->deskripsi = $request->deskripsi;
            $promotion->status = $request->status;
            
            if($request->hasFile('image'))
            {
                if(File::exists(public_path('uploads/promotions').'/'.$promotion->image))
                {
                    File::delete(public_path('uploads/promotions').'/'.$promotion->image);
                }
                $image = $request->file('image');
                $file_extention = $request->file('image')->extension();
                $file_name = Carbon::now()->timestamp.'.'.$file_extention;
                $this->GeneratePromotionThumbailsImage($image,$file_name);
                $promotion->image = $file_name;
            }
           
            $promotion->save();
            return redirect()->route('admin.promotions')->with('success','Slide Updated Successfully');
    }

    public function promotion_delete($id)
    {
        $promotion = Promotion::find($id);
        if(File::exists(public_path('uploads/promotions').'/'.$promotion->image))
        {
            File::delete(public_path('uploads/promotions').'/'.$promotion->image);
        }
        $promotion->delete();
        return redirect()->route('admin.promotions')->with('success','Promotion Deleted Successfully');
    }
}
