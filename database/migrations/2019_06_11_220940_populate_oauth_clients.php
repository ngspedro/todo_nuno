<?php

use Illuminate\Database\Migrations\Migration;

class PopulateOauthClients extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        /* This is just so you don't have to run passport:install
           and then set the generated secret in the env file.
           This way all you have to do is run the migrations and the secret 
           will already be set up */
        $data = array(
            array(  'name' => 'Laravel Password Grant Client', 
                    'secret' => config('services.passport.client_secret'),
                    'redirect' => 'http://localhost',
                    'personal_access_client' => '0',
                    'password_client' => '1',
                    'revoked' =>'0'),
        );
        
        DB::table('oauth_clients')->insert($data);
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
