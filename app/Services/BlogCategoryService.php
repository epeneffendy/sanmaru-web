<?php
namespace App\Services;

use App\Models\BlogCategory;

class BlogCategoryService
{
    public function countCategories()
    {
        return BlogCategory::count('id');
    }

    public function listCategories($offset, $limit)
    {
        $datas = new BlogCategory();
        if (isset($offset)) {
            $datas = $datas->offset($offset);
        }
        if (isset($limit)) {
            $datas = $datas->limit($limit);
        }

        $collect = collect();
        foreach ($datas->with('blogs')->get() as $data) {
            $collect->push($this->_category($data));
        }

        return $collect;
    }

    public function getCategory($slug)
    {
        $data = BlogCategory::where('slug', $slug)->with('blogs')->first();
        return $this->_category($data);
    }

    private function _category($data)
    {
        if (!$data) {
            return false;
        }

        return [
            'name' => $data->name,
            'slug' => $data->slug,
            'active' => $data->active,
            'total_blogs' => $data->blogs->count()
        ];
    }
}