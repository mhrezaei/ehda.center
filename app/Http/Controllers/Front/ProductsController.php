<?php

namespace App\Http\Controllers\Front;

use App\Http\Requests\Front\ProductsFilterRequest;
use App\Http\Requests\Front\PurchaseProductRequest;
use App\Http\Requests\Front\TrackPurchasementRequest;
use App\Models\Category;
use App\Models\File;
use App\Models\FileDownloads;
use App\Models\Folder;
use App\Models\Order;
use App\Models\OrderPost;
use App\Models\Post;
use App\Models\Posttype;
use App\Providers\PostsServiceProvider;
use App\Traits\ManageControllerTrait;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Session\Store;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\URL;

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
        $folder->spreadMeta();

        $selectorData = [
            'type'         => 'products',
            'is_base_page' => true,
            'folder'       => $folder->slug,
        ];

        $ogData = [
            'description' => $folder->title,
        ];

        if ($folder->image) {
            $ogData['image'] = url($folder->image);
        }

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
                $this->abort(410);
            }
            $breadCrumb[] = [$category->title, url_locale('products/' . $folder->slug . '/' . $category->slug)];
            $selectorData['category'] = $category->slug;
            $ogData['description'] .= ' - ' . $category->title;
        }

        $productsListHTML = PostsServiceProvider::showList($selectorData);

        return view('front.products.products.0', compact('productsListHTML', 'breadCrumb', 'ogData'));

    }

    public function showProduct($lang, $identifier)
    {
        $identifier = substr($identifier, strlen($this->productPrefix));

        $dehashed = hashid_decrypt($identifier, 'ids');
        if (is_array($dehashed) and is_numeric($identifier = $dehashed[0])) {
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
        $product->spreadMeta();

        $breadCrumb = [
            [trans('front.home'), url_locale('')],
            [trans('front.products'), url_locale('products')],
        ];

        $categories = $product->categories;
        if ($categories->count()) {
            $firstCat = $categories->first();
            $breadCrumb[] = [$firstCat->title, url_locale('products/' . $firstCat->folder->slug . '/' . $firstCat->slug)];
        }
        $breadCrumb[] = [$product->title, url_locale('products/' . $this->productPrefix . ($product->slug ? $product->slug : $product->id))];

        $ogData = [
            'title' => $product->title,
        ];

        if ($product->viewable_featured_image) {
            $ogData['image'] = url($product->viewable_featured_image);
        }
        if ($product->abstract) {
            $ogData['description'] = $product->abstract;
        }

        $postHTML = PostsServiceProvider::showPost($product);

        return view('front.products.single.0', compact('postHTML', 'breadCrumb', 'ogData'));
    }

    public function ajaxFilter(Request $request)
    {
        $hash = $request->hash;
        $hashArray = [];
        $selectorData = [
            'type'          => 'products',
            'show_filter'   => false,
            'ajax_request'  => true,
            'paginate_hash' => $hash,
            'paginate_url'  => URL::previous(),
            'max_per_page'  => 2,
        ];

        $referrer = URL::previous();
        $prefix = url_locale('products');
        $prefixLength = strlen($prefix);
        if ((substr($referrer, 0, $prefixLength) == $prefix)) {
            $importantUrl = substr($referrer, $prefixLength);
            $parametersParts = explodeNotEmpty('/', $importantUrl);
            $folderSlug = $parametersParts[0];
            $categorySlug = array_key_exists(1, $parametersParts) ? $parametersParts[1] : null;

            $folder = Folder::where(['slug' => $folderSlug, 'locale' => getLocale()])->first();
            if (!$folder) {
                return redirect($referrer);
            }
            $selectorData['folder'] = $folder->slug;

            if ($categorySlug) {
                $category = Category::findBySlug($categorySlug);
                if (!$category->exists or !$folder->is($category->folder)) {
                    return redirect($referrer);
                }
                $selectorData['category'] = $category->slug;
            } else {
                $selectorData['category'] = $folder->slug;
            }

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
                                $selectorData['conditions'][] = ['sale_price', '>=', $value['min']];
                                $selectorData['conditions'][] = ['sale_price', '<=', $value['max']];
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
                                $selectorData['conditions'][] = ['sale_price', '!=', '0'];
                                $selectorData['conditions'][] = ['price', '!=', 'sale_price'];
                                break;
                        }
                    }
                }
            }

            if (isset($hashArray['pagination'])) {
                foreach ($hashArray['pagination'] as $field => $value) {
                    if ($value) {
                        switch ($field) {
                            case 'page':
                                $selectorData['paginate_current'] = $value;
                                break;
                        }
                    }
                }
            }

            if (isset($hashArray['sort'])) {
                foreach ($hashArray['sort'] as $field => $value) {
                    if ($value) {
                        switch ($field) {
                            case 'price': // accepted sort fields
                                $selectorData['sort'] = $value;
                                $selectorData['sort_by'] = $field;
                                break;
                        }
                    }
                }
            }

            return PostsServiceProvider::showList($selectorData);
        }
    }

    public function archive()
    {
        /************************* Checking Products Post Type Existence ********************** START */
        $postType = Posttype::findBySlug('products');
        if (!$postType->exists) {
            return $this->abort('404');
        }
        /************************* Checking Products Post Type Existence ********************** END */

        /************************* Selecting Slider Data ********************** START */
        $slideShow = PostsServiceProvider::collectPosts([
            'type'     => 'slideshows',
            'category' => 'products-slider',
        ]);
        /************************* Selecting Slider Data ********************** END */

        /************************* Generating List View ********************** START */
        $postsListHtml = PostsServiceProvider::showList([
            'type' => 'products'
        ]);

        if (!$postsListHtml) {
            return redirect(getLocale());
        }
        /************************* Generating List View ********************** END */

        /************************* Generating View Data ********************** START */
        $compactedData = compact('slideShow', 'postType', 'postsListHtml');
        $otherData = [];
        $viewData = array_merge($compactedData, $otherData);
        /************************* Generating View Data ********************** END */

        /************************* Returning View ********************** START */
        return view('front.products.archive.main', $viewData);
    }

    public function purchase(PurchaseProductRequest $request)
    {
        $postId = $request->post_id;
        $post = Post::findBySlug($postId, 'id');


        $orderPostData = [
            'original_price' => $post->price,
            'offered_price'  => $request->price,
            'total_price'    => $request->price,
        ];

        $orderData = [
            'user_id'        => (auth()->guest()) ? null : user()->id,
            'code_melli'     => $request->code_melli,
            'name'           => $request->name,
            'mobile'         => $request->mobile,
            'phone'          => $request->phone,
            'email'          => $request->email,
            'status'         => 0, // just created
            'invoice_amount' => $orderPostData['total_price'],
            'payable_amount' => $orderPostData['total_price'],
            'paid_amount'    => 0,
        ];
        $orderId = Order::store($orderData);
        $order = Order::findBySlug($orderId, 'id');
        $orderPostData['order_id'] = $orderId;

        // @todo: uncomment these lines
//        $trackingNumber = invoice($post->price, route_locale('education.paymentResult', [
//                'order' => $order->hashid
//            ])
//        )->getTracking();
//        $payment = gateway()->fire($trackingNumber);
//
//        if (!$payment) {
//            return $this->jsonAjaxSaveFeedback(0, [
//                'danger_message' => trans('front.gateway.disabled')
//            ]);
//        }
//
        // @todo: remove this line
        $trackingNumber = '222222';

        $order->tracking_number = $trackingNumber;
        $order->save();

        $order->storePosts($post->id, $orderPostData);

        return $this->jsonAjaxSaveFeedback($orderId, [
//            @todo comment this line
//            'success_redirect' => $payment,
            'redirectTime' => 2000,
        ]);
    }

    public function paymentResult($lang, $order)
    {
        $order = Order::findByHashid($order);
        $trackingNumber = Input::get('tracking');

        // If there is no "tracking" query param, we will redirect to home page
        if (!$trackingNumber) {
            return redirect(getLocale());
        }

        // If there is no order with this info we will show 404 error
        if (!$order->exists) {
            return $this->abort('404');
        }

        if ($order->status == 0) {
            // If there is no post with this info we will show 404 error
            $post = $order->posts()->first();
            if (!$post->exists) {
                return $this->abort('404', request()->ajax());
            }

            $redirectUrl = $post->direct_url . '#payment-result';
            $flashData = ['trackingNumber'];
            $flashData['product-order-' . $post->hashid] = $order->id;

//          peyment_verify($trackingNumber) @todo: check it!!!
            if (true or peyment_verify($trackingNumber)) {
                // If received tracking number doesn't match with order's tracking number, we will show 403 error
                if ($order->tracking_number != $trackingNumber) {
                    return $this->abort(403);
                }

                $order->status = 1; // Succeeded
                $flashData['paymentSucceeded'] = true;

                $this->serveDownload($post, $order);

            } else {
                $flashData['paymentSucceeded'] = false;

                $order->status = -1; // Canceled

            }

            $order->save();
            return redirect($redirectUrl)->with($flashData);

        } else {
            return $this->abort(404);
        }
    }

    public function track(TrackPurchasementRequest $request)
    {
        $order = Order::where([
            'tracking_number' => $request->tracking_number,
            'status'          => 1
        ])->first();

        if ($order) {
            // If there is no post with this info we will show 404 error
            $post = $order->posts()->first();
            if (!$post->exists) {
                return $this->abort('404', request()->ajax());
            }

            $redirectUrl = $post->direct_url . '#payment-result';
            $flashData = ['paymentSucceeded' => true];

            $this->serveDownload($post, $order);


//            // @todo: remove this line
//            session()->reFlash();

            $flashData['product-order-' . $post->hashid] = $order->id;

//            ss(session()->all()['_flash']);
            foreach ($flashData as $flashName => $flashValue) {
                session()->flash($flashName, $flashValue);
            }
            session()->reFlash();

            return $this->jsonFeedback([
                'ok'       => 1,
                'message'  => trans('forms.feed.wait'),
                'redirect' => $redirectUrl,
            ]);
        }
    }

    protected function serveDownload($post, $order)
    {
        $post->spreadMeta();

        $files = $post->post_files;

        if ($files and is_array($files) and count($files)) {
            foreach ($files as $file) {
                $fileRow = File::findByHashid($file['src']);
                if ($fileRow->exists) {
                    $similarRow = FileDownloads::where([
                        'file_id'  => $fileRow->id,
                        'order_id' => $order->id,
                    ])->first();
                    if (!$similarRow or !$similarRow->exists) {
                        FileDownloads::store([
                            'user_id'            => (auth()->guest()) ? null : user()->id,
                            'file_id'            => $fileRow->id,
                            'order_id'           => $order->id,
                            'downloadable_count' => 1,
                        ]);
                    }
                }
            }
        }

    }
}
