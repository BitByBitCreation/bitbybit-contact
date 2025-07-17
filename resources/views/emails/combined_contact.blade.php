<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <title>Contact request + AI response</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 700px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f9f9f9;
        }
        h1, h2 {
            color: #2575fc;
        }
        .section {
            margin-bottom: 25px;
            padding: 20px;
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 6px rgba(0,0,0,0.05);
        }
        .badge {
            display: inline-block;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 14px;
            font-weight: 600;
            background: #eee;
            margin: 3px;
        }
        .sentiment-positive { background: #4caf50; color: white; }
        .sentiment-neutral { background: #999; color: white; }
        .sentiment-negative { background: #f44336; color: white; }
        .draft {
            background: #e3f2fd;
            padding: 15px;
            border-left: 4px solid #2196f3;
            white-space: pre-wrap;
        }
        .metrics {
            display: flex;
            gap: 15px;
            margin-top: 10px;
        }
        .metric {
            flex: 1;
            background: #f8f9fa;
            padding: 12px;
            border-radius: 6px;
            text-align: center;
        }
        .metric-value {
            font-size: 24px;
            font-weight: bold;
        }
        .toxicity-low { color: #4caf50; }
        .toxicity-medium { color: #ff9800; }
        .toxicity-high { color: #f44336; }
        .spam-low { color: #4caf50; }
        .spam-medium { color: #ff9800; }
        .spam-high { color: #f44336; }
        .message-content {
            background: #f0f0f0;
            padding: 10px;
            border-radius: 6px;
            white-space: pre-wrap;
        }
        .footer {
            margin-top: 30px;
            font-size: 0.9em;
            text-align: center;
            color: #777;
        }
    </style>
</head>
<body>

    <h1>New contact request</h1>

    <div class="section">
        <h2>Sender</h2>
        <p><strong>Name:</strong> {{ $data->sender }}</p>
        <p><strong>E-Mail:</strong> {{ $data->email }}</p>
        <p><strong>Subject:</strong> {{ $data->subject }}</p>
    </div>

    <div class="section">
        <h2>Message</h2>
        <div class="message-content">{{ $data->message }}</div>
    </div>

    @if($data->summary)
    <div class="section">
        <h2>Summary</h2>
        <p>{{ $data->summary }}</p>
    </div>
    @endif

    @if(!empty($data->keywords))
    <div class="section">
        <h2>Keywords</h2>
        @foreach($data->keywords as $keyword)
            <span class="badge">{{ $keyword }}</span>
        @endforeach
    </div>
    @endif

    <div class="section">
        <h2>Analysis results</h2>

        <p>
            <strong>Mood:</strong>
            <span class="badge sentiment-{{ $data->sentiment ?? 'neutral' }}">
                {{ $data->sentiment }}
            </span>
        </p>

        <div class="metrics">
            <div class="metric">
                <div>Toxicity</div>
                <div class="metric-value toxicity-{{ $data->toxicity_score > 70 ? 'high' : ($data->toxicity_score > 30 ? 'medium' : 'low') }}">
                    {{ $data->toxicity_score }}%
                </div>
            </div>

            <div class="metric">
                <div>Spam</div>
                <div class="metric-value spam-{{ $data->spam_score > 70 ? 'high' : ($data->spam_score > 30 ? 'medium' : 'low') }}">
                    {{ $data->spam_score }}%
                </div>
            </div>
        </div>
    </div>

    <div class="section">
        <h2>Draft response (AI)</h2>
        <div class="draft">
            {{ $draft }}
        </div>
        <p style="margin-top: 10px;">
            <a href="mailto:{{ $data->email }}?subject=Re: {{ $data->subject }}&body={{ rawurlencode($draft) }}">
                Reply directly
            </a>
        </p>
    </div>

    <div class="footer">
        Sent on {{ now()->format('d.m.Y H:i') }} | Automatically analyzed and prepared
    </div>

</body>
</html>
