<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Notifications\MindSpace;
use App\Models\reserved;
use App\Models\res;
use App\Models\book;
use App\Models\User;

class send extends Command
{

    protected $signature = 'user:send';

    protected $description = 'telling user when a book they want is available';


    public function __construct()
    {
        parent::__construct();
    }


    public function handle()
    {
        $reserveds = reserved::get();
        foreach($reserveds as $res)
        {
            $book = book::where('id', $res->book_id)->first();
            $data = [
                'body'=>' New notification',
                'dataText'=>"the book $book->name is avaialbe",
                'url'=>url('/'),
                'thankyou'=>'Get it now!'
            ];

            if($book->state == 1)
            {
                 $user = User::where('id', $res->user_id)->first();

                try{
                    $mail = $user->notify(new MindSpace($data));
                }catch(\Throwable $e){
                
                }

                res::create([
                    'book_id'=>$res->book_id,
                    'user_id'=>$res->user_id,
                    'number'=>$res->number
                ]);

                reserved::where('id', $res->id)->delete();
            }
        }

        return Command::SUCCESS;
    }
}
