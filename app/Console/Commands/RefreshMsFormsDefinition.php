<?php

namespace App\Console\Commands;

use App\Models\FeedbackLink;
use App\Models\ReservationLink;
use App\Services\MsForms\FormDefinitionService;
use App\Services\MsForms\MsFormsException;
use Illuminate\Console\Command;

final class RefreshMsFormsDefinition extends Command
{
    protected $signature = 'msforms:refresh-definition';

    protected $description = 'Warm the cached MS Forms definitions so user requests never hit a cold fetch';

    public function handle(): int
    {
        $configured = [
            'feedback' => FeedbackLink::configured()->first()?->link,
            'reservation' => ReservationLink::configured()->first()?->link,
        ];

        $failed = false;

        foreach ($configured as $name => $link) {
            if (! $link) {
                continue;
            }

            try {
                app(FormDefinitionService::class)->refresh($link);
            } catch (MsFormsException) {
                $this->warn("Unable to refresh the {$name} MS Forms definition; the existing cache is kept.");
                $failed = true;
            }
        }

        return $failed ? self::FAILURE : self::SUCCESS;
    }
}
