<?php
namespace App\Services;

use Illuminate\Support\Str;
use App\Helpers\Helper;
use App\Models\Faq;
use App\Models\Tag;

class FaqService
{
    public function generateIndexData($nav)
    {
        $faqs = Faq::with([
                    'tags' => function ($query) {
                        $query->select('id','name');
                    }])
                ->orderBy('publish_date', 'desc')
                ->get();
        
        return ['nav' => $nav, 'faqs' => $faqs];
    }

    public function generateAddingData($nav)
    {
        $faq = new Faq();

        return [
            'tags' => '', 
            'faq' => '',
            'categories' => $faq->listCategory(),
            'nav' => $nav
        ];
    }

    public function generateEditableData($id, $nav)
    {
        $faq    = Faq::where('id',$id)->firstOrFail();
        $tags   = $faq->tags()->pluck('name')->toArray();
        $tags   = implode(",",$tags);

        return [
            'status' => 'edit', 
            'faq' => $faq, 
            'categories' => $faq->listCategory(),
            'tags' => $tags, 
            'nav' => $nav
        ];
    }

    public function generateShowData($id, $nav)
    {
        $faq = Faq::with([
                    'tags' => function ($query) {
                        $query->select('id','name');
                    }])
                    ->where('id', $id)->firstOrFail();

        return [
            'status' => 'show', 
            'faq' => $faq, 
            'categories' => $faq->listCategory(),
            'nav' => $nav
        ];
    }

    private function params($params)
    {
        $imageService = new ImageService();

        if (isset($params['answer'])) {
            $params['answer'] = $imageService->filterUploadHTML($params['answer'], 'content_image');
        }

        if (isset($params['current_answer'])) {
            $imageService->filterUpdateHTML($params['current_answer'], $params['answer']);
        }

        if (!Helper::canPublishArticle() && isset($params['published'])) {
            unset($params['published']);
        }

        return $params;
    }

    public function create($params)
    {
        $params = $this->params($params);
        $faq    = Faq::create($params);
        $tags   = $this->saveTags($faq, $params);

        return $faq;
    }

    public function update($id, $params)
    {
        $faq = Faq::findOrFail($id);
        $params['current_answer'] = $faq->answer;
        $params = $this->params($params);
        $faq->fill($params);
        $tags = $this->saveTags($faq, $params);

        return $faq->save();
    }

    public function delete($id)
    {
        $faq = Faq::where('id', $id)->firstOrFail();
        $imageService = new ImageService();
        $imageService->filterDeleteHTML($faq->answer, 'content_image');
        $faq->tags()->detach();
        return $faq->delete();
    }

    public function toggleStatus($id)
    {
        $faq = Faq::where('id', $id)->firstOrFail();
        $faq->published = $faq->isPublished() ? 0 : 1;
        $faq->save();
        return $faq->status;
    }

    public function saveTags(Faq $faq, $params)
    {
        if(isset($params['tags'])) {
            $tags = explode(",", $params['tags']);
            foreach($tags as $key => $value){
                $slug = Str::slug($value);
                if (!$tag = Tag::where('slug', $slug)->first()) {
                    $tag = Tag::create([
                        'name' =>$value,
                        'slug' => $slug
                    ]);
                }
                $tags[$key] = $tag->id;
            }
            $faq->tags()->sync($tags);
        } else {
            $faq->tags()->detach();
        }
    }

    public function countFaqs()
    {
        return Faq::count('id');
    }

    public function listFaqs($offset, $limit, $published)
    {
        $datas = new Faq();
        if (isset($offset)) {
            $datas = $datas->offset($offset);
        }
        if (isset($limit)) {
            $datas = $datas->limit($limit);
        }
        if (isset($published)) {
            $datas = $datas->where('published', $published ? 1 : 0);
        }

        $collect = collect();
        foreach ($datas->orderBy('publish_date', 'DESC')->get() as $data) {
            $collect->push($this->_faq($data));
        }

        return $collect;
    }

    public function getFaq($slug)
    {
        $data = Faq::where('slug', $slug)->first();
        return $this->_faq($data);
    }

    private function _faq($data)
    {
        if (!$data) {
            return false;
        }

        return [
            'title' => $data->title,
            'slug' => $data->slug,
            'content' => $data->content,
            'answer' => $data->html_answer,
            'category' => $data->category,
            'publish_date' => $data->publish_date,
            'published' => $data->published,
        ];
    }
}
