<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
</head>
<body style="font-family: Arial, sans-serif; color: #2D3748; margin: 0; padding: 24px; background: #F7FAFC;">
    <div style="max-width: 480px; margin: 0 auto; background: #ffffff; border-radius: 12px; padding: 24px; border: 1px solid #E2E8F0;">
        <h2 style="margin-top: 0;">Backup Database Berhasil</h2>
        <p>File backup database sistem telah dibuat dan dilampirkan pada email ini.</p>
        <table style="width: 100%; font-size: 14px; margin: 16px 0;">
            <tr>
                <td style="padding: 4px 0; color: #718096;">Nama File</td>
                <td style="padding: 4px 0;"><strong>{{ $fileName }}</strong></td>
            </tr>
            <tr>
                <td style="padding: 4px 0; color: #718096;">Dibuat Pada</td>
                <td style="padding: 4px 0;"><strong>{{ $createdAt }}</strong></td>
            </tr>
        </table>
        <p style="font-size: 13px; color: #718096;">Email ini dikirim otomatis oleh sistem admin. Simpan file lampiran di tempat yang aman.</p>
    </div>
</body>
</html>