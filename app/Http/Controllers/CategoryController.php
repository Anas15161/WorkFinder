<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Job;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index($id)
    {
        $jobs = Job::where('category_id', $id)->paginate(15);
        $category = Category::find($id); // Using a consistent variable name

        if (!$category) {
            return redirect()->route('categories.index')->with('error', 'Category not found.');
        }

        return view('frontend.jobs.jobs-category', compact('jobs', 'category'));
    }
}

