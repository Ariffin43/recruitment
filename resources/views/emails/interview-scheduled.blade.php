<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f1f5f9;
            margin: 0;
            padding: 24px;
        }

        .card {
            background: #fff;
            border-radius: 12px;
            max-width: 560px;
            margin: 0 auto;
            overflow: hidden;
            border: 1px solid #e2e8f0;
        }

        .header {
            background: #4338ca;
            padding: 28px 32px;
        }

        .header h1 {
            color: #fff;
            margin: 0;
            font-size: 20px;
        }

        .header p {
            color: #c7d2fe;
            margin: 4px 0 0;
            font-size: 13px;
        }

        .body {
            padding: 28px 32px;
        }

        .greeting {
            font-size: 15px;
            color: #1e293b;
            margin-bottom: 12px;
        }

        .info-box {
            background: #f8fafc;
            border: 1.5px solid #e2e8f0;
            border-radius: 10px;
            padding: 16px 20px;
            margin: 20px 0;
        }

        .info-row {
            display: flex;
            gap: 12px;
            padding: 6px 0;
            border-bottom: 1px solid #f1f5f9;
            font-size: 13px;
        }

        .info-row:last-child {
            border-bottom: none;
        }

        .info-label {
            color: #64748b;
            width: 120px;
            flex-shrink: 0;
            font-weight: 600;
        }

        .info-value {
            color: #1e293b;
            font-weight: 500;
        }

        .badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
        }

        .badge-online {
            background: #e0f2fe;
            color: #0284c7;
        }

        .badge-offline {
            background: #fef3c7;
            color: #d97706;
        }

        .note-box {
            background: #faf5ff;
            border: 1.5px solid #ddd6fe;
            border-radius: 10px;
            padding: 12px 16px;
            margin-top: 16px;
            font-size: 13px;
            color: #6d28d9;
        }

        .footer {
            padding: 16px 32px;
            background: #f8fafc;
            border-top: 1px solid #e2e8f0;
            font-size: 12px;
            color: #94a3b8;
            text-align: center;
        }
    </style>
</head>

<body>
    <div class="card">
        <div class="header">
            <h1>📅 Undangan Interview</h1>
            <p>{{ config('app.name') }}</p>
        </div>

        <div class="body">
            <p class="greeting">Yth. <strong>{{ $lamaran->pelamar->user->nama }}</strong>,</p>
            <p style="font-size:14px; color:#475569; line-height:1.7;">
                Selamat! Anda telah lolos tahap seleksi dan kami mengundang Anda untuk mengikuti sesi interview.
                Berikut detail jadwal interview Anda:
            </p>

            <div class="info-box">
                <div class="info-row">
                    <span class="info-label">Posisi</span>
                    <span class="info-value">{{ $lamaran->lowongan->fptk->posisi_dibutuhkan }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Tanggal</span>
                    <span class="info-value">
                        {{ \Carbon\Carbon::parse($lamaran->tanggal_interview)->translatedFormat('l, d F Y') }}
                    </span>
                </div>
                <div class="info-row">
                    <span class="info-label">Jam</span>
                    <span class="info-value">{{ \Carbon\Carbon::parse($lamaran->jam_interview)->format('H:i') }}
                        WIB</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Metode</span>
                    <span class="info-value">
                        <span
                            class="badge {{ $lamaran->metode_interview === 'online' ? 'badge-online' : 'badge-offline' }}">
                            {{ ucfirst($lamaran->metode_interview) }}
                        </span>
                    </span>
                </div>
                @if($lamaran->metode_interview === 'online' && $lamaran->link)
                    <div class="info-row">
                        <span class="info-label">Link Meeting</span>
                        <span class="info-value">
                            <a href="{{ $lamaran->link }}" style="color:#4338ca;">{{ $lamaran->link }}</a>
                        </span>
                    </div>
                @endif
                @if($lamaran->metode_interview === 'offline' && $lamaran->lokasi_interview)
                    <div class="info-row">
                        <span class="info-label">Lokasi</span>
                        <span class="info-value">{{ $lamaran->lokasi_interview }}</span>
                    </div>
                @endif
            </div>

            @if($lamaran->catatan_interview)
                <div class="note-box">
                    <strong>📝 Catatan dari HRD:</strong><br>
                    {{ $lamaran->catatan_interview }}
                </div>
            @endif

            <p style="font-size:13px; color:#64748b; margin-top:20px; line-height:1.7;">
                Harap hadir tepat waktu. Jika ada pertanyaan, silakan balas email ini atau hubungi tim HRD kami.
            </p>
        </div>

        <div class="footer">
            &copy; {{ date('Y') }} {{ config('app.name') }} &nbsp;·&nbsp; Email ini dikirim otomatis oleh sistem.
        </div>
    </div>
</body>

</html>