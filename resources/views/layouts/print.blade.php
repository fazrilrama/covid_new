<!DOCTYPE html>
<html>
<head>
    <title>Print</title>
    <link rel="stylesheet" href="{{ asset('vendor/adminlte/vendor/bootstrap/dist/css/bootstrap.min.css') }}">
    <script src="{{ asset('vendor/adminlte/vendor/jquery/dist/jquery.min.js') }}"></script>
    <script src="{{ asset('vendor/adminlte/vendor/bootstrap/dist/js/bootstrap.min.js') }}"></script>
    <script src="//code.jquery.com/jquery-1.9.1.js"></script>
    <style>
        .space-top {
            margin-top: 20px;
        }

        table th, table td {
            padding: 5px;
        }

        table th {
            text-align: center;
        }
    </style>
</head>
<body>

@yield('content')

<script>
    $(document).ready(function () {
        window.print();
    });
</script>

</body>
</html>