<?php
   
    require_once(__DIR__ . '/vendor/autoload.php');
    require_once(__DIR__ . '/functions.php');


    $milestoneBoardId = '5d0f878d0076f44264b78f0f';
    $taskBoardId      = '5d16460669cfb305c8a47308';

    use Trello\Client;
    use Trello\Manager;

    $client = new Client();
    $client->authenticate( $_ENV["TRELLO_KEY"] , $_ENV["TRELLO_TOKEN"] , Client::AUTH_URL_CLIENT_ID);
    $manager = new Manager($client);

    $input = file_get_contents("php://input");

    set_error_handler(function($severity, $message, $file, $line) 
    {
        throw new \ErrorException($message, 0, $severity, $file, $line);
    });

    set_exception_handler(function($e) 
    {
        header("HTTP/1.1 500 Internal Server Error");
        echo "Error on line {$e->getLine()}: " . htmlSpecialChars($e->getMessage());
        die();
    });

    $workflow = array(
        "STIPS" => "5d3c6d84a6924d64d9659c58",
        "DISCLOSURE" => "5d3c6d8594ad06858fde827e",
        "UNDERWRITING" => "5d3c6d85b9f0638ec0a1a6b8",
        "QC" => "5d3c6d853a8c0c451a0a2244",
        "PTF" => "5d3c6d8549af6a03ee953a2f",
    );    

    $data = json_decode($input , true );

    if( $data->action->type == "updateCheckItemStateOnCard" ){
            
        $checklistId   = $data->action->data->checklist->id;
        $checklistName = $data->action->data->checklist->name;
        $cardId        = $data->action->data->card->id;

        // $results = TrelloBoardCheckListsStatus($checklistId);        
        $task_card = $manager->getCard($cardId);

        list( $id , $query ) = explode( "-" , $task_card->getName() ) ;

        $card = TrelloFindCards( $milestoneBoardId , $query );  

        // check if card found
        if( count($card) == 1 ){
                
            $status = TrelloBoardCheckListsStatus($checklistId);

            if(isset($workflow[$checklistName])){
                
                $listId = $status?next($workflow):current($workflow);
                
                if( key($workflow) == "PTF" && $status == FALSE ){
                    TrelloMoveCards( $card[0]->id , $listId );
                }else{
                    TrelloMoveCards( $card[0]->id , $listId );
                }

            }

        }

    }

 

?>
