<?php

namespace App\Http\Controllers\Front;

use App\Http\Requests\Front\ProductsFilterRequest;
use App\Models\Category;
use App\Models\Folder;
use App\Models\Post;
use App\Providers\PostsServiceProvider;
use App\Traits\ManageControllerTrait;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ProductsController extends Controller
{
    use ManageControllerTrait;

    private $productPrefix = 'pd-';

    public function index()
    {
        $categories = Folder::where('posttype_id', 2)
            ->where([
                'locale' => getLocale(),
                ['slug', '<>', 'no']
            ])
            ->orderBy('title', 'asc')
            ->get();

        return view('front.products.folders.0', compact('categories'));
    }

    public function products($lang, $folderSlug, $categorySlug = null)
    {
        $folder = Folder::where(['slug' => $folderSlug, 'locale' => getLocale()])->first();
        if (!$folder) {
            $this->abort(410);
        }

        $selectConditions = ['type' => 'products'];

        $breadCrumb = [
            [trans('front.home'), url_locale('')],
            [trans('front.products'), url_locale('products')],
            [$folder->title, url_locale('products/' . $folder->slug)],
        ];

        if ($categorySlug) {
            $category = Category::findBySlug($categorySlug);
            if (!$category->exists) {
                $this->abort(410);
            }
            if (!$folder->is($category->folder)) {
                $this->abort(404);
            }
            $breadCrumb[] = [$category->title, url_locale('products/' . $folder->slug . '/' . $category->slug)];
            $selectConditions['category'] = $category->slug;
        } else {
            $selectConditions['category'] = $folder->slug;
        }

        return view('front.products.products.0', compact('selectConditions', 'breadCrumb'));

    }

    public function showProduct($lang, $identifier)
    {
        $identifier = substr($identifier, strlen($this->productPrefix));

        if (is_numeric($identifier)) {
            $field = 'id';
        } else {
            $field = 'slug';
        }
        $product = Post::where([
            $field => $identifier,
            'type' => 'products'
        ])->first();

        if (!$product) {
            $this->abort(410);
        }

        $breadCrumb = [
            [trans('front.home'), url_locale('')],
            [trans('front.products'), url_locale('products')],
            [$product->title, url_locale('products/' . $this->productPrefix . ($product->slug ? $product->slug : $product->id))],
        ];

        return view('front.products.single.0', compact('product', 'breadCrumb'));
    }


    public function ajaxFilter(Request $request)
    {
        $hash = $request->hash;
        $hashArray = [];
        $selectorData = [
            'type' => 'products',
            'show_filter' => false,
            'ajax_request' => true,
        ];
        $conditions = [];

        $hash = explodeNotEmpty('!', $hash);
        foreach ($hash as $field) {
            $field = explodeNotEmpty('?', $field);
            if (count($field) == 2) {
                $hashArray[$field[1]] = explodeNotEmpty('/', $field[0]);
                $currentGroup = &$hashArray[$field[1]];
                $currentGroup = arrayPrefixToIndex('_', $currentGroup);
            }
        }


        if (isset($hashArray['text'])) {
            foreach ($hashArray['text'] as $field => $value) {
                switch ($field) {
                    case 'title':
                        $selectorData['search'] = $value;
                        break;
                }
            }
        }

        if (isset($hashArray['range'])) {
            foreach ($hashArray['range'] as $field => $value) {
                switch ($field) {
                    case 'price':
                        if (isset($value['min']) and isset($value['max']) and $value['min'] and $value['max']) {
                            $selectorData['conditions'][] = ['price', '>=', $value['min']];
                            $selectorData['conditions'][] = ['price', '<=', $value['max']];
                        }
                        break;
                }
            }
        }

        if (isset($hashArray['checkbox'])) {
            foreach ($hashArray['checkbox'] as $field => $value) {
                switch ($field) {
                    case 'category':
                        if (is_array($value)) {
                            $noCatIndex = array_search('', $value);
                            if ($noCatIndex !== false) {
                                $value[$noCatIndex] = 'no';
                            }
                            $selectorData['category'] = $value;
                        }
                        break;
                }
            }
        }

        if (isset($hashArray['switchKey'])) {
            foreach ($hashArray['switchKey'] as $field => $value) {
                if ($value) {
                    switch ($field) {
                        case 'available':
                            $selectorData['conditions'][] = ['is_available', true];
                            break;
                        case 'special-sale':
                            // @TODO: next line will work when "sale_price" is defined as a column in "posts" table
//                            $selectorData['conditions'][] = ['price', '<>', 'sale_price'];
                            break;
                    }
                }
            }
        }
//        dd($selectorData);

        return PostsServiceProvider::showList($selectorData);
//        dd($selectorData);
    }

}
