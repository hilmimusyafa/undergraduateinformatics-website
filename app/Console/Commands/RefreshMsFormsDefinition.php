<?php

namespace App\Console\Commands;

use App\Models\FeedbackLink;
use App\Services\MsForms\FormDefinitionService;
use App\Services\MsForms\MsFormsException;
use Illuminate\Console\Command;

final class RefreshMsFormsDefinition extends Command
{
    protected $signature = 'msforms:refresh-definition';

    protected $description = 'Warm the cached MS Forms definition so user requests never hit a cold fetch';

    public function handle(): int
    {
        $feedbackLink = FeedbackLink::configured()->first();

        if (!$feedbackLink) {
            return self::SUCCESS;
        }

        try {
            app(FormDefinitionService::class)->refresh($feedbackLink);
        } catch (MsFormsException) {
            $this->warn('Unable to refresh the MS Forms definition; the existing cache is kept.');

            return self::FAILURE;
        }

        return self::SUCCESS;
    }
}