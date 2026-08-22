<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

app()->setLocale('ar');

$controller = app(App\Http\Controllers\Admin\AdminPdfSettingsController::class);
$request = Illuminate\Http\Request::create('/admin/pdf-settings', 'GET');
$response = $controller->edit($request);
$html = $response->getContent();

file_put_contents(storage_path('app/pdf-settings-render.html'), $html);

echo 'len=' . strlen($html) . PHP_EOL;

preg_match_all('/slide_label_([a-z]+)_([a-z]+)/', $html, $m, PREG_SET_ORDER);
$ids = array_unique(array_map(fn ($r) => $r[0], $m));
echo 'locale-label-inputs=' . count($ids) . PHP_EOL;
foreach (array_slice($ids, 0, 30) as $id) {
    echo '  ' . $id . PHP_EOL;
}

// Check that each locale card is present
foreach (['ar' => 'العربية', 'en' => 'English', 'ur' => 'اردو', 'zh' => '中文'] as $locale => $native) {
    echo ($locale . ': card=' . (str_contains($html, 'name="slide_labels[' . $locale . '][details]"') ? 'yes' : 'no') . PHP_EOL);
}
