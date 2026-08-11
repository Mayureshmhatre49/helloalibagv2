<tr>
<td>
<table class="footer" align="center" width="570" cellpadding="0" cellspacing="0" role="presentation">
<tr>
<td class="content-cell" align="center" style="padding: 28px 20px 32px;">
    <p style="color:#a0aec0; font-size:13px; margin-bottom:8px;">
        <a href="{{ config('app.url') }}" style="color:#e8831a; text-decoration:none; font-weight:600;">Hello Alibaug</a>
        &nbsp;·&nbsp;
        <a href="{{ config('app.url') }}/contact" style="color:#a0aec0; text-decoration:none;">Contact Us</a>
    </p>
    <p style="color:#a0aec0; font-size:12px; margin-bottom:6px; margin-top:0;">
        Discover · Stay · Eat — Alibaug & Konkan Coast
    </p>
    <p style="color:#cbd5e0; font-size:11px; margin:0;">
        © {{ date('Y') }} Hello Alibaug. All rights reserved.<br>
        You're receiving this email because you have an account or made a request on Hello Alibaug.
    </p>
    {{ Illuminate\Mail\Markdown::parse($slot) }}
</td>
</tr>
</table>
</td>
</tr>
