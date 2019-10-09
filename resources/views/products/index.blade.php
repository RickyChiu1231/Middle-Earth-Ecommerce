@extends('layouts.app')
@section('title', 'Product List')

@section('content')
<div class="row">
<div class="col-lg-10 offset-lg-1">
<div class="card">
  <div class="card-body">



<!-- banner -->
<div class="col-12">

      <img  src="{{ URL::asset('images/welcomebanner.png') }}" height="240" width="850" alt="Milkproduct">

</div>


<div align="middle">
<table>
<tr>


<td>
<a href="{{ route('products.indexmilk') }}">
<div style="position:relative;">

　　<img  src="{{ URL::asset('images/milkad.png') }}" height="135" width="270" alt="Milkproduct">

　　<div style="position:absolute; color:white; z-index:2; left:10px; top:10px">
　　　　<b>Milk Product</b>
　　</div>
</div>
</a>
</td>


<td>&nbsp;&nbsp;</td>

<td>
    <a href="{{ route('products.indexhealth') }}">
<div style="position:relative;">

　　<img  src="{{ URL::asset('images/healthad.png') }}" height="135" width="270" alt="Milkproduct">

　　<div style="position:absolute; color:white; z-index:2; left:10px; top:10px">
　　　　<b>Health Product</b>
　　</div>
</div>
</a>
</td>

<td>&nbsp;&nbsp;</td>

<td>
    <a href="{{ route('products.indexothers') }}">
<div style="position:relative;">

　　<img  src="{{ URL::asset('images/sheepad.png') }}" height="135" width="270" alt="Milkproduct">

　　<div style="position:absolute; color:white; z-index:2; left:10px; top:10px">
　　　　<b>Sheep Product</b>
　　</div>
</div>
</a>
</td>

</tr>
</table>
</div>
<br />

<!--
<div align="middle">
<a class="navbar-brand " href="{{ url('/') }}">
      <img  src="{{ URL::asset('images/healthad.png') }}" height="135" width="270" alt="Milkproduct">
    </a>

<a class="navbar-brand " href="{{ url('/') }}">
      <img  src="{{ URL::asset('images/sheepad.png') }}" height="135" width="270" alt="Milkproduct">
    </a>

<a class="navbar-brand " href="{{ url('/') }}">
      <img  src="{{ URL::asset('images/milkad.png') }}" height="135" width="270" alt="Milkproduct">
    </a>

</div>
-->


<!-- End of testing -->

<h2 ><a style = "color:red" href="{{ route('products.indexhotsell') }}">Hot Sell now!!!</a></h2>
    <div class="row products-list">
      @foreach($category4->products as $product)
        <div class="col-3 product-item">
          <div class="product-content">
            <div class="top">
              <div class="img">
                <a href="{{ route('products.show', ['product' => $product->id]) }}">
                  <img src="{{ $product->image_url }}" alt="">
                </a>
              </div>
              <div class="price"><b>$</b>{{ $product->price }}</div>
              <div class="title">
                <a href="{{ route('products.show', ['product' => $product->id]) }}">{{ $product->title }}</a>
              </div>
            </div>
            <div class="bottom">
              <div class="sold_count">Quantity of sale <span>{{ $product->sold_count }}</span></div>
              <div class="review_count">Review <span>{{ $product->review_count }}</span></div>
            </div>
          </div>
        </div>
      @endforeach
    </div>

<!--

<h2><a href="{{ route('products.indexhealth') }}">Health</a></h2>
    <div class="row products-list">
      @foreach($category2->products as $product)
        <div class="col-3 product-item">
          <div class="product-content">
            <div class="top">
              <div class="img">
                <a href="{{ route('products.show', ['product' => $product->id]) }}">
                  <img src="{{ $product->image_url }}" alt="">
                </a>
              </div>
              <div class="price"><b>$</b>{{ $product->price }}</div>
              <div class="title">
                <a href="{{ route('products.show', ['product' => $product->id]) }}">{{ $product->title }}</a>
              </div>
            </div>
            <div class="bottom">
              <div class="sold_count">Quantity of sale <span>{{ $product->sold_count }}</span></div>
              <div class="review_count">Review <span>{{ $product->review_count }}</span></div>
            </div>
          </div>
        </div>
      @endforeach
    </div>




<h2><a href="{{ route('products.indexothers') }}">Sheep</a></h2>
    <div class="row products-list">
      @foreach($category3->products as $product)
        <div class="col-3 product-item">
          <div class="product-content">
            <div class="top">
              <div class="img">
                <a href="{{ route('products.show', ['product' => $product->id]) }}">
                  <img src="{{ $product->image_url }}" alt="">
                </a>
              </div>
              <div class="price"><b>$</b>{{ $product->price }}</div>
              <div class="title">
                <a href="{{ route('products.show', ['product' => $product->id]) }}">{{ $product->title }}</a>
              </div>
            </div>
            <div class="bottom">
              <div class="sold_count">Quantity of sale <span>{{ $product->sold_count }}</span></div>
              <div class="review_count">Review <span>{{ $product->review_count }}</span></div>
            </div>
          </div>
        </div>
      @endforeach
    </div>
-->



  </div>
</div>
</div>
</div>
@section('scriptsAfterJs')
  <script>
    var filters = {!! json_encode($filters) !!};
    $(document).ready(function () {
      $('.search-form input[name=search]').val(filters.search);
      $('.search-form select[name=order]').on('change', function() {
        $('.search-form').submit();
      });
    })
  </script>
@endsection
@endsection
