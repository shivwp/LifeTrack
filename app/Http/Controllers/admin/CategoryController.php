<?php



namespace App\Http\Controllers\admin;



use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use App\Models\Category;
 use Validator;


class CategoryController extends Controller

{

    /**

     * Display a listing of the resource.

     *

     * @return \Illuminate\Http\Response

     */

    public function index()

    {
         

        $d['title'] = "CATEGORY";
        $d['buton_name'] = "ADD NEW";
        
        if(isset($_GET['paginate'])){
            $pagination=$_GET['paginate'];
        }
        $category =Category::all();
      
        foreach($category as $key => $val){
           
             
            $categoryParent = Category::where('parent_id','!=',0)->where('id',$val->id)->first();
            if(!empty($categoryParent)){
                $Parent = Category::where('id','=',$categoryParent->parent_id)->first();
                $category[$key]['parent_name'] = !empty($Parent->title) ? $Parent->title : '';
            }
        }
    
        $d['category'] = $category;
        return view('admin/category/index',$d);
    }

    // public function pagination(Request $request)

    // {
    //     $pagevalue=$request->option;
    //      $category=Category::all();

    //     foreach($category as $key => $val){

    //         $categoryParent = Category::where('parent_id','!=',0)->where('id',$val->id)->first();

    //         if(!empty($categoryParent)){

    //             $Parent = Category::where('id','=',$categoryParent->parent_id)->first();



    //             $category[$key]['parent_name'] = !empty($Parent->title) ? $Parent->title : '';

    //         }

    //     }


    //     $d['category'] = $category;
    //     $paginationvalue =  Category::paginate($pagevalue)->withQueryString();

    //      $html = '';
    //   foreach ($paginationvalue as $key => $item) {
    //       $list = Category::where('id', $item)->first(); 
    //       $html.='
    //                 <tr>
    //                 <td>'.$item->id .'  </td>
    //                 <td>'.$item->title .'  </td>
    //                 <td>'.$item->parent_name . '  </td>
    //                 <td>
    //                 <a class="btn btn-sm btn-secondary" href="'.route("dashboard.category.edit", $item->id).'"><i class="fa fa-edit"></i> </a>
    //                 <form action="'.route("dashboard.category.destroy", $item->id).'" method="POST"onsubmit="return confirm("Are you sure");" style="display: inline-block;">
    //                     <input type="hidden" name="_method" value="DELETE">
    //                     <input type="hidden" name="_token" value="'.csrf_token().'">
    //                     <button type="submit" class="btn btn-sm btn-danger" value="'.trans("global.delete").'"><i class="fa fa-trash"></i></button>
    //                 </form>
    //                 </td>
    //                 </tr>';
            
       
       
    //   }
      
    //   $html .= '';
    //    return response()->json(['paginationvalue'=>$paginationvalue, 'html' => $html], 201);

    // }


    /**

     * Show the form for creating a new resource.

     *

     * @return \Illuminate\Http\Response

     */

    public function create()

    {

         

        $d['title'] = "CATEGORY";
        
       
        $d['cat'] = Category::where('status','enable')->get();

        

        return view('admin/category/add',$d);
    }

    



    /**

     * Store a newly created resource in storage.

     *

     * @param  \Illuminate\Http\Request  $request

     * @return \Illuminate\Http\Response

     */

    public function store(Request $request)

    {

       

   

        
      //dd($request);
         if ($request->id) {
            
           
       
          $sms = "Category has been updated successfully" ;
        }else{

           
        $request->validate([
        'title' => 'required|unique:categories',
        
      ]);
          $sms = "Category has been saved successfully" ;
        }

        if($request->level == 1) {

            $level = 2;

        } else if($request->level == 2) {

            $level = 3;

        } else if($request->level == 3) {

            $level = 4;

        }
          
      $disc =$request->discription;
      

       
        
       $category = Category::updateOrCreate(

        
             
            [
           
             'id' => $request->id

            ],

        
           
            [
            
            

            // 'user_id'   => Auth::user()->id,

            'title'         => $request->input('title'),

            'commision'         => $request->input('commision'),

            'discription'   => $disc,

            'parent_id'     => $request->input('parent_cat',0),

            'level'         => !empty($level) ? $level : 0,

            'status'        => $request->input('status')
        ]);

       
   
   $category->update();


    return redirect('/dashboard/category')->with('message', ' '.$sms.' ');


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

    public function edit(Request $request ,$id)

    {
           
       

        $d['category']=Category::findorfail($id);


        $d['title'] = "CATEGORY";

       

        $d['cat'] = Category::where('status','enable')->where('id','<>',$id)->get();

     
        return view('admin/category/add',$d);

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

        //

    }



    /**

     * Remove the specified resource from storage.

     *

     * @param  int  $id

     * @return \Illuminate\Http\Response

     */

    public function destroy($id)

    {

       $category =Category::where('id',$id)->first();

        if ($category != null) {

           $category->delete();

            return redirect('dashboard/category')->with('message','Category deleted successfully');

        }

    }

}

