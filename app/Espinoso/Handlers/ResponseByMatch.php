<?php
namespace App\Espinoso\Handlers ; 

use App\Espinoso\Helpers\Msg; 
use Telegram\Bot\Laravel\Facades\Telegram;

class ResponseByMatch extends EspinosoHandler
{
    public function shouldHandle($updates, $context=null) 
    {
        if ( ! $this->isTextMessage($updates) ) return false ; 

        foreach ($this->mappings() as $needle => $response)
            if ( preg_match($needle, $updates->message->text) )
                return true ; 
        return false ; 
    }

    public function handle($updates, $context=null)
    {
        foreach ($this->mappings() as $pattern => $response)
        {
            if ( preg_match($pattern, $updates->message->text) ) 
            {
                $msg = $this->buildMessage($response, $pattern, $updates);
                Telegram::sendMessage($msg);
            }
        }
    }

    public function buildMessage($response, $pattern, $updates)
    {
        if ($response instanceof Msg)
            return $response->build($pattern, $updates);
        else 
            return Msg::plain($response)->build($pattern, $updates);
    }
 
    private function mappings()
    {
        return config('espinoso_data.ResponseByMatch.mappings');
    }
}


