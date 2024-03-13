<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Laravel</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet"/>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css"
          integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">

</head>
<body>
<div class="first-task">
    <h2>Task 1</h2>
    <table class="table">
        <thead>
        <tr>
            <th scope="col">#</th>
            <th scope="col">Hotel Id</th>
            <th scope="col">Weekend</th>
        </tr>
        </thead>
        <tbody>
        @foreach($task1 as $key => $data)
            <tr>
                <th scope="row">{{$key+1}}</th>
                <td>{{$data['id']}}</td>
                <td>{{$data['weekend_stays']}}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
<div class="second-task">
    <h2>Task 2</h2>
    <table class="table">
        <thead>
        <tr>
            <th scope="col">#</th>
            <th scope="col">Hotel Id</th>
            <th scope="col">Reject Dates</th>
        </tr>
        </thead>
        <tbody>
        @foreach($task2 as $key => $data)
            <tr>
                <th scope="row">{{$key+1}}</th>
                <td>{{$data['id']}}</td>
                <td>
                    @foreach($data['dates'] as $date)
                        {{$date}}
                        <br>
                    @endforeach
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
<div class="third-task">
    <h2>Task 3</h2>
    <table class="table">
        <thead>
        <tr>
            <th scope="col">#</th>
            <th scope="col">Date</th>
            <th scope="col">Total Money Lost</th>
        </tr>
        </thead>
        <tbody>
            <tr>
                <th scope="row">1</th>
                <td>{{$task3['date']}}</td>
                <td>{{$task3['value']}}</td>
            </tr>
        </tbody>
    </table>
</div>
</body>
</html>
