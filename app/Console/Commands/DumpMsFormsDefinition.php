<?php

namespace App\Console\Commands;

use App\Services\MsForms\MsFormsClient;
use App\Services\MsForms\MsFormsException;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

final class DumpMsFormsDefinition extends Command
{
    protected $signature = 'msforms:dump {link} {--output=msforms/raw-definition.json}';

    protected $description = 'Dump the raw MS Forms definition JSON for inspection';

    public function handle(MsFormsClient $client): int
    {
        try {
            $target = $client->resolve((string) $this->argument('link'));
            $raw = $client->fetchFormDefinition($target);
        } catch (MsFormsException) {
            $this->error('Unable to fetch the form definition from Microsoft Forms.');

            return self::FAILURE;
        }

        Storage::disk('local')->put($this->option('output'), json_encode($raw, JSON_PRETTY_PRINT));

        $this->info('Raw definition written to storage/app/' . $this->option('output'));

        return self::SUCCESS;
    }
}