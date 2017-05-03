<div class="field add-to-card mt20">
    <label>{{ trans('cart.number') }}</label>
    <div class="row">
        <div class="col col-sm-3">
            <input type="number" value="1" min="1">
        </div>
        <div class="col col-sm-3">
            <div class="select">
                <select>
                    <option value="1">{{ trans('cart.units.kilogram') }}</option>
                    <option value="2">{{ trans('cart.units.package') }}</option>
                </select>
            </div>
        </div>
        <div class="col col-sm-3">
            <button class="block green">{{ trans('cart.add_to_cart') }}</button>
        </div>
    </div>
</div>