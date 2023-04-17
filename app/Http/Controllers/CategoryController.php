<?php

namespace App\Http\Controllers;

use App\Models\Categorie;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;

use App\Http\Resources\CategorieResource;

class CategoryController extends Controller
{
    public function loadCategories()
    {
        $discipline_id = request('discipline_id');

        $categories = Categorie::whereHas('disciplines', function ($query) use ($discipline_id) {
            $query->where('discipline_id', $discipline_id);
        })->get();

        return CategorieResource::collection($categories);
    }
}
