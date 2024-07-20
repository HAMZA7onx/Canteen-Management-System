<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        h1, h2 { color: #2c3e50; }
        table { border-collapse: collapse; width: 100%; margin-bottom: 20px; }
        th, td { border: 1px solid #ddd; padding: 12px; text-align: left; }
        th { background-color: #f2f2f2; font-weight: bold; }
        tr:nth-child(even) { background-color: #f9f9f9; }
        .summary { background-color: #e8f4fd; padding: 15px; border-radius: 5px; margin-bottom: 20px; }
        .meal-group { margin-bottom: 30px; }
        .meal-header { background-color: #3498db; color: white; padding: 10px; border-radius: 5px 5px 0 0; }
    </style>
</head>
<body>
<h1>{{ ucfirst($frequency) }} Detailed Record Report</h1>
<p>From: {{ $dateRange['start']->toDateString() }} To: {{ $dateRange['end']->toDateString() }}</p>
<div class="summary">
    <h2>Summary</h2>
    <p>Total Days: {{ count($records) }}</p>
    <p>Total Meals Served: {{ collect($records)->sum(function($day) { return collect($day->meals)->sum('persons_count'); }) }}</p>
    <p>Total Revenue (With Discount): {{ number_format(collect($records)->sum(function($day) { return collect($day->meals)->sum('total_with_discount'); }), 2) }} DH</p>
</div>
@foreach($records as $date => $dayRecords)
    <div class="meal-group">
        <h2 class="meal-header">{{ $date }}</h2>
        <table>
            <thead>
            <tr>
                <th>Meal</th>
                <th>Time</th>
                <th>Persons</th>
                <th>Price</th>
                <th>Total (No Discount)</th>
                <th>Total (With Discount)</th>
            </tr>
            </thead>
            <tbody>
            @foreach($dayRecords->meals as $meal)
                <tr>
                    <td>{{ $meal->meal_name }}</td>
                    <td>{{ $meal->start_time }} - {{ $meal->end_time }}</td>
                    <td>{{ $meal->persons_count }}</td>
                    <td>{{ number_format($meal->price, 2) }} DH</td>
                    <td>{{ number_format($meal->total_no_discount, 2) }} DH</td>
                    <td>{{ number_format($meal->total_with_discount, 2) }} DH</td>
                </tr>
            @endforeach
            </tbody>
            <tfoot>
            <tr>
                <th colspan="2">Daily Totals</th>
                <th>{{ collect($dayRecords->meals)->sum('persons_count') }}</th>
                <th>-</th>
                <th>{{ number_format(collect($dayRecords->meals)->sum('total_no_discount'), 2) }} DH</th>
                <th>{{ number_format(collect($dayRecords->meals)->sum('total_with_discount'), 2) }} DH</th>
            </tr>
            </tfoot>
        </table>
    </div>
@endforeach
</body>
</html>
