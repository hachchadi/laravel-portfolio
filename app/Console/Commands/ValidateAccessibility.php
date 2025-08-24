<?php

namespace App\Console\Commands;

use App\Services\AccessibilityService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class ValidateAccessibility extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'accessibility:validate {--fix : Attempt to fix common accessibility issues}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Validate accessibility compliance and suggest improvements';

    /**
     * Execute the console command.
     */
    public function handle(AccessibilityService $accessibilityService): int
    {
        $this->info('Validating accessibility compliance...');

        $issues = [];
        $suggestions = [];

        // Check color contrast ratios
        $this->line('Checking color contrast ratios...');
        $colorPairs = [
            ['foreground' => '#374151', 'background' => '#FFFFFF', 'context' => 'Dark text on white background'],
            ['foreground' => '#6B7280', 'background' => '#FFFFFF', 'context' => 'Gray text on white background'],
            ['foreground' => '#D1D5DB', 'background' => '#1F2937', 'context' => 'Light text on dark background'],
            ['foreground' => '#3B82F6', 'background' => '#FFFFFF', 'context' => 'Blue links on white background'],
        ];

        foreach ($colorPairs as $pair) {
            $contrast = $accessibilityService->validateColorContrast($pair['foreground'], $pair['background']);
            
            if (!$contrast['aa_normal']) {
                $issues[] = "Low contrast ratio ({$contrast['ratio']}:1) for {$pair['context']}";
                $suggestions[] = "Increase contrast ratio to at least 4.5:1 for {$pair['context']}";
            } else {
                $this->info("✓ Good contrast ratio ({$contrast['ratio']}:1) for {$pair['context']}");
            }
        }

        // Check for common accessibility patterns in views
        $this->line('Checking view files for accessibility patterns...');
        $viewPath = resource_path('views');
        $viewFiles = File::allFiles($viewPath);

        foreach ($viewFiles as $file) {
            if ($file->getExtension() === 'php') {
                $content = File::get($file->getPathname());
                $relativePath = str_replace($viewPath . DIRECTORY_SEPARATOR, '', $file->getPathname());

                // Check for images without alt text
                if (preg_match('/<img(?![^>]*alt=)/i', $content)) {
                    $issues[] = "Images without alt text found in {$relativePath}";
                    $suggestions[] = "Add alt attributes to all images in {$relativePath}";
                }

                // Check for form inputs without labels
                if (preg_match('/<input(?![^>]*aria-label)(?![^>]*id="[^"]*")(?![^>]*aria-labelledby)/i', $content)) {
                    $issues[] = "Form inputs without proper labels found in {$relativePath}";
                    $suggestions[] = "Add labels or aria-label attributes to form inputs in {$relativePath}";
                }

                // Check for buttons without accessible text
                if (preg_match('/<button[^>]*>[\s]*<\/button>/i', $content)) {
                    $issues[] = "Empty buttons found in {$relativePath}";
                    $suggestions[] = "Add text content or aria-label to buttons in {$relativePath}";
                }

                // Check for proper heading hierarchy
                $headings = [];
                preg_match_all('/<h([1-6])[^>]*>/i', $content, $matches);
                if (!empty($matches[1])) {
                    $headingLevels = array_map('intval', $matches[1]);
                    for ($i = 1; $i < count($headingLevels); $i++) {
                        if ($headingLevels[$i] > $headingLevels[$i-1] + 1) {
                            $issues[] = "Heading hierarchy skip found in {$relativePath} (h{$headingLevels[$i-1]} to h{$headingLevels[$i]})";
                            $suggestions[] = "Maintain proper heading hierarchy in {$relativePath}";
                            break;
                        }
                    }
                }
            }
        }

        // Display results
        if (empty($issues)) {
            $this->info('✓ No accessibility issues found!');
        } else {
            $this->error('Found ' . count($issues) . ' accessibility issues:');
            foreach ($issues as $issue) {
                $this->line("  • {$issue}");
            }

            $this->newLine();
            $this->info('Suggestions for improvement:');
            foreach ($suggestions as $suggestion) {
                $this->line("  • {$suggestion}");
            }

            if ($this->option('fix')) {
                $this->info('Auto-fix functionality would be implemented here...');
                // In a real implementation, this would attempt to fix common issues
            }
        }

        // Generate accessibility report
        $report = [
            'timestamp' => now()->toISOString(),
            'issues_found' => count($issues),
            'issues' => $issues,
            'suggestions' => $suggestions,
            'color_contrast_results' => array_map(function ($pair) use ($accessibilityService) {
                $contrast = $accessibilityService->validateColorContrast($pair['foreground'], $pair['background']);
                return array_merge($pair, $contrast);
            }, $colorPairs),
        ];

        File::put(storage_path('logs/accessibility-report.json'), json_encode($report, JSON_PRETTY_PRINT));
        $this->info('Accessibility report saved to storage/logs/accessibility-report.json');

        return empty($issues) ? self::SUCCESS : self::FAILURE;
    }
}
