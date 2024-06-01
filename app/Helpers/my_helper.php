<?php

use App\Models\Category\Category;

function name_category($id)
{
    $category = (new Category())->find($id);
    return $category["name"];
}
