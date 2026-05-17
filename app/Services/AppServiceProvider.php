use Barryvdh\DomPDF\Facade\Pdf;

public function boot(): void
{
    $dompdf = app('dompdf.wrapper');

    config([
        'dompdf.public_path' => public_path(),
        'dompdf.temp_dir' => storage_path('app'),
    ]);
}