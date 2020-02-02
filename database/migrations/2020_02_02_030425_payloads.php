<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Address;
use App\Models\Client;
use App\Models\Debt;

class Payloads extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $names = ['Daniel', 'Ana', 'Leticia', 'Tiago', 'Eva', 'Diego', 'Matheus', 'Marcio', 'Marcos', 'Maria', 'José'];
        $sobrenomes = ['Santos', 'Silva', 'Oliveira', 'Souza', 'Ribeiro', 'Trevor'];
        $dbts = ["Esquina do seu Zé", "Armazem do ouro",  "Supermecado Bocão", "Loja do Buteco"];

        for($i = 0; $i < 1000; $i ++){
            $name = $names[array_rand($names)];
            $sobrenome = $sobrenomes[array_rand($sobrenomes)];

            $client = new Client();
            $client->name = "$name $sobrenome";
            $client->dctRegistry = sprintf('%011d', $i);

            $client->save();

            $address = new Address();
            $address->client_id = $client->id;
            $address->cod_ibge = '5208707';
            $address->dctZipCode = '74840060';
            $address->dctStreetAddress = 'Avenida Arumã';
            $address->dctComplement = " ";
            $address->dctNeighborhood = 'Parque Amazônia';
            $address->save();

            for($x = 0; $x <= rand ( 0 , 5 ); $x++){
                $debt = new Debt();
                $debt->client_id = $client->id;
                $debt->description = "Deve ao estabelecimento: ". $dbts[array_rand($dbts)]; 
                $date = new \DateTime("2019-". rand(1,12). '-'. rand(1,28) );
                $debt->startDate = $date;
                $debt->initialAmount = rand(1,1000);
                
                if(rand(1,2) % 2 === 0){
                    $date = clone $date;
                    $date->modify("+".rand(1, 120). " month" );
                    $debt->datePayment = $date;
                    $debt->paymentAmount = $debt->initialAmount + rand(0, 1200);
                    $debt->isActive = false;
                }
                else{
                    $debt->isActive = true;
                }

                $debt->save();
            }
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
