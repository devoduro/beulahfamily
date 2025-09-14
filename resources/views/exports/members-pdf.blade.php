<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Members Export</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 10px;
            margin: 0;
            padding: 20px;
        }
        
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #333;
            padding-bottom: 15px;
        }
        
        .header h1 {
            margin: 0;
            font-size: 24px;
            color: #333;
        }
        
        .header p {
            margin: 5px 0 0 0;
            color: #666;
            font-size: 12px;
        }
        
        .stats {
            display: flex;
            justify-content: space-around;
            margin-bottom: 20px;
            background-color: #f8f9fa;
            padding: 10px;
            border-radius: 5px;
        }
        
        .stat-item {
            text-align: center;
        }
        
        .stat-number {
            font-size: 18px;
            font-weight: bold;
            color: #2563eb;
        }
        
        .stat-label {
            font-size: 10px;
            color: #666;
            margin-top: 2px;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            font-size: 8px;
        }
        
        th {
            background-color: #e2e8f0;
            color: #1e293b;
            font-weight: bold;
            padding: 8px 4px;
            text-align: left;
            border: 1px solid #cbd5e1;
        }
        
        td {
            padding: 6px 4px;
            border: 1px solid #e2e8f0;
            vertical-align: top;
        }
        
        tr:nth-child(even) {
            background-color: #f8fafc;
        }
        
        .status-active {
            color: #059669;
            font-weight: bold;
        }
        
        .status-inactive {
            color: #dc2626;
            font-weight: bold;
        }
        
        .type-member {
            background-color: #dbeafe;
            color: #1e40af;
            padding: 2px 4px;
            border-radius: 3px;
            font-size: 7px;
        }
        
        .type-visitor {
            background-color: #fef3c7;
            color: #92400e;
            padding: 2px 4px;
            border-radius: 3px;
            font-size: 7px;
        }
        
        .type-friend {
            background-color: #d1fae5;
            color: #065f46;
            padding: 2px 4px;
            border-radius: 3px;
            font-size: 7px;
        }
        
        .type-associate {
            background-color: #e0e7ff;
            color: #3730a3;
            padding: 2px 4px;
            border-radius: 3px;
            font-size: 7px;
        }
        
        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 8px;
            color: #666;
            border-top: 1px solid #e2e8f0;
            padding-top: 10px;
        }
        
        .page-break {
            page-break-after: always;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Church Members Directory</h1>
        <p>Generated on {{ now()->format('F j, Y \a\t g:i A') }}</p>
        <p>Total Members: {{ $members->count() }}</p>
    </div>

    <div class="stats">
        <div class="stat-item">
            <div class="stat-number">{{ $members->where('membership_status', 'active')->count() }}</div>
            <div class="stat-label">Active Members</div>
        </div>
        <div class="stat-item">
            <div class="stat-number">{{ $members->where('membership_type', 'member')->count() }}</div>
            <div class="stat-label">Full Members</div>
        </div>
        <div class="stat-item">
            <div class="stat-number">{{ $members->where('gender', 'male')->count() }}</div>
            <div class="stat-label">Male</div>
        </div>
        <div class="stat-item">
            <div class="stat-number">{{ $members->where('gender', 'female')->count() }}</div>
            <div class="stat-label">Female</div>
        </div>
        <div class="stat-item">
            <div class="stat-number">{{ $members->groupBy('family_id')->count() }}</div>
            <div class="stat-label">Families</div>
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th style="width: 8%;">ID</th>
                <th style="width: 15%;">Name</th>
                <th style="width: 12%;">Contact</th>
                <th style="width: 6%;">Gender</th>
                <th style="width: 8%;">Age</th>
                <th style="width: 15%;">Address</th>
                <th style="width: 12%;">Family</th>
                <th style="width: 8%;">Status</th>
                <th style="width: 8%;">Type</th>
                <th style="width: 8%;">Joined</th>
            </tr>
        </thead>
        <tbody>
            @foreach($members as $index => $member)
                <tr>
                    <td>{{ $member->member_id }}</td>
                    <td>
                        <strong>{{ $member->full_name }}</strong>
                        @if($member->ministries->count() > 0)
                            <br><small style="color: #666;">{{ $member->ministries->pluck('name')->join(', ') }}</small>
                        @endif
                    </td>
                    <td>
                        @if($member->email)
                            {{ $member->email }}<br>
                        @endif
                        @if($member->phone)
                            {{ $member->phone }}
                        @endif
                    </td>
                    <td>{{ ucfirst($member->gender ?? '') }}</td>
                    <td>
                        @if($member->date_of_birth)
                            {{ \Carbon\Carbon::parse($member->date_of_birth)->age }}
                        @endif
                    </td>
                    <td>
                        @if($member->address)
                            {{ $member->address }}
                            @if($member->city), {{ $member->city }}@endif
                            @if($member->state), {{ $member->state }}@endif
                        @endif
                    </td>
                    <td>{{ $member->family ? $member->family->family_name : '' }}</td>
                    <td>
                        <span class="status-{{ $member->membership_status }}">
                            {{ ucfirst($member->membership_status) }}
                        </span>
                    </td>
                    <td>
                        <span class="type-{{ $member->membership_type }}">
                            {{ ucfirst($member->membership_type) }}
                        </span>
                    </td>
                    <td>
                        @if($member->membership_date)
                            {{ \Carbon\Carbon::parse($member->membership_date)->format('M Y') }}
                        @endif
                    </td>
                </tr>
                
                @if(($index + 1) % 25 == 0 && $index + 1 < $members->count())
                    </tbody>
                    </table>
                    <div class="page-break"></div>
                    <table>
                        <thead>
                            <tr>
                                <th style="width: 8%;">ID</th>
                                <th style="width: 15%;">Name</th>
                                <th style="width: 12%;">Contact</th>
                                <th style="width: 6%;">Gender</th>
                                <th style="width: 8%;">Age</th>
                                <th style="width: 15%;">Address</th>
                                <th style="width: 12%;">Family</th>
                                <th style="width: 8%;">Status</th>
                                <th style="width: 8%;">Type</th>
                                <th style="width: 8%;">Joined</th>
                            </tr>
                        </thead>
                        <tbody>
                @endif
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        <p>This report contains {{ $members->count() }} members across {{ $members->groupBy('family_id')->count() }} families.</p>
        <p>Generated by Church Management System | {{ config('app.name') }}</p>
    </div>
</body>
</html>
