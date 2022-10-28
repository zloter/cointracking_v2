<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
    <title>Laravel File Upload</title>
</head>
<body>
<div class="">
    <form action="{{route('sendToCointracking')}}" method="post" enctype="multipart/form-data">
        <h3 class="text-center mb-5">Send results to cointracking</h3>
        @csrf
        <input type="hidden" name="transactions"  value="{{ $serialized }}">
        <button type="submit" name="submit" class="btn btn-primary btn-block ">
            Send to Cointracking
        </button>
    </form>
    <table class="table">
        <thead>
        <tr>
            <th scope="col">time</th>
            <th scope="col">type</th>
            <th scope="col">buy</th>
            <th scope="col">buyCoin</th>
            <th scope="col">sell</th>
            <th scope="col">sellCoin</th>
            <th scope="col">fee</th>
            <th scope="col">feeCoin</th>
            <th scope="col">comment</th>
            <th scope="col">buyOrigin</th>
            <th scope="col">sellOrigin</th>
        </tr>
        </thead>
        <tbody>
        @foreach($transactions as $transaction)
        <tr>
            <th scope="col">{{ $transaction->time }}</th>
            <th scope="col">{{ $transaction->type }}</th>
            <th scope="col">{{ $transaction->buy }}</th>
            <th scope="col">{{ $transaction->buyCoin }}</th>
            <th scope="col">{{ $transaction->sell }}</th>
            <th scope="col">{{ $transaction->sellCoin }}</th>
            <th scope="col">{{ $transaction->fee }}</th>
            <th scope="col">{{ $transaction->feeCoin }}</th>
            <th scope="col">{{ $transaction->comment }}</th>
            <th scope="col">{{ $transaction->buyOrigin }}</th>
            <th scope="col">{{ $transaction->sellOrigin }}</th>
        </tr>
        @endforeach
        </tbody>
    </table>
    </form>
</div>
</body>
</html>
