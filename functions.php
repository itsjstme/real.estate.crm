<?php


function TrelloBoardGetListsId( $name , $boardId ){

    $results = TrelloBoardLists($boardId);
    foreach($results  as $item){
        if($item->name == $name){
            return $item->id;
        }
    }

    return null;
}

function TrelloBoardLists( $boardId ){

    global $_ENV;

    $key =  $_ENV["TRELLO_KEY"] ;
    $token =  $_ENV["TRELLO_TOKEN"];   
    $endpoint = $_ENV["CRM_API"];

    $Parameters = array(
        'cards' => 'none',
        'card_fields' => 'all',
        'filter' => 'open' ,
        'token' => 'fields',
        'key' => $key ,
        'token' => $token          
    );

    $uri = "{$endpoint}/1/boards/{$boardId}/lists?" . http_build_query($Parameters, '', '&'  ) ;
    $response = \Httpful\Request::get($uri)->send();
    return $response->body;

}

function TrelloGetCustomFields ( $boardId  ){

    global $_ENV;

    $key =  $_ENV["TRELLO_KEY"] ;
    $token =  $_ENV["TRELLO_TOKEN"];   
    $endpoint = $_ENV["CRM_API"];   

    $Parameters = array(
        'key' => $key ,
        'token' => $token          
    );

    $uri = "{$endpoint}/1/boards/{$boardId}/customFields/?" . http_build_query($Parameters, '', '&'  ) ;
    $response = \Httpful\Request::get($uri)->send();
    return $response->body;

}

function TrelloUpdateCustomFieldsValue ( $cardId , $fieldObject , $value  ){

    global $_ENV;

    $key =  $_ENV["TRELLO_KEY"] ;
    $token =  $_ENV["TRELLO_TOKEN"];   
    $endpoint = $_ENV["CRM_API"];

    $data = json_encode( array( "value" => array( $fieldObject->type => $value ) , 'key' => $key , 'token' => $token    ) );

    $uri = "{$endpoint}/1/card/{$cardId}/customField/{$fieldObject->id}/item"  ;
    $response = \Httpful\Request::put($uri)->sendsJson()->body($data)->send();
    return $response->body;

}


function TrelloGetAddCustomFields ( $boardId  , $Parameters  ){

    global $_ENV;

    $Parameters['key'] =  $_ENV["TRELLO_KEY"] ;
    $Parameters['token'] = $_ENV["TRELLO_TOKEN"];
    $endpoint = $_ENV["CRM_API"];

    $uri = "{$endpoint}/1/customFields/{$fieldId}?" . http_build_query($Parameters, '', '&'  ) ;
    $response = \Httpful\Request::post($uri)->send();
    return $response->body;

}

function TrelloGetDeleteCustomFields ( $fieldId  ){

    global $_ENV;

    $key =  $_ENV["TRELLO_KEY"] ;
    $token =  $_ENV["TRELLO_TOKEN"];   
    $endpoint = $_ENV["CRM_API"];

    $Parameters = array(
        'key' => $key ,
        'token' => $token          
    );

    $uri = "{$endpoint}/1/customFields/{$fieldId}?" . http_build_query($Parameters, '', '&'  ) ;
    $response = \Httpful\Request::delete($uri)->send();
    return $response->body;

}

function TrelloCreateLists( $boardId , $name  ){

    global $_ENV;

    $key =  $_ENV["TRELLO_KEY"] ;
    $token =  $_ENV["TRELLO_TOKEN"];   
    $endpoint = $_ENV["CRM_API"];

    $Parameters = array(
        'name' => $name ,
        'idBoard' => $boardId,
        'pos' => 'bottom',
        'key' => $key ,
        'token' => $token          
    );

    $uri = "{$endpoint}/1/lists/?" . http_build_query($Parameters, '', '&'  ) ;
    $response = \Httpful\Request::post($uri)->send();
    return $response->body->cards;

}

function TrelloCreateCard( $parameters  , $cardId=0  ){

    global $card;

    if($cardId == 0){
        $rs              = $card->create($parameters['card']);             
    }else{
        unset($parameters['card']['name']);
        $rs           = $card->update( $cardId , $parameters['card']);
    }    

    return $rs;
}


function TrelloGetCardsInList( $listId ){

    global $_ENV;

    $key =  $_ENV["TRELLO_KEY"] ;
    $token =  $_ENV["TRELLO_TOKEN"];   
    $endpoint = $_ENV["CRM_API"];

    $Parameters = array(
        'key' => $key ,
        'token' => $token          
    );

    $uri = "{$endpoint}/1/lists/{$listId}/cards?" . http_build_query($Parameters, '', '&'  ) ;
    $response = \Httpful\Request::get($uri)->send();
    return $response->body;

}

function TrelloFindCards($boardId , $query){

    global $_ENV;

    $key =  $_ENV["TRELLO_KEY"] ;
    $token =  $_ENV["TRELLO_TOKEN"];   
    $endpoint = $_ENV["CRM_API"];

    $Parameters = array(
        'query' => '"'.$query.'"' ,
        'idBoards' => $boardId , 
        'modelTypes' => 'cards',
        'card_fields' => 'id,name',
        'key' => $key ,
        'token' => $token 
    );

    $uri = "{$endpoint}/1/search/?" . http_build_query($Parameters, '', '&'  ) ;
    
    $response = \Httpful\Request::get($uri)->send();
    return $response->body->cards;

}


function TrelloChecklists($cardId){

    global $_ENV;

    $key =  $_ENV["TRELLO_KEY"] ;
    $token =  $_ENV["TRELLO_TOKEN"];   
    $endpoint = $_ENV["CRM_API"];

    $Parameters = array(
        "checkItems" => "all",
        "checkItem_fields" => "name,nameData,pos,state",
        "filter" => "all",
        "fields" => "all",
        'key' => $key ,
        'token' => $token 
    );

    $uri = "{$endpoint}/1/cards/{$cardId}/checklists/?" . utf8_encode(http_build_query($Parameters, '', '&'))  ;
    $response = \Httpful\Request::get($uri)->send();

    return $response->body;

}

function TrelloMoveCards($cardId , $listId){

    global $_ENV;

    $key =  $_ENV["TRELLO_KEY"] ;
    $token =  $_ENV["TRELLO_TOKEN"];   
    $endpoint = $_ENV["CRM_API"];

    $Parameters = array(
        'idList' => $listId ,
        'key' => $key ,
        'token' => $token 
    );

    $data = json_encode( array(  'idList' => $listId  ) );

    echo $uri = "{$endpoint}/1/card/{$cardId}/?" . utf8_encode(http_build_query($Parameters, '', '&'))  ;
    $response = \Httpful\Request::put($uri)->send();

    return $response;

}

function TrelloArchiveCards($boardId , $query){

    global $_ENV;

    $key =  $_ENV["TRELLO_KEY"] ;
    $token =  $_ENV["TRELLO_TOKEN"];   
    $endpoint = $_ENV["CRM_API"];

    $Parameters = array(
        'query' => '"'.$query.'"' ,
        'idBoards' => $boardId , 
        'modelTypes' => 'cards',
        'card_fields' => 'id,name',
        'key' => $key ,
        'token' => $token 
    );

    $uri = "{$endpoint}/1/search/?" . utf8_encode(http_build_query($Parameters, '', '&')) ;
    $response = \Httpful\Request::get($uri)->send();

    return $response->body->cards;

}


function CRMValidContact($record){

    return (count($record['Email']) > 0 &&  count($record['Phone']) > 0 && !empty($record['FirstName']) && !empty($record['LastName']));  
}

function CRMSearch($keyword="" , $numRows=500 , $page=1){

    global $_ENV;

    $UserCode =  $_ENV["CRM_USERCODE"] ;
    $APIToken =  $_ENV["CRM_TOKEN"];
    $EndpointURL =  $_ENV["CRM_ENDPOINT_URL"];

    $Function = "SearchContacts";
    $Parameters = array(
        "SearchTerms"=> $keyword ,
        "NumRows"=> $numRows , //Optional (defaults to 25): Max number of rows returned (must be 1-500)
        "Page"=> $page , //Optional (defaults to 1): use this to retrieve more rows if limited by NumRows
        "Sort"=>"DateEntered" //Optional. Can be FirstName, LastName, DateEntered, DateEdited, or Relevance
    );

    $results = CRMCallAPI($EndpointURL, $UserCode, $APIToken, $Function, $Parameters);
    return $results;
}


function CRMCallAPI($EndpointURL, $UserCode, $APIToken, $Function, $Parameters){
    
    $PostData = array(
        'UserCode' => $UserCode,
        'APIToken' => $APIToken,
        'Function' => $Function,
        'Parameters' => json_encode($Parameters),
    );

    $Options = array(
        'http' =>
            array(
                'method'  => 'POST', //We are using the POST HTTP method.
                'header'  => 'Content-type: application/x-www-form-urlencoded',
                'content' => http_build_query($PostData) // URL-encoded query string.
            )
    );


    $StreamContext  = stream_context_create($Options);
    $APIResult = file_get_contents("$EndpointURL?UserCode=$UserCode", false, $StreamContext);
    $APIResult = json_decode($APIResult, true);
    
    if(@$APIResult['Success'] != true){
        echo "API call failed. Error:".@$APIResult['Error'];
        exit;
    }
    return $APIResult;
}


function CRMContactExport(){

    $arr_data = array();

    for ($pagination = 1; $pagination <= 20 ; $pagination++) {
        echo "Grab 500 records from page {$pagination} ";
        $results = CRMSearch( "" , 500 , $pagination );
        
        if(count($results["Result"]) > 0 ){
            
            foreach($results["Result"] as $record){

                if( CRMValidContact($record) ){
                                        
                    if(!isset($insert[$record["ContactId"]])){
                        array_push($arr_data,$record);
                        $insert[$record["ContactId"]] = 1;
                    }
                }

            } //     
        }   
    }

    $jsondata = json_encode($arr_data, JSON_PRETTY_PRINT);

    //write json data into data.json file
    if(file_put_contents('data.json', $jsondata)) {
        echo "File imported";
    }


}


function TrelloBoardAddCheckLists( $idCard , $name , $arrayOfItem ){

    global $_ENV;

    $key =  $_ENV["TRELLO_KEY"] ;
    $token =  $_ENV["TRELLO_TOKEN"];   
    $endpoint = $_ENV["CRM_API"];

    $Parameters = array(
        'idCard' => $idCard ,
        'name' => $name ,
        'key' => $key ,
        'token' => $token          
    );

    $uri = "{$endpoint}/1/checklists?" . http_build_query($Parameters, '', '&'  ) ;
    $response = \Httpful\Request::post($uri)->send();
   

    $checklistId = $response->body->id;

    foreach($arrayOfItem as $name){

        $Parameters = array(
            'name' => $name ,
            'key' => $key ,
            'token' => $token          
        );

        $uri = "{$endpoint}/1/checklists/{$checklistId}/checkItems?" . http_build_query($Parameters, '', '&'  ) ;
        $response = \Httpful\Request::post($uri)->send();           

    }

}


function TrelloBoardCheckListsStatus( $checklistId ){

    global $_ENV;

    $key =  $_ENV["TRELLO_KEY"] ;
    $token =  $_ENV["TRELLO_TOKEN"];   
    $endpoint = $_ENV["CRM_API"];
    $status = FALSE;


    $Parameters = array(
        'fields' => 'all' ,
        'checkItem_fields' => 'all' ,
        'checkItems' => 'all',
        'cards' => 'none',
        'key' => $key ,
        'token' => $token          
    );

    $uri = "{$endpoint}/1/checklists/{$checklistId}?" . http_build_query($Parameters, '', '&'  ) ;
    $response = \Httpful\Request::get($uri)->send();

    $counter = 0;
    foreach($response->body->checkItems as $item){
            if($item->state == "complete"){
                $counter++;
            }
    }
    
    $status = ( count($response->body->checkItems) == $counter );
    return $status;

}


function TrelloBoardGetCheckLists( $cardId  ){

    global $_ENV;

    $key =  $_ENV["TRELLO_KEY"] ;
    $token =  $_ENV["TRELLO_TOKEN"];   
    $endpoint = $_ENV["CRM_API"];

    $Parameters = array(
        'checkItems' => 'all',
        'checkItem_fields' => 'all',
        'filter' => 'all',
        'fields' => 'all' ,
        'key' => $key ,
        'token' => $token          
    );

    $uri = "{$endpoint}/1/cards/{$cardId}/checklists?" . http_build_query($Parameters, '', '&'  ) ;
    $response = \Httpful\Request::get($uri)->send();
    return $response->body;

}


function substr_in_array($needle, array $haystack)
{
    $filtered = array_filter($haystack, function ($item) use ($needle) {
        return false !== strpos($item, $needle);
    });
 
    return !empty($filtered);
}


