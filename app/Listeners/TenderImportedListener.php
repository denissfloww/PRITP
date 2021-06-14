<?php

namespace App\Listeners;

use App\Events\TenderImported;
use App\Mail\TestEmail;
use App\Models\Tender;
use App\Models\TenderObject;
use App\Models\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail as Mail;

class TenderImportedListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  TenderImported  $event
     * @return void
     */
    public function handle(TenderImported $event)
    {
        $tendersOkvad = [];
        $tenders = $event->tenders;
        /** @var Tender $tender */
        foreach ($tenders as $tender) {
            /** @var TenderObject $object */
            foreach ($tender->objects as $object) {
                if (!array_key_exists($object->okvad2_classifier, $tendersOkvad)) {
                    $tendersOkvad[$object->okvad2_classifier] = [];
                }
                if (!in_array($tender, $tendersOkvad[$object->okvad2_classifier])) {
                    $tendersOkvad[$object->okvad2_classifier][] = $tender;
                }
            }
        }

        foreach ($tendersOkvad as $okvad2 => $tenders) {
            $users = User::whereHas('mailingTenders', function ($query) use ($tendersOkvad, $okvad2) {
                return $query->where('okvad2_classifier', $okvad2);
            })->get();

            foreach ($users as $user) {
//                Mail::to($user->email)->send(new TestEmail($tenders));
                Mail::to('denbugackoff21@gmail.com')->send(new TestEmail($tenders, $okvad2));
            }
        }
    }
}
