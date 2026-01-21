<?php
namespace App\Services;

use App\Enums\ProductScheduleDayEnum;
use App\Enums\ProductTypeEnum;
use App\Models\ProductCategory;
use App\Models\ProductStand;
use App\Traits\ImageHandler;
use Illuminate\Support\Str;
use App\Models\ProductType;
use App\Models\ProductUnit;
use App\Models\Product;
use App\Models\Vendor;
use App\Models\Unit;
use App\Models\ActivityLog;
use App\Models\CartDetail;
use App\Models\ProductOrder;
use App\Models\ProductOrderDetail;

class ProductService
{
    use ImageHandler;

    public function listCategories()
    {
        return ProductCategory::select('slug', 'name')->get();
    }

    public function countCategories()
    {
        return ProductCategory::count();
    }

    public function getCategory($slug)
    {
        return ProductCategory::where('slug', $slug)->first();
    }

    public function listStand()
    {
        return ProductStand::select('slug', 'name')->get();
    }

    public function countStand()
    {
        return ProductStand::count();
    }

    public function getStand($slug)
    {
        return ProductStand::where('slug', $slug)->first();
    }

    public function listTypes()
    {
        return ProductType::select('slug', 'name')->get();
    }

    public function countTypes()
    {
        return ProductType::count();
    }

    public function getType($slug)
    {
        return ProductType::where('slug', $slug)->first();
    }

    public function listProducts($categorySlug, $offset, $limit)
    {
        $products = (isset($categorySlug) ? Product::WithCategoryAndActive($categorySlug) : Product::published());
        $products = new Product();
        if (isset($offset)) {
            $products->offset($offset);
        }
        if (isset($limit)) {
            $products->limit($limit);
        }

        $collect = collect();
        foreach ($products->with('details:stock,product_id,price_siswa,price_ppdb,size')->get() as $product) {
            $collect->push([
                'name' => $product->name,
                'slug' => $product->slug,
                'details' => $product->details->makeHidden('product_id'),
                'image' => $product->image
            ]);
        }

        return $collect;
    }

    public function countProducts($categorySlug)
    {
        return (isset($categorySlug) ? Product::WithCategoryAndActive($categorySlug) : Product::published())->count();
    }

    public function getProduct($slug)
    {
        $product = Product::where('slug',$slug)->with('details:stock,price_siswa,price_ppdb,size,product_id')->firstOrFail()->makeHidden(['type_id', 'category_id', 'vendor_id', 'status', 'deleted_at']);

        $product->details->makeHidden('product_id');

        return $product;
    }

    public function generateIndexData($nav, $request)
    {
        $collections = collect();
        foreach (ProductTypeEnum::getValues() as $type) {
            $queryProduct = Product::with('units', 'type', 'category', 'details', 'details.orderDetails')
                        ->withCount('details')
                        ->where(function($query) use ($request) {
                            $query->whereHas('productUnits', function($q) use ($request) {
                                $q->byUserRole();
                                if ($request->input('unit')) {
                                    $q->where('unit_id', $request->input('unit'));
                                }
                            });

                            if ($request->input('category')) {
                                $query->whereHas('category', function($q) use ($request) {
                                    $q->where('id', $request->input('category'));
                                });
                            }

                            return $query;
                        })
                        ->where(function ($query) use ($type) {
                            $query->whereHas('category', function($q) use ($type) {
                                $q->where('type', $type);
                            });
                            $query->orWhereHas('type', function($q) use ($type) {
                                $q->where('type', $type);
                            });
                        });

            if ($request->input('name')) {
                $name = strtolower($request->input('name'));
                $queryProduct->whereRaw("LOWER(name) like '%" . $name . "%'");
            }

            $collections->put($type, [
                'products' => $queryProduct->paginate(20),
                'categories' => ProductCategory::whereType($type)->get(),
                'units' => Unit::byUserRole()->get()->all(),
                'fragment' => $type
            ]);
        }

        return [
            'nav'           => $nav,
            'collections'   => $collections,
            'params'        => $request->except(['page']),
        ];
    }

    public function generateAddingData($nav, $type = '')
    {
        $type = $type ?: ProductTypeEnum::SERAGAM;
        $levelOpt = [
            'PA' => 'PA - Putra',
            'PI' => 'PI - Putri',
            'PA/PI' => 'PA/PI - Putra dan Putri'
        ];

        $stand = [];
        if ($type == 'kantin') {
            $stand = ProductStand::pluck('name', 'id');
        }

        return [
            'product' => false,
            'productCategory' => ProductCategory::whereType($type)->pluck('name', 'id'),
            'productStand' => $stand,
            'productType' => ProductType::whereType($type)->pluck('name', 'id'),
            'unitList' => Unit::byUserRole()->pluck('name', 'id'),
            'vendorList' => Vendor::pluck('name', 'id'),
            'levelOpt' => $levelOpt,
            'nav' => $nav,
            'type' => $type,
            'days' => ProductScheduleDayEnum::getValues(),
        ];
    }

    public function generateEditableData($id, $nav, $type = '')
    {
        $type = $type ?: ProductTypeEnum::SERAGAM;
        $levelOpt = [
            'PA' => 'PA - Putra',
            'PI' => 'PI - Putri',
            'PA/PI' => 'PA/PI - Putra dan Putri'
        ];

        $stand = [];
        if ($type == 'kantin') {
            $stand = ProductStand::pluck('name', 'id');
        }

        return [
            'method' => 'edit',
            'product' => Product::findOrFail($id),
            'productCategory' => ProductCategory::whereType($type)->pluck('name', 'id'),
            'productStand' => $stand,
            'productType' => ProductType::whereType($type)->pluck('name', 'id'),
            'unitList' => Unit::byUserRole()->pluck('name', 'id'),
            'vendorList' => Vendor::pluck('name', 'id'),
            'levelOpt' => $levelOpt,
            'nav' => $nav,
            'type' => $type,
            'days' => ProductScheduleDayEnum::getValues(),
        ];
    }

    public function generateShowData($id, $nav)
    {
        return [
            'product' => Product::with(['type', 'category', 'productUnits', 'productUnits.unit', 'vendor', 'details'])->findOrFail($id),
            'days' => ProductScheduleDayEnum::getValues(),
            'nav' => $nav
        ];
    }

    private function params($params)
    {
        if ((int) $params['category'] === 0) {
            $category = ProductCategory::firstOrNew([
                'name' => $params['category']
            ]);
            if (!$category->slug) {
                $category->slug = Str::slug($params['category']);
                $category->type = $params['product_type'] ?? null;
                $category->save();
            }
            $params['category_name'] = $params['category'];
            $params['category_id'] = $category->id;
        } else {
            $category = ProductCategory::find($params['category']);
            $params['category_name'] = $category->name;
            $params['category_id'] = $category->id;
        }

        if (isset($params['stand'])) {
            if ((int) $params['stand'] === 0) {
            $stand = ProductStand::firstOrNew([
                'name' => $params['stand']
            ]);
            if (!$stand->slug) {
                $stand->slug = Str::slug($params['stand']);
                $stand->save();
            }
            $params['stand_name'] = $params['stand'];
            $params['stand_id'] = $stand->id;
        } else {
            $stand = ProductStand::find($params['stand']);
            $params['stand_name'] = $stand->name;
            $params['stand_id'] = $stand->id;
        }
        }

        if ((int) $params['type'] === 0) {
            $type = ProductType::firstOrNew([
                'name' => $params['type']
            ]);
            if (!$type->slug) {
                $type->slug = Str::slug($params['type']);
                $type->type = $params['product_type'] ?? null;
                $type->save();
            }
            $params['type_name'] = $params['type'];
            $params['type_id'] = $type->id;
        } else {
            $type = ProductType::find($params['type']);
            $params['type_name'] = $type->name;
            $params['type_id'] = $type->id;
        }

        if (isset($params['image']) && $params['image'] && $image = $this->uploadImage(request(), $params)) {
            $params['image_path'] = $image;
        }

        $params['detail'] = collect();
        if (isset($params['sizes']) && count($params['sizes'])) {
            foreach ($params['sizes'] as $key => $val) {
                $params['detail']->push([
                    'size' => $val,
                    'stock' => $params['stocks'][$key],
                    'price_siswa' => $params['prices_siswa'][$key],
                    'price_vendor_regular' => $params['prices_vendor_regular'][$key],
                    'price_ppdb' => $params['prices_ppdb'][$key],
                    'price_vendor_ppdb' => $params['prices_vendor_ppdb'][$key],
                    'id' => isset($params['product_details_ids'][$key]) ? $params['product_details_ids'][$key] : ''
                ]);
            }
        }

        if (isset($params['units']) && count($params['units'])) {
            $units = Unit::whereIn('name', $params['units'])->get();
            if ($units->isNotEmpty()) {
                $params['units'] = $units->pluck('id')->all();
            }
        }

        return $params;
    }

    private function uploadImage($request, $params)
    {
        if ($request->hasFile('image')) {
            if ($upload = $this->doUploadImage($request->file('image'), 'product')) {
                return $upload['path_upload'];
            }
        }

        return false;
    }

    public function create($params)
    {
        $params['slug'] = Str::slug($params['name']);
        $params = $this->params($params);
        $product = Product::create($params);

        $product->syncUnits($params['units']);
        $product->syncDetails($params['detail']);

        if (isset($params['schedule'])) {
            $product->schedule()->create($params['schedule']);
        }

        return $product;
    }

    public function update($id, $params)
    {
        $product = Product::findOrFail($id);
        $params = $this->params($params);

        $product->syncUnits($params['units']);
        $syncDetailsStatus = $product->syncDetails($params['detail']);

        $product->fill($params);

        if (isset($params['schedule'])) {
            if ($product->schedule) {
                $product->schedule->fill($params['schedule']);
                $product->schedule->save();
            } else {
                $product->schedule()->create($params['schedule']);
            }
        }
        $product->save();

        $status = [
            'status' => $product->save(),
        ];
        // if ($syncDetailsStatus['errorOccurred']) {
        //     $status['errors']['syncDetails'] = $syncDetailsStatus;
        // }
        return $status;
    }

    public function updateBySlug($slug, $params)
    {
        $product = Product::where('slug', $slug)->firstOrFail();
        $params = $this->params($params);

        $product->syncUnits($params['units']);
        $product->syncDetails($params['detail']);
        $product->fill($params);
        return $product->save();
    }

    public function delete($id)
    {
        $product = Product::findOrFail($id);
        $productDetails = $product->details();
        if ($productDetails) {
            $productDetails->delete();
        }
        $productUnits = $product->productUnits();
        if ($productUnits) {
            $productUnits->delete();
        }

        return $product->delete();
    }

    public function toggleStatus($id)
    {
        $product = Product::findOrFail($id);
        $product->status = $product->isPublished() ? Product::STATUS_UNPUBLISHED : Product::STATUS_PUBLISHED;
        return $product->save();
    }

    public function generateHistoryStockData($nav)
    {
        $activityLogs = ActivityLog::with('user')
                ->where('model_type', 'ProductDetail')
                ->where(function ($query) {
                    return $query->where('data', 'like', '%stock%')
                                ->orWhere('origin', 'like', '%stock%');

                })
                ->latest();

        if (request()->username) {
            $activityLogs = $activityLogs->whereHas('user', function ($query) {
                return $query->where('username', 'like' , "%".request()->username."%");
            });
        }

        if (request()->stock) {
            $operator = [
                1 => '>',
                2 => '<'
            ];

            $activityLogs = $activityLogs->where(function ($query) use ($operator) {
                return $query->whereRaw("CAST(REPLACE(REPLACE(REPLACE(SUBSTR(data, LOCATE(':', data, LOCATE('stock', data)), 5), ',',''), '\"',''), ':', '') AS SIGNED) {$operator[request()->stock]} CAST(REPLACE(REPLACE(REPLACE(SUBSTR(origin, LOCATE(':', origin, LOCATE('stock', origin)), 5), ',',''), '\"',''), ':', '') AS SIGNED)");
            });
        }

        $activityLogs = $activityLogs->paginate();

        return [
            'nav' => $nav,
            'params' => request()->except(['page']),
            'activityLogs' => $activityLogs,
        ];
    }

    public function getUniform()
    {
        $product = Product::with(['type' => function ($query){
           $query->where('type','=','seragam');
        }])->where('status','=', Product::STATUS_PUBLISHED)->get();
        return $product;
    }

    public function getTypeName()
    {
        $type = ProductType::where('type','=','seragam')->get();
        return $type;
    }

    public function getUniformByType($type)
    {
        $product = Product::where(['type_id'=>$type,'status'=> Product::STATUS_PUBLISHED])->get();
        return $product;
    }
}
