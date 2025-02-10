<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</head>
<body>
<nav class="navbar navbar-expand-lg" style="background-color: rgb(209, 188, 83); box-shadow: 0px 8px rgb(177, 115, 40);" data-bs-theme="light">
 <div class="container-fluid">
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse justify-content-between" id="navbarNavAltMarkup">
      <div class="navbar-nav">
        <a class="nav-link " aria-current="page" href="#"><h2><-</h2></a>
        <a class="nav-link active" href="#" ><h2>History</h2></a>
      </div>
      <div class="navbar-nav">
        <a class="navbar-brand" href="#">
          <img src="{{ asset('images_history/settings.png') }}" alt="logo" width="50px">
        </a>
      </div>
      <div class="navbar-nav">
      <form class="d-flex" role="search" method="post" action="{{ route('histories.search') }}">
    @csrf
    <input class="form-control me-2" type="search" name="name" placeholder="Name item..." aria-label="Search">
    <button class="btn" type="submit">Search</button>
</form>

      </div>
    </div>
 </div>
</nav>
<div class="container mt-5">
  <form method="post" action="{{ route('histories.search_date') }}">
    @csrf
    <div class="form-group">
      <label for="exampleDate">Date:</label>
      <input type="date" class="form-control" id="Date" name="date" placeholder="Enter date" >
    </div>
    <button type="submit" class="btn btn-primary">Submit</button>
  </form>
</div>
<div class="container mt-5">
    <div class="btn-group">
        <a class="btn btn-success dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-bs-toggle="dropdown" aria-expanded="false">
            {{$numberofDays}} hari terakhir
        </a>
        <ul class="dropdown-menu" aria-labelledby="dropdownMenuLink">
            <li><a class="dropdown-item" href="{{ route('histories.last', ['action' => 'action3']) }}">show all data</a></li>
            <li><a class="dropdown-item" href="{{ route('histories.last', ['action' => 'action1']) }}">30 hari terakhir</a></li>
            <li><a class="dropdown-item" href="{{ route('histories.last', ['action' => 'action2']) }}">90 hari terakhir</a></li>

        </ul>
    </div>
</div>

<div class="container mt-5">
<a href="{{route('history')}}">
<button type="button" class="btn btn-danger">Reset</button>
</a>
</div>

<div class="container">
    @if($histories->isEmpty())
        <div class="d-flex align-items-center vh-100">
            <div class="mx-auto">
                <h6 class="text-center display-4">There is no data here!</h6>
                <br/>
            </div>
        </div>
    @else
        <br />
        <br />
        @foreach($histories as $history)
        <div style="background-color: rgb(255, 241, 178); border-radius:10px; outline-style:solid; outline-color: rgb(177, 115, 40);
            outline-width: 2px; box-shadow: 10px 10px rgb(209, 188, 83); padding-right: 15px;">
            <div>
                <p><strong>belanja</strong></p>
                <p><strong>{{ $history->created_at }}</strong></p>
            </div>
            <p><strong>Name:</strong> {{ $history->name }}</p>
            <p><strong>Quantity:</strong> {{ $history->quantitas }}</p>
            <p><strong>Price:</strong> Rp. {{ $history->price }}</p>
</div>
            <br />
            <br />  
        @endforeach
    @endif
</div>
</body>
</html>