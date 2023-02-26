@extends('template')

@section('content')

<div class="section">
  <div class="container">
    <div class="row">

      <div class="col-md-6">
        <div class="billing-details">
          <div class="section-title">
            <h3 class="title">Customer Detail</h3>
          </div>
          @foreach ($user as $key => $value)
            <div class="form-group">
              <label for="">{{ $key }}</label>
              <input class="input" type="text" readonly value="{{ $value }}">
            </div>
          @endforeach
        </div>
      </div>

      <div class="col-md-6 order-details">
        <div class="section-title text-center">
            <h3 class="title">Your Order</h3>
        </div>
        <table class="table">
          <tr>
            <td><strong>PRODUCT</strong></td>
            <td style="text-align: right"><strong>TOTAL</strong></td>
          </tr>
          @for ($i = 0; $i < count($order_items); $i++)
            <tr>
              <td><span id="qtyItem{{ $i }}">{{ $order_items[$i]->qty }}x</span> {{ $order_items[$i]->name }}</td>
              <td class="rupiah" style="text-align: right" id="totalItem{{ $i }}">{{ ( $order_items[$i]->price - ( $order_items[$i]->price * ($order_items[$i]->discount / 100) ) ) * $order_items[$i]->qty }}</td>
            </tr>
          @endfor
          <tr>
            <td>Coupon Discount</td>
            <td style="text-align: right">{{ $order->coupon_discount }}</td>
          </tr>
          <tr>
            <td>Total</td>
            <td style="text-align: right"><del class="rupiah" style="text-align: right">{{ $order->total_order }}</del> <span class="rupiah">{{ $order->total_amount }}</span></td>
          </tr>
          @if($order->status == 'UNPAID')
            <tr>
              <td colspan="2" style="text-align: center"><button class="btn btn-danger" id="pay-button" >Pay Now</button></td>
            </tr>
          @elseif($order->status == 'PAID')
            <tr>
              <td colspan="2" style="text-align: center"><button class="btn btn-success">Payment has been paid</button></td>
            </tr>
          @endif
        </table>
      </div>
    </div>
  </div>
</div>

<form action="/api/webhook-midtrans" id="submitForm" method="POST">
  @csrf
  <input type="hidden" name="json" id="callbackJson">
  <input type="hidden" name="snapToken" id="snapToken">
</form>

{{-- MIDTRANS --}}
<script type="text/javascript">
  // For example trigger on button clicked, or any time you need
  var payButton = document.getElementById('pay-button');
  payButton.addEventListener('click', function () {
      // Trigger snap popup. @TODO: Replace TRANSACTION_TOKEN_HERE with your transaction token
      window.snap.pay('{{ $snapToken }}', {
      onSuccess: function(result){
        /* You may add your own implementation here */
        alert("payment success!"); console.log(result);
        sendResponseToForm(result)
      },
      onPending: function(result){
        /* You may add your own implementation here */
        alert("wating your payment!"); console.log(result);
        sendResponseToForm(result)
      },
      onError: function(result){
        /* You may add your own implementation here */
        alert("payment failed!"); console.log(result);
        sendResponseToForm(result)
      },
      onClose: function(){
        /* You may add your own implementation here */
        alert('you closed the popup without finishing the payment');
        sendResponseToForm(result)
      }
    })
  });

  function sendResponseToForm(result){
    document.getElementById('callbackJson').value = JSON.stringify(result);
    document.getElementById('snapToken').value = "{{ $snapToken }}";
    document.getElementById('submitForm').submit();
  }
</script>

@endsection