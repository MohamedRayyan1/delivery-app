<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Restaurant Performance Report</title>
    <style>
        /* الإعدادات الأساسية */
        body {
            font-family: 'DejaVu Sans', sans-serif;
            color: #333333;
            background-color: #ffffff;
            margin: 0;
            padding: 20px;
            font-size: 14px;
        }

        /* ترويسة التقرير */
        .header {
            text-align: center;
            padding-bottom: 20px;
            margin-bottom: 30px;
            border-bottom: 3px solid #ff6d00; /* الخط البرتقالي الخاص بالهوية */
        }
        .header h2 {
            margin: 0;
            color: #2c3e50;
            font-size: 28px;
            letter-spacing: 1px;
        }
        .header p {
            margin: 5px 0 0 0;
            color: #7f8c8d;
            font-size: 12px;
        }

        /* حاوية البطاقات */
        .cards-container {
            width: 100%;
            text-align: center;
            margin-bottom: 40px;
        }

        /* تصميم البطاقة الفردية */
        .card {
            display: inline-block;
            width: 30%;
            margin: 0 1%;
            background: #f8f9fa;
            border: 1px solid #e0e0e0;
            border-radius: 8px;
            padding: 20px 10px;
            vertical-align: top;
            box-sizing: border-box;
        }
        .card h3 {
            margin: 0 0 10px 0;
            font-size: 13px;
            color: #7f8c8d;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        .card p {
            margin: 0;
            font-size: 22px;
            font-weight: bold;
            color: #2c3e50;
        }
        .card .highlight {
            color: #ff6d00; /* إبراز الرقم باللون البرتقالي */
        }

        /* قسم الجداول */
        .section-title {
            color: #2c3e50;
            font-size: 18px;
            margin-bottom: 15px;
            border-left: 4px solid #ff6d00;
            padding-left: 10px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
            background-color: #ffffff;
        }
        th, td {
            padding: 12px 15px;
            text-align: center;
            border-bottom: 1px solid #e0e0e0;
        }
        th {
            background-color: #2c3e50;
            color: #ffffff;
            font-size: 13px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        tbody tr:nth-child(even) {
            background-color: #f9fbfd; /* لون خفيف جداً للأسطر الزوجية */
        }
        td {
            color: #444;
            font-size: 14px;
        }

        /* تذييل الصفحة */
        .footer {
            text-align: center;
            margin-top: 50px;
            font-size: 10px;
            color: #95a5a6;
            border-top: 1px solid #e0e0e0;
            padding-top: 10px;
        }
    </style>
</head>
<body>

    <div class="header">
        <h2>Restaurant Performance Report</h2>
        <p>Generated on {{ now()->format('F j, Y, g:i a') }}</p>
    </div>

    <div class="cards-container">
        <div class="card">
            <h3>Customers</h3>
            <p class="highlight">{{ $cards['customers']['value'] }}</p>
        </div>
        <div class="card">
            <h3>Net Income</h3>
            <p class="highlight">{{ $cards['net_income']['value'] }}</p>
        </div>
        <div class="card">
            <h3>Total Sales</h3>
            <p class="highlight">{{ $cards['total_sales']['value'] }}</p>
        </div>
    </div>

    <h3 class="section-title">Monthly Growth Comparison</h3>

    <table>
        <thead>
            <tr>
                <th>Month</th>
                <th>This Year</th>
                <th>Last Year</th>
            </tr>
        </thead>
        <tbody>
            @foreach($growth as $row)
                <tr>
                    <td><strong>{{ $row['month'] }}</strong></td>
                    <td>{{ $row['current'] }}</td>
                    <td>{{ $row['last_year'] }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        Report generated automatically by the Management Dashboard.
    </div>

</body>
</html>
