<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Home Connection Assignments Report</title>
    <style>
        body {
            font-family: DejaVu Sans, Arial, sans-serif;
            font-size: 12px;
            color: #333;
        }
        h2 {
            text-align: center;
            margin-bottom: 10px;
        }
        .meta {
            margin-bottom: 15px;
        }
        .meta p {
            margin: 2px 0;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid #555;
            padding: 6px 8px;
            text-align: left;
        }
        th {
            background-color: #f1f1f1;
            font-weight: bold;
        }
        tbody tr:nth-child(even) {
            background-color: #fafafa;
        }
        .text-center {
            text-align: center;
        }
        .badge {
            display: inline-block;
            padding: 2px 6px;
            border-radius: 3px;
        }
    </style>
</head>
<body>
    <h2>Home Connection Assignments Report</h2>

    <div class="meta">
        <p><strong>Generated:</strong> {{ $generatedAt->format('Y-m-d H:i') }}</p>
        @php
            $filterLabels = [
                'date_from' => 'From Date',
                'date_to' => 'To Date',
                'team_id' => 'Team',
                'location' => 'Location',
                'connection_type' => 'Connection Type',
                'issue' => 'Issue',
            ];
            $issueLabels = [
                'with_issue' => 'With Issues',
                'without_issue' => 'Without Issues',
                'resolved_issue' => 'Resolved Issues',
            ];
            $activeFilters = collect($filters ?? [])->filter(function ($value) {
                return $value !== null && $value !== '';
            });
        @endphp
        @if($activeFilters->isNotEmpty())
            <p><strong>Filters:</strong>
                @foreach($activeFilters as $key => $value)
                    @php
                        $label = $filterLabels[$key] ?? ucfirst(str_replace('_', ' ', $key));
                        if ($key === 'issue') {
                            $value = $issueLabels[$value] ?? ucfirst(str_replace('_', ' ', $value));
                        }
                    @endphp
                    <span>{{ $label }}: {{ $value }}@if(!$loop->last), @endif</span>
                @endforeach
            </p>
        @endif
        <p><strong>Total Assignments:</strong> {{ $assignments->count() }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th class="text-center">#</th>
                <th>Customer</th>
                <th>Team</th>
                <th>Assignment Title</th>
                <th>Assignment Date</th>
                <th>Priority</th>
                <th>Status</th>
                <th>Location</th>
                <th>Connection Type</th>
                <th>Has Issue</th>
                <th>Issue Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($assignments as $index => $assignment)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td>{{ optional($assignment->customer)->customer_name ?? 'N/A' }}</td>
                    <td>{{ optional($assignment->team)->team_name ?? 'N/A' }}</td>
                    <td>{{ $assignment->assignment_title ?? 'N/A' }}</td>
                    <td>
                        @if($assignment->assigned_date)
                            {{ $assignment->assigned_date->format('Y-m-d H:i') }}
                        @elseif($assignment->created_at)
                            {{ $assignment->created_at->format('Y-m-d H:i') }}
                        @else
                            N/A
                        @endif
                    </td>
                    <td>{{ $assignment->priority ? ucfirst($assignment->priority) : 'N/A' }}</td>
                    <td>{{ $assignment->status ? ucfirst(str_replace('_', ' ', $assignment->status)) : 'N/A' }}</td>
                    <td>{{ optional($assignment->customer)->location ?? 'N/A' }}</td>
                    <td>{{ optional($assignment->customer)->connection_type ?? 'N/A' }}</td>
                    <td>{{ $assignment->has_issue ? 'Yes' : 'No' }}</td>
                    <td>
                        @if($assignment->issue_status)
                            {{ ucfirst(str_replace('_', ' ', $assignment->issue_status)) }}
                        @elseif($assignment->has_issue)
                            Open
                        @else
                            N/A
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="11" class="text-center">No assignments found for the selected criteria.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>
