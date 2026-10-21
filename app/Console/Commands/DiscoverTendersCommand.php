<?php

namespace App\Console\Commands;

use App\Models\TenderSource;
use App\Services\TenderDiscoveryService;
use Illuminate\Console\Command;

class DiscoverTendersCommand extends Command
{
    protected $signature = 'tenders:discover {source? : ID or name of the source}';

    protected $description = 'Discover new tenders from configured sources.';

    public function handle(TenderDiscoveryService $service): int
    {
        $identifier = $this->argument('source');

        if ($identifier) {
            $source = TenderSource::query()
                ->where('id', $identifier)
                ->orWhere('name', $identifier)
                ->first();

            if (! $source) {
                $this->error('Source not found.');
                return self::FAILURE;
            }

            $sources = collect([$source]);
        } else {
            $sources = TenderSource::active()->get();

            if ($sources->isEmpty()) {
                $this->warn('No active tender sources. Add one to tender_sources table.');
                return self::SUCCESS;
            }
        }

        foreach ($sources as $source) {
            try {
                $count = $service->discover($source);
                $this->info("[{$source->name}] Created/updated {$count} tenders.");
            } catch (\Throwable $e) {
                $this->error("[{$source->name}] {$e->getMessage()}");
            }
        }

        return self::SUCCESS;
    }
}
