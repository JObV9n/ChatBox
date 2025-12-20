<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use function Laravel\Prompts\textarea;
use EchoLabs\Prism\Facades\Prism;
use EchoLabs\Prism\Enums\Provider;

class Chat extends Command
{
    protected $signature = 'chat';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Chat With OpenAI:';

    /**
     * Execute the console command.
     */
    public function handle() :void
    {
        $prompt = textarea('Message');

        try {
            $prismOutput = $this->generatedText()->withPrompt($prompt)->generate()->text;

        }catch (\Exception $exception){
            dd($exception->getMessage());
        }

//        echo $prismOutput . PHP_EOL;
        // dd($prismOutput);
    }


//    returns a generated Text from the OPENAI
    protected function generatedText(){

        $response = Prism::text()
            ->using('openai', 'gpt-4o');

        return $response;
    }
}
