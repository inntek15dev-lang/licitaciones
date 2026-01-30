<?php

use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

require __DIR__ . '/../../../../vendor/autoload.php';
$app = require_once __DIR__ . '/../../../../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$themePath = base_path('.agent/temp/theme.json');
if (!File::exists($themePath)) {
    echo "❌ Error: Theme file not found at $themePath\n";
    exit(1);
}

$theme = json_decode(File::get($themePath), true);
echo "🎨 Applying Theme...\n";

// 1. Generate CSS Variables
$cssContent = ":root {\n";
foreach ($theme['colors'] as $key => $val) {
    // Convert underscore to hyphens for CSS
    $cssKey = str_replace('_', '-', $key);
    $cssContent .= "    --color-$cssKey: $val;\n";
}
$cssContent .= "    --radius: {$theme['radius']};\n";
$cssContent .= "}\n";

$cssPath = resource_path('css/theme.css');
File::put($cssPath, $cssContent);
echo "✓ Generated resources/css/theme.css\n";

// 2. Ensure Import in app.css
$appCssPath = resource_path('css/app.css');
$appCss = File::get($appCssPath);
if (!str_contains($appCss, '@import "theme.css"')) {
    File::prepend($appCssPath, "@import \"theme.css\";\n");
    echo "✓ Linked theme in app.css\n";
}

// 3. Update Tailwind Config
$tailwindPath = base_path('tailwind.config.js');
$tailwindConfig = <<<'JS'
import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],

    theme: {
        extend: {
            colors: {
                primary: 'var(--color-primary)',
                secondary: 'var(--color-secondary)',
                background: 'var(--color-background)',
                surface: 'var(--color-surface)',
                text: {
                    main: 'var(--color-text-main)',
                    muted: 'var(--color-text-muted)',
                }
            },
            borderRadius: {
                DEFAULT: 'var(--radius)',
            },
            fontFamily: {
                sans: ['%FONT%', ...defaultTheme.fontFamily.sans],
            },
        },
    },

    plugins: [forms],
};
JS;

$fontName = explode(',', $theme['font'])[0];
$fontName = trim($fontName, "'\"");
$tailwindConfig = str_replace('%FONT%', $fontName, $tailwindConfig);

File::put($tailwindPath, $tailwindConfig);
echo "✓ Updated tailwind.config.js\n";

// 4. Build
echo "🔨 Running Build...\n";
// exec('npm run build', $output, $return); 
// Note: In agent mode, we might let the agent run this separately, 
// but for the skill completeness we can try. 
// However, 'npm' might not be in path for php exec context sometimes.
echo "⚠️  Please run 'npm run build' to finalize changes.\n";
