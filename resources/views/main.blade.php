@extends('template')

@section('content')

<div class="section">
  <div class="container">
    <div class="row">
        <!-- section title -->
      <div class="col-md-12">
        <div class="section-title">
          <h3 class="title">Our Products</h3>
          <div class="section-nav">
            <ul class="section-tab-nav tab-nav">
              @for($i = 0; $i < count($data); $i++)
                @if ($i == 0)
                  <li data-toggle="tab{{ $data[$i]['tab_name'] }}" class="active"><a href="#">{{ $data[$i]['tab_name'] }}</a></li>
                @else
                  <li data-toggle="tab{{ $data[$i]['tab_name'] }}"><a href="#">{{ $data[$i]['tab_name'] }}</a></li>
                @endif
              @endfor
            </ul>
          </div>
        </div>
    </div>

    <div class="col-md-12">
      <div class="row">
        <div class="products-tabs">

          @for($i = 0; $i < count($data); $i++)
            @if ($i == 0)
              <div id="tab{{ $data[$i]['tab_name'] }}" class="tab-pane active">
            @else
              <div id="tab{{ $data[$i]['tab_name'] }}" class="tab-pane">
            @endif
                <div class="products-slick" data-nav="#slick-nav-{{ $i }}">
                  <!-- product -->
                  @foreach ($data[$i]['products'] as $product)
                    <div class="product">
                      <div class="product-img">
                        <img src="{{ asset('./img/'.$product['img_name']) }}" alt="">
                        <div class="product-label">
                          <span class="discount">-{{ $product['discount'] }}%</span>
                          {{-- <span class="new">NEW</span> --}}
                        </div>
                      </div>
                      <div class="product-body">
                        {{-- <p class="product-category">Category</p> --}}
                        <h3 class="product-name"><a href="#">{{ $product['name'] }}</a></h3>
                        <h4 class="product-price"><span class="rupiah">{{ $product['price'] - ($product['price'] * ($product['discount'] / 100)) }}</span> <del class="product-old-price rupiah">{{ $product['price'] }}</del></h4>
                      </div>
                      <div class="add-to-cart">
                        <button 
                            data-product_id ="{{ $product['id'] }}" 
                            data-name = "{{ $product['name']}}"
                            data-price="{{ $product['price'] - ($product['price'] * ($product['discount'] / 100)) }}" class="add-to-cart-btn"><i class="fa fa-shopping-cart"
                        ></i> add to cart
                        </button>
                      </div>
                    </div>
                  @endforeach
                  <!-- /product -->
                </div>
                <div id="slick-nav-{{ $i }}" class="products-slick-nav"></div>
              </div>
          @endfor
          
        </div>
      </div>
    </div>


  </div>
</div>

@endsection