@extends('layouts.app')
@section('title', 'Shopping Cart')

@section('content')
<div class="row">
<div class="col-lg-10 offset-lg-1">
<div class="card">
  <div class="card-header">My Shopping Cart</div>
  <div class="card-body">
    <table class="table table-striped">
      <thead>
      <tr>
        <th><input type="checkbox" id="select-all"></th>
        <th>Product Specification</th>
        <th>Price</th>
        <th>Quantity</th>
        <th>Action</th>
      </tr>
      </thead>
      <tbody class="product_list">
      @foreach($cartItems as $item)
        <tr data-id="{{ $item->productSku->id }}">
          <td>
            <input type="checkbox" name="select" value="{{ $item->productSku->id }}" {{ $item->productSku->product->on_sale ? 'checked' : 'disabled' }}>
          </td>
          <td class="product_info">
            <div class="preview">
              <a target="_blank" href="{{ route('products.show', [$item->productSku->product_id]) }}">
                <img src="{{ $item->productSku->product->image_url }}">
              </a>
            </div>
            <div @if(!$item->productSku->product->on_sale) class="not_on_sale" @endif>
              <span class="product_title">
                <a target="_blank" href="{{ route('products.show', [$item->productSku->product_id]) }}">{{ $item->productSku->product->title }}</a>
              </span>
              <span class="sku_title">{{ $item->productSku->title }}</span>
              @if(!$item->productSku->product->on_sale)
                <span class="warning">The product is not on sale</span>
              @endif
            </div>
          </td>
          <td><span class="price">${{ $item->productSku->price }}</span></td>
          <td>
            <input type="text" class="form-control form-control-sm amount" @if(!$item->productSku->product->on_sale) disabled @endif name="amount" value="{{ $item->amount }}">
          </td>
          <td>
            <button class="btn btn-sm btn-danger btn-remove">remove</button>
          </td>
        </tr>
      @endforeach
      </tbody>
    </table>

    <div>
  <form class="form-horizontal" role="form" id="order-form">
    <div class="form-group row">
      <label class="col-form-label col-sm-3 text-md-right">Select Shipping Address</label>
      <div class="col-sm-9 col-md-7">
        <select class="form-control" name="address">
          @foreach($addresses as $address)
            <option value="{{ $address->id }}">{{ $address->full_address }} {{ $address->contact_name }} {{ $address->contact_phone }}</option>
          @endforeach
        </select>
      </div>
    </div>
    <div class="form-group row">
      <label class="col-form-label col-sm-3 text-md-right">Remarks</label>
      <div class="col-sm-9 col-md-7">
        <textarea name="remark" class="form-control" rows="3"></textarea>
      </div>
    </div>

    <!-- Coupon start -->
    <div class="form-group row">
      <label class="col-form-label col-sm-3 text-md-right">Coupon</label>
      <div class="col-sm-4">
        <input type="text" class="form-control" name="coupon_code">
        <span class="form-text text-muted" id="coupon_desc"></span>
      </div>
      <div class="col-sm-3">
        <button type="button" class="btn btn-success" id="btn-check-coupon">Check</button>
        <button type="button" class="btn btn-danger" style="display: none;" id="btn-cancel-coupon">Cancel</button>
      </div>
    </div>
    <!-- end -->




    <div class="form-group">
      <div class="offset-sm-3 col-sm-3">
        <button type="button" class="btn btn-primary btn-create-order">Confirm and Pay</button>
      </div>
    </div>
  </form>
</div>


  </div>
</div>
</div>
</div>
@endsection

@section('scriptsAfterJs')
<script>
  $(document).ready(function () {
    // Listen for the click event of the remove button
    $('.btn-remove').click(function () {
      // $(this) gets the jQuery object of the currently removed button
      // The closest() method gets the first ancestor element of the matching selector, here the <tr> tag above the remove button of the current click
      // The data('id') method gets the value of the data-id property we set earlier, which is the corresponding SKU id.
      var id = $(this).closest('tr').data('id');
      swal({
        title: "Are you sure to remove this item？",
        icon: "warning",
        buttons: ['Cancel', 'Yes'],
        dangerMode: true,
      })
      .then(function(willDelete) {
        // The user will click the OK button and the value of willDelete will be true, otherwise false
        if (!willDelete) {
          return;
        }
        axios.delete('/cart/' + id)
          .then(function () {
            location.reload();
          })
          });
      });


          // Listen all-select/deselect all-selection box change event
    $('#select-all').change(function() {
      // Get the selected state of the radio button
      // prop() method can know whether the tag contains an attribute. When the radio button is checked, the corresponding tag will add a checked attribute.
      var checked = $(this).prop('checked');
      // Get all checkboxes with name=select and no disabled attribute
      // The check box corresponding to the item that has been removed should not be selected, so you need to add the condition :not([disabled])
      $('input[name=select][type=checkbox]:not([disabled])').each(function() {
        // Set its check status to match the target radio button
        $(this).prop('checked', checked);
      });
    });

    // Listen for click events that create an order button
    $('.btn-create-order').click(function () {
      // Build request parameters, write the id and comment content of the user-selected address to the request parameters
      var req = {
        address_id: $('#order-form').find('select[name=address]').val(),
        items: [],
        remark: $('#order-form').find('textarea[name=remark]').val(),

        coupon_code: $('input[name=coupon_code]').val(),
      };
      // Traverse all <tr> tags with the data-id attribute in the <table> tag, which is the item SKU in each shopping cart.
      $('table tr[data-id]').each(function () {
        // Get the current row of radio buttons
        var $checkbox = $(this).find('input[name=select][type=checkbox]');
        // Skip if the radio button is disabled or not selected
        if ($checkbox.prop('disabled') || !$checkbox.prop('checked')) {
          return;
        }
        // Get the current number of rows in the input box
        var $input = $(this).find('input[name=amount]');

        // If the user sets the quantity to 0 or not a number, skip it too
        if ($input.val() == 0 || isNaN($input.val())) {
          return;
        }
        // Store the SKU id and quantity in the request parameter array
        req.items.push({
          sku_id: $(this).data('id'),
          amount: $input.val(),
        })
      });
      axios.post('{{ route('orders.store') }}', req)
        .then(function (response) {
          swal('Order place successful', '', 'success')
          .then(() => {
            location.href = '/orders/' + response.data.id;
            });
        }, function (error) {
          if (error.response.status === 422) {

            // Http status code is 422 for user input verification failure
            var html = '<div>';

            _.each(error.response.data.errors, function (errors) {
              _.each(errors, function (error) {
                html += error+'<br>';
              })
            });
            html += '</div>';
            swal({content: $(html)[0], icon: 'error'})
          } else if (error.response.status === 403) {
            var html = '<div>' +error.response.data.msg+'<br></div>';
            swal({content: $(html)[0], icon: 'error'})
          } else {
            // otherwise shoud be system error
            swal('System error', '', 'error');
          }
        });
    });

    // Check button click event
    $('#btn-check-coupon').click(function () {
      // Get the coupon code entered by the user
      var code = $('input[name=coupon_code]').val();
      // If there is no input, the box prompts
      if(!code) {
        swal('Please enter a coupon code', '', 'warning');
        return;
      }
      // Call the check interface
      axios.get('/coupon_codes/' + encodeURIComponent(code))
        .then(function (response) {  // The first argument to the then method is a callback, which is called when the request succeeds.
          $('#coupon_desc').text(response.data.description); // Output coupon info
          $('input[name=coupon_code]').prop('readonly', true); // Disable input box
          $('#btn-cancel-coupon').show(); // Show cancel button
          $('#btn-check-coupon').hide(); // Hide check button
        }, function (error) {
          // If the return code is 404, the coupon does not exist.
          if(error.response.status === 404) {
            swal('Coupon not exist', '', 'error');
          } else if (error.response.status === 403) {
          // If the return code is 403, there are other conditions that are not met.
            swal(error.response.data.msg, '', 'error');
          } else {
          // Other error
            swal('System error', '', 'error');
          }
        })
    });

    // Hide button click event
    $('#btn-cancel-coupon').click(function () {
      $('#coupon_desc').text(''); // Hide coupon information
      $('input[name=coupon_code]').prop('readonly', false);  // Enable input box
      $('#btn-cancel-coupon').hide(); // Hide cancel button
      $('#btn-check-coupon').show(); // Show check button
    });

  });
</script>
@endsection
