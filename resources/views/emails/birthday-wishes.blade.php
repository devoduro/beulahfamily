<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $subject }}</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f8f9fa;
        }
        .container {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        }
        .header {
            background: rgba(255,255,255,0.1);
            padding: 30px 20px;
            text-align: center;
            color: white;
        }
        .header h1 {
            margin: 0;
            font-size: 2.5em;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
        }
        .birthday-icon {
            font-size: 3em;
            margin-bottom: 10px;
            display: block;
        }
        .content {
            background: white;
            padding: 40px 30px;
            text-align: center;
        }
        .message {
            font-size: 1.1em;
            line-height: 1.8;
            margin-bottom: 30px;
            white-space: pre-line;
        }
        .celebration {
            background: linear-gradient(45deg, #ff6b6b, #feca57, #48dbfb, #ff9ff3);
            background-size: 400% 400%;
            animation: gradient 3s ease infinite;
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            font-size: 1.5em;
            font-weight: bold;
            margin: 20px 0;
        }
        @keyframes gradient {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }
        .footer {
            background: #f8f9fa;
            padding: 20px;
            text-align: center;
            color: #666;
            font-size: 0.9em;
        }
        .church-logo {
            width: 60px;
            height: 60px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.5em;
            margin-bottom: 15px;
        }
        .decorative-border {
            height: 4px;
            background: linear-gradient(90deg, #ff6b6b, #feca57, #48dbfb, #ff9ff3);
            margin: 20px 0;
        }
        .birthday-wishes {
            display: flex;
            justify-content: space-around;
            margin: 30px 0;
            flex-wrap: wrap;
        }
        .wish-item {
            text-align: center;
            margin: 10px;
            flex: 1;
            min-width: 120px;
        }
        .wish-icon {
            font-size: 2em;
            margin-bottom: 5px;
            display: block;
        }
        .wish-text {
            font-size: 0.9em;
            color: #666;
        }
        @media (max-width: 600px) {
            body { padding: 10px; }
            .content { padding: 20px 15px; }
            .header h1 { font-size: 2em; }
            .birthday-wishes { flex-direction: column; }
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <span class="birthday-icon">🎂</span>
            <h1>Happy Birthday!</h1>
            <p style="margin: 10px 0 0 0; font-size: 1.2em;">{{ $member->first_name }}</p>
        </div>

        <!-- Content -->
        <div class="content">
            <div class="church-logo">
                <i class="fas fa-church" style="font-size: 1.2em;"></i>
            </div>
            
            <div class="celebration">🎉 Celebrating You Today! 🎉</div>
            
            <div class="decorative-border"></div>
            
            <div class="message">{{ $message }}</div>
            
            <!-- Birthday Wishes Icons -->
            <div class="birthday-wishes">
                <div class="wish-item">
                    <span class="wish-icon">🎈</span>
                    <div class="wish-text">Joy</div>
                </div>
                <div class="wish-item">
                    <span class="wish-icon">🎁</span>
                    <div class="wish-text">Blessings</div>
                </div>
                <div class="wish-item">
                    <span class="wish-icon">🌟</span>
                    <div class="wish-text">Happiness</div>
                </div>
                <div class="wish-item">
                    <span class="wish-icon">💝</span>
                    <div class="wish-text">Love</div>
                </div>
            </div>
            
            <div class="decorative-border"></div>
            
            <p style="font-style: italic; color: #666; margin-top: 30px;">
                "For I know the plans I have for you," declares the Lord, "plans to prosper you and not to harm you, to give you hope and a future."
                <br><strong>- Jeremiah 29:11</strong>
            </p>
        </div>

        <!-- Footer -->
        <div class="footer">
            <p><strong>Beulah Family Church</strong></p>
            <p>Your Church Family</p>
            <p style="font-size: 0.8em; margin-top: 15px;">
                This birthday message was sent with love from your church family. 
                May God continue to bless you abundantly!
            </p>
        </div>
    </div>
</body>
</html>
