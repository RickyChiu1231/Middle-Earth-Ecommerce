<nav class="navbar navbar-expand-lg navbar-light bg-light navbar-static-top">
  <div class="container">
    <!-- Branding Image -->
<!--
    <a class="navbar-brand " href="{{ url('/') }}">
      Middle-Earth Shop
    </a> -->
    <a class="navbar-brand " href="{{ url('/') }}">
      <img  src="{{ URL::asset('images/mee.png') }}" height="60" width="200" alt="Image">
    </a>

    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarSupportedContent">
      <!-- Left Side Of Navbar -->
      <ul class="navbar-nav mr-auto">
    <a class="navbar-brand " href="{{ url('/') }}" >
          Home
    </a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    <a class="navbar-brand " href="{{ url('products/all') }}">
      Products
    </a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    <a class="navbar-brand " href="{{ url('products/all') }}">
      Blog
    </a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    <a class="navbar-brand " href="{{ url('https://www.bizdb.co.nz/company/9429042069539/#addresses') }}">
      About Middle-Earth
    </a>
      </ul>


      <!-- Right Side Of Navbar -->
      <ul class="navbar-nav navbar-right">
        <!-- Login,register link start -->
        @guest
        <img  src="{{ URL::asset('images/login.png') }}" height="28" width="28" alt="Image"><li class="nav-item"><h5><a class="nav-link" href="{{ route('login') }}">Login</a></h5></li></img>
        <li class="nav-item"><a class="nav-link">or</a></li>
        <h5><li class="nav-item"><a class="nav-link" href="{{ route('register') }}">Create Account</a></li></h5>
        @else
        <li class="nav-item">
    <a class="nav-link mt-1" href="{{ route('cart.index') }}"><i class="fa fa-shopping-cart"></i></a>
  </li>
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <img src="https://cdn.learnku.com/uploads/images/201709/20/1/PtDKbASVcz.png?imageView2/1/w/60/h/60" class="img-responsive img-circle" width="30px" height="30px">
            {{ Auth::user()->name }}
          </a>
          <div class="dropdown-menu" aria-labelledby="navbarDropdown">
            <a href="{{ route('user_addresses.index') }}" class="dropdown-item">Shipping Address</a>
            <a href="{{ route('orders.index') }}" class="dropdown-item">My Orders</a>
            <a href="{{ route('products.favorites') }}" class="dropdown-item">My Favorites</a>
            <a class="dropdown-item" id="logout" href="#"
               onclick="event.preventDefault();document.getElementById('logout-form').submit();">Sign Out</a>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
              {{ csrf_field() }}
            </form>
          </div>
        </li>
        @endguest
        <!-- Login,register link End -->
      </ul>
    </div>
  </div>
</nav>
