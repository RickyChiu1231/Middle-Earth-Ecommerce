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
        <button type="button" class="btn btn-danger" style="display: none;" id="btn-cancel-coupon">取消</button>
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


          // 监听 全选/取消全选 单选框的变更事件
    $('#select-all').change(function() {
      // 获取单选框的选中状态
      // prop() 方法可以知道标签中是否包含某个属性，当单选框被勾选时，对应的标签就会新增一个 checked 的属性
      var checked = $(this).prop('checked');
      // 获取所有 name=select 并且不带有 disabled 属性的勾选框
      // 对于已经下架的商品我们不希望对应的勾选框会被选中，因此我们需要加上 :not([disabled]) 这个条件
      $('input[name=select][type=checkbox]:not([disabled])').each(function() {
        // 将其勾选状态设为与目标单选框一致
        $(this).prop('checked', checked);
      });
    });

    // 监听创建订单按钮的点击事件
    $('.btn-create-order').click(function () {
      // 构建请求参数，将用户选择的地址的 id 和备注内容写入请求参数
      var req = {
        address_id: $('#order-form').find('select[name=address]').val(),
        items: [],
        remark: $('#order-form').find('textarea[name=remark]').val(),
      };
      // 遍历 <table> 标签内所有带有 data-id 属性的 <tr> 标签，也就是每一个购物车中的商品 SKU
      $('table tr[data-id]').each(function () {
        // 获取当前行的单选框
        var $checkbox = $(this).find('input[name=select][type=checkbox]');
        // 如果单选框被禁用或者没有被选中则跳过
        if ($checkbox.prop('disabled') || !$checkbox.prop('checked')) {
          return;
        }
        // 获取当前行中数量输入框
        var $input = $(this).find('input[name=amount]');
        // 如果用户将数量设为 0 或者不是一个数字，则也跳过
        if ($input.val() == 0 || isNaN($input.val())) {
          return;
        }
        // 把 SKU id 和数量存入请求参数数组中
        req.items.push({
          sku_id: $(this).data('id'),
          amount: $input.val(),
        })
      });
      axios.post('{{ route('orders.store') }}', req)
        .then(function (response) {
          swal('订单提交成功', '', 'success')
          .then(() => {
            location.href = '/orders/' + response.data.id;
            });
        }, function (error) {
          if (error.response.status === 422) {
            // http 状态码为 422 代表用户输入校验失败
            var html = '<div>';
            _.each(error.response.data.errors, function (errors) {
              _.each(errors, function (error) {
                html += error+'<br>';
              })
            });
            html += '</div>';
            swal({content: $(html)[0], icon: 'error'})
          } else {
            // 其他情况应该是系统挂了
            swal('系统错误', '', 'error');
          }
        });
    });

    // 检查按钮点击事件
    $('#btn-check-coupon').click(function () {
      // 获取用户输入的优惠码
      var code = $('input[name=coupon_code]').val();
      // 如果没有输入则弹框提示
      if(!code) {
        swal('请输入优惠码', '', 'warning');
        return;
      }
      // 调用检查接口
      axios.get('/coupon_codes/' + encodeURIComponent(code))
        .then(function (response) {  // then 方法的第一个参数是回调，请求成功时会被调用
          $('#coupon_desc').text(response.data.description); // 输出优惠信息
          $('input[name=coupon_code]').prop('readonly', true); // 禁用输入框
          $('#btn-cancel-coupon').show(); // 显示 取消 按钮
          $('#btn-check-coupon').hide(); // 隐藏 检查 按钮
        }, function (error) {
          // 如果返回码是 404，说明优惠券不存在
          if(error.response.status === 404) {
            swal('Coupon not exist', '', 'error');
          } else if (error.response.status === 403) {
          // 如果返回码是 403，说明有其他条件不满足
            swal(error.response.data.msg, '', 'error');
          } else {
          // 其他错误
            swal('System error', '', 'error');
          }
        })
    });

    // 隐藏 按钮点击事件
    $('#btn-cancel-coupon').click(function () {
      $('#coupon_desc').text(''); // 隐藏优惠信息
      $('input[name=coupon_code]').prop('readonly', false);  // 启用输入框
      $('#btn-cancel-coupon').hide(); // 隐藏 取消 按钮
      $('#btn-check-coupon').show(); // 显示 检查 按钮
    });

  });
</script>
@endsection
