<?php

namespace App\Http\Controllers;
use App\Category;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Input;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'category_name' => 'required|max:100',
        ]);        
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // check view permission on the user profile
        if(!\Auth::user()->allowView(config('global.modules.category'))) {
            abort(401);
        }

        $search = Input::get ('search');
        $parent = Input::get ('parent');


        $parents = Category::parentList();

        $categories = Category::where('is_delete', 0);

        if($parent != null) {
            $categories = $categories->where('parent_id', $parent);    
        }

        $categories = $categories->where(function($data) use ($search) {
                                    $data->where('category_name','LIKE','%'.$search.'%')
                                    ->orWhere('tags','LIKE','%'.$search.'%');
                                })        
                                ->orderBy('category_name')
                                ->paginate(config('global.page_limit'));

        return view('setups.categories.index', ['categories' => $categories, 'parents' => $parents]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $parents = Category::parentList();
        return view('setups.categories.create', ['parents' => $parents]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        
        $request['image_url'] = $this->upload($request, config('global.paths.category'));
        Category::createItem($request);

        return redirect()->back()->with('message', __('message.save_successful'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $item = Category::findOrFail($id);
        if($item->is_delete == 1) {
            abort(401);
        }

        $parents = Category::parentList();

        return view('setups.categories.update', ['parents' => $parents, 'category' => $item]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $validator = $this->validator($request->all());
        // redirect to the page when it failed
        if ($validator->fails()) {
            return redirect()
                        ->route('category.edit', $id)
                        ->withErrors($validator)
                        ->withInput();
        }        

        if ($request->hasFile('file_upload')) {
            $request['image_url'] = $this->upload($request, config('global.paths.category'));            
        }    

        Category::updateItem($request, $id);
        return redirect()->back()->with('message', __('message.update_successful'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Category::deleteItem($id);
        return redirect()->back()->with('message', __('message.delete_successful'));
    }
}
