<?php

namespace App\Http\Controllers\Manage;

use App\Http\Requests\Manage\OrderSaveRequest;
use App\Models\Order;
use App\Traits\ManageControllerTrait;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class OrdersController extends Controller
{
    use ManageControllerTrait;

    protected $page;
    protected $Model;
    protected $browse_handle;
    protected $view_folder;

    public function __construct()
    {
        $this->Model = new Order();

        $this->browse_handle = 'selector';
        $this->view_folder = "manage.orders";
    }

    public function browse($request_tab = 'succeeded', $switch = null)
    {
        /**
         * Switches
         */
        $switches = array_normalize(array_maker($switch), [
            'post_id'      => "0",
            'created_by'   => "",
            'published_by' => "",
            'search'       => "",
            'order_by'     => "created_at",
            'order_type'   => "desc",
            'criteria'     => $request_tab,
            'is_by_admin'  => "0",
        ]);

        /**
         * Page Browse
         */
        $page = [
            ["orders", trans('front.orders'), "orders/"],
        ];
        if (!is_null(Order::statusesCodes($request_tab))) {
            $page[] = [$request_tab, trans("forms.status_text.$request_tab"), "orders/$request_tab"];
        } else {
            $page[] = [$request_tab, trans("posts.criteria.$request_tab"), "orders/$request_tab"];
        }

        /**
         * Model
         */
        $models = Order::selector($switches)
            ->orderBy($switches['order_by'], $switches['order_type'])
            ->paginate(user()->preference('max_rows_per_page'));
        $db = $this->Model;
        $viewFolder = $this->view_folder;

        /*-----------------------------------------------
        * View
        */
        return view($this->view_folder . ".browse", compact(
            'page',
            'models',
            'db',
            'switches',
            'switch',
            'viewFolder'
        ));
    }

    public function save(OrderSaveRequest $request)
    {
        /*-----------------------------------------------
        | Model Selection ...
        */
        $model = Order::find($request->id);
        if (!$model or !$model->exists) {
            return $this->jsonFeedback(trans('validation.http.Error410'));
        }

        /*-----------------------------------------------
        | Permission ...
        */
        if (!$model->can('edit')) {
            return $this->jsonFeedback(trans('validation.http.Error403'));
        }

        /*-----------------------------------------------
        | Save ...
        */
        $ok = Order::store($request);

        /*-----------------------------------------------
        | Feedback ...
        */

        return $this->jsonAjaxSaveFeedback($ok, [
            'success_callback' => "rowUpdate('tblOrders','$request->id')",
        ]);

    }
}
