<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\BlogCategory;
use App\APIError;

class BlogCategoryController extends Controller
{

    public function create(Request $request)
    {
        $request->validate([
            'title' => 'required'
        ]);

        $data = $request->only([
            'title',
            'description'
        ]);

        $blogcat = BlogCategory::create($data);
        return response()->json($blogcat);
    }



    public function update(Request $request, $id)
    {
        $blogcat = BlogCategory::find($id);
        if ($blogcat == null) {
            $apiError = new APIError;
            $apiError->setStatus("404");
            $apiError->setCode("BLOGCATEGORY_ID_NOT_EXISTING");
            $apiError->setErrors(['id' => 'blog category id not existing']);

            return response()->json($apiError, 404);
        }

        $request->validate([
            'title' => 'required'
        ]);

        $data = $request->only([
            'title',
            'description'
        ]);

        $blogcat->update($data);
        return response()->json($blogcat);
    }


    /**
     * delete a blog Category
     * @author Brell Sanwouo
     */
    public function delete ($id){
        $blogCategory = BlogCategory::find($id);
        if(!$blogCategory){
            $notFound = new APIError;
            $notFound->setStatus("404");
            $notFound->setCode("BLOG_CATEGORY_NOT_FOUND");
            $notFound->setMessage("blog Category id not found in database.");

            return response()->json($notFound, 404);
        }

        $blogCategory->delete();

        return response()->json(null);
    }

    /**
     * find a specific blog Category
     * @author Brell Sanwouo
     */
    public function find($id){
        $blogCategory = BlogCategory::find($id);
        if($blogCategory == null){
            $notFound = new APIError;
            $notFound->setStatus("404");
            $notFound->setCode("BLOG_CATEGORY_NOT_FOUND");
            $notFound->setMessage("blog Category id not found in database.");

            return response()->json($notFound, 404);
        }
        return response()->json($blogCategory);
    }

    /**
     * get all blog categories with spécific value
     * @author Brell Sanwouo
     */

     public function get(Request $request){
        $limit = $request->limit;
        $s = $request->s;
        $page = $request->page;
        $blogcats = BlogCategory::where('title', 'LIKE', '%'.$s.'%')
                                  ->paginate($limit);

        return response()->json($blogcats);
    }
}
