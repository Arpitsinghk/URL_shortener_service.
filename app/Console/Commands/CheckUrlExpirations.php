<?php

namespace App\Console\Commands;

use App\Models\ShortUrl;
use App\Mail\UrlExpiredNotification;
use App\Notifications\UrlExpirationReminder;
use Illuminate\Support\Facades\Mail;

use Illuminate\Console\Command;

class CheckUrlExpirations extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    // protected $signature = 'app:check-url-expirations';
    protected $signature = 'check:urlexpirations';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send notifications and emails for expired or soon-to-expire URLs';

    /**
     * Execute the console command.
     */
 public function handle()
    {
        $now = now();

        // Send email if expiring within 1 day
        $expiringSoon = ShortUrl::where('expires_at', '>', $now)
                                ->where('expires_at', '<', $now->copy()->addDay())
                                ->where('notified', 0) 
                                ->get();

        foreach ($expiringSoon as $url) {
            $url->user->notify(new UrlExpirationReminder());
            $url->notified = true;
            $url->save();
        }

        // Send expired emails
        $expired = ShortUrl::where('expires_at', '<', $now)
                           ->where('is_expired', 0)
                           ->get();

        foreach ($expired as $url) {
            Mail::to($url->user->email)->send(new UrlExpiredNotification($url));
            $url->is_expired = true;
            $url->save();
        }

        $this->info('URL expiration check completed.');
    }

}
